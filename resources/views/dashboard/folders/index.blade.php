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
                <div style="position: absolute;top: -10px;left: -5px;">
                    <button style="border-radius: 50%;" class="btn btn-sm btn-secondary" @click="toggleSidebar">
                        <i v-if="sidebarCollapsed" class="fas fa-step-forward"></i>
                        <i v-else class="fas fa-step-backward"></i>
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



                {{-- <div 
                    class="dropzone" data-gallery-id="{{ $gallery->id }}" :data-folder-id="currentFolderId">
                </div> --}}

                <dropzone-uploader :gallery-id="{{ $gallery->id }}" :current-folder-id="currentFolderId"></dropzone-uploader>

                
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
        .col-sm-4.col-md-3 {
            /* transition: all 0.3s ease; */
        }
        .ibox-content {
            /* transition: all 0.3s ease; */
        }
    </style>
@stop

@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop
