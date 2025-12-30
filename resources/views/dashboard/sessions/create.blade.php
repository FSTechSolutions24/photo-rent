@extends('adminlte::page')
@section('title', 'Create Session')


@section('content')
    <h4 class="page_header">Create Session</h4>
    <form method="POST" action="{{ route('dashboard.sessions.store') }}" enctype="multipart/form-data">
        <div class="ibox-content">
            @include('dashboard.sessions._form')
            <button class="btn btn-primary mt-3">Submit</button>
        </div>
    </form>
@stop
