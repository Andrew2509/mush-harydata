<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
<meta charset="utf-8" /><title>{{ !$config ? '' : $config->judul_web }} — Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"> <link rel="manifest" href="{{ asset('assets/site.webmanifest') }}"><meta name="theme-color" content="#0a0e17"><meta name="csrf-token" content="{{ csrf_token() }}"><meta content="{{ !$config ? '' : $config->deskripsi_web }}" name="description" /><meta content="SURS" name="author" /><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="description" content="Dashboard admin " /><link rel="icon" type="image/x-icon" href="{{ ENV('APP_IMAGE') }}" />

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>

{{-- Vendor CSS --}}
<link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

{{-- Vendor JS --}}
<script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<style>
/* ============================================
   CYBERPUNK ADMIN THEME — PREMIUM DARK
   ============================================ */

:root {
    --bg-primary: #0a0e17;
    --bg-secondary: #0f1423;
    --bg-card: rgba(15, 20, 40, 0.85);
    --bg-card-hover: rgba(20, 28, 55, 0.95);
    --bg-sidebar: rgba(8, 12, 24, 0.97);
    --bg-navbar: rgba(10, 14, 23, 0.85);
    --border-glass: rgba(0, 240, 255, 0.12);
    --border-glow: rgba(0, 240, 255, 0.25);
    --accent-cyan: #00f0ff;
    --accent-magenta: #ff00e5;
    --accent-yellow: #eef70b;
    --accent-green: #00ff88;
    --accent-red: #ff3366;
    --accent-orange: #ff8800;
    --text-primary: #e8eaf6;
    --text-secondary: #8892b0;
    --text-muted: #5a6380;
    --font-body: 'Inter', sans-serif;
    --font-heading: 'Orbitron', sans-serif;
    --glass-blur: blur(16px);
    --shadow-neon: 0 0 20px rgba(0, 240, 255, 0.15);
    --shadow-card: 0 8px 32px rgba(0, 0, 0, 0.4);
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ============================================
   LIGHT THEME VARIABLES
   ============================================ */
[data-theme="light"] {
    --bg-primary: #f0f2f8;
    --bg-secondary: #ffffff;
    --bg-card: rgba(255, 255, 255, 0.92);
    --bg-card-hover: rgba(240, 245, 255, 0.98);
    --bg-sidebar: rgba(255, 255, 255, 0.97);
    --bg-navbar: rgba(255, 255, 255, 0.88);
    --border-glass: rgba(0, 100, 200, 0.1);
    --border-glow: rgba(0, 120, 255, 0.2);
    --accent-cyan: #0077cc;
    --accent-magenta: #c800a1;
    --accent-yellow: #c5a600;
    --accent-green: #00995c;
    --accent-red: #dd1144;
    --accent-orange: #d97200;
    --text-primary: #1a1d2e;
    --text-secondary: #4a5068;
    --text-muted: #8892b0;
    --font-body: 'Inter', sans-serif;
    --font-heading: 'Orbitron', sans-serif;
    --glass-blur: blur(12px);
    --shadow-neon: 0 0 12px rgba(0, 100, 200, 0.08);
    --shadow-card: 0 4px 24px rgba(0, 0, 0, 0.06);
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ---- Light mode body overlay ---- */
[data-theme="light"] body::before {
    background:
        radial-gradient(ellipse at 20% 50%, rgba(0, 120, 255, 0.04) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 20%, rgba(200, 0, 161, 0.03) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 80%, rgba(0, 153, 92, 0.02) 0%, transparent 50%);
}

/* ---- Light mode scrollbar ---- */
[data-theme="light"] ::-webkit-scrollbar-track { background: #f0f2f8; }
[data-theme="light"] ::-webkit-scrollbar-thumb { background: rgba(0, 100, 200, 0.25); }
[data-theme="light"] ::-webkit-scrollbar-thumb:hover { background: #0077cc; }

/* ---- Light mode sidebar ---- */
[data-theme="light"] .bg-menu-theme {
    border-right-color: rgba(0, 100, 200, 0.08) !important;
}
[data-theme="light"] .bg-menu-theme .menu-inner > .menu-item.active > .menu-link {
    background: linear-gradient(135deg, rgba(0, 119, 204, 0.1), rgba(200, 0, 161, 0.05)) !important;
    border-color: rgba(0, 119, 204, 0.18) !important;
    box-shadow: 0 0 12px rgba(0, 119, 204, 0.08), inset 0 0 12px rgba(0, 119, 204, 0.04) !important;
}
[data-theme="light"] .layout-wrapper:not(.layout-horizontal) .bg-menu-theme .menu-inner > .menu-item.active:before {
    background: linear-gradient(180deg, #0077cc, #c800a1);
    box-shadow: 0 0 8px rgba(0, 119, 204, 0.3);
}

/* ---- Light mode app-brand ---- */
[data-theme="light"] .app-brand-text {
    color: #0077cc !important;
    text-shadow: none;
}

/* ---- Light mode navbar ---- */
[data-theme="light"] .layout-navbar {
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06) !important;
}
[data-theme="light"] .avatar-online img {
    border-color: #0077cc;
    box-shadow: 0 0 8px rgba(0, 119, 204, 0.15);
}

/* ---- Light mode forms ---- */
[data-theme="light"] .form-control,
[data-theme="light"] .form-select {
    background: rgba(255, 255, 255, 0.95) !important;
    border-color: rgba(0, 100, 200, 0.15) !important;
}
[data-theme="light"] .form-control:focus,
[data-theme="light"] .form-select:focus {
    background: #ffffff !important;
    border-color: #0077cc !important;
    box-shadow: 0 0 0 3px rgba(0, 119, 204, 0.1), 0 0 10px rgba(0, 119, 204, 0.06) !important;
}
[data-theme="light"] .input-group-text {
    background: rgba(0, 119, 204, 0.06) !important;
    border-color: rgba(0, 100, 200, 0.12) !important;
}

/* ---- Light mode tables ---- */
[data-theme="light"] .table thead th {
    background: rgba(0, 119, 204, 0.05) !important;
    border-bottom-color: rgba(0, 100, 200, 0.12) !important;
}
[data-theme="light"] .table tbody tr:hover {
    background: rgba(0, 119, 204, 0.03) !important;
}
[data-theme="light"] .table-striped tbody tr:nth-of-type(odd) {
    background: rgba(0, 0, 0, 0.015) !important;
}
[data-theme="light"] .table-dark {
    --bs-table-hover-bg: rgba(0, 119, 204, 0.04) !important;
}

/* ---- Light mode DataTables ---- */
[data-theme="light"] .dataTables_wrapper .dataTables_filter input {
    background: #ffffff !important;
    border-color: rgba(0, 100, 200, 0.15) !important;
}
[data-theme="light"] .dataTables_wrapper .dataTables_length select {
    background: #ffffff !important;
    border-color: rgba(0, 100, 200, 0.15) !important;
}
[data-theme="light"] .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, rgba(0, 119, 204, 0.12), rgba(200, 0, 161, 0.06)) !important;
}

/* ---- Light mode modal ---- */
[data-theme="light"] .modal-content {
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12) !important;
}

/* ---- Light mode SweetAlert ---- */
[data-theme="light"] .swal2-popup {
    background: #ffffff !important;
    border-color: rgba(0, 100, 200, 0.1) !important;
}

/* ---- Light mode Summernote ---- */
[data-theme="light"] .note-editor .note-toolbar {
    background: #f8f9fc !important;
}
[data-theme="light"] .note-editor .note-editing-area .note-editable {
    background: #ffffff !important;
}
[data-theme="light"] .note-btn {
    color: var(--text-secondary) !important;
    border-color: rgba(0, 100, 200, 0.12) !important;
}

/* ---- Light mode btn-close ---- */
[data-theme="light"] .btn-close {
    filter: none;
}

/* ---- Light mode Morris chart ---- */
[data-theme="light"] .morris-hover {
    background: #ffffff !important;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08) !important;
}

/* ---- Light mode badges ---- */
[data-theme="light"] .badge.bg-success, [data-theme="light"] .bg-label-success { background: rgba(0, 153, 92, 0.12) !important; }
[data-theme="light"] .badge.bg-danger, [data-theme="light"] .bg-label-danger { background: rgba(221, 17, 68, 0.1) !important; }
[data-theme="light"] .badge.bg-warning, [data-theme="light"] .bg-label-warning { background: rgba(197, 166, 0, 0.1) !important; }
[data-theme="light"] .badge.bg-info, [data-theme="light"] .bg-label-info { background: rgba(0, 119, 204, 0.1) !important; }

/* ---- Light mode status rows ---- */
[data-theme="light"] .table-success { background: rgba(0, 153, 92, 0.06) !important; }
[data-theme="light"] .table-danger { background: rgba(221, 17, 68, 0.06) !important; }
[data-theme="light"] .table-warning { background: rgba(197, 166, 0, 0.06) !important; }
[data-theme="light"] .table-info { background: rgba(0, 119, 204, 0.06) !important; }

/* ---- Light mode layout overlay ---- */
[data-theme="light"] .layout-overlay {
    background: rgba(0, 0, 0, 0.25) !important;
}

/* ---- Light mode Spectrum ---- */
[data-theme="light"] .sp-container {
    background: #ffffff !important;
}
[data-theme="light"] .sp-input {
    background: #f8f9fc !important;
}

/* ============================================
   THEME TOGGLE BUTTON
   ============================================ */
.theme-toggle-btn {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid var(--border-glass);
    background: var(--bg-card);
    color: var(--accent-cyan);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    transition: var(--transition-smooth);
    backdrop-filter: var(--glass-blur);
    box-shadow: 0 0 10px rgba(0, 240, 255, 0.1);
    outline: none;
    margin-right: 12px;
}
.theme-toggle-btn:hover {
    transform: scale(1.1) rotate(15deg);
    border-color: var(--border-glow);
    box-shadow: 0 0 20px rgba(0, 240, 255, 0.2);
}
.theme-toggle-btn .toggle-icon {
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
}
.theme-toggle-btn:active .toggle-icon {
    transform: rotate(360deg);
}
[data-theme="light"] .theme-toggle-btn {
    box-shadow: 0 2px 10px rgba(0, 80, 200, 0.08);
}
[data-theme="light"] .theme-toggle-btn:hover {
    box-shadow: 0 4px 16px rgba(0, 80, 200, 0.15);
}

/* ---- Base ---- */
body {
    margin: 0;
    font-family: var(--font-body) !important;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.6;
    color: var(--text-primary) !important;
    background: var(--bg-primary) !important;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(0, 240, 255, 0.04) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 20%, rgba(255, 0, 229, 0.03) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 80%, rgba(0, 255, 136, 0.02) 0%, transparent 50%);
    pointer-events: none;
    z-index: 0;
}

/* ---- Scrollbar ---- */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: var(--bg-primary); }
::-webkit-scrollbar-thumb { background: rgba(0, 240, 255, 0.3); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: var(--accent-cyan); }

/* ============================================
   SIDEBAR
   ============================================ */
.bg-menu-theme {
    background: var(--bg-sidebar) !important;
    border-right: 1px solid var(--border-glass) !important;
    backdrop-filter: var(--glass-blur);
}

.layout-menu {
    background: var(--bg-sidebar) !important;
}

.bg-menu-theme .menu-link,
.bg-menu-theme .menu-horizontal-prev,
.bg-menu-theme .menu-horizontal-next {
    color: var(--text-secondary) !important;
    transition: var(--transition-smooth);
    border-radius: 8px;
    margin: 2px 8px;
}

.bg-menu-theme .menu-link:hover {
    color: var(--accent-cyan) !important;
    background: rgba(0, 240, 255, 0.06) !important;
    transform: translateX(4px);
}

.bg-menu-theme .menu-inner > .menu-item.active > .menu-link {
    color: var(--accent-cyan) !important;
    background: linear-gradient(135deg, rgba(0, 240, 255, 0.12), rgba(255, 0, 229, 0.06)) !important;
    border: 1px solid rgba(0, 240, 255, 0.2);
    box-shadow: 0 0 15px rgba(0, 240, 255, 0.1), inset 0 0 15px rgba(0, 240, 255, 0.05);
}

