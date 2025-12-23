import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    daisyui:{
        themes:[
            {light:
                {
                    primary: "#60a5fa",
                    secondary: "#22d3ee",
                    accent: "#fbbf24",
                    neutral: "#1f2937",

                    "base-100": "#f8fafc",
                    "base-200": "#e2e8f0",
                    "base-300": "#94a3b8",
                    "base-content": "#1e293b",
                },
            }, 
            {
                dark:
                {
                    primary: "#60a5fa",
                    secondary: "#22d3ee",
                    accent: "#fbbf24",
                    neutral: "#1f2937",

                    "base-100": "#0c1220",
                    "base-200": "#111827",
                    "base-300": "#1f2937",
                    "base-content": "#e5e7eb",
                },
            }
            ]
    },

    plugins: [
        forms,
        require('daisyui'),
    ],
};
