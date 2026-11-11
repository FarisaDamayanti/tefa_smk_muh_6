<script>
    $(document).ready(function () {
        $('.js-matching-board').each(function () {
            drawMatchingLines($(this));
        });

        function drawMatchingLines($board) {
            const $svg = $board.find('.js-matching-svg');
            if (!$svg.length) return;

            const connections = $board.data('connections');
            if (!connections) return;

            const boardRect = $board[0].getBoundingClientRect();
            $svg.empty();
            $svg.attr('viewBox', `0 0 ${boardRect.width} ${boardRect.height}`);

            const $from = $board.find(`.js-anchor[data-node="${connections.left_item_id}"]`);
            const $to   = $board.find(`.js-anchor[data-node="${connections.right_item_id}"]`);
            if (!$from.length || !$to.length) return;

            const fromRect = $from[0].getBoundingClientRect();
            const toRect   = $to[0].getBoundingClientRect();

            const x1 = fromRect.right  - boardRect.left;
            const y1 = fromRect.top + fromRect.height / 2 - boardRect.top;
            const x2 = toRect.left     - boardRect.left;
            const y2 = toRect.top  + toRect.height  / 2 - boardRect.top;

            const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
            line.setAttribute('x1', x1);
            line.setAttribute('y1', y1);
            line.setAttribute('x2', x2);
            line.setAttribute('y2', y2);
            line.setAttribute('stroke', '#5D87FF');
            line.setAttribute('stroke-width', '2');
            line.setAttribute('stroke-linecap', 'round');
            $svg[0].appendChild(line);
        }

        $(window).on('resize', function () {
            $('.js-matching-board').each(function () {
                drawMatchingLines($(this));
            });
        });
    });
</script>