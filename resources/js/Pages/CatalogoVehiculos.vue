<script setup>
import { computed, onMounted, ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import axios from "axios";
import Swal from "sweetalert2";

const page = usePage();
const user = page.props.auth.user;

const vehiculos = ref([]);
const urlBase = "http://localhost:8000/api/";

</script>

<template>
  <nav class="fixed top-0 left-0 w-full bg-black text-green-600 shadow-md z-50">
        <div class="container mx-auto flex flex-wrap justify-between items-center p-4">
            <h1 class="text-xl font-bold text-green-600">RentaCart</h1>
            <!-- Botón menú hamburguesa -->
            <button @click="isOpen = !isOpen" class="lg:hidden text-white focus:outline-none">
                <FontAwesomeIcon :icon="faBars" class="w-6 h-6 text-white" />
            </button>

            <!-- Menú principal -->
            <div :class="{'hidden': !isOpen, 'block': isOpen}" class="w-full lg:w-auto lg:flex lg:items-center">
            <div v-if="!user" class="flex flex-col lg:flex-row lg:space-x-4 text-center mt-4 lg:mt-0">
                <a href="/login" class="text-white py-2 lg:py-0">Iniciar Sesión</a>
                <a href="/register" class="text-white py-2 lg:py-0">Registrarse</a>
            </div>

            <div v-else class="flex flex-col lg:flex-row lg:items-center lg:space-x-4 text-center mt-4 lg:mt-0">
                <span class="py-2 lg:py-0">{{ user.name }}</span>
                <Link :href="route('logout')" method="post" as="button" class="bg-red-500 px-4 py-2 rounded w-full lg:w-auto">Cerrar Sesión</Link>
            </div>
            </div>
        </div>
    </nav>
    
</template>
