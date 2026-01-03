@extends('adminlte::page')
@section('title', 'Calendar')
@section('content')
    <div id="app">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-body">
                    <calendar gallery-id="3" current-folder-id="4"></calendar>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop

