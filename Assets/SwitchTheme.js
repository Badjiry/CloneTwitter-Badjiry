document.addEventListener("DOMContentLoaded", () => {
    const themeToggle = document.getElementById("theme-toggle");
    const html = document.documentElement;

    if (localStorage.getItem("theme") === "dark") {
        html.classList.add("dark");
        themeToggle.textContent = "☀️"; 
    } else {
        html.classList.remove("dark");
        themeToggle.textContent = "🌙"; 
    }

    themeToggle.addEventListener("click", () => {
        if (html.classList.contains("dark")) {
            html.classList.remove("dark");
            localStorage.setItem("theme", "light");
            themeToggle.textContent = "🌙";
        } else {
            html.classList.add("dark");
            localStorage.setItem("theme", "dark");
            themeToggle.textContent = "☀️";
        }
    });
});
