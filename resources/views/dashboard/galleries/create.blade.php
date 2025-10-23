@extends('adminlte::page')
@section('title', 'Create Gallery')


@section('content')
    <h4 class="page_header">Create Gallery</h4>
    <form method="POST" action="{{ route('dashboard.galleries.store') }}" enctype="multipart/form-data">
        <div class="ibox-content">
            @include('dashboard.galleries._form')
            <button class="btn btn-primary mt-3">Submit</button>
        </div>
    </form>
@stop
