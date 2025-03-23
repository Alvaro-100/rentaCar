<script setup>

</script> 

<template>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Realizar Reserva</h1>

        <form action="/reserva" method="POST" class="max-w-md mx-auto">
            @csrf
            <div class="mb-4">
                <h2 class="text-lg font-semibold mb-2">
                    Información del Cliente
                </h2>
                <div class="mb-2">
                    <label
                        for="nombre"
                        class="block text-sm font-medium text-gray-700"
                        >Nombre</label
                    >
                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        class="mt-1 p-2 border rounded w-full"
                    />
                </div>
                <div class="mb-2">
                    <label
                        for="apellido"
                        class="block text-sm font-medium text-gray-700"
                        >Apellido</label
                    >
                    <input
                        type="text"
                        name="apellido"
                        id="apellido"
                        class="mt-1 p-2 border rounded w-full"
                    />
                </div>
                <div class="mb-2">
                    <label
                        for="fecha_nacimiento"
                        class="block text-sm font-medium text-gray-700"
                        >Fecha de Nacimiento</label
                    >
                    <input
                        type="date"
                        name="fecha_nacimiento"
                        id="fecha_nacimiento"
                        class="mt-1 p-2 border rounded w-full"
                    />
                </div>
                <div class="mb-2">
                    <label
                        for="direccion"
                        class="block text-sm font-medium text-gray-700"
                        >Dirección</label
                    >
                    <input
                        type="text"
                        name="direccion"
                        id="direccion"
                        class="mt-1 p-2 border rounded w-full"
                    />
                </div>
                <div class="mb-2">
                    <label
                        for="telefono"
                        class="block text-sm font-medium text-gray-700"
                        >Teléfono</label
                    >
                    <input
                        type="text"
                        name="telefono"
                        id="telefono"
                        class="mt-1 p-2 border rounded w-full"
                    />
                </div>
            </div>
            <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                Reservar
            </button>
        </form>
    </div>
</template>
