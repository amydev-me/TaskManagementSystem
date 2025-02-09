<template>
  <div>
    <Navbar />
 
    <div class="p-6 max-w-3xl mx-auto">
      

      <!-- Add Task Button -->
      <button
        @click="showModal = true"
        class="bg-blue-500 text-white px-4 py-2 rounded"
      >
        + Add Task
      </button>

      <!-- Task List -->
      <ul class="mt-4">
        <li
          v-for="(task, index) in tasks"
          :key="index"
          class="bg-gray-100 p-4 rounded mt-2"
        >
          <div class="flex justify-between">
            <div>
              <h3 class="font-semibold text-lg">{{ task.title }}</h3>
              <p class="text-gray-600">{{ task.description }}</p>
              <p class="text-sm text-gray-500">Due: {{ task.due_date }}</p>
              <span
                class="px-2 py-1 text-xs rounded text-white bg-green-600"
                
              >
                {{ task.category.name }}
              </span>
            </div>
            <button @click="deleteTask(task.id)" class="text-red-500">X</button>
          </div>
        </li>
      </ul>

       <!-- Pagination Controls -->
       <div class="flex justify-center items-center mt-4">
        <button
          @click="fetchTasks(currentPage - 1)"
          :disabled="!prevPageUrl"
          class="px-4 py-2 mx-2 bg-gray-300 rounded disabled:opacity-50"
        >
          Previous
        </button>
        <span class="text-gray-700">Page {{ currentPage }} of {{ totalPages }}</span>
        <button
          @click="fetchTasks(currentPage + 1)"
          :disabled="!nextPageUrl"
          class="px-4 py-2 mx-2 bg-blue-500 text-white rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>
      <!-- Modal -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center"
      >
        <div class="bg-white p-6 rounded shadow-lg w-96">
          <h2 class="text-xl mb-4">Add Task</h2>

          <!-- Title Input -->
          <label class="block text-sm font-medium text-gray-700">Title</label>
          <input
            v-model="newTask.title"
            type="text"
            placeholder="Enter title..."
            class="w-full p-2 border rounded mb-2"
          />

          <!-- Description Input -->
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            v-model="newTask.description"
            placeholder="Enter description..."
            class="w-full p-2 border rounded mb-2"
          ></textarea>

          <!-- Due Date Input -->
          <label class="block text-sm font-medium text-gray-700">Due Date</label>
          <input
            v-model="newTask.due_date"
            type="date"
            class="w-full p-2 border rounded mb-2"
          />

          <!-- Category Dropdown -->
          <label class="block text-sm font-medium text-gray-700">Category</label>
          <select
            v-model="newTask.category_id"
            class="w-full p-2 border rounded mb-4"
          >
            <option  v-for="(category, index) in categories" :key="index" :value=category.id>{{ category.name  }}</option>
            
          </select>

          <div class="flex justify-end space-x-2">
            <button @click="showModal = false" class="bg-gray-400 px-4 py-2 rounded">
              Cancel
            </button>
            <button @click="addTask" class="bg-blue-500 text-white px-4 py-2 rounded">
              Add
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "../components/Nav-Bar.vue";
import api from "../axios.js";
export default {
  components: { Navbar },
  data() {
    return {
      tasks: [],
      categories: [],
      showModal: false,
      newTask: {
        title: "",
        description: "",
        due_date: "",
        category_id: 1
      },
      currentPage: 1,
      totalPages: 1,
      prevPageUrl: null,
      nextPageUrl: null,
    };
  },
  methods: {
    async addTask() {
      if (this.newTask.title.trim() && this.newTask.description.trim()) {
        try{
          await api.post('/tasks', this.newTask); 
          this.fetchTasks(this.currentPage);
        }
        catch(error) {
          console.log(error);
        }
        this.resetForm();
        this.showModal = false;
      }
    },
    async deleteTask(taskId) {
      try{
        await api.delete(`/tasks/${taskId}`); 
        this.fetchTasks(this.currentPage);
         
      }catch(error) {
        console.log(error);
      }
    },
    resetForm() {
      this.newTask = {
        title: "",
        description: "",
        dueDate: "",
        category: 1,
      };
    },
    async fetchTasks(page = 1) {
      try{
        const { data } =  await api.get(`/tasks?page=${page}`);
        this.tasks = data.data;
        this.currentPage = data.current_page;
        this.totalPages = data.last_page;
        this.prevPageUrl = data.prev_page_url;
        this.nextPageUrl = data.next_page_url;
      }catch(error) {
        console.log(error);
      } 
    }, 
    async fetchCategories() {
      try{
        const { data } =  await api.get('/categories');
        this.categories = data;
      }catch(error) {
        console.log(error);
      } 
    }
  },
  mounted() {
    this.fetchCategories();
    this.fetchTasks();
  }
};
</script>
