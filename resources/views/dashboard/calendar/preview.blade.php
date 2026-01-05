@extends('adminlte::page')
@section('title', 'Calendar')
@section('content')
    <div id="app">
        <div class="ibox-content calendar">
            <calendar gallery-id="3" current-folder-id="4"></calendar>                
        </div>
    </div>
@stop

@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop

