/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/**/*.blade.php"],
    theme: {
        fontFamily: {
            display: ["Oswald"],
            body: ['"Open Sans"'],
        },
        extend: {},
    },
    plugins: [require("daisyui")],
    daisyui: {
        themes: false,
    },
};
