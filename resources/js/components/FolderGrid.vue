<template>
  <div>
    <h3 class="text-lg font-semibold mb-4">Foldersw</h3>

    <draggable
      v-model="media"
      item-key="id"
      animation="200"
      class="grid grid-cols-3 gap-4"
    >
      <template #item="{ element }">
        <div
          class="border p-4 bg-white shadow-sm cursor-move rounded text-center"
        >
          {{ element.name }}
        </div>
      </template>
    </draggable>

    <!-- Optional: show message if empty -->
    <div v-if="media.length === 0" class="text-gray-500 mt-4">
      No folders available
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import draggable from 'vuedraggable'

// Test data
const media = ref([
  { id: 1, name: 'Folder 1' },
  { id: 2, name: 'Folder 2' },
  { id: 3, name: 'Folder 3' },
  { id: 4, name: 'Folder 4' },
  { id: 5, name: 'Folder 5' },
])
</script>

<style scoped>
/* Optional: adjust grid styling if needed */
</style>



