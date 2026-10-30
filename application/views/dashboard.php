<?php
// Memperbaiki validasi core framework CodeIgniter agar halaman tidak blank putih
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<link rel="stylesheet" href="<?= base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">

<style>
    /* ==========================================================================
       OVERRIDE TEMA UTAMA: MODERN CBT LIGHT THEME (SESUAI GAMBAR)
       ========================================================================== */
    body, html {
        width: 100%;
        height: 100%;
        background-color: #f0f4f8 !important; 
        font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .content-wrapper.bg-custom-theme {
        background: #f0f4f8 !important;
        position: relative;
        min-height: 100vh;
        overflow-x: hidden;
        padding: 0 25px;
    }

    .content-wrapper.bg-custom-theme::before,
    .content-wrapper.bg-custom-theme::after,
    .orange-glow-left,
    .bottom-light-bar {
        display: none !important;
    }

    .card-stat-custom {
        border: none !important;
        border-radius: 16px !important;
        padding: 20px 24px !important;
        min-height: 145px;
        color: #ffffff !important;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05) !important;
        transition: transform 0.2s;
    }
    .card-stat-custom:hover {
        transform: translateY(-2px);
    }
    .card-stat-custom .stat-title {
        font-size: 14px;
        font-weight: 700;
        opacity: 0.95;
        margin-bottom: 4px;
        letter-spacing: 0.3px;
    }
    .card-stat-custom .stat-value {
        font-size: 34px;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 4px;
    }
    .card-stat-custom .stat-value-sub {
        font-size: 18px !important;
        font-weight: 600;
        opacity: 0.8;
    }
    .card-stat-custom .stat-desc {
        font-size: 12px;
        opacity: 0.85;
        font-weight: 500;
    }
    .card-stat-custom .stat-icon-bg {
        position: absolute;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 50px;
        opacity: 0.25;
    }

    .bg-card-orange { background: linear-gradient(135deg, #ff6b3d 0%, #ff8e6c 100%) !important; }
    .bg-card-blue   { background: linear-gradient(135deg, #1488cc 0%, #2b32b2 100%) !important; }
    .bg-card-green  { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; }
    .bg-card-amber  { background: linear-gradient(135deg, #f5a623 0%, #f8b739 100%) !important; }

    .circle-progress-card {
        position: absolute;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        width: 65px;
        height: 65px;
        border: 6px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        border-top: 6px solid #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
    }

    .check-circle-card-white {
        position: absolute;
        right: 24px;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: #ffffff !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .check-circle-card-white i { color: #11998e !important; }

    .card-bottom-progress {
        width: 85%;
        height: 6px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 10px;
        margin-top: 10px;
        overflow: hidden;
    }
    .card-bottom-progress-fill {
        width: 0%;
        height: 100%;
        background: #ffffff;
        border-radius: 10px;
        transition: width 0.5s ease-in-out;
    }

    .gauge-score-container {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 5px;
        position: relative;
    }
    .semi-gauge {
        position: relative;
        width: 90px;
        height: 45px;
        overflow: hidden;
        border-top-left-radius: 90px;
        border-top-right-radius: 90px;
        background: rgba(255, 255, 255, 0.2);
    }
    .semi-gauge::after {
        content: '';
        position: absolute;
        bottom: 0; left: 10px;
        width: 70px; height: 35px;
        border-top-left-radius: 70px;
        border-top-right-radius: 70px;
        background: #f5a623; 
    }
    .semi-gauge-fill {
        position: absolute;
        top: 0; left: 0;
        width: 90px; height: 90px;
        border-radius: 50%;
        background: #ffffff;
        transform: rotate(0deg);
        transform-origin: center bottom;
        transition: transform 0.5s ease-in-out;
    }
    .gauge-text {
        position: absolute;
        bottom: -2px; left: 0; right: 0;
        font-size: 19px;
        font-weight: 800;
        color: #ffffff;
        z-index: 10;
        text-align: center;
        line-height: 1.1;
    }
    .gauge-text span.gauge-label {
        font-size: 7px;
        display: block;
        font-weight: 500;
        opacity: 0.85;
        text-transform: uppercase;
        margin-top: 1px;
    }

    .sparkline-wave-bg {
        position: absolute;
        right: 20px;
        bottom: 30px;
        font-size: 45px;
        opacity: 0.35;
        color: #ffffff;
    }

    .main-table-card {
        background: #ffffff !important;
        border-radius: 16px !important;
        border: none !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
    }
    .main-table-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 20px 24px !important;
    }

    #wadah-tabel-live table th {
        background-color: #f8fafc !important;
        color: #64748b !important;
        font-size: 11px !important;
        text-transform: uppercase !important;
        font-weight: 700 !important;
        padding: 14px 16px !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    #wadah-tabel-live table td {
        vertical-align: middle !important;
        padding: 14px 16px !important;
        font-size: 13px !important;
        color: #334155 !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }

    @media (min-width: 992px) {
        .col-lg-custom-4 { flex: 0 0 25%; max-width: 25%; }
    }

    /* ==========================================================================
       STYLE BARU: POP-UP BANNER PROMO SELAMAT DATANG (PERBAIKAN FONT MENUMPUK)
       ========================================================================== */
    .banner-modal .modal-content {
        border: none !important;
        border-radius: 20px !important;
        background: linear-gradient(135deg, #1e3a8a, #3b82f6);
        color: #ffffff;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4) !important;
        position: relative;
    }

    .banner-modal .close-banner {
        position: absolute;
        top: 15px;
        right: 20px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 30px;
        font-weight: 300;
        cursor: pointer;
        background: none;
        border: none;
        outline: none;
        z-index: 1051;
        transition: color 0.2s;
    }
    .banner-modal .close-banner:hover {
        color: #ffffff;
    }

    .banner-body {
        padding: 45px 35px !important;
        text-align: center;
    }

    .banner-badge {
        background: #f43f5e;
        color: white;
        font-weight: 800;
        padding: 6px 18px;
        border-radius: 30px;
        display: inline-block;
        font-size: 0.75rem;
        letter-spacing: 1.5px;
        margin-bottom: 15px;
        box-shadow: 0 4px 10px rgba(244, 63, 94, 0.4);
    }

    .banner-title {
        font-size: 1.45rem;
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 5px;
        color: rgba(255, 255, 255, 0.95);
    }

    .banner-sub {
        font-size: 1.85rem; /* Dikecilkan sedikit agar lebih pas saat wrap text */
        font-weight: 800;
        color: #fdba74; 
        margin: 12px 0 18px 0;
        text-shadow: 0 2px 5px rgba(0,0,0,0.2);
        line-height: 1.35 !important; /* Mencegah tulisan menumpuk saat terpecah ke baris baru */
        word-wrap: break-word;
    }

    .banner-desc {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .btn-banner-action {
        background: #22c55e !important; 
        color: white !important;
        font-weight: 700;
        padding: 12px 45px;
        border-radius: 10px;
        border: none;
        font-size: 0.95rem;
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.4);
        transition: all 0.2s;
    }
    .btn-banner-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(34, 197, 94, 0.6);
        background: #16a34a !important;
    }

    /* ==========================================================================
       PREMIUM UI — tampilan real / glossy (override, tidak mengubah ID & logika)
       ========================================================================== */
    .content-wrapper.bg-custom-theme {
        background:
            radial-gradient(1100px 480px at 8% -8%, rgba(56,189,248,.20), transparent 62%),
            radial-gradient(900px 420px at 100% 0%, rgba(20,184,166,.15), transparent 58%),
            radial-gradient(800px 500px at 50% 110%, rgba(99,102,241,.10), transparent 60%),
            #eef3f9 !important;
    }
    @keyframes dsRise { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }
    @keyframes dsPulse { 0% { box-shadow: 0 0 0 0 rgba(255,255,255,.75); } 70% { box-shadow: 0 0 0 7px rgba(255,255,255,0); } 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); } }
    @keyframes dsFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @keyframes dsShine { 0% { transform: translateX(-120%) skewX(-20deg); } 55%,100% { transform: translateX(260%) skewX(-20deg); } }
    @keyframes dsStripe { from { background-position: 0 0; } to { background-position: 28px 0; } }

    .card-stat-custom {
        --glow: rgba(15,23,42,.35);
        height: calc(100% - 0px);
        border-radius: 22px !important;
        padding: 22px 26px !important;
        min-height: 156px;
        isolation: isolate;
        box-shadow:
            0 22px 36px -16px var(--glow),
            0 6px 12px -6px rgba(15,23,42,.18),
            inset 0 1.5px 0 rgba(255,255,255,.45),
            inset 0 -4px 10px rgba(0,0,0,.10) !important;
        transition: transform .28s cubic-bezier(.2,.8,.2,1), box-shadow .28s ease !important;
        animation: dsRise .7s cubic-bezier(.2,.8,.2,1) backwards;
    }
    .col-lg-custom-4:nth-child(2) .card-stat-custom { animation-delay: .08s; }
    .col-lg-custom-4:nth-child(3) .card-stat-custom { animation-delay: .16s; }
    .col-lg-custom-4:nth-child(4) .card-stat-custom { animation-delay: .24s; }
    .card-stat-custom:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 30px 44px -16px var(--glow), 0 8px 16px -6px rgba(15,23,42,.22), inset 0 1.5px 0 rgba(255,255,255,.55), inset 0 -4px 10px rgba(0,0,0,.10) !important;
    }
    .card-stat-custom::before {
        content: ''; position: absolute; inset: 0; z-index: 0; pointer-events: none; border-radius: inherit;
        background:
            linear-gradient(158deg, rgba(255,255,255,.34) 0%, rgba(255,255,255,.08) 38%, rgba(255,255,255,0) 60%),
            repeating-linear-gradient(135deg, rgba(255,255,255,.05) 0 2px, transparent 2px 9px);
    }
    .card-stat-custom::after {
        content: ''; position: absolute; z-index: 0; pointer-events: none;
        right: -46px; bottom: -70px; width: 200px; height: 200px; border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.26), rgba(255,255,255,0) 68%);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.14);
    }
    .card-stat-custom .stat-title, .card-stat-custom .stat-value, .card-stat-custom .stat-desc,
    .card-stat-custom .gauge-score-container, .card-stat-custom .card-bottom-progress { position: relative; z-index: 2; }
    .card-stat-custom .stat-title {
        font-size: 12.5px; text-transform: uppercase; letter-spacing: 1.1px; font-weight: 800; opacity: .95;
        display: flex; align-items: center; gap: 8px; margin-bottom: 6px;
    }
    .card-stat-custom .stat-value {
        font-size: 40px; letter-spacing: -1px; font-variant-numeric: tabular-nums;
        text-shadow: 0 3px 8px rgba(0,0,0,.20);
    }
    .card-stat-custom .stat-value-sub { font-size: 19px !important; letter-spacing: 0; text-shadow: none; }
    .card-stat-custom .stat-desc { font-size: 12.5px; opacity: .92; text-shadow: 0 1px 2px rgba(0,0,0,.12); }

    .bg-card-orange { background: linear-gradient(135deg, #ffa05c 0%, #ff6a3a 48%, #e8431c 100%) !important; --glow: rgba(240,90,40,.60); }
    .bg-card-blue   { background: linear-gradient(135deg, #38a8ff 0%, #2563eb 50%, #3a2fb8 100%) !important; --glow: rgba(37,99,235,.60); }
    .bg-card-green  { background: linear-gradient(135deg, #34e0a1 0%, #0fae8c 50%, #0b7a6c 100%) !important; --glow: rgba(15,174,140,.58); }
    .bg-card-amber  { background: linear-gradient(135deg, #ffd35a 0%, #f5a31a 50%, #dc7f08 100%) !important; --glow: rgba(235,150,15,.60); }

    .live-dot { width: 9px; height: 9px; border-radius: 50%; background: #fff; display: inline-block; animation: dsPulse 1.8s infinite; flex: 0 0 auto; }

    /* ubin ikon kaca */
    .card-stat-custom .stat-icon-bg, .card-stat-custom .sparkline-wave-bg {
        position: absolute; z-index: 2; right: 24px; top: 50%; bottom: auto; transform: translateY(-50%);
        width: 74px; height: 74px; border-radius: 22px; opacity: 1; font-size: inherit; color: inherit;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(150deg, rgba(255,255,255,.46), rgba(255,255,255,.12));
        border: 1px solid rgba(255,255,255,.55);
        box-shadow: inset 0 1.5px 0 rgba(255,255,255,.6), inset 0 -6px 12px rgba(0,0,0,.08), 0 12px 22px rgba(0,0,0,.20);
        -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px);
        transition: transform .3s ease;
    }
    .card-stat-custom:hover .stat-icon-bg, .card-stat-custom:hover .sparkline-wave-bg { transform: translateY(-50%) scale(1.07) rotate(-3deg); }
    .ds-ico { width: 48px; height: 48px; filter: drop-shadow(0 4px 5px rgba(0,0,0,.30)); }
    .ds-seal-inline { width: 26px; height: 26px; filter: drop-shadow(0 2px 3px rgba(0,0,0,.30)); }

    /* cincin persen (Siswa Aktif) */
    .ring-card {
        --p: 0; position: absolute; z-index: 2; right: 24px; top: 50%; transform: translateY(-50%);
        width: 82px; height: 82px; display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 16px; color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,.25);
    }
    .ring-card::before {
        content: ''; position: absolute; inset: 0; border-radius: 50%;
        background: conic-gradient(from 0deg, #ffffff calc(var(--p) * 1%), rgba(255,255,255,.22) 0);
        -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 9px), #000 calc(100% - 8px));
                mask: radial-gradient(farthest-side, transparent calc(100% - 9px), #000 calc(100% - 8px));
        filter: drop-shadow(0 0 7px rgba(255,255,255,.65));
        transition: background .5s ease;
    }
    .ring-card::after {
        content: ''; position: absolute; inset: 11px; border-radius: 50%;
        background: linear-gradient(150deg, rgba(255,255,255,.30), rgba(255,255,255,.08));
        border: 1px solid rgba(255,255,255,.35);
        box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 6px 14px rgba(0,0,0,.18);
    }
    .ring-card > span { position: relative; z-index: 1; }

    /* bar progres (Siswa Selesai) */
    .card-bottom-progress {
        width: 82%; height: 9px; margin-top: 12px; border-radius: 99px;
        background: rgba(0,0,0,.16); box-shadow: inset 0 2px 3px rgba(0,0,0,.22);
    }
    .card-bottom-progress-fill {
        border-radius: 99px; position: relative;
        background: linear-gradient(90deg, #ffffff, #d5fff0);
        background-image: repeating-linear-gradient(115deg, rgba(16,185,129,.22) 0 7px, transparent 7px 14px), linear-gradient(90deg, #ffffff, #d5fff0);
        background-size: 28px 100%, 100% 100%;
        animation: dsStripe 1.2s linear infinite;
        box-shadow: 0 0 10px rgba(255,255,255,.85);
    }

    /* gauge skor (SVG) */
    .gauge-premium { position: relative; width: 140px; height: 82px; margin-top: 2px; }
    .gauge-premium svg { width: 100%; height: 100%; overflow: visible; }
    .gauge-premium .g-track { fill: none; stroke: rgba(0,0,0,.18); stroke-width: 11; stroke-linecap: round; }
    .gauge-premium .g-arc {
        fill: none; stroke: url(#ds-gaugeGrad); stroke-width: 11; stroke-linecap: round;
        stroke-dasharray: 150.8; stroke-dashoffset: 150.8; transition: stroke-dashoffset .9s cubic-bezier(.2,.8,.2,1);
        filter: drop-shadow(0 0 5px rgba(255,255,255,.8));
    }
    .gauge-premium .g-ticks { fill: none; stroke: rgba(255,255,255,.7); stroke-width: 1.1; stroke-linecap: round; }
    .gauge-premium .gauge-text {
        position: absolute; left: 0; right: 0; bottom: 4px; font-size: 26px; font-weight: 800; line-height: 1;
        text-shadow: 0 2px 5px rgba(0,0,0,.25);
    }
    .gauge-premium .gauge-text .gauge-label { font-size: 7.5px; letter-spacing: .8px; margin-top: 4px; opacity: .9; }

    /* kartu tabel */
    .main-table-card {
        border: 1px solid rgba(203,213,225,.55) !important; border-radius: 22px !important;
        box-shadow: 0 24px 40px -22px rgba(15,23,42,.30), 0 4px 10px -4px rgba(15,23,42,.08), inset 0 1px 0 #fff !important;
        animation: dsRise .8s .3s cubic-bezier(.2,.8,.2,1) backwards;
    }
    #wadah-tabel-live table th {
        background: linear-gradient(180deg, #f8fafc 0%, #eef2f7 100%) !important;
        letter-spacing: .7px !important; color: #475569 !important;
        border-bottom: 1px solid #dbe3ec !important; box-shadow: inset 0 1px 0 #fff;
    }
    #wadah-tabel-live table tbody tr { transition: background-color .18s ease; }
    #wadah-tabel-live table tbody tr:hover { background-color: rgba(20,184,166,.07); }
    #wadah-tabel-live table tbody tr:hover td:first-child { box-shadow: inset 3px 0 0 #14b8a6; }

    /* ===== Pop-up selamat datang ===== */
    .banner-modal .modal-content {
        background:
            radial-gradient(520px 260px at 85% -10%, rgba(125,211,252,.45), transparent 60%),
            radial-gradient(420px 260px at 0% 110%, rgba(99,102,241,.55), transparent 62%),
            linear-gradient(150deg, #0b1b55 0%, #1e3a8a 45%, #2563eb 100%) !important;
        border: 1px solid rgba(255,255,255,.22) !important; border-radius: 26px !important;
        box-shadow: 0 40px 80px -20px rgba(8,18,60,.75), inset 0 1.5px 0 rgba(255,255,255,.28) !important;
    }
    .banner-modal .modal-content::before {
        content: ''; position: absolute; inset: 0; pointer-events: none; opacity: .5;
        background-image: linear-gradient(rgba(255,255,255,.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.06) 1px, transparent 1px);
        background-size: 26px 26px;
        -webkit-mask: radial-gradient(ellipse at 50% 20%, #000 0%, transparent 72%); mask: radial-gradient(ellipse at 50% 20%, #000 0%, transparent 72%);
    }
    .banner-modal .banner-body { position: relative; padding: 40px 34px 36px !important; }
    .banner-orb {
        width: 116px; height: 116px; margin: 0 auto 18px; border-radius: 50%; position: relative;
        display: flex; align-items: center; justify-content: center; animation: dsFloat 4s ease-in-out infinite;
        background: radial-gradient(circle at 32% 26%, rgba(255,255,255,.55), rgba(255,255,255,.14) 62%);
        border: 1px solid rgba(255,255,255,.55);
        box-shadow: inset 0 2px 0 rgba(255,255,255,.6), inset 0 -10px 18px rgba(30,58,138,.35), 0 18px 34px rgba(5,12,45,.55), 0 0 0 10px rgba(255,255,255,.07), 0 0 0 22px rgba(255,255,255,.04);
    }
    .banner-orb svg { width: 74px; height: 74px; filter: drop-shadow(0 8px 8px rgba(0,0,0,.35)); }
    .banner-badge {
        display: inline-flex !important; align-items: center; gap: 9px;
        background: linear-gradient(135deg, #fb7185, #e11d48) !important; border: 1px solid rgba(255,255,255,.35);
        box-shadow: 0 8px 18px rgba(225,29,72,.5), inset 0 1px 0 rgba(255,255,255,.5) !important;
    }
    .banner-title { text-transform: uppercase; letter-spacing: 3px; font-size: 1.05rem !important; font-weight: 700; opacity: .9; }
    .banner-sub {
        background: linear-gradient(180deg, #ffffff 0%, #fde2b8 55%, #fdba74 100%); -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent; color: transparent; text-shadow: none !important;
        filter: drop-shadow(0 3px 6px rgba(0,0,0,.35));
    }
    .banner-chips { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin: 0 0 26px; }
    .banner-chip {
        display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px 7px 8px; border-radius: 99px;
        font-size: 12px; font-weight: 700; color: #fff;
        background: linear-gradient(150deg, rgba(255,255,255,.22), rgba(255,255,255,.07));
        border: 1px solid rgba(255,255,255,.28); box-shadow: inset 0 1px 0 rgba(255,255,255,.35);
    }
    .banner-chip svg { width: 24px; height: 24px; filter: drop-shadow(0 2px 2px rgba(0,0,0,.3)); }
    .btn-banner-action {
        position: relative; overflow: hidden; display: inline-flex !important; align-items: center; gap: 10px;
        background: linear-gradient(135deg, #4ade80 0%, #16a34a 55%, #15803d 100%) !important;
        border: 1px solid rgba(255,255,255,.35) !important;
        box-shadow: 0 12px 26px rgba(22,163,74,.55), inset 0 1.5px 0 rgba(255,255,255,.55), inset 0 -3px 6px rgba(0,0,0,.18) !important;
        letter-spacing: .3px;
    }
    .btn-banner-action::after {
        content: ''; position: absolute; top: 0; bottom: 0; width: 40%; left: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.55), transparent);
        animation: dsShine 3.2s infinite; pointer-events: none;
    }
    .btn-banner-action svg { width: 18px; height: 18px; }
    @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation: none !important; transition: none !important; } }

    /* Kartu statistik di atas halo liquid (#stat-cards-row .lq-blob) */
    #stat-cards-row { position: relative; }
    #stat-cards-row .card-stat-custom { z-index: 1; }
</style>

<!-- Sprite ikon premium dashboard -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="ds-blue" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#bfdbfe"/><stop offset=".5" stop-color="#3b82f6"/><stop offset="1" stop-color="#1e3a8a"/></linearGradient><linearGradient id="ds-sky" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e0f2fe"/><stop offset=".5" stop-color="#38bdf8"/><stop offset="1" stop-color="#075985"/></linearGradient><linearGradient id="ds-teal" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#99f6e4"/><stop offset=".5" stop-color="#14b8a6"/><stop offset="1" stop-color="#115e59"/></linearGradient><linearGradient id="ds-green" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#bbf7d0"/><stop offset=".5" stop-color="#22c55e"/><stop offset="1" stop-color="#14532d"/></linearGradient><linearGradient id="ds-red" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fecaca"/><stop offset=".5" stop-color="#ef4444"/><stop offset="1" stop-color="#7f1d1d"/></linearGradient><linearGradient id="ds-orange" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fed7aa"/><stop offset=".5" stop-color="#f97316"/><stop offset="1" stop-color="#9a3412"/></linearGradient><linearGradient id="ds-gold" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fef08a"/><stop offset=".5" stop-color="#f59e0b"/><stop offset="1" stop-color="#b45309"/></linearGradient><linearGradient id="ds-purple" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e9d5ff"/><stop offset=".5" stop-color="#a855f7"/><stop offset="1" stop-color="#581c87"/></linearGradient><linearGradient id="ds-pink" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fbcfe8"/><stop offset=".5" stop-color="#ec4899"/><stop offset="1" stop-color="#831843"/></linearGradient><linearGradient id="ds-brown" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fcd9b6"/><stop offset=".5" stop-color="#b45309"/><stop offset="1" stop-color="#451a03"/></linearGradient><linearGradient id="ds-slate" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e2e8f0"/><stop offset=".5" stop-color="#94a3b8"/><stop offset="1" stop-color="#334155"/></linearGradient><linearGradient id="ds-dark" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#64748b"/><stop offset=".5" stop-color="#1e293b"/><stop offset="1" stop-color="#020617"/></linearGradient><linearGradient id="ds-white" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".5" stop-color="#f1f5f9"/><stop offset="1" stop-color="#cbd5e1"/></linearGradient><linearGradient id="ds-silver" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".5" stop-color="#cbd5e1"/><stop offset="1" stop-color="#64748b"/></linearGradient><linearGradient id="ds-skin" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffe8cc"/><stop offset=".5" stop-color="#f4b97a"/><stop offset="1" stop-color="#9a5b25"/></linearGradient><linearGradient id="ds-screen" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#38bdf8"/><stop offset=".5" stop-color="#2563eb"/><stop offset="1" stop-color="#1e1b4b"/></linearGradient><linearGradient id="ds-greenb" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#22c55e"/><stop offset=".5" stop-color="#166534"/><stop offset="1" stop-color="#052e16"/></linearGradient><linearGradient id="ds-hair" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#78350f"/><stop offset=".5" stop-color="#451a03"/><stop offset="1" stop-color="#1c0a00"/></linearGradient></defs><symbol id="ds-users3" viewBox="0 0 32 32"><path d="M0.6 27.5c0-4.6 3.3-7 7.6-7s7.6 2.4 7.6 7z" fill="url(#ds-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="8.2" cy="11.2" r="3.5" fill="url(#ds-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M4.3999999999999995 11.0a3.8 3.8 0 0 1 7.6 0c-1.75-2.10-4.20-3.15-3.80-3.15s-2.75 .3-3.80 3.15z" fill="url(#ds-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M16.2 27.5c0-4.6 3.3-7 7.6-7s7.6 2.4 7.6 7z" fill="url(#ds-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="23.8" cy="11.2" r="3.5" fill="url(#ds-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M20.0 11.0a3.8 3.8 0 0 1 7.6 0c-1.75-2.10-4.20-3.15-3.80-3.15s-2.75 .3-3.80 3.15z" fill="url(#ds-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M5.2 31c0-6.2 4.6-9.6 10.8-9.6S26.8 24.8 26.8 31z" fill="url(#ds-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="16" cy="12.6" r="4.9" fill="url(#ds-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M10.799999999999999 12.4a5.2 5.2 0 0 1 10.4 0c-2.45-2.94-5.88-4.41-5.20-4.41s-3.73 .3-5.20 4.41z" fill="url(#ds-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="ds-seal" viewBox="0 0 32 32"><circle cx="16" cy="16" r="11.6" fill="none" stroke="#14532d" stroke-opacity=".5" stroke-width="4.6" stroke-dasharray="3.50 1.70" stroke-dashoffset="-0.85"/><circle cx="16" cy="16" r="11.6" fill="none" stroke="url(#ds-green)" stroke-width="3.6" stroke-dasharray="2.60 2.60" stroke-dashoffset="-1.30"/><circle cx="16" cy="16" r="9.2" fill="url(#ds-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="1" fill="url(#ds-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="9.6" fill="url(#ds-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="9.6" fill="none" stroke="#fff" stroke-opacity=".7" stroke-width="1"/><path d="M7.5 14a9 9 0 0 1 17 0c-5-2.6-12-2.6-17 0z" fill="#fff" fill-opacity="0.3"/><path d="M11.2 16.4l3.4 3.5 6.4-7.4" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ds-check" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13.2" fill="url(#ds-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="13.2" fill="none" stroke="#fff" stroke-opacity=".7" stroke-width="1"/><path d="M4.8 14.5a11.4 11.4 0 0 1 22.4 0c-6.5-3.2-15.9-3.2-22.4 0z" fill="#fff" fill-opacity="0.28"/><path d="M9.5 16.6l4.4 4.5 8.6-9.6" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ds-chart" viewBox="0 0 32 32"><rect x="2.5" y="3.5" width="27" height="25" rx="3.4" fill="url(#ds-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M6.500 24.500h19" fill="none" stroke="#cbd5e1" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><rect x="6" y="19" width="3.8" height="5.5" rx="0.8" fill="url(#ds-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><rect x="11.5" y="15" width="3.8" height="9.5" rx="0.8" fill="url(#ds-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><rect x="17" y="17.5" width="3.8" height="7" rx="0.8" fill="url(#ds-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><rect x="22.5" y="12" width="3.8" height="12.5" rx="0.8" fill="url(#ds-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><path d="M7.900 15.500l5.500-4.500 5.500 3 5.200-6.500" fill="none" stroke="#ef4444" stroke-opacity="1" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/><circle cx="7.9" cy="15.5" r="1.4" fill="url(#ds-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="13.4" cy="11" r="1.4" fill="url(#ds-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="18.9" cy="14" r="1.4" fill="url(#ds-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="24.1" cy="7.5" r="1.4" fill="url(#ds-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ds-school" viewBox="0 0 32 32"><path d="M1.8 13.6L16 4l14.2 9.6z" fill="url(#ds-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="4" y="13.6" width="24" height="15.2" rx="0.8" fill="url(#ds-orange)" stroke="#9a3412" stroke-opacity=".5" stroke-width=".8"/><rect x="13.4" y="19.2" width="5.2" height="9.6" rx="1" fill="url(#ds-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="6.6" y="16.4" width="4.2" height="4.2" rx="0.6" fill="url(#ds-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><rect x="21.2" y="16.4" width="4.2" height="4.2" rx="0.6" fill="url(#ds-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="9.8" r="2" fill="url(#ds-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M16 4V1.6" fill="none" stroke="#b45309" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 1.6l4 1.2-4 1.2z" fill="#ef4444" fill-opacity="1"/></symbol><symbol id="ds-hourglass" viewBox="0 0 32 32"><rect x="5.5" y="2.5" width="21" height="3.2" rx="1.4" fill="url(#ds-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="5.5" y="26.3" width="21" height="3.2" rx="1.4" fill="url(#ds-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><path d="M8.5 5.7h15c0 5-5 7-7.5 10.3 2.5 3.300 7.5 5.300 7.500 10.300h-15c0-5 5-7 7.500-10.300C13.500 12.700 8.500 10.700 8.500 5.700z" fill="url(#ds-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M11 7.2h10c-.4 3-2.8 4.4-5 5.6-2.2-1.2-4.6-2.6-5-5.6z" fill="url(#ds-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M9.8 25.8c.8-3.6 3.6-5.2 6.200-5.200s5.400 1.600 6.200 5.200z" fill="url(#ds-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M16 13v7.500" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ds-userclock" viewBox="0 0 32 32"><path d="M2 27c0-5 4-8 9-8s9 3 9 8z" fill="url(#ds-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="11" cy="9.2" r="4.3" fill="url(#ds-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M6.4 9.0a4.6 4.6 0 0 1 9.2 0c-2.15-2.58-5.16-3.87-4.60-3.87s-3.31 .3-4.60 3.87z" fill="url(#ds-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="23.3" cy="22.8" r="7.2" fill="url(#ds-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><circle cx="23.3" cy="22.8" r="5.76" fill="url(#ds-white)" stroke="#334155" stroke-opacity=".35" stroke-width=".7"/><path d="M23.30 17.84L23.30 19.14" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M25.78 18.50L25.48 19.02" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M27.60 20.32L27.08 20.62" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M28.26 22.80L26.96 22.80" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M27.60 25.28L27.08 24.98" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M25.78 27.10L25.48 26.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M23.30 27.76L23.30 26.46" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M20.82 27.10L21.12 26.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M19.00 25.28L19.52 24.98" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M18.34 22.80L19.64 22.80" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M19.00 20.32L19.52 20.62" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M20.82 18.50L21.12 19.02" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M23.3 22.8V19.20" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M23.3 22.8l2.59 1.44" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="23.3" cy="22.8" r="1.3" fill="#ef4444"/></symbol></svg>

<!-- HTML STRUKTUR: POP-UP BANNER SELAMAT DATANG -->
<div class="modal fade banner-modal" id="welcomePromoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <button type="button" class="close-banner" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body banner-body">
                <div class="banner-orb"><svg class="" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ds-school"/></svg></div>
                <div class="banner-badge"><span class="live-dot"></span>INFO LIVE STATUS</div>
                <h3 class="banner-title">Selamat Datang</h3>
                <div class="banner-sub">SD Negeri 14 Sungai Kunyit</div>
                <p class="banner-desc">Sistem pemantauan ujian terintegrasi sedang berjalan. Semua pergerakan nilai, durasi, dan status siswa diperbarui secara real-time.</p>
                <div class="banner-chips">
                    <span class="banner-chip"><svg class="" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ds-chart"/></svg>Nilai</span>
                    <span class="banner-chip"><svg class="" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ds-hourglass"/></svg>Durasi</span>
                    <span class="banner-chip"><svg class="" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ds-userclock"/></svg>Status Siswa</span>
                </div>
                <button type="button" class="btn btn-banner-action" data-dismiss="modal">Masuk Dashboard<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="csrf-live-token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

<div class="content-wrapper bg-custom-theme">
    <div class="pt-4 pb-5 mx-auto" style="max-width: 1650px;">
        
        <!-- CARD WIDGET STATISTIK -->
        <div class="row px-2 mb-4" id="stat-cards-row">
            <div class="col-12 col-sm-6 col-lg-custom-4 mb-3">
                <div class="card-stat-custom bg-card-orange">
                    <div class="stat-title">Total Siswa</div>
                    <div class="stat-value" id="card-total-peserta">0</div>
                    <div class="stat-desc">Siswa Mendaftar</div>
                    <div class="stat-icon-bg"><svg class="ds-ico" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ds-users3"/></svg></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-custom-4 mb-3">
                <div class="card-stat-custom bg-card-blue">
                    <div class="stat-title"><span class="live-dot"></span>Siswa Aktif</div>
                    <div class="stat-value">
                        <span id="card-sedang-ujian">0</span><span class="stat-value-sub" id="card-total-append-blue"> / 0</span>
                    </div>
                    <div class="stat-desc" id="pct-sedang-ujian">Sedang Mengerjakan</div>
                    <div class="ring-card" id="ring-card"><span id="circle-pct-val">0%</span></div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-custom-4 mb-3">
                <div class="card-stat-custom bg-card-green">
                    <div class="stat-title">Siswa Selesai</div>
                    <div class="stat-value d-flex align-items-center gap-2">
                        <span id="card-selesai-ujian">0</span>
                        <svg class="ds-seal-inline" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ds-seal"/></svg>
                    </div>
                    <div class="stat-desc" id="pct-selesai-ujian">Selesai & Kumpul Ujian</div>
                    <div class="stat-icon-bg"><svg class="ds-ico" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ds-check"/></svg></div>
                    <div class="card-bottom-progress">
                        <div class="card-bottom-progress-fill" id="progress-hijau-fill"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-custom-4 mb-3">
                <div class="card-stat-custom bg-card-amber">
                    <div class="stat-title">Skor Rata-Rata</div>
                    <div class="gauge-score-container">
                        <div class="gauge-premium">
                            <svg viewBox="0 0 120 70" aria-hidden="true" focusable="false">
                                <defs><linearGradient id="ds-gaugeGrad" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#fff7cc"/><stop offset="1" stop-color="#ffffff"/></linearGradient></defs>
                                <path class="g-ticks" d="M4.5 60.0L1.0 60.0M7.2 42.8L3.9 41.8M15.1 27.4L12.3 25.3M27.4 15.1L25.3 12.3M42.8 7.2L41.8 3.9M60.0 4.5L60.0 1.0M77.2 7.2L78.2 3.9M92.6 15.1L94.7 12.3M104.9 27.4L107.7 25.3M112.8 42.8L116.1 41.8M115.5 60.0L119.0 60.0"/>
                                <path class="g-track" d="M12 60A48 48 0 0 1 108 60"/>
                                <path class="g-arc" id="gauge-arc" d="M12 60A48 48 0 0 1 108 60"/>
                            </svg>
                            <div class="gauge-text">
                                <span id="card-rata-nilai" style="display:block; margin-bottom:2px;">0.0</span>
                                <span class="gauge-label">Current Avg Score</span>
                            </div>
                            <div class="semi-gauge-fill" id="gauge-fill-id" style="display:none"></div>
                        </div>
                    </div>
                    <div class="stat-desc" style="margin-top: 5px;">Nilai Rata-rata Ujian</div>
                    <div class="sparkline-wave-bg"><svg class="ds-ico" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ds-chart"/></svg></div>
                </div>
                <span id="card-belum-mulai" style="display:none;">0</span>
                <span id="pct-belum-mulai" style="display:none;">0%</span>
            </div>
        </div>

        <!-- LIVE TABLE DATA VIEW -->
        <div class="row px-2 connectedSortable">
            <div class="col-12">
                <div class="card main-table-card overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive w-full">
                            <div id="wadah-tabel-live" class="w-full">
                                <?php $this->load->view('live_tabel'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Sinkron cincin persen & busur gauge dengan angka yang diisi oleh logika dashboard (tanpa mengubah logika itu).
    (function () {
        var LEN = 150.8;
        function setRing() {
            var el = document.getElementById('circle-pct-val'), ring = document.getElementById('ring-card');
            if (!el || !ring) return;
            var v = parseInt(el.textContent, 10) || 0;
            ring.style.setProperty('--p', Math.max(0, Math.min(100, v)));
        }
        function setGauge() {
            var el = document.getElementById('card-rata-nilai'), arc = document.getElementById('gauge-arc');
            if (!el || !arc) return;
            var v = parseFloat(String(el.textContent).replace(',', '.')) || 0;
            v = Math.max(0, Math.min(100, v));
            arc.style.strokeDashoffset = (LEN * (1 - v / 100)).toFixed(1);
        }
        function watch(id, fn) {
            var el = document.getElementById(id);
            if (!el || !window.MutationObserver) return;
            new MutationObserver(fn).observe(el, { childList: true, characterData: true, subtree: true });
        }
        watch('circle-pct-val', setRing);
        watch('card-rata-nilai', setGauge);
        setRing(); setGauge();
    })();
</script>
<script>
    adaJadwalUjian = '<?= isset($ada_ujian) ? count($ada_ujian) : 0 ?>';
    localStorage.setItem('ada_jadwal_ujian', adaJadwalUjian);
</script>
<script src="<?= base_url() ?>/assets/app/js/jquery.rowspanizer.js"></script>
<script src="<?= base_url() ?>/assets/app/js/dashboard.js"></script>

<script>
    var runtime_val_kelas  = "";
    var runtime_txt_kelas  = "Semua Kelas";
    var runtime_val_jadwal = "";
    var runtime_txt_jadwal = "Semua Jadwal Ujian";
    var master_backup_jadwal = [];
    var is_user_interacting = false; 

    function hitungUlangStatistikMurniDariDOM() {
        if (!window.jQuery) return;
        
        var tbody = jQuery('#wadah-tabel-live table tbody');
        if (!tbody.length) return;
        
        var baris = tbody.find('tr');
        if (baris.length === 0 || baris.hasClass('no-data') && baris.length === 1) return;

        var totalPeserta = 0;
        var sedangUjian = 0;
        var selesaiUjian = 0;
        var belumMulai = 0;
        var totalNilai = 0;
        var jumlahSiswaPunyaNilai = 0;

        var idxStatus = 9; 
        var idxNilai = 8;  

        baris.each(function() {
            var tr = jQuery(this);
            if (tr.hasClass('no-data') || tr.find('td').length <= 1) return;
            if (tr.css('display') === 'none') return;

            totalPeserta++;
            
            var txtStatus = tr.find('td').eq(idxStatus).text().toLowerCase().trim();
            var isSelesai = txtStatus.indexOf('selesai') > -1 || txtStatus.indexOf('complete') > -1 || txtStatus.indexOf('sudah') > -1;
            var isAktif   = txtStatus.indexOf('online') > -1 || txtStatus.indexOf('kerja') > -1 || txtStatus.indexOf('active') > -1 || txtStatus.indexOf('mulai') > -1;
            
            if (isSelesai) { 
                selesaiUjian++; 
            } else if (isAktif) { 
                sedangUjian++; 
            } else { 
                belumMulai++; 
            }

            var txtNilai = tr.find('td').eq(idxNilai).text().replace(/[^0-9.,]/g, '').replace(',', '.').trim();
            var nilaiFloat = parseFloat(txtNilai);

            if (!isNaN(nilaiFloat) && (isSelesai || isAktif)) {
                totalNilai += nilaiFloat;
                jumlahSiswaPunyaNilai++;
            }
        });

        var rataRata = jumlahSiswaPunyaNilai > 0 ? (totalNilai / jumlahSiswaPunyaNilai).toFixed(1) : "0.0";

        jQuery('#card-total-peserta').text(totalPeserta.toLocaleString('id-ID'));
        jQuery('#card-sedang-ujian').text(sedangUjian.toLocaleString('id-ID'));
        jQuery('#card-total-append-blue').text(' / ' + totalPeserta.toLocaleString('id-ID'));
        jQuery('#card-selesai-ujian').text(selesaiUjian.toLocaleString('id-ID'));
        jQuery('#card-rata-nilai').text(rataRata);

        var pctAktif = totalPeserta > 0 ? ((sedangUjian / totalPeserta) * 100).toFixed(0) : 0;
        jQuery('#circle-pct-val').text(pctAktif + '%');
        
        var pctSelesai = totalPeserta > 0 ? ((selesaiUjian / totalPeserta) * 100).toFixed(0) : 0;
        jQuery('#progress-hijau-fill').css('width', pctSelesai + '%');

        var nilaiMaksimalUjian = 100;
        var hitungSudutDerajat = (parseFloat(rataRata) / nilaiMaksimalUjian) * 180;
        if(hitungSudutDerajat > 180) hitungSudutDerajat = 180;
        jQuery('#gauge-fill-id').css('transform', 'rotate(' + hitungSudutDerajat + 'deg)');
    }

    function dapatkanDataTabelDanUpdateGrafik() {
        hitungUlangStatistikMurniDariDOM();
    }

    function terapkanRowspanizerOtomatis() {
        if (window.jQuery && jQuery('#wadah-tabel-live table').length) {
            jQuery('#wadah-tabel-live table td').removeAttr('rowspan').show();
            jQuery('#wadah-tabel-live table').rowspanizer({ vertical_align: 'middle' });
        }
    }

    function urutkanDanBeriRankingOtomatis() {
        if (!window.jQuery) return;
        var tBody = jQuery('#wadah-tabel-live table tbody');
        if (!tBody.length || tBody.find('tr').length === 0) return;

        var indexKolomNilai = 8, indexKolomRanking = 0;
        var barisSiswa = tBody.find('tr').get();
        var urutanLama = barisSiswa.slice();
        barisSiswa.sort(function(a, b) {
            var rawA = jQuery(a).find('td').eq(indexKolomNilai).text().replace(/[^0-9.,]/g, '').replace(',', '.');
            var rawB = jQuery(b).find('td').eq(indexKolomNilai).text().replace(/[^0-9.,]/g, '').replace(',', '.');
            return (parseFloat(rawB) || 0) - (parseFloat(rawA) || 0);
        });

        // Hanya susun ulang bila urutannya benar-benar berubah. Menyusun ulang baris setiap kali
        // (tiap AJAX sukses) membuat klik pada tombol di dalam baris sering hilang.
        var urutanBerubah = false;
        for (var u = 0; u < barisSiswa.length; u++) {
            if (barisSiswa[u] !== urutanLama[u]) { urutanBerubah = true; break; }
        }
        if (urutanBerubah) {
            jQuery.each(barisSiswa, function(index, row) { tBody.append(row); });
        }

        var nomorRank = 1;
        tBody.find('tr').each(function() {
            var tr = jQuery(this);
            if (tr.css('display') !== 'none' && !tr.hasClass('no-data') && tr.find('td').length > 1) {
                var selRank = tr.find('td').eq(indexKolomRanking);
                if (selRank.text().trim() !== String(nomorRank)) {
                    selRank.html('<b class="text-muted">' + nomorRank + '</b>');
                }
                nomorRank++;
            }
        });
        hitungUlangStatistikMurniDariDOM();
    }

    // Menerapkan filter Kelas & Mapel dari dropdown #filter-kelas / #filter-mapel (live_tabel.php).
    // Dipanggil ulang setiap AJAX sukses (refresh 4 detik), jadi harus membaca pilihan dropdown saat ini,
    // kalau tidak semua baris ditampilkan lagi dan filter Mapel/Kelas seolah ter-reset.
    function saringKelompokKelasSecaraPresisi() {
        if (!window.jQuery) return;
        var kelasDipilih = jQuery('#filter-kelas').val() || '';
        var mapelDipilih = jQuery('#filter-mapel').val() || '';

        jQuery('#wadah-tabel-live table tbody tr').each(function() {
            var trSis = jQuery(this);
            if (trSis.attr('data-id') === undefined) return; // lewati baris 'tidak ada data'

            var cocokKelas = (kelasDipilih === '' || trSis.attr('data-kelas') === kelasDipilih);
            var cocokMapel = (mapelDipilih === '' || trSis.attr('data-mapel') === mapelDipilih);

            if (cocokKelas && cocokMapel) trSis.show(); else trSis.hide();
        });
        urutkanDanBeriRankingOtomatis();
    }

    if (window.jQuery) {
        jQuery(document).ajaxSuccess(function(event, xhr, settings) {
            setTimeout(function() {
                saringKelompokKelasSecaraPresisi();
                hitungUlangStatistikMurniDariDOM();
            }, 80); 
        });
    }

    setInterval(function() {
        hitungUlangStatistikMurniDariDOM();
    }, 1000);

    document.addEventListener("DOMContentLoaded", function() {
        if (window.jQuery) {
            jQuery("#loading").hide();
            jQuery(".overlay").hide();
            
            saringKelompokKelasSecaraPresisi();
            hitungUlangStatistikMurniDariDOM();

            // SCRIPT PEMICU BANNER: Hanya muncul 1 kali per login/sesi aktif tab browser.
            if (!sessionStorage.getItem('dashboard_banner_shown')) {
                jQuery('#welcomePromoModal').modal('show');
                sessionStorage.setItem('dashboard_banner_shown', 'true');
            }
        }
    });
</script>

<script>

/* Efek liquid untuk 4 kartu statistik: glow berpindah/melar antar kartu saat hover */
(function () {
    try {
        var row = document.getElementById('stat-cards-row');
        if (!row || !window.LiquidHL) { return; }
        window.LiquidHL({
            container: row,
            items: '.card-stat-custom',
            cls: 'lq-card', padX: 8, padY: 8,
            color: function (el) {
                var c = el.className;
                if (/bg-card-orange/.test(c)) { return '255,107,61'; }
                if (/bg-card-blue/.test(c))   { return '20,136,204'; }
                if (/bg-card-green/.test(c))  { return '17,190,130'; }
                return '245,166,35';
            }
        });
    } catch (err) { if (window.console) { console.error('LiquidHL (dashboard):', err); } }
})();
</script>