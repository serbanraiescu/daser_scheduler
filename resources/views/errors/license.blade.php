<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licență Necesară - Daser Scheduler</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --bg: #0f172a;
            --card: #1e293b;
            --text: #f8fafc;
            --text-muted: #94a3b8;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .card {
            background-color: var(--card);
            padding: 40px;
            border-radius: 24px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .icon {
            font-size: 64px;
            margin-bottom: 24px;
            display: inline-block;
        }
        h1 { margin: 0 0 16px 0; font-size: 24px; }
        p { color: var(--text-muted); line-height: 1.6; margin-bottom: 32px; }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            color: white;
            padding: 12px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn:hover { transform: translateY(-2px); }
        .status-badge {
            display: block;
            margin-top: 24px;
            font-size: 12px;
            color: #ef4444;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">🔒</div>
        <h1>Activare Necesară</h1>
        <p>Aplicația Daser Scheduler necesită o licență validă pentru a funcționa. Te rugăm să intri în panoul de administrare pentru a introduce cheia de licență.</p>
        
        <a href="{{ url('/login') }}" class="btn">Autentifică-te ca Admin</a>

        @if(isset($status))
            <span class="status-badge">Status: {{ $status->status }}</span>
        @endif
    </div>
</body>
</html>
