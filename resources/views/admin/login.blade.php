@extends('admin.layouts.app')

@section('title', 'Admin Login')

@section('styles')
<style>
    /* Specific overrides for the login page to remove scrolling and center content */
    body {
        overflow: hidden !important;
        height: 100vh !important;
        width: 100vw !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    main {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
        width: 100vw !important;
        height: 100vh !important;
        overflow-y: auto !important; /* Elegant scroll ONLY if screen height is extremely small */
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .login-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 100vh;
        padding: 1.5rem;
        box-sizing: border-box;
    }

    /* Keyframe animation for a subtle, premium floating motion */
    @keyframes float {
        0% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-8px);
        }
        100% {
            transform: translateY(0px);
        }
    }

    /* Elite card design matching the RupiaChat mobile login layout (Image 2 & 3) */
    .login-card-container {
        width: 100%;
        max-width: 400px;
        background: #FFFFFF;
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), 0 0 40px rgba(26, 61, 149, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.8);
        display: flex;
        flex-direction: column;
        animation: float 6s ease-in-out infinite;
        transition: box-shadow 0.3s ease;
    }

    .login-card-container:hover {
        box-shadow: 0 30px 60px -10px rgba(0, 0, 0, 0.6), 0 0 50px rgba(26, 61, 149, 0.25);
    }

    /* Top blue header section mirroring the mobile screen's curved blue backdrop */
    .login-header-banner {
        background: linear-gradient(135deg, #0d2561 0%, #153280 50%, #204ec2 100%);
        padding: 3.5rem 2rem 2.5rem 2rem;
        text-align: center;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .login-header-banner::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 20px;
        background: #FFFFFF;
        border-radius: 100% 100% 0 0;
    }

    /* Silver circular ring with Rp, matching user screenshot */
    .brand-logo-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.95);
        background: linear-gradient(135deg, #1A3D95, #2563EB);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.6rem;
        color: white;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.2), inset 0 2px 4px rgba(255, 255, 255, 0.3), 0 6px 16px rgba(0, 0, 0, 0.2);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .brand-title {
        font-size: 1.85rem;
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: -0.03em;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    }

    .brand-subtitle {
        font-size: 0.85rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.75);
        letter-spacing: 0.02em;
        margin-top: -4px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Main form body */
    .login-body {
        padding: 2rem 2.25rem 2.75rem 2.25rem;
        background: #FFFFFF;
    }

    /* Dual tabs layout matching "Masuk" and "Daftar" */
    .login-tab-bar {
        display: flex;
        background: #F1F5F9;
        padding: 5px;
        border-radius: 14px;
        margin-bottom: 2rem;
        border: 1px solid #E2E8F0;
    }

    .login-tab {
        flex: 1;
        padding: 10px 0;
        text-align: center;
        font-size: 0.9rem;
        font-weight: 700;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .login-tab.active {
        background: linear-gradient(135deg, #1A3D95 0%, #2563EB 100%);
        color: #FFFFFF;
        box-shadow: 0 4px 14px rgba(26, 61, 149, 0.25);
    }

    .login-tab.inactive {
        color: #64748B;
    }
    
    .login-tab.inactive:hover {
        color: #1E293B;
    }

    /* Modern gray input container matching screenshot */
    .form-input-container {
        position: relative;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        background: #F8FAFC;
        border-radius: 14px;
        border: 1.5px solid #E2E8F0;
        transition: all 0.25s ease;
    }

    .form-input-container:focus-within {
        border-color: #2563EB;
        background: #FFFFFF;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
    }

    .form-input-icon {
        padding-left: 16px;
        color: #94A3B8;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.25s ease;
    }

    .form-input-container:focus-within .form-input-icon {
        color: #2563EB;
    }

    .form-input-container input {
        width: 100%;
        background: transparent;
        border: none;
        padding: 14px 16px 14px 12px;
        color: #1E293B;
        font-size: 0.95rem;
        font-weight: 600;
        outline: none;
    }

    .form-input-container input::placeholder {
        color: #94A3B8;
        font-weight: 500;
    }

    /* Visibility Toggle */
    .visibility-toggle {
        padding-right: 16px;
        color: #94A3B8;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }
    
    .visibility-toggle:hover {
        color: #2563EB;
    }

    /* Links & Buttons */
    .forgot-link-wrapper {
        text-align: right;
        margin-bottom: 2rem;
    }

    .forgot-link {
        font-size: 0.85rem;
        font-weight: 700;
        color: #1A3D95;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .forgot-link:hover {
        color: #2563EB;
        text-decoration: underline;
    }

    /* Premium Blue submit button matching mobile mockup active status */
    .btn-login-submit {
        background: linear-gradient(135deg, #1A3D95 0%, #2563EB 100%);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 6px 20px rgba(26, 61, 149, 0.35);
        position: relative;
        overflow: hidden;
    }

    .btn-login-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
        transition: all 0.6s ease;
    }

    .btn-login-submit:hover::before {
        left: 100%;
    }

    .btn-login-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(26, 61, 149, 0.5);
    }

    .btn-login-submit:active {
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(26, 61, 149, 0.3);
    }

    .login-error-box {
        background: #FEF2F2;
        border: 1px solid #FEE2E2;
        border-radius: 12px;
        padding: 12px 14px;
        color: #EF4444;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.05);
    }
</style>
@endsection

@section('content')
<div class="login-container animate-fade-in">
    <div class="login-card-container">
        <!-- Top banner Section -->
        <div class="login-header-banner">
            <div class="brand-logo-circle">Rp</div>
            <div class="brand-title">RupiaChat</div>
            <div class="brand-subtitle">Chat & Bayar dalam Satu App</div>
        </div>

        <!-- Form Section -->
        <div class="login-body">
            <!-- Tabs -->
            <div class="login-tab-bar">
                <div class="login-tab active">Masuk</div>
                <div class="login-tab inactive">Daftar</div>
            </div>

            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                
                @if($errors->any())
                    <div class="login-error-box animate-fade-in">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Email Input with Icon -->
                <div class="form-input-container">
                    <div class="form-input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    <input type="email" id="email" name="email" placeholder="Email" required value="admin@rupiachat.com">
                </div>

                <!-- Password Input with Icon & Visibility Toggle -->
                <div class="form-input-container">
                    <div class="form-input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    <input type="password" id="password" name="password" placeholder="Password" required value="admin123">
                    <div class="visibility-toggle" id="togglePassword">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                </div>

                <!-- Forgot Password -->
                <div class="forgot-link-wrapper">
                    <a href="#" class="forgot-link">Lupa Password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login-submit">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Premium dynamic visibility toggle for password input field
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        // Toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // Toggle icon style
        if (type === 'text') {
            this.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
            `;
        } else {
            this.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            `;
        }
    });
</script>
@endsection
