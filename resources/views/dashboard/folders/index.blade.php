@extends('adminlte::page')
@section('title', 'Folders')

@section('content')

    <h4 class="page_header">{{ $gallery->name }}</h4>
    <div id="app">
        {{-- <folder-grid></folder-grid> --}}
        <div class="row">
            <div class="col-sm-4 col-md-3">                    
                <gallery-folder :gallery_id="{{ $gallery->id }}" @folder-selected="setCurrentFolder"></gallery-folder>
            </div>

            <div class="col-sm-8 col-md-9 ibox-content">

                {{-- folder name here --}}

                <div class="selected-folder" v-if="currentFolderName">
                    <div class="selected-folder-icon">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div class="selected-folder-info">
                        <h5 class="folder-title">@{{ currentFolderName }}</h5>
                        <span class="folder-subtitle">Currently selected folder</span>
                    </div>
                </div>
                <div class="selected-folder placeholder" v-else>
                    <i class="fas fa-info-circle"></i>
                    <span>Please select a folder from the list to upload files</span>
                </div>



                <div 
                    class="dropzone" data-gallery-id="{{ $gallery->id }}" :data-folder-id="currentFolderId">
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .dropzone { 
            border: 2px dashed #d2d6de !important;
        }
        .dropzone .dz-message {
            color: #646c7f;
            font-size: 20px;
            margin-top: 82px;
        }
    </style>
@stop

@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop
