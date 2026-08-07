import hljs from 'highlight.js/lib/core';

// Static imports, not require(): the production bundler resolves require()
// at build time, but the dev server serves this file as an ES module and
// leaves the call in place, where `require` is undefined. That threw on the
// first line and silently left every code block unhighlighted in dev.
import bash from 'highlight.js/lib/languages/bash';
import css from 'highlight.js/lib/languages/css';
import xml from 'highlight.js/lib/languages/xml';
import javascript from 'highlight.js/lib/languages/javascript';
import json from 'highlight.js/lib/languages/json';
import markdown from 'highlight.js/lib/languages/markdown';
import php from 'highlight.js/lib/languages/php';
import scss from 'highlight.js/lib/languages/scss';
import yaml from 'highlight.js/lib/languages/yaml';

// Syntax highlighting
hljs.registerLanguage('bash', bash);
hljs.registerLanguage('css', css);
hljs.registerLanguage('html', xml);
hljs.registerLanguage('javascript', javascript);
hljs.registerLanguage('json', json);
hljs.registerLanguage('markdown', markdown);
hljs.registerLanguage('php', php);
hljs.registerLanguage('scss', scss);
hljs.registerLanguage('yaml', yaml);

document.querySelectorAll('pre code').forEach((block) => {
    hljs.highlightElement(block);
});

// Dark mode logic
document.addEventListener('DOMContentLoaded', function () {
    function applyTheme(isOsDark) {
        document.documentElement.setAttribute('theme', isOsDark ? 'dark' : 'light');
    }

    // Get initial OS theme preference
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    applyTheme(darkModeMediaQuery.matches);

    // Listen for OS theme changes
    darkModeMediaQuery.addEventListener('change', function (e) {
        applyTheme(e.matches);
    });
});

// copy to clipboard logic
document.addEventListener('DOMContentLoaded', function () {
    const copyBtn = document.getElementById('copy-url-btn');
    if (!copyBtn) return;

    copyBtn.addEventListener('click', function () {
        navigator.clipboard.writeText(window.location.href).then(() => {
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
