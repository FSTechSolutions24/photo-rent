@extends('adminlte::page')
@section('title', 'Create Client')

@section('content_header')
    <h1>Create Client</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('dashboard.galleries.store') }}" enctype="multipart/form-data">
        <div class="card">
            <div class="card-body">
                @include('dashboard.galleries._form')
                <button class="btn btn-primary mt-3">Create</button>
            </div>
        </div>
    </form>
@stop
