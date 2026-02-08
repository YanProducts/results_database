// 現在はapp.cssがメインのため使われていない？
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.jsx",
  ],
  theme: {
    extend: {
      screens: {

      },
    },
  },
}
