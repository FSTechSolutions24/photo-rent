<x-app-layout>
    <h2 class="text-xl font-semibold mb-3">Create Gallery for {{ $client->name }}</h2>

    <form method="POST" action="{{ route('dashboard.clients.galleries.store', $client) }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Gallery Name:</label>
            <input type="text" name="name" class="input" required>
        </div>

        <div>
            <label>Password:</label>
            <input type="password" name="password" class="input" required>
        </div>

        <div>
            <label>Thumbnail:</label>
            <input type="file" name="thumbnail" class="input">
        </div>

        <button class="btn btn-primary mt-3">Create Gallery</button>
    </form>
</x-app-layout>
