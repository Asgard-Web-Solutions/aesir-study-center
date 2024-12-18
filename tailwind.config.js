import colors from 'tailwindcss/colors';

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
  darkMode: 'media',
  theme: {
    fontFamily: {
        sans: ['Inter', 'sans-serif'],
    },
  },
}

export default {
    theme: {
        extend: {
            colors: {
                // Re-assign Flux's gray of choice...
                zinc: colors.slate,

                // Accent variables are defined in resources/css/app.css...
                accent: {
                    DEFAULT: 'var(--color-accent)',
                    content: 'var(--color-accent-content)',
                    foreground: 'var(--color-accent-foreground)',
                },
            },
        },
    },
};
