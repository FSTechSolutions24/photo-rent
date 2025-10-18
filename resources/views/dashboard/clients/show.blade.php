<x-app-layout>
    <h1 class="text-xl font-bold">{{ $client->name }}</h1>

    <a href="{{ route('dashboard.galleries.create', $client) }}" class="btn btn-primary my-3">
        + Add Gallery
    </a>

    <ul>
        @foreach($galleries as $gallery)
            <li class="mb-2">
                <img src="{{ Storage::disk('local')->url($gallery->thumbnail_path) }}" width="80">
                <a href="{{ url(Auth::user()->subdomain . '.example.com/' . $client->name . '/' . $gallery->slug) }}"
                   target="_blank" class="text-blue-600 font-semibold">
                    {{ $gallery->name }}
                </a>
            </li>
        @endforeach
    </ul>
</x-app-layout>
