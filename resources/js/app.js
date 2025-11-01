import './bootstrap';
import { createApp } from 'vue'
import draggable from 'vuedraggable'
import FolderGrid from './components/FolderGrid.vue'
// import Uploader from './Uploader'
import GalleryFolder from './components/GalleryFolder.vue'
import axios from 'axios'
import registerVueApp from './vueApp'  // renamed for clarity
import datatableJS from './datatableJS' //this to fix some datatable issue like resize the columns in zoom in and out
import mitt from 'mitt';

window.axios = axios

const app = createApp({})

// Register global components
app.component('draggable', draggable)
app.component('folder-grid', FolderGrid)
app.component('gallery-folder', GalleryFolder)

// Register mixins / shared logic from vueApp.js
registerVueApp(app)

// Mount Vue
app.mount('#app')
console.log('✅ Vue app mounted successfully')

// Initialize Dropzone if exists
// if ($('.dropzone').length !== 0) {
//     new Uploader()
// }

// CSRF setup
const token = document.head.querySelector('meta[name="csrf-token"]')
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
    console.error('CSRF token not found: make sure it exists in your <head>!')
}
