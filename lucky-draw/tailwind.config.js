/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        ink: {
          950: "#05070f",
          900: "#0a0e1c",
          800: "#0f1528",
          700: "#182042",
        },
        gold: {
          100: "#fbf0cf",
          200: "#f6e2a6",
          300: "#f0d078",
          400: "#e8b94a",
          500: "#d9a132",
          600: "#b8801f",
        },
      },
      fontFamily: {
        display: ['"Cormorant Garamond"', "Georgia", "serif"],
        sans: ["Montserrat", "ui-sans-serif", "system-ui", "sans-serif"],
      },
    },
  },
  plugins: [],
};
