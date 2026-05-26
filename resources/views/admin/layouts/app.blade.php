<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - RupiaChat Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1A3D95; /* RupiaChat App Primary Blue */
            --primary-accent: #2563EB;
            --primary-glow: rgba(26, 61, 149, 0.3);
            --bg-color: #0B0F19; /* Midnight Deep Blue */
            --surface: rgba(17, 24, 43, 0.7);
            --surface-hover: rgba(22, 32, 58, 0.85);
            --surface-border: rgba(255, 255, 255, 0.08);
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --success: #10B981;
            --danger: #F43F5E;
            --warning: #F59E0B;
            --font-family: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-family);
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        /* Rich premium background gradients matching RupiaChat Call screen atmosphere */
        body::before {
            content: '';
            position: fixed;
            top: -20%;
            right: -20%;
            width: 70vw;
            height: 70vw;
            background: radial-gradient(circle, rgba(26, 61, 149, 0.25) 0%, rgba(15, 23, 42, 0) 70%);
            z-index: -1;
            pointer-events: none;
            filter: blur(60px);
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -20%;
            left: -20%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(15, 23, 42, 0) 70%);
            z-index: -1;
            pointer-events: none;
            filter: blur(50px);
        }

        /* Glassmorphism Panel styles */
        .glass-panel {
            background: var(--surface);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--surface-border);
            border-radius: 20px;
            box-shadow: 0 12px 40px -10px rgba(0, 0, 0, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-panel:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 16px 48px -8px rgba(0, 0, 0, 0.5);
        }

        /* Inputs & Buttons styling matching input style in mobile mockup */
        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .input-field {
            width: 100%;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--text-main);
            padding: 0.85rem 1.2rem;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: var(--primary-accent);
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 0.85rem 1.75rem;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 24px -6px rgba(26, 61, 149, 0.5);
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            box-shadow: 0 12px 30px -4px rgba(26, 61, 149, 0.7);
        }

        .btn-primary:active {
            transform: translateY(0);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Floating Modern Navbar */
        .navbar {
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(11, 15, 25, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: -0.02em;
        }

        /* Silver circular ring with Rp, matching user screenshot */
        .navbar-brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.8);
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            color: white;
            box-shadow: 0 4px 12px rgba(26, 61, 149, 0.4);
        }

        .navbar-brand span {
            color: #3b82f6;
        }

        .nav-actions {
            display: flex;
            gap: 1.25rem;
            align-items: center;
        }

        .nav-actions span {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        main {
            flex: 1;
            padding: 2.5rem 2rem;
            max-width: 1300px;
            margin: 0 auto;
            width: 100%;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
    @yield('styles')
</head>
<body>
    @if(View::hasSection('navbar'))
        @yield('navbar')
    @endif
    
    <main>
        @yield('content')
    </main>
</body>
</html>

