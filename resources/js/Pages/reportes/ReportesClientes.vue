<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

//variable a utilizar 

const fechaInicio = ref("")
const fechaFinal = ref("")
const error = ref("")

//funcion para enviar parámetros al reporte

const generarReportes = () => {
    if (!fechaInicio.value || !fechaFinal.value) {
        error.value = "Selecione todos los parámetros para generar el reporte";
        return;
    }
    error.value = '';
   // router(`/reportes/ordene?fechaInicio=${fechaInicio.value}&fechaFinal=${fechaFinal}&estado$=${estado}`)

    const url = `/reportes/clientes?fechaInicio=${fechaInicio.value}&fechaFinal=${fechaFinal.value}`
    window.open(url, "_blank");
}
</script>

<template>

    <Head title="Reporte de ordenes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12 text-black">
            <h2 class="text-xl font-bold mb-4">Generar reportes de ordenes</h2>
            <div class="text-xl font-bold">
                <div>
                    <label for="" class="text-white">Fecha Inico</label>
                    <input type="date" v-model="fechaInicio" class="w-full p-2 ">
                </div>
                <div>
                    <label for="" class="text-white">Fecha Final</label>
                    <input type="date" v-model="fechaFinal" class="w-full p-2 ">
                </div>
                <div>
                    <p v-if="error" class="text-red-600 text-sm mt-2">{{ error }}</p>
                </div>
                <button @click="generarReportes"
                    class="px-6 mt-4 bg-blue-600 hover:bg-blue-700 py-2 rounded-lg shadow-md">Generar Reporte</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