.bg-menu-theme .menu-item.open:not(.menu-item-closing) > .menu-toggle,
.bg-menu-theme .menu-item.active > .menu-link {
    color: var(--accent-cyan) !important;
}

.bg-menu-theme .menu-sub > .menu-item.active > .menu-link:not(.menu-toggle) {
    color: var(--accent-cyan) !important;
}

.bg-menu-theme .menu-sub > .menu-item > .menu-link:before {
    content: "";
    position: absolute;
    left: 0.375rem;
    width: 0.375rem;
    height: 0.375rem;
    border-radius: 50%;
    background: var(--accent-cyan);
    opacity: 0.4;
}

.bg-menu-theme .menu-sub > .menu-item.active > .menu-link:not(.menu-toggle):before {
    opacity: 1;
    box-shadow: 0 0 6px var(--accent-cyan);
}

.layout-wrapper:not(.layout-horizontal) .bg-menu-theme .menu-inner > .menu-item.active:before {
    content: "";
    position: absolute;
    right: 0;
    width: 3px;
    height: 30px;
    border-radius: 4px 0 0 4px;
    background: linear-gradient(180deg, var(--accent-cyan), var(--accent-magenta));
    box-shadow: 0 0 10px var(--accent-cyan);
}

.menu-vertical .menu-block,
.menu-vertical .menu-item .menu-link {
    padding: 0.5rem 1rem;
}

.menu-vertical,
.menu-vertical .menu-block,
.menu-vertical .menu-inner > .menu-header,
.menu-vertical .menu-inner > .menu-item {
    width: 16rem;
}

.menu-inner-shadow {
    display: none !important;
}

.menu-icon {
    color: var(--text-muted) !important;
    transition: var(--transition-smooth);
    font-size: 1rem;
}

.menu-item.active .menu-icon,
.menu-item:hover .menu-icon {
    color: var(--accent-cyan) !important;
    filter: drop-shadow(0 0 4px rgba(0, 240, 255, 0.5));
}

/* Sidebar brand */
.app-brand {
    padding: 1.2rem 1.5rem !important;
    border-bottom: 1px solid var(--border-glass);
}

.app-brand-text {
    font-family: var(--font-heading) !important;
    color: var(--accent-cyan) !important;
    text-shadow: 0 0 10px rgba(0, 240, 255, 0.4);
}

/* ============================================
   NAVBAR
   ============================================ */
.bg-navbar-theme,
.layout-navbar {
    background: var(--bg-navbar) !important;
    backdrop-filter: var(--glass-blur) !important;
    border-bottom: 1px solid var(--border-glass) !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
}

.bg-navbar-theme .navbar-nav > .nav-link,
.bg-navbar-theme .navbar-nav > .nav-item > .nav-link,
.bg-navbar-theme .navbar-nav > .nav > .nav-item > .nav-link {
    color: var(--text-primary) !important;
}

.avatar-online img {
    border: 2px solid var(--accent-cyan);
    box-shadow: 0 0 10px rgba(0, 240, 255, 0.3);
    border-radius: 50%;
}

.dropdown-menu {
    background: var(--bg-secondary) !important;
    border: 1px solid var(--border-glass) !important;
    backdrop-filter: var(--glass-blur);
    box-shadow: var(--shadow-card) !important;
}

.dropdown-item {
    color: var(--text-secondary) !important;
    transition: var(--transition-smooth);
}

.dropdown-item:hover {
    color: var(--accent-cyan) !important;
    background: rgba(0, 240, 255, 0.08) !important;
}

.dropdown-divider {
    border-top-color: var(--border-glass) !important;
}

/* ============================================
   FOOTER
   ============================================ */
.bg-footer-theme,
.content-footer {
    background: var(--bg-sidebar) !important;
    border-top: 1px solid var(--border-glass) !important;
    color: var(--text-muted) !important;
}

.footer-link {
    color: var(--accent-cyan) !important;
    text-decoration: none;
}

/* ============================================
   CONTENT AREA
   ============================================ */
.layout-page {
    background: transparent !important;
}

.content-wrapper {
    background: transparent !important;
}

