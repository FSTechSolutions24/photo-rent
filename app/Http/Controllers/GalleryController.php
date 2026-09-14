<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\GalleryDownload;
use App\Models\GalleriesToBeEmailed;
use Illuminate\Support\Str;
use App\Traits\HelperTrait;
use App\Models\Photographer;
use App\Models\WhatsAppTemplate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    use HelperTrait;
    //
    public function show(Request $request, $photographer_subdomain, $gallery_slug)
    {
        // 1️⃣ Fetch photographer by subdomain
        $photographer = Photographer::where('subdomain', $photographer_subdomain)->firstOrFail();

        // 2️⃣ Find the client under this photographer

        // 3️⃣ Get the gallery with folders and images
        $gallery = $photographer->galleries()
            ->where('slug', $gallery_slug)
            ->firstOrFail();

        // 4️⃣ Password protection logic
        $sessionKey = 'access_granted_' . $gallery->id;

        // Public galleries are available without a password. Private galleries
        // retain the existing client/guest password gate.
        if (! $gallery->is_public && ! $this->isOwningPhotographer($gallery) && ! session($sessionKey)) {

            // Handle password form submission (POST)
            if ($request->isMethod('post')) {

                $request->validate([
                    'password' => 'required|string'
                ]);

                // Check password match (assuming $gallery->password is hashed)
                // if (!Hash::check($request->password, $gallery->password)) {
                //     return back()->withErrors(['password' => 'Incorrect password.'])->withInput();
                // }

                // $password = Crypt::decryptString($gallery->client_password);

                $storedClientPassword = Crypt::decryptString($gallery->client_password);
                $storedGuestPassword = Crypt::decryptString($gallery->guest_password);

                $guest = $client = false;
                if (hash_equals($storedGuestPassword, $request->password)) {
                    $guest = true;
                    session([$this->visitorTypeSessionKey($gallery) => 'guest']);
                }
                if (hash_equals($storedClientPassword, $request->password)) {
                    $client = true;
                    session([$this->visitorTypeSessionKey($gallery) => 'client']);
                }

                if(!$guest && !$client) {
                    return back()
                        ->withErrors(['password' => 'Incorrect password.'])
                        ->withInput();
                }

                // Password correct → grant access
                session([$sessionKey => true]);

            } else {
                // No access yet → show password page
                return view('dashboard.galleries.password', compact('gallery', 'photographer'));
            }
        }

        // 5️⃣ Access granted → show gallery
        $canViewPrivateMedia = $this->canViewPrivateMedia($gallery);
        $gallery->load(['folders.media' => function ($query) use ($canViewPrivateMedia) {
            if (! $canViewPrivateMedia) {
                $query->visibleToGuests();
            }
        }]);

        // Generate URLs only for media this viewer is permitted to see.
        foreach ($gallery->folders as $folder) {
            foreach ($folder->media as $media) {
                $media->path = $this->get_pre_signed_url($media->path, 'medium');
            }
        }

        $layout = $gallery->gallery_layout ?? Gallery::LAYOUT_MASONRY;

        $view = match ($layout) {
            Gallery::LAYOUT_EDITORIAL => 'dashboard.galleries.show-editorial',
            Gallery::LAYOUT_LUXE => 'dashboard.galleries.show-luxe',
            default => 'dashboard.galleries.show',
        };

        return view($view, compact('gallery', 'photographer'));
    }

    public function index(){
        return view('dashboard.galleries.index');
    }

    public function getData(){
    
        $photographer = Auth::user()->photographer;
        $galleries = $photographer
            ? $photographer->galleries()->with(['client', 'session.client'])->get()
            : collect();

        return DataTables::of($galleries)
        ->addColumn('is_public', function ($model) {
            return $model->is_public == '1' ? 'Yes' : 'No';
        })
        ->addColumn('session_name', function ($model) {
            return $model->session ? $model->session->name : '-';
        })
        ->addColumn('actions', function ($model) {

            $editUrl = route('dashboard.galleries.edit', $model->id);
            $downloadUrl = route('dashboard.galleries.download');

            $buffer = '<a href="'.$editUrl.'" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-edit"></i>
            </a>';

            // Gallery sharing always uses the gallery's assigned session.
            $phone = optional($model->session)->phone;

            if ($phone) {
                $whatsAppUrl = route('dashboard.galleries.whatsapp', $model->id);
                $buffer .= '<a href="'.$whatsAppUrl.'" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success" style="margin-left:5px;" title="Send via WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </a>';
            } else {
                $buffer .= '<span class="btn btn-sm btn-outline-secondary disabled" style="margin-left:5px;" title="Assign a client with a phone number to send via WhatsApp">
                    <i class="fab fa-whatsapp"></i>
                </span>';
            }

            $folderUrl = route('dashboard.galleries.folders.index', [
                'gallery' => $model->id,
            ]);

            $buffer .= '<a href="'.$folderUrl.'" class="btn btn-sm btn-outline-success" style="margin-left: 5px;">
                <i class="fas fa-cogs"></i>
            </a>';

            $buffer .= '
            <form id="download-form-'.$model->id.'" action="'.$downloadUrl.'" method="POST" style="display:inline;">
                '.csrf_field().'
                <input type="hidden" name="id" value="'.$model->id.'">
            </form>

            <a href="#" onclick="event.preventDefault(); document.getElementById(\'download-form-'.$model->id.'\').submit();" class="btn btn-sm btn-outline-primary" style="margin-left:5px;">
                <i class="fas fa-download"></i>
            </a>';
            
            return $buffer;
        })
        ->addIndexColumn()
        ->rawColumns(['actions'])
        ->make(true);

    }

    /** Build the saved WhatsApp message for a gallery and open WhatsApp. */
    public function sendViaWhatsApp($id)
    {
        $photographer = Auth::user()->photographer;
        $gallery = $photographer->galleries()->with('session.client')->findOrFail($id);
        $session = $gallery->session;
        $phone = optional($session)->phone;

        abort_unless($phone, 422, 'This gallery needs an assigned session with a phone number.');

        $template = WhatsAppTemplate::firstOrCreate(
            ['photographer_id' => $photographer->id],
            ['message' => "Hello {{client_name}},\n\nYour gallery is ready: {{url}}\n\nClient password: {{client_password}}\nGuest password: {{guest_password}}"]
        );

        $galleryUrl = route('gallery.show', [
            'photographer_subdomain' => $photographer->subdomain,
            'gallery_slug' => $gallery->slug,
        ]);

        $message = strtr($template->message, [
            '{{client_name}}' => optional(optional($session)->client)->name ?? 'Client',
            '{{url}}' => $galleryUrl,
            '{{client_password}}' => Crypt::decryptString($gallery->client_password),
            '{{guest_password}}' => Crypt::decryptString($gallery->guest_password),
        ]);

        return redirect()->away('https://wa.me/'.$this->whatsAppPhone($phone).'?text='.rawurlencode($message));
    }

    private function whatsAppPhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '20' . substr($phone, 1);
        }

        return str_starts_with($phone, '1') ? '20' . $phone : $phone;
    }
    
    public function edit($id){
        $photographer = auth()->user()->photographer;
        $gallery = $photographer->galleries()->findOrFail($id);
        $clients = $photographer->clients;
        $sessions = $photographer->sessions()->orderByDesc('date')->get();
        $gallery->client_password = Crypt::decryptString($gallery->client_password);
        $gallery->guest_password = Crypt::decryptString($gallery->guest_password);
        return view('dashboard.galleries.edit', compact('gallery', 'clients', 'sessions'));   
    }
    
    public function download(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer'],
        ]);
        $gallery = Auth::user()->photographer->galleries()->findOrFail($data['id']);
        $email = Auth::user()->email;
        // Download logic
        $exist_download = $this->search_gallery_file_exist(['id' => $gallery->id]);
        if($exist_download){
            $this->modify_gallery_expiration_date($exist_download);
            $this->send_url_to_email_asked_for_download($exist_download, $email);
        }
        else {
            $download = $this->add_new_gallery_media_download_request([
                'id' => $gallery->id,
                'folder_id' => null,
                'requested_by_email' => $email,
            ]);
            $this->send_url_to_email_asked_for_download($download, $email);
        }        
        
        return view('dashboard.galleries.index');

    }

    /**
     * Queue a download requested from the public gallery page.
     */
    public function requestDownload(Request $request, $photographer_subdomain, $gallery_slug)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'not_regex:/[\r\n]/', 'max:255'],
            'folder_ids' => ['nullable', 'array'],
            'folder_ids.*' => ['integer'],
            'download_all' => ['nullable', 'boolean'],
        ]);

        $photographer = Photographer::where('subdomain', $photographer_subdomain)->firstOrFail();
        $gallery = $photographer->galleries()->where('slug', $gallery_slug)->with('folders')->firstOrFail();

        abort_unless(
            $gallery->is_public
                || $this->isOwningPhotographer($gallery)
                || session('access_granted_' . $gallery->id),
            403
        );

        $availableFolderIds = $gallery->folders->pluck('id')->map(function ($id) {
            return (int) $id;
        });

        $folderIds = $request->boolean('download_all')
            ? $availableFolderIds->all()
            : collect($data['folder_ids'] ?? [])->map(function ($id) {
                return (int) $id;
            })->intersect($availableFolderIds)->values()->all();

        if (empty($folderIds)) {
            return back()->withErrors(['folder_ids' => 'Please select at least one folder to download.'])->withInput();
        }

        $download = GalleryDownload::create([
            'gallery_id' => $gallery->id,
            'user_type' => $this->get_current_user_type($gallery),
            'requested_by_email' => $data['email'],
            'selected_folder_ids' => $folderIds,
            'full_gallery' => count($folderIds) === $availableFolderIds->count(),
            'status' => 'Pending',
        ]);

        GalleriesToBeEmailed::create([
            'gallery_downloads_id' => $download->id,
            'send_to' => $data['email'],
            'status' => 'Pending',
        ]);

        return back()->with('download_requested', 'Your download is being prepared. We will email the link to you shortly.');
    }

    public function search_gallery_file_exist($data){

        // we need to check if the gallery exist and with the same permission of the current request
        // what is the current user permission? guest or client >> based on that we will do the user_type condition

        $current_user_type = $this->get_current_user_type();

        return GalleryDownload::where('user_type', $current_user_type)->where('gallery_id', $data['id'])->first();

    }

    public function get_current_user_type(Gallery $gallery = null){

        if ($gallery && $this->isOwningPhotographer($gallery)) {
            return 'admin';
        }

        if (! $gallery && Auth::check()) {
            return 'admin';
        }

        if ($gallery && session($this->visitorTypeSessionKey($gallery)) === 'client') {
            return 'client';
        }

        return 'guest';
    }

    private function canViewPrivateMedia(Gallery $gallery): bool
    {
        return $this->isOwningPhotographer($gallery)
            || session($this->visitorTypeSessionKey($gallery)) === 'client';
    }

    private function isOwningPhotographer(Gallery $gallery): bool
    {
        return Auth::check()
            && Auth::user()->photographer
            && (int) Auth::user()->photographer->id === (int) $gallery->photographer_id;
    }

    private function visitorTypeSessionKey(Gallery $gallery): string
    {
        return 'visitor_type_' . $gallery->id;
    }

    public function modify_gallery_expiration_date($exist_download){
        if($exist_download->status != 'Completed'){
            return;
        }
        $exist_download->expires_at = Carbon::now()->addDays(3);
        $exist_download->save();
    }

    public function send_url_to_email_asked_for_download($gallery, $email){

        if(Auth::check()){
            $email = Auth::user()->email;
        }

        GalleriesToBeEmailed::create([
            'gallery_downloads_id' => $gallery->id,
            'send_to' => $email,
            'status' => 'Pending',
        ]);

        // we will need a script to be running every 1 minute to run the queue to send the emails
        // the script should get the records from GalleriesToBeEmailed where the status is Pending
        // if the email sent seccessfully then update the GalleriesToBeEmailed with the sent at and status completed
    
    }

    public function add_new_gallery_media_download_request($data){

        $user_type = $this->get_current_user_type();
    
        return GalleryDownload::create([
            'gallery_id' => $data['id'],
            'folder_id' => $data['folder_id'],
            'user_type' => $user_type,
            'requested_by_email' => $data['requested_by_email'],
            'full_gallery' => $data['folder_id'] ? 0 : 1,
            'status' => 'Pending',
        ]);
    }

    protected function validateGallery(Request $request, $gallery = null)
    {
        // Determine the correct photographer_id
        $photographerId = $gallery ? $gallery->photographer_id : Photographer::where('user_id', Auth::id())->first()->id;

        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('galleries')
                    ->where(fn ($query) => $query->where('photographer_id', $photographerId))
                    ->when($gallery, fn($rule) => $rule->ignore($gallery->id)),
            ],
            'client_password' => [
                Rule::requiredIf(! $request->boolean('is_public')),
                'nullable',
                'string',
                'min:6',
                'different:guest_password',
            ],
            'guest_password' => [
                Rule::requiredIf(! $request->boolean('is_public')),
                'nullable',
                'string',
                'min:6',
                'different:client_password',
            ],
            'thumbnail_path' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'background_path' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:15360',
                'dimensions:min_width=1920,min_height=720',
            ],
            'gallery_layout' => ['required', Rule::in([
                Gallery::LAYOUT_MASONRY,
                Gallery::LAYOUT_EDITORIAL,
                Gallery::LAYOUT_LUXE,
            ])],
            'is_public' => ['nullable', 'in:0,1'],
            'session_id' => [
                'nullable',
                'integer',
                Rule::exists('sessions', 'id')->where('photographer_id', $photographerId),
            ],
        ], [
            'background_path.max' => 'The gallery background may not be larger than 15 MB.',
            'background_path.dimensions' => 'The gallery background must be at least 1920 x 720 pixels to remain sharp on large screens.',
        ]);
    }

    public function create()
    {
        $clients = auth()->user()->photographer->clients;
        $sessions = auth()->user()->photographer->sessions()->orderByDesc('date')->get();
        return view('dashboard.galleries.create', compact('clients', 'sessions'));
    }

    public function update(Request $request, Gallery $gallery){
        $this->authorizeGallery($gallery);
        $data = $this->validateGallery($request, $gallery);

        $data = $this->prepare_gallery_data($data);

        $gallery->update($data);
        
        $this->update_gallery_thumbnail($request, $gallery);
        $this->update_gallery_background($request, $gallery);

        return redirect()->route('dashboard.galleries.index')->with('success', 'Client updated successfully.');
    }

    public function store(Request $request)
    {
        $data = $this->validateGallery($request);

        $data['photographer_id'] = Photographer::where('user_id', Auth::id())->first()->id;

        $data = $this->prepare_gallery_data($data);
        
        $gallery = Gallery::create($data);

        $this->update_gallery_thumbnail($request, $gallery);
        $this->update_gallery_background($request, $gallery);

        return redirect()->route('dashboard.galleries.index')->with('success', 'Gallery created successfully.');
    }

    public function update_gallery_thumbnail(Request $request, $gallery){
        if ($request->hasFile('thumbnail_path')) {
            $this->delete_gallery_asset($gallery->thumbnail_path);
            $gallery->thumbnail_path = $this->store_gallery_asset($request->file('thumbnail_path'), $gallery, 'thumbnail');
            $gallery->save();
        }
    }

    public function update_gallery_background(Request $request, $gallery)
    {
        if (! $request->hasFile('background_path')) {
            return;
        }

        $this->delete_gallery_asset($gallery->background_path);
        $gallery->background_path = $this->store_gallery_asset($request->file('background_path'), $gallery, 'background');
        $gallery->save();
    }

    private function store_gallery_asset($file, Gallery $gallery, string $type): string
    {
        $extension = strtolower($file->extension());
        $path = sprintf(
            'users/%s/galleries/%s/assets/%s/%s.%s',
            Auth::id(),
            $gallery->id,
            $type,
            Str::uuid(),
            $extension
        );

        Storage::disk('wasabi')->put($path, file_get_contents($file));

        return $path;
    }

    private function delete_gallery_asset(?string $path): void
    {
        if (! $path) {
            return;
        }

        $disk = str_starts_with($path, 'users/') ? 'wasabi' : 'public';

        if (Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }

    public function prepare_gallery_data(array $data){

        // Uploaded files are stored separately once the gallery has an ID.
        unset($data['thumbnail_path'], $data['background_path']);

        // Prepare other fields
        $data['slug'] = Str::slug($data['name']) ?: Str::random(8);
        // The database columns remain non-nullable. Public galleries may omit
        // passwords, so store encrypted empty strings instead of requiring a
        // schema change. Private galleries have already passed validation.
        $data['client_password'] = Crypt::encryptString((string) ($data['client_password'] ?? ''));
        $data['guest_password'] = Crypt::encryptString((string) ($data['guest_password'] ?? ''));

        return $data;
    }

    private function authorizeClient(Client $client)
    {
        abort_unless((int) $client->photographer_id === (int) Auth::user()->photographer->id, 403);
    }

    private function authorizeGallery(Gallery $gallery): void
    {
        abort_unless((int) $gallery->photographer_id === (int) Auth::user()->photographer->id, 403);
    }
}
