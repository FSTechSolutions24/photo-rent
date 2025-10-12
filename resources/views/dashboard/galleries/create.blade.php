@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <h2 class="text-xl font-semibold mb-3">Create Gallery for {{ $client->name }}</h2>

    <form method="POST" action="{{ route('dashboard.clients.galleries.store', $client) }}" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Gallery Name:</label>
            <input type="text" name="name" class="input form-control" required>
        </div>

        <div>
            <label>Password:</label>
            <input type="password" name="password" class="input form-control" required>
        </div>

        <div>
            <label>Thumbnail:</label>
            <input type="file" name="thumbnail" class="input form-control">
        </div>

        <button class="btn btn-primary mt-3">Create Gallery</button>
    </form>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
