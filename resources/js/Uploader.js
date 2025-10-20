import Dropzone from 'dropzone';
import 'dropzone/dist/dropzone.css';

export default class {
    constructor() {
        // Get the dropzone element and IDs from data attributes
        const dropzoneElement = document.querySelector('.dropzone');
        const galleryId = dropzoneElement.dataset.galleryId;
        const folderId = dropzoneElement.dataset.folderId;

        Dropzone.autoDiscover = false;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Initialize Dropzone with the dynamic URL
        this.dropzone = new Dropzone(dropzoneElement, {
            url: `/dashboard/galleries/${galleryId}/folders/${folderId}/upload`, // use your route
            autoProcessQueue: true,
            // maxFilesize: FleetCart.maxFileSize,
            headers: {
                'X-CSRF-TOKEN': token
            }
        });

        this.dropzone.on('sending', this.sending.bind(this.dropzone));
        this.dropzone.on('success', this.success.bind(this.dropzone));
        this.dropzone.on('error', this.error.bind(this.dropzone));
    }

    sending(file, xhr) {
        xhr.timeout = 3600000;
        $('.alert-danger').remove();
    }

    success() {
        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            setTimeout(DataTable.reload, 1000, '#media-table .table');
        }
    }

    error(file, response) {
        $('.dz-progress').css('z-index', 1);
        $(file.previewElement).find('.dz-error-message').text(response.message);
    }
}
