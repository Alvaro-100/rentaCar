<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const urlBase = 'http://localhost:8000/api/marcas';
const marcas = ref([]);
const nuevaMarca = ref({ nombre: '' });
const botonTexto = ref('Agregar Marca');
const mostrarModal = ref(false);
const buscarMarca = ref('');
const mostrarConfirmacion = ref(false);
const marcaAEliminar = ref(null);

onMounted(() => {
    fetchMarcas();
});

const fetchMarcas = async () => {
    try {
        const response = await axios.get(urlBase);
        marcas.value = response.data; 
    } catch (error) {
        console.error('Error al obtener las marcas:', error);
    }
};

const addMarca = async () => {
    try {
        const response = await axios.post(urlBase, nuevaMarca.value);
        marcas.value.push(response.data.marca);
        nuevaMarca.value.nombre = ''; 
        mostrarModal.value = false;
        botonTexto.value = 'Agregar Marca';
    } catch (error) {
        console.error('Error al registrar la marca:', error);
    }
};

const confirmDeleteMarca = (id) => {
    marcaAEliminar.value = id;
    mostrarConfirmacion.value = true;
};

const deleteMarca = async () => {
    try {
        await axios.delete(`${urlBase}/${marcaAEliminar.value}`);
        marcas.value = marcas.value.filter(marca => marca.id !== marcaAEliminar.value);
        mostrarConfirmacion.value = false;
    } catch (error) {
        console.error('Error al eliminar la marca:', error);
    }
};

const marcasFiltradas = computed(() => {
    return marcas.value.filter(marca => marca.nombre.toLowerCase().includes(buscarMarca.value.toLowerCase()));
});

const openModal = () => {
    nuevaMarca.value = { nombre: '' };
    botonTexto.value = 'Agregar Marca';
    mostrarModal.value = true;
};

const closeModal = () => {
    mostrarModal.value = false;
};

</script>

<template>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4 flex items-center gap-2">
        <i class="pi pi-tags text-blue-500"></i>
        Gestión de Marcas de Vehículos
      </h1>

      <div class="mb-4 flex gap-2">
        <input v-model="buscarMarca" type="text" placeholder="Buscar marca..." 
               class="border rounded-lg p-2 w-full"/>
        <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center gap-2">
          <i class="pi pi-search"></i> Buscar
        </button>
      </div>

      <button @click="openModal" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center gap-2 mb-4">
        <i class="pi pi-plus"></i> {{ botonTexto }}
      </button>

      <table class="w-full border border-gray-300 rounded-lg">
        <thead>
          <tr class="bg-gray-200">
            <th class="border px-4 py-2">Nombre</th>
            <th class="border px-4 py-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="marca in marcasFiltradas" :key="marca.id" class="odd:bg-white even:bg-gray-100">
            <td class="border px-4 py-2">{{ marca.nombre }}</td>
            <td class="border px-4 py-2">
              <button @click="confirmDeleteMarca(marca.id)" 
                      class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 flex items-center gap-2">
                <i class="fas fa-trash"></i> Eliminar
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Modal de Confirmación -->
      <div v-if="mostrarConfirmacion" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-10">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
          <h2 class="text-xl font-bold mb-4">Confirmar Eliminación</h2>
          <p>Seguro(a) de que deseas eliminar esta marca?</p>
          <div class="flex justify-end gap-4 mt-4">
            <button @click="mostrarConfirmacion = false" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
              Cancelar
            </button>
            <button @click="deleteMarca" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
              Eliminar
            </button>
          </div>
        </div>
      </div>

      <!-- Modal de Agregar Marca -->
      <div v-if="mostrarModal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-10">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
          <h2 class="text-xl font-bold mb-4">{{ botonTexto }}</h2>
          <form @submit.prevent="addMarca" class="flex flex-col gap-4">
            <input v-model="nuevaMarca.nombre" type="text" placeholder="Nombre de la marca" required 
                   class="border rounded-lg p-2"/>
            <div class="flex justify-end gap-4">
              <button type="button" @click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Cancelar
              </button>
              <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                {{ botonTexto }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
</template>