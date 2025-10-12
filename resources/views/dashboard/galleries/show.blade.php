
    <h1 class="text-xl font-bold">{{ $client->name }} / {{ $gallery->name }}</h1>

    <a href="{{ route('dashboard.clients.galleries.create', $client) }}" class="btn btn-primary my-3">
        + Add Gallery
    </a>

    <ul>
        @foreach($gallery->folders as $folder)
            <li class="mb-2">

                <i class="fas fa-folder"></i>
                <label>{{ $folder->name }}</label>
                {{-- <img src="{{ Storage::disk('local')->url($folder->thumbnail_path) }}" width="80"> --}}
                {{-- <a href="{{ url(Auth::user()->subdomain . '.example.com/' . $client->name . '/' . $folder->slug) }}"
                   target="_blank" class="text-blue-600 font-semibold">
                    {{ $folder->name }}
                </a> --}}
            </li>
        @endforeach
    </ul>
