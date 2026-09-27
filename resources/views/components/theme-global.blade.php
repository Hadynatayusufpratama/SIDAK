<script>
    try {
        const savedTheme = localStorage.getItem('sidak-theme') || localStorage.getItem('sidak-dashboard-theme');
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        document.documentElement.dataset.theme = savedTheme || systemTheme;
    } catch (error) {
        document.documentElement.dataset.theme = 'light';
    }
</script>
<style>
    body, header, footer, main, [data-theme-surface] {
        transition: background-color 220ms ease, border-color 220ms ease, color 220ms ease;
    }

    html[data-theme="dark"] body {
        color-scheme: dark;
        background-color: #101915;
        color: #e5eee8;
    }

    html[data-theme="dark"] body > div.fixed > div.absolute {
        background-color: rgba(12, 20, 16, 0.92);
    }

    html[data-theme="dark"] header,
    html[data-theme="dark"] footer,
    html[data-theme="dark"] [class~="bg-white"],
    html[data-theme="dark"] [class~="bg-white/90"],
    html[data-theme="dark"] [class~="bg-white/80"],
    html[data-theme="dark"] [class~="bg-white/95"] {
        background-color: #18261f;
        border-color: #344b3d;
    }

    html[data-theme="dark"] [class~="bg-slate-100"],
    html[data-theme="dark"] [class~="bg-slate-100/90"],
    html[data-theme="dark"] [class~="bg-slate-100/80"],
    html[data-theme="dark"] [class~="bg-slate-100/75"],
    html[data-theme="dark"] [class~="bg-slate-50"],
    html[data-theme="dark"] [class~="bg-slate-50/50"],
    html[data-theme="dark"] [class~="bg-slate-50/80"],
    html[data-theme="dark"] [class~="bg-slate-200"],
    html[data-theme="dark"] [class~="bg-slate-200/80"] {
        background-color: #17251e;
        border-color: #344b3d;
    }

    html[data-theme="dark"] [class~="bg-gray-100"],
    html[data-theme="dark"] [class~="bg-gray-50"] {
        background-color: #17251e;
    }

    html[data-theme="dark"] [class~="bg-emerald-50"],
    html[data-theme="dark"] [class~="bg-emerald-100/70"] {
        background-color: rgba(16, 185, 129, 0.13);
        border-color: rgba(16, 185, 129, 0.24);
    }

    html[data-theme="dark"] [class~="bg-blue-50"] {
        background-color: rgba(59, 130, 246, 0.14);
        border-color: rgba(59, 130, 246, 0.24);
    }

    html[data-theme="dark"] [class~="border-slate-100"],
    html[data-theme="dark"] [class~="border-slate-200"],
    html[data-theme="dark"] [class~="border-slate-200/80"] {
        border-color: #344b3d;
    }

    html[data-theme="dark"] .text-slate-900 { color: #edf5ef; }
    html[data-theme="dark"] .text-slate-800 { color: #dce8df; }
    html[data-theme="dark"] .text-slate-700,
    html[data-theme="dark"] .text-slate-600,
    html[data-theme="dark"] .text-slate-500 { color: #a2b5a8; }
    html[data-theme="dark"] .text-slate-400 { color: #8da293; }
    html[data-theme="dark"] .text-gray-800,
    html[data-theme="dark"] .text-gray-700 { color: #dce8df; }
    html[data-theme="dark"] .text-gray-600,
    html[data-theme="dark"] .text-gray-500 { color: #a2b5a8; }
    html[data-theme="dark"] .text-emerald-800,
    html[data-theme="dark"] .text-emerald-700 { color: #86efac; }
    html[data-theme="dark"] input,
    html[data-theme="dark"] select,
    html[data-theme="dark"] textarea {
        color: #e5eee8;
        background-color: #17251e;
        border-color: #3b5646;
    }

    .dashboard-theme-toggle {
        width: 2.5rem;
        height: 2.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dbe4dc;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.8);
        color: #166534;
        cursor: pointer;
        transition: background-color 220ms ease, border-color 220ms ease, color 220ms ease, transform 180ms ease;
    }

    .dashboard-theme-toggle:hover {
        transform: translateY(-1px);
    }

    .dashboard-theme-toggle:focus-visible {
        outline: 3px solid rgba(34, 197, 94, 0.45);
        outline-offset: 3px;
    }

    html[data-theme="dark"] .dashboard-theme-toggle {
        background: #1d3026;
        border-color: #3b5646;
        color: #fbbf24;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const syncThemeToggles = function () {
            const isDark = document.documentElement.dataset.theme === 'dark';
            document.querySelectorAll('[data-theme-toggle]').forEach(function (toggle) {
                const icon = toggle.querySelector('[data-theme-toggle-icon]');
                if (icon) icon.className = `fa-solid ${isDark ? 'fa-sun' : 'fa-moon'}`;
                toggle.setAttribute('aria-label', isDark ? 'Aktifkan mode siang' : 'Aktifkan mode malam');
                toggle.title = isDark ? 'Aktifkan mode siang' : 'Aktifkan mode malam';
            });
        };

        document.querySelectorAll('[data-theme-toggle]').forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
                document.documentElement.dataset.theme = nextTheme;
                try {
                    localStorage.setItem('sidak-theme', nextTheme);
                } catch (error) {
                    // Keep the selected theme for the current page if storage is unavailable.
                }
                syncThemeToggles();
                document.dispatchEvent(new CustomEvent('sidak:theme-change', { detail: { theme: nextTheme } }));
            });
        });

        syncThemeToggles();
    });
</script>