import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/bulkupload.js', // will put Piotr's bulk upload code here, when it no longer has @ blade directives
                'resources/css/filament/app/theme.css',
            ],
            refresh: true,
        }),
    ],
});
