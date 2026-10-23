import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                navy: {
                    50: '#f5f9fe',
                    100: '#eaf1fd',
                    200: '#cfe1fa',
                    300: '#9ec5f4',
                    400: '#6ba8ec',
                    500: '#3b90e4',
                    600: '#2577c9',
                    700: '#1b64ae',
                    800: '#145394',
                    850: '#104780',
                    900: '#0c3b6d',
                    950: '#07264a',
                },
                accent: {
                    light: '#38bdf8',
                    DEFAULT: '#0ea5e9',
                    dark: '#0284c7',
                },
                gold: {
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['Outfit', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};
