/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,ts}", // Agrega esto para incluir todos los archivos HTML y TypeScript en tu proyecto Angular
    "./node_modules/flowbite/**/*.js" // add this line
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('flowbite/plugin')
  ],
}
