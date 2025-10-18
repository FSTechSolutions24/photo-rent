@extends('adminlte::page')

@section('content')

<h4 class="page_header">Edit Client</h4>

<form method="POST" action="{{ route('dashboard.clients.update', $client->id) }}" enctype="multipart/form-data">

    @method('PUT')
    <div class="card">
        <div class="card-body">
            @include('dashboard.clients._form')
            <button class="btn btn-primary mt-3">Update</button>
        </div>
    </div>
</form>

@stop