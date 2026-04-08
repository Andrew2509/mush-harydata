module.exports = {
  darkMode: 'class',
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        // Game Theme
        primary: "#F5C754",
        secondary: "#0EA5E9",
        accent: "#22d3ee",
        "background-dark": "#060B18",
        "card-dark": "#111827",
        "card-border": "rgba(6, 182, 212, 0.2)",
        "review-bg": "#1e293b",
        "review-header": "#0f172a",
        "accent-cyan": "#22D3EE"
      },
      fontFamily: {
        display: ["Rajdhani", "Plus Jakarta Sans", "Inter", "sans-serif"],
        body: ["Inter", "Plus Jakarta Sans", "sans-serif"],
      },
      animation: {
        'pulse-glow': 'pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'float': 'float 6s ease-in-out infinite',
        'rain': 'rain 1s linear infinite',
      },
      keyframes: {
        'pulse-glow': {
          '0%, 100%': { opacity: '1', boxShadow: '0 0 20px rgba(6, 182, 212, 0.5)' },
          '50%': { opacity: '.8', boxShadow: '0 0 5px rgba(6, 182, 212, 0.2)' },
        },
        'float': {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        'rain': {
          '0%': { transform: 'translateY(-100vh)' },
          '100%': { transform: 'translateY(100vh)' }
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/container-queries'),
  ],
}
