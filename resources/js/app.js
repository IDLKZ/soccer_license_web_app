import './bootstrap';

// Theme Toggle Functionality
const themeToggle = {
    init() {
        // Check for saved theme preference or default to 'light' mode
        const currentTheme = localStorage.getItem('theme') || 'light';

        // Set initial theme
        this.setTheme(currentTheme);

        // Listen for theme toggle clicks
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-theme-toggle]')) {
                e.preventDefault();
                this.toggleTheme();
            }
        });
    },

    setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            this.updateIcons('dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            this.updateIcons('light');
        }
    },

    toggleTheme() {
        const currentTheme = localStorage.getItem('theme') || 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        this.setTheme(newTheme);
    },

    updateIcons(theme) {
        const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
        toggleButtons.forEach(button => {
            const icon = button.querySelector('i');
            if (icon) {
                if (theme === 'dark') {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                }
            }
        });
    }
};

// Initialize theme toggle when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => themeToggle.init());
} else {
    themeToggle.init();
}

// Sidebar toggle for mobile (if not already initialized)
document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
        });
    }
});
