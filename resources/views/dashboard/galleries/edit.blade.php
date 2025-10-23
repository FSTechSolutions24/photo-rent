@extends('adminlte::page')
@section('title', 'Update Gallery')

@section('content_header')
    <h1>Update Gallery</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('dashboard.galleries.update', $gallery->id) }}" enctype="multipart/form-data">
        @method('PUT')
        <div class="ibox-content">
            @include('dashboard.galleries._form')
            <button class="btn btn-primary mt-3">Update</button>
        </div>
    </form>
@stop
