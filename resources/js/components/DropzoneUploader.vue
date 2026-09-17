
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
        emitter.on('folder-unselected-action', this.handleUnSelectFolderAction);
    },

    methods: {
        handleFolderAction(folder){
            this.dropzone.options.url = `/dashboard/galleries/${this.galleryId}/folders/${folder.id}/upload`;
            if(folder.id){
                this.dropzone.enable();
                this.setDropzoneMessage(true);
            } else {
                this.dropzone.disable();
                this.setDropzoneMessage(false);
            }
        },
        handleUnSelectFolderAction(){
            this.dropzone.disable();
            this.setDropzoneMessage(false);
        },
        setDropzoneMessage(enabled) {
            const messageElement = this.$refs.dropzone.querySelector('.dz-message');
            if (!messageElement) return;

            messageElement.innerHTML = enabled
                ? '<span class="dropzone-prompt"><span class="dropzone-prompt__icon"><i class="fas fa-cloud-upload-alt"></i></span><strong>Drop media here or click to browse</strong><small>JPG, PNG, GIF, WebP, MP4, MOV or AVI · up to 30 MB</small></span>'
                : '<span class="dropzone-prompt"><span class="dropzone-prompt__icon dropzone-prompt__icon--muted"><i class="far fa-folder-open"></i></span><strong>Select a folder before uploading</strong><small>Choose one of the folders above to activate uploads</small></span>';
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
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.webp,.mp4,.mov,.avi",
                dictDefaultMessage: '<span class="dropzone-prompt"><span class="dropzone-prompt__icon dropzone-prompt__icon--muted"><i class="far fa-folder-open"></i></span><strong>Select a folder before uploading</strong><small>Choose one of the folders above to activate uploads</small></span>',
            });

            this.dropzone.on('sending', (file, xhr, formData) => {
                console.log('📤 Uploading:', file.name);
            });

            this.dropzone.on('success', (file, response) => {
                console.log('✅ Uploaded:', response);
                this.dropzone.removeFile(file);
                emitter.emit('media-uploaded');
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
    .dropzone-prompt {
        display: flex;
        align-items: center;
        flex-direction: column;
        color: #516579;
    }
    .dropzone-prompt__icon {
        display: grid;
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        place-items: center;
        border-radius: 14px;
        background: #e2efff;
        color: #1675df;
        font-size: 20px;
        box-shadow: 0 7px 16px rgba(22, 117, 223, .11);
    }
    .dropzone-prompt__icon--muted {
        background: #edf1f5;
        color: #8392a2;
        box-shadow: none;
    }
    .dropzone-prompt strong {
        font-size: 15px;
        font-weight: 700;
    }
    .dropzone-prompt small {
        margin-top: 5px;
        color: #8a98a7;
        font-size: 11px;
    }
</style>
