<template>
    <div class="ibox float-e-margins">
        <div class="ibox-content" style="padding-left: 0 !important;">
            <div class="file-manager" style="padding-left: 15px;">                            
                <div class="hr-line-dashed"></div>
                
                <div class="selected-folder placeholder">

                    <div @click="addNewFolder()" class="btn-effects">
                        <i class="fas fa-plus-circle"></i>
                    </div>

                    <!-- <i class="fas fa-folder"></i> -->
                    <!-- <span>FOLDERS</span> -->
                </div>

                <ul class="folder-list" style="padding:0;">



                    <div class="selected-folder btn selected-folder-element" v-for="folder in folders" @click="selectFolder(folder)" :key="folder.id" :class="{ active: folder.id === selectedFolderId }">
                        <div class="selected-folder-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        <div class="selected-folder-info">
                            <h5 class="folder-title">{{ folder.name }}</h5>
                        </div>
                    </div>



                </ul>                            
                <div class="clearfix"></div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Folder Name: <span class="required_start">*</span></label>
                            <input type="text" name="phone" class="input form-control" autocomplete="false">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Folder Description: <span class="required_start">*</span></label>
                            <input type="text" name="phone" class="input form-control" autocomplete="false">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-blank" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
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
            selectedFolderId: null
        }
    },
    created(){
        this.load_folders();
    },
    methods: {
        addNewFolder(){
            $('#exampleModal').modal('show');
        },
        async load_folders() {
            try {
                const response = await axios.get(`/dashboard/api/galleries/${this.gallery_id}/folders`)
                this.folders = response.data
            } catch (error) {
                console.error('Failed to load folders:', error)
            }
        },
        selectFolder(folder) {
            // Emit an event to the parent Vue app
            this.selectedFolderId = folder.id
            this.$emit('folder-selected', folder);
        },
    }
}
</script>

<style>
    .folder-list li {
        color: #666666;
        display: block;
        padding: 5px 0;
        cursor: pointer;
        user-select: none;       
        -webkit-user-select: none; 
        -moz-user-select: none; 
    }
    .folder-list li:hover {
        background-color: #f1f1f1;
    }
    .folder-list li {
        border-bottom: 1px solid #e7eaec;
        display: block;
        padding-left: 15px;
    }
    .folder-list li i {
        margin-right: 8px;
        color: #3d4d5d;
    }
    .ibox-footer {
        color: inherit;
        border-top: 1px solid #e7eaec;
        font-size: 90%;
        background: #ffffff;
        padding: 10px 15px;
    }
    .selected-folder {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e0e6ed;
        border-radius: 10px;
        padding: 10px 16px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: all 0.2s ease-in-out;
    }
    .selected-folder-element {
        background-color: unset;
        /* padding: 7px 15px; */
    }
    /* .selected-folder-element .selected-folder-icon{
        font-size: 16px;
        width: 28px;
        height: 28px;
    } */
    /* .selected-folder-element .selected-folder-info .folder-title{
        font-size: 14px;
    } */
    .selected-folder:hover {
        background: #f5f5f5;
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
    }
    .selected-folder.placeholder:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .selected-folder-icon {
        background-color: #007bff1a;
        color: #007bff;
        font-size: 22px;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    .selected-folder-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .folder-title {
        font-size: 16px;
        font-weight: 600;
        color: #343a40;
        margin: 0;
    }

    .folder-subtitle {
        font-size: 13px;
        color: #6c757d;
    }
    .selected-folder.placeholder {
        justify-content: center;
        color: #999;
        font-style: italic;
        border: 1px dashed #ddd;
        background: #fafafa;
    }
    .selected-folder.placeholder i {
        margin-right: 4px;
    }
    .selected-folder.active {
        background: #f5f5f5;
        box-shadow: 0 4px 6px rgba(0,0,0,0.08);
        border-left: 4px solid #007bff;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Optional slight variation when hovering over the selected one */
    .selected-folder.active:hover {
        background: #f5f5f5;
    }
</style>



