import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'SF Pro Display', 'SF Pro Text', ...defaultTheme.fontFamily.sans],
                display: ['Inter', 'SF Pro Display', ...defaultTheme.fontFamily.sans],
            },
            letterSpacing: {
                tightest: '-0.04em',
            },
            colors: {
                ink: {
                    50:  '#f7f7f7',
                    100: '#ededed',
                    200: '#d9d9d9',
                    300: '#b8b8b8',
                    400: '#8a8a8a',
                    500: '#5e5e5e',
                    600: '#3f3f3f',
                    700: '#262626',
                    800: '#171717',
                    900: '#0a0a0a',
                    950: '#050505',
                },
                'brand-blue': {
                    50:  '#eef0fb',
                    100: '#dadefa',
                    200: '#b4bcf3',
                    300: '#8893ea',
                    400: '#5e6cdf',
                    500: '#3b48cf',
                    600: '#1e0fb7',
                    700: '#180b97',
                    800: '#130a78',
                    900: '#0d075a',
                    950: '#080339',
                },
                'brand-orange': {
                    50:  '#fff4ec',
                    100: '#ffe5d2',
                    200: '#fec7a4',
                    300: '#fda071',
                    400: '#fa7e44',
                    500: '#f37021',
                    600: '#d95711',
                    700: '#b3420f',
                    800: '#8d3614',
                    900: '#732d13',
                },
            },
            boxShadow: {
                'soft':  '0 1px 2px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06)',
                'lift':  '0 4px 12px rgba(0,0,0,0.06), 0 24px 48px rgba(0,0,0,0.08)',
            },
            transitionTimingFunction: {
                'apple': 'cubic-bezier(0.22, 1, 0.36, 1)',
            },
        },
    },
    plugins: [forms, typography],
};
