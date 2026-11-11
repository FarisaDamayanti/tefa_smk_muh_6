<script>
(function () {
    $(document).on('click', '.js-toggle-active', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        $btn.find('.js-icon-empty').toggleClass('d-none');
        $btn.find('.js-icon-check').toggleClass('d-none d-inline-flex');

        setTimeout(updateTotalSelected, 50);
    });

    function updateTotalSelected() {
        const totalChecked = $('.js-toggle-active .js-icon-check:not(.d-none)').length;
        $('#total-selected-questions').text(totalChecked);
    }

    $('#checkAll').on('change', function () {
        const isChecked = $(this).is(':checked');

        $('.js-toggle-active').each(function () {
            const $btn        = $(this);
            const $iconEmpty  = $btn.find('.js-icon-empty');
            const $iconCheck  = $btn.find('.js-icon-check');

            if (isChecked) {
                $iconEmpty.addClass('d-none');
                $iconCheck.removeClass('d-none').addClass('d-inline-flex');
            } else {
                $iconEmpty.removeClass('d-none');
                $iconCheck.removeClass('d-inline-flex').addClass('d-none');
            }
        });

        updateTotalSelected();
    });

    $(function () {
        [100, 300, 500, 1000].forEach(function (delay) {
            setTimeout(redrawAllMatchingBoards, delay);
        });
    });

    // =========================
    // MATCHING LINES
    // =========================
    function drawMatchingLines($board) {
        const $svg = $board.find('.js-matching-svg');
        if (!$svg.length) return;

        let connections = [];
        try {
            connections = JSON.parse($board.attr('data-connections') || '[]');
        } catch (e) {
            return;
        }

        if (!connections.length) return;

        const boardRect = $board[0].getBoundingClientRect();
        if (!boardRect.width || !boardRect.height) return;

        $svg.empty();
        $svg.attr('viewBox', `0 0 ${boardRect.width} ${boardRect.height}`);

        connections.forEach(function (conn) {
            const $from = $board.find(`.js-anchor[data-node="${conn.from}"]`);
            const $to   = $board.find(`.js-anchor[data-node="${conn.to}"]`);

            if (!$from.length || !$to.length) return;

            const fromRect = $from[0].getBoundingClientRect();
            const toRect   = $to[0].getBoundingClientRect();

            const x1 = fromRect.right  - boardRect.left;
            const y1 = fromRect.top + fromRect.height / 2 - boardRect.top;

            const x2 = toRect.left     - boardRect.left;
            const y2 = toRect.top  + toRect.height  / 2 - boardRect.top;

            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', x1);
            line.setAttribute('y1', y1);
            line.setAttribute('x2', x2);
            line.setAttribute('y2', y2);
            line.setAttribute('stroke', '#5D87FF');
            line.setAttribute('stroke-width', '2');
            line.setAttribute('stroke-linecap', 'round');

            $svg[0].appendChild(line);
        });
    }

    function redrawAllMatchingBoards() {
        $('.js-matching-board').each(function () {
            drawMatchingLines($(this));
        });
    }

    let resizeTimer;
    $(window).on('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(redrawAllMatchingBoards, 100);
    });

    $(document).on('shown.bs.collapse', '.accordion-collapse', function () {
        setTimeout(redrawAllMatchingBoards, 150);
    });

})();
</script>
