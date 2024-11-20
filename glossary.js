document.addEventListener("DOMContentLoaded", function() {
    if (typeof window.glossaryTerms !== 'undefined' && window.glossaryTerms.length > 0) {
        const contentElement = document.querySelector('div[itemprop="articleBody"]');
        if (contentElement) {
            let content = contentElement.innerHTML;

            // Get the current language code from the URL
            const urlPath = window.location.pathname;
            const langCodeMatch = urlPath.match(/^\/(en|es|pt-br|ar)\//);
            const langCode = langCodeMatch ? langCodeMatch[1] : 'en';

            function replaceTextOutsideATags(node, term, definition, abbreviation) {
                if (node.nodeType === Node.TEXT_NODE && node.parentNode.nodeName !== 'A') {
                    const parentNode = node.parentNode;
                    const text = node.textContent;
                    const regex = new RegExp(`\\b${term}\\b`, 'gi');
                    const newText = text.replace(regex, function(matched) {
                        // Preserve the original term's casing and add tooltip functionality
                        return `<abbr class="glossary-term" data-definition="${encodeURIComponent(definition)}" data-abbreviation="${encodeURIComponent(abbreviation)}">${matched}</abbr>`;
                    });
                    if (newText !== text) {
                        const span = document.createElement('span');
                        span.innerHTML = newText;
                        parentNode.replaceChild(span, node);
                    }
                } else if (node.nodeType === Node.ELEMENT_NODE && node.nodeName !== 'A') {
                    node.childNodes.forEach(child => replaceTextOutsideATags(child, term, definition, abbreviation));
                }
            }

            window.glossaryTerms.forEach(termData => {
                const term = termData.term;
                const abbreviation = termData.abbrev || ''; // Use abbreviation if provided
                const definitionKey = `definition_${langCode.replace('-', '_')}`; // Handle language code format
                const definition = termData[definitionKey] || termData.definition_en; // Fallback to English definition
                contentElement.childNodes.forEach(child => replaceTextOutsideATags(child, term, definition, abbreviation));
            });

            const glossaryTerms = document.querySelectorAll('.glossary-term');
            let tooltip = null;

            function showTooltip(term) {
                if (!tooltip) {
                    tooltip = document.createElement('div');
                    tooltip.classList.add('glossary-tooltip');
                    document.body.appendChild(tooltip);
                    tooltip.style.position = 'fixed';
                    tooltip.style.bottom = '50px';
                    tooltip.style.left = '10px';
                    tooltip.style.width = 'calc(100vw - 30px)';
                }
                const definition = decodeURIComponent(term.dataset.definition);
                const abbreviation = decodeURIComponent(term.dataset.abbreviation);
                tooltip.innerHTML = abbreviation ? `<h3 style="margin-bottom:10px">${abbreviation}</h3>${definition}` : definition;
                tooltip.style.visibility = 'visible';
                tooltip.style.opacity = 1;
                document.body.classList.add('showing-tooltip');
            }

            function hideTooltip() {
                if (tooltip) {
                    tooltip.style.visibility = 'hidden';
                    tooltip.style.opacity = 0;
                    document.body.classList.remove('showing-tooltip');
                }
            }

            glossaryTerms.forEach(term => {
                term.addEventListener('click', (event) => {
                    event.stopPropagation();
                    if (tooltip && tooltip.style.visibility === 'visible') {
                        hideTooltip();
                    } else {
                        showTooltip(term);
                    }
                });
            });

            document.addEventListener('click', () => {
                hideTooltip();
            });
        }
    }
});

