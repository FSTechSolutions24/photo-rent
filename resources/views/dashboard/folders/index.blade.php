@extends('adminlte::page')
@section('title', 'Folders')

@section('content_header')
    <h1>Folders</h1>
@stop

@section('content')
    <div class="card">
        <div id="app">
            {{-- <folder-grid></folder-grid> --}}
        </div>
    
        <div class="row">
    
            <div class="col-sm-4 col-md-3">test</div>
            <div class="col-sm-8 col-md-9">
                <div 
                    class="dropzone" data-gallery-id="{{ $gallery->id }}"  data-folder-id="1">
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop
