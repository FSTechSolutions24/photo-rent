
<template>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Thumbnail</th>
                <th scope="col">Name</th>
                <th scope="col">Disk</th>
                <th scope="col">Size</th>            
                <th scope="col">Created</th>            
            </tr>
        </thead>
        <tbody>                  
        </tbody>
    </table>

</template>

<script>

// import Dropzone from 'dropzone';
// import 'dropzone/dist/dropzone.css';
import emitter from '../eventBus'; // Adjust path as needed

export default {
    props: ['galleryId', 'currentFolderId'],

    mounted() {

        if (this.currentFolderId && this.currentFolderId > 0) {
            this.initDataTable();
        }
        emitter.on('folder-selected-action', this.handleFolderAction);
        emitter.on('media-uploaded', this.reloadTable);
    },

    methods: {
        reloadTable(){
            // Otherwise safely reload from the new folder
            const table = window.view_reports;
            const url = this.getApiUrl();

            if (url) {
                table.ajax.url(url);
                table.ajax.reload(null, false);
            } else {
                console.warn('No valid URL found — reload skipped.');
            }
        },
        getApiUrl() {
            // dynamically build the correct route inside Vue
            return `/dashboard/api/galleries/${this.galleryId}/folders/${this.currentFolderId}/media`;
        },
        handleFolderAction(folder) {
            this.currentFolderId = folder.id;

            // Stop if invalid folder
            if (!this.currentFolderId || this.currentFolderId <= 0) {
                console.warn('No valid folder selected — skipping reload.');
                return;
            }

            // If table doesn't exist yet, initialize it
            if (!window.view_reports) {
                this.initDataTable();
                return;
            }

            this.reloadTable();
        },
        initDataTable() {
            $(document).ready(() => {
                window.view_reports = $('.table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    autoWidth: false,
                    scrollX: true, // optional, if you have many columns
                    ajax: {
                        url: this.getApiUrl(),
                        type: 'POST',
                        data: (d) => {
                            d._token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            d.folder_id = this.currentFolderId;
                            d.gallery_id = this.galleryId;
                        },
                    },
                    columns: [
                        { data: 'id' },
                        { data: 'thumbnail' },
                        { data: 'name' },
                        { data: 'disk' },
                        { data: 'size' },
                        { data: 'created_at' },
                    ],
                });
            });
        },
    }
};
</script>


<style>
    .dropzone-icon {
        padding: 8px;
        border-radius: 50%;
        font-size: 14px;
        background: #dfeefc;
        color: #007bff;
        margin-right: 5px;
    }
    .thumbnail-holder {
        border: 1px solid #e2e8f0;
        position: relative;
        background: transparent;
        height: 55px;
        width: 60px;
        border-radius: 6px;
        overflow: hidden !important;
    }
    .thumbnail-holder img {
        position: absolute;
        top: 50%;
        left: 50%;
        max-height: 100%;
        max-width: 100%;
        transform: translate(-50%, -50%);
    }
</style>