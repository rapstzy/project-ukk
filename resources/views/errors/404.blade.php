<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>404 - Halaman tidak ditemukan</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #050505;
            --panel: rgba(12, 12, 12, 0.88);
            --line: #202020;
            --muted: #9ca3af;
            --text: #f9fafb;
            --accent: #ffffff;
            --accent-soft: rgba(255, 255, 255, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            min-height: 100%;
            background:
                radial-gradient(circle at top left, rgba(29, 155, 240, 0.16), transparent 30%),
                radial-gradient(circle at bottom right, rgba(168, 85, 247, 0.14), transparent 24%),
                linear-gradient(160deg, #040404 0%, #0a0a0a 55%, #111111 100%);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .shell {
            width: min(980px, 100%);
            position: relative;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 32px;
            background: var(--panel);
            backdrop-filter: blur(18px);
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.45);
        }

        .shell::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.06), transparent 35%);
            pointer-events: none;
        }

        .content {
            position: relative;
            padding: 40px;
        }

        .kicker {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 10px 16px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.55);
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.28em;
            text-transform: uppercase;
        }

        .grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 28px;
            align-items: center;
            margin-top: 26px;
        }

        h1 {
            margin: 16px 0 0;
            font-size: clamp(3.5rem, 12vw, 7rem);
            line-height: 0.92;
            letter-spacing: -0.06em;
            font-weight: 900;
        }

        p {
            margin: 18px 0 0;
            color: #c7c7c7;
            font-size: 16px;
            line-height: 1.8;
            max-width: 48ch;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 26px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 20px;
            border-radius: 999px;
            border: 1px solid var(--line);
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #fff;
            color: #000;
        }

        .btn-secondary {
            background: var(--accent-soft);
            color: #fff;
        }

        .panel {
            border: 1px solid var(--line);
            border-radius: 28px;
            background: rgba(0, 0, 0, 0.42);
            padding: 24px;
        }

        .number {
            font-size: clamp(4rem, 14vw, 8rem);
            line-height: 1;
            font-weight: 900;
            letter-spacing: -0.08em;
        }

        .hint {
            margin-top: 14px;
            color: var(--muted);
            line-height: 1.7;
        }

        .links {
            display: grid;
            gap: 10px;
            margin-top: 20px;
        }

        .links a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid var(--line);
            background: rgba(255,255,255,0.03);
            color: #fff;
            text-decoration: none;
        }

        .links small {
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.24em;
            font-size: 11px;
        }

        .footer {
            margin-top: 24px;
            color: #7f7f7f;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.24em;
        }

        @media (max-width: 860px) {
            .content {
                padding: 28px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <div class="content">
            <div class="kicker">404</div>

            <div class="grid">
                <section>
                    <h1>Halaman tidak ditemukan.</h1>
                    <p>
                        Link yang kamu buka mungkin sudah dipindah, dihapus, atau salah ketik.
                        Coba kembali ke dashboard atau login lagi untuk lanjut.
                    </p>

                    <div class="actions">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Ke Dashboard</a>
                        <a href="{{ route('login') }}" class="btn btn-secondary">Ke Login</a>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </section>

                <aside class="panel">
                    <div class="number">404</div>
                    <div class="hint">
                        Kalau kamu sedang cari data buku atau tiket peminjaman, cek kembali URL atau pakai menu navigasi aplikasi.
                    </div>

                    <div class="links">
                        <a href="{{ route('books.index') }}">
                            <span>
                                <strong>Books</strong><br>
                                <small>Katalog buku</small>
                            </span>
                            <span>→</span>
                        </a>
                        <a href="{{ route('notifications.index') }}">
                            <span>
                                <strong>Notifications</strong><br>
                                <small>Inbox notifikasi</small>
                            </span>
                            <span>→</span>
                        </a>
                    </div>
                </aside>
            </div>

            <div class="footer">Apay's Books</div>
        </div>
    </main>
</body>
</html>
