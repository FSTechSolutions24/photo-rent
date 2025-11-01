@extends('adminlte::page')
@section('title', 'Folders')

@section('content')

    <h4 class="page_header">{{ $gallery->name }}</h4>
    <div id="app">
        {{-- <folder-grid></folder-grid> --}}
        <div class="row">
            <div class="col-sm-4 col-md-3" :class="{ 'd-none': sidebarCollapsed }">                    
                <gallery-folder :gallery_id="{{ $gallery->id }}" @folder-selected="setCurrentFolder"></gallery-folder>
            </div>

            <div class="col-sm-8 col-md-9 ibox-content" :class="{ 'col-sm-12 col-md-12': sidebarCollapsed }">

                <!-- Collapse/Expand Button -->
                <div style="position: absolute;top: -10px;left: -10px;">
                    <button class="btn btn-sm btn-secondary toggle_btn" @click="toggleSidebar">
                        <i v-if="sidebarCollapsed" class="fas fa-chevron-right"></i>
                        <i v-else class="fas fa-chevron-left"></i> 
                    </button>
                </div>

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

                <dropzone-uploader :gallery-id="{{ $gallery->id }}" :current-folder-id="currentFolderId"></dropzone-uploader>
                
                {{-- table --}}
                <br>          
                <media-table :gallery-id="{{ $gallery->id }}" :current-folder-id="currentFolderId"></media-table>


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
        .toggle_btn{
            background-color: #fafafa !important;
            color: #999 !important;
            border: 1px solid #ddd !important;
            border-radius: 20px !important;
        }
    </style>
@stop

@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop
