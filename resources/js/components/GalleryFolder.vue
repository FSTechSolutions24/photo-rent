<template>
    <div class="folder-library">
        <div class="folder-library__header">
            <div>
                <span class="folder-library__eyebrow">GALLERY ORGANIZATION</span>
                <h4>Folders</h4>
            </div>
            <p>Select a folder to upload and manage its media</p>
            <span class="folder-library__count">{{ folders.length }} {{ folders.length === 1 ? 'folder' : 'folders' }}</span>
        </div>

        <div class="folder-library__rail">
            <button type="button" class="folder-add-card" @click="addNewFolder()" title="Create a new folder">
                <span class="folder-add-card__icon"><i class="fas fa-plus"></i></span>
                <span>New folder</span>
            </button>

            <div class="folder-library__divider" aria-hidden="true"></div>

            <div class="folder-library__scroll" role="group" aria-label="Gallery folders">
                <div
                    v-for="folder in folders"
                    :key="folder.id"
                    role="button"
                    tabindex="0"
                    class="folder-card"
                    :class="{ 'is-active': folder.id === selectedFolderId }"
                    :aria-pressed="folder.id === selectedFolderId"
                    @click="selectFolder(folder)"
                    @keydown.enter="selectFolder(folder)"
                    @keydown.space.prevent="selectFolder(folder)"
                >
                    <span class="folder-card__icon"><i :class="folder.id === selectedFolderId ? 'fas fa-folder-open' : 'fas fa-folder'"></i></span>
                    <span class="folder-card__body">
                        <strong>{{ folder.name }}</strong>
                        <small>{{ folder.id === selectedFolderId ? 'Selected folder' : 'Open folder' }}</small>
                    </span>
                    <button
                        type="button"
                        class="folder-card__settings"
                        title="Folder settings"
                        aria-label="Folder settings"
                        @click.stop="previewFolderSettings(folder)"
                    ><i class="fas fa-ellipsis-h"></i></button>
                </div>

                <div v-if="folders.length === 0" class="folder-library__empty">
                    <i class="far fa-folder-open"></i>
                    <span><strong>No folders yet</strong><small>Create your first folder to start uploading media.</small></span>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="folderModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">



                        <h5 class="modal-title fs-5" id="folderModalLabel">
                            <span v-if="!folder.id">Create New Folder</span>
                            <span v-else>Update {{ folder.name }}</span>
                        </h5>


                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body position-relative">

                        <div class="mb-3">
                            <label class="form-label">Folder Name: <span class="required_start">*</span></label>
                            <input type="text" v-model="folder.name" class="input form-control" autocomplete="off">
                            <label v-show="errors.folderName" class="text-danger">{{ errors.folderName }}</label>
                        </div>

                        <!-- Thumbnail Upload -->
                        <div>
                            <label class="form-label">Folder Thumbnail</label>
                            <div class="thumbnail-upload text-center p-3 border rounded-3" @click="$refs.thumbnailInput.click()" style="cursor:pointer; transition:0.2s; border-style:dashed;">
                                <div v-if="!folder.thumbnailPreview">
                                    <i class="bi bi-cloud-upload fs-2 text-primary"></i>
                                    <p class="mb-0 text-muted">Click to upload an image</p>
                                    <small class="text-secondary">PNG, JPG, or JPEG (max 2MB)</small>
                                </div>
                                <div v-else>
                                    <img :src="folder.isLocalPreview ? folder.thumbnailPreview : `/storage/${folder.thumbnail_path}`" class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                                    <p class="text-muted mt-2 mb-0">Click to change image</p>
                                </div>
                            </div>
                            <input type="file" ref="thumbnailInput" class="d-none" accept="image/*" @change="handleThumbnailUpload">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-success" @click="downloadFolder(folder)">Download</button>
                        <button type="button" class="btn btn-outline-danger" @click="deleteFolder(folder)">Delete</button>
                        <button type="button" class="btn btn-blank" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="updateOrCreateFolder()">
                            <span v-if="!folder.id">Create Folder</span>
                            <span v-else>Update Folder</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import emitter from '../eventBus'; // Adjust path as needed
export default {
    name: 'GalleryFolder',
    props: {
        gallery_id: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            folders: [],
            selectedFolderId: null,
            folder: {
                id: '',
                name: '',
                thumbnail_path: '',
                thumbnail: null,
                thumbnailPreview: null,
            },
            errors: {
                folderName: null
            }
        }
    },
    watch: {
        'folder.name'(newValue) {
            if (newValue && newValue.trim() !== '') {
                this.errors.folderName = null;
            } else {
                this.errors.folderName = 'Folder name is required';
            }
        }
    },
    created(){
        this.loadFolders();
    },
    methods: {
        previewFolderSettings(selectedFolder){
            this.folder.id = selectedFolder.id;
            this.folder.name = selectedFolder.name;
            this.folder.thumbnail_path = selectedFolder.thumbnail_path;
            this.folder.isLocalPreview = false;
            // set thumbnail preview if available
            if (selectedFolder.thumbnail_path) {
                this.folder.thumbnailPreview = selectedFolder.thumbnail_path;
            } else {
                this.folder.thumbnailPreview = null;
            }
            setTimeout(()=>{
                $('#folderModal').modal('show');
            }, 100)
        },
        handleThumbnailUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.folder.thumbnail_path = file;
                this.folder.thumbnailPreview = URL.createObjectURL(file);
                this.folder.isLocalPreview = true; // mark as local
            }
        },
        addNewFolder(){
            $('#folderModal').modal('show');
            this.clearFolderPopup();
        },
        clearFolderPopup(){
            this.folder.id = '';
            this.folder.name = '';
            this.folder.thumbnail_path = null;
            this.folder.thumbnailPreview = null;
            this.errors.folderName = null
        },
        updateOrCreateFolder() {
            if(this.folder.name == ''){
                this.errors.folderName = 'Folder name is required';
                return;
            }
            const formData = new FormData();
            if (this.folder.id) {
                formData.append('id', this.folder.id);
            }
            formData.append('name', this.folder.name);
            if (this.folder.thumbnail_path) {
                formData.append('thumbnail_path', this.folder.thumbnail_path);
            }

            axios.post(`/dashboard/galleries/${this.gallery_id}/folders`, formData)
            .then(response => {
                const updatedFolder = response.data.folder;

                // Check if folder exists in current list
                const index = this.folders.findIndex(f => f.id === updatedFolder.id);
                if (index !== -1) {
                    this.folders[index] = updatedFolder;
                } else {
                    this.folders.push(updatedFolder);
                }

                $('#folderModal').modal('hide');
                setTimeout(()=>{
                    this.clearFolderPopup();
                    toastr.success(this.folder.id ? 'Folder updated successfully!' : 'Folder created successfully!');
                }, 100)
            })
            .catch(error => {
                toastr.error('Failed to save folder.' + error);
            });
        },
        loadFolders() {
            axios.get(`/dashboard/api/galleries/${this.gallery_id}/folders`)
            .then(response => {
                this.folders = response.data;
            })
            .catch(error => {
                window.toastr.error('Failed to load folders.' + error);
            });
        },
        selectFolder(folder) {
            this.selectedFolderId = folder.id
            this.$emit('folder-selected', folder);
            emitter.emit('folder-selected-action', folder);
        },
        unSelectFolder() {
            this.selectedFolderId = null;
            this.currentFolderId = null;
            this.currentFolderName = null;
            emitter.emit('folder-unselected-action');
        },
        deleteFolder(folder){
            Swal.fire({
                title: 'Are you sure?',
                text: "This folder and its contents will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/dashboard/galleries/${this.gallery_id}/folders/${folder.id}`)
                    .then(response => {
                        Swal.fire('Deleted!', 'The folder has been deleted.', 'success');
                        this.folders = this.folders.filter(f => f.id !== folder.id);
                        this.unSelectFolder();
                        $('#folderModal').modal('hide');
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Something went wrong while deleting.', 'error');
                        console.error(error);
                    });
                }
            });
        },
        downloadFolder(id) {
            axios.post('/dashboard/folders/download', { id })
            .then(response => {
                console.log('Download request created:', response.data);
                alert(response.data.message);
            })
            .catch(err => {
                console.error('Failed to create download request:', err);

                alert(
                    'Download request failed: ' +
                    (err.response?.data?.error || 'Server error')
                );
            });
        },
    }
}
</script>

<style>
    .folder-library {
        width: 100%;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
    }
    .folder-library__header {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 20px 24px 15px;
        border-bottom: 1px solid #edf1f5;
    }
    .folder-library__header h4 {
        margin: 2px 0 0;
        color: #172b3a;
        font-size: 19px;
        font-weight: 700;
    }
    .folder-library__eyebrow {
        color: #7b8b9b;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .13em;
    }
    .folder-library__header p {
        margin: 10px 0 0;
        color: #7a8998;
        font-size: 12px;
    }
    .folder-library__count {
        margin-left: auto;
        border-radius: 999px;
        padding: 6px 11px;
        background: #eef4fb;
        color: #4d6c8d;
        font-size: 11px;
        font-weight: 700;
    }
    .folder-library__rail {
        display: flex;
        width: 100%;
        align-items: stretch;
        min-width: 0;
        max-width: 100%;
        overflow: hidden;
        box-sizing: border-box;
        padding: 16px 20px 20px;
    }
    .folder-add-card,
    .folder-card {
        min-height: 78px;
        border-radius: 12px;
        font: inherit;
        cursor: pointer;
        transition: border-color .2s ease, background .2s ease, box-shadow .2s ease, transform .2s ease;
    }
    .folder-add-card {
        display: flex;
        width: 92px;
        flex: 0 0 92px;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 7px;
        border: 1px dashed #8bbbf1;
        background: #f3f8ff;
        color: #176cc7;
        font-size: 11px;
        font-weight: 750;
    }
    .folder-add-card:hover,
    .folder-add-card:focus {
        border-color: #1877dd;
        outline: 0;
        background: #eaf3ff;
        box-shadow: 0 7px 16px rgba(29, 112, 203, .12);
        transform: translateY(-1px);
    }
    .folder-add-card__icon {
        display: grid;
        width: 30px;
        height: 30px;
        place-items: center;
        border-radius: 9px;
        background: #1675df;
        color: #fff;
        box-shadow: 0 5px 12px rgba(22, 117, 223, .24);
    }
    .folder-library__divider {
        width: 1px;
        flex: 0 0 1px;
        margin: 6px 16px;
        background: #e7edf3;
    }
    .folder-library__scroll {
        display: flex;
        width: 0;
        min-width: 0;
        max-width: 100%;
        flex: 1;
        align-items: stretch;
        gap: 12px;
        overflow-x: auto;
        overflow-y: hidden;
        overscroll-behavior-x: contain;
        -webkit-overflow-scrolling: touch;
        padding: 1px 3px 8px 1px;
        scrollbar-color: #c4d1dd transparent;
        scrollbar-width: thin;
    }
    .folder-library__scroll::-webkit-scrollbar {
        height: 6px;
    }
    .folder-library__scroll::-webkit-scrollbar-thumb {
        border-radius: 999px;
        background: #c4d1dd;
    }
    .folder-card {
        display: flex;
        width: 225px;
        flex: 0 0 225px;
        align-items: center;
        gap: 11px;
        border: 1px solid #e0e7ee;
        padding: 13px;
        background: #fff;
        color: #33485c;
        text-align: left;
    }
    .folder-card:hover,
    .folder-card:focus {
        border-color: #b9d4ef;
        outline: 0;
        background: #fafcff;
        box-shadow: 0 7px 18px rgba(35, 62, 89, .09);
        transform: translateY(-1px);
    }
    .folder-card.is-active {
        border-color: #4794e5;
        background: linear-gradient(135deg, #f1f7ff 0%, #f8fbff 100%);
        box-shadow: inset 0 0 0 1px rgba(71, 148, 229, .08), 0 7px 18px rgba(31, 113, 200, .1);
    }
    .folder-card__icon {
        display: grid;
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        place-items: center;
        border-radius: 11px;
        background: #edf3f9;
        color: #6b8299;
        font-size: 18px;
    }
    .folder-card.is-active .folder-card__icon {
        background: #dcecff;
        color: #1675df;
    }
    .folder-card__body {
        min-width: 0;
        flex: 1;
    }
    .folder-card__body strong,
    .folder-card__body small {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .folder-card__body strong {
        color: #263d51;
        font-size: 13px;
        font-weight: 700;
    }
    .folder-card__body small {
        margin-top: 4px;
        color: #8a98a7;
        font-size: 10px;
    }
    .folder-card.is-active .folder-card__body small {
        color: #367abf;
        font-weight: 700;
    }
    .folder-card__settings {
        display: grid;
        width: 28px;
        height: 28px;
        flex: 0 0 28px;
        place-items: center;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: #93a0ad;
        transition: background .2s ease, color .2s ease;
    }
    .folder-card__settings:hover,
    .folder-card__settings:focus {
        outline: 0;
        background: #e5edf5;
        color: #2e5f8f;
    }
    .folder-library__empty {
        display: flex;
        min-width: 290px;
        align-items: center;
        gap: 12px;
        color: #8a98a7;
    }
    .folder-library__empty > i {
        font-size: 25px;
    }
    .folder-library__empty strong,
    .folder-library__empty small {
        display: block;
    }
    .folder-library__empty strong {
        color: #53677a;
        font-size: 13px;
    }
    .folder-library__empty small {
        margin-top: 2px;
        font-size: 11px;
    }
    .thumbnail-upload {
        border: 2px dashed #d2d6de !important;
        border-radius: 14px !important;
        padding: 30px 20px !important;
    }
    @media (max-width: 700px) {
        .folder-library__header {
            align-items: flex-start;
            flex-wrap: wrap;
            padding: 18px;
        }
        .folder-library__header p {
            width: 100%;
            margin: 0;
            order: 3;
        }
        .folder-library__count {
            margin-top: 4px;
        }
        .folder-library__rail {
            padding: 14px 14px 17px;
        }
        .folder-add-card {
            width: 76px;
            flex-basis: 76px;
        }
        .folder-library__divider {
            margin-right: 11px;
            margin-left: 11px;
        }
        .folder-card {
            width: 200px;
            flex-basis: 200px;
        }
    }
</style>



