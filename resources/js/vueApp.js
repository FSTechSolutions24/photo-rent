// vueApp.js
export default function registerVueApp(app) {
    // Add shared reactive data to the root app
    app.mixin({
        data() {
            return {
                currentFolderId: null,
                currentFolderName: ''
            }
        },
        methods: {
            setCurrentFolder(folder) {
                this.currentFolderId = folder.id
                this.currentFolderName = folder.name
            }
        }
    })

    console.log('✅ Vue shared mixin registered successfully')
}
