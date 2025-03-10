const themeToggle = document.getElementById('theme-toggle');

function toggleTheme(){
    const currentTheme = document.documentElement.getAttribute('data-theme');
    if(currentTheme === 'dark'){
        document.documentElement.setAttribute('data-theme', 'light');
        localStorage.setItem('theme', 'light');
    } else{
        document.documentElement.setAttribute('data-theme', 'dark');
        localStorage.setItem('theme', 'dark');
    }
}

const savedTheme = localStorage.getItem('theme');
if (savedTheme === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
    themeToggle.checked = true;
} else{
    document.documentElement.setAttribute('data-theme', 'light');
    themeToggle.checked = false;
}

themeToggle.addEventListener('change', toggleTheme);