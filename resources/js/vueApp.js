import DropzoneUploader from './components/DropzoneUploader.vue';

export default function registerVueApp(app) {
    app.mixin({
        data() {
            return {
                currentFolderId: null,
                currentFolderName: '',
                sidebarCollapsed: false,
            }
        },
        methods: {
            setCurrentFolder(folder) {                
                this.currentFolderId = folder.id;
                this.currentFolderName = folder.name;
            },
            toggleSidebar() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
            },
        }
    });

    // Register the Dropzone component globally
    app.component('dropzone-uploader', DropzoneUploader);

    console.log('✅ Vue shared mixin and DropzoneUploader registered successfully');
}
