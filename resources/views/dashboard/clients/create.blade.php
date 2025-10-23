@extends('adminlte::page')

@section('content')

<h4 class="page_header">Create Client</h4>

<form method="POST" action="{{ route('dashboard.clients.store') }}" enctype="multipart/form-data">

    <div class="ibox-content">
        @include('dashboard.clients._form')
        <button class="btn btn-primary mt-3">Submit</button>
    </div>

</form>

@stop