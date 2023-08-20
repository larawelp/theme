module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.jsx",
        "./resources/**/*.ts",
        "./resources/**/*.tsx",
    ],
    safelist: [], // add any classes that are not found in the source code but are used in your templates here
    plugins: [
        require("@tailwindcss/typography"),
    ]
};
