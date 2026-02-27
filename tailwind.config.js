// 現在はapp.cssがメインのため使われていない？
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.jsx",
  ],
//  検索されなくとも使う(例:Laravelから渡すとき)
  safelist: [
    'bg-lime-200',
    'bg-blue-200',
    'bg-red-200',
    'bg-yellow-200',
  ],
  theme: {
    extend: {
      screens: {

      },
    },
  },
}
