<script>
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        function isSchemaA(dd) {
            return dd.hasAttribute('data-dropdown') || dd.querySelector('[data-dropdown-toggle]');
        }
        function getToggle(dd) {
            return dd.querySelector('[data-dropdown-toggle]') || dd.querySelector('.dropdown-selected');
        }
        function getMenu(dd) {
            return dd.querySelector('[data-dropdown-menu]') || dd.querySelector('.dropdown-options');
        }
        function getOptionsWrap(dd) {
            return dd.querySelector('[data-dropdown-options]') || dd;
        }
        function getSelectedText(dd) {
            return dd.querySelector('[data-selected-text]') || dd.querySelector('.selected-text');
        }
        function getHiddenInput(dd) {
            const name = dd.dataset.name;
            if (name) return dd.querySelector('input[type="hidden"][name="' + name + '"]');
            return dd.querySelector('input[type="hidden"]');
        }
        function getPlaceholder(dd) {
            if (dd.dataset.placeholder) return dd.dataset.placeholder;
            const firstOpt = dd.querySelector('.dropdown-option');
            return firstOpt ? firstOpt.textContent.trim() : 'Pilih';
        }
        function closeAll(except = null) {
            document.querySelectorAll('.custom-dropdown, [data-dropdown]').forEach(function(dd) {
                if (except && dd === except) return;
                const toggle = getToggle(dd);
                const menu = getMenu(dd);
                toggle?.classList.remove('active');
                menu?.classList.remove('show');
            });
        }

        function applySelected(dd, value) {
            const selectedText = getSelectedText(dd);
            const placeholder = getPlaceholder(dd);
            const allOptions = dd.querySelectorAll('.dropdown-option');

            let matched = null;
            allOptions.forEach(function(opt) {
                const v = opt.dataset.value ?? '';
                opt.classList.remove('selected');
                if ((value ?? '') === v) matched = opt;
            });

            if (matched) {
                matched.classList.add('selected');
                if (selectedText) selectedText.textContent = matched.textContent.trim();
            } else {
                if (selectedText) selectedText.textContent = placeholder;
            }
        }

        document.querySelectorAll('.custom-dropdown, [data-dropdown]').forEach(function(dd) {
            const hidden = getHiddenInput(dd);
            if (!hidden) return;

            const name = hidden.getAttribute('name');
            const qs = name ? params.get(name) : null;

            const current = (qs !== null) ? qs : (hidden.value ?? '');

            hidden.value = current;
            applySelected(dd, current);

            const searchInput = dd.querySelector('[data-dropdown-search]');
            const optionsWrap = dd.querySelector('[data-dropdown-options]');
            if (searchInput && optionsWrap) {
                searchInput.addEventListener('input', function() {
                    const q = searchInput.value.toLowerCase();
                    optionsWrap.querySelectorAll('.dropdown-option').forEach(function(opt) {
                        const t = (opt.textContent || '').toLowerCase();
                        opt.style.display = t.includes(q) ? '' : 'none';
                    });
                });
            }
        });

        document.addEventListener('click', function(e) {
            const opt = e.target.closest('.dropdown-option');
            if (opt) {
                const dd = opt.closest('.custom-dropdown') || opt.closest('[data-dropdown]');
                if (!dd) return;

                const hidden = getHiddenInput(dd);
                const value = opt.dataset.value ?? '';
                if (hidden) hidden.value = value;

                applySelected(dd, value);
                closeAll();
                return;
            }

            const toggle = e.target.closest('[data-dropdown-toggle]') || e.target.closest(
                '.custom-dropdown .dropdown-selected');
            if (toggle) {
                e.preventDefault();
                e.stopPropagation();

                const dd = toggle.closest('.custom-dropdown') || toggle.closest('[data-dropdown]');
                if (!dd) return;

                const menu = getMenu(dd);
                const isOpen = toggle.classList.contains('active') && menu?.classList.contains('show');

                if (isOpen) {
                    toggle.classList.remove('active');
                    menu?.classList.remove('show');
                    return;
                }

                closeAll(dd);
                toggle.classList.add('active');
                menu?.classList.add('show');
                return;
            }

            closeAll();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAll();
        });
    });
</script>
