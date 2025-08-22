window.axios = require('axios');
import Alpine from "alpinejs";
import Fuse from "fuse.js";

window.Fuse = Fuse;
window.Alpine = Alpine;

Alpine.start();

import hljs from 'highlight.js/lib/core';

// Syntax highlighting
hljs.registerLanguage('bash', require('highlight.js/lib/languages/bash'));
hljs.registerLanguage('css', require('highlight.js/lib/languages/css'));
hljs.registerLanguage('html', require('highlight.js/lib/languages/xml'));
hljs.registerLanguage('javascript', require('highlight.js/lib/languages/javascript'));
hljs.registerLanguage('json', require('highlight.js/lib/languages/json'));
hljs.registerLanguage('markdown', require('highlight.js/lib/languages/markdown'));
hljs.registerLanguage('php', require('highlight.js/lib/languages/php'));
hljs.registerLanguage('scss', require('highlight.js/lib/languages/scss'));
hljs.registerLanguage('yaml', require('highlight.js/lib/languages/yaml'));

document.querySelectorAll('pre code').forEach((block) => {
    hljs.highlightElement(block);
});

// Dark mode logic
document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('theme');
    const html = document.documentElement;
    const lightCss = document.getElementById('hljs-light');
    const darkCss = document.getElementById('hljs-dark');
    const hour = new Date().getHours();
    const isNight = (hour >= 19 || hour < 7);
    const defaultTheme = isNight ? 'dark' : 'light';

    // Helper to switch syntax highlight theme
    function updateHighlightTheme(theme) {
        if (theme === 'dark') {
            lightCss.disabled = true;
            darkCss.disabled = false;
        } else {
            lightCss.disabled = false;
            darkCss.disabled = true;
        }
    }

    // Initialize theme
    const savedTheme = localStorage.getItem('theme') || defaultTheme;
    html.setAttribute('theme', savedTheme);
    updateHighlightTheme(savedTheme);

    // Toggle theme on click
    themeToggle.addEventListener('click', function () {
        const currentTheme = html.getAttribute('theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        html.setAttribute('theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateHighlightTheme(newTheme);
    });
});

// copy to clipboard logic
document.addEventListener('DOMContentLoaded', function() {
    const copyBtn = document.getElementById('copy-url-btn');
    if (!copyBtn) return;

    copyBtn.addEventListener('click', function() {
        const postUrl = window.location.href;
        
        navigator.clipboard.writeText(postUrl).then(() => {
            const copyText = copyBtn.querySelector('.copy-text');
            const copiedText = copyBtn.querySelector('.copied-text');
            
            copyText.style.display = 'none';
            copiedText.style.display = 'inline';
            
            setTimeout(() => {
                copyText.style.display = 'inline';
                copiedText.style.display = 'none';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    });
}); 