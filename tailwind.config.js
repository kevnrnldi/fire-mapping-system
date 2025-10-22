/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
  ],
  theme: {
    extend: {
       fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                // Tambahkan baris ini
                serif: ['Lora', 'serif'], 
            },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}