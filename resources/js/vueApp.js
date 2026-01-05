import DropzoneUploader from './components/DropzoneUploader.vue';
import MediaTable from './components/MediaTable.vue';
import Calendar from './components/Calendar.vue';
import DynamicTable from './components/DynamicTable.vue';
import emitter from './eventBus'; // Adjust path as needed

export default function registerVueApp(app) {
    app.mixin({
        data() {
            return {
                currentFolderId: null,
                currentFolderName: '',
                sidebarCollapsed: false,
            }
        },
        mounted() {
            emitter.on('folder-unselected-action', this.handleUnSelectFolderAction);
        },
        methods: {
            setCurrentFolder(folder) {                
                this.currentFolderId = folder.id;
                this.currentFolderName = folder.name;
            },
            toggleSidebar() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
            },
            handleUnSelectFolderAction() {
                this.selectedFolderId = null;
                this.currentFolderId = null;
                this.currentFolderName = null;
            },
        }
    });

    // Register the Dropzone component globally
    app.component('dropzone-uploader', DropzoneUploader);
    app.component('media-table', MediaTable);
    app.component('calendar', Calendar);
    app.component('dynamic-table', DynamicTable);

    console.log('✅ Vue shared mixin and DropzoneUploader registered successfully');
}
