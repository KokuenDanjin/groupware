import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/calendar/calendar.js',
                'resources/js/pages/schedule/schedule.js',
                'resources/js/components/calendar/index.js',
                'resources/js/components/calendar/timebase-schedule.js',
                'resources/js/components/schedule/index.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            usePolling: true,
        },
    },
});
