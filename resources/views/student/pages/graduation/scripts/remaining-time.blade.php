<script>
    let remainingSeconds = @json($remainingTime);
    const interval = setInterval(updateCountdown, 1000);

    function formatCountdown(totalSeconds) {
        const days = Math.floor(totalSeconds / (24 * 60 * 60));

        const hours = Math.floor(
            (totalSeconds % (24 * 60 * 60)) / (60 * 60)
        );

        const minutes = Math.floor(
            (totalSeconds % (60 * 60)) / 60
        );

        const seconds = totalSeconds % 60;

        return {
            days: String(days).padStart(2, '0'),
            hours: String(hours).padStart(2, '0'),
            minutes: String(minutes).padStart(2, '0'),
            seconds: String(seconds).padStart(2, '0'),
        };
    }

    function updateCountdown() {
        if (remainingSeconds <= 0) {
            clearInterval(interval);
            $('#seconds').html('00');
            $('#btn-view-graduation button')
                .addClass('btn-primary')
                .removeClass('bg-dark bg-opacity-50 text-white')
                .addAttr('type', 'submit');
            return;
        }

        const time = formatCountdown(remainingSeconds);

        $('#days').html(time.days);
        $('#hours').html(time.hours);
        $('#minutes').html(time.minutes);
        $('#seconds').html(time.seconds);

        remainingSeconds--;
    }

    updateCountdown();
</script>
