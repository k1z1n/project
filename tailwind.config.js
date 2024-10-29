/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    ],
    theme: {
        extend: {
            fontSize: {
                'h1': '2em', // примерно соответствует стандартному размеру <h1>
                'h2': '1.5em', // примерно соответствует стандартному размеру <h2>
                'h3': '1.17em', // примерно соответствует стандартному размеру <h3>
                'h4': '1em', // примерно соответствует стандартному размеру <h4>
                'h5': '0.83em', // примерно соответствует стандартному размеру <h5>
                'h6': '0.67em', // примерно соответствует стандартному размеру <h6>
            }
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
}

