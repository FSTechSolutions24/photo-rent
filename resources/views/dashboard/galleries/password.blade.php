
<div class="max-w-md mx-auto mt-24 bg-white p-6 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-semibold text-center mb-4">
        Access Gallery: {{ $gallery->title }}
    </h2>

    @if ($errors->any())
        <div class="text-red-600 text-center mb-2">
            {{ $errors->first('password') }}
        </div>
    @endif

    <form method="POST" action="{{ route('gallery.show', [$photographer->subdomain, $gallery->slug]) }}">
        @csrf
        <input type="password" name="password" placeholder="Enter gallery password"
               class="w-full border rounded-lg px-4 py-2 mb-4 focus:ring focus:ring-blue-300" required>
        <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
            View Gallery
        </button>
    </form>
</div>
