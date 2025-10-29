
<script>

import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';
import emitter from '../eventBus'; // Adjust path as needed

export default {
    props: ['galleryId', 'currentFolderId'],

    template: `<div ref="dropzone" class="dropzone"></div>`,


    mounted() {
        this.initDropzone();
        emitter.on('folder-selected-action', this.handleFolderAction);
    },

    methods: {
        handleFolderAction(folder){
            this.dropzone.options.url = `/dashboard/galleries/${this.galleryId}/folders/${folder.id}/upload`;
            if(folder.id){
                this.dropzone.enable();
                const messageElement = this.$refs.dropzone.querySelector('.dz-message');
                if (messageElement) messageElement.innerHTML = '<i class="fas fa-upload dropzone-icon"></i> Drop files here to upload';
            } else {
                this.dropzone.disable();
                if (messageElement) messageElement.innerHTML = ' <i class="fas fa-ban dropzone-icon"></i> Please select a folder before uploading';
            }
        },
        initDropzone() {
            Dropzone.autoDiscover = false;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const element = this.$refs.dropzone;

            this.dropzone = new Dropzone(element, {
                url: `/dashboard/galleries/${this.galleryId}/folders/${this.currentFolderId ?? ''}/upload`,
                headers: { 'X-CSRF-TOKEN': token },
                autoProcessQueue: true,
                paramName: 'file',
                maxFilesize: 30, // 30MB
                acceptedFiles: 'image/*,video/*', // ✅ only images or videos
                dictDefaultMessage: '<i class="fas fa-ban dropzone-icon"></i> Please select a folder before uploading',
            });

            this.dropzone.on('sending', (file, xhr, formData) => {
                console.log('📤 Uploading:', file.name);
            });

            this.dropzone.on('success', (file, response) => {
                console.log('✅ Uploaded:', response);
                this.dropzone.removeFile(file);
            });

            this.dropzone.on('error', (file, errorMessage) => {
                if (file.accepted === false) {
                    alert('❌ Only image and video files are allowed.');
                } else {
                    console.error('Upload error:', errorMessage);
                }
            });

            // Initially disable if no folder selected
            if (!this.currentFolderId) {
                this.dropzone.disable();
                console.log('🚫 Dropzone disabled (no folder selected)');
            }
        }
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
</style>