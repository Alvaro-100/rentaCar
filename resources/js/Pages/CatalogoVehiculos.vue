<script setup>
import { computed, onMounted, ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import axios from "axios";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faSearch, faBars } from "@fortawesome/free-solid-svg-icons";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";

const page = usePage();
const user = page.props.auth.user;

const vehiculos = ref([]);
const url = "http://127.0.0.1:8000/api/vehiculos";
const searchQuery = ref("");
const isOpen = ref(false); // Inicializa isOpen

const fetchCarros = async () => {
    try {
        const response = await axios.get(url);
        vehiculos.value = response.data;
        console.log(response.data);
    } catch (error) {
        console.error(
            "Error fetching vehiculos:",
            error.response ? error.response.data : error.message
        );
    }
};

onMounted(() => {
    fetchCarros();
});

const filteredVehiculos = computed(() => {
    const search = searchQuery.value.toLowerCase();
    return vehiculos.value.filter((carros) => {
        return (
            carros.marca.nombre.toLowerCase().includes(search) ||
            carros.modelo.toLowerCase().includes(search)
        );
    });
});

const logout = async () => {
    try {
        await axios.post("/logout");
        window.location.href = "/login";
    } catch (err) {
        console.error("Error al cerrar la sesión", err);
    }
};
const alquilar = () => {
    try {
        if(user){
           window.location.href = 'formCliente';
        }else{
            window.alert("debes estar registrado para alqular")
            window.location.href = '/login'
        }
    } catch (err) {
        console.error("Error al cerrar la sesión", err);
    }
};
</script>

<template>
    <nav
        class="fixed top-0 left-0 w-full bg-blue-900 text-yellow-500 shadow-md z-50"
    >
        <div
            class="container mx-auto flex flex-wrap justify-between items-center p-4"
        >
            <h1 class="text-xl font-bold text-yellow-500">RentaCart</h1>
            <!-- Botón menú hamburguesa -->
            <button
                @click="isOpen = !isOpen"
                class="lg:hidden text-white focus:outline-none"
            >
                <FontAwesomeIcon :icon="faBars" class="w-6 h-6 text-white" />
            </button>

            <!-- Menú principal -->
            <div
                :class="{ hidden: !isOpen, block: isOpen }"
                class="w-full lg:w-auto lg:flex lg:items-center"
            >
                <div
                    v-if="!user"
                    class="flex flex-col lg:flex-row lg:space-x-4 text-center mt-4 lg:mt-0"
                >
                    <a href="/login" class="text-white py-2 lg:py-0"
                        >Iniciar Sesión</a
                    >
                    <a href="/register" class="text-white py-2 lg:py-0"
                        >Registrarse</a
                    >
                </div>

                <div
                    v-else
                    class="flex flex-col lg:flex-row lg:items-center lg:space-x-4 text-center mt-4 lg:mt-0"
                >
                    <span class="py-2 lg:py-0 text-white">{{ user.name }}</span>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="bg-red-500 px-4 py-2 rounded w-full lg:w-auto"
                        >Cerrar Sesión</Link
                    >
                </div>
            </div>
        </div>
    </nav>
    <div class="py-16 bg-gray-100">
        <div class="mb-4 flex items-center justify-center">
            <InputText
                v-model="searchQuery"
                placeholder="Buscar..."
                class="mr-2 w-1/2"
            />
            <Button label="Buscar" class="p-button-lg bg-blue-500 text-white">
                <template #icon>
                    <FontAwesomeIcon :icon="faSearch" />
                </template>
            </Button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div
                v-for="vehiculo in filteredVehiculos"
                :key="vehiculo.id"
                class="border rounded-md p-4 flex flex-col lg:flex-row bg-white shadow-lg"
            >
                <div class="w-full lg:w-1/2">
                    <div class="w-full h-48 mb-2 lg:mb-0 lg:mr-4">
                        <Swiper
                            v-if="vehiculo.imagenes.length"
                            :slides-per-view="1"
                            :space-between="10"
                            class="mySwiper"
                        >
                            <SwiperSlide
                                v-for="img in vehiculo.imagenes"
                                :key="img.id"
                            >
                                <img
                                    :src="img.url"
                                    :alt="vehiculo.modelo"
                                    class="w-full h-full object-cover"
                                />
                            </SwiperSlide>
                        </Swiper>
                    </div>
                    <div class="text-center mt-2">
                        <h3 class="text-lg font-semibold text-blue-900">
                            {{ vehiculo.marca.nombre }} {{ vehiculo.modelo }}
                        </h3>
                    </div>
                </div>
                <div class="flex flex-col justify-center w-full lg:w-1/2">
                    <div class="mb-2">
                        <ul class="list-none pl-0 mb-2">
                            <li class="text-gray-700">
                                Descripción: {{ vehiculo.descripcion }}
                            </li>
                            <li class="text-gray-700">
                                Aire acondicionado:
                                {{ vehiculo.aire_acondicionado ? "Sí" : "No" }}
                            </li>
                            <li class="text-gray-700">
                                Conexión Bluetooth:
                                {{ vehiculo.conexion_bluetooth ? "Sí" : "No" }}
                            </li>
                            <li class="text-gray-700">
                                Disponibilidad: {{ vehiculo.disponibilidad }}
                            </li>
                        </ul>
                    </div>
                    <div class="mb-2">
                        <p class="text-lg font-bold text-blue-900">
                            Precio: ${{ vehiculo.precio }} por día
                        </p>
                    </div>
                    <Button
                        label="Alquilar"
                        class="w-full bg-yellow-500 text-blue-900"
                        @click="alquilar()"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
