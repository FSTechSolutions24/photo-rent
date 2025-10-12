{{-- @include('layouts.app') --}}

<div>
    <h1 class="text-2xl font-bold mb-4">My Clients</h1>


    <a href="{{ route('dashboard.clients.create') }}" class="btn btn-primary mb-3">+ Add Client</a>

    <ul>
        @foreach($clients as $client)
            <li class="mb-2">
                <a href="{{ route('dashboard.clients.show', $client) }}" class="text-blue-600">
                    {{ $client->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
