module.exports = {
  content: [
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  plugins: [require("daisyui")],
  daisyui: {
  },
  darkMode: 'class', // or 'media' if you prefer automatic dark mode based on user preferences
  // theme: {
  //   extend: {},
  // },
  // ...
}
