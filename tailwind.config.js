/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/**/*.php",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    DEFAULT: '#C8102E',
                    dark: '#A50D25',
                    light: '#FDECEF',
                },
                accent: {
                    DEFAULT: '#F4C400',
                    light: '#FFF8D8',
                },
                ink: {
                    DEFAULT: '#18181B',
                    secondary: '#52525B',
                    muted: '#71717A',
                },
                line: {
                    DEFAULT: '#E4E4E7',
                    light: '#F0F0F1',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                mono: ['ui-monospace', 'SF Mono', 'Monaco', 'monospace'],
            },
        },
    },
    plugins: [],
};