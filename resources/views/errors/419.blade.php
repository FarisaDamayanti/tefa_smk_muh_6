<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ujian Belum Dimulai</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            color: #999999;
            text-align: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        /* Radial glow spots — matching screenshot */
        .glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }

        .glow-teal {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(20, 120, 100, 0.55), transparent 70%);
            top: 5%;
            left: 5%;
        }

        .glow-blue {
            width: 340px;
            height: 340px;
            background: radial-gradient(circle, rgba(30, 60, 160, 0.45), transparent 70%);
            top: 10%;
            right: 8%;
        }

        .container {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            animation: fadeUp 0.7s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-number-wrap {
            position: relative;
            display: inline-flex;
            align-items: flex-end;
            justify-content: center;
            padding-top: 80px;
        }

        .digit {
            font-size: 11rem;
            font-weight: 900;
            line-height: 1;
            background: linear-gradient(160deg, #a8c4ff 10%, #3b82f6 50%, #1e3a8a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -4px;
            user-select: none;
        }

        .digit-zero {
            position: relative;
        }

        .graduation-cap {
            position: absolute;
            top: -90px;
            left: 55%;
            transform: translateX(-50%);
            width: 250px;
            filter: drop-shadow(0 4px 16px rgba(59, 130, 246, 0.4));
        }

        .error-message {
            font-size: 1.35rem;
            font-weight: 600;
            color: #999999;
            letter-spacing: 0.3px;
        }

         .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            background: linear-gradient(to right, #79C7FF, #0042FF);
            color: #fff;
            border: none;
            border-radius: 999px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(0, 66, 255, 0.45);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(0, 66, 255, 0.55);
        }

        .btn-back svg {
            width: 16px;
            height: 16px;
        }
    </style>
</head>

<body>

    <div class="glow glow-teal"></div>
    <div class="glow glow-blue"></div>

    <div class="container">
        <div class="error-number-wrap">
            <span class="digit">4</span>
            <span class="digit digit-zero">
                <img src="{{ asset('assets/images/graduation-cap.png') }}" alt="Graduation Cap" class="graduation-cap">
                1
            </span>
            <span class="digit">9</span>
        </div>

        <p class="error-message">Oops! Halaman Telah Kadaluwarsa</p>

        <a href="{{ url()->previous() }}" class="btn-back">
            Kembali
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M7 17L17 7M17 7H7M17 7v10" />
            </svg>
        </a>
    </div>

</body>

</html>
