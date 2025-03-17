<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";
import { FilterMatchMode } from "@primevue/core/api";
import { Toast } from "primevue";
import { useToast } from "primevue/usetoast";
import axios from "axios";

onMounted(() => {
    fetchVehiculos();
    fetchMarcas();
});

const toast = useToast();
const dt = ref();
const vehiculos = ref([]);
const marcas = ref([]);
const dialog = ref(false);
const deleteVehiculoDialog = ref(false);
const vehiculo = ref({});
const selectedVehiculos = ref();
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});
const submitted = ref(false);
const urlVehiculos = "http://127.0.0.1:8000/api/vehiculos";
const urlMarcas = "http://127.0.0.1:8000/api/marcas";
const imagenes = ref([]);

const openNew = () => {
    vehiculo.value = {
        matricula: "",
        modelo: "",
        precio: "",
        descripcion: "",
        disponibilidad: "disponible",
        aire_acondicionado: false,
        conexion_bluetooth: false,
        marca: { id: "" },
        imagenes: [],
    };
    submitted.value = false;
    dialog.value = true;
    imagenes.value = [];
};

const hideDialog = () => {
    dialog.value = false;
    submitted.value = false;
    imagenes.value = [];
};

const fetchVehiculos = async () => {
    try {
        const response = await axios.get(urlVehiculos);
        vehiculos.value = response.data;
    } catch (err) {
        console.error("Error al obtener los Vehículos", err);
    }
};

const fetchMarcas = async () => {
    try {
        const response = await axios.get(urlMarcas);
        marcas.value = response.data;
    } catch (err) {
        console.error("Error al obtener las Marcas", err);
    }
};

const handleImageUpload = (event) => {
    imagenes.value = [...event.target.files];
};

const saveOrUpdate = async () => {
    submitted.value = true;

    if (vehiculo.value.matricula?.trim()) {
        const formData = new FormData();
        formData.append(
            "vehiculo",
            JSON.stringify({
                matricula: vehiculo.value.matricula,
                modelo: vehiculo.value.modelo,
                precio: vehiculo.value.precio,
                descripcion: vehiculo.value.descripcion,
                disponibilidad: vehiculo.value.disponibilidad,
                aire_acondicionado: vehiculo.value.aire_acondicionado,
                conexion_bluetooth: vehiculo.value.conexion_bluetooth,
                marca: { id: vehiculo.value.marca.id },
            })
        );
        imagenes.value.forEach((img) => {
            formData.append("imagenes[]", img);
        });

        try {
            let response;
            if (vehiculo.value.id) {
                formData.append("_method", "PUT");
                response = await axios.post(
                    `${urlVehiculos}/${vehiculo.value.id}`,
                    formData,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );
            } else {
                response = await axios.post(urlVehiculos, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
            }

            if (response.status === 200 || response.status === 201) {
                fetchVehiculos();
                toast.add({
                    severity: "success",
                    summary: "Successful",
                    detail: response.data.message,
                    life: 3000,
                });
                dialog.value = false;
                vehiculo.value = {};
                imagenes.value = [];
            }
        } catch (err) {
            if (err.response.status === 409) {
                toast.add({
                    severity: "warn",
                    summary: "Advertencia",
                    detail: `${err.response.data.message}, Ya existe este vehículo`,
                    life: 3000,
                });
            }
            console.log(err);
        }
    }
};

const editVehiculo = (item) => {
    vehiculo.value = { ...item, marca: { id: item.marca.id } };
    dialog.value = true;
};

const confirmDeleteVehiculo = (v) => {
    vehiculo.value = v;
    deleteVehiculoDialog.value = true;
};

const deleteVehiculo = async () => {
    try {
        const response = await axios.delete(
            `${urlVehiculos}/${vehiculo.value.id}`
        );
        if (response.status === 200) {
            fetchVehiculos();
            deleteVehiculoDialog.value = false;
            vehiculo.value = {};
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: response.data.message,
                life: 3000,
            });
        }
    } catch (err) {
        if (err.response.status === 500 || err.response.status === 409) {
            toast.add({
                severity: "error",
                summary: "Error",
                detail: err.response.data.err,
                life: 3000,
            });
        } else {
            console.log("Error al eliminar el vehículo");
        }
    }
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const dialogTitle = computed(() =>
    vehiculo.value.id ? "Edición de Vehículos" : "Registro de Vehículos"
);
const btnTitle = computed(() => (vehiculo.value.id ? "Actualizar" : "Guardar"));
</script>

<template>
    <Head title="Vehículos" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                CRUD de Vehículos
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div>
                    <div class="card">
                        <Toolbar class="mb-6">
                            <template #start>
                                <Button
                                    label="Nuevo"
                                    icon="pi pi-plus"
                                    class="mr-2"
                                    @click="openNew"
                                />
                            </template>
                            <template #end>
                                <Button
                                    label="Exportar"
                                    icon="pi pi-upload"
                                    severity="secondary"
                                    @click="exportCSV($event)"
                                />
                            </template>
                        </Toolbar>

                        <DataTable
                            ref="dt"
                            v-model:selection="selectedVehiculos"
                            :value="vehiculos"
                            dataKey="id"
                            :paginator="true"
                            :rows="10"
                            :filters="filters"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            :rowsPerPageOptions="[5, 10, 25]"
                            currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} vehículos"
                        >
                            <template #header>
                                <div
                                    class="flex flex-wrap gap-2 items-center justify-between"
                                >
                                    <h4 class="m-0">Gestión de Vehículos</h4>
                                    <IconField>
                                        <InputIcon>
                                            <i class="pi pi-search" />
                                        </InputIcon>
                                        <InputText
                                            v-model="filters['global'].value"
                                            placeholder="Search..."
                                        />
                                    </IconField>
                                </div>
                            </template>
                            <Column
                                field="matricula"
                                header="Matrícula"
                                sortable
                                style="min-width: 12rem"
                            ></Column>
                            <Column
                                field="modelo"
                                header="Modelo"
                                sortable
                                style="min-width: 12rem"
                            ></Column>
                            <Column
                                field="precio"
                                header="Precio"
                                sortable
                                style="min-width: 10rem"
                            ></Column>
                            <Column
                                field="descripcion"
                                header="Descripción"
                                sortable
                                style="min-width: 16rem"
                            ></Column>
                            <Column
                                field="disponibilidad"
                                header="Disponibilidad"
                                sortable
                                style="min-width: 12rem"
                            ></Column>
                            <Column
                                field="aire_acondicionado"
                                header="Aire Acondicionado"
                                sortable
                                style="min-width: 12rem"
                            >
                                <template #body="slotProps">
                                    {{
                                        slotProps.data.aire_acondicionado
                                            ? "Sí"
                                            : "No"
                                    }}
                                </template>
                            </Column>
                            <Column
                                field="conexion_bluetooth"
                                header="Conexión Bluetooth"
                                sortable
                                style="min-width: 12rem"
                            >
                                <template #body="slotProps">
                                    {{
                                        slotProps.data.conexion_bluetooth
                                            ? "Sí"
                                            : "No"
                                    }}
                                </template>
                            </Column>
                            <Column
                                field="marca.nombre"
                                header="Marca"
                                sortable
                                style="min-width: 12rem"
                            ></Column>
                            <Column header="Imágenes" style="min-width: 16rem">
                                <template #body="slotProps">
                                    <div class="flex flex-wrap gap-2">
                                        <img
                                            v-for="img in slotProps.data
                                                .imagenes"
                                            :key="img.id"
                                            :src="
                                                '/images/vehiculo/' + img.nombre
                                            "
                                            alt="Imagen del vehículo"
                                            class="w-16 h-12 object-cover"
                                        />
                                    </div>
                                </template>
                            </Column>
                            <Column
                                :exportable="false"
                                style="min-width: 12rem"
                            >
                                <template #body="slotProps">
                                    <Button
                                        icon="pi pi-pencil"
                                        outlined
                                        rounded
                                        class="mr-2"
                                        @click="editVehiculo(slotProps.data)"
                                    />
                                    <Button
                                        icon="pi pi-trash"
                                        outlined
                                        rounded
                                        severity="danger"
                                        @click="
                                            confirmDeleteVehiculo(
                                                slotProps.data
                                            )
                                        "
                                    />
                                </template>
                            </Column>
                        </DataTable>
                    </div>

                    <Dialog
                        v-model:visible="dialog"
                        :style="{ width: '600px' }"
                        :header="dialogTitle"
                        :modal="true"
                    >
                        <div class="flex flex-col gap-4">
                            <div>
                                <label
                                    for="matricula"
                                    class="block font-bold mb-2"
                                    >Matrícula</label
                                >
                                <InputText
                                    id="matricula"
                                    v-model.trim="vehiculo.matricula"
                                    required="true"
                                    autofocus
                                    :invalid="submitted && !vehiculo.matricula"
                                />
                                <small
                                    v-if="submitted && !vehiculo.matricula"
                                    class="text-red-500"
                                    >Matrícula es requerida.</small
                                >
                            </div>
                            <div>
                                <label for="modelo" class="block font-bold mb-2"
                                    >Modelo</label
                                >
                                <InputText
                                    id="modelo"
                                    v-model.trim="vehiculo.modelo"
                                    required="true"
                                    :invalid="submitted && !vehiculo.modelo"
                                />
                                <small
                                    v-if="submitted && !vehiculo.modelo"
                                    class="text-red-500"
                                    >Modelo es requerido.</small
                                >
                            </div>
                            <div class="col-span-6">
                                <label for="precio">Precio</label>
                                <InputNumber
                                    id="precio"
                                    v-model="vehiculo.precio"
                                    mode="currency"
                                    currency="USD"
                                    locale="en-US"
                                    fluid
                                    :class="{
                                        'p-invalid':
                                            submitted && !vehiculo.precio,
                                    }"
                                />
                                <small
                                    class="p-error text-red-500"
                                    v-if="submitted && !vehiculo.precio"
                                    >Precio es requerido.</small
                                >
                            </div>
                            <div>
                                <label
                                    for="descripcion"
                                    class="block font-bold mb-2"
                                    >Descripción</label
                                >
                                <Textarea
                                    id="descripcion"
                                    v-model.trim="vehiculo.descripcion"
                                    rows="3"
                                />
                            </div>
                            <div>
                                <label
                                    for="disponibilidad"
                                    class="block font-bold mb-2"
                                    >Disponibilidad</label
                                >
                                <Dropdown
                                    id="disponibilidad"
                                    v-model="vehiculo.disponibilidad"
                                    :options="['disponible', 'no_disponible']"
                                />
                            </div>
                            <div class="flex gap-4">
                                <div>
                                    <label
                                        for="aire_acondicionado"
                                        class="block font-bold mb-2"
                                        >Aire Acondicionado</label
                                    >
                                    <Checkbox
                                        id="aire_acondicionado"
                                        v-model="vehiculo.aire_acondicionado"
                                        :binary="true"
                                    />
                                </div>
                                <div>
                                    <label
                                        for="conexion_bluetooth"
                                        class="block font-bold mb-2"
                                        >Conexión Bluetooth</label
                                    >
                                    <Checkbox
                                        id="conexion_bluetooth"
                                        v-model="vehiculo.conexion_bluetooth"
                                        :binary="true"
                                    />
                                </div>
                            </div>
                            <div class="col-span-6">
                                <label for="marca">Marca</label>
                                <Select
                                    v-model="vehiculo.marca"
                                    :options="marcas"
                                    option-label="nombre"
                                    class="w-full"
                                />
                                <small
                                    v-if="submitted && !vehiculo.marca"
                                    class="text-red-500"
                                    >Seleccione una marca</small
                                >
                            </div>
                            <div>
                                <label
                                    for="imagenes"
                                    class="block font-bold mb-2"
                                    >Imágenes</label
                                >
                                <input
                                    type="file"
                                    id="imagenes"
                                    multiple
                                    @change="handleImageUpload"
                                />
                            </div>
                        </div>

                        <template #footer>
                            <Button
                                label="Cancelar"
                                icon="pi pi-times"
                                text
                                @click="hideDialog"
                            />
                            <Button
                                :label="btnTitle"
                                icon="pi pi-check"
                                @click="saveOrUpdate"
                            />
                        </template>
                    </Dialog>

                    <Dialog
                        v-model:visible="deleteVehiculoDialog"
                        :style="{ width: '450px' }"
                        header="Confirmación"
                        :modal="true"
                    >
                        <div class="flex items-center gap-4">
                            <i class="pi pi-exclamation-triangle !text-3xl" />
                            <span v-if="vehiculo"
                                >Seguro(a) de eliminar el vehículo con matrícula
                                <b>{{ vehiculo.matricula }}</b
                                >?</span
                            >
                        </div>
                        <template #footer>
                            <Button
                                label="No"
                                icon="pi pi-times"
                                text
                                @click="deleteVehiculoDialog = false"
                            />
                            <Button
                                label="Sí"
                                icon="pi pi-check"
                                @click="deleteVehiculo"
                            />
                        </template>
                    </Dialog>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
