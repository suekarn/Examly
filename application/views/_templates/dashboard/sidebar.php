<?php
// ===================================================================
// Gate menu "AI Soal" — dikendalikan dari license-server/admin.php
// lewat kode lisensi instalasi ini (application/config/license_key.php).
// Logikanya di application/helpers/license_helper.php, dipakai bersama
// dengan Converter.php supaya satu sumber kebenaran.
// ===================================================================
if (!function_exists('lg_show_ai_soal_menu')) {
    $this->load->helper('license');
}
$show_ai_soal_menu = lg_show_ai_soal_menu();
?>
<style>
    /* Styling Badge NEW Berkedip */
    .badge-new-blink {
        background-color: #e74c3c !important;
        color: #ffffff !important;
        font-size: 9px !important;
        font-weight: bold !important;
        padding: 2px 6px !important;
        border-radius: 8px !important;
        margin-left: 6px !important;
        display: inline-block !important;
        vertical-align: middle !important;
        letter-spacing: 0.5px !important;
        
        -webkit-animation: blinkAnim 1s infinite alternate !important;
        animation: blinkAnim 1s infinite alternate !important;
    }

    @keyframes blinkAnim {
        0% { opacity: 1; transform: scale(1); }
        100% { opacity: 0.2; transform: scale(0.92); }
    }

    /* EFEK DENYUT UNTUK SEMUA IKON UTAMA */
    @keyframes universalPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.12); }
        100% { transform: scale(1); }
    }

    /* Efek Denyut Khusus AI Soal (Lebih Cepat & Berkilau) */
    @keyframes aiPulse {
        0% { transform: scale(1); text-shadow: 0 0 0 rgba(155, 89, 182, 0.4); }
        50% { transform: scale(1.22); text-shadow: 0 0 10px rgba(155, 89, 182, 0.9); }
        100% { transform: scale(1); text-shadow: 0 0 0 rgba(155, 89, 182, 0.4); }
    }

    /* Menerapkan animasi denyut ke semua ikon menu utama */
    #tree-menus > .nav-item > .nav-link > .nav-icon {
        animation: universalPulse 3s infinite ease-in-out;
    }

    /* Khusus ikon AI Soal animasinya lebih hidup */
    .icon-alive {
        animation: aiPulse 2s infinite ease-in-out !important;
    }

    /* WARNA-WARNI KHUSUS UNTUK SETIAP IKON MENU UTAMA */
    .color-dashboard { color: #3498db !important; } /* Biru */
    .color-data-umum { color: #1abc9c !important; } /* Tosca */
    .color-elearning  { color: #e67e22 !important; } /* Oranye */
    .color-ujian     { color: #e74c3c !important; } /* Merah */
    .color-pengumuman{ color: #f1c40f !important; } /* Kuning */
    .color-ai        { color: #9b59b6 !important; } /* Ungu AI */
    .color-hasil     { color: #2980b9 !important; } /* Biru Tua */
    .color-rapor     { color: #d35400 !important; } /* Cokelat Bata */
    .color-pengaturan{ color: #7f8c8d !important; } /* Abu-abu */
    .color-qr        { color: #16a085 !important; } /* Hijau QR */

    /* Samakan tinggi header sidebar (logo) dengan navbar atas
       supaya garis bawahnya sejajar/rata */
    .main-header.navbar {
        height: 57px !important;
        min-height: 57px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        box-sizing: border-box !important;
    }

    .main-sidebar .brand-link {
        height: 57px !important;
        display: flex !important;
        align-items: center !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        box-sizing: border-box !important;
    }

    /* Jarak menu pertama dari header (terutama saat sidebar diciutkan,
       karena tulisan "HOME" ikut tersembunyi) */
    #tree-menus {
        padding-top: 14px;
    }

    /* Ikon rata tengah dan tidak terpotong saat sidebar diciutkan */
    .sidebar-collapse .main-sidebar:not(:hover) #tree-menus .nav-link {
        text-align: center;
        overflow: visible !important;
    }

    /* Info LAN di bagian bawah sidebar */
    .main-sidebar .lan-info {
        position: absolute;
        left: 0; right: 0; bottom: 0;
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 600;
        background: inherit;
        border-top: 1px solid rgba(128, 128, 128, 0.25);
        z-index: 10;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .main-sidebar .lan-info a { color: inherit; }
    .main-sidebar .lan-info i { margin-right: 6px; opacity: .7; }
    .sidebar-collapse .main-sidebar:not(:hover) .lan-info { display: none; }
    /* Sudah dipindah ke navbar (samping jam) -> sembunyikan di bawah sidebar.
       Di layar sempit pil LAN di navbar disembunyikan, jadi info di sidebar dimunculkan lagi. */
    .main-sidebar .lan-info.lan-moved { display: none; }
    @media (max-width: 767.98px) {
        .navbar-lan-pill { display: none !important; }
        .main-sidebar .lan-info.lan-moved { display: block; }
    }

    .navbar-lan-pill[data-plain] a { color: #1f2d3d !important; font-size: 13px !important; font-weight: 700 !important; }
    .dark-mode .navbar-lan-pill[data-plain],
    .navbar-lan-pill[data-plain][data-dark] { background: #1b2638 !important; border-color: rgba(255,255,255,.14) !important; box-shadow: 0 1px 3px rgba(0,0,0,.45) !important; }
    .dark-mode .navbar-lan-pill[data-plain] a,
    .navbar-lan-pill[data-plain][data-dark] a { color: #f1f5f9 !important; }

    /* Transisi menu */
    #tree-menus .nav-item .nav-link {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        transform: scale(1);
    }

    #tree-menus .nav-item .nav-link {
        border-radius: 6px !important;
    }

    /* Hover & aktif: latar baris dibuat tipis, sorotan utama diambil alih
       oleh gelembung cair (#sb-blob) yang meluncur di belakang ikon. */
    #tree-menus .nav-item .nav-link:not(.bg-danger):hover {
        background-color: rgba(var(--rgb, 32, 201, 151), 0.10) !important;
        transform: none !important;
        box-shadow: none !important;
    }

    #tree-menus .nav-item .nav-link .nav-icon,
    #tree-menus .nav-item .nav-link p {
        transition: all 0.3s ease !important;
    }

    #tree-menus .nav-item .nav-link:hover .nav-icon {
        transform: scale(1.1);
        animation: none !important;
    }

    /* ===== Ikon premium (SVG) ===== */
    .sb-ico, .sb-mini { display: inline-block; filter: drop-shadow(0 1px 1px rgba(0,0,0,.28)); }
    #tree-menus .nav-link > svg.sb-ico { width: 1.45rem; height: 1.45rem; margin: 0 .45rem 0 .05rem; vertical-align: middle; }
    #tree-menus .nav-treeview .nav-link > svg.sb-ico { width: 1.3rem; height: 1.3rem; }
    .sb-mini { width: 16px; height: 16px; vertical-align: -3px; }
    .sb-title-ico { width: 24px; height: 24px; vertical-align: -7px; }
    .main-sidebar .lan-info .sb-mini { margin-right: 6px; opacity: 1; }
    @keyframes aiPulseSvg {
        0%   { transform: scale(1);    filter: drop-shadow(0 0 0 rgba(155, 89, 182, 0.4)); }
        50%  { transform: scale(1.22); filter: drop-shadow(0 0 6px rgba(155, 89, 182, 0.95)); }
        100% { transform: scale(1);    filter: drop-shadow(0 0 0 rgba(155, 89, 182, 0.4)); }
    }
    svg.icon-alive { animation: aiPulseSvg 2s infinite ease-in-out !important; }
    #tree-menus .nav-link:hover > svg.sb-ico { filter: drop-shadow(0 2px 3px rgba(0,0,0,.35)); }

    /* CSS Pengaman Tambahan untuk Sembunyikan Menu Spesifik */
    #tree-menus a[href*="kelasjadwal"],
    #tree-menus a[href*="kelasmateri"],
    #tree-menus a[href*="kelasmaterijadwal"],
    #tree-menus a[href*="kelasstatus"],
    #tree-menus a[href*="kelasabsensi"],
    #tree-menus a[href*="kelasnilai"],
    #tree-menus a[href*="rapor"],
    #tree-menus a[href*="bukurapor"],
    #tree-menus a[href*="dataalumni"] {
        display: none !important;
    }

    /* ===================================================================
       LIQUID SIDEBAR — gelembung warna yang meluncur & melar antar menu
       =================================================================== */
    #tree-menus { position: relative; }
    #tree-menus .nav-item .nav-link { z-index: 1; }

    #sb-blob {
        position: absolute;
        top: 0; left: 0;
        width: 38px; height: 38px;
        z-index: 0;
        pointer-events: none;
        opacity: 0;
        transform: scale(.3);
        background-color: var(--c, #20c997);
        background-image: radial-gradient(circle at 34% 28%, rgba(255,255,255,.55) 0, rgba(255,255,255,0) 52%);
        box-shadow: 0 0 10px 2px rgba(var(--rgb, 32,201,151), .60),
                    0 0 26px 6px rgba(var(--rgb, 32,201,151), .32);
        border-radius: 50% 46% 54% 48% / 48% 54% 46% 52%;
        animation: sbBlobMorph 5s ease-in-out infinite;
        will-change: top, left, width, height;
    }
    #sb-blob.sb-on { opacity: 1; transform: scale(1); }
    #sb-blob.sb-track { transition: none !important; }

    @keyframes sbBlobMorph {
        0%, 100% { border-radius: 50% 46% 54% 48% / 48% 54% 46% 52%; }
        33%      { border-radius: 44% 56% 46% 54% / 55% 45% 55% 45%; }
        66%      { border-radius: 54% 46% 50% 50% / 46% 52% 48% 54%; }
    }

    /* Ikon yang sedang "tersorot" gelembung jadi putih seperti di video */
    #tree-menus .nav-link.sb-lit > svg.sb-ico {
        filter: brightness(0) invert(1) drop-shadow(0 1px 2px rgba(0,0,0,.35)) !important;
    }
    #tree-menus .nav-link.sb-lit > svg.icon-alive { animation: none !important; }

    /* Baris aktif: tinted lembut mengikuti warna menunya */
    #tree-menus .nav-item .nav-link.active:not(.bg-danger),
    #tree-menus .nav-item .nav-link.active:not(.bg-danger):hover {
        background-color: rgba(var(--rgb, 32, 201, 151), 0.12) !important;
        color: inherit !important;
        font-weight: 600;
        box-shadow: none !important;
    }

    @media (prefers-reduced-motion: reduce) {
        #sb-blob { animation: none; }
    }
</style>

<!-- Sprite ikon premium sidebar -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="sb-blue" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#bfdbfe"/><stop offset=".5" stop-color="#3b82f6"/><stop offset="1" stop-color="#1e3a8a"/></linearGradient><linearGradient id="sb-sky" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e0f2fe"/><stop offset=".5" stop-color="#38bdf8"/><stop offset="1" stop-color="#075985"/></linearGradient><linearGradient id="sb-teal" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#99f6e4"/><stop offset=".5" stop-color="#14b8a6"/><stop offset="1" stop-color="#115e59"/></linearGradient><linearGradient id="sb-green" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#bbf7d0"/><stop offset=".5" stop-color="#22c55e"/><stop offset="1" stop-color="#14532d"/></linearGradient><linearGradient id="sb-red" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fecaca"/><stop offset=".5" stop-color="#ef4444"/><stop offset="1" stop-color="#7f1d1d"/></linearGradient><linearGradient id="sb-orange" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fed7aa"/><stop offset=".5" stop-color="#f97316"/><stop offset="1" stop-color="#9a3412"/></linearGradient><linearGradient id="sb-gold" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fef08a"/><stop offset=".5" stop-color="#f59e0b"/><stop offset="1" stop-color="#b45309"/></linearGradient><linearGradient id="sb-purple" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e9d5ff"/><stop offset=".5" stop-color="#a855f7"/><stop offset="1" stop-color="#581c87"/></linearGradient><linearGradient id="sb-pink" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fbcfe8"/><stop offset=".5" stop-color="#ec4899"/><stop offset="1" stop-color="#831843"/></linearGradient><linearGradient id="sb-brown" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fcd9b6"/><stop offset=".5" stop-color="#b45309"/><stop offset="1" stop-color="#451a03"/></linearGradient><linearGradient id="sb-slate" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e2e8f0"/><stop offset=".5" stop-color="#94a3b8"/><stop offset="1" stop-color="#334155"/></linearGradient><linearGradient id="sb-dark" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#64748b"/><stop offset=".5" stop-color="#1e293b"/><stop offset="1" stop-color="#020617"/></linearGradient><linearGradient id="sb-white" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".5" stop-color="#f1f5f9"/><stop offset="1" stop-color="#cbd5e1"/></linearGradient><linearGradient id="sb-silver" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".5" stop-color="#cbd5e1"/><stop offset="1" stop-color="#64748b"/></linearGradient><linearGradient id="sb-skin" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffe8cc"/><stop offset=".5" stop-color="#f4b97a"/><stop offset="1" stop-color="#9a5b25"/></linearGradient><linearGradient id="sb-screen" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#38bdf8"/><stop offset=".5" stop-color="#2563eb"/><stop offset="1" stop-color="#1e1b4b"/></linearGradient><linearGradient id="sb-greenb" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#22c55e"/><stop offset=".5" stop-color="#166534"/><stop offset="1" stop-color="#052e16"/></linearGradient><linearGradient id="sb-hair" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#78350f"/><stop offset=".5" stop-color="#451a03"/><stop offset="1" stop-color="#1c0a00"/></linearGradient></defs><symbol id="sb-desktop" viewBox="0 0 32 32"><rect x="2.5" y="4" width="27" height="18.5" rx="3" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="4.6" y="6.1" width="22.8" height="14.3" rx="1.7" fill="url(#sb-screen)" stroke="#1e1b4b" stroke-opacity=".5" stroke-width=".8"/><rect x="8" y="14.8" width="3.2" height="3.7" rx="0.7" fill="#fff" fill-opacity="0.92"/><rect x="12.8" y="11.8" width="3.2" height="6.7" rx="0.7" fill="#fff" fill-opacity="0.92"/><rect x="17.6" y="13.2" width="3.2" height="5.3" rx="0.7" fill="#fde68a" fill-opacity="0.95"/><rect x="22.2" y="9.6" width="3.2" height="8.9" rx="0.7" fill="#fff" fill-opacity="0.92"/><path d="M4.6 7.8a1.7 1.7 0 0 1 1.7-1.7H16L8.5 20.4H6.3a1.7 1.7 0 0 1-1.7-1.7z" fill="#fff" fill-opacity="0.16"/><path d="M13 22.5h6l1.2 3.6h-8.4z" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="8.5" y="26" width="15" height="2.8" rx="1.4" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-server" viewBox="0 0 32 32"><rect x="3" y="3" width="26" height="7.8" rx="2.2" fill="url(#sb-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="3.8" width="24" height="2.6" rx="1.3" fill="#fff" fill-opacity="0.28"/><circle cx="7.6" cy="7.2" r="1.3" fill="#86efac" stroke="#064e3b" stroke-opacity=".5" stroke-width=".5"/><path d="M12 7.2h12" fill="none" stroke="#ffffff" stroke-opacity="0.85" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 9.2h7" fill="none" stroke="#ffffff" stroke-opacity="0.5" stroke-width="0.9" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="12" width="26" height="7.8" rx="2.2" fill="url(#sb-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="12.8" width="24" height="2.6" rx="1.3" fill="#fff" fill-opacity="0.28"/><circle cx="7.6" cy="16.2" r="1.3" fill="#fde68a" stroke="#064e3b" stroke-opacity=".5" stroke-width=".5"/><path d="M12 16.2h12" fill="none" stroke="#ffffff" stroke-opacity="0.85" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 18.2h7" fill="none" stroke="#ffffff" stroke-opacity="0.5" stroke-width="0.9" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="21" width="26" height="7.8" rx="2.2" fill="url(#sb-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="21.8" width="24" height="2.6" rx="1.3" fill="#fff" fill-opacity="0.28"/><circle cx="7.6" cy="25.2" r="1.3" fill="#86efac" stroke="#064e3b" stroke-opacity=".5" stroke-width=".5"/><path d="M12 25.2h12" fill="none" stroke="#ffffff" stroke-opacity="0.85" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 27.2h7" fill="none" stroke="#ffffff" stroke-opacity="0.5" stroke-width="0.9" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-graduate" viewBox="0 0 32 32"><path d="M4 30.5c0-4.6 4.6-6.8 12-6.8s12 2.2 12 6.8z" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M12.8 22.6L16 27l3.2-4.4z" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="16" cy="18.4" r="5.4" fill="url(#sb-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M8.8 10.5v3.4c0 2 3.2 3.4 7.2 3.4s7.2-1.4 7.2-3.4v-3.4L16 13z" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M2.5 8.8L16 3.2l13.5 5.6L16 14.4z" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M5.5 8.8L16 4.6l3 1.3L9 10.3z" fill="#fff" fill-opacity="0.3"/><path d="M27.2 9.8v7" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="27.2" cy="18" r="1.7" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-bullhorn" viewBox="0 0 32 32"><rect x="2.5" y="11.5" width="6" height="9" rx="1.6" fill="url(#sb-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/><path d="M8.5 11.8L23 4.5v22L8.5 19.8z" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M9.5 12.3L22 6v5L9.5 16z" fill="#fff" fill-opacity="0.35"/><path d="M10 20l2.8 8.5h3.6l-1.9-7.6z" fill="url(#sb-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><ellipse cx="23.2" cy="15.5" rx="2.5" ry="11" fill="url(#sb-orange)" stroke="#9a3412" stroke-opacity=".5" stroke-width=".8"/><path d="M28 10c1.8 3.2 1.8 8.8 0 12" fill="none" stroke="#f97316" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M30.2 7.6c3 4.4 3 12.4 0 16.8" fill="none" stroke="#f97316" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-magic" viewBox="0 0 32 32"><g transform="rotate(-45 16 16)"><rect x="2.5" y="13.9" width="22" height="4.2" rx="1.8" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="17.5" y="13.9" width="5" height="4.2" rx="0" fill="url(#sb-purple)" stroke="#581c87" stroke-opacity=".5" stroke-width=".8"/><rect x="2.5" y="13.9" width="4" height="4.2" rx="1.6" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="7" y="14.5" width="10" height="1" rx="0.5" fill="#fff" fill-opacity="0.35"/></g><path d="M23.6 1.9999999999999991l1.86 4.34 4.34 1.86-4.34 1.86-1.86 4.34-1.86-4.34-4.34-1.86 4.34-1.86z" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M6.5 4l0.90 2.10 2.10 0.90-2.10 0.90-0.90 2.10-0.90-2.10-2.10-0.90 2.10-0.90z" fill="url(#sb-purple)" stroke="#581c87" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M26.5 19.7l0.84 1.96 1.96 0.84-1.96 0.84-0.84 1.96-0.84-1.96-1.96-0.84 1.96-0.84z" fill="url(#sb-purple)" stroke="#581c87" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M8 21.9l0.63 1.47 1.47 0.63-1.47 0.63-0.63 1.47-0.63-1.47-1.47-0.63 1.47-0.63z" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="sb-qr" viewBox="0 0 32 32"><rect x="2.5" y="2.5" width="27" height="27" rx="4" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="5.8" y="5.8" width="7" height="7" rx="1.4" fill="#0f766e" fill-opacity="1"/><rect x="7.3" y="7.3" width="4" height="4" rx="0.6" fill="#ffffff" fill-opacity="1"/><rect x="8.2" y="8.2" width="2.2" height="2.2" rx="0.4" fill="#0f766e" fill-opacity="1"/><rect x="19.2" y="5.8" width="7" height="7" rx="1.4" fill="#0f766e" fill-opacity="1"/><rect x="20.7" y="7.3" width="4" height="4" rx="0.6" fill="#ffffff" fill-opacity="1"/><rect x="21.599999999999998" y="8.2" width="2.2" height="2.2" rx="0.4" fill="#0f766e" fill-opacity="1"/><rect x="5.8" y="19.2" width="7" height="7" rx="1.4" fill="#0f766e" fill-opacity="1"/><rect x="7.3" y="20.7" width="4" height="4" rx="0.6" fill="#ffffff" fill-opacity="1"/><rect x="8.2" y="21.599999999999998" width="2.2" height="2.2" rx="0.4" fill="#0f766e" fill-opacity="1"/><rect x="15" y="5.8" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="15" y="9.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="15" y="15" width="2.8" height="2.8" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="19.5" y="15" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="24" y="15.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="19.4" y="19.4" width="3" height="3" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="24.2" y="20" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="15.4" y="19.6" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="15.2" y="24" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="19.6" y="24.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="24.2" y="24.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="9" y="15" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="5.8" y="15.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/></symbol><symbol id="sb-gradcap" viewBox="0 0 32 32"><path d="M8 14.8v7c0 2.8 3.6 5 8 5s8-2.2 8-5v-7L16 20z" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M1.8 12L16 5.2 30.2 12 16 18.8z" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M5.4 12L16 7l3.4 1.6L9.6 14z" fill="#fff" fill-opacity="0.3"/><path d="M27.2 13.2v8.2" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="27.2" cy="23.2" r="2.1" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><path d="M25.7 25l-.6 3M28.7 25l.6 3" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-university" viewBox="0 0 32 32"><path d="M16 2.8L2.5 10h27z" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M16 4.2L6.5 9.4h19z" fill="#fff" fill-opacity="0.2"/><circle cx="16" cy="7.3" r="1.6" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="5.4" y="11.5" width="3.8" height="11" rx="0.8" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="10.8" y="11.5" width="3.8" height="11" rx="0.8" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="17.4" y="11.5" width="3.8" height="11" rx="0.8" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="22.8" y="11.5" width="3.8" height="11" rx="0.8" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="3" y="22.8" width="26" height="3" rx="0.8" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="1.5" y="25.8" width="29" height="3.4" rx="1" fill="url(#sb-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-userscog" viewBox="0 0 32 32"><path d="M2.5 27.5c0-5.4 4.2-8.6 9.5-8.6s9.5 3.2 9.5 8.6z" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="12" cy="9.6" r="4.5" fill="url(#sb-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M7.2 9.4a4.8 4.8 0 0 1 9.6 0c-2.25-2.70-5.40-4.05-4.80-4.05s-3.45 .3-4.80 4.05z" fill="url(#sb-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="23.5" cy="22.5" r="5.4" fill="none" stroke="#b45309" stroke-opacity=".5" stroke-width="3.6" stroke-dasharray="3.02 1.22" stroke-dashoffset="-0.61"/><circle cx="23.5" cy="22.5" r="5.4" fill="none" stroke="url(#sb-gold)" stroke-width="2.6" stroke-dasharray="2.12 2.12" stroke-dashoffset="-1.06"/><circle cx="23.5" cy="22.5" r="3.9" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><circle cx="23.5" cy="22.5" r="1.7" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-database" viewBox="0 0 32 32"><path d="M5 7.6v17.4c0 5.1 5.0 6.6 11 6.6s11 -1.6 11 -6.6V7.6z" fill="url(#sb-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M5 14.2c0 5.8 5.0 7.4 11 7.4s11 -1.6 11 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 20.1c0 5.8 5.0 7.4 11 7.4s11 -1.6 11 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><ellipse cx="16" cy="7.6" rx="11" ry="3.9" fill="url(#sb-white)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><ellipse cx="16" cy="7.6" rx="7.7" ry="1.9" fill="url(#sb-sky)" fill-opacity=".35"/></symbol><symbol id="sb-dbbackup" viewBox="0 0 32 32"><path d="M3.8000000000000007 7.4v15.1c0 5.1 4.5 6.6 10 6.6s10 -1.6 10 -6.6V7.4z" fill="url(#sb-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M3.8000000000000007 13.1c0 5.8 4.5 7.4 10 7.4s10 -1.6 10 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.8000000000000007 18.3c0 5.8 4.5 7.4 10 7.4s10 -1.6 10 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><ellipse cx="13.8" cy="7.4" rx="10" ry="3.9" fill="url(#sb-white)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><ellipse cx="13.8" cy="7.4" rx="7.0" ry="1.9" fill="url(#sb-sky)" fill-opacity=".35"/><circle cx="24.3" cy="24.3" r="6.4" fill="url(#sb-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/><circle cx="24.3" cy="24.3" r="6.4" fill="none" stroke="#fff" stroke-width="1.3"/><path d="M24.3 20.8v6.6M21.4 24.9l2.9 3 2.9-3" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-dbclear" viewBox="0 0 32 32"><path d="M3.8000000000000007 7.4v15.1c0 5.1 4.5 6.6 10 6.6s10 -1.6 10 -6.6V7.4z" fill="url(#sb-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M3.8000000000000007 13.1c0 5.8 4.5 7.4 10 7.4s10 -1.6 10 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.8000000000000007 18.3c0 5.8 4.5 7.4 10 7.4s10 -1.6 10 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><ellipse cx="13.8" cy="7.4" rx="10" ry="3.9" fill="url(#sb-white)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><ellipse cx="13.8" cy="7.4" rx="7.0" ry="1.9" fill="url(#sb-sky)" fill-opacity=".35"/><circle cx="24.3" cy="24.3" r="6.4" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="24.3" cy="24.3" r="6.4" fill="none" stroke="#fff" stroke-width="1.3"/><path d="M21.5 21.5l5.6 5.6M27.1 21.5l-5.6 5.6" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-logout" viewBox="0 0 32 32"><rect x="3.5" y="3.5" width="15" height="25" rx="2.6" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="5.5" y="5.5" width="11" height="21" rx="1.6" fill="#fde68a" fill-opacity="0.4"/><circle cx="14" cy="16" r="1.3" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><path d="M14.5 16h13.5M22.5 10.5l5.5 5.5-5.5 5.5" fill="none" stroke="#7f1d1d" stroke-opacity="0.55" stroke-width="5.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.5 16h13.5M22.5 10.5l5.5 5.5-5.5 5.5" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-network" viewBox="0 0 32 32"><path d="M16 10.5V16M7 21.5V16h18v5.5" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><rect x="10.5" y="3" width="11" height="8" rx="2" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><rect x="2" y="21" width="10" height="8" rx="2" fill="url(#sb-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="20" y="21" width="10" height="8" rx="2" fill="url(#sb-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="12.5" y="5" width="7" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="12.5" y="7.5" width="4" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.5"/><rect x="4" y="23" width="6" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="22" y="23" width="6" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="4" y="25.5" width="3.5" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.5"/><rect x="22" y="25.5" width="3.5" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.5"/></symbol><symbol id="sb-copy" viewBox="0 0 32 32"><rect x="3" y="2.5" width="16.5" height="21" rx="2.4" fill="url(#sb-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/><rect x="11" y="8.5" width="18" height="21" rx="2.4" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M15 14.5h10M15 18.5h10M15 22.5h6" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 14.5h10" fill="none" stroke="#3b82f6" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-print" viewBox="0 0 32 32"><rect x="8.5" y="2.5" width="15" height="10" rx="1.4" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M11.5 6h9M11.5 8.6h6" fill="none" stroke="#94a3b8" stroke-opacity="1" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><rect x="2.5" y="11" width="27" height="13" rx="3.6" fill="url(#sb-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="12" width="24" height="3" rx="1.4" fill="#fff" fill-opacity="0.28"/><rect x="8" y="19" width="16" height="10.5" rx="1.2" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M11 22.5h10M11 25.5h7" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="25" cy="15.4" r="1.1" fill="url(#sb-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-check" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13.2" fill="url(#sb-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="13.2" fill="none" stroke="#fff" stroke-opacity=".7" stroke-width="1"/><path d="M4.8 14.5a11.4 11.4 0 0 1 22.4 0c-6.5-3.2-15.9-3.2-22.4 0z" fill="#fff" fill-opacity="0.28"/><path d="M9.5 16.6l4.4 4.5 8.6-9.6" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-calcheck" viewBox="0 0 32 32"><rect x="3.5" y="5.5" width="25" height="23" rx="3.6" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M3.5 13.5V9.1a3.6 3.6 0 0 1 3.6-3.6h17.8a3.6 3.6 0 0 1 3.6 3.6v4.4z" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="9" y="2.5" width="3" height="6.5" rx="1.5" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="20" y="2.5" width="3" height="6.5" rx="1.5" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><path d="M10.2 21.3l4.2 4.2 7.6-8.2" fill="none" stroke="#16a34a" stroke-opacity="1" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-book" viewBox="0 0 32 32"><rect x="4.5" y="3" width="5" height="26" rx="1.2" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="8" y="3" width="19.5" height="26" rx="2.4" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><rect x="8.8" y="4" width="3" height="24" rx="1" fill="#fff" fill-opacity="0.18"/><rect x="12.5" y="8" width="11.5" height="7" rx="1" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M14.5 10.5h7.5M14.5 12.8h5" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><rect x="9" y="27" width="19" height="2.6" rx="1" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M22 3v8l-2.2-1.8L17.6 11V3z" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="sb-flask" viewBox="0 0 32 32"><rect x="11.5" y="2.5" width="9" height="3.2" rx="1.2" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><path d="M12.5 5.7v7.8L5 25.4c-1.2 2.2.1 4.1 2.6 4.1h16.8c2.5 0 3.8-1.9 2.6-4.1L19.5 13.5V5.7z" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M8.4 21l-1.7 3.2c-.8 1.4.1 2.4 1.7 2.4h15.2c1.6 0 2.5-1 1.7-2.4L23.6 21z" fill="url(#sb-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="12" cy="24" r="1" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><circle cx="17.5" cy="22.8" r="1.2" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><circle cx="21" cy="25" r="0.8" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M13.8 7v6.6L8.6 22.4" fill="#fff" fill-opacity="0.0"/><path d="M14.4 7.5v5.8" fill="none" stroke="#ffffff" stroke-opacity="0.8" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-users" viewBox="0 0 32 32"><path d="M13.5 25c0-4.6 3.6-7 8-7s8 2.4 8 7z" fill="url(#sb-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="21.5" cy="9.5" r="3.8" fill="url(#sb-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M17.4 9.3a4.1 4.1 0 0 1 8.2 0c-1.90-2.28-4.56-3.42-4.10-3.42s-2.96 .3-4.10 3.42z" fill="url(#sb-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M1.8 29.5c0-5.6 4.2-8.8 9.7-8.8s9.7 3.2 9.7 8.8z" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="11.5" cy="11" r="4.6" fill="url(#sb-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M6.6000000000000005 10.8a4.8999999999999995 4.8999999999999995 0 0 1 9.799999999999999 0c-2.30-2.76-5.52-4.14-4.90-4.14s-3.52 .3-4.90 4.14z" fill="url(#sb-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="sb-school" viewBox="0 0 32 32"><path d="M1.8 13.6L16 4l14.2 9.6z" fill="url(#sb-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="4" y="13.6" width="24" height="15.2" rx="0.8" fill="url(#sb-orange)" stroke="#9a3412" stroke-opacity=".5" stroke-width=".8"/><rect x="13.4" y="19.2" width="5.2" height="9.6" rx="1" fill="url(#sb-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="6.6" y="16.4" width="4.2" height="4.2" rx="0.6" fill="url(#sb-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><rect x="21.2" y="16.4" width="4.2" height="4.2" rx="0.6" fill="url(#sb-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="9.8" r="2" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M16 4V1.6" fill="none" stroke="#b45309" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 1.6l4 1.2-4 1.2z" fill="#ef4444" fill-opacity="1"/></symbol><symbol id="sb-chess" viewBox="0 0 32 32"><ellipse cx="16" cy="28" rx="10" ry="2.2" fill="#020617" fill-opacity=".25"/><circle cx="16" cy="8.4" r="4.6" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="11" y="12.6" width="10" height="2.8" rx="1.4" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><path d="M13 15.4c0 3-1.4 5-3.4 8h12.8c-2-3-3.4-5-3.4-8z" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="7.5" y="23.2" width="17" height="3" rx="1.5" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="6" y="26" width="20" height="3.4" rx="1.7" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><path d="M13 6a3.4 3.4 0 0 1 4-2.4 3.4 3.4 0 0 0-3.4 3.6z" fill="#fff" fill-opacity="0.5"/><rect x="12.4" y="23.9" width="3" height="0.9" rx="0.4" fill="#fff" fill-opacity="0.35"/></symbol><symbol id="sb-teacher" viewBox="0 0 32 32"><rect x="2" y="2.5" width="28" height="17.5" rx="1.8" fill="url(#sb-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="4.5" width="24" height="13.2" rx="0.8" fill="url(#sb-greenb)" stroke="#052e16" stroke-opacity=".5" stroke-width=".8"/><path d="M7 8.2h9M7 11.4h14M7 14.4h6" fill="none" stroke="#ffffff" stroke-opacity="0.85" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><rect x="4" y="20" width="24" height="1.8" rx="0.8" fill="url(#sb-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><path d="M2.2 31.6c0-3.6 2.6-5 5.8-5s5.8 1.4 5.8 5z" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="8" cy="24.4" r="2.9" fill="url(#sb-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M4.8 24.2a3.1999999999999997 3.1999999999999997 0 0 1 6.3999999999999995 0c-1.45-1.74-3.48-2.61-3.20-2.61s-2.33 .3-3.20 2.61z" fill="url(#sb-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M13 28.6l10.5-6.4" fill="none" stroke="#92400e" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="23.8" cy="22" r="0.9" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-diagram" viewBox="0 0 32 32"><path d="M16 10.5v5M7.5 21.5v-6h17v6" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><rect x="10.5" y="3" width="11" height="7.5" rx="1.9" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><rect x="2.5" y="21.5" width="10" height="7.5" rx="1.9" fill="url(#sb-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="19.5" y="21.5" width="10" height="7.5" rx="1.9" fill="url(#sb-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="12.5" y="5.2" width="7" height="1.2" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="4.5" y="23.7" width="6" height="1.2" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="21.5" y="23.7" width="6" height="1.2" rx="0.6" fill="#fff" fill-opacity="0.7"/></symbol><symbol id="sb-clock" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13.2" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="10.56" fill="url(#sb-white)" stroke="#334155" stroke-opacity=".35" stroke-width=".7"/><path d="M16.00 6.24L16.00 7.54" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M20.88 7.55L20.58 8.07" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M24.45 11.12L23.93 11.42" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M25.76 16.00L24.46 16.00" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M24.45 20.88L23.93 20.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M20.88 24.45L20.58 23.93" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M16.00 25.76L16.00 24.46" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M11.12 24.45L11.42 23.93" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M7.55 20.88L8.07 20.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M6.24 16.00L7.54 16.00" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M7.55 11.12L8.07 11.42" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M11.12 7.55L11.42 8.07" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M16 16V9.40" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 16l4.75 2.64" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="16" cy="16" r="1.3" fill="#ef4444"/></symbol><symbol id="sb-door" viewBox="0 0 32 32"><rect x="4.5" y="2.5" width="21.5" height="27" rx="1.5" fill="url(#sb-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="6.5" y="4.5" width="17.5" height="25" rx="0.8" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><path d="M6.5 4.5L18.6 7.4V29.5H6.5z" fill="url(#sb-orange)" stroke="#9a3412" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="8.5" y="10" width="7.6" height="6.4" rx="0.8" fill="#ffffff" fill-opacity="0.22"/><rect x="8.5" y="19" width="7.6" height="7.2" rx="0.8" fill="#ffffff" fill-opacity="0.18"/><path d="M18.6 7.4L24 4.5v25h-5.4z" fill="#fff" fill-opacity="0.12"/><circle cx="16.2" cy="18.2" r="1.1" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-userclock" viewBox="0 0 32 32"><path d="M2 27c0-5 4-8 9-8s9 3 9 8z" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="11" cy="9.2" r="4.3" fill="url(#sb-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M6.4 9.0a4.6 4.6 0 0 1 9.2 0c-2.15-2.58-5.16-3.87-4.60-3.87s-3.31 .3-4.60 3.87z" fill="url(#sb-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="23.3" cy="22.8" r="7.2" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><circle cx="23.3" cy="22.8" r="5.76" fill="url(#sb-white)" stroke="#334155" stroke-opacity=".35" stroke-width=".7"/><path d="M23.30 17.84L23.30 19.14" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M25.78 18.50L25.48 19.02" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M27.60 20.32L27.08 20.62" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M28.26 22.80L26.96 22.80" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M27.60 25.28L27.08 24.98" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M25.78 27.10L25.48 26.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M23.30 27.76L23.30 26.46" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M20.82 27.10L21.12 26.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M19.00 25.28L19.52 24.98" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M18.34 22.80L19.64 22.80" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M19.00 20.32L19.52 20.62" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M20.82 18.50L21.12 19.02" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M23.3 22.8V19.20" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M23.3 22.8l2.59 1.44" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="23.3" cy="22.8" r="1.3" fill="#ef4444"/></symbol><symbol id="sb-idcard" viewBox="0 0 32 32"><rect x="12.5" y="2.5" width="7" height="3.6" rx="1.5" fill="url(#sb-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/><rect x="2" y="5.5" width="28" height="21.5" rx="3.2" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M2 12V8.7a3.2 3.2 0 0 1 3.2-3.2h21.6A3.2 3.2 0 0 1 30 8.7V12z" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M4 25.6c0-3.4 2.4-4.8 5.8-4.8s5.8 1.4 5.8 4.8z" fill="url(#sb-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="9.8" cy="17.2" r="3.2" fill="url(#sb-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M6.300000000000001 17.0a3.5 3.5 0 0 1 7.0 0c-1.60-1.92-3.84-2.88-3.50-2.88s-2.54 .3-3.50 2.88z" fill="url(#sb-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M18.5 16.5h8.5M18.5 20h8.5M18.5 23.5h5" fill="none" stroke="#94a3b8" stroke-opacity="1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-folder" viewBox="0 0 32 32"><path d="M2.8 7.8a2.4 2.4 0 0 1 2.4-2.4h7.2l3 3.4h10.4a2.4 2.4 0 0 1 2.4 2.4V25H2.8z" fill="url(#sb-orange)" stroke="#9a3412" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="6" y="9.6" width="19.5" height="11" rx="1" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M9 13h12M9 16.4h8" fill="none" stroke="#94a3b8" stroke-opacity="1" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M1.5 27.4l4.1-13a2.4 2.4 0 0 1 2.3-1.7H30a1.6 1.6 0 0 1 1.5 2.1l-3.7 12a2.4 2.4 0 0 1-2.3 1.7H3.2a1.6 1.6 0 0 1-1.7-1.1z" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M7.2 14.2h22l-.5 1.6H6.7z" fill="#fff" fill-opacity="0.4"/></symbol><symbol id="sb-calendar" viewBox="0 0 32 32"><rect x="3.5" y="5.5" width="25" height="23" rx="3.6" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M3.5 13.5V9.1a3.6 3.6 0 0 1 3.6-3.6h17.8a3.6 3.6 0 0 1 3.6 3.6v4.4z" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="9" y="2.5" width="3" height="6.5" rx="1.5" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="20" y="2.5" width="3" height="6.5" rx="1.5" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="7" y="16.4" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="7" y="21.6" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="12.4" y="16.4" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="12.4" y="21.6" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="17.8" y="16.4" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="17.8" y="21.6" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="23.2" y="16.4" width="3.6" height="3.2" rx="0.8" fill="#ef4444" fill-opacity="1"/><rect x="23.2" y="21.6" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/></symbol><symbol id="sb-hourglass" viewBox="0 0 32 32"><rect x="5.5" y="2.5" width="21" height="3.2" rx="1.4" fill="url(#sb-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="5.5" y="26.3" width="21" height="3.2" rx="1.4" fill="url(#sb-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><path d="M8.5 5.7h15c0 5-5 7-7.5 10.3 2.5 3.300 7.5 5.300 7.500 10.300h-15c0-5 5-7 7.500-10.300C13.500 12.700 8.500 10.700 8.500 5.700z" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M11 7.2h10c-.4 3-2.8 4.4-5 5.6-2.2-1.2-4.6-2.6-5-5.6z" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M9.8 25.8c.8-3.6 3.6-5.2 6.200-5.200s5.400 1.600 6.200 5.200z" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M16 13v7.500" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-briefcase" viewBox="0 0 32 32"><path d="M11.5 10V7.5a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2V10" fill="none" stroke="#451a03" stroke-opacity="1" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/><rect x="2.5" y="9.5" width="27" height="19" rx="3.2" fill="url(#sb-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="3.5" y="10.5" width="25" height="3.4" rx="1.6" fill="#fff" fill-opacity="0.22"/><rect x="2.5" y="17.2" width="27" height="2.6" rx="0" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="13.2" y="15.4" width="5.6" height="6" rx="1.2" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="18.4" r="0.9" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-key" viewBox="0 0 32 32"><path d="M14.5 14.5L28 28" fill="none" stroke="#78350f" stroke-opacity="0.5" stroke-width="5.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M22.5 22.5l3.2-3.2M26 26l3-3" fill="none" stroke="#78350f" stroke-opacity="0.5" stroke-width="4.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.5 14.5L28 28" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M22.5 22.5l3.2-3.2M26 26l3-3" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.5 15l11 11" fill="none" stroke="#fde68a" stroke-opacity="0.7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.5 3a7.4 7.4 0 1 1 0 14.8 7.4 7.4 0 0 1 0-14.8zm0 4.4a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" fill="url(#sb-gold)" fill-rule="evenodd" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="sb-filealt" viewBox="0 0 32 32"><path d="M6 2.5h14.5l7.500 7.500V27a2.500 2.500 0 0 1-2.500 2.500H8.500A2.500 2.500 0 0 1 6 27z" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M20.500 2.500V8a2 2 0 0 0 2 2H28z" fill="url(#sb-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M10 14.500h12M10 18.500h12M10 22.500h7" fill="none" stroke="#94a3b8" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 14.500h7" fill="none" stroke="#3b82f6" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="22.5" cy="23.5" r="3.8" fill="url(#sb-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/><path d="M20.700 23.600l1.300 1.300 2.400-2.800" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="sb-chart" viewBox="0 0 32 32"><rect x="2.5" y="3.5" width="27" height="25" rx="3.4" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M6.500 24.500h19" fill="none" stroke="#cbd5e1" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><rect x="6" y="19" width="3.8" height="5.5" rx="0.8" fill="url(#sb-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><rect x="11.5" y="15" width="3.8" height="9.5" rx="0.8" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><rect x="17" y="17.5" width="3.8" height="7" rx="0.8" fill="url(#sb-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><rect x="22.5" y="12" width="3.8" height="12.5" rx="0.8" fill="url(#sb-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><path d="M7.900 15.500l5.500-4.500 5.500 3 5.200-6.500" fill="none" stroke="#ef4444" stroke-opacity="1" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/><circle cx="7.9" cy="15.5" r="1.4" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="13.4" cy="11" r="1.4" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="18.9" cy="14" r="1.4" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="24.1" cy="7.5" r="1.4" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-trophy" viewBox="0 0 32 32"><path d="M9 7H4.500v2.500c0 3 2 5 4.800 5.500M23 7h4.500v2.500c0 3-2 5-4.800 5.500" fill="none" stroke="#d97706" stroke-opacity="1" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.500 3.500h15v8.500c0 4.800-3.400 7.800-7.500 7.800S8.500 16.800 8.500 12z" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M10.500 5h4v9c-2.500-.8-4-2.500-4-5z" fill="#fff" fill-opacity="0.35"/><path d="M16 7l1.300 2.700 3 .4-2.200 2 .6 3-2.700-1.500-2.700 1.500.6-3-2.200-2 3-.4z" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="14" y="19.5" width="4" height="4.5" rx="0.6" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><rect x="9.5" y="24" width="13" height="5" rx="1.6" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="11.5" y="25.3" width="9" height="1.8" rx="0.6" fill="url(#sb-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-laptop" viewBox="0 0 32 32"><rect x="5" y="4.5" width="22" height="16.5" rx="2.2" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="7" y="6.5" width="18" height="12.5" rx="1" fill="url(#sb-screen)" stroke="#1e1b4b" stroke-opacity=".5" stroke-width=".8"/><path d="M7 7.500a1 1 0 0 1 1-1h8.500L9 19H8a1 1 0 0 1-1-1z" fill="#fff" fill-opacity="0.16"/><path d="M10 12.500l3 2-3 2M15 17h5" fill="none" stroke="#ffffff" stroke-opacity="0.9" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M1.800 22.500h28.400l-1.800 3.600a2 2 0 0 1-1.800 1.100H5.400a2 2 0 0 1-1.800-1.100z" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="13" y="22.5" width="6" height="1.3" rx="0.6" fill="url(#sb-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="sb-cog" viewBox="0 0 32 32"><circle cx="16" cy="16" r="11.3" fill="none" stroke="#64748b" stroke-opacity=".5" stroke-width="5.4" stroke-dasharray="4.45 2.65" stroke-dashoffset="-1.32"/><circle cx="16" cy="16" r="11.3" fill="none" stroke="url(#sb-silver)" stroke-width="4.4" stroke-dasharray="3.55 3.55" stroke-dashoffset="-1.77"/><circle cx="16" cy="16" r="9.3" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="3.6" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="6" fill="url(#sb-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="3.2" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><path d="M9 13a7.500 7.500 0 0 1 8-5c-4 .2-6.500 2-8 5z" fill="#fff" fill-opacity="0.4"/></symbol><symbol id="sb-usertie" viewBox="0 0 32 32"><path d="M3.500 30.500c0-6 5-9.500 12.500-9.500s12.500 3.500 12.500 9.500z" fill="url(#sb-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="16" cy="11" r="5.4" fill="url(#sb-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M10.400 10.800a5.600 5.600 0 0 1 11.200 0c-1.600-2.200-3.600-3-5.600-3s-4 .8-5.600 3z" fill="url(#sb-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M12.200 21.500L16 26.500l3.800-5z" fill="url(#sb-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M14.900 24.500l1.100-1.600 1.100 1.600 1.200 5.500-2.300 1.700-2.300-1.700z" fill="url(#sb-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol></svg>

<aside class="main-sidebar sidebar-light-teal my-shadow">
    <a href="<?= base_url(); ?>" class="brand-link bg-white">
        <?php $logo_app = base_url() . 'assets/img/examly-icon-animasi.svg'; ?>
        <img src="<?= $logo_app ?>" alt="Examly" class="brand-image" style="opacity: 1; width: 33px; height: 33px;">
        <span class="brand-text font-weight-bold">Examly</span>
    </a>

    <div class="sidebar">
        <?php $avatar = $profile->foto == null ? base_url() . 'assets/adminlte/dist/img/avatar5.png' : base_url() . $profile->foto; ?>
        <?php /* Panel profil (foto + NoName / Admin) disembunyikan.
                 Hapus tanda komentar ini kalau ingin menampilkannya lagi.
        <div class="user-panel border-0 d-flex mt-3 mb-3">
            <div class="image">
                <img src="<?= $avatar ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block" style="line-height: 1">
                    <?= $profile->nama_lengkap == null ? 'NoName' : $profile->nama_lengkap ?>
                    <br>
                    <small class="text-muted">
                        <?= $profile->jabatan == null ? 'Admin' : $profile->jabatan ?>
                    </small>
                </a>
            </div>
        </div>
        */ ?>

        <nav class="mt-2 mb-5">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent"
                id="tree-menus" data-widget="treeview" role="menu" data-accordion="false">
            </ul>
        </nav>
    </div>

    <?php if (!empty($lan_url)) : ?>
    <div class="lan-info" title="Alamat akses dari komputer lain (LAN)">
        <svg class="sb-mini" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#sb-network"/></svg>LAN: <a href="<?= htmlspecialchars($lan_url); ?>"><?= htmlspecialchars($lan_url); ?></a>
    </div>
    <?php endif; ?>
</aside>

<script>
    const page = '<?= $this->uri->segment(1) ?>';
    const pageact = '<?= $this->uri->segment(2); ?>';
    const baseUrlSistem = '<?= base_url(); ?>';
    const currentPath = pageact ? `${page}/${pageact}` : page;

    // Menu Data E-Learning, Hasil E-Learning, RAPOR, dan Alumni telah dibuang
    const menus = [
        {
            'header': 'HOME', 'cbt': '1',
            'menu': [
                {'name': 'Dashboard', 'link': 'dashboard', 'icon': 'sb-desktop', 'cbt': '1'},
                {
                    'name': 'Data Umum', 'icon': 'sb-server', 'cbt': '1',
                    'submenu': [
                        {'name': "Tahun Pelajaran", 'link': "datatahun", 'icon': 'sb-calcheck'},
                        {'name': "Mata Pelajaran", 'link': "datamapel", 'icon': 'sb-book'},
                        {'name': "Jurusan", 'link': "datajurusan", 'icon': 'sb-flask'},
                        {'name': "Siswa", 'link': "datasiswa", 'icon': 'sb-users'},
                        {'name': "Kelas / Rombel", 'link': "datakelas", 'icon': 'sb-school'},
                        {'name': "Ekstrakurikuler", 'link': "dataekstra", 'icon': 'sb-chess'},
                        {'name': "Guru", 'link': "dataguru", 'icon': 'sb-teacher'}
                    ]
                },
                {
                    'name': 'Data Ujian', 'icon': 'sb-graduate', 'cbt': '1',
                    'submenu': [
                        {'name': "Jenis Ujian", 'link': "cbtjenis", 'icon': 'sb-diagram'},
                        {'name': "Sesi", 'link': "cbtsesi", 'icon': 'sb-clock'},
                        {'name': "Ruang", 'link': "cbtruang", 'icon': 'sb-door'},
                        {'name': "Atur Ruang/Sesi", 'link': "cbtsesisiswa", 'icon': 'sb-userclock'},
                        {'name': "Nomor Peserta", 'link': "cbtnomorpeserta", 'icon': 'sb-idcard'},
                        {'name': "Bank Soal", 'link': "cbtbanksoal", 'icon': 'sb-folder'},
                        {'name': "Jadwal", 'link': "cbtjadwal", 'icon': 'sb-calendar'},
                        {'name': "Alokasi Waktu", 'link': "cbtalokasi", 'icon': 'sb-hourglass'},
                        {'name': "Pengawas", 'link': "cbtpengawas", 'icon': 'sb-briefcase'},
                        {'name': "Token", 'link': "cbttoken", 'icon': 'sb-key'}
                    ]
                },
                {
                    'name': 'Pengumuman', 'link': 'pengumuman', 'icon': 'sb-bullhorn', 'cbt': '1'
                },
                <?php if ($show_ai_soal_menu): ?>
                {
                    'name': 'AI Soal <span class="badge-new-blink">New</span>', 'link': 'aiimport', 'icon': 'sb-magic icon-alive', 'cbt': '1'
                },
                <?php endif; ?>
                {
                    'name': 'QR Login Siswa', 'link': '#', 'action': 'qrlogin', 'icon': 'sb-qr', 'cbt': '1'
                },
            ]
        },
        {
            'header': 'PELAKSANAAN', 'cbt': '1',
            'menu': [
                {
                    'name': 'Pelaksanaan Ujian', 'icon': 'sb-gradcap', 'cbt': '1',
                    'submenu': [
                        {'name': 'Cetak', 'link': "cbtcetak", 'icon': 'sb-print'},
                        {'name': 'Status Siswa', 'link': "cbtstatus", 'icon': 'sb-userclock'},
                        {'name': 'Hasil Ujian', 'link': "cbtnilai", 'icon': 'sb-filealt'},
                        {'name': 'Analisis Soal', 'link': "cbtanalisis", 'icon': 'sb-chart'},
                        {'name': 'Rekap Nilai', 'link': "cbtrekap", 'icon': 'sb-trophy'},
                        {'name': 'Log Login', 'link': "loglogin", 'icon': 'sb-laptop'},
                    ]
                },
            ]
        },
        {
            'header': 'PENGATURAN', 'cbt': '1',
            'menu': [
                {'name': 'Profile Sekolah', 'link': 'settings', 'icon': 'sb-university', 'cbt': '1'},
                {
                    'name': 'User Management', 'icon': 'sb-userscog', 'cbt': '1',
                    'submenu': [
                        {'name': 'Administrator', 'link': "useradmin", 'icon': 'sb-cog'},
                        {'name': 'Guru', 'link': "userguru", 'icon': 'sb-usertie'},
                        {'name': 'Siswa', 'link': "usersiswa", 'icon': 'sb-users'}
                    ]
                },
                {
                    'name': 'Database', 'icon': 'sb-database', 'cbt': '1',
                    'submenu': [
                        {'name': 'Backup', 'link': "dbmanager", 'icon': 'sb-dbbackup'},
                        {'name': 'Data Manager', 'link': "dbclear", 'icon': 'sb-dbclear'},
                    ]
                },
            ]
        },
        {
            'header': null,
            'logout_button': true
        }
    ];

    // Ikon premium (SVG sprite di atas sidebar). Nama pertama = id simbol, sisanya = class tambahan.
    function ico(spec) {
        const p = String(spec || '').split(' ');
        return `<svg class="nav-icon sb-ico ${p.slice(1).join(' ')}" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#${p[0]}"/></svg>`;
    }

    const isLogin = localStorage.getItem('garudaCBT.login');
    const isCbtMode = isLogin ? isLogin === '1' : false;
    let htmlMenu = '';

    menus.forEach(function (header) {
        if (isCbtMode && header.cbt === '0') {
            return;
        }

        if (header.header) {
            htmlMenu += `<li class="nav-header">${header.header}</li>`;
            
            header.menu.forEach(function (menu) {
                if (isCbtMode && menu.cbt === '0') {
                    return;
                }

                if (menu.submenu) {
                    let subLinks = menu.submenu.map(function (item) {
                        return item.link;
                    });

                    let isTreeOpen = subLinks.includes(currentPath) || subLinks.includes(page);
                    
                    htmlMenu += `
                    <li class="nav-item has-treeview ${isTreeOpen ? "menu-open" : ""}">
                        <a href="#" class="nav-link ${isTreeOpen ? "active" : ""}">
                            ${ico(menu.icon)}
                            <p>${menu.name}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">`;

                    menu.submenu.forEach(function (sub) {
                        let isSubActive = (currentPath === sub.link) || (page === sub.link);
                        
                        htmlMenu += `
                            <li class="nav-item">
                                <a href="${baseUrlSistem + sub.link}" class="nav-link ${isSubActive ? "active" : ""}">
                                    ${ico(sub.icon)}
                                    <p>${sub.name}</p>
                                </a>
                            </li>`;
                    });

                    htmlMenu += `</ul></li>`;
                } else {
                    let isMenuActive = (page === menu.link);
                    let menuHref = menu.action ? '#' : baseUrlSistem + menu.link;
                    let menuAttr = menu.action ? `data-action="${menu.action}"` : '';
                    htmlMenu += `
                    <li class="nav-item">
                        <a href="${menuHref}" ${menuAttr} class="nav-link ${isMenuActive ? "active" : ""}">
                            ${ico(menu.icon)}
                            <p>${menu.name}</p>
                        </a>
                    </li>`;
                }
            });
        } else if (header.logout_button) {
            htmlMenu += `
            <hr style="border-top: 1px solid #dfdfdf;" />
            <li class="nav-item">
                <a href="${baseUrlSistem}login/logout_admin" class="nav-link bg-danger text-white">
                    ${ico('sb-logout')}
                    <p>LOGOUT</p>
                </a>
            </li>`;
        }
    });

    $('#tree-menus').html(htmlMenu);

    // ===================================================================
    // LIQUID SIDEBAR — gelembung warna meluncur & melar mengikuti menu
    // (hover = pindah ke menu yang disorot, keluar = kembali ke menu aktif)
    // ===================================================================
    (function () {
        var ul = document.getElementById('tree-menus');
        if (!ul) return;

        var SIZE = 38;                       // diameter gelembung (px)
        var PALETTE = [                      // [hex, "r,g,b"] — urutan sama seperti di video
            ['#20c997', '32,201,151'],
            ['#ff3d7f', '255,61,127'],
            ['#4f7cff', '79,124,255'],
            ['#a43cff', '164,60,255'],
            ['#ffb02e', '255,176,46'],
            ['#00b8e6', '0,184,230'],
            ['#ff6b3d', '255,107,61']
        ];
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Setiap menu utama dapat warnanya sendiri; submenu mewarisi dari induknya.
        $(ul).children('li.nav-item').each(function (i) {
            var c = PALETTE[i % PALETTE.length];
            this.style.setProperty('--c', c[0]);
            this.style.setProperty('--rgb', c[1]);
        });

        var blob = document.createElement('span');
        blob.id = 'sb-blob';
        ul.appendChild(blob);

        var hovered = null, lit = null, timer = null, rafId = null, shown = false;
        var cur = { x: 0, y: 0 };

        function trans(dur, ease) {
            return ['top', 'left', 'width', 'height'].map(function (k) {
                return k + ' ' + dur + 's ' + ease;
            }).join(',') + ',opacity .3s ease,transform .35s ease,background-color .35s ease,box-shadow .35s ease';
        }

        // Titik tengah ikon relatif terhadap <ul> (null kalau menu tersembunyi)
        function iconCenter(link) {
            var ic = link.querySelector('.nav-icon');
            if (!ic) return null;
            var r = ic.getBoundingClientRect();
            if (!r.width) return null;
            var u = ul.getBoundingClientRect();
            return { x: r.left - u.left + r.width / 2, y: r.top - u.top + r.height / 2 };
        }

        // Menu aktif (submenu yang terbuka diutamakan)
        function restLink() {
            var list = ul.querySelectorAll('.nav-treeview .nav-link.active');
            var i, top = ul.querySelectorAll(':scope > .nav-item > .nav-link.active');
            for (i = 0; i < list.length; i++) if (iconCenter(list[i])) return list[i];
            for (i = 0; i < top.length; i++) if (iconCenter(top[i])) return top[i];
            return null;
        }

        function light(link) {
            if (lit === link) return;
            if (lit) lit.classList.remove('sb-lit');
            if (link) link.classList.add('sb-lit');
            lit = link;
        }

        function paint(link) {
            var cs = getComputedStyle(link);
            blob.style.setProperty('--c', (cs.getPropertyValue('--c') || '#20c997').trim());
            blob.style.setProperty('--rgb', (cs.getPropertyValue('--rgb') || '32,201,151').trim());
        }

        function hide() {
            clearTimeout(timer);
            light(null);
            shown = false;
            blob.classList.remove('sb-on');
        }

        function setGeom(x, y, w, h) {
            blob.style.left = x + 'px';
            blob.style.top = y + 'px';
            blob.style.width = w + 'px';
            blob.style.height = h + 'px';
        }

        // Pindah ke menu `link`: melar dulu ke arah tujuan, lalu "menetes" menyusut (jelly).
        function moveTo(link, animate) {
            var p = iconCenter(link);
            if (!p) { hide(); return; }
            var x1 = p.x - SIZE / 2, y1 = p.y - SIZE / 2;
            clearTimeout(timer);
            paint(link);
            light(link);

            if (!shown || !animate || reduce) {
                var wasShown = shown;
                if (!wasShown) {
                    blob.classList.add('sb-track');          // tempatkan tanpa meluncur
                    setGeom(x1, y1, SIZE, SIZE);
                    void blob.offsetWidth;                   // paksa reflow
                    blob.classList.remove('sb-track');
                    blob.style.transition = trans(.2, 'ease');
                } else {
                    blob.style.transition = trans(.25, 'ease-out');
                    setGeom(x1, y1, SIZE, SIZE);
                }
                blob.classList.add('sb-on');
                shown = true;
                cur = { x: x1, y: y1 };
                return;
            }

            var dist = Math.abs(y1 - cur.y);
            var t1 = 0.12 + Math.min(dist, 300) / 300 * 0.08;   // fase melar 120–200 ms
            var squeeze = SIZE * 0.78;

            // Fase 1 — melar: bentuk kapsul membentang dari posisi lama ke tujuan
            blob.style.transition = trans(t1, 'cubic-bezier(.2,.7,.2,1)');
            setGeom(x1 + (SIZE - squeeze) / 2, Math.min(cur.y, y1), squeeze, dist + SIZE);

            // Fase 2 — menetes: ekor menyusul, sedikit memantul
            timer = setTimeout(function () {
                blob.style.transition = trans(.42, 'cubic-bezier(.34,1.56,.64,1)');
                setGeom(x1, y1, SIZE, SIZE);
            }, t1 * 1000);

            cur = { x: x1, y: y1 };
        }

        // Ikuti posisi ikon tiap frame (dipakai saat layout bergerak: submenu
        // membuka/menutup, sidebar melebar saat hover mode ciut, dsb.)
        function track(ms) {
            cancelAnimationFrame(rafId);
            clearTimeout(timer);
            var end = performance.now() + ms;
            blob.classList.add('sb-track');
            (function loop() {
                var link = hovered || restLink();
                var p = link && iconCenter(link);
                if (p) {
                    paint(link);
                    light(link);
                    setGeom(p.x - SIZE / 2, p.y - SIZE / 2, SIZE, SIZE);
                    cur = { x: p.x - SIZE / 2, y: p.y - SIZE / 2 };
                    blob.classList.add('sb-on');
                    shown = true;
                } else if (!hovered) {
                    hide();
                }
                if (performance.now() < end) {
                    rafId = requestAnimationFrame(loop);
                } else {
                    blob.classList.remove('sb-track');
                    blob.style.transition = trans(.25, 'ease-out');
                }
            })();
        }

        $(ul).on('mouseenter', '.nav-link', function () {
            if (this.classList.contains('bg-danger')) {      // tombol LOGOUT: tidak dapat gelembung
                hovered = null;
                var r = restLink();
                if (r) moveTo(r, true); else hide();
                return;
            }
            hovered = this;
            moveTo(this, true);
        });

        $(ul).on('mouseleave', function () {
            hovered = null;
            var r = restLink();
            if (r) moveTo(r, true); else hide();
        });

        // Buka/tutup submenu menggeser menu di bawahnya
        $(ul).on('click', '.nav-link', function () {
            if ($(this).next('.nav-treeview').length) track(650);
        });

        // Sidebar melebar/menciut
        $('.main-sidebar').on('mouseenter mouseleave', function () { track(550); });
        $(document).on('collapsed.lte.pushmenu shown.lte.pushmenu', function () { track(550); });
        $(window).on('resize', function () { track(120); });
        $(window).on('load', function () { track(400); });

        track(400);   // posisi awal di menu aktif
    })();
</script>

<!-- ===== Modal QR Login Siswa ===== -->
<div class="modal fade" id="modalQrLogin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 400px;">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold"><svg class="sb-mini sb-title-ico mr-2" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#sb-qr"/></svg>QR Login Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted mb-3">Scan dengan kamera HP/tablet untuk membuka halaman login ujian.</p>
                <div id="qrLoginBox" style="display:inline-block;padding:12px;background:#fff;border:1px solid #e2e8f0;border-radius:12px;"></div>
                <div class="mt-3 font-weight-bold" id="qrLoginUrl" style="word-break:break-all;color:#0f766e;"></div>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="qrLoginCopy"><svg class="sb-mini mr-1" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#sb-copy"/></svg>Salin Link</button>
                <button type="button" class="btn btn-sm btn-success" id="qrLoginPrint"><svg class="sb-mini mr-1" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#sb-print"/></svg>Cetak</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/qrcode.min.js') ?>"></script>
<script>
    (function () {
        // Alamat yang dipakai siswa = IP LAN server (sama dengan yang tampil di bawah sidebar).
        // Jika IP LAN tidak terdeteksi, pakai alamat yang sedang dibuka.
        var lanUrl = <?= json_encode(!empty($lan_url) ? rtrim($lan_url, '/') . '/' : '') ?>;
        var loginUrl = lanUrl || baseUrlSistem;
        var rendered = false;

        $(document).on('click', 'a[data-action="qrlogin"]', function (e) {
            e.preventDefault();
            $('#modalQrLogin').appendTo('body').modal('show');
        });

        $('#modalQrLogin').on('shown.bs.modal', function () {
            if (rendered) return;
            rendered = true;
            $('#qrLoginUrl').text(loginUrl);
            if (typeof QRCode === 'undefined') {
                $('#qrLoginBox').text('Pustaka QR belum terpasang (assets/js/qrcode.min.js).');
                return;
            }
            new QRCode(document.getElementById('qrLoginBox'), {
                text: loginUrl, width: 240, height: 240,
                correctLevel: QRCode.CorrectLevel.M
            });
        });

        $('#qrLoginCopy').on('click', function () {
            var btn = $(this);
            var done = function () {
                btn.html('<svg class="sb-mini mr-1" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#sb-check"/></svg>Tersalin');
                setTimeout(function () { btn.html('<svg class="sb-mini mr-1" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#sb-copy"/></svg>Salin Link'); }, 1500);
            };
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(loginUrl).then(done);
            } else {
                var t = $('<input>').val(loginUrl).appendTo('body').select();
                document.execCommand('copy'); t.remove(); done();
            }
        });

        $('#qrLoginPrint').on('click', function () {
            var w = window.open('', '_blank', 'width=480,height=620');
            var img = $('#qrLoginBox').find('canvas')[0];
            var src = img ? img.toDataURL('image/png') : $('#qrLoginBox img').attr('src');
            w.document.write('<html><head><title>QR Login Siswa</title></head>' +
                '<body style="font-family:Arial;text-align:center;padding:30px">' +
                '<h2>Login Ujian Siswa</h2><p>Scan QR untuk membuka halaman login</p>' +
                '<img src="' + src + '" style="width:300px;height:300px"><p style="font-weight:bold">' + loginUrl + '</p>' +
                '<script>window.onload=function(){window.print();}<\/script></body></html>');
            w.document.close();
        });
    })();
</script>

<?php if (!empty($lan_url)) : ?>
<script>
    // Pindahkan info "LAN: http://..." dari bawah sidebar ke navbar atas, di samping pil jam.
    // Pil dibuat dari salinan pil jam (gaya sama persis), jadi tidak perlu mengubah file navbar.
    (function () {
        var info = document.querySelector('.main-sidebar .lan-info');
        var link = info && info.querySelector('a');
        if (!info || !link) return;

        var timeRe = /^\s*\d{1,2}[:.]\d{2}([:.]\d{2})?(\s*[A-Za-z]{2,4})?\s*$/;
        var tries = 0;

        // Cari pil jam: elemen terdekat di atas teks jam yang punya tampilan "pil"
        // (sudut membulat + latar/garis tepi). Hanya satu pil, bukan pembungkusnya.
        function findClockPill(header) {
            var tw = document.createTreeWalker(header, NodeFilter.SHOW_TEXT, null), n, leaf = null;
            while ((n = tw.nextNode())) {
                if (timeRe.test(n.nodeValue)) { leaf = n.parentElement; break; }
            }
            if (!leaf) return null;
            var el = leaf;
            while (el && el !== header) {
                var cs = getComputedStyle(el);
                var rad = parseFloat(cs.borderTopLeftRadius) || 0;
                var hasBg = cs.backgroundColor && cs.backgroundColor !== 'rgba(0, 0, 0, 0)' && cs.backgroundColor !== 'transparent';
                var hasBorder = parseFloat(cs.borderTopWidth) > 0;
                if (rad >= 6 && (hasBg || hasBorder || cs.boxShadow !== 'none')) return { pill: el, leaf: leaf };
                el = el.parentElement;
            }
            return { pill: leaf, leaf: leaf };
        }

        // Pindahkan tombol tema siang/malam ke pojok kanan (setelah jam).
        // Tombol dan jam dicari induk bersamanya, lalu tombol dipindah ke sesudah jam
        // di tingkat yang sama, jadi tata letak aslinya tidak berubah.
        function moveThemeToggleRight(header, pill, leaf, box) {
            var cands = header.querySelectorAll('[class*="sun"], [class*="moon"], [class*="theme"], [id*="theme"], [id*="dark"]');
            var icon = null;
            for (var i = 0; i < cands.length; i++) {
                var c = cands[i];
                if (c === leaf || c.contains(leaf) || c.contains(box) || box.contains(c)) continue;
                icon = c; break;
            }
            if (!icon) return;

            // Induk bersama (LCA) tombol dan jam
            var lca = icon, toggleTop = icon;
            while (lca && lca !== header && !lca.contains(leaf)) { toggleTop = lca; lca = lca.parentElement; }
            if (!lca) return;
            var clockTop = leaf;
            while (clockTop.parentElement && clockTop.parentElement !== lca) clockTop = clockTop.parentElement;
            if (toggleTop === clockTop || toggleTop.parentElement !== lca || clockTop.parentElement !== lca) return;

            // Jangan pindahkan blok besar (mis. seluruh sisi kiri navbar)
            if (toggleTop.getBoundingClientRect().width > 220) return;
            // Sudah di sebelah kanan jam? tidak perlu dipindah
            if (clockTop.compareDocumentPosition(toggleTop) & Node.DOCUMENT_POSITION_FOLLOWING) return;

            toggleTop.style.marginLeft = '14px';
            toggleTop.style.marginRight = '0';
            lca.insertBefore(toggleTop, clockTop.nextSibling);
        }

        // Ikuti tema siang/malam: pil LAN ikut berubah warna saat tombol tema ditekan
        function setupThemeSync(header, box, pill, leaf, isPill, a) {
            function bgOf(el) {
                while (el) {
                    var c = getComputedStyle(el).backgroundColor;
                    if (c && c !== 'transparent' && c !== 'rgba(0, 0, 0, 0)') return c;
                    el = el.parentElement;
                }
                return 'rgb(255,255,255)';
            }
            function apply() {
                var m = bgOf(header).match(/[\d.]+/g) || [255, 255, 255];
                var lum = (0.299 * m[0] + 0.587 * m[1] + 0.114 * m[2]) / 255;
                var dark = lum < 0.5 || document.documentElement.classList.contains('dark-mode') || document.body.classList.contains('dark-mode');
                if (dark) box.setAttribute('data-dark', '1'); else box.removeAttribute('data-dark');
                if (isPill && pill.isConnected) {            // pil jam asli: salin ulang gayanya
                    var cs = getComputedStyle(pill), ls = getComputedStyle(leaf);
                    box.style.background = cs.backgroundColor;
                    box.style.borderColor = cs.borderTopColor;
                    box.style.boxShadow = cs.boxShadow;
                    a.style.color = ls.color;
                }
            }
            var later = function () { apply(); setTimeout(apply, 120); setTimeout(apply, 450); };
            var mo = new MutationObserver(later);
            [document.documentElement, document.body].forEach(function (n) {
                mo.observe(n, { attributes: true, attributeFilter: ['class', 'data-theme', 'data-bs-theme', 'style'] });
            });
            header.addEventListener('click', later, true);
            window.addEventListener('storage', later);
            apply();
        }

        function build() {
            if (document.querySelector('.navbar-lan-pill')) return true;
            var header = document.querySelector('.main-header');
            if (!header) return false;
            var f = findClockPill(header);
            if (!f || !f.pill.parentNode) return false;
            var pill = f.pill, leaf = f.leaf;
            var cs = getComputedStyle(pill), ls = getComputedStyle(leaf);

            // Jam berbentuk pil (Dashboard) atau teks polos (halaman lain)?
            var isPill = (pill !== leaf) && (parseFloat(cs.borderTopLeftRadius) || 0) >= 6;

            var box = document.createElement('div');
            box.className = 'navbar-lan-pill';
            box.title = 'Alamat akses dari komputer lain (LAN)';
            if (isPill) {
                // Pil LAN baru (bukan salinan), gayanya disamakan dengan pil jam
                box.style.cssText = 'display:inline-flex;align-items:center;white-space:nowrap;margin-right:10px;' +
                    'background:' + cs.backgroundColor + ';border:' + cs.borderTopWidth + ' ' + cs.borderTopStyle + ' ' + cs.borderTopColor +
                    ';border-radius:' + cs.borderTopLeftRadius + ';box-shadow:' + cs.boxShadow +
                    ';padding:' + cs.paddingTop + ' ' + cs.paddingRight + ' ' + cs.paddingBottom + ' ' + cs.paddingLeft +
                    ';height:' + cs.height + ';box-sizing:' + cs.boxSizing;
            } else {
                // Jam teks polos: LAN dibuat sebaris di kiri jam, tanpa kotak
                box.style.cssText = 'display:inline-flex;align-items:center;vertical-align:middle;white-space:nowrap;margin:0 14px 0 18px;' +
                    'background:#fff;border:1px solid #e2e8f0;border-radius:10px;box-shadow:0 1px 3px rgba(0,0,0,.10);' +
                    'padding:0 14px;height:34px;box-sizing:border-box;';
                box.setAttribute('data-plain', '1');
                if (getComputedStyle(leaf).display === 'block') leaf.style.display = 'inline-block';
                var par = pill.parentElement;
                if (par) {
                    var ps = getComputedStyle(par);
                    if ((ps.display === 'flex' || ps.display === 'inline-flex') && ps.flexDirection.indexOf('column') === 0) {
                        par.style.flexDirection = 'row';
                        par.style.alignItems = 'center';
                    }
                    par.style.whiteSpace = 'nowrap';
                }
            }

            var ns = document.createElement('span');
            ns.innerHTML = '<svg class="sb-mini" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#sb-network"/></svg>';
            var icon = ns.firstChild;
            icon.style.marginRight = '8px';

            var a = document.createElement('a');
            a.href = link.href;
            a.textContent = 'LAN: ' + link.textContent.trim();
            a.style.cssText = 'color:' + ls.color + ';font:' + ls.font + ';letter-spacing:' + ls.letterSpacing +
                              ';text-decoration:none;white-space:nowrap';

            box.appendChild(icon);
            box.appendChild(a);
            pill.parentNode.insertBefore(box, pill);     // tepat di kiri pil jam
            try { moveThemeToggleRight(header, pill, leaf, box); } catch (e) {}
            try { setupThemeSync(header, box, pill, leaf, isPill, a); } catch (e) {}
            info.classList.add('lan-moved');
            return true;
        }

        function tick() {
            var ok = false;
            try { ok = build(); } catch (e) { ok = false; }
            if (ok || ++tries > 60) { clearInterval(timer); }
        }
        var timer = setInterval(tick, 250);
        tick();
    })();
</script>
<?php endif; ?>