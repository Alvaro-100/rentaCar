import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

//importaciones ´para swetalert2 y primevue
import Swal from 'sweetalert2';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import Swiper from 'swiper';
import { SwiperSlide } from 'vue-awesome-swiper';

import { ToastService } from 'primevue';
import 'primeicons/primeicons.css';
//importaciones de los componentes de PrimeVue
import Toolbar  from 'primevue/toolbar';
import DataTable  from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog  from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import IconField from 'primevue/iconfield';
import Button from 'primevue/button';
import FileUpload from 'primevue/fileupload';
import Textarea from 'primevue/textarea';
import InputNumber from 'primevue/inputnumber';
import AutoComplete from 'primevue/autocomplete';
import Select from 'primevue/select';
import Checkbox from 'primevue/checkbox';
import { useToast } from 'primevue';


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            //configuracion global de sweetalert2
            app.config.globalProperties.$swal = Swal;
            app.use(PrimeVue, {
                theme: {
                    preset: Aura
                }
            });
            app.use(ToastService);
            app.component('Toolbar',Toolbar);
            app.component('DataTable',DataTable);
            app.component('Column',Column);
            app.component('Dialog',Dialog );
            app.component('InputText',InputText);
            app.component('InputIcon',InputIcon);
            app.component('IconField ',IconField);
            app.component('Button',Button);
            app.component('Textarea',Textarea);
            app.component('InputNumber',InputNumber);
            app.component('FileUpload',FileUpload);
            app.component('AutoComplete',AutoComplete);
            app.component('Select',Select);
             app.component('Checkbox',Checkbox);
            app.component('Toast', useToast);
            
            app.mount(el);
            return app;
    },
    progress: {
        color: '#4B5563',
    },
});