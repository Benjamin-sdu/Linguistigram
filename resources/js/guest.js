import TomSelect from "tom-select";
import 'tom-select/dist/css/tom-select.default.css';
import '../css/tom-select.css';
window.TomSelect = TomSelect


// Light/Dark Mode
function initThemeToggle() {
    const root = document.documentElement;

    // Get saved theme or system preference
    let theme = localStorage.getItem("theme");
    if (!theme) {
        theme = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }

    // Apply theme
    root.setAttribute("data-theme", theme);
    root.classList.toggle("dark", theme === "dark");
}


// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    initThemeToggle();
});