@extends('adminlte::page')
@section('title', 'Folders')

@section('content')

    <x-page-header :title="$gallery->name" description="Organize this gallery into folders and manage its uploaded photographs."
        :breadcrumbs="[['label' => 'Galleries', 'url' => route('dashboard.galleries.index')], ['label' => $gallery->name], ['label' => 'Folders']]"
        :action-url="route('dashboard.galleries.index')" action-label="View galleries" action-icon="fas fa-images" />
    <div id="app">
        <div class="gallery-folder-workspace">
            <section class="folder-library-section" aria-label="Gallery folders">
                <gallery-folder :gallery_id="{{ $gallery->id }}" @folder-selected="setCurrentFolder"></gallery-folder>
            </section>

            <section class="upload-workspace">
                <div class="upload-workspace__header">
                    <div class="upload-workspace__heading">
                        <span class="workspace-eyebrow">MEDIA UPLOAD</span>
                        <h4>Add photos and videos</h4>
                    </div>

                    <div class="upload-destination upload-destination--selected" v-if="currentFolderName">
                        <span class="upload-destination__icon"><i class="fas fa-folder-open"></i></span>
                        <span>
                            <small>Uploading to</small>
                            <strong>@{{ currentFolderName }}</strong>
                        </span>
                    </div>
                    <div class="upload-destination" v-else>
                        <span class="upload-destination__icon"><i class="fas fa-hand-pointer"></i></span>
                        <span>
                            <small>Upload destination</small>
                            <strong>Select a folder above</strong>
                        </span>
                    </div>
                </div>

                <dropzone-uploader :gallery-id="{{ $gallery->id }}" :current-folder-id="currentFolderId"></dropzone-uploader>
            </section>

            <section class="media-library-section" v-show="currentFolderId">
                <div class="media-library-section__header">
                    <div>
                        <span class="workspace-eyebrow">FOLDER CONTENTS</span>
                        <h4>@{{ currentFolderName }}</h4>
                    </div>
                    <span class="media-library-section__hint"><i class="fas fa-shield-alt"></i> Control guest visibility with the Private switch</span>
                </div>
                <media-table v-show="currentFolderId" :gallery-id="{{ $gallery->id }}" :current-folder-id="currentFolderId"></media-table>
            </section>
        </div>
    </div>
@stop

@section('css')
    <style>
        .gallery-folder-workspace {
            display: grid;
            gap: 22px;
            padding-bottom: 36px;
        }
        .folder-library-section,
        .upload-workspace,
        .media-library-section {
            width: 100%;
            border: 1px solid #e5ebf1;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(31, 45, 61, .065);
        }
        .folder-library-section {
            overflow: visible;
        }
        .upload-workspace {
            padding: 24px;
        }
        .upload-workspace__header,
        .media-library-section__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }
        .upload-workspace__heading h4,
        .media-library-section__header h4 {
            margin: 3px 0 0;
            color: #172b3a;
            font-size: 19px;
            font-weight: 700;
        }
        .workspace-eyebrow {
            color: #7b8b9b;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .13em;
        }
        .upload-destination {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 220px;
            border: 1px solid #e3e9ef;
            border-radius: 12px;
            padding: 9px 12px;
            background: #f8fafc;
        }
        .upload-destination--selected {
            border-color: #bfd8fb;
            background: #f2f7ff;
        }
        .upload-destination__icon {
            display: grid;
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            place-items: center;
            border-radius: 9px;
            background: #e7edf3;
            color: #6a7b8d;
        }
        .upload-destination--selected .upload-destination__icon {
            background: #dcecff;
            color: #1675e0;
        }
        .upload-destination small,
        .upload-destination strong {
            display: block;
            line-height: 1.25;
        }
        .upload-destination small {
            margin-bottom: 2px;
            color: #8190a0;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .upload-destination strong {
            overflow: hidden;
            max-width: 190px;
            color: #2d4052;
            font-size: 13px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .dropzone { 
            min-height: 210px !important;
            border: 2px dashed #cbd6e2 !important;
            border-radius: 14px !important;
            background: linear-gradient(135deg, #fbfdff 0%, #f7faff 100%) !important;
            transition: border-color .2s ease, background .2s ease, transform .2s ease;
        }
        .dropzone:hover {
            border-color: #7eb4f2 !important;
            background: #f5f9ff !important;
        }
        .dropzone .dz-message {
            color: #646c7f;
            font-size: 17px;
            margin: 66px 0 0;
        }
        .media-library-section {
            overflow: hidden;
            padding: 24px;
        }
        .media-library-section__header {
            padding-bottom: 17px;
            border-bottom: 1px solid #edf1f5;
        }
        .media-library-section__hint {
            color: #748394;
            font-size: 12px;
        }
        .media-library-section__hint i {
            margin-right: 5px;
            color: #327dcc;
        }
        .media-library-section .dataTables_wrapper {
            width: 100%;
        }
        @media (max-width: 700px) {
            .upload-workspace,
            .media-library-section {
                padding: 18px;
            }
            .upload-workspace__header,
            .media-library-section__header {
                align-items: flex-start;
                flex-direction: column;
            }
            .upload-destination {
                width: 100%;
            }
            .media-library-section__hint {
                line-height: 1.5;
            }
        }
    </style>
@stop

@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop
