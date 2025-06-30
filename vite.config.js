import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
 

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
//todo::
//C: \Users\sharif\Desktop\property_project\property_project\resources\views\units\index.blade.php
// في هذا المكان دداير اعمل شيك على الفالديشن بتاعت لارفل 
// شوف الفالديشن حقت لارفل شيك عليها وشوف بيحصل شنو اذا ارتكبت هذا الخطأ


 