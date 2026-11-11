(() => {
    'use strict';
    function initializeWorkspace() {
        const sidebar = document.getElementById('app-sidebar');
        const toggle = document.getElementById('hamburgerMenu');
        const backdrop = document.querySelector('.app-sidebar-backdrop');
        const close = document.querySelector('.app-sidebar-close');
        const smallScreen = window.matchMedia('(max-width: 1023px)');
        if (sidebar && toggle && backdrop && close) {
            const isOpen = () => smallScreen.matches && sidebar.classList.contains('show');
            function setNavigation(open, restoreFocus = false) {
                open = Boolean(open && smallScreen.matches);
                sidebar.classList.toggle('show', open);
                sidebar.inert = smallScreen.matches && !open;
                sidebar.setAttribute('aria-hidden', String(smallScreen.matches && !open));
                toggle.classList.toggle('active', open);
                toggle.setAttribute('aria-expanded', String(open));
                toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
                backdrop.hidden = !open;
                document.body.classList.toggle('nav-open', open);
                document.querySelector('.main-content').inert = open;
                document.querySelector('.nav-panel').inert = open;
                if (open) { sidebar.setAttribute('role', 'dialog'); sidebar.setAttribute('aria-modal', 'true'); }
                else { sidebar.removeAttribute('role'); sidebar.removeAttribute('aria-modal'); }
                if (open) requestAnimationFrame(() => { if (isOpen()) close.focus(); });
                else if (restoreFocus) toggle.focus();
            }
            toggle.addEventListener('click', () => setNavigation(!isOpen(), isOpen()));
            close.addEventListener('click', () => setNavigation(false, true));
            backdrop.addEventListener('click', () => setNavigation(false, true));
            sidebar.querySelectorAll('.sidebar-nav a').forEach(link => {
                if (link.classList.contains('active')) link.setAttribute('aria-current', 'page');
                link.addEventListener('click', () => setNavigation(false));
            });
            smallScreen.addEventListener('change', () => setNavigation(false));
            document.addEventListener('keydown', event => {
                if (!isOpen()) return;
                if (event.key === 'Escape') { event.preventDefault(); setNavigation(false, true); }
                if (event.key !== 'Tab') return;
                const focusable = [...sidebar.querySelectorAll('a[href], button:not([disabled])')]
                    .filter(element => element.getClientRects().length);
                const first = focusable[0];
                const last = focusable[focusable.length - 1];
                if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
                else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
            });
            setNavigation(false);
        }
        const suspensionDialog = document.getElementById('suspendModal');
        if (suspensionDialog?.tagName === 'DIALOG') {
            suspensionDialog.addEventListener('close', () => {
                document.getElementById('suspendForm')?.reset();
                if (typeof window.toggleSuspensionDays === 'function') window.toggleSuspensionDays();
            });
        }
        const page = document.querySelector('.mg-page');
        if (!page) return;
        // Preserve existing IDs, row order, action handlers, and form submissions.
        page.querySelectorAll('input:not([type=hidden]), select, textarea').forEach((field, index) => {
            if (!field.id) field.id = `management-field-${index}`;
            const group = field.closest('.space-y-2, .col-md-3, .col-md-2') || field.parentElement;
            const label = group?.querySelector('label');
            if (label && !label.htmlFor) label.htmlFor = field.id;
            if (!label && !document.querySelector(`label[for="${field.id}"]`) && !field.hasAttribute('aria-label')) {
                field.setAttribute('aria-label', field.placeholder || field.name?.replaceAll('_', ' ') || 'Filter records');
            }
        });
        page.querySelectorAll('a[title], button[title]').forEach(control => {
            if (!control.getAttribute('aria-label')) control.setAttribute('aria-label', control.title);
        });
        const emailFilters = [...page.querySelectorAll('[data-filter]')];
        emailFilters.forEach(button => {
            button.setAttribute('aria-pressed', String(button.dataset.filter === 'all'));
            button.addEventListener('click', () => {
                emailFilters.forEach(other => other.setAttribute('aria-pressed', String(other === button)));
            });
        });
        page.querySelectorAll('.mg-table').forEach(table => {
            const headers = [...table.querySelectorAll('thead th')].map(header => header.textContent.trim());
            const body = table.tBodies[0];
            if (!body) return;
            const container = table.closest('.mg-table-container');
            if (container) { container.tabIndex = 0; container.setAttribute('role', 'region'); container.setAttribute('aria-label', 'Records table'); }
            let summary;
            let empty;
            if (!page.classList.contains('mg-reports')) {
                summary = document.createElement('div');
                summary.className = 'mg-results-summary';
                summary.setAttribute('role', 'status');
                summary.setAttribute('aria-live', 'polite');
                empty = document.createElement('div');
                empty.className = 'mg-no-results';
                empty.hidden = true;
                empty.textContent = 'No records match these filters. Try another search or clear the filters.';
                (container || table).after(empty, summary);
            }
            function decorateRows() {
                const rows = [...body.rows].filter(row => !row.querySelector('td[colspan]'));
                rows.forEach(row => [...row.cells].forEach((cell, index) => {
                    const label = headers[index] === '#' ? 'Record' : headers[index] || 'Details';
                    if (cell.dataset.label !== label) cell.dataset.label = label;
                }));
                if (!summary) return;
                const visible = rows.filter(row => !row.hidden && row.style.display !== 'none').length;
                summary.textContent = visible === rows.length
                    ? `${rows.length} ${rows.length === 1 ? 'record' : 'records'} on this page`
                    : `${visible} of ${rows.length} records shown on this page`;
                empty.hidden = rows.length === 0 || visible > 0;
            }
            decorateRows();
            // Report/email filters may replace rows after fetching fresh data.
            new MutationObserver(decorateRows).observe(body, {
                childList: true, subtree: true, attributes: true, attributeFilter: ['style', 'hidden'],
            });
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializeWorkspace);
    else initializeWorkspace();
})();
