<!DOCTYPE html>
<html lang="en" class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniPOS Pro</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Particles -->
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2/tsparticles.bundle.min.js"></script>

    <style>
        #particles {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        body {
            transition: background-color 0.4s, color 0.4s;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

<!-- PARTICLES -->
<div id="particles"></div>

<!-- NAVBAR -->
<nav class="flex justify-between items-center px-8 py-4 backdrop-blur-xl bg-white/40 dark:bg-gray-800/40 shadow-lg border-b border-white/20">

    <h1 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
        MiniPOS Pro
    </h1>

    <div class="flex items-center gap-4">

        <!-- 🌙 DARK MODE SWITCH -->
        <button onclick="toggleDarkMode()" 
            class="relative w-14 h-7 bg-gray-300 dark:bg-indigo-600 rounded-full transition">
            <div id="toggleCircle"
                class="absolute top-1 left-1 w-5 h-5 bg-white rounded-full shadow-md transform transition duration-300">
            </div>
        </button>

        @auth
            <a href="/dashboard"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}"
               class="px-4 py-2 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                Login
            </a>

            <a href="{{ route('register') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition">
                Register
            </a>
        @endauth
    </div>
</nav>

<!-- HERO -->
<section class="text-center py-24 px-6">

    <h2 class="text-5xl font-extrabold mb-6 animate-pulse">
        Smart POS System 🚀
    </h2>

    <p class="text-lg mb-8 text-gray-600 dark:text-gray-300">
        Manage your business with modern technology
    </p>

    <div class="space-x-4">
        <a href="/dashboard"
           class="bg-indigo-600 text-white px-6 py-3 rounded-xl shadow hover:scale-105 transition">
            Get Started
        </a>

        <a href="#features"
           class="border px-6 py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Learn More
        </a>
    </div>

</section>

<!-- FEATURES -->
<section id="features"
    class="py-20 px-8 grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">

    <div class="p-6 rounded-2xl backdrop-blur-xl bg-white/40 dark:bg-gray-800/40 shadow-lg hover:scale-105 hover:shadow-2xl transition">
        <h3 class="text-lg font-bold mb-2">📦 Product Management</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Manage inventory, images, and stock easily.
        </p>
    </div>

    <div class="p-6 rounded-2xl backdrop-blur-xl bg-white/40 dark:bg-gray-800/40 shadow-lg hover:scale-105 hover:shadow-2xl transition">
        <h3 class="text-lg font-bold mb-2">🧾 Sales System</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Fast checkout with invoice & receipt printing.
        </p>
    </div>

    <div class="p-6 rounded-2xl backdrop-blur-xl bg-white/40 dark:bg-gray-800/40 shadow-lg hover:scale-105 hover:shadow-2xl transition">
        <h3 class="text-lg font-bold mb-2">📊 Analytics</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Track revenue, performance, and reports.
        </p>
    </div>

</section>

<!-- FOOTER -->
<footer class="text-center py-6 text-sm text-gray-500 dark:text-gray-400">
    © {{ date('Y') }} MiniPOS Pro 💎
</footer>

<!-- SCRIPT -->
<script>
function toggleDarkMode() {
    const html = document.documentElement;
    const circle = document.getElementById('toggleCircle');

    html.classList.toggle('dark');

    if (html.classList.contains('dark')) {
        circle.style.transform = "translateX(26px)";
        localStorage.setItem('theme', 'dark');
        loadParticles("dark");
    } else {
        circle.style.transform = "translateX(0px)";
        localStorage.setItem('theme', 'light');
        loadParticles("light");
    }
}

// Load theme on start
window.onload = function () {
    const theme = localStorage.getItem('theme');
    const circle = document.getElementById('toggleCircle');

    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
        circle.style.transform = "translateX(26px)";
        loadParticles("dark");
    } else {
        loadParticles("light");
    }
};

// 🎇 PARTICLES MODE SWITCH
function loadParticles(mode) {
    tsParticles.load("particles", {
        background: { color: "transparent" },
        particles: {
            number: { value: 60 },
            size: { value: 3 },
            move: { speed: 1 },
            links: { 
                enable: true,
                color: mode === "dark" ? "#ffffff" : "#6366f1"
            },
            color: {
                value: mode === "dark" ? "#ffffff" : "#6366f1"
            }
        }
    });
}
</script>

</body>
</html>