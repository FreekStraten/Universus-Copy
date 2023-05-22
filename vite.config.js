import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/chooseWinner.js',
                'resources/js/competitionList.js',
                'resources/js/notificationNav.js',
                'resources/js/competitionDetails.js',
            ],
            refresh: true,
        }),
    ],
});
