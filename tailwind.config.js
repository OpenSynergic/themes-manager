const colors = require("tailwindcss/colors");

module.exports = {
    content: ["./resources/views/**/*.blade.php"],
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: colors.yellow,
                success: colors.green,
                warning: colors.amber,
            },
        },
    },
    darkMode: "class",
    // important: ".filament-themes-manager",
    plugins: [],
    corePlugins: {
        preflight: false,
    },
};