/* ============================================
   CARDS — GLASSMORPHISM
   ============================================ */
.card {
    background: var(--bg-card) !important;
    border: 1px solid var(--border-glass) !important;
    border-radius: 16px !important;
    backdrop-filter: var(--glass-blur);
    box-shadow: var(--shadow-card) !important;
    transition: var(--transition-smooth);
    overflow: hidden;
}

.card:hover {
    border-color: var(--border-glow) !important;
    box-shadow: var(--shadow-card), var(--shadow-neon) !important;
    transform: translateY(-2px);
}

.card-header {
    background: transparent !important;
    border-bottom: 1px solid var(--border-glass) !important;
    color: var(--text-primary) !important;
    font-family: var(--font-heading);
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    padding: 1.2rem 1.5rem !important;
}

.card-body {
    color: var(--text-primary) !important;
    padding: 1.5rem !important;
}

/* ============================================
   TYPOGRAPHY
   ============================================ */
h1,h2,h3,h4,h5,h6 {
    color: var(--text-primary) !important;
}

.page-title, .header-title {
    font-family: var(--font-heading) !important;
    font-size: 1rem !important;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--accent-cyan) !important;
    text-shadow: 0 0 8px rgba(0, 240, 255, 0.2);
}

.text-muted { color: var(--text-muted) !important; }
.fw-semibold { color: var(--text-primary) !important; }
small, .small { color: var(--text-secondary) !important; }

/* ============================================
   FORMS
   ============================================ */
.form-control,
.form-select {
    background: rgba(15, 20, 40, 0.8) !important;
    border: 1px solid rgba(0, 240, 255, 0.15) !important;
    color: var(--text-primary) !important;
    border-radius: 10px !important;
    padding: 0.6rem 1rem !important;
    transition: var(--transition-smooth);
    font-size: 0.875rem;
}

.form-control:focus,
.form-select:focus {
    border-color: var(--accent-cyan) !important;
    box-shadow: 0 0 0 3px rgba(0, 240, 255, 0.12), 0 0 15px rgba(0, 240, 255, 0.1) !important;
    background: rgba(15, 20, 40, 0.95) !important;
    outline: none !important;
}

.form-control::placeholder {
    color: var(--text-muted) !important;
}

.form-label, .col-form-label {
    color: var(--text-secondary) !important;
    font-weight: 500;
    font-size: 0.8rem;
    letter-spacing: 0.3px;
}

.input-group-text {
    background: rgba(0, 240, 255, 0.08) !important;
    border: 1px solid rgba(0, 240, 255, 0.15) !important;
    color: var(--accent-cyan) !important;
}

/* ============================================
   BUTTONS
   ============================================ */
.btn-primary {
    background: linear-gradient(135deg, #0066ff, #00ccff) !important;
    border: none !important;
    color: #fff !important;
    border-radius: 10px !important;
    font-weight: 600;
    letter-spacing: 0.3px;
    padding: 0.5rem 1.5rem;
    transition: var(--transition-smooth);
    box-shadow: 0 4px 15px rgba(0, 102, 255, 0.25);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 102, 255, 0.4);
}

.btn-view-site {
    display: flex;
    align-items: center;
    padding: 8px 16px;
    background: rgba(0, 240, 255, 0.05);
    border: 1px solid rgba(0, 240, 255, 0.15);
    border-radius: 12px;
    color: var(--accent-cyan);
    text-decoration: none;
    transition: var(--transition-smooth);
}

.btn-view-site:hover {
    background: rgba(0, 240, 255, 0.12);
    border-color: var(--accent-cyan);
    box-shadow: 0 0 15px rgba(0, 240, 255, 0.2);
    transform: translateY(-1px);
    color: var(--accent-cyan);
}

.btn-view-site i {
    font-size: 0.9rem;
}

.btn-view-site span {
    font-size: 0.75rem;
    font-weight: 700;
    font-family: var(--font-heading);
    letter-spacing: 0.5px;
}

.btn-secondary {
    background: rgba(0, 240, 255, 0.1) !important;
    border: 1px solid rgba(0, 240, 255, 0.2) !important;
    color: var(--accent-cyan) !important;
    border-radius: 10px !important;
    transition: var(--transition-smooth);
}

.btn-secondary:hover {
    background: rgba(0, 240, 255, 0.2) !important;
    box-shadow: 0 0 15px rgba(0, 240, 255, 0.2);
}

.btn-danger {
    background: linear-gradient(135deg, #ff1744, #ff6090) !important;
    border: none !important;
    border-radius: 10px !important;
    box-shadow: 0 4px 15px rgba(255, 23, 68, 0.25);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 23, 68, 0.4);
}

.btn-info {
    background: linear-gradient(135deg, #00bcd4, #00e5ff) !important;
    border: none !important;
    border-radius: 10px !important;
    color: #fff !important;
    box-shadow: 0 4px 15px rgba(0, 188, 212, 0.25);
}

.btn-info:hover {
    transform: translateY(-2px);
}

.btn-warning {
    background: linear-gradient(135deg, #ff9800, #ffc107) !important;
    border: none !important;
    border-radius: 10px !important;
    color: #000 !important;
}

.btn-success {
    background: linear-gradient(135deg, #00c853, #69f0ae) !important;
    border: none !important;
    border-radius: 10px !important;
    color: #000 !important;
}

.btn-sm {
    padding: 0.35rem 0.8rem;
    font-size: 0.78rem;
    border-radius: 8px !important;
}

/* ============================================
   TABLES — DARK THEME
   ============================================ */
.table {
    color: var(--text-primary) !important;
    border-color: var(--border-glass) !important;
}

.table thead th {
    background: rgba(0, 240, 255, 0.06) !important;
    color: var(--accent-cyan) !important;
    border-bottom: 2px solid rgba(0, 240, 255, 0.15) !important;
    font-family: var(--font-heading);
    font-size: 0.7rem;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 0.8rem 1rem !important;
    font-weight: 600;
    white-space: nowrap;
}

.table tbody td,
.table tbody th {
    border-color: rgba(255, 255, 255, 0.04) !important;
    padding: 0.75rem 1rem !important;
    vertical-align: middle;
    font-size: 0.82rem;
    color: var(--text-primary) !important;
}

.table tbody tr {
    transition: var(--transition-smooth);
    background: transparent !important;
}

.table tbody tr:hover {
    background: rgba(0, 240, 255, 0.04) !important;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: rgba(255, 255, 255, 0.015) !important;
}

.table-dark {
    --bs-table-bg: transparent !important;
    --bs-table-striped-bg: rgba(255, 255, 255, 0.015) !important;
    --bs-table-hover-bg: rgba(0, 240, 255, 0.04) !important;
    --bs-table-active-bg: rgba(0, 240, 255, 0.06) !important;
    background: transparent !important;
    color: var(--text-primary) !important;
    border-color: var(--border-glass) !important;
}

.table-dark thead th {
    background: rgba(0, 240, 255, 0.06) !important;
    color: var(--accent-cyan) !important;
}

/* Status row colors */
.table-success { background: rgba(0, 255, 136, 0.08) !important; }
.table-success td, .table-success th { color: var(--accent-green) !important; }
.table-danger { background: rgba(255, 51, 102, 0.08) !important; }
.table-danger td, .table-danger th { color: var(--accent-red) !important; }
.table-warning { background: rgba(238, 247, 11, 0.06) !important; }
.table-warning td, .table-warning th { color: var(--accent-yellow) !important; }
.table-info { background: rgba(0, 240, 255, 0.06) !important; }
.table-info td, .table-info th { color: var(--accent-cyan) !important; }

/* DataTables */
.dataTables_wrapper .dataTables_filter input {
    background: rgba(15, 20, 40, 0.8) !important;
    border: 1px solid rgba(0, 240, 255, 0.15) !important;
    color: var(--text-primary) !important;
    border-radius: 8px !important;
    padding: 0.4rem 0.8rem;
}

.dataTables_wrapper .dataTables_filter input:focus {
    border-color: var(--accent-cyan) !important;
    box-shadow: 0 0 10px rgba(0, 240, 255, 0.15) !important;
}

.dataTables_wrapper .dataTables_length select {
    background: rgba(15, 20, 40, 0.8) !important;
    border: 1px solid rgba(0, 240, 255, 0.15) !important;
    color: var(--text-primary) !important;
    border-radius: 8px !important;
}

.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_filter label,
.dataTables_wrapper .dataTables_length label {
    color: var(--text-secondary) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    color: var(--text-secondary) !important;
    background: transparent !important;
    border: 1px solid var(--border-glass) !important;
    border-radius: 8px !important;
    margin: 0 2px;
    transition: var(--transition-smooth);
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: rgba(0, 240, 255, 0.1) !important;
    color: var(--accent-cyan) !important;
    border-color: rgba(0, 240, 255, 0.3) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, rgba(0, 240, 255, 0.15), rgba(255, 0, 229, 0.08)) !important;
    color: var(--accent-cyan) !important;
    border-color: rgba(0, 240, 255, 0.3) !important;
    box-shadow: 0 0 10px rgba(0, 240, 255, 0.15);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    color: var(--text-muted) !important;
    opacity: 0.4;
}

/* ============================================
   BADGES
   ============================================ */
.badge {
    border-radius: 6px;
    font-weight: 500;
    letter-spacing: 0.3px;
}

.badge.bg-success, .bg-label-success { background: rgba(0, 255, 136, 0.15) !important; color: var(--accent-green) !important; }
.badge.bg-danger, .bg-label-danger { background: rgba(255, 51, 102, 0.15) !important; color: var(--accent-red) !important; }
.badge.bg-warning, .bg-label-warning { background: rgba(238, 247, 11, 0.15) !important; color: var(--accent-yellow) !important; }
.badge.bg-info, .bg-label-info { background: rgba(0, 240, 255, 0.15) !important; color: var(--accent-cyan) !important; }
.badge.bg-primary { background: linear-gradient(135deg, #0066ff, #00ccff) !important; color: #fff !important; }

/* ============================================
   ALERTS
   ============================================ */
.alert {
    border-radius: 12px !important;
    backdrop-filter: var(--glass-blur);
    border: 1px solid;
}

.alert-success {
    background: rgba(0, 255, 136, 0.08) !important;
    color: var(--accent-green) !important;
    border-color: rgba(0, 255, 136, 0.2) !important;
}

.alert-danger {
    background: rgba(255, 51, 102, 0.08) !important;
    color: var(--accent-red) !important;
    border-color: rgba(255, 51, 102, 0.2) !important;
}

.alert-info {
    background: rgba(0, 240, 255, 0.08) !important;
    color: var(--accent-cyan) !important;
    border-color: rgba(0, 240, 255, 0.2) !important;
}

.alert-warning {
    background: rgba(238, 247, 11, 0.08) !important;
    color: var(--accent-yellow) !important;
    border-color: rgba(238, 247, 11, 0.2) !important;
}

/* ============================================
   MODALS
   ============================================ */
.modal-content {
    background: var(--bg-secondary) !important;
    border: 1px solid var(--border-glass) !important;
    border-radius: 16px !important;
    backdrop-filter: var(--glass-blur);
    box-shadow: 0 16px 48px rgba(0, 0, 0, 0.5) !important;
}

.modal-header {
    border-bottom: 1px solid var(--border-glass) !important;
    color: var(--text-primary) !important;
}

.modal-title {
    font-family: var(--font-heading);
    color: var(--accent-cyan) !important;
}

.modal-body { color: var(--text-primary) !important; }

.modal-footer {
    border-top: 1px solid var(--border-glass) !important;
}

.btn-close {
    filter: invert(1) brightness(0.8);
}

/* ============================================
   SPINNER
   ============================================ */
.spinner-border {
    color: var(--accent-cyan) !important;
}

/* ============================================
   SUMMERNOTE DARK
   ============================================ */
.note-editor.note-frame {
    border: 1px solid rgba(0, 240, 255, 0.15) !important;
    border-radius: 10px !important;
    overflow: hidden;
}

.note-editor .note-toolbar {
    background: rgba(15, 20, 40, 0.9) !important;
    border-bottom: 1px solid var(--border-glass) !important;
}

.note-editor .note-editing-area .note-editable {
    background: rgba(15, 20, 40, 0.8) !important;
    color: var(--text-primary) !important;
}

.note-btn {
    background: transparent !important;
    color: var(--text-secondary) !important;
    border-color: var(--border-glass) !important;
}

.note-btn:hover {
    background: rgba(0, 240, 255, 0.1) !important;
    color: var(--accent-cyan) !important;
}

/* ============================================
   LINKS
   ============================================ */
a {
    color: var(--accent-cyan);
    text-decoration: none;
    transition: var(--transition-smooth);
}

a:hover {
    color: #66f7ff;
    text-shadow: 0 0 6px rgba(0, 240, 255, 0.3);
}

a.bg-primary:hover, a.bg-primary:focus {
    background-color: rgba(0, 240, 255, 0.15) !important;
}

/* ============================================
   MORRIS CHART HOVER
   ============================================ */
.morris-hover {
    background: var(--bg-secondary) !important;
    border: 1px solid var(--border-glass) !important;
    border-radius: 10px !important;
    color: var(--text-primary) !important;
    padding: 8px 12px !important;
    box-shadow: var(--shadow-card) !important;
}

.morris-hover-row-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--accent-cyan) !important;
    margin-bottom: 4px;
    font-family: var(--font-heading);
}

.morris-hover-point {
    font-size: 12px;
    color: var(--text-secondary) !important;
}

/* ============================================
   SWEETALERT DARK
   ============================================ */
.swal2-popup {
    background: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
    border: 1px solid var(--border-glass) !important;
    border-radius: 16px !important;
}

.swal2-title { color: var(--text-primary) !important; font-family: var(--font-heading) !important; }
.swal2-html-container { color: var(--text-secondary) !important; }

.swal2-confirm {
    background: linear-gradient(135deg, #0066ff, #00ccff) !important;
    border: none !important;
    border-radius: 10px !important;
}

.swal2-cancel {
    background: rgba(255, 51, 102, 0.15) !important;
    color: var(--accent-red) !important;
    border: 1px solid rgba(255, 51, 102, 0.3) !important;
    border-radius: 10px !important;
}

/* ============================================
   PAGE HEADER (optional breadcrumb area)
   ============================================ */
.container-xxl h3,
.container-xxl h4.page-title {
    font-family: var(--font-heading) !important;
}

/* ============================================
   ANIMATIONS
   ============================================ */
@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 5px rgba(0, 240, 255, 0.2); }
    50% { box-shadow: 0 0 20px rgba(0, 240, 255, 0.4); }
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.container-xxl {
    animation: fadeInUp 0.4s ease-out;
}

/* ============================================
   COLOR PICKER (Spectrum)
   ============================================ */
.sp-container {
    background: var(--bg-secondary) !important;
    border: 1px solid var(--border-glass) !important;
    border-radius: 10px !important;
}

.sp-input {
    background: rgba(15, 20, 40, 0.8) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-glass) !important;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 1199.98px) {
    .layout-menu {
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5) !important;
    }
}

/* ============================================
   LAYOUT OVERLAY
   ============================================ */
.layout-overlay {
    background: rgba(0, 0, 0, 0.6) !important;
}

/* Feather icons sizing */
.icon-lg {
    width: 28px;
    height: 28px;
}

.icon-dual-primary { color: var(--accent-cyan); }
.icon-dual-success { color: var(--accent-green); }
.icon-dual-info { color: #00bcd4; }
.icon-dual-danger { color: var(--accent-red); }

/* Prevent blue-ish background on page/content wrapper */
.bg-body {
    background: transparent !important;
}

</style>
</head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo" style="padding: 1.2rem 1.5rem;">
            <a href="{{ route('dashboard') }}" class="app-brand-link" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
                <img src="{{ ENV('APP_IMAGE') }}" alt="Logo" style="width:36px;height:36px;border-radius:8px;border:1px solid rgba(0,240,255,0.2);box-shadow:0 0 10px rgba(0,240,255,0.15);">
                <span style="font-family:'Orbitron',sans-serif;font-size:0.9rem;font-weight:700;color:#00f0ff;text-shadow:0 0 10px rgba(0,240,255,0.3);letter-spacing:1px;">ADMIN</span>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

         <ul class="menu-inner py-1">
            @auth

            {{-- ─── MENU UTAMA ─── --}}
            <li class="menu-header small text-uppercase" style="color:var(--text-muted);font-family:'Orbitron',sans-serif;font-size:0.6rem;letter-spacing:1.5px;padding:1rem 1rem 0.4rem;">
                <span>Menu Utama</span>
            </li>

            {{-- Dashboard --}}
            <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="menu-icon fas fa-tachometer-alt"></i>
                    <div>Dashboard</div>
                </a>
            </li>

            {{-- Pesanan --}}
            <li class="menu-item {{ request()->is('pesanan', 'data/joki', 'data/giftskin') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-shopping-cart"></i>
                    <div>Pesanan</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('pesanan') ? 'active' : '' }}">
                        <a href="{{ route('pesanan') }}" class="menu-link">
                            <div>Semua Pesanan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('data/joki') ? 'active' : '' }}">
                        <a href="{{ url('/data/joki') }}" class="menu-link">
                            <div>Pesanan Joki & Vilog</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('data/giftskin') ? 'active' : '' }}">
                        <a href="{{ url('/data/giftskin') }}" class="menu-link">
                            <div>Pesanan Gift Skin</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Latency Analysis --}}
            <li class="menu-item {{ request()->is('latency') ? 'active' : '' }}">
                <a href="{{ url('/latency') }}" class="menu-link">
                    <i class="menu-icon fas fa-chart-line"></i>
                    <div>Analisis Latensi</div>
                </a>
            </li>

            {{-- SUS Analysis --}}
            <li class="menu-item {{ request()->is('admin/sus*') ? 'active' : '' }}">
                <a href="{{ route('admin.sus.index') }}" class="menu-link">
                    <i class="menu-icon fas fa-poll-h"></i>
                    <div>Analisis Skor SUS</div>
                </a>
            </li>

            {{-- Member --}}
            <li class="menu-item {{ request()->is('member') ? 'active' : '' }}">
                <a href="{{ route('member') }}" class="menu-link">
                    <i class="menu-icon fas fa-users"></i>
                    <div>Member</div>
                </a>
            </li>

            {{-- Deposit --}}
            <li class="menu-item {{ request()->routeIs('userdeposit', 'user-deposit') ? 'active' : '' }}">
                <a href="{{ route('userdeposit') }}" class="menu-link">
                    <i class="menu-icon fas fa-piggy-bank"></i>
                    <div>Deposit Member</div>
                </a>
            </li>

            {{-- ─── KELOLA TOKO ─── --}}
            <li class="menu-header small text-uppercase" style="color:var(--text-muted);font-family:'Orbitron',sans-serif;font-size:0.6rem;letter-spacing:1.5px;padding:1rem 1rem 0.4rem;">
                <span>Kelola Toko</span>
            </li>

            {{-- Kategori --}}
            <li class="menu-item {{ request()->is('kategori') ? 'active' : '' }}">
                <a href="{{ route('kategori') }}" class="menu-link">
                    <i class="menu-icon fas fa-tags"></i>
                    <div>Kategori</div>
                </a>
            </li>

            {{-- Produk --}}
            <li class="menu-item {{ request()->routeIs('layanan', 'paket.index') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-box"></i>
                    <div>Produk</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('layanan') ? 'active' : '' }}">
                        <a href="{{ route('layanan') }}" class="menu-link">
                            <div>Layanan</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->routeIs('paket.index') ? 'active' : '' }}">
                        <a href="{{ route('paket.index') }}" class="menu-link">
                            <div>Paket Layanan</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Voucher --}}
            <li class="menu-item {{ request()->is('voucher') ? 'active' : '' }}">
                <a href="{{ route('voucher') }}" class="menu-link">
                    <i class="menu-icon fas fa-gift"></i>
                    <div>Voucher</div>
                </a>
            </li>

            {{-- Slider & Banner --}}
            <li class="menu-item {{ request()->is('berita') ? 'active' : '' }}">
                <a href="{{ route('berita') }}" class="menu-link">
                    <i class="menu-icon fas fa-images"></i>
                    <div>Slider & Banner</div>
                </a>
            </li>

            {{-- Pembayaran --}}
            <li class="menu-item {{ request()->is('method') ? 'active' : '' }}">
                <a href="{{ route('method') }}" class="menu-link">
                    <i class="menu-icon fas fa-credit-card"></i>
                    <div>Pembayaran</div>
                </a>
            </li>

            {{-- Rating Customer --}}
            <li class="menu-item {{ request()->is('rating-customer') ? 'active' : '' }}">
                <a href="{{ route('rating-customer') }}" class="menu-link">
                    <i class="menu-icon fas fa-star"></i>
                    <div>Rating Customer</div>
                </a>
            </li>

            {{-- ─── INTEGRASI ─── --}}
            <li class="menu-header small text-uppercase" style="color:var(--text-muted);font-family:'Orbitron',sans-serif;font-size:0.6rem;letter-spacing:1.5px;padding:1rem 1rem 0.4rem;">
                <span>Integrasi</span>
            </li>

            {{-- Provider --}}
            <li class="menu-item {{ request()->is('digiflazz/*', 'bangjeff/*', 'topupedia/*') ? 'active' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon fas fa-bolt"></i>
                    <div>Provider</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('digiflazz/*') ? 'active' : '' }}">
                        <a href="{{ route('digiflazz.prices') }}" class="menu-link">
                            <div>Digiflazz</div>
                        </a>
                    </li>
                    <li class="menu-item {{ request()->is('bangjeff/*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div>Bangjeff</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('bangjeff/balance') ? 'active' : '' }}">
                                <a href="{{ route('bangjeff.balance') }}" class="menu-link">
                                    <div>Cek Saldo</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('bangjeff/product') ? 'active' : '' }}">
                                <a href="{{ route('bangjeff.product') }}" class="menu-link">
                                    <div>Produk</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item {{ request()->is('topupedia/*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div>Topupedia</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('topupedia/balance') ? 'active' : '' }}">
                                <a href="{{ route('topupedia.balance') }}" class="menu-link">
                                    <div>Cek Saldo</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('topupedia/product') ? 'active' : '' }}">
                                <a href="{{ route('topupedia.product') }}" class="menu-link">
                                    <div>Produk</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            {{-- Pengaturan --}}
            <li class="menu-item {{ request()->is('setting/web') ? 'active' : '' }}">
                <a href="{{ url('/setting/web') }}" class="menu-link">
                    <i class="menu-icon fas fa-cog"></i>
                    <div>Pengaturan Website</div>
                </a>
            </li>

            {{-- Hubungi Kami --}}
            <li class="menu-item">
                <a href="https://wa.me/" target="_blank" class="menu-link">
                    <i class="menu-icon fab fa-whatsapp"></i>
                    <div>Hubungi Kami</div>
                </a>
            </li>

            @endauth
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm" style="color: var(--accent-cyan);"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
             

              <ul class="navbar-nav flex-row align-items-center ms-auto">

                <!-- View Website -->
                <li class="nav-item me-2">
                    <a href="{{ url('/') }}" target="_blank" class="btn-view-site">
                        <i class="fas fa-globe me-2"></i>
                        <span class="d-none d-md-inline">VIEW SITE</span>
                    </a>
                </li>

                <!-- Theme Toggle -->
                <li class="nav-item me-1">
                    <button class="theme-toggle-btn" id="themeToggleBtn" title="Toggle Light/Dark Mode">
                        <i class="toggle-icon fas fa-moon"></i>
                    </button>
                </li>

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    @auth
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                   <div class="avatar avatar-online">
                  <img src="{{ ENV('APP_IMAGE') }}"  alt="" class="w-px-30 h-auto " />
                </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="{{ ENV('APP_IMAGE') }}" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block">{{ ENV('APP_NAME') }}</span>
                            <small class="text-muted">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                   
                    <li>
                      <a class="dropdown-item" href="{{ url('/setting/web') }}">
                        <i class="bx bx-cog me-2"></i>
                        <span class="align-middle">Settings</span>
                      </a>
                    </li>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{route ('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item notify-item">
                            <i class="bx bx-power-off me-2"></i><span>Keluar</span>
                        </button>
                    </form>
                  </ul>
                  @endauth
                </li>
              </ul>
              
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
              @yield('content')
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme mt-5">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0" style="display:flex;align-items:center;gap:6px;">
                  <span style="color:var(--text-muted);font-size:0.78rem;">©</span>
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  <a href="{{env('APP_URL')}}" target="_blank" class="footer-link fw-bolder" style="font-family:'Orbitron',sans-serif;font-size:0.75rem;letter-spacing:1px;">{{env('APP_NAME')}}</a>
                  <span style="color:var(--text-muted);font-size:0.78rem;">— All rights reserved</span>
                </div>
              </div>
            </footer>

            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- News menu handler -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var newsEl = document.getElementById('menu-news');
        if (newsEl) {
            newsEl.addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Fitur Sedang Dalam Pengembangan',
                    text: 'Maaf, fitur ini sedang dalam pengembangan.',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
    </script>

    <!-- Core JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Third party JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>
      $('#summernote').summernote({
        placeholder: 'Silahkan Isi',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    </script>
    <script>
    $(document).ready(function() {
        var spectrumConfig = {
            preferredFormat: "hex",
            showInput: true,
            showInitial: true,
            showPalette: true,
            palette: [
                ["#ff0000", "#00ff00", "#0000ff"],
                ["#ffffff", "#000000", "#cccccc"]
            ],
            chooseText: "Pilih",
            cancelText: "Batal",
            change: function(color) {
                console.log("Warna terpilih:", color.toHexString());
            }
        };
        ["#colorbackground","#colorInputmel","#colorInputtih","#colorInputt","#colorInputtt","#colorInputi"].forEach(function(id) {
            if ($(id).length) $(id).spectrum(spectrumConfig);
        });
    });
</script>

<!-- Theme Toggle Script -->
<script>
(function() {
    const html = document.documentElement;
    const toggleBtn = document.getElementById('themeToggleBtn');
    const icon = toggleBtn ? toggleBtn.querySelector('.toggle-icon') : null;

    // Load saved theme
    const savedTheme = localStorage.getItem('admin-theme');
    if (savedTheme) {
        html.setAttribute('data-theme', savedTheme);
    }

    function updateIcon() {
        if (!icon) return;
        const currentTheme = html.getAttribute('data-theme');
        if (currentTheme === 'light') {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }
    }

    // Set icon on load
    updateIcon();

    // Toggle handler
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('admin-theme', newTheme);
            updateIcon();
        });
    }
})();
</script>

<!-- Load Spectrum CSS -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css">
<!-- Load Spectrum JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.js"></script>

  </body>
</html>
