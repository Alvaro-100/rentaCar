<script setup>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
const page = usePage();
const user = page.props.auth.user;

const logout = async () => {
    try {
        await axios.post("/logout");
        window.location.href = "/login";
    } catch (err) {
        console.error("Error al cerrar la sesión", err);
    }
};
</script>

<template>
    <div>
        <header
            class="bg-blue-700 text-white shadow-md fixed top-0 w-full z-50"
        >
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center"
            >
                <div class="flex items-center">
                    <h1 class="ml-4 text-xl font-bold">Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-semibold">{{ user.name }}</span>
                    <button
                        @click="logout"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    >
                        Cerrar Sesión
                    </button>
                </div>
            </div>
        </header>
        <main class="pt-16">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <slot></slot>
            </div>
        </main>
    </div>
</template>

