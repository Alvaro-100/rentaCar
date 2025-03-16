<script setup>
import { ref } from 'vue';
import { usePage, Link } from "@inertiajs/vue3";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import '@fortawesome/fontawesome-free/css/all.min.css';
import { faBars, faHome, faBox, faClipboardList, faFileAlt, faLayerGroup, faTimes, faUserCircle, faSignOutAlt } from "@fortawesome/free-solid-svg-icons";
import axios from 'axios';
import { Toast } from 'primevue';

const page = usePage();
const user = page.props.auth.user;
const isSidebarOpen = ref(false);
const anioActual = ref(new Date().getFullYear());

const logout = async () => {
    try {
        await axios.post(route('logout'));
        window.location.href = route('login');
    } catch (err) {
        console.error("Error al cerrar la sesión", err);
    }
};
</script>

<template>
    <Toast />
    <div class="h-screen flex flex-col">
        <header class="bg-blue-700 text-white shadow-md fixed top-0 w-full z">
            <div class="px-6 py-4 flex justify-between items-center">
                <button @click="isSidebarOpen = !isSidebarOpen" class="text-white md:hidden">
                    <FontAwesomeIcon :icon="faBars" />
                </button>
                <div class="text-font-semibold">Administrador</div>
                <div class="flex items-center space-x-4">
                    <FontAwesomeIcon :icon="faUserCircle" class="text-2x1" />
                    <div>
                        <p class="text-sm font-semibold">{{ user.name }}</p>
                    </div>
                    <button @click="logout" class="text-white hover:bg-gray-200">
                        <FontAwesomeIcon :icon="faSignOutAlt" />
                    </button>
                </div>
            </div>
        </header>
        <div class="flex flex-1 pt-1">
            <aside :class="{
                '-translate-x-full md:translate-x-0': !isSidebarOpen,
                'translate-x-0': isSidebarOpen,
            }" class="fixed md:relative top-16 left-0 h-[calc(100vh-64px)] w-64 bg-blue-700 text-white transform transition-transform duration-300 aese-in-out shadow-lg z-40">
                <div class="px-4 text-xl font-semibold flex justify-between items-center ">
                    <span>Menu</span>
                    <button @click="isSidebarOpen = false">
                        <FontAwesomeIcon :icon="faBars" />
                    </button>
                </div>
                <nav class="mt-4">
                    <ul>
                        <li class="px-6 py-3 hover:bg-blue-500 flex items-center">
                            <Link :href="route('dashboard')" class="flex items-center w-full">
                                <FontAwesomeIcon :icon="faHome" class="mr-3" />
                                Inicio
                            </Link>
                        </li>
                        <li class="px-6 py-3 hover:bg-blue-500 flex items-center">
                            <Link :href="route('Marcas')" class="flex items-center w-full">
                                <FontAwesomeIcon :icon="faLayerGroup" class="mr-3" />
                                Marcas
                            </Link>
                        </li>
                        <li class="px-6 py-3 hover:bg-blue-500 flex items-center">
                            <Link :href="route('Vehiculo')" class="flex items-center w-full">
                                <FontAwesomeIcon :icon="faBox" class="mr-3" />
                                Vehiculos
                            </Link>
                        </li>
                        <li class="px-6 py-3 hover:bg-blue-500 flex items-center">
                            <a href="#" class="flex items-center w-full">
                                <FontAwesomeIcon :icon="faClipboardList" class="mr-3" />
                                detalles reservas
                            </a>
                        </li>
                        <li class="px-6 py-3 hover:bg-blue-500 flex items-center">
                            <a href="#" class="flex items-center w-full">
                                <FontAwesomeIcon :icon="faFileAlt" class="mr-3" />
                                Reportes
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>
            <main class="flex-1 p-6 overflow-auto border-t-gray-100">
                <slot></slot>
            </main>
        </div>
        <footer class="bg bg-gray-700 text-white text-center py-3 shadow-md bottom-0 left-0 w-full">
            &copy; {{ new Date().getFullYear() }} Todos los derechos reservados
        </footer>
    </div>
</template>