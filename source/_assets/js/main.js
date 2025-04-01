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
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme');
    const html = document.documentElement;

    // Initialize
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('theme', savedTheme);

    // Toggle theme
    themeToggle.addEventListener('click', function() {
        const newTheme = html.getAttribute('theme') === 'dark' ? 'light' : 'dark';
        html.setAttribute('theme', newTheme);
        localStorage.setItem('theme', newTheme);
    });
});