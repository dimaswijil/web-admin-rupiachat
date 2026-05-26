@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    /* Override global main container styles for full screen sidebar layout */
    main {
        max-width: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }

    /* Core Sidebar Dashboard Layout */
    .dashboard-layout {
        display: flex;
        min-height: 100vh;
        background-color: var(--bg-color);
        position: relative;
    }

    /* Modern Glassmorphic Fixed Left Sidebar */
    .sidebar {
        width: 280px;
        background: rgba(9, 13, 24, 0.85);
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        padding: 2.25rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
    }

    .main-content {
        flex: 1;
        margin-left: 280px;
        padding: 3rem;
        min-width: 0; /* Prevents grid/flex items from overflowing */
        z-index: 1;
    }

    /* Sidebar Header Layout */
    .sidebar-header {
        display: flex;
        align-items: center;
        height: 42px;
        margin-bottom: 0.5rem;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Sidebar text label styles */
    .brand-text-label,
    .sidebar-label {
        white-space: nowrap;
    }

    /* Vertical Navigation Menu */
    .sidebar-menu {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        border-radius: 12px;
        color: var(--text-muted);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        transition: none;
        border: 1px solid transparent;
        position: relative;
    }



    .sidebar-link:hover {
        color: white;
        background: rgba(255, 255, 255, 0.04);
    }

    .sidebar-link.active {
        background: var(--primary);
        color: white;
        box-shadow: 0 4px 15px rgba(26, 61, 149, 0.35);
        border-color: rgba(255, 255, 255, 0.08);
    }

    .sidebar-link svg {
        flex-shrink: 0;
    }

    /* Notification badges inside sidebar link are removed */





    /* Page Header Styles with high stacking context for floating dropdowns */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2.5rem;
        position: relative;
        z-index: 50;
    }
    
    .dashboard-header h1 {
        font-size: 2.25rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        background: linear-gradient(to right, #ffffff 30%, #94a3b8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.4rem;
    }
    
    .dashboard-header p {
        color: var(--text-muted);
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        padding: 1.75rem;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    /* Glowing aura for primary cards */
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, var(--stat-glow, rgba(59, 130, 246, 0.15)) 0%, rgba(0, 0, 0, 0) 70%);
        z-index: 0;
        pointer-events: none;
    }

    .stat-icon-wrapper {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--icon-bg, rgba(255, 255, 255, 0.05));
        border: 1px solid var(--icon-border, rgba(255, 255, 255, 0.08));
        margin-bottom: 1rem;
        color: var(--icon-color, white);
        z-index: 1;
        position: relative;
    }

    .stat-title {
        color: var(--text-muted);
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 0.25rem;
        z-index: 1;
        position: relative;
    }

    .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: white;
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
        z-index: 1;
        position: relative;
        white-space: nowrap;
    }

    .stat-change {
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 8px;
        z-index: 1;
        position: relative;
    }
    .stat-change.positive { 
        background: rgba(16, 185, 129, 0.1); 
        color: var(--success); 
    }
    .stat-change.negative { 
        background: rgba(244, 63, 94, 0.1); 
        color: var(--danger); 
    }

    /* Monthly Analytics Chart Panel */
    .chart-panel {
        padding: 2rem;
        margin-bottom: 2.5rem;
    }

    .chart-title-group {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .chart-grid {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        height: 220px;
        padding: 10px 20px 0 20px;
        position: relative;
        border-bottom: 2px solid rgba(255, 255, 255, 0.08);
    }

    .chart-grid-lines {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        pointer-events: none;
        z-index: 0;
    }

    .chart-line {
        border-top: 1px dashed rgba(255, 255, 255, 0.05);
        width: 100%;
        height: 1px;
    }

    .chart-line-label {
        font-size: 0.7rem;
        color: var(--text-muted);
        position: absolute;
        left: -24px;
        transform: translateY(-50%);
    }

    .chart-bar-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        height: 100%;
        justify-content: flex-end;
        z-index: 1;
        position: relative;
    }

    .chart-bar {
        width: 32px;
        border-radius: 8px 8px 0 0;
        background: linear-gradient(to top, var(--primary) 0%, var(--primary-accent) 100%);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        position: relative;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.15);
    }

    .chart-bar:hover {
        transform: scaleX(1.1);
        filter: brightness(1.2);
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.4);
    }

    .chart-tooltip {
        position: absolute;
        top: -38px;
        background: #1E293B;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        opacity: 0;
        transform: scale(0.9) translateY(5px);
        transition: all 0.2s ease;
        pointer-events: none;
        white-space: nowrap;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .chart-bar:hover .chart-tooltip {
        opacity: 1;
        transform: scale(1) translateY(0);
    }

    .chart-label {
        margin-top: 10px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
    }

    .insights-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .insight-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .insight-title {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .insight-value {
        font-size: 1.2rem;
        font-weight: 800;
        color: white;
    }

    /* Table Section Inputs & Forms */
    .search-container {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 8px 16px;
        border-radius: 12px;
        width: 300px;
        transition: all 0.3s ease;
    }
    
    .search-container:focus-within {
        border-color: var(--primary-accent);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }

    .search-container input {
        background: transparent;
        border: none;
        color: white;
        outline: none;
        font-size: 0.9rem;
        font-weight: 500;
        width: 100%;
    }

    .search-container input::placeholder {
        color: rgba(255, 255, 255, 0.35);
    }

    .search-container svg {
        color: var(--text-muted);
        flex-shrink: 0;
    }

    /* Table styles */
    .table-section {
        overflow: hidden;
    }

    .table-header {
        padding: 1.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .table-header h2 {
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        color: white;
    }

    .table-container {
        overflow-x: auto;
    }
    
    .table-container::-webkit-scrollbar {
        height: 6px;
    }
    .table-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .table-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
    }
    .table-container::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .data-table th {
        padding: 1.2rem 0.75rem;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        background: rgba(255, 255, 255, 0.01);
    }

    .data-table td {
        padding: 1.25rem 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        vertical-align: middle;
        font-size: 0.925rem;
        font-weight: 500;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr {
        transition: all 0.2s ease;
    }

    .data-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.02);
    }

    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .badge.completed {
        background: rgba(16, 185, 129, 0.12);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    .badge.completed::before {
        background-color: #10B981;
    }

    .badge.pending {
        background: rgba(245, 158, 11, 0.12);
        color: #F59E0B;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    .badge.pending::before {
        background-color: #F59E0B;
    }

    .badge.failed {
        background: rgba(244, 63, 94, 0.12);
        color: #F43F5E;
        border: 1px solid rgba(244, 63, 94, 0.2);
    }
    .badge.failed::before {
        background-color: #F43F5E;
    }

    .badge.active {
        background: rgba(16, 185, 129, 0.12);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    .badge.active::before {
        background-color: #10B981;
    }

    .badge.suspended {
        background: rgba(244, 63, 94, 0.12);
        color: #F43F5E;
        border: 1px solid rgba(244, 63, 94, 0.2);
    }
    .badge.suspended::before {
        background-color: #F43F5E;
    }

    /* User Profile avatar row */
    .user-info {
        display: flex;
        align-items: center;
        gap: 0.9rem;
    }

    .user-avatar {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1A3D95 0%, #3B82F6 100%);
        border: 2px solid rgba(255, 255, 255, 0.15);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 700;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
    }

    .feature-icon-box {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.06);
    }

    .feature-name-group {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
    }

    /* Toast Alert notification */
    .alert-toast {
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.25);
        color: #10B981;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.1);
    }

    /* Action buttons */
    .btn-action-approve {
        background: #10B981;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: none;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    .btn-action-approve:hover {
        background: #059669;
        transform: none;
    }

    .btn-action-reject {
        background: #F43F5E;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: none;
        box-shadow: 0 4px 12px rgba(244, 63, 94, 0.3);
    }
    .btn-action-reject:hover {
        background: #E11D48;
        transform: none;
    }

    .btn-action-toggle {
        background: rgba(255, 255, 255, 0.05);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        transition: none;
    }
    .btn-action-toggle:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.2);
    }



    /* Profile Badge Dropdown & Wrapper */
    .admin-profile-wrapper {
        position: relative;
    }

    .admin-profile-badge {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        padding: 6px 16px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        cursor: pointer;
        user-select: none;
        transition: background 0.15s, border-color 0.15s;
    }
    
    .admin-profile-badge:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.12);
    }

    .admin-profile-wrapper.active .chevron-icon {
        transform: rotate(180deg);
    }

    .profile-avatar {
        flex-shrink: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1A3D95 0%, #3B82F6 100%);
        border: 1.5px solid rgba(255, 255, 255, 0.25);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 800;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .admin-profile-info {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .admin-profile-name {
        font-size: 0.875rem;
        font-weight: 700;
        color: white;
    }

    .admin-profile-role {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    /* Dropdown Panel */
    .profile-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 280px;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        padding: 1.25rem;
        display: none;
        flex-direction: column;
        gap: 12px;
        z-index: 200;
    }

    .profile-dropdown.active {
        display: flex;
        animation: scale-up-dropdown 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes scale-up-dropdown {
        from { opacity: 0; transform: scale(0.95) translateY(-5px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .dropdown-header {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .dropdown-avatar {
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1A3D95 0%, #3B82F6 100%);
        border: 2px solid rgba(255, 255, 255, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        font-weight: 800;
    }

    .dropdown-user-details {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .dropdown-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: white;
    }

    .dropdown-role {
        font-size: 0.75rem;
        color: var(--primary-accent);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .dropdown-email {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    .dropdown-divider {
        height: 1px;
        background: rgba(255, 255, 255, 0.06);
        margin: 4px 0;
    }

    .dropdown-menu-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .dropdown-item {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 10px;
        color: #E2E8F0;
        background: transparent;
        border: none;
        font-size: 0.85rem;
        font-weight: 700;
        text-align: left;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }

    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .dropdown-item svg {
        color: var(--text-muted);
    }

    .dropdown-item.logout-item {
        color: var(--danger);
        background: rgba(244, 63, 94, 0.02);
    }

    .dropdown-item.logout-item:hover {
        background: rgba(244, 63, 94, 0.1);
        color: #FF4D6D;
    }

    .dropdown-item.logout-item svg {
        color: var(--danger);
    }

    /* Glassmorphic Modal Styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(9, 13, 24, 0.65);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        width: 100%;
        max-width: 440px;
        background: rgba(15, 23, 42, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
        overflow: hidden;
    }

    .animate-scale-in {
        animation: modal-scale-in 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes modal-scale-in {
        from { opacity: 0; transform: scale(0.92); }
        to { opacity: 1; transform: scale(1); }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-close-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close-btn:hover {
        color: white;
    }

    .modal-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-group input {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        padding: 12px 14px;
        border-radius: 10px;
        color: white;
        font-size: 0.9rem;
        font-weight: 600;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
        border-color: var(--primary-accent);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
    }

    .modal-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        background: rgba(0, 0, 0, 0.15);
    }

    /* Header Action Controls (Notification + Profile) */
    .header-actions {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .header-notification-btn {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        color: white;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        transition: none;
    }

    .header-notification-btn:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.12);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    }

    .header-notification-btn:active {
        transform: none;
    }

    .notification-dot {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--danger); /* Glowing red/danger alert dot */
        box-shadow: 0 0 8px var(--danger);
        animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(244, 63, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(244, 63, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(244, 63, 94, 0); }
    }

    /* Notification Wrapper and Dropdown Panel */
    .notification-wrapper {
        position: relative;
    }

    .notification-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 320px;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        padding: 1.25rem;
        display: none;
        flex-direction: column;
        gap: 12px;
        z-index: 200;
    }

    .notification-dropdown.active {
        display: flex;
        animation: scale-up-dropdown 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .notification-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .notification-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: white;
        letter-spacing: -0.01em;
    }

    .notification-count-badge {
        font-size: 0.7rem;
        font-weight: 800;
        background: rgba(59, 130, 246, 0.15);
        color: var(--primary-accent);
        padding: 2px 8px;
        border-radius: 100px;
        text-transform: uppercase;
        border: 1px solid rgba(59, 130, 246, 0.25);
    }

    .notification-menu-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-height: 280px;
        overflow-y: auto;
        padding-right: 4px;
    }

    /* Custom scrollbar for notifications */
    .notification-menu-list::-webkit-scrollbar {
        width: 4px;
    }
    .notification-menu-list::-webkit-scrollbar-track {
        background: transparent;
    }
    .notification-menu-list::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .notification-item {
        display: flex;
        gap: 12px;
        padding: 12px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.03);
        text-decoration: none;
        color: inherit;
        transition: background 0.15s, border-color 0.15s, transform 0.1s;
    }

    .notification-item:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.08);
        transform: translateY(-1px);
    }

    .notification-icon-wrapper {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.2);
        color: var(--primary-accent);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notification-item-content {
        display: flex;
        flex-direction: column;
        gap: 3px;
        flex-grow: 1;
    }

    .notification-item-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: white;
    }

    .notification-item-desc {
        font-size: 0.75rem;
        color: var(--text-muted);
        line-height: 1.3;
    }

    .notification-item-desc strong {
        color: #E2E8F0;
        font-weight: 600;
    }

    .notification-item-time {
        font-size: 0.65rem;
        color: var(--text-muted);
        opacity: 0.7;
        margin-top: 2px;
    }

    .notification-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
        text-align: center;
        color: var(--text-muted);
        gap: 8px;
    }

    .notification-empty-state p {
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Premium Glassmorphic Pagination */
    .table-pagination-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        background: rgba(255, 255, 255, 0.01);
    }

    .pagination-info {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .pagination-controls {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .pagination-btn {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.06);
        color: #E2E8F0;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s, color 0.15s;
    }

    .pagination-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.15);
        color: white;
    }

    .pagination-btn.active {
        background: var(--primary-accent);
        border-color: rgba(59, 130, 246, 0.3);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
    }

    .pagination-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>
@endsection

@section('content')
@php
    $activeTab = request()->query('tab', 'overview');
    $pendingCount = 0;
@endphp

<div class="dashboard-layout">
    
    <!-- LEFT SIDEBAR: Fixed Left Panel -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="navbar-brand">
                <div class="navbar-brand-icon">Rp</div>
                <div class="brand-text-label" style="font-weight: 800; font-size: 1.4rem; color: white;">Rupia<span style="color: #3b82f6;">Chat</span></div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="sidebar-menu">
            <!-- Tab 1: Overview & Graph -->
            <a href="?tab=overview" class="sidebar-link {{ $activeTab === 'overview' ? 'active' : '' }}" title="Ringkasan & Analisis">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="3" width="7" height="7" rx="1.5" style="stroke: #10B981;"/> <!-- Dynamic emerald green accent square -->
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                <span class="sidebar-label">Ringkasan & Analisis</span>
            </a>

            <!-- Tab 2: Users Database -->
            <a href="?tab=users" class="sidebar-link {{ $activeTab === 'users' ? 'active' : '' }}" title="Database Pengguna">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" style="stroke: #3B82F6;"/> <!-- Dynamic royal blue accent silhouette -->
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" style="stroke: #3B82F6;"/>
                </svg>
                <span class="sidebar-label">Database User</span>
            </a>

            <!-- Tab 3: Recent Feature Purchases -->
            <a href="?tab=purchases" class="sidebar-link {{ $activeTab === 'purchases' ? 'active' : '' }}" title="Riwayat Transaksi">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0" style="stroke: #F59E0B;"/>
                </svg>
                <span class="sidebar-label">Riwayat Transaksi</span>
            </a>
        </nav>


    </aside>

    <!-- RIGHT CONTENT AREA -->
    <div class="main-content" id="main-content">
        <!-- Dashboard Top Header Bar -->
        <div class="dashboard-header animate-fade-in">
            <div style="display: flex; flex-direction: column; gap: 12px; align-items: flex-start;">
                <div>
                    @if($activeTab === 'overview')
                        <h1>Ringkasan & Analisis</h1>
                        <p>Pemantauan kinerja dan metrik utama portal RupiaChat</p>
                    @elseif($activeTab === 'users')
                        <h1>Database User</h1>
                        <p>Kelola data dan status aktif pengguna RupiaChat</p>
                    @elseif($activeTab === 'purchases')
                        <h1>Riwayat Transaksi</h1>
                        <p>Daftar pembelian fitur terbaru oleh pengguna</p>
                    @endif
                </div>

                <!-- Kurs USD/IDR Badge -->
                @if($activeTab === 'overview')
                <div class="admin-profile-badge" style="cursor: default; gap: 10px; padding: 6px 14px; background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.2);">
                    <div class="stat-icon-wrapper" style="width:28px; height:28px; margin-bottom:0; --icon-bg: rgba(245, 158, 11, 0.1); --icon-border: rgba(245, 158, 11, 0.2); --icon-color: #F59E0B; border-radius: 6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v12M6 12h12"/>
                        </svg>
                    </div>
                    <div class="admin-profile-info">
                        <span class="admin-profile-role" style="font-size: 0.65rem; color: #F59E0B; font-weight: 800;">KURS USD/IDR</span>
                        <div style="display: flex; align-items: baseline; gap: 8px;">
                            <span class="admin-profile-name" style="font-size: 0.95rem;">{{ $stats['usd_rate'] }}</span>
                            <span style="font-size: 0.65rem; color: var(--text-muted);">Update: {{ $stats['rate_updated_at'] }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Header Action Controls (Notification Bell & Profile Badge) -->
            <div class="header-actions">
                <!-- Notification Bell Wrapper -->
                <div class="notification-wrapper" id="notificationWrapper">
                    <button class="header-notification-btn" id="notificationBellBtn" title="Notifications">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" style="stroke: #F59E0B;"/>
                        </svg>
                        @if($pendingCount > 0)
                            <span class="notification-dot"></span>
                        @endif
                    </button>

                    <!-- Glassmorphic Notification Dropdown Menu -->
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <span class="notification-title">Notifikasi</span>
                            @if($pendingCount > 0)
                                <span class="notification-count-badge">{{ $pendingCount }} Pending</span>
                            @endif
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        
                        <div class="notification-menu-list">
                            @if($pendingCount > 0)
                                @foreach(collect($purchases)->where('status', 'Pending') as $p)
                                    <a href="?tab=overview" class="notification-item">
                                        <div class="notification-icon-wrapper">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"/>
                                                <polyline points="12 6 12 12 16 14"/>
                                            </svg>
                                        </div>
                                        <div class="notification-item-content">
                                            <div class="notification-item-title">
                                                Permintaan: {{ $p['feature'] }}
                                            </div>
                                            <div class="notification-item-desc">
                                                User <strong>{{ $p['user'] }}</strong> mengajukan persetujuan sebesar <strong>{{ $p['amount'] }}</strong>.
                                            </div>
                                            <div class="notification-item-time">
                                                {{ date('d M Y, H:i', strtotime($p['date'])) }}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="notification-empty-state">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.5; color: var(--text-muted);">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                        <polyline points="22 4 12 14.01 9 11.01"/>
                                    </svg>
                                    <p>Semua transaksi telah diproses!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Admin Profile Group with Dropdown -->
                <div class="admin-profile-wrapper" id="adminProfileWrapper">
                    <div class="admin-profile-badge" id="adminProfileBadge">
                        <div class="profile-avatar">A</div>
                        <div class="admin-profile-info">
                            <span class="admin-profile-name">Admin Portal</span>
                            <span class="admin-profile-role">Super Admin</span>
                        </div>
                        <svg class="chevron-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; opacity: 0.7; transition: transform 0.2s;">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>

                    <!-- Glassmorphic Profile Dropdown Menu -->
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="dropdown-header">
                            <div class="dropdown-avatar">A</div>
                            <div class="dropdown-user-details">
                                <span class="dropdown-name">Admin Portal</span>
                                <span class="dropdown-role">Super Admin</span>
                                <span class="dropdown-email">admin@rupiachat.com</span>
                            </div>
                        </div>
                        
                        <div class="dropdown-divider"></div>
                        
                        <div class="dropdown-menu-list">
                            <!-- Change Password Option -->
                            <button class="dropdown-item" id="changePasswordBtn">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                Ganti Password
                            </button>
                            
                            <!-- Logout Option -->
                            <form action="{{ route('admin.logout') }}" method="POST" style="width: 100%;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-item">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                        <polyline points="16 17 21 12 16 7"/>
                                        <line x1="21" y1="12" x2="9" y2="12"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast success alerts -->
        @if(session('success_message'))
            <div class="alert-toast animate-fade-in">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                {{ session('success_message') }}
            </div>
        @endif

        <!-- TAB 1: OVERVIEW & ANALISIS BULANAN -->
        @if($activeTab === 'overview')
        <div class="stats-grid">
            <!-- Card 1: Revenue -->
            <div class="glass-panel stat-card" style="--stat-glow: rgba(59, 130, 246, 0.25);">
                <div class="stat-icon-wrapper" style="--icon-bg: rgba(37, 99, 235, 0.1); --icon-border: rgba(37, 99, 235, 0.2); --icon-color: #3B82F6;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <span class="stat-title">Total Revenue</span>
                <span class="stat-value">{{ $stats['revenue'] }}</span>
                <span class="stat-change positive">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                    +14.8% this month
                </span>
            </div>

            <!-- Card 2: Voice Call -->
            <div class="glass-panel stat-card" style="--stat-glow: rgba(16, 185, 129, 0.2);">
                <div class="stat-icon-wrapper" style="--icon-bg: rgba(16, 185, 129, 0.1); --icon-border: rgba(16, 185, 129, 0.2); --icon-color: #10B981;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                </div>
                <span class="stat-title">Voice Calls Sold</span>
                <span class="stat-value">{{ $stats['voice_calls'] }}</span>
                <span class="stat-change positive">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                    +8.2% this month
                </span>
            </div>

            <!-- Card 3: Video Call -->
            <div class="glass-panel stat-card" style="--stat-glow: rgba(168, 85, 247, 0.2);">
                <div class="stat-icon-wrapper" style="--icon-bg: rgba(168, 85, 247, 0.1); --icon-border: rgba(168, 85, 247, 0.2); --icon-color: #A855F7;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="23 7 16 12 23 17 23 7"/>
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                    </svg>
                </div>
                <span class="stat-title">Video Calls Sold</span>
                <span class="stat-value">{{ $stats['video_calls'] }}</span>
                <span class="stat-change positive">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                    +19.1% this month
                </span>
            </div>

            <!-- Card 4: Buat Grup -->
            <div class="glass-panel stat-card" style="--stat-glow: rgba(59, 130, 246, 0.25);">
                <div class="stat-icon-wrapper" style="--icon-bg: rgba(59, 130, 246, 0.1); --icon-border: rgba(59, 130, 246, 0.2); --icon-color: #3B82F6;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <span class="stat-title">Group Access Sold</span>
                <span class="stat-value">{{ $stats['group_access'] }}</span>
                <span class="stat-change positive">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                    +11.4% this month
                </span>
            </div>

            <!-- Card 5: Voice Note -->
            <div class="glass-panel stat-card" style="--stat-glow: rgba(236, 72, 153, 0.25);">
                <div class="stat-icon-wrapper" style="--icon-bg: rgba(236, 72, 153, 0.1); --icon-border: rgba(236, 72, 153, 0.2); --icon-color: #EC4899;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"/>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                        <line x1="12" y1="19" x2="12" y2="22"/>
                    </svg>
                </div>
                <span class="stat-title">Voice Notes Sold</span>
                <span class="stat-value">{{ $stats['voice_notes'] }}</span>
                <span class="stat-change positive">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                    +5.3% this month
                </span>
            </div>

            <!-- Card 6: VIP Member -->
            <div class="glass-panel stat-card" style="--stat-glow: rgba(245, 158, 11, 0.25);">
                <div class="stat-icon-wrapper" style="--icon-bg: rgba(245, 158, 11, 0.1); --icon-border: rgba(245, 158, 11, 0.2); --icon-color: #F59E0B;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
                <span class="stat-title">VIP Memberships Sold</span>
                <span class="stat-value">{{ $stats['vip_memberships'] }}</span>
                <span class="stat-change positive">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                    +22.1% this month
                </span>
            </div>
        </div>

        <!-- Analisis Bulanan (Monthly Analytics Chart Card) -->
        <div class="glass-panel chart-panel">
            <div class="chart-title-group">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: white; letter-spacing: -0.02em; margin-bottom: 0.25rem;">Analisis Kinerja Bulanan</h3>
                    <p style="color: var(--text-muted); font-size: 0.85rem;">Tren grafik total pendapatan RupiaChat 6 bulan terakhir</p>
                </div>
            </div>

            <!-- Pure CSS/HTML Bar Chart -->
            <div class="chart-grid">
                <div class="chart-grid-lines">
                    <div class="chart-line"><span class="chart-line-label">Rp 30jt</span></div>
                    <div class="chart-line"><span class="chart-line-label">Rp 20jt</span></div>
                    <div class="chart-line"><span class="chart-line-label">Rp 10jt</span></div>
                    <div style="width: 100%; height: 1px;"></div>
                </div>

                <!-- Des (Rp 12.000.000) -->
                <div class="chart-bar-wrapper">
                    <div class="chart-bar" style="height: 40%;">
                        <div class="chart-tooltip">Rp 12.000.000</div>
                    </div>
                    <span class="chart-label">Des</span>
                </div>

                <!-- Jan (Rp 15.500.000) -->
                <div class="chart-bar-wrapper">
                    <div class="chart-bar" style="height: 52%;">
                        <div class="chart-tooltip">Rp 15.500.000</div>
                    </div>
                    <span class="chart-label">Jan</span>
                </div>

                <!-- Feb (Rp 14.000.000) -->
                <div class="chart-bar-wrapper">
                    <div class="chart-bar" style="height: 47%;">
                        <div class="chart-tooltip">Rp 14.000.000</div>
                    </div>
                    <span class="chart-label">Feb</span>
                </div>

                <!-- Mar (Rp 18.200.000) -->
                <div class="chart-bar-wrapper">
                    <div class="chart-bar" style="height: 61%;">
                        <div class="chart-tooltip">Rp 18.200.000</div>
                    </div>
                    <span class="chart-label">Mar</span>
                </div>

                <!-- Apr (Rp 21.000.000) -->
                <div class="chart-bar-wrapper">
                    <div class="chart-bar" style="height: 70%;">
                        <div class="chart-tooltip">Rp 21.000.000</div>
                    </div>
                    <span class="chart-label">Apr</span>
                </div>

                <!-- Mei (Rp 24.500.000 - Terkini) -->
                <div class="chart-bar-wrapper">
                    <div class="chart-bar" style="height: 82%; background: linear-gradient(to top, #3B82F6 0%, #10B981 100%);">
                        <div class="chart-tooltip">Rp 24.500.000 (Mei)</div>
                    </div>
                    <span class="chart-label" style="color: #10B981;">Mei</span>
                </div>
            </div>

            <!-- Insights Summary Row -->
            <div class="insights-row">
                <div class="insight-item">
                    <span class="insight-title">Rata-rata Pendapatan</span>
                    <span class="insight-value">Rp 17.533.333 / bln</span>
                </div>
                <div class="insight-item">
                    <span class="insight-title">Bulan Teraktif</span>
                    <span class="insight-value" style="color: #10B981;">Mei (Rp 24.500.000)</span>
                </div>
                <div class="insight-item">
                    <span class="insight-title">Pertumbuhan Volume</span>
                    <span class="insight-value" style="color: #10B981;">+16.6% Vs April</span>
                </div>
            </div>
        </div>

        </div>
        @endif

        <!-- TAB 3: RIWAYAT TRANSAKSI -->
        @if($activeTab === 'purchases')
        <!-- Table Section -->
        <div class="glass-panel table-section">
            <div class="table-header">
                <h2>Recent Feature Purchases</h2>
                <div class="search-container">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Search transactions...">
                </div>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>User</th>
                            <th>Feature</th>
                            <th>Amount</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $loggedTransactions = $purchases;
                        @endphp
                        @foreach($loggedTransactions as $purchase)
                        <tr>
                            <td style="color: var(--text-muted); font-family: monospace; font-size: 0.85rem; font-weight: 600;">
                                #TXN-{{ str_pad($purchase['id'], 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ substr($purchase['user'], 0, 1) }}</div>
                                    <span style="color: white; font-weight: 600; text-transform: capitalize;">{{ $purchase['user'] }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="feature-name-group">
                                    @if($purchase['feature'] == 'Voice Call')
                                        <div class="feature-icon-box" style="background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.15);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                            </svg>
                                        </div>
                                        <span style="color: #E2E8F0;">Voice Call</span>
                                    @elseif($purchase['feature'] == 'Video Call')
                                        <div class="feature-icon-box" style="background: rgba(168, 85, 247, 0.08); border-color: rgba(168, 85, 247, 0.15);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#A855F7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="23 7 16 12 23 17 23 7"/>
                                                <rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>
                                            </svg>
                                        </div>
                                        <span style="color: #E2E8F0;">Video Call</span>
                                    @elseif($purchase['feature'] == 'Buat Grup')
                                        <div class="feature-icon-box" style="background: rgba(59, 130, 246, 0.08); border-color: rgba(59, 130, 246, 0.15);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            </svg>
                                        </div>
                                        <span style="color: #E2E8F0;">Buat Grup</span>
                                    @elseif($purchase['feature'] == 'Voice Note' || $purchase['feature'] == 'Sticker Pack')
                                        <div class="feature-icon-box" style="background: rgba(236, 72, 153, 0.08); border-color: rgba(236, 72, 153, 0.15);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#EC4899" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"/>
                                                <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                                                <line x1="12" y1="19" x2="12" y2="22"/>
                                            </svg>
                                        </div>
                                        <span style="color: #E2E8F0;">Voice Note</span>
                                    @elseif($purchase['feature'] == 'Tema Pro')
                                        <div class="feature-icon-box" style="background: rgba(99, 102, 241, 0.08); border-color: rgba(99, 102, 241, 0.15);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6366F1" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 14.7255 3.09032 17.1962 4.85857 19C5.03457 19.176 5.09914 19.431 5.02111 19.6719C4.84883 20.2037 4.70881 20.7675 4.60609 21.3529C4.5441 21.7062 4.8166 22 5.1758 22H12Z"/>
                                                <circle cx="7.5" cy="10.5" r="1.5"/>
                                                <circle cx="11.5" cy="7.5" r="1.5"/>
                                                <circle cx="16.5" cy="9.5" r="1.5"/>
                                                <circle cx="15.5" cy="14.5" r="1.5"/>
                                            </svg>
                                        </div>
                                        <span style="color: #E2E8F0;">Tema Pro</span>
                                    @else
                                        <div class="feature-icon-box" style="background: rgba(20, 184, 166, 0.08); border-color: rgba(20, 184, 166, 0.15);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#14B8A6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                                <circle cx="12" cy="12" r="10" stroke="#14B8A6" stroke-width="2" fill="none"/>
                                            </svg>
                                        </div>
                                        <span style="color: #E2E8F0;">{{ $purchase['feature'] }}</span>
                                    @endif
                                </div>
                            </td>
                            <td style="color: white; font-weight: 700; white-space: nowrap;">{{ $purchase['amount'] }}</td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;">{{ date('M d, Y H:i', strtotime($purchase['date'])) }}</td>
                            <td>
                                <span class="badge {{ strtolower($purchase['status']) }}">
                                    {{ $purchase['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- TAB 2: DATABASE USER -->
        @if($activeTab === 'users')
        <div class="glass-panel table-section">
            <div class="table-header">
                <h2>Registered Users Database</h2>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <form method="GET" action="{{ route('admin.dashboard') }}" style="margin: 0;">
                        <input type="hidden" name="tab" value="users">
                        <div class="search-container" style="width: auto; padding: 6px 12px 6px 16px;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(255,255,255,0.4);"><line x1="21" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="3" y2="18"/></svg>
                            <select name="sort" onchange="this.form.submit()" style="background: transparent; border: none; color: white; outline: none; cursor: pointer; font-size: 0.85rem; font-weight: 600; appearance: none; -webkit-appearance: none; padding-right: 4px; padding-left: 2px;">
                                <option value="newest" {{ request('sort') != 'oldest' ? 'selected' : '' }} style="background: #0F172A; color: white;">Terbaru (Newest)</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }} style="background: #0F172A; color: white;">Terlama (Oldest)</option>
                            </select>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: rgba(255,255,255,0.4);"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                    </form>
                    <div class="search-container">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" placeholder="Search users...">
                    </div>
                </div>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No.</th>
                            <th>User</th>
                            <th>Email Address</th>
                            <th>Phone Number</th>
                            <th>Balance (Saldo)</th>
                            <th>Joined Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td style="color: var(--text-muted); font-weight: 700; text-align: center; width: 50px; white-space: nowrap;">{{ $loop->iteration }}</td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ substr($user['name'], 0, 1) }}</div>
                                    <span style="color: white; font-weight: 600; text-transform: capitalize;">{{ $user['name'] }}</span>
                                </div>
                            </td>
                            <td style="color: #E2E8F0;">{{ $user['email'] }}</td>
                            <td style="color: var(--text-muted); font-family: monospace; white-space: nowrap;">{{ $user['phone'] }}</td>
                            <td style="color: #3B82F6; font-weight: 700; white-space: nowrap;">Rp {{ number_format($user['balance'], 0, ',', '.') }}</td>
                            <td style="color: var(--text-muted);">{{ date('M d, Y', strtotime($user['joined'])) }}</td>
                            <td>
                                <span class="badge {{ strtolower($user['status']) }}">
                                    {{ $user['status'] }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.users.toggle', $user['id']) }}?tab=users" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn-action-toggle">
                                        {{ $user['status'] === 'Active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Premium Glassmorphic Pagination controls -->
            <div class="table-pagination-footer" id="usersPagination">
                <span class="pagination-info" id="usersPaginationInfo">Showing 1 to 10 of 0 users</span>
                <div class="pagination-controls" id="usersPaginationControls">
                    <!-- Page buttons will be generated here by Javascript dynamically -->
                </div>
            </div>
        </div>
        @endif


    </div>
</div>

<!-- Glassmorphic Change Password Modal -->
<div class="modal-overlay" id="changePasswordModal">
    <div class="modal-content glass-panel animate-scale-in">
        <div class="modal-header">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: white;">Ganti Password Administrator</h3>
            <button class="modal-close-btn" id="closeModalBtn" title="Close">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <form id="changePasswordForm" onsubmit="event.preventDefault(); alert('Sukses: Password admin berhasil diubah!'); document.getElementById('changePasswordModal').classList.remove('active'); this.reset();">
            <div class="modal-body">
                <div class="form-group">
                    <label for="oldPassword">Password Lama</label>
                    <input type="password" id="oldPassword" required placeholder="Masukkan password lama">
                </div>
                <div class="form-group">
                    <label for="newPassword">Password Baru</label>
                    <input type="password" id="newPassword" required placeholder="Masukkan password baru">
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Konfirmasi Password Baru</label>
                    <input type="password" id="confirmPassword" required placeholder="Ulangi password baru">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-action-toggle" id="cancelModalBtn" style="padding: 10px 20px;">Batal</button>
                <button type="submit" class="btn-action-approve" style="padding: 10px 20px; background-color: var(--primary-accent); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">Simpan Sandi</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript for Admin Profile Dropdown and Change Password Modal -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('adminProfileWrapper');
        const badge = document.getElementById('adminProfileBadge');
        const dropdown = document.getElementById('profileDropdown');
        
        const notifWrapper = document.getElementById('notificationWrapper');
        const notifBellBtn = document.getElementById('notificationBellBtn');
        const notifDropdown = document.getElementById('notificationDropdown');

        const changePasswordBtn = document.getElementById('changePasswordBtn');
        const modal = document.getElementById('changePasswordModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelModalBtn = document.getElementById('cancelModalBtn');

        // Toggle profile dropdown panel
        badge.addEventListener('click', (e) => {
            e.stopPropagation();
            
            // Mutually close notification dropdown if open
            if (notifDropdown) {
                notifDropdown.classList.remove('active');
            }

            const isActive = dropdown.classList.contains('active');
            if (isActive) {
                dropdown.classList.remove('active');
                wrapper.classList.remove('active');
            } else {
                dropdown.classList.add('active');
                wrapper.classList.add('active');
            }
        });

        // Toggle notification dropdown panel
        if (notifBellBtn && notifDropdown) {
            notifBellBtn.addEventListener('click', (e) => {
                e.stopPropagation();

                // Mutually close profile dropdown if open
                if (dropdown) {
                    dropdown.classList.remove('active');
                    wrapper.classList.remove('active');
                }

                const isActive = notifDropdown.classList.contains('active');
                if (isActive) {
                    notifDropdown.classList.remove('active');
                } else {
                    notifDropdown.classList.add('active');
                }
            });
        }

        // Click outside dropdown to close both
        document.addEventListener('click', (e) => {
            // Close profile dropdown if clicked outside profile wrapper
            if (wrapper && !wrapper.contains(e.target)) {
                dropdown.classList.remove('active');
                wrapper.classList.remove('active');
            }
            // Close notification dropdown if clicked outside notification wrapper
            if (notifWrapper && !notifWrapper.contains(e.target)) {
                notifDropdown.classList.remove('active');
            }
        });

        // Open change password modal
        changePasswordBtn.addEventListener('click', () => {
            dropdown.classList.remove('active');
            wrapper.classList.remove('active');
            modal.classList.add('active');
        });

        // Close change password modal handlers
        const closeModal = () => {
            modal.classList.remove('active');
            document.getElementById('changePasswordForm').reset();
        };

        closeModalBtn.addEventListener('click', closeModal);
        cancelModalBtn.addEventListener('click', closeModal);

        // Click on modal background overlay to close
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // --- User Table Pagination & Search Javascript ---
        const rowsPerPage = 10;
        let currentPage = 1;
        
        const tableBody = document.querySelector('.data-table tbody');
        if (tableBody) {
            const allRows = Array.from(tableBody.querySelectorAll('tr'));
            let filteredRows = allRows;
            
            const paginationFooter = document.getElementById('usersPagination');
            const paginationInfo = document.getElementById('usersPaginationInfo');
            const paginationControls = document.getElementById('usersPaginationControls');
            const searchInput = document.querySelector('.search-container input');

            const updatePagination = () => {
                const totalRows = filteredRows.length;
                const totalPages = Math.ceil(totalRows / rowsPerPage);
                
                if (currentPage > totalPages) currentPage = Math.max(1, totalPages);

                const startIdx = (currentPage - 1) * rowsPerPage;
                const endIdx = startIdx + rowsPerPage;

                // Toggle visibility
                allRows.forEach(row => row.style.display = 'none');
                filteredRows.slice(startIdx, endIdx).forEach(row => row.style.display = '');

                // Update Info Label
                if (totalRows === 0) {
                    paginationInfo.textContent = 'Showing 0 to 0 of 0 users';
                } else {
                    const displayStart = startIdx + 1;
                    const displayEnd = Math.min(endIdx, totalRows);
                    paginationInfo.textContent = `Showing ${displayStart} to ${displayEnd} of ${totalRows} users`;
                }

                // Render page control buttons
                paginationControls.innerHTML = '';

                if (totalPages <= 1) return; // No buttons needed if only 1 page

                // Previous page button
                const prevBtn = document.createElement('button');
                prevBtn.type = 'button';
                prevBtn.className = `pagination-btn ${currentPage === 1 ? 'disabled' : ''}`;
                prevBtn.innerHTML = `
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                `;
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        updatePagination();
                    }
                });
                paginationControls.appendChild(prevBtn);

                // Numeric page buttons
                for (let i = 1; i <= totalPages; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.type = 'button';
                    pageBtn.className = `pagination-btn ${currentPage === i ? 'active' : ''}`;
                    pageBtn.textContent = i;
                    pageBtn.addEventListener('click', () => {
                        currentPage = i;
                        updatePagination();
                    });
                    paginationControls.appendChild(pageBtn);
                }

                // Next page button
                const nextBtn = document.createElement('button');
                nextBtn.type = 'button';
                nextBtn.className = `pagination-btn ${currentPage === totalPages ? 'disabled' : ''}`;
                nextBtn.innerHTML = `
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                `;
                nextBtn.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        updatePagination();
                    }
                });
                paginationControls.appendChild(nextBtn);
            };

            // Search input filter integration
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    const query = e.target.value.toLowerCase().trim();
                    
                    filteredRows = allRows.filter(row => {
                        const userName = row.querySelector('.user-info span')?.textContent.toLowerCase() || '';
                        const userEmail = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
                        return userName.includes(query) || userEmail.includes(query);
                    });

                    currentPage = 1;
                    updatePagination();
                });
            }

            // Run initial pagination load
            updatePagination();
        }
    });
</script>

@endsection
