module.exports = {
  content: [
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    "./vendor/livewire/flux-pro/stubs/**/*.blade.php",
    "./vendor/livewire/flux/stubs/**/*.blade.php",
  ],
  plugins: [require("daisyui")],
  daisyui: {
    themes: ["night", "cupcake"],
  },
  darkMode: 'media', // or 'media' if you prefer automatic dark mode based on user preferences
  // theme: {
  //   extend: {},
  // },
  // ...
  theme: {
    fontFamily: {
        sans: ['Inter', 'sans-serif'],
    },
  },

}
