@extends('adminlte::page')

@section('content')

<h4 class="page_header">Add Client</h4>

<form method="POST" action="{{ route('dashboard.clients.store') }}" enctype="multipart/form-data">

    <div class="card">
        <div class="card-body">
            @include('dashboard.clients._form')
            <button class="btn btn-primary mt-3">Create</button>
        </div>
    </div>

</form>

@stop