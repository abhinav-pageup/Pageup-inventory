module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      screens: {
        'max-2xl': { 'max': '1535px' },

        'max-xl': { 'max': '1279px' },

        'max-lg': { 'max': '1023px' },

        'max-md': { 'max': '767px' },

        'max-sm': { 'max': '639px' },
      }
    },
  },
  plugins: [],
}
