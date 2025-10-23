@extends('adminlte::page')
@section('title', 'Create Gallery')

@section('content_header')
    <h1>Create Gallery</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('dashboard.galleries.store') }}" enctype="multipart/form-data">
        <div class="ibox-content">
            @include('dashboard.galleries._form')
            <button class="btn btn-primary mt-3">Submit</button>
        </div>
    </form>
@stop
