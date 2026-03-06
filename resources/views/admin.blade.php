<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="Platform pembelajaran online untuk Web Development, UI/UX Design, dan Data Analytics">
    <meta name="keywords" content="elearning, online course, web development, ui/ux, data analytics">
    <meta name="author" content="Andre">

    <meta property="og:title" content="Elearning APP - Platform Pembelajaran Online">
    <meta property="og:description" content="Lanjutkan perjalanan belajar Anda dengan kursus Web Development, UI/UX Design, dan Data Analytics">
    <meta property="og:type" content="website">
    <title>G-English Quiz for Learn</title>
    <script src="https://cdn.tailwindcss.com"></script>

    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>

<body>
    <div id="app">
        <App></App>
    </div>
</body>
<script>
    const defaultConfig = {
        platform_name: 'SKYLA',
        welcome_heading: 'Welcome back, Student!',
        dashboard_subtitle: 'Continue your learning journey today',
        background_color: '#f9fafb',
        sidebar_color: '#0ea5e9',
        card_color: '#ffffff',
        text_color: '#111827',
        accent_color: '#0284c7'
    };

    let config = {
        ...defaultConfig
    };

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        sidebar.classList.remove('open');
        overlay.classList.remove('active');
    }

    function toggleSubmenu(menuId) {
        const submenu = document.getElementById(`submenu-${menuId}`);
        const chevron = document.getElementById(`chevron-${menuId}`);

        submenu.classList.toggle('open');
        chevron.classList.toggle('rotated');
    }

    async function onConfigChange(newConfig) {
        config = {
            ...config,
            ...newConfig
        };

        const platformName = document.getElementById('platform-name');
        const welcomeHeading = document.getElementById('welcome-heading');
        const dashboardSubtitle = document.getElementById('dashboard-subtitle');

        if (platformName) {
            platformName.textContent = config.platform_name;
        }
        if (welcomeHeading) {
            welcomeHeading.textContent = config.welcome_heading;
        }
        if (dashboardSubtitle) {
            dashboardSubtitle.textContent = config.dashboard_subtitle;
        }

        // const sidebar = document.querySelector('.sidebar');
        // if (sidebar && config.sidebar_color) {
        //     const color1 = config.sidebar_color;
        //     const color2 = config.accent_color;
        //     sidebar.style.background = `linear-gradient(180deg, ${color1} 0%, ${color2} 100%)`;
        // }

        const contentArea = document.querySelector('.content-area');
        if (contentArea && config.background_color) {
            contentArea.style.backgroundColor = config.background_color;
        }

        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            if (config.card_color) {
                card.style.backgroundColor = config.card_color;
            }
        });

        const textElements = document.querySelectorAll('h2, h3, h4, .text-gray-900');
        textElements.forEach(el => {
            if (config.text_color) {
                el.style.color = config.text_color;
            }
        });
    }

    if (window.elementSdk) {
        window.elementSdk.init({
            defaultConfig,
            onConfigChange,
            mapToCapabilities: (config) => ({
                recolorables: [{
                        get: () => config.background_color || defaultConfig.background_color,
                        set: (value) => {
                            config.background_color = value;
                            window.elementSdk.setConfig({
                                background_color: value
                            });
                        }
                    },
                    {
                        get: () => config.sidebar_color || defaultConfig.sidebar_color,
                        set: (value) => {
                            config.sidebar_color = value;
                            window.elementSdk.setConfig({
                                sidebar_color: value
                            });
                        }
                    },
                    {
                        get: () => config.card_color || defaultConfig.card_color,
                        set: (value) => {
                            config.card_color = value;
                            window.elementSdk.setConfig({
                                card_color: value
                            });
                        }
                    },
                    {
                        get: () => config.text_color || defaultConfig.text_color,
                        set: (value) => {
                            config.text_color = value;
                            window.elementSdk.setConfig({
                                text_color: value
                            });
                        }
                    },
                    {
                        get: () => config.accent_color || defaultConfig.accent_color,
                        set: (value) => {
                            config.accent_color = value;
                            window.elementSdk.setConfig({
                                accent_color: value
                            });
                        }
                    }
                ],
                borderables: [],
                fontEditable: undefined,
                fontSizeable: undefined
            }),
            mapToEditPanelValues: (config) => new Map([
                ['platform_name', config.platform_name || defaultConfig.platform_name],
                ['welcome_heading', config.welcome_heading || defaultConfig.welcome_heading],
                ['dashboard_subtitle', config.dashboard_subtitle || defaultConfig.dashboard_subtitle]
            ])
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        onConfigChange(config);
    });
</script>

</html>