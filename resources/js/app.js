import { createApp } from 'vue'
import draggable from 'vuedraggable'
import FolderGrid from './components/FolderGrid.vue'
import Uploader from './Uploader';

// Create the app instance
const app = createApp({})

// Register components globally
app.component('draggable', draggable)
app.component('folder-grid', FolderGrid)

// Mount Vue
app.mount('#app')

console.log('✅ Vue app mounted successfully')

if ($('.dropzone').length !== 0) {
    new Uploader();
}
