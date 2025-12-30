@extends('adminlte::page')
@section('title', 'Update Session')

@section('content')
    <h4 class="page_header">Update Session</h4>
    <form method="POST" action="{{ route('dashboard.sessions.update', $session->id) }}" enctype="multipart/form-data">
        @method('PUT')
        <div class="ibox-content">
            @include('dashboard.sessions._form')
            <button class="btn btn-primary mt-3">Update</button>
        </div>
    </form>
@stop
