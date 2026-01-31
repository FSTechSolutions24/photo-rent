<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\PaymentLog;
use App\Models\Transaction;
use App\Traits\HelperTrait;
use App\Models\Photographer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PhotographerSubscription;
use Illuminate\Support\Facades\Validator;


class ProfileController extends Controller
{
    use HelperTrait;

    public $config_values; 

    public function __construct()
    {
        $this->config_values = config('paymob');
    }


    public function index()
    {
        
    }

    public function profile_update(Request $request)
    {
        $user = auth()->user();

        // ✅ Validation
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // ✅ Update basic fields
        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        // ✅ Update password ONLY if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function create()
    {
        $user = auth()->user();

        // Check if user has photographer relation
        if ($user->photographer) {
            return redirect()->route('dashboard'); // or 'dashboard.index' depending on your route name
        }

        $plans = SubscriptionPlan::with('lines')->get();

        return view('dashboard.profile.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|min:3',
            'phone' => ['required','regex:/^(?:\+20|0)?1[0125][0-9]{8}$/'],
            'phone2' => ['nullable','regex:/^(?:\+20|0)?1[0125][0-9]{8}$/'],            
            'email' => 'nullable|email',
        ]);

        $validated['photographer_id'] = Auth::id();

        Client::create($validated);

        return redirect()->route('dashboard.clients.index')->with('success', 'Client added successfully.');
    }

    public function show(Client $client)
    {
        $this->authorizeClient($client);
        $galleries = $client->galleries;
        return view('dashboard.clients.show', compact('client', 'galleries'));
    }

    private function authorizeClient(Client $client)
    {
        
    }

    public function checksubdomain(Request $request)
    {
        $subdomain = $request->query('subdomain');
        
        if (!$subdomain) {
            return response()->json([
                'subdomain_empty' => true,
                'message' => 'Please fill your subdomain.'
            ]);
        }

        // Reject if contains slashes or spaces
        if (preg_match('/[^A-Za-z0-9-]/', $subdomain)) {
            return response()->json([
                'invalid_format' => true,
                'message' => 'Invalid subdomain format. Only letters, numbers, and hyphens are allowed. No spaces, slashes, or special characters.'
            ]);
        }

        // Check if it starts or ends with a hyphen
        if (str_starts_with($subdomain, '-') || str_ends_with($subdomain, '-')) {
            return response()->json([
                'invalid_format' => true,
                'message' => 'Subdomain cannot start or end with a hyphen.'
            ]);
        }

        // Check if subdomain already exists
        $exists = Photographer::where('subdomain', $subdomain)->exists();
        if ($exists) {
            return response()->json([
                'available' => false,
                'message' => 'Taken, please try another.'
            ]);
        } else {
            return response()->json([
                'available' => true,
                'message' => 'Available!'
            ]);
        }
    }

    public function createphotographerprofile(Request $request)
    {

        $subdomain = $request->query('subdomain');
        $selectedPlan = $request->query('selectedPlan');

        $plan = SubscriptionPlan::where('id', $selectedPlan)->firstOrFail();

        Photographer::create([
            'user_id' => Auth::user()->id,
            'plan_storage' => ($plan->storage_gb * 1024 * 1024 * 1024),
            'subdomain' => $subdomain,
            'active' => 0,
        ]);

        $plan = SubscriptionPlan::where('id', $selectedPlan)->firstOrFail();

        $url = $this->paymob_create_order($plan);

        return response()->json([
            'success' => true,
            'price' => $plan->price,
            'url' => $url,
        ]);

    }
    
    public function paymob_create_order($plan){
        $token = $this->getToken();
        $paymob = $this->createOrder($token, $plan);
        $paymentToken = $this->getPaymentToken($paymob, $token, $plan);        
        return 'https://accept.paymobsolutions.com/api/acceptance/iframes/' . $this->config_values['iframe_id'] . '?payment_token=' . $paymentToken;
    }

    function add_payment_log($paymob_order_id, $type){
        PaymentLog::create([
            'user_id' => Auth::user()->id,
            'payment_getway' => 'paymob',
            'order_id' => $paymob_order_id,
            'status' => 'sent',
            'type' => $type,
        ]);
    }

    public function getPaymentToken($order, $token, $plan)
    {

        $billingData = [
            "apartment" => "N/A",
            "email" => 'test@gmail.com',
            "floor" => "N/A",
            "first_name" => 'beshoy',
            "street" => "N/A",
            "building" => "N/A",
            "phone_number" => "N/A",
            "shipping_method" => "PKG",
            "postal_code" => "N/A",
            "city" => "N/A",
            "country" => "N/A",
            "last_name" => 'ecladuos',
            "state" => "N/A",
        ];

        $data = [
            "auth_token" => $token,
            'amount_cents' => round($plan->price * 100),
            "expiration" => 3600,
            "order_id" => $order->id,
            "billing_data" => $billingData,
            "currency" => 'EGP',
            "integration_id" => $this->config_values['integration_id']
        ];

        $response = $this->cURL(
            'https://accept.paymob.com/api/acceptance/payment_keys',
            $data
        );

        return $response->token;
    }

    public function createOrder($token, $plan)
    {
        
        $total = $plan->price;
        $items[] = [
            'name' => 'Plan',
            'amount_cents' => round($total * 100),
            'description' => 'payment ID :' . $plan['id'] . ' UserID: ' . Auth::user()->id,
            'quantity' => 1
        ];

        $data = [
            "auth_token" => $token,
            "delivery_needed" => "false",
            "amount_cents" => round($total * 100),
            "currency" => "EGP",
            "items" => $items,
            'order_id' => $plan->id,
            'portal_order_id' => $plan->id,

        ];
        $response = $this->cURL(
            'https://accept.paymob.com/api/ecommerce/orders',
            $data
        );

        $this->add_payment_log($response->id, 'create_order');
        return $response;
    }

    public function getToken()
    {

        $response = $this->cURL(
            'https://accept.paymob.com/api/auth/tokens',
            ['api_key' => $this->config_values['api_key']]
        );

        
        return $response->token;
    }

    protected function cURL($url, $json)
    {
        $ch = curl_init($url);

        $headers = array();
        $headers[] = 'Content-Type: application/json';

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($ch);

        // Check if there was a cURL error
        if (curl_errno($ch)) {
            dd('cURL error: ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($output);
    }

    public function get_session_count($photographer){
        return (count($photographer->sessions));
    }

    public function get_gallery_count($photographer){
        return (count($photographer->galleries));
    }

    public function get_client_count($photographer){
        return (count($photographer->clients));
    }

    public function prepare_user_data($user){
        $photographer = $user->photographer;
        $data['available_storage'] = $this->convert_storage($photographer->available_storage);
        $data['plan_storage'] = $this->convert_storage($photographer->plan_storage);
        $data['session_count'] = $this->get_session_count($photographer);
        $data['gallery_count'] = $this->get_gallery_count($photographer);
        $data['client_count'] = $this->get_client_count($photographer);
        return $data;
    }

    public function profile_settings(){
        $user = Auth::user();
        $data = $this->prepare_user_data($user);
        return view('dashboard.profile.settings', compact('user','data'));
    }

    public function paymobcallbackresponseview(Request $request){
        $data = $request;
        return view('dashboard.profile.profile_completed', compact('data'));
    }

    public function callback(Request $request)
    {

        $data = $request->all();

        ksort($data);
        $hmac = $data['hmac'];

        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];

        $secret = $this->config_values['hmac'];

        $connectedString = '';
        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }

        $hased = hash_hmac('sha512', $connectedString, $secret);
        
        $order_id = $data['obj']['order']['id'];

        $order = PaymentLog::where('order_id',$order_id)->firstOrFail();

        if ($hased == $hmac &&  $data['obj']['success'] == "true") {    
            Transaction::create([
                'user_id' => $order['user_id'],
                'order_id' => $order['order_id'],
                'transaction_id' => $data['obj']['id'],
                'payment_method' => 'paymob',
                'created_at' => date('Y-m-d H:i:s'),
                'status' => $data['obj']['order']['status'],
            ]);                
            
            $this->after_payment_success($order);
            return redirect()->route('checkout.complete.show');
        }      

    }

    public function after_payment_success(array $order)
    {
        DB::transaction(function () use ($order) {

            $photographer = Photographer::where('user_id', $order['user_id'])->firstOrFail();
            $plan = SubscriptionPlan::findOrFail($order['plan_id']);

            // Activate photographer
            $photographer->update([
                'active' => 1,
            ]);

            $start_date = Carbon::now();
            $end_date   = $start_date->copy();

            if ($plan->billing_cycle === 'month') {
                $end_date->addMonthNoOverflow();
            } elseif ($plan->billing_cycle === 'year') {
                $end_date->addYearNoOverflow();
            }

            PhotographerSubscription::create([
                'user_id' => $order['user_id'],
                'photographer_id' => $photographer->id,
                'plan_id' => $plan->id,
                'start_date' => $start_date->toDateTimeString(),
                'end_date' => $end_date->toDateTimeString(),
            ]);

        });
    }


}
