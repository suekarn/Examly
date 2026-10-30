<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Konversi Soal Ujian</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light dark">
<script>try{if(localStorage.getItem('garudaCBT.theme')==='dark')document.documentElement.setAttribute('data-theme','dark');}catch(e){}</script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/fontawesome-free/css/v4-shims.min.css">
<style>
  :root{
    --bg:#EEF0F6; --sheet:#FFFFFF; --sunken:#F6F7FB; --line:#E4E7EF; --line-strong:#CDD2E0;
    --ink:#10152B; --muted:#5E6780;
    --accent:#2B3990; --accent-2:#4254C8; --accent-ink:#FFFFFF; --accent-soft:#EDEFFC;
    --gold:#B98F4A; --gold-2:#E3C687;
    --top:#0E1430; --top-ink:#F7F3E8; --top-muted:#AAB3D6;
    --ok:#23694A; --ok-bg:#E5F3EB;
    --warn:#80520F; --warn-bg:#FBF1D9;
    --err:#933630; --err-bg:#F9E6E3;
    --shadow:0 1px 1px rgba(14,20,48,.04),0 2px 4px rgba(14,20,48,.04),0 24px 48px -16px rgba(14,20,48,.28);
    --tombol:inset 0 1px 0 rgba(255,255,255,.22),0 1px 2px rgba(14,20,48,.3),0 10px 20px -10px rgba(43,57,144,.7);
    --serif:"Iowan Old Style","Palatino Linotype",Palatino,"Book Antiqua",Georgia,serif;
    --sans:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
    --mono:ui-monospace,SFMono-Regular,Menlo,Consolas,monospace;
  }
  :root[data-theme="dark"]{
      --bg:#080B18; --sheet:#121830; --sunken:#0D1226; --line:#242C4A; --line-strong:#38426B;
      --ink:#E9ECF7; --muted:#97A2C0;
      --accent:#8E9EFF; --accent-2:#B0BBFF; --accent-ink:#0B1030; --accent-soft:#1B2347;
      --gold:#D8B16C; --gold-2:#F0D9A4;
      --top:#060916; --top-ink:#F4F0E4; --top-muted:#8F9ABD;
      --ok:#8FD0A9; --ok-bg:#16291F;
      --warn:#E5C27C; --warn-bg:#31271A;
      --err:#F0A59D; --err-bg:#36201E;
      --shadow:0 1px 1px rgba(0,0,0,.4),0 28px 56px -16px rgba(0,0,0,.7);
      --tombol:inset 0 1px 0 rgba(255,255,255,.4),0 1px 2px rgba(0,0,0,.5),0 10px 22px -10px rgba(142,158,255,.45);
  }
  *{box-sizing:border-box}
  html{-webkit-text-size-adjust:100%;scroll-behavior:smooth}
  body{margin:0;background:var(--bg);color:var(--ink);font:16px/1.65 var(--sans);-webkit-font-smoothing:antialiased;text-rendering:optimizeLegibility}

  /* Menu samping kiri (meniru sidebar aplikasi) */
  .side{position:fixed;top:0;bottom:0;left:0;width:250px;display:flex;flex-direction:column;z-index:50;
    background:#fff;color:#334155;border-right:1px solid #e2e8f0;font:14px/1.4 'Inter',system-ui,sans-serif;
    overflow:hidden;transition:transform .25s,width .25s}
  html[data-theme="dark"] .side{background:#1b2438;color:#e2e8f0;border-color:#2a344a}
  .side-brand{flex:none;height:57px;display:flex;align-items:center;gap:10px;padding:0 16px;text-decoration:none;color:inherit;
    font-weight:700;font-size:16px;border-bottom:1px solid #e2e8f0;white-space:nowrap}
  html[data-theme="dark"] .side-brand{border-color:#2a344a}
  .side-brand img{width:33px;height:33px;flex:none}
  .side nav{flex:1;min-height:0;overflow-y:auto;overflow-x:hidden;padding:8px 8px 10px;scrollbar-width:none;-ms-overflow-style:none}
  .side nav::-webkit-scrollbar{display:none}
  .side .grp{padding:10px 14px 6px;font-size:14px;font-weight:500;color:#94a3b8;white-space:nowrap}
  .side a,.side summary{display:flex;align-items:center;gap:12px;margin:1px 0;padding:7px 12px;border-radius:6px;color:inherit;
    text-decoration:none;cursor:pointer;list-style:none;transition:background .2s,color .2s;white-space:nowrap}
  .side summary::-webkit-details-marker{display:none}
  .side .ik{width:20px;flex:none;text-align:center;font-size:15px;transition:color .2s}
  .side .nm{flex:1}
  .side summary::after{content:"";width:6px;height:6px;border-right:2px solid currentColor;border-bottom:2px solid currentColor;
    transform:rotate(135deg);transition:transform .2s;opacity:.6;margin-right:4px}
  .side details[open]>summary::after{transform:rotate(45deg)}
  .side a:hover,.side summary:hover{background:#20c997;color:#fff;box-shadow:0 2px 8px rgba(32,201,151,.35)}
  .side a:hover .ik,.side summary:hover .ik{color:#fff!important}
  .side .sub a{padding-left:20px;font-size:13.5px}
  .side .sub .ik{font-size:13px;color:#94a3b8}
  .side a.aktif{background:#20c997;color:#fff}
  .side a.aktif .ik{color:#fff!important}
  html[data-theme="dark"] .side a.aktif{background:rgba(32,201,151,.16);color:#2dd4bf}
  html[data-theme="dark"] .side a.aktif .ik{color:#2dd4bf!important}
  .side .baru{background:#e74c3c;color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:8px;letter-spacing:.5px}
  .side a.keluar{margin-top:8px;background:#dc3545;color:#fff;font-weight:600}
  .side a.keluar:hover{background:#c82333}
  .side .lan{flex:none;padding:10px 14px;font-size:12px;font-weight:600;border-top:1px solid rgba(128,128,128,.25);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .side .lan a{display:inline;padding:0;margin:0;background:none;box-shadow:none;color:inherit;text-decoration:none}
  .side .lan a:hover{background:none;box-shadow:none;color:#20c997}
  .side .lan i{margin-right:6px;opacity:.7}
  .c-dash{color:#3498db}.c-umum{color:#1abc9c}.c-ujian{color:#e74c3c}.c-info{color:#f1c40f}.c-ai{color:#9b59b6}.c-set{color:#7f8c8d}
  .konten{margin-left:250px;min-width:0;transition:margin-left .25s}
  #navToggle,.burger{display:none}

  /* Desktop: sidebar menciut jadi deretan ikon (seperti halaman lain), melebar sementara saat disentuh mouse */
  @media (min-width:901px){
    body.side-tutup .konten{margin-left:73px}
    body.side-tutup .side:not(:hover){width:73px}
    body.side-tutup .side:not(:hover) .nm,
    body.side-tutup .side:not(:hover) .grp,
    body.side-tutup .side:not(:hover) .lan,
    body.side-tutup .side:not(:hover) .baru,
    body.side-tutup .side:not(:hover) .sub{display:none}
    body.side-tutup .side:not(:hover) summary::after{display:none}
    body.side-tutup .side:not(:hover) a,
    body.side-tutup .side:not(:hover) summary{justify-content:center;padding-left:0;padding-right:0}
    body.side-tutup .side:not(:hover) .side-brand{justify-content:center;padding:0}
    body.side-tutup .side:not(:hover) nav{padding-top:14px}
    /* Perbesar ikon saat sidebar menciut */
    body.side-tutup .side:not(:hover) .ik{font-size:20px;width:auto}
    body.side-tutup .side:not(:hover) a,
    body.side-tutup .side:not(:hover) summary{padding-top:10px;padding-bottom:10px}
    /* saat melebar karena disentuh mouse, tampil di atas konten tanpa menggeser */
    body.side-tutup .side:hover{box-shadow:6px 0 24px -8px rgba(15,23,42,.35)}
  }

  @media (max-width:900px){
    .side{transform:translateX(-100%)}
    #navToggle:checked~.side{transform:none;box-shadow:0 0 0 100vmax rgba(0,0,0,.45)}
    .konten{margin-left:0}
    .burger{display:grid;position:fixed;top:10px;left:10px;z-index:60;width:40px;height:40px;place-items:center;border-radius:10px;
      background:var(--top);color:var(--top-ink);font-size:1.2rem;cursor:pointer;border:1px solid rgba(255,255,255,.15)}
  }

  /* Hero */
  .top{position:relative;overflow:hidden;isolation:isolate;background-color:var(--top);padding:0 20px 72px;
    background-image:
      radial-gradient(60% 90% at 88% -10%,rgba(98,116,240,.42),transparent 62%),
      radial-gradient(50% 70% at 8% 110%,rgba(185,143,74,.24),transparent 60%),
      repeating-linear-gradient(to bottom,transparent 0 35px,rgba(255,255,255,.04) 35px 36px)}
  .top::after{content:"";position:absolute;left:0;right:0;bottom:0;height:3px;
    background:linear-gradient(90deg,transparent,var(--gold) 20%,var(--gold-2) 50%,var(--gold) 80%,transparent)}
  .top-in{position:relative;max-width:820px;margin:0 auto}
  .mark{display:grid;place-items:center;width:44px;height:44px;border-radius:14px;margin-bottom:16px;color:var(--gold-2);
    background:linear-gradient(145deg,rgba(255,255,255,.12),rgba(255,255,255,.02));
    border:1px solid rgba(227,198,135,.45);box-shadow:inset 0 1px 0 rgba(255,255,255,.18),0 12px 30px -12px rgba(0,0,0,.6);backdrop-filter:blur(6px)}
  .mark,.eyebrow,.pills{display:none}
  .eyebrow{display:block;margin:0 0 10px;font:600 .74rem/1 var(--sans);letter-spacing:.2em;text-transform:uppercase;color:var(--gold-2)}
  h1{font:600 clamp(1.4rem,3.4vw,1.75rem)/1.2 var(--serif);letter-spacing:-.015em;margin:0 0 8px;max-width:none;
    background:linear-gradient(180deg,#fff 30%,#D9DEF5);-webkit-background-clip:text;background-clip:text;color:transparent}
  .top p{margin:0;color:var(--top-muted);max-width:60ch;font-size:.9rem;line-height:1.55}
  .pills{display:flex;flex-wrap:wrap;gap:8px;margin-top:16px}
  .pills span{font-size:.8rem;font-weight:500;color:var(--top-ink);padding:6px 14px;border-radius:999px;
    background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.14);backdrop-filter:blur(6px)}

  /* Lembar utama */
  .sheet{position:relative;width:min(820px,calc(100% - 24px));margin:28px auto 56px;background:var(--sheet);
    border:1px solid var(--line);border-radius:20px;box-shadow:var(--shadow);padding:30px 32px 34px}
  .sheet::before{content:"";position:absolute;left:36px;right:36px;top:-1px;height:2px;border-radius:2px;
    background:linear-gradient(90deg,transparent,var(--gold),var(--gold-2),var(--gold),transparent)}

  .steps{list-style:none;margin:0;padding:0}
  .step{position:relative;padding-left:52px;padding-bottom:26px}
  .step:last-child{padding-bottom:0}
  .step:not(:last-child)::before{content:"";position:absolute;left:15px;top:40px;bottom:6px;width:2px;border-radius:2px;background:var(--line);transition:background .5s}
  .step.selesai:not(:last-child)::before{background:linear-gradient(to bottom,var(--ok),var(--accent))}
  .marker{position:absolute;left:0;top:0;width:32px;height:32px;border-radius:50%;background:var(--sheet);border:2px solid var(--line-strong);
    display:grid;place-items:center;font:600 .92rem var(--serif);color:var(--muted);transition:background .3s,border-color .3s,box-shadow .3s,color .3s}
  .step.aktif .marker{background:linear-gradient(145deg,var(--accent-2),var(--accent));border-color:transparent;color:var(--accent-ink);
    box-shadow:0 0 0 4px var(--accent-soft)}
  .step.selesai .marker{background:var(--ok);border-color:var(--ok);color:transparent;font-size:0;box-shadow:0 0 0 4px var(--ok-bg)}
  .step.selesai .marker::after{content:"✓";font:700 .95rem var(--sans);color:var(--sheet)}
  .step h2{font:600 1.15rem/1.3 var(--serif);letter-spacing:-.01em;margin:3px 0 2px}
  .step>p.ket{margin:0 0 12px;color:var(--muted);font-size:.88rem;max-width:60ch}
  .step.terkunci{display:none}
  .step:has(+ .terkunci){padding-bottom:0}
  .step:has(+ .terkunci)::before{display:none}
  .step.aktif{animation:naik .45s cubic-bezier(.2,.7,.2,1) both}
  .step .panel{transition:opacity .4s,filter .4s}
  .step.terkunci .panel{opacity:.4;filter:saturate(.4) blur(.4px);pointer-events:none}
  .panel{background:linear-gradient(180deg,var(--sunken),color-mix(in srgb,var(--sunken) 70%,var(--sheet)));
    border:1px solid var(--line);border-radius:14px;padding:16px;box-shadow:inset 0 1px 0 rgba(255,255,255,.5)}
  html[data-theme="dark"] .panel{box-shadow:inset 0 1px 0 rgba(255,255,255,.04)}

  /* Zona unggah */
  .drop{position:relative;display:flex;align-items:center;gap:14px;padding:16px;border:1.5px dashed var(--line-strong);border-radius:12px;
    background:var(--sheet);cursor:pointer;transition:border-color .2s,background .2s,transform .2s,box-shadow .2s}
  .drop:hover,.drop.over,.drop:focus-within{border-color:var(--accent);background:var(--accent-soft);box-shadow:0 12px 28px -16px rgba(43,57,144,.5)}
  .drop.over{transform:scale(1.012)}
  .drop input{position:absolute;opacity:0;width:1px;height:1px}
  .drop .ikon{flex:none;display:grid;place-items:center;width:44px;height:44px;border-radius:12px;
    background:linear-gradient(145deg,var(--accent-soft),var(--sheet));color:var(--accent);border:1px solid var(--line);transition:transform .25s}
  .drop:hover .ikon,.drop.over .ikon{transform:translateY(-3px)}
  .drop svg{width:22px;height:22px}
  .drop b{display:block;font-weight:600;font-size:.96rem;word-break:break-word}
  .drop small{color:var(--muted);font-size:.88rem}
  .daftar-berkas{list-style:none;margin:10px 0 0;padding:0;display:flex;flex-direction:column;gap:6px}
  .daftar-berkas li{display:flex;align-items:center;gap:10px;padding:8px 12px;border:1px solid var(--line-strong);border-radius:10px;font-size:.9rem}
  .daftar-berkas li span{flex:1;min-width:0;word-break:break-word}
  .daftar-berkas li small{color:var(--muted);white-space:nowrap}
  .daftar-berkas li button{flex:none;border:0;background:transparent;color:inherit;cursor:pointer;font-size:1.15rem;line-height:1;padding:2px 6px;border-radius:6px;opacity:.7}
  .daftar-berkas li button:hover{opacity:1;background:var(--accent-soft)}
  .drop.terisi{border-style:solid;border-color:var(--accent);background:var(--accent-soft)}

  /* Saklar */
  .saklar{display:flex;gap:14px;align-items:flex-start;margin-top:22px;padding-top:20px;border-top:1px solid var(--line);cursor:pointer}
  .saklar input{position:absolute;opacity:0;width:1px;height:1px}
  .saklar .rel{flex:none;width:46px;height:27px;border-radius:14px;background:var(--line-strong);position:relative;margin-top:1px;transition:background .25s;
    box-shadow:inset 0 1px 2px rgba(0,0,0,.18)}
  .saklar .rel::after{content:"";position:absolute;left:3px;top:3px;width:21px;height:21px;border-radius:50%;background:#fff;
    box-shadow:0 1px 3px rgba(0,0,0,.35);transition:transform .25s cubic-bezier(.3,1.4,.5,1),background .2s}
  .saklar input:checked+.rel{background:linear-gradient(135deg,var(--accent-2),var(--accent))}
  .saklar input:checked+.rel::after{transform:translateX(19px);background:var(--accent-ink)}
  .saklar input:focus-visible+.rel{outline:3px solid var(--accent);outline-offset:2px}
  .saklar span.teks{font-size:.96rem;font-weight:500}
  .saklar small{display:block;color:var(--muted);font-size:.86rem;font-weight:400;margin-top:2px}

  /* Tombol */
  button,.btn{position:relative;overflow:hidden;font:600 .96rem var(--sans);display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:42px;
    border-radius:10px;padding:0 20px;border:0;cursor:pointer;text-decoration:none;letter-spacing:.01em;
    background:linear-gradient(180deg,var(--accent-2),var(--accent));color:var(--accent-ink);box-shadow:var(--tombol);
    transition:transform .18s,filter .18s,box-shadow .18s}
  button::after,.btn::after{content:"";position:absolute;inset:0;background:linear-gradient(115deg,transparent 35%,rgba(255,255,255,.28) 50%,transparent 65%);
    transform:translateX(-120%);transition:transform .7s}
  button:hover::after,.btn:hover::after{transform:translateX(120%)}
  button:hover,.btn:hover{transform:translateY(-2px);filter:brightness(1.06)}
  button:active,.btn:active{transform:translateY(0)}
  button:disabled{opacity:.55;cursor:progress;transform:none}
  .btn2{background:var(--sheet);color:var(--ink);box-shadow:0 0 0 1px var(--line-strong),0 1px 2px rgba(14,20,48,.06)}
  .btn2::after{display:none}
  .btn2:hover{filter:none;box-shadow:0 0 0 1.5px var(--accent),0 6px 14px -8px rgba(43,57,144,.4)}
  .aksi{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}
  :focus-visible{outline:3px solid var(--accent);outline-offset:2px}

  textarea{display:block;width:100%;min-height:190px;resize:vertical;background:var(--sheet);color:var(--ink);
    border:1px solid var(--line-strong);border-radius:14px;padding:16px 18px;font:.82rem/1.65 var(--mono);transition:border-color .2s,box-shadow .2s;
    box-shadow:inset 0 1px 2px rgba(14,20,48,.05)}
  textarea:focus{outline:0;border-color:var(--accent);box-shadow:0 0 0 4px var(--accent-soft)}

  /* Header atas */
  .nav-atas{position:sticky;top:0;z-index:40;height:57px;display:flex;align-items:center;gap:14px;padding:0 20px;background:#fff;color:#334155;
    border-bottom:1px solid #e2e8f0;box-shadow:0 1px 3px rgba(15,23,42,.06);font:14px/1.4 'Inter',system-ui,sans-serif}
  html[data-theme="dark"] .nav-atas{background:#1b2438;color:#e2e8f0;border-color:#2a344a}
  .nav-atas button{min-height:0;padding:0;border-radius:0;background:none;box-shadow:none;color:inherit;filter:none;transform:none;overflow:visible;font:inherit}
  .nav-atas button::after{display:none}
  .nav-atas button:hover{transform:none;filter:none}
  .tgl{display:flex;flex-direction:column;align-items:flex-start;justify-content:center;gap:5px;width:40px;height:40px;padding:0 11px!important;
    border-radius:12px!important;border:1px solid rgba(32,201,151,.35)!important;
    background:linear-gradient(135deg,rgba(32,201,151,.14),rgba(29,111,216,.14))!important;cursor:pointer}
  .tgl span{display:block;height:2.5px;border-radius:2px;background:#0f172a;transition:width .25s}
  .tgl span:nth-child(1){width:18px}.tgl span:nth-child(2){width:12px}.tgl span:nth-child(3){width:15px}
  /* saat sidebar menciut, panjang garis berbalik (seperti halaman lain) */
  body.side-tutup .tgl span:nth-child(1){width:12px}
  body.side-tutup .tgl span:nth-child(2){width:18px}
  body.side-tutup .tgl span:nth-child(3){width:12px}
  html[data-theme="dark"] .tgl span{background:#e2e8f0}
  .tgl:hover{background:linear-gradient(135deg,#20c997,#1d6fd8)!important;border-color:transparent!important}
  .tgl:hover span{background:#fff!important;width:18px!important}
  .jdl{font-weight:700;font-size:14px}
  .kanan{margin-left:auto;display:flex;align-items:center;gap:8px}
  .kotak{display:flex;align-items:center;gap:8px;padding:6px 12px;border:1px solid #e2e8f0;border-radius:10px;font-weight:600;font-size:13.5px;background:#fff}
  html[data-theme="dark"] .kotak{background:#151d30;border-color:#2a344a}
  .kotak i{color:#64748b;font-size:14px}
  .tema{display:flex;padding:3px;border-radius:999px;background:#e9edf3;gap:2px}
  html[data-theme="dark"] .tema{background:#0f172a}
  .tema button{width:30px;height:28px;border-radius:999px;display:grid;place-items:center;color:#64748b;cursor:pointer;font-size:13px}
  .tema button.on{background:#fff;color:#0f172a;box-shadow:0 1px 3px rgba(0,0,0,.2)}
  html[data-theme="dark"] .tema button.on{background:#2dd4bf;color:#0f172a}
  @media (max-width:900px){.kt-tgl{display:none}}
  @media (max-width:560px){.kotak{display:none}}

  /* Tempel gambar untuk penanda [GAMBAR: ...] */
  #gambarBox{margin-top:24px;padding-top:22px;border-top:1px solid var(--line)}
  #gambarBox h3{font:600 1.2rem var(--serif);margin:0 0 6px}
  #gambarBox p{margin:0 0 14px;color:var(--muted);font-size:.9rem}
  #gambarHitung{display:inline-block;font-weight:600;font-size:.85rem;margin-bottom:14px;color:var(--accent);
    background:var(--accent-soft);padding:4px 12px;border-radius:999px}
  .gbr{display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:14px;padding:14px 16px;border:1.5px solid var(--line);
    border-radius:16px;background:var(--sheet);margin-bottom:10px;cursor:pointer;transition:border-color .2s,background .2s,box-shadow .2s}
  .gbr:hover{box-shadow:0 8px 20px -14px rgba(14,20,48,.4)}
  .gbr.aktif{border-color:var(--accent);background:var(--accent-soft)}
  .gbr.terisi{border-color:var(--ok)}
  .gbr.terisi.aktif{border-color:var(--accent)}
  .gbr.seret{border-style:dashed;border-color:var(--accent)}
  .gbr-info{min-width:0}
  .gbr-info b{display:block;font-size:.93rem}
  .gbr-info small{display:block;color:var(--muted);font-size:.82rem;line-height:1.4}
  .gbr-zona{width:120px;height:72px;border:1.5px dashed var(--line-strong);border-radius:12px;display:grid;place-items:center;
    text-align:center;font-size:.76rem;color:var(--muted);overflow:hidden;background:var(--sunken);padding:4px}
  .gbr.aktif .gbr-zona{border-color:var(--accent);color:var(--accent);background:var(--sheet)}
  .gbr.terisi .gbr-zona{border-style:solid;border-color:var(--ok);padding:0}
  .gbr-thumb{width:100%;height:100%;object-fit:contain;background:#fff}
  .gbr-thumb[hidden]{display:none}
  .gbr-aksi{display:flex;flex-direction:column;gap:6px}
  .gbr-aksi .btn,.gbr-aksi button{min-height:0;padding:7px 16px;margin:0;font-size:.82rem;border-radius:10px}
  .gbr-aksi [hidden]{display:none}

  /* Pesan hasil */
  .hasil{display:none;margin-top:20px;padding:18px 20px;border-radius:14px;border:1px solid var(--line);border-left:4px solid var(--line-strong);
    background:var(--sheet);font-size:.95rem;white-space:pre-wrap}
  .hasil:not(.ok):not(.err):not(.warn)::before{content:"";display:inline-block;width:14px;height:14px;margin-right:10px;vertical-align:-2px;
    border:2px solid var(--line-strong);border-top-color:var(--accent);border-radius:50%;animation:putar .8s linear infinite}
  .hasil.ok{background:var(--ok-bg);border-color:var(--ok);white-space:normal}
  .hasil.warn{background:var(--warn-bg);border-color:var(--warn);white-space:normal}
  .hasil.err{background:var(--err-bg);border-color:var(--err)}
  .judul{font:600 1.18rem/1.3 var(--serif);display:flex;align-items:center;gap:10px}
  .ok .judul{color:var(--ok)} .warn .judul{color:var(--warn)}
  .ok .judul::before,.warn .judul::before{flex:none;display:grid;place-items:center;width:24px;height:24px;border-radius:50%;
    color:var(--sheet);font:700 .8rem var(--sans)}
  .ok .judul::before{content:"✓";background:var(--ok)}
  .warn .judul::before{content:"!";background:var(--warn)}
  .chips{display:flex;flex-wrap:wrap;gap:8px;margin:14px 0 0}
  .chip{background:var(--sheet);border:1px solid var(--line-strong);border-radius:999px;padding:3px 14px;font-size:.84rem;font-weight:500}
  .catatan{margin:14px 0 0;padding-left:20px}
  .catatan li{margin:4px 0}
  .hint{margin:14px 0 0;color:var(--muted);font-size:.86rem}
  .hasil .btn{margin-top:16px}
  .log{margin-top:14px;font-size:.85rem}
  .log summary{cursor:pointer;color:var(--muted)}
  .log pre{margin:8px 0 0;white-space:pre-wrap;font:.78rem/1.5 var(--mono);color:var(--muted)}
  @keyframes putar{to{transform:rotate(360deg)}}
  @keyframes naik{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}
  .sheet{animation:naik .6s cubic-bezier(.2,.7,.2,1) both}
  .hasil.ok,.hasil.warn,.hasil.err{animation:naik .4s ease both}

  @media (max-width:640px){
    .top{padding:28px 16px 88px}
    .sheet{padding:32px 18px 36px;border-radius:22px;margin-top:16px}
    .sheet::before{left:22px;right:22px}
    .step{padding-left:58px;padding-bottom:40px}
    .step:not(:last-child)::before{left:18px;top:48px}
    .marker{width:38px;height:38px;font-size:.98rem}
    .step h2{font-size:1.22rem}
    .panel{padding:18px;border-radius:16px}
    .drop{flex-direction:column;text-align:center;padding:24px 16px}
    .gbr{grid-template-columns:1fr auto}
    .gbr-aksi{grid-column:1/-1;flex-direction:row}
  }
  @media (prefers-reduced-motion:reduce){*,*::after{transition:none!important;animation:none!important}}

  /* ==========================================================================
     PREMIUM UI — tampilan real / glossy (override; ID & logika tidak diubah)
     ========================================================================== */
  @keyframes ixFloat{0%,100%{transform:translateY(0)}50%{transform:translateY(-6px)}}
  @keyframes ixPulse{0%{box-shadow:0 0 0 0 rgba(255,255,255,.7)}70%{box-shadow:0 0 0 7px rgba(255,255,255,0)}100%{box-shadow:0 0 0 0 rgba(255,255,255,0)}}
  body{background:
      radial-gradient(900px 440px at 14% -6%,rgba(66,84,200,.17),transparent 62%),
      radial-gradient(800px 420px at 100% 2%,rgba(185,143,74,.15),transparent 58%),
      radial-gradient(700px 500px at 50% 112%,rgba(20,184,166,.10),transparent 60%),var(--bg);
    background-attachment:fixed}
  html[data-theme="dark"] body{background:
      radial-gradient(900px 440px at 14% -6%,rgba(98,116,240,.20),transparent 62%),
      radial-gradient(800px 420px at 100% 2%,rgba(216,177,108,.10),transparent 58%),var(--bg);background-attachment:fixed}

  /* ----- Sidebar ----- */
  .side{background:linear-gradient(180deg,#ffffff 0%,#f6f8fc 100%);box-shadow:1px 0 0 #fff inset,10px 0 30px -22px rgba(15,23,42,.45)}
  html[data-theme="dark"] .side{background:linear-gradient(180deg,#1d273d 0%,#141b2d 100%)}
  .side-brand{background:linear-gradient(180deg,rgba(32,201,151,.10),transparent)}
  .side .grp{font-size:10.5px;font-weight:800;letter-spacing:1.6px;text-transform:uppercase;color:#94a3b8;padding:14px 14px 6px}
  .side svg.ik{width:22px;height:22px;flex:none;filter:drop-shadow(0 1px 1px rgba(0,0,0,.28));transition:transform .2s}
  .side .sub svg.ik{width:19px;height:19px}
  .side a:hover svg.ik,.side summary:hover svg.ik{transform:scale(1.12)}
  .side .sub{margin-left:18px;padding-left:4px;border-left:1.5px solid rgba(148,163,184,.32)}
  .side a:hover,.side summary:hover{background:linear-gradient(135deg,#34d6a6,#20c997 55%,#12a67f);box-shadow:0 8px 18px -8px rgba(32,201,151,.85),inset 0 1px 0 rgba(255,255,255,.35)}
  .side a.aktif{background:linear-gradient(135deg,#34d6a6,#20c997 55%,#0e9b78);box-shadow:0 10px 20px -8px rgba(32,201,151,.9),inset 0 1px 0 rgba(255,255,255,.4)}
  html[data-theme="dark"] .side a.aktif{background:linear-gradient(135deg,rgba(45,212,191,.28),rgba(45,212,191,.10));box-shadow:inset 0 0 0 1px rgba(45,212,191,.4)}
  .side .baru{background:linear-gradient(135deg,#fb7185,#e11d48);box-shadow:0 4px 10px -2px rgba(225,29,72,.6),inset 0 1px 0 rgba(255,255,255,.4)}
  .side a.keluar{background:linear-gradient(135deg,#f87171,#dc2626 55%,#991b1b);box-shadow:0 10px 18px -8px rgba(220,38,38,.75),inset 0 1px 0 rgba(255,255,255,.35)}
  .side a.keluar:hover{background:linear-gradient(135deg,#fca5a5,#ef4444 55%,#b91c1c)}
  .side .lan .ix-mini{width:16px;height:16px;vertical-align:-3px;margin-right:6px}
  body.side-tutup .side:not(:hover) svg.ik{width:26px;height:26px}
  body.side-tutup .side:not(:hover) .sub svg.ik{width:26px;height:26px}

  /* ----- Header atas ----- */
  .nav-atas{background:rgba(255,255,255,.80);-webkit-backdrop-filter:saturate(1.6) blur(14px);backdrop-filter:saturate(1.6) blur(14px);
    box-shadow:inset 0 -1px 0 #e2e8f0,0 12px 28px -20px rgba(15,23,42,.5)}
  html[data-theme="dark"] .nav-atas{background:rgba(27,36,56,.80)}
  .ix-ttl{width:26px;height:26px;flex:none;filter:drop-shadow(0 2px 3px rgba(67,56,202,.45))}
  .jdl{display:flex;align-items:center;gap:10px;font-size:15px;letter-spacing:.1px}
  .kotak{background:linear-gradient(180deg,#fff,#f4f7fb);box-shadow:0 1px 0 #fff inset,0 4px 10px -6px rgba(15,23,42,.3)}
  html[data-theme="dark"] .kotak{background:linear-gradient(180deg,#1a2338,#141b2d)}
  .kotak .ix-mini{width:20px;height:20px;flex:none;filter:drop-shadow(0 1px 1px rgba(0,0,0,.25))}
  .tema{box-shadow:inset 0 2px 4px rgba(15,23,42,.14)}
  .tema button{width:34px;height:30px}
  .tema button .ix-mini{width:18px;height:18px;opacity:.55;filter:grayscale(.5) drop-shadow(0 1px 1px rgba(0,0,0,.2));transition:all .2s}
  .tema button.on{background:linear-gradient(180deg,#fff,#eef2f8)}
  html[data-theme="dark"] .tema button.on{background:linear-gradient(180deg,#334155,#1e293b)}
  .tema button.on .ix-mini{opacity:1;filter:drop-shadow(0 1px 2px rgba(0,0,0,.3))}

  /* ----- Hero ----- */
  .hero{position:relative;overflow:hidden;isolation:isolate;width:min(820px,calc(100% - 24px));margin:26px auto 0;border-radius:26px;padding:26px 30px 24px;
    display:grid;grid-template-columns:auto 1fr;gap:6px 22px;align-items:center;color:#fff;
    background:radial-gradient(60% 130% at 94% -25%,rgba(98,116,240,.6),transparent 62%),radial-gradient(50% 100% at 0% 125%,rgba(185,143,74,.38),transparent 60%),linear-gradient(150deg,#0a1038 0%,#16216a 55%,#26379b 100%);
    border:1px solid rgba(255,255,255,.16);box-shadow:0 34px 64px -30px rgba(14,20,48,.75),inset 0 1.5px 0 rgba(255,255,255,.22);animation:naik .6s cubic-bezier(.2,.7,.2,1) both}
  .hero::before{content:"";position:absolute;inset:0;z-index:-1;opacity:.55;pointer-events:none;
    background-image:linear-gradient(rgba(255,255,255,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.07) 1px,transparent 1px);background-size:28px 28px;
    -webkit-mask:radial-gradient(ellipse at 70% 0%,#000,transparent 70%);mask:radial-gradient(ellipse at 70% 0%,#000,transparent 70%)}
  .hero::after{content:"";position:absolute;left:0;right:0;bottom:0;height:3px;background:linear-gradient(90deg,transparent,var(--gold) 20%,var(--gold-2) 50%,var(--gold) 80%,transparent)}
  .hero-orb{width:92px;height:92px;border-radius:50%;display:grid;place-items:center;animation:ixFloat 4.2s ease-in-out infinite;
    background:radial-gradient(circle at 30% 24%,rgba(255,255,255,.55),rgba(255,255,255,.12) 64%);border:1px solid rgba(255,255,255,.5);
    box-shadow:inset 0 2px 0 rgba(255,255,255,.6),inset 0 -10px 16px rgba(30,40,140,.4),0 16px 30px rgba(5,10,40,.55),0 0 0 9px rgba(255,255,255,.06)}
  .hero-orb svg{width:58px;height:58px;filter:drop-shadow(0 6px 7px rgba(0,0,0,.4))}
  .hero-eyebrow{display:inline-flex;align-items:center;gap:8px;font:700 .7rem/1 var(--sans);letter-spacing:.22em;color:var(--gold-2);margin-bottom:8px}
  .hero-dot{width:8px;height:8px;border-radius:50%;background:var(--gold-2);animation:ixPulse 1.8s infinite}
  .hero h1{margin:0 0 6px;font-size:clamp(1.45rem,3.6vw,1.9rem)}
  .hero p{margin:0;color:#c9d2f7;font-size:.92rem;max-width:52ch}
  .hero-steps{grid-column:1/-1;list-style:none;display:flex;flex-wrap:wrap;gap:10px;margin:14px 0 0;padding:0}
  .hero-steps li{display:flex;align-items:center;gap:10px;padding:7px 16px 7px 8px;border-radius:999px;font-size:.84rem;font-weight:600;
    background:linear-gradient(150deg,rgba(255,255,255,.20),rgba(255,255,255,.05));border:1px solid rgba(255,255,255,.24);box-shadow:inset 0 1px 0 rgba(255,255,255,.3)}
  .hero-steps b{display:grid;place-items:center;width:24px;height:24px;border-radius:50%;background:linear-gradient(145deg,#f6e3b4,#b98f4a);color:#1c1405;font:700 .8rem var(--serif);box-shadow:0 2px 6px rgba(0,0,0,.35)}
  .sheet{margin-top:18px}

  /* ----- Lembar & langkah ----- */
  .sheet{border-radius:26px;box-shadow:inset 0 1.5px 0 rgba(255,255,255,.7),var(--shadow)}
  html[data-theme="dark"] .sheet{box-shadow:inset 0 1px 0 rgba(255,255,255,.05),var(--shadow)}
  .step:not(:last-child)::before{width:3px;background:repeating-linear-gradient(to bottom,var(--line-strong) 0 6px,transparent 6px 11px)}
  .step.selesai:not(:last-child)::before{background:linear-gradient(to bottom,#10b981,var(--accent))}
  .marker{width:34px;height:34px;box-shadow:inset 0 1px 0 rgba(255,255,255,.8),0 3px 8px -2px rgba(14,20,48,.25)}
  .step.aktif .marker{box-shadow:0 0 0 5px var(--accent-soft),0 10px 18px -6px rgba(43,57,144,.75),inset 0 1.5px 0 rgba(255,255,255,.4)}
  .step.selesai .marker{background:linear-gradient(145deg,#34d399,#047857);border-color:transparent;box-shadow:0 0 0 5px var(--ok-bg),0 10px 18px -6px rgba(4,120,87,.7),inset 0 1.5px 0 rgba(255,255,255,.45)}
  .step.selesai .marker::after{content:"";width:18px;height:18px;font-size:0;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='3.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M5 12.5l4.5 4.5L19 7'/%3E%3C/svg%3E") center/contain no-repeat;filter:drop-shadow(0 1px 1px rgba(0,0,0,.35))}
  .panel{border-radius:20px;box-shadow:inset 0 1px 0 rgba(255,255,255,.7),0 14px 28px -22px rgba(14,20,48,.45)}
  html[data-theme="dark"] .panel{box-shadow:inset 0 1px 0 rgba(255,255,255,.05),0 14px 28px -22px rgba(0,0,0,.8)}

  /* ----- Zona unggah ----- */
  .drop{padding:20px;border-width:2px;border-radius:18px;background:linear-gradient(180deg,var(--sheet),var(--sunken))}
  .drop .ikon{width:64px;height:64px;border-radius:20px;background:radial-gradient(circle at 30% 18%,#fff,var(--accent-soft) 75%);
    box-shadow:inset 0 1.5px 0 rgba(255,255,255,.9),0 12px 22px -12px rgba(43,57,144,.6);border-color:var(--line)}
  html[data-theme="dark"] .drop .ikon{background:radial-gradient(circle at 30% 18%,#2a3566,var(--accent-soft) 75%);box-shadow:inset 0 1px 0 rgba(255,255,255,.12),0 12px 22px -12px rgba(0,0,0,.8)}
  .drop svg.ix-up{width:44px;height:44px;filter:drop-shadow(0 4px 5px rgba(30,58,138,.35))}
  .drop:hover .ikon,.drop.over .ikon{animation:ixFloat 1.6s ease-in-out infinite}
  .daftar-berkas li{background:linear-gradient(180deg,var(--sheet),var(--sunken));box-shadow:0 6px 14px -12px rgba(14,20,48,.5)}
  .daftar-berkas li::before{content:"";flex:none;width:24px;height:24px;background:url("data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22%3E%3Cdefs%3E%3ClinearGradient id=%22sb-sky%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23e0f2fe%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%2338bdf8%22/%3E%3Cstop offset=%221%22 stop-color=%22%23075985%22/%3E%3C/linearGradient%3E%3ClinearGradient id=%22sb-green%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23bbf7d0%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%2322c55e%22/%3E%3Cstop offset=%221%22 stop-color=%22%2314532d%22/%3E%3C/linearGradient%3E%3ClinearGradient id=%22sb-white%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23ffffff%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%23f1f5f9%22/%3E%3Cstop offset=%221%22 stop-color=%22%23cbd5e1%22/%3E%3C/linearGradient%3E%3C/defs%3E%3Cpath d=%22M6 2.5h14.5l7.500 7.500V27a2.500 2.500 0 0 1-2.500 2.500H8.500A2.500 2.500 0 0 1 6 27z%22 fill=%22url(%23sb-white)%22 stroke=%22%23cbd5e1%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M20.500 2.500V8a2 2 0 0 0 2 2H28z%22 fill=%22url(%23sb-sky)%22 stroke=%22%23075985%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M10 14.500h12M10 18.500h12M10 22.500h7%22 fill=%22none%22 stroke=%22%2394a3b8%22 stroke-opacity=%221%22 stroke-width=%221.6%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M10 14.500h7%22 fill=%22none%22 stroke=%22%233b82f6%22 stroke-opacity=%221%22 stroke-width=%221.6%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3Ccircle cx=%2222.5%22 cy=%2223.5%22 r=%223.8%22 fill=%22url(%23sb-green)%22 stroke=%22%2314532d%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22/%3E%3Cpath d=%22M20.700 23.600l1.300 1.300 2.400-2.800%22 fill=%22none%22 stroke=%22%23ffffff%22 stroke-opacity=%221%22 stroke-width=%221.4%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E") center/contain no-repeat;filter:drop-shadow(0 2px 2px rgba(0,0,0,.25))}

  /* ----- Tombol berikon (pseudo-element, aman dari textContent) ----- */
  #btnEks::before,#btnSalin::before,#btnJson::before,#btnSalinUlang::before,#lnkUnduh::before,.aksi a.btn2[href*="gemini"]::before{
    content:"";flex:none;width:20px;height:20px;background-repeat:no-repeat;background-position:center;background-size:contain;filter:drop-shadow(0 1px 2px rgba(0,0,0,.35))}
  #btnEks::before{background-image:url("data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22%3E%3Cdefs%3E%3ClinearGradient id=%22sb-gold%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23fef08a%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%23f59e0b%22/%3E%3Cstop offset=%221%22 stop-color=%22%23b45309%22/%3E%3C/linearGradient%3E%3ClinearGradient id=%22sb-sw%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23ffffff%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%23fff1c2%22/%3E%3Cstop offset=%221%22 stop-color=%22%23fbbf24%22/%3E%3C/linearGradient%3E%3C/defs%3E%3Cpath d=%22M16 1.5c1.1 8 6.5 13.4 14.5 14.5-8 1.1-13.4 6.5-14.5 14.5-1.1-8-6.5-13.4-14.5-14.5C9.5 14.9 14.9 9.5 16 1.5z%22 fill=%22url(%23sb-sw)%22 stroke=%22%23fbbf24%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M26.2 2.6l1.02 2.38 2.38 1.02-2.38 1.02-1.02 2.38-1.02-2.38-2.38-1.02 2.38-1.02z%22 fill=%22url(%23sb-gold)%22 stroke=%22%23b45309%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M6 23.4l0.78 1.82 1.82 0.78-1.82 0.78-0.78 1.82-0.78-1.82-1.82-0.78 1.82-0.78z%22 fill=%22url(%23sb-sw)%22 stroke=%22%23fbbf24%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E")}
  #btnSalin::before,#btnSalinUlang::before{background-image:url("data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22%3E%3Cdefs%3E%3ClinearGradient id=%22sb-slate%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23e2e8f0%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%2394a3b8%22/%3E%3Cstop offset=%221%22 stop-color=%22%23334155%22/%3E%3C/linearGradient%3E%3ClinearGradient id=%22sb-white%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23ffffff%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%23f1f5f9%22/%3E%3Cstop offset=%221%22 stop-color=%22%23cbd5e1%22/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect x=%223%22 y=%222.5%22 width=%2216.5%22 height=%2221%22 rx=%222.4%22 fill=%22url(%23sb-slate)%22 stroke=%22%23334155%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22/%3E%3Crect x=%2211%22 y=%228.5%22 width=%2218%22 height=%2221%22 rx=%222.4%22 fill=%22url(%23sb-white)%22 stroke=%22%23cbd5e1%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22/%3E%3Cpath d=%22M15 14.5h10M15 18.5h10M15 22.5h6%22 fill=%22none%22 stroke=%22%2364748b%22 stroke-opacity=%221%22 stroke-width=%221.6%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M15 14.5h10%22 fill=%22none%22 stroke=%22%233b82f6%22 stroke-opacity=%221%22 stroke-width=%221.6%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E")}
  #btnJson::before{background-image:url("data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22%3E%3Cdefs%3E%3ClinearGradient id=%22sb-sky%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23e0f2fe%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%2338bdf8%22/%3E%3Cstop offset=%221%22 stop-color=%22%23075985%22/%3E%3C/linearGradient%3E%3ClinearGradient id=%22sb-green%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23bbf7d0%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%2322c55e%22/%3E%3Cstop offset=%221%22 stop-color=%22%2314532d%22/%3E%3C/linearGradient%3E%3ClinearGradient id=%22sb-white%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23ffffff%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%23f1f5f9%22/%3E%3Cstop offset=%221%22 stop-color=%22%23cbd5e1%22/%3E%3C/linearGradient%3E%3C/defs%3E%3Cpath d=%22M6 2.5h14.5l7.500 7.500V27a2.500 2.500 0 0 1-2.500 2.500H8.500A2.500 2.500 0 0 1 6 27z%22 fill=%22url(%23sb-white)%22 stroke=%22%23cbd5e1%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M20.500 2.500V8a2 2 0 0 0 2 2H28z%22 fill=%22url(%23sb-sky)%22 stroke=%22%23075985%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M10 14.500h12M10 18.500h12M10 22.500h7%22 fill=%22none%22 stroke=%22%2394a3b8%22 stroke-opacity=%221%22 stroke-width=%221.6%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M10 14.500h7%22 fill=%22none%22 stroke=%22%233b82f6%22 stroke-opacity=%221%22 stroke-width=%221.6%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3Ccircle cx=%2222.5%22 cy=%2223.5%22 r=%223.8%22 fill=%22url(%23sb-green)%22 stroke=%22%2314532d%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22/%3E%3Cpath d=%22M20.700 23.600l1.300 1.300 2.400-2.800%22 fill=%22none%22 stroke=%22%23ffffff%22 stroke-opacity=%221%22 stroke-width=%221.4%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E")}
  #lnkUnduh::before{background-image:url("data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22%3E%3Cdefs%3E%3C/defs%3E%3Cpath d=%22M16 4v15M9.5 13.5l6.5 6.5 6.5-6.5%22 fill=%22none%22 stroke=%22%23ffffff%22 stroke-opacity=%221%22 stroke-width=%223.2%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M5 22.5v3a2.5 2.5 0 0 0 2.5 2.5h17a2.5 2.5 0 0 0 2.5-2.5v-3%22 fill=%22none%22 stroke=%22%23ffffff%22 stroke-opacity=%221%22 stroke-width=%223.2%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E")}
  .aksi a.btn2[href*="gemini"]::before{background-image:url("data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22%3E%3Cdefs%3E%3ClinearGradient id=%22sb-gold%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23fef08a%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%23f59e0b%22/%3E%3Cstop offset=%221%22 stop-color=%22%23b45309%22/%3E%3C/linearGradient%3E%3ClinearGradient id=%22sb-gem%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%2393c5fd%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%238b5cf6%22/%3E%3Cstop offset=%221%22 stop-color=%22%234338ca%22/%3E%3C/linearGradient%3E%3C/defs%3E%3Cpath d=%22M16 1.5c1.1 8 6.5 13.4 14.5 14.5-8 1.1-13.4 6.5-14.5 14.5-1.1-8-6.5-13.4-14.5-14.5C9.5 14.9 14.9 9.5 16 1.5z%22 fill=%22url(%23sb-gem)%22 stroke=%22%234338ca%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M26.2 2.6l1.02 2.38 2.38 1.02-2.38 1.02-1.02 2.38-1.02-2.38-2.38-1.02 2.38-1.02z%22 fill=%22url(%23sb-gold)%22 stroke=%22%23b45309%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3Cpath d=%22M6 23.4l0.78 1.82 1.82 0.78-1.82 0.78-0.78 1.82-0.78-1.82-1.82-0.78 1.82-0.78z%22 fill=%22url(%23sb-gold)%22 stroke=%22%23b45309%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E")}
  button,.btn{min-height:44px;border-radius:12px;font-weight:700}
  .btn2{background:linear-gradient(180deg,var(--sheet),var(--sunken))}

  /* ----- Saklar ----- */
  .saklar .rel{box-shadow:inset 0 2px 4px rgba(0,0,0,.22)}
  .saklar input:checked+.rel{box-shadow:inset 0 1px 2px rgba(0,0,0,.25),0 0 14px -2px var(--accent-2)}

  /* ----- Pesan hasil ----- */
  .hasil{border-radius:16px;box-shadow:0 14px 28px -22px rgba(14,20,48,.5)}
  .hasil.err::before{content:"";display:inline-block;width:22px;height:22px;margin-right:10px;vertical-align:-5px;background:url("data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22%3E%3Cdefs%3E%3ClinearGradient id=%22sb-red%22 x1=%220%22 y1=%220%22 x2=%221%22 y2=%221%22%3E%3Cstop offset=%220%22 stop-color=%22%23fecaca%22/%3E%3Cstop offset=%22.5%22 stop-color=%22%23ef4444%22/%3E%3Cstop offset=%221%22 stop-color=%22%237f1d1d%22/%3E%3C/linearGradient%3E%3C/defs%3E%3Ccircle cx=%2216%22 cy=%2216%22 r=%2213.2%22 fill=%22url(%23sb-red)%22 stroke=%22%237f1d1d%22 stroke-opacity=%22.5%22 stroke-width=%22.8%22/%3E%3Ccircle cx=%2216%22 cy=%2216%22 r=%2213.2%22 fill=%22none%22 stroke=%22%23fff%22 stroke-opacity=%22.7%22 stroke-width=%221%22/%3E%3Cpath d=%22M11 11l10 10M21 11l-10 10%22 fill=%22none%22 stroke=%22%23ffffff%22 stroke-opacity=%221%22 stroke-width=%223.2%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/%3E%3C/svg%3E") center/contain no-repeat;filter:drop-shadow(0 2px 3px rgba(147,54,48,.4))}
  .ok .judul::before,.warn .judul::before{width:28px;height:28px;font-size:.9rem;box-shadow:0 6px 12px -4px rgba(0,0,0,.35),inset 0 1px 0 rgba(255,255,255,.45)}
  .ok .judul::before{background:linear-gradient(145deg,#34d399,#047857);color:#fff}
  .warn .judul::before{background:linear-gradient(145deg,#fbbf24,#b45309);color:#fff}
  .chip{background:linear-gradient(180deg,var(--sheet),var(--sunken));box-shadow:0 4px 8px -6px rgba(14,20,48,.5)}
  textarea{border-radius:16px}
  textarea:focus{box-shadow:0 0 0 4px var(--accent-soft),0 10px 24px -14px rgba(43,57,144,.5)}

  /* ----- Baris gambar ----- */
  .gbr{border-radius:18px;background:linear-gradient(180deg,var(--sheet),var(--sunken))}
  .gbr.aktif{box-shadow:0 0 0 4px var(--accent-soft),0 14px 26px -16px rgba(43,57,144,.6)}
  .gbr-zona{border-radius:14px}

  @media (max-width:640px){
    .hero{grid-template-columns:1fr;text-align:center;padding:24px 18px 22px}
    .hero-orb{margin:0 auto}.hero p{margin:0 auto}.hero-steps{justify-content:center}
  }
</style>
</head>
<body>

<!-- Sprite ikon premium -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="ix-blue" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#bfdbfe"/><stop offset=".5" stop-color="#3b82f6"/><stop offset="1" stop-color="#1e3a8a"/></linearGradient><linearGradient id="ix-sky" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e0f2fe"/><stop offset=".5" stop-color="#38bdf8"/><stop offset="1" stop-color="#075985"/></linearGradient><linearGradient id="ix-teal" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#99f6e4"/><stop offset=".5" stop-color="#14b8a6"/><stop offset="1" stop-color="#115e59"/></linearGradient><linearGradient id="ix-green" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#bbf7d0"/><stop offset=".5" stop-color="#22c55e"/><stop offset="1" stop-color="#14532d"/></linearGradient><linearGradient id="ix-red" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fecaca"/><stop offset=".5" stop-color="#ef4444"/><stop offset="1" stop-color="#7f1d1d"/></linearGradient><linearGradient id="ix-orange" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fed7aa"/><stop offset=".5" stop-color="#f97316"/><stop offset="1" stop-color="#9a3412"/></linearGradient><linearGradient id="ix-gold" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fef08a"/><stop offset=".5" stop-color="#f59e0b"/><stop offset="1" stop-color="#b45309"/></linearGradient><linearGradient id="ix-purple" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e9d5ff"/><stop offset=".5" stop-color="#a855f7"/><stop offset="1" stop-color="#581c87"/></linearGradient><linearGradient id="ix-pink" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fbcfe8"/><stop offset=".5" stop-color="#ec4899"/><stop offset="1" stop-color="#831843"/></linearGradient><linearGradient id="ix-brown" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fcd9b6"/><stop offset=".5" stop-color="#b45309"/><stop offset="1" stop-color="#451a03"/></linearGradient><linearGradient id="ix-slate" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#e2e8f0"/><stop offset=".5" stop-color="#94a3b8"/><stop offset="1" stop-color="#334155"/></linearGradient><linearGradient id="ix-dark" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#64748b"/><stop offset=".5" stop-color="#1e293b"/><stop offset="1" stop-color="#020617"/></linearGradient><linearGradient id="ix-white" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".5" stop-color="#f1f5f9"/><stop offset="1" stop-color="#cbd5e1"/></linearGradient><linearGradient id="ix-silver" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".5" stop-color="#cbd5e1"/><stop offset="1" stop-color="#64748b"/></linearGradient><linearGradient id="ix-skin" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffe8cc"/><stop offset=".5" stop-color="#f4b97a"/><stop offset="1" stop-color="#9a5b25"/></linearGradient><linearGradient id="ix-screen" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#38bdf8"/><stop offset=".5" stop-color="#2563eb"/><stop offset="1" stop-color="#1e1b4b"/></linearGradient><linearGradient id="ix-greenb" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#22c55e"/><stop offset=".5" stop-color="#166534"/><stop offset="1" stop-color="#052e16"/></linearGradient><linearGradient id="ix-hair" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#78350f"/><stop offset=".5" stop-color="#451a03"/><stop offset="1" stop-color="#1c0a00"/></linearGradient><linearGradient id="ix-gem" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#93c5fd"/><stop offset=".5" stop-color="#8b5cf6"/><stop offset="1" stop-color="#4338ca"/></linearGradient><linearGradient id="ix-sw" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ffffff"/><stop offset=".5" stop-color="#fff1c2"/><stop offset="1" stop-color="#fbbf24"/></linearGradient></defs><symbol id="ix-desktop" viewBox="0 0 32 32"><rect x="2.5" y="4" width="27" height="18.5" rx="3" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="4.6" y="6.1" width="22.8" height="14.3" rx="1.7" fill="url(#ix-screen)" stroke="#1e1b4b" stroke-opacity=".5" stroke-width=".8"/><rect x="8" y="14.8" width="3.2" height="3.7" rx="0.7" fill="#fff" fill-opacity="0.92"/><rect x="12.8" y="11.8" width="3.2" height="6.7" rx="0.7" fill="#fff" fill-opacity="0.92"/><rect x="17.6" y="13.2" width="3.2" height="5.3" rx="0.7" fill="#fde68a" fill-opacity="0.95"/><rect x="22.2" y="9.6" width="3.2" height="8.9" rx="0.7" fill="#fff" fill-opacity="0.92"/><path d="M4.6 7.8a1.7 1.7 0 0 1 1.7-1.7H16L8.5 20.4H6.3a1.7 1.7 0 0 1-1.7-1.7z" fill="#fff" fill-opacity="0.16"/><path d="M13 22.5h6l1.2 3.6h-8.4z" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="8.5" y="26" width="15" height="2.8" rx="1.4" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-server" viewBox="0 0 32 32"><rect x="3" y="3" width="26" height="7.8" rx="2.2" fill="url(#ix-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="3.8" width="24" height="2.6" rx="1.3" fill="#fff" fill-opacity="0.28"/><circle cx="7.6" cy="7.2" r="1.3" fill="#86efac" stroke="#064e3b" stroke-opacity=".5" stroke-width=".5"/><path d="M12 7.2h12" fill="none" stroke="#ffffff" stroke-opacity="0.85" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 9.2h7" fill="none" stroke="#ffffff" stroke-opacity="0.5" stroke-width="0.9" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="12" width="26" height="7.8" rx="2.2" fill="url(#ix-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="12.8" width="24" height="2.6" rx="1.3" fill="#fff" fill-opacity="0.28"/><circle cx="7.6" cy="16.2" r="1.3" fill="#fde68a" stroke="#064e3b" stroke-opacity=".5" stroke-width=".5"/><path d="M12 16.2h12" fill="none" stroke="#ffffff" stroke-opacity="0.85" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 18.2h7" fill="none" stroke="#ffffff" stroke-opacity="0.5" stroke-width="0.9" stroke-linecap="round" stroke-linejoin="round"/><rect x="3" y="21" width="26" height="7.8" rx="2.2" fill="url(#ix-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="21.8" width="24" height="2.6" rx="1.3" fill="#fff" fill-opacity="0.28"/><circle cx="7.6" cy="25.2" r="1.3" fill="#86efac" stroke="#064e3b" stroke-opacity=".5" stroke-width=".5"/><path d="M12 25.2h12" fill="none" stroke="#ffffff" stroke-opacity="0.85" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 27.2h7" fill="none" stroke="#ffffff" stroke-opacity="0.5" stroke-width="0.9" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-graduate" viewBox="0 0 32 32"><path d="M4 30.5c0-4.6 4.6-6.8 12-6.8s12 2.2 12 6.8z" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M12.8 22.6L16 27l3.2-4.4z" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="16" cy="18.4" r="5.4" fill="url(#ix-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M8.8 10.5v3.4c0 2 3.2 3.4 7.2 3.4s7.2-1.4 7.2-3.4v-3.4L16 13z" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M2.5 8.8L16 3.2l13.5 5.6L16 14.4z" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M5.5 8.8L16 4.6l3 1.3L9 10.3z" fill="#fff" fill-opacity="0.3"/><path d="M27.2 9.8v7" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="27.2" cy="18" r="1.7" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-bullhorn" viewBox="0 0 32 32"><rect x="2.5" y="11.5" width="6" height="9" rx="1.6" fill="url(#ix-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/><path d="M8.5 11.8L23 4.5v22L8.5 19.8z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M9.5 12.3L22 6v5L9.5 16z" fill="#fff" fill-opacity="0.35"/><path d="M10 20l2.8 8.5h3.6l-1.9-7.6z" fill="url(#ix-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><ellipse cx="23.2" cy="15.5" rx="2.5" ry="11" fill="url(#ix-orange)" stroke="#9a3412" stroke-opacity=".5" stroke-width=".8"/><path d="M28 10c1.8 3.2 1.8 8.8 0 12" fill="none" stroke="#f97316" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M30.2 7.6c3 4.4 3 12.4 0 16.8" fill="none" stroke="#f97316" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-magic" viewBox="0 0 32 32"><g transform="rotate(-45 16 16)"><rect x="2.5" y="13.9" width="22" height="4.2" rx="1.8" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="17.5" y="13.9" width="5" height="4.2" rx="0" fill="url(#ix-purple)" stroke="#581c87" stroke-opacity=".5" stroke-width=".8"/><rect x="2.5" y="13.9" width="4" height="4.2" rx="1.6" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="7" y="14.5" width="10" height="1" rx="0.5" fill="#fff" fill-opacity="0.35"/></g><path d="M23.6 1.9999999999999991l1.86 4.34 4.34 1.86-4.34 1.86-1.86 4.34-1.86-4.34-4.34-1.86 4.34-1.86z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M6.5 4l0.90 2.10 2.10 0.90-2.10 0.90-0.90 2.10-0.90-2.10-2.10-0.90 2.10-0.90z" fill="url(#ix-purple)" stroke="#581c87" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M26.5 19.7l0.84 1.96 1.96 0.84-1.96 0.84-0.84 1.96-0.84-1.96-1.96-0.84 1.96-0.84z" fill="url(#ix-purple)" stroke="#581c87" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M8 21.9l0.63 1.47 1.47 0.63-1.47 0.63-0.63 1.47-0.63-1.47-1.47-0.63 1.47-0.63z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="ix-qr" viewBox="0 0 32 32"><rect x="2.5" y="2.5" width="27" height="27" rx="4" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="5.8" y="5.8" width="7" height="7" rx="1.4" fill="#0f766e" fill-opacity="1"/><rect x="7.3" y="7.3" width="4" height="4" rx="0.6" fill="#ffffff" fill-opacity="1"/><rect x="8.2" y="8.2" width="2.2" height="2.2" rx="0.4" fill="#0f766e" fill-opacity="1"/><rect x="19.2" y="5.8" width="7" height="7" rx="1.4" fill="#0f766e" fill-opacity="1"/><rect x="20.7" y="7.3" width="4" height="4" rx="0.6" fill="#ffffff" fill-opacity="1"/><rect x="21.599999999999998" y="8.2" width="2.2" height="2.2" rx="0.4" fill="#0f766e" fill-opacity="1"/><rect x="5.8" y="19.2" width="7" height="7" rx="1.4" fill="#0f766e" fill-opacity="1"/><rect x="7.3" y="20.7" width="4" height="4" rx="0.6" fill="#ffffff" fill-opacity="1"/><rect x="8.2" y="21.599999999999998" width="2.2" height="2.2" rx="0.4" fill="#0f766e" fill-opacity="1"/><rect x="15" y="5.8" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="15" y="9.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="15" y="15" width="2.8" height="2.8" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="19.5" y="15" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="24" y="15.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="19.4" y="19.4" width="3" height="3" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="24.2" y="20" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="15.4" y="19.6" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="15.2" y="24" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="19.6" y="24.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="24.2" y="24.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="9" y="15" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/><rect x="5.8" y="15.4" width="2.4" height="2.4" rx="0.5" fill="#0f766e" fill-opacity="1"/></symbol><symbol id="ix-gradcap" viewBox="0 0 32 32"><path d="M8 14.8v7c0 2.8 3.6 5 8 5s8-2.2 8-5v-7L16 20z" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M1.8 12L16 5.2 30.2 12 16 18.8z" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M5.4 12L16 7l3.4 1.6L9.6 14z" fill="#fff" fill-opacity="0.3"/><path d="M27.2 13.2v8.2" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="27.2" cy="23.2" r="2.1" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><path d="M25.7 25l-.6 3M28.7 25l.6 3" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-university" viewBox="0 0 32 32"><path d="M16 2.8L2.5 10h27z" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M16 4.2L6.5 9.4h19z" fill="#fff" fill-opacity="0.2"/><circle cx="16" cy="7.3" r="1.6" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="5.4" y="11.5" width="3.8" height="11" rx="0.8" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="10.8" y="11.5" width="3.8" height="11" rx="0.8" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="17.4" y="11.5" width="3.8" height="11" rx="0.8" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="22.8" y="11.5" width="3.8" height="11" rx="0.8" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="3" y="22.8" width="26" height="3" rx="0.8" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="1.5" y="25.8" width="29" height="3.4" rx="1" fill="url(#ix-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-userscog" viewBox="0 0 32 32"><path d="M2.5 27.5c0-5.4 4.2-8.6 9.5-8.6s9.5 3.2 9.5 8.6z" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="12" cy="9.6" r="4.5" fill="url(#ix-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M7.2 9.4a4.8 4.8 0 0 1 9.6 0c-2.25-2.70-5.40-4.05-4.80-4.05s-3.45 .3-4.80 4.05z" fill="url(#ix-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="23.5" cy="22.5" r="5.4" fill="none" stroke="#b45309" stroke-opacity=".5" stroke-width="3.6" stroke-dasharray="3.02 1.22" stroke-dashoffset="-0.61"/><circle cx="23.5" cy="22.5" r="5.4" fill="none" stroke="url(#ix-gold)" stroke-width="2.6" stroke-dasharray="2.12 2.12" stroke-dashoffset="-1.06"/><circle cx="23.5" cy="22.5" r="3.9" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><circle cx="23.5" cy="22.5" r="1.7" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-database" viewBox="0 0 32 32"><path d="M5 7.6v17.4c0 5.1 5.0 6.6 11 6.6s11 -1.6 11 -6.6V7.6z" fill="url(#ix-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M5 14.2c0 5.8 5.0 7.4 11 7.4s11 -1.6 11 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 20.1c0 5.8 5.0 7.4 11 7.4s11 -1.6 11 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><ellipse cx="16" cy="7.6" rx="11" ry="3.9" fill="url(#ix-white)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><ellipse cx="16" cy="7.6" rx="7.7" ry="1.9" fill="url(#ix-sky)" fill-opacity=".35"/></symbol><symbol id="ix-dbbackup" viewBox="0 0 32 32"><path d="M3.8000000000000007 7.4v15.1c0 5.1 4.5 6.6 10 6.6s10 -1.6 10 -6.6V7.4z" fill="url(#ix-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M3.8000000000000007 13.1c0 5.8 4.5 7.4 10 7.4s10 -1.6 10 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.8000000000000007 18.3c0 5.8 4.5 7.4 10 7.4s10 -1.6 10 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><ellipse cx="13.8" cy="7.4" rx="10" ry="3.9" fill="url(#ix-white)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><ellipse cx="13.8" cy="7.4" rx="7.0" ry="1.9" fill="url(#ix-sky)" fill-opacity=".35"/><circle cx="24.3" cy="24.3" r="6.4" fill="url(#ix-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/><circle cx="24.3" cy="24.3" r="6.4" fill="none" stroke="#fff" stroke-width="1.3"/><path d="M24.3 20.8v6.6M21.4 24.9l2.9 3 2.9-3" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-dbclear" viewBox="0 0 32 32"><path d="M3.8000000000000007 7.4v15.1c0 5.1 4.5 6.6 10 6.6s10 -1.6 10 -6.6V7.4z" fill="url(#ix-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M3.8000000000000007 13.1c0 5.8 4.5 7.4 10 7.4s10 -1.6 10 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><path d="M3.8000000000000007 18.3c0 5.8 4.5 7.4 10 7.4s10 -1.6 10 -7.4" fill="none" stroke="#ffffff" stroke-opacity="0.6" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/><ellipse cx="13.8" cy="7.4" rx="10" ry="3.9" fill="url(#ix-white)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><ellipse cx="13.8" cy="7.4" rx="7.0" ry="1.9" fill="url(#ix-sky)" fill-opacity=".35"/><circle cx="24.3" cy="24.3" r="6.4" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="24.3" cy="24.3" r="6.4" fill="none" stroke="#fff" stroke-width="1.3"/><path d="M21.5 21.5l5.6 5.6M27.1 21.5l-5.6 5.6" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-logout" viewBox="0 0 32 32"><rect x="3.5" y="3.5" width="15" height="25" rx="2.6" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><rect x="5.5" y="5.5" width="11" height="21" rx="1.6" fill="#fde68a" fill-opacity="0.4"/><circle cx="14" cy="16" r="1.3" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><path d="M14.5 16h13.5M22.5 10.5l5.5 5.5-5.5 5.5" fill="none" stroke="#7f1d1d" stroke-opacity="0.55" stroke-width="5.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.5 16h13.5M22.5 10.5l5.5 5.5-5.5 5.5" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-network" viewBox="0 0 32 32"><path d="M16 10.5V16M7 21.5V16h18v5.5" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><rect x="10.5" y="3" width="11" height="8" rx="2" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><rect x="2" y="21" width="10" height="8" rx="2" fill="url(#ix-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="20" y="21" width="10" height="8" rx="2" fill="url(#ix-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="12.5" y="5" width="7" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="12.5" y="7.5" width="4" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.5"/><rect x="4" y="23" width="6" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="22" y="23" width="6" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="4" y="25.5" width="3.5" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.5"/><rect x="22" y="25.5" width="3.5" height="1.3" rx="0.6" fill="#fff" fill-opacity="0.5"/></symbol><symbol id="ix-copy" viewBox="0 0 32 32"><rect x="3" y="2.5" width="16.5" height="21" rx="2.4" fill="url(#ix-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/><rect x="11" y="8.5" width="18" height="21" rx="2.4" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M15 14.5h10M15 18.5h10M15 22.5h6" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 14.5h10" fill="none" stroke="#3b82f6" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-print" viewBox="0 0 32 32"><rect x="8.5" y="2.5" width="15" height="10" rx="1.4" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M11.5 6h9M11.5 8.6h6" fill="none" stroke="#94a3b8" stroke-opacity="1" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><rect x="2.5" y="11" width="27" height="13" rx="3.6" fill="url(#ix-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="12" width="24" height="3" rx="1.4" fill="#fff" fill-opacity="0.28"/><rect x="8" y="19" width="16" height="10.5" rx="1.2" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M11 22.5h10M11 25.5h7" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="25" cy="15.4" r="1.1" fill="url(#ix-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-check" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13.2" fill="url(#ix-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="13.2" fill="none" stroke="#fff" stroke-opacity=".7" stroke-width="1"/><path d="M4.8 14.5a11.4 11.4 0 0 1 22.4 0c-6.5-3.2-15.9-3.2-22.4 0z" fill="#fff" fill-opacity="0.28"/><path d="M9.5 16.6l4.4 4.5 8.6-9.6" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-calcheck" viewBox="0 0 32 32"><rect x="3.5" y="5.5" width="25" height="23" rx="3.6" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M3.5 13.5V9.1a3.6 3.6 0 0 1 3.6-3.6h17.8a3.6 3.6 0 0 1 3.6 3.6v4.4z" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="9" y="2.5" width="3" height="6.5" rx="1.5" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="20" y="2.5" width="3" height="6.5" rx="1.5" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><path d="M10.2 21.3l4.2 4.2 7.6-8.2" fill="none" stroke="#16a34a" stroke-opacity="1" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-book" viewBox="0 0 32 32"><rect x="4.5" y="3" width="5" height="26" rx="1.2" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="8" y="3" width="19.5" height="26" rx="2.4" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><rect x="8.8" y="4" width="3" height="24" rx="1" fill="#fff" fill-opacity="0.18"/><rect x="12.5" y="8" width="11.5" height="7" rx="1" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M14.5 10.5h7.5M14.5 12.8h5" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><rect x="9" y="27" width="19" height="2.6" rx="1" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M22 3v8l-2.2-1.8L17.6 11V3z" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="ix-flask" viewBox="0 0 32 32"><rect x="11.5" y="2.5" width="9" height="3.2" rx="1.2" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><path d="M12.5 5.7v7.8L5 25.4c-1.2 2.2.1 4.1 2.6 4.1h16.8c2.5 0 3.8-1.9 2.6-4.1L19.5 13.5V5.7z" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M8.4 21l-1.7 3.2c-.8 1.4.1 2.4 1.7 2.4h15.2c1.6 0 2.5-1 1.7-2.4L23.6 21z" fill="url(#ix-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="12" cy="24" r="1" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><circle cx="17.5" cy="22.8" r="1.2" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><circle cx="21" cy="25" r="0.8" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M13.8 7v6.6L8.6 22.4" fill="#fff" fill-opacity="0.0"/><path d="M14.4 7.5v5.8" fill="none" stroke="#ffffff" stroke-opacity="0.8" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-users" viewBox="0 0 32 32"><path d="M13.5 25c0-4.6 3.6-7 8-7s8 2.4 8 7z" fill="url(#ix-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="21.5" cy="9.5" r="3.8" fill="url(#ix-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M17.4 9.3a4.1 4.1 0 0 1 8.2 0c-1.90-2.28-4.56-3.42-4.10-3.42s-2.96 .3-4.10 3.42z" fill="url(#ix-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M1.8 29.5c0-5.6 4.2-8.8 9.7-8.8s9.7 3.2 9.7 8.8z" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="11.5" cy="11" r="4.6" fill="url(#ix-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M6.6000000000000005 10.8a4.8999999999999995 4.8999999999999995 0 0 1 9.799999999999999 0c-2.30-2.76-5.52-4.14-4.90-4.14s-3.52 .3-4.90 4.14z" fill="url(#ix-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="ix-school" viewBox="0 0 32 32"><path d="M1.8 13.6L16 4l14.2 9.6z" fill="url(#ix-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="4" y="13.6" width="24" height="15.2" rx="0.8" fill="url(#ix-orange)" stroke="#9a3412" stroke-opacity=".5" stroke-width=".8"/><rect x="13.4" y="19.2" width="5.2" height="9.6" rx="1" fill="url(#ix-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="6.6" y="16.4" width="4.2" height="4.2" rx="0.6" fill="url(#ix-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><rect x="21.2" y="16.4" width="4.2" height="4.2" rx="0.6" fill="url(#ix-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="9.8" r="2" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M16 4V1.6" fill="none" stroke="#b45309" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 1.6l4 1.2-4 1.2z" fill="#ef4444" fill-opacity="1"/></symbol><symbol id="ix-chess" viewBox="0 0 32 32"><ellipse cx="16" cy="28" rx="10" ry="2.2" fill="#020617" fill-opacity=".25"/><circle cx="16" cy="8.4" r="4.6" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="11" y="12.6" width="10" height="2.8" rx="1.4" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><path d="M13 15.4c0 3-1.4 5-3.4 8h12.8c-2-3-3.4-5-3.4-8z" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="7.5" y="23.2" width="17" height="3" rx="1.5" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="6" y="26" width="20" height="3.4" rx="1.7" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><path d="M13 6a3.4 3.4 0 0 1 4-2.4 3.4 3.4 0 0 0-3.4 3.6z" fill="#fff" fill-opacity="0.5"/><rect x="12.4" y="23.9" width="3" height="0.9" rx="0.4" fill="#fff" fill-opacity="0.35"/></symbol><symbol id="ix-teacher" viewBox="0 0 32 32"><rect x="2" y="2.5" width="28" height="17.5" rx="1.8" fill="url(#ix-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="4" y="4.5" width="24" height="13.2" rx="0.8" fill="url(#ix-greenb)" stroke="#052e16" stroke-opacity=".5" stroke-width=".8"/><path d="M7 8.2h9M7 11.4h14M7 14.4h6" fill="none" stroke="#ffffff" stroke-opacity="0.85" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><rect x="4" y="20" width="24" height="1.8" rx="0.8" fill="url(#ix-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><path d="M2.2 31.6c0-3.6 2.6-5 5.8-5s5.8 1.4 5.8 5z" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="8" cy="24.4" r="2.9" fill="url(#ix-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M4.8 24.2a3.1999999999999997 3.1999999999999997 0 0 1 6.3999999999999995 0c-1.45-1.74-3.48-2.61-3.20-2.61s-2.33 .3-3.20 2.61z" fill="url(#ix-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M13 28.6l10.5-6.4" fill="none" stroke="#92400e" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="23.8" cy="22" r="0.9" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-diagram" viewBox="0 0 32 32"><path d="M16 10.5v5M7.5 21.5v-6h17v6" fill="none" stroke="#64748b" stroke-opacity="1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><rect x="10.5" y="3" width="11" height="7.5" rx="1.9" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><rect x="2.5" y="21.5" width="10" height="7.5" rx="1.9" fill="url(#ix-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="19.5" y="21.5" width="10" height="7.5" rx="1.9" fill="url(#ix-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8"/><rect x="12.5" y="5.2" width="7" height="1.2" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="4.5" y="23.7" width="6" height="1.2" rx="0.6" fill="#fff" fill-opacity="0.7"/><rect x="21.5" y="23.7" width="6" height="1.2" rx="0.6" fill="#fff" fill-opacity="0.7"/></symbol><symbol id="ix-clock" viewBox="0 0 32 32"><circle cx="16" cy="16" r="13.2" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="10.56" fill="url(#ix-white)" stroke="#334155" stroke-opacity=".35" stroke-width=".7"/><path d="M16.00 6.24L16.00 7.54" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M20.88 7.55L20.58 8.07" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M24.45 11.12L23.93 11.42" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M25.76 16.00L24.46 16.00" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M24.45 20.88L23.93 20.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M20.88 24.45L20.58 23.93" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M16.00 25.76L16.00 24.46" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M11.12 24.45L11.42 23.93" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M7.55 20.88L8.07 20.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M6.24 16.00L7.54 16.00" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M7.55 11.12L8.07 11.42" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M11.12 7.55L11.42 8.07" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M16 16V9.40" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 16l4.75 2.64" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="16" cy="16" r="1.3" fill="#ef4444"/></symbol><symbol id="ix-door" viewBox="0 0 32 32"><rect x="4.5" y="2.5" width="21.5" height="27" rx="1.5" fill="url(#ix-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="6.5" y="4.5" width="17.5" height="25" rx="0.8" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><path d="M6.5 4.5L18.6 7.4V29.5H6.5z" fill="url(#ix-orange)" stroke="#9a3412" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="8.5" y="10" width="7.6" height="6.4" rx="0.8" fill="#ffffff" fill-opacity="0.22"/><rect x="8.5" y="19" width="7.6" height="7.2" rx="0.8" fill="#ffffff" fill-opacity="0.18"/><path d="M18.6 7.4L24 4.5v25h-5.4z" fill="#fff" fill-opacity="0.12"/><circle cx="16.2" cy="18.2" r="1.1" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-userclock" viewBox="0 0 32 32"><path d="M2 27c0-5 4-8 9-8s9 3 9 8z" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="11" cy="9.2" r="4.3" fill="url(#ix-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M6.4 9.0a4.6 4.6 0 0 1 9.2 0c-2.15-2.58-5.16-3.87-4.60-3.87s-3.31 .3-4.60 3.87z" fill="url(#ix-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="23.3" cy="22.8" r="7.2" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><circle cx="23.3" cy="22.8" r="5.76" fill="url(#ix-white)" stroke="#334155" stroke-opacity=".35" stroke-width=".7"/><path d="M23.30 17.84L23.30 19.14" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M25.78 18.50L25.48 19.02" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M27.60 20.32L27.08 20.62" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M28.26 22.80L26.96 22.80" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M27.60 25.28L27.08 24.98" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M25.78 27.10L25.48 26.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M23.30 27.76L23.30 26.46" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M20.82 27.10L21.12 26.58" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M19.00 25.28L19.52 24.98" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M18.34 22.80L19.64 22.80" stroke="#334155" stroke-width="1.1" stroke-linecap="round"/><path d="M19.00 20.32L19.52 20.62" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M20.82 18.50L21.12 19.02" stroke="#334155" stroke-width="0.7" stroke-linecap="round"/><path d="M23.3 22.8V19.20" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M23.3 22.8l2.59 1.44" fill="none" stroke="#1e293b" stroke-opacity="1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="23.3" cy="22.8" r="1.3" fill="#ef4444"/></symbol><symbol id="ix-idcard" viewBox="0 0 32 32"><rect x="12.5" y="2.5" width="7" height="3.6" rx="1.5" fill="url(#ix-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/><rect x="2" y="5.5" width="28" height="21.5" rx="3.2" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M2 12V8.7a3.2 3.2 0 0 1 3.2-3.2h21.6A3.2 3.2 0 0 1 30 8.7V12z" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M4 25.6c0-3.4 2.4-4.8 5.8-4.8s5.8 1.4 5.8 4.8z" fill="url(#ix-teal)" stroke="#115e59" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="9.8" cy="17.2" r="3.2" fill="url(#ix-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M6.300000000000001 17.0a3.5 3.5 0 0 1 7.0 0c-1.60-1.92-3.84-2.88-3.50-2.88s-2.54 .3-3.50 2.88z" fill="url(#ix-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M18.5 16.5h8.5M18.5 20h8.5M18.5 23.5h5" fill="none" stroke="#94a3b8" stroke-opacity="1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-folder" viewBox="0 0 32 32"><path d="M2.8 7.8a2.4 2.4 0 0 1 2.4-2.4h7.2l3 3.4h10.4a2.4 2.4 0 0 1 2.4 2.4V25H2.8z" fill="url(#ix-orange)" stroke="#9a3412" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="6" y="9.6" width="19.5" height="11" rx="1" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M9 13h12M9 16.4h8" fill="none" stroke="#94a3b8" stroke-opacity="1" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M1.5 27.4l4.1-13a2.4 2.4 0 0 1 2.3-1.7H30a1.6 1.6 0 0 1 1.5 2.1l-3.7 12a2.4 2.4 0 0 1-2.3 1.7H3.2a1.6 1.6 0 0 1-1.7-1.1z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M7.2 14.2h22l-.5 1.6H6.7z" fill="#fff" fill-opacity="0.4"/></symbol><symbol id="ix-calendar" viewBox="0 0 32 32"><rect x="3.5" y="5.5" width="25" height="23" rx="3.6" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M3.5 13.5V9.1a3.6 3.6 0 0 1 3.6-3.6h17.8a3.6 3.6 0 0 1 3.6 3.6v4.4z" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="9" y="2.5" width="3" height="6.5" rx="1.5" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="20" y="2.5" width="3" height="6.5" rx="1.5" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><rect x="7" y="16.4" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="7" y="21.6" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="12.4" y="16.4" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="12.4" y="21.6" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="17.8" y="16.4" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="17.8" y="21.6" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/><rect x="23.2" y="16.4" width="3.6" height="3.2" rx="0.8" fill="#ef4444" fill-opacity="1"/><rect x="23.2" y="21.6" width="3.6" height="3.2" rx="0.8" fill="#94a3b8" fill-opacity="1"/></symbol><symbol id="ix-hourglass" viewBox="0 0 32 32"><rect x="5.5" y="2.5" width="21" height="3.2" rx="1.4" fill="url(#ix-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="5.5" y="26.3" width="21" height="3.2" rx="1.4" fill="url(#ix-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><path d="M8.5 5.7h15c0 5-5 7-7.5 10.3 2.5 3.300 7.5 5.300 7.500 10.300h-15c0-5 5-7 7.500-10.300C13.500 12.700 8.500 10.700 8.500 5.700z" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M11 7.2h10c-.4 3-2.8 4.4-5 5.6-2.2-1.2-4.6-2.6-5-5.6z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M9.8 25.8c.8-3.6 3.6-5.2 6.200-5.200s5.400 1.600 6.200 5.200z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M16 13v7.500" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-briefcase" viewBox="0 0 32 32"><path d="M11.5 10V7.5a2 2 0 0 1 2-2h5a2 2 0 0 1 2 2V10" fill="none" stroke="#451a03" stroke-opacity="1" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/><rect x="2.5" y="9.5" width="27" height="19" rx="3.2" fill="url(#ix-brown)" stroke="#451a03" stroke-opacity=".5" stroke-width=".8"/><rect x="3.5" y="10.5" width="25" height="3.4" rx="1.6" fill="#fff" fill-opacity="0.22"/><rect x="2.5" y="17.2" width="27" height="2.6" rx="0" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="13.2" y="15.4" width="5.6" height="6" rx="1.2" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="18.4" r="0.9" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-key" viewBox="0 0 32 32"><path d="M14.5 14.5L28 28" fill="none" stroke="#78350f" stroke-opacity="0.5" stroke-width="5.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M22.5 22.5l3.2-3.2M26 26l3-3" fill="none" stroke="#78350f" stroke-opacity="0.5" stroke-width="4.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.5 14.5L28 28" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M22.5 22.5l3.2-3.2M26 26l3-3" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.5 15l11 11" fill="none" stroke="#fde68a" stroke-opacity="0.7" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.5 3a7.4 7.4 0 1 1 0 14.8 7.4 7.4 0 0 1 0-14.8zm0 4.4a3 3 0 1 0 0 6 3 3 0 0 0 0-6z" fill="url(#ix-gold)" fill-rule="evenodd" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="ix-filealt" viewBox="0 0 32 32"><path d="M6 2.5h14.5l7.500 7.500V27a2.500 2.500 0 0 1-2.500 2.500H8.500A2.500 2.500 0 0 1 6 27z" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M20.500 2.500V8a2 2 0 0 0 2 2H28z" fill="url(#ix-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M10 14.500h12M10 18.500h12M10 22.500h7" fill="none" stroke="#94a3b8" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 14.500h7" fill="none" stroke="#3b82f6" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="22.5" cy="23.5" r="3.8" fill="url(#ix-green)" stroke="#14532d" stroke-opacity=".5" stroke-width=".8"/><path d="M20.700 23.600l1.300 1.300 2.400-2.800" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></symbol><symbol id="ix-chart" viewBox="0 0 32 32"><rect x="2.5" y="3.5" width="27" height="25" rx="3.4" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8"/><path d="M6.500 24.500h19" fill="none" stroke="#cbd5e1" stroke-opacity="1" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"/><rect x="6" y="19" width="3.8" height="5.5" rx="0.8" fill="url(#ix-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><rect x="11.5" y="15" width="3.8" height="9.5" rx="0.8" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><rect x="17" y="17.5" width="3.8" height="7" rx="0.8" fill="url(#ix-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8"/><rect x="22.5" y="12" width="3.8" height="12.5" rx="0.8" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><path d="M7.900 15.500l5.500-4.500 5.500 3 5.200-6.500" fill="none" stroke="#ef4444" stroke-opacity="1" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/><circle cx="7.9" cy="15.5" r="1.4" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="13.4" cy="11" r="1.4" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="18.9" cy="14" r="1.4" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/><circle cx="24.1" cy="7.5" r="1.4" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-trophy" viewBox="0 0 32 32"><path d="M9 7H4.500v2.500c0 3 2 5 4.800 5.500M23 7h4.500v2.500c0 3-2 5-4.800 5.500" fill="none" stroke="#d97706" stroke-opacity="1" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.500 3.500h15v8.500c0 4.800-3.400 7.800-7.500 7.800S8.500 16.800 8.500 12z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M10.500 5h4v9c-2.500-.8-4-2.500-4-5z" fill="#fff" fill-opacity="0.35"/><path d="M16 7l1.300 2.700 3 .4-2.200 2 .6 3-2.700-1.500-2.700 1.500.6-3-2.200-2 3-.4z" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="14" y="19.5" width="4" height="4.5" rx="0.6" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><rect x="9.5" y="24" width="13" height="5" rx="1.6" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="11.5" y="25.3" width="9" height="1.8" rx="0.6" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-laptop" viewBox="0 0 32 32"><rect x="5" y="4.5" width="22" height="16.5" rx="2.2" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><rect x="7" y="6.5" width="18" height="12.5" rx="1" fill="url(#ix-screen)" stroke="#1e1b4b" stroke-opacity=".5" stroke-width=".8"/><path d="M7 7.500a1 1 0 0 1 1-1h8.500L9 19H8a1 1 0 0 1-1-1z" fill="#fff" fill-opacity="0.16"/><path d="M10 12.500l3 2-3 2M15 17h5" fill="none" stroke="#ffffff" stroke-opacity="0.9" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M1.800 22.500h28.400l-1.800 3.600a2 2 0 0 1-1.800 1.100H5.400a2 2 0 0 1-1.800-1.100z" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><rect x="13" y="22.5" width="6" height="1.3" rx="0.6" fill="url(#ix-slate)" stroke="#334155" stroke-opacity=".5" stroke-width=".8"/></symbol><symbol id="ix-cog" viewBox="0 0 32 32"><circle cx="16" cy="16" r="11.3" fill="none" stroke="#64748b" stroke-opacity=".5" stroke-width="5.4" stroke-dasharray="4.45 2.65" stroke-dashoffset="-1.32"/><circle cx="16" cy="16" r="11.3" fill="none" stroke="url(#ix-silver)" stroke-width="4.4" stroke-dasharray="3.55 3.55" stroke-dashoffset="-1.77"/><circle cx="16" cy="16" r="9.3" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="3.6" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="6" fill="url(#ix-silver)" stroke="#64748b" stroke-opacity=".5" stroke-width=".8"/><circle cx="16" cy="16" r="3.2" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8"/><path d="M9 13a7.500 7.500 0 0 1 8-5c-4 .2-6.500 2-8 5z" fill="#fff" fill-opacity="0.4"/></symbol><symbol id="ix-usertie" viewBox="0 0 32 32"><path d="M3.500 30.500c0-6 5-9.500 12.500-9.500s12.500 3.500 12.500 9.500z" fill="url(#ix-dark)" stroke="#020617" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><circle cx="16" cy="11" r="5.4" fill="url(#ix-skin)" stroke="#9a5b25" stroke-opacity=".5" stroke-width=".8"/><path d="M10.400 10.800a5.600 5.600 0 0 1 11.200 0c-1.600-2.200-3.600-3-5.600-3s-4 .8-5.600 3z" fill="url(#ix-hair)" stroke="#1c0a00" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M12.200 21.500L16 26.500l3.800-5z" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M14.900 24.500l1.100-1.600 1.100 1.600 1.200 5.500-2.300 1.700-2.300-1.700z" fill="url(#ix-red)" stroke="#7f1d1d" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="ix-spark" viewBox="0 0 32 32"><path d="M16 1.5c1.1 8 6.5 13.4 14.5 14.5-8 1.1-13.4 6.5-14.5 14.5-1.1-8-6.5-13.4-14.5-14.5C9.5 14.9 14.9 9.5 16 1.5z" fill="url(#ix-gem)" stroke="#4338ca" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M26.2 2.6l1.02 2.38 2.38 1.02-2.38 1.02-1.02 2.38-1.02-2.38-2.38-1.02 2.38-1.02z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M6 23.4l0.78 1.82 1.82 0.78-1.82 0.78-0.78 1.82-0.78-1.82-1.82-0.78 1.82-0.78z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="ix-sun" viewBox="0 0 32 32"><path d="M26.30 16.00L29.60 16.00" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M23.28 23.28L25.62 25.62" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.00 26.30L16.00 29.60" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.72 23.28L6.38 25.62" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M5.70 16.00L2.40 16.00" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.72 8.72L6.38 6.38" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.00 5.70L16.00 2.40" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M23.28 8.72L25.62 6.38" fill="none" stroke="#f59e0b" stroke-opacity="1" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="16" cy="16" r="7.2" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8"/><path d="M10.6 14a5.6 5.6 0 0 1 9-3.4c-3.2-.6-6.8.4-9 3.4z" fill="#fff" fill-opacity="0.5"/></symbol><symbol id="ix-moon" viewBox="0 0 32 32"><path d="M21 3.2A13 13 0 1 0 28.8 21 10.2 10.2 0 0 1 21 3.2z" fill="url(#ix-gold)" stroke="#b45309" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M9 12a9 9 0 0 1 8-7.6c-4.6 1.5-7.6 4.2-8 7.6z" fill="#fff" fill-opacity="0.45"/><path d="M24.5 6.1l0.72 1.68 1.68 0.72-1.68 0.72-0.72 1.68-0.72-1.68-1.68-0.72 1.68-0.72z" fill="url(#ix-sw)" stroke="#fbbf24" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M28 13.4l0.48 1.12 1.12 0.48-1.12 0.48-0.48 1.12-0.48-1.12-1.12-0.48 1.12-0.48z" fill="url(#ix-sw)" stroke="#fbbf24" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/></symbol><symbol id="ix-upload" viewBox="0 0 32 32"><path d="M6.5 2.5h12.2l6.8 6.8v18.2a2.5 2.5 0 0 1-2.5 2.5h-16.5a2.5 2.5 0 0 1-2.5-2.5V5a2.5 2.5 0 0 1 2.5-2.5z" fill="url(#ix-white)" stroke="#cbd5e1" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M18.7 2.5V7.8a1.5 1.5 0 0 0 1.5 1.5h5.3z" fill="url(#ix-sky)" stroke="#075985" stroke-opacity=".5" stroke-width=".8" stroke-linejoin="round"/><path d="M9.5 14h9M9.5 18h6" fill="none" stroke="#94a3b8" stroke-opacity="1" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="23" cy="23" r="7.4" fill="url(#ix-blue)" stroke="#1e3a8a" stroke-opacity=".5" stroke-width=".8"/><circle cx="23" cy="23" r="7.4" fill="none" stroke="#fff" stroke-width="1.4"/><path d="M23 27.2v-8M19.6 22.4l3.4-3.4 3.4 3.4" fill="none" stroke="#ffffff" stroke-opacity="1" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></symbol></svg>

<input type="hidden" id="tpl" value="<?= !empty($templates) ? (int)$templates[0]->id : 0 ?>">

<?php
$menu_samping = [
  ['HOME', [
    ['Dashboard','dashboard','ix-desktop','c-dash'],
    ['Data Umum',[['Tahun Pelajaran','datatahun','ix-calcheck'],['Mata Pelajaran','datamapel','ix-book'],['Jurusan','datajurusan','ix-flask'],['Siswa','datasiswa','ix-users'],['Kelas / Rombel','datakelas','ix-school'],['Ekstrakurikuler','dataekstra','ix-chess'],['Guru','dataguru','ix-teacher']],'ix-server','c-umum'],
    ['Data Ujian',[['Jenis Ujian','cbtjenis','ix-diagram'],['Sesi','cbtsesi','ix-clock'],['Ruang','cbtruang','ix-door'],['Atur Ruang/Sesi','cbtsesisiswa','ix-userclock'],['Nomor Peserta','cbtnomorpeserta','ix-idcard'],['Bank Soal','cbtbanksoal','ix-folder'],['Jadwal','cbtjadwal','ix-calendar'],['Alokasi Waktu','cbtalokasi','ix-hourglass'],['Pengawas','cbtpengawas','ix-briefcase'],['Token','cbttoken','ix-key']],'ix-graduate','c-ujian'],
    ['Pengumuman','pengumuman','ix-bullhorn','c-info'],
    ['AI Soal','aiimport','ix-magic','c-ai','New'],
  ]],
  ['PELAKSANAAN', [
    ['Pelaksanaan Ujian',[['Cetak','cbtcetak','ix-print'],['Status Siswa','cbtstatus','ix-userclock'],['Hasil Ujian','cbtnilai','ix-filealt'],['Analisis Soal','cbtanalisis','ix-chart'],['Rekap Nilai','cbtrekap','ix-trophy']],'ix-gradcap','c-ujian'],
  ]],
  ['PENGATURAN', [
    ['Profile Sekolah','settings','ix-university','c-set'],
    ['User Management',[['Administrator','useradmin','ix-cog'],['Guru','userguru','ix-usertie'],['Siswa','usersiswa','ix-users']],'ix-userscog','c-set'],
    ['Database',[['Backup','dbmanager','ix-dbbackup'],['Data Manager','dbclear','ix-dbclear']],'ix-database','c-set'],
  ]],
];
if (!function_exists('ix_ico')) {
    function ix_ico($id) {
        return '<svg class="ik" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#' . htmlspecialchars($id) . '"/></svg>';
    }
}
if (!function_exists('lan_ip_aiimport')) {
    function lan_ip_aiimport() {
        $sock = @stream_socket_client('udp://8.8.8.8:53', $errno, $errstr, 1);
        if ($sock) {
            $name = stream_socket_get_name($sock, false);
            fclose($sock);
            $ip = explode(':', $name)[0];
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && $ip !== '127.0.0.1') return $ip;
        }
        $ip = gethostbyname(gethostname());
        if ($ip !== '127.0.0.1' && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) return $ip;
        if (!empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] !== '::1') return $_SERVER['SERVER_ADDR'];
        return '127.0.0.1';
    }
}
$lan_script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
$lan_dir    = trim(dirname($lan_script), '/');
$lan_path   = ($lan_dir === '.') ? '' : $lan_dir;
$lan_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$lan_port   = isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : 80;
$lan_url    = $lan_scheme . '://' . lan_ip_aiimport() . (in_array($lan_port, [80, 443]) ? '' : ':' . $lan_port) . ($lan_path !== '' ? '/' . $lan_path : '');
?>
<input type="checkbox" id="navToggle">
<aside class="side">
  <a class="side-brand" href="<?= base_url('dashboard') ?>"><img src="<?= base_url('assets/img/examly-icon-animasi.svg') ?>" alt=""><span class="nm">Examly</span></a>
  <nav>
    <?php foreach ($menu_samping as $grup): ?>
      <div class="grp"><?= $grup[0] ?></div>
      <?php foreach ($grup[1] as $m): ?>
        <?php if (is_array($m[1])): ?>
          <?php $buka = false; foreach ($m[1] as $x) { if ($x[1] === 'aiimport') $buka = true; } ?>
          <details <?= $buka ? 'open' : '' ?>>
            <summary><?= ix_ico($m[2]) ?><span class="nm"><?= htmlspecialchars($m[0]) ?></span><?php if (!empty($m[4])): ?><span class="baru"><?= $m[4] ?></span><?php endif; ?></summary>
            <div class="sub">
              <?php foreach ($m[1] as $x): ?>
                <a href="<?= base_url($x[1]) ?>" class="<?= $x[1] === 'aiimport' ? 'aktif' : '' ?>"><?= ix_ico($x[2]) ?><span class="nm"><?= htmlspecialchars($x[0]) ?></span></a>
              <?php endforeach; ?>
            </div>
          </details>
        <?php else: ?>
          <a href="<?= base_url($m[1]) ?>" class="<?= $m[1] === 'aiimport' ? 'aktif' : '' ?>"><?= ix_ico($m[2]) ?><span class="nm"><?= htmlspecialchars($m[0]) ?></span><?php if (!empty($m[4])): ?><span class="baru"><?= $m[4] ?></span><?php endif; ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endforeach; ?>
    <a class="keluar" href="<?= base_url('login/logout_admin') ?>"><?= ix_ico('ix-logout') ?><span class="nm">LOGOUT</span></a>
  </nav>
  <div class="lan" title="Alamat akses dari komputer lain (LAN)"><svg class="ix-mini" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ix-network"/></svg>LAN: <a href="<?= htmlspecialchars($lan_url) ?>"><?= htmlspecialchars($lan_url) ?></a></div>
</aside>

<div class="konten">
<header class="nav-atas">
  <button type="button" class="tgl" id="btnSide" aria-label="Buka / tutup menu"><span></span><span></span><span></span></button>
  <span class="jdl"><svg class="ix-ttl" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ix-spark"/></svg>Konversi Soal Ujian</span>
  <div class="kanan">
    <div class="kotak"><svg class="ix-mini" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ix-clock"/></svg><span id="jam">00:00:00</span></div>
    <div class="kotak kt-tgl"><svg class="ix-mini" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ix-calendar"/></svg><span id="tgl"></span></div>
    <div class="tema" role="group" aria-label="Pilih tema">
      <button type="button" data-tema="light" title="Tema siang"><svg class="ix-mini" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ix-sun"/></svg></button>
      <button type="button" data-tema="dark" title="Tema malam"><svg class="ix-mini" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ix-moon"/></svg></button>
    </div>
  </div>
</header>

<section class="hero" aria-label="Ringkasan">
  <div class="hero-orb"><svg class="" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ix-spark"/></svg></div>
  <div class="hero-txt">
    <span class="hero-eyebrow"><i class="hero-dot"></i>AI SOAL</span>
    <h1>Konversi Soal Ujian</h1>
    <p>Format yang bisa dipakai: Word, PDF, foto, atau teks biasa.</p>
  </div>
  <ol class="hero-steps">
    <li><b>1</b>Unggah berkas</li>
    <li><b>2</b>Kirim ke Gemini</li>
    <li><b>3</b>Tempel jawaban</li>
  </ol>
</section>

<main class="sheet">
  <ol class="steps">
    <li class="step aktif" id="blok1">
      <span class="marker" aria-hidden="true">1</span>
      <h2>Unggah berkas soal</h2>
      <p class="ket">Format yang bisa dipakai: Word, PDF, foto, atau teks biasa.</p>
      <div class="panel">
        <label class="drop" id="drop" for="berkasM">
          <span class="ikon"><svg class="ix-up" viewBox="0 0 32 32" aria-hidden="true" focusable="false"><use href="#ix-upload"/></svg></span>
          <span><b id="dropJudul">Pilih berkas atau seret ke sini</b><small id="dropInfo">.docx, .pdf, .png, .jpg, .webp, .txt</small></span>
          <input type="file" id="berkasM" accept=".docx,.txt,.pdf,.png,.jpg,.jpeg,.webp" multiple>
        </label>
        <ul id="daftarBerkas" class="daftar-berkas" hidden></ul>

        <label class="saklar">
          <input type="checkbox" id="tebak" checked>
          <span class="rel" aria-hidden="true"></span>
          <span class="teks">Tentukan kunci jawaban dengan AI jika tidak ada di dokumen
            <small>Kunci dari AI tidak selalu tepat. Periksa sekilas sebelum dipakai ujian.</small></span>
        </label>

        <div class="aksi"><button id="btnEks">Siapkan untuk Gemini</button></div>
        <div id="hasilEks" class="hasil"></div>
      </div>
    </li>

    <li class="step terkunci" id="blokSalin" inert>
      <span class="marker" aria-hidden="true">2</span>
      <h2>Kirim ke Gemini</h2>
      <p class="ket" id="infoLangkah2">Salin teks di bawah, tempel ke Gemini (percakapan baru), lalu kirim.</p>
      <div class="panel">
        <textarea id="paket" readonly aria-label="Teks yang akan ditempel ke Gemini"></textarea>
        <div class="aksi">
          <button id="btnSalin">Salin semua</button>
          <a class="btn btn2" href="https://gemini.google.com/app" target="_blank" rel="noopener">Buka Gemini</a>
        </div>
      </div>
    </li>

    <li class="step terkunci" id="blokJson" inert>
      <span class="marker" aria-hidden="true">3</span>
      <h2>Tempel jawaban Gemini</h2>
      <p class="ket">Salin seluruh jawaban Gemini, mulai dari tanda { sampai }, lalu tempel di sini.</p>
      <div class="panel">
        <textarea id="jsonIn" placeholder='{"soal":[ ... ]}' aria-label="Jawaban Gemini"></textarea>
        <div id="gambarBox" style="display:none">
          <h3>Tempel gambar soal</h3>
          <p>Salin gambarnya dari Word, PDF, atau internet (klik kanan lalu Salin gambar, atau tekan Win+Shift+S untuk memotong layar), lalu tekan <b>Ctrl+V</b> di halaman ini. Gambar masuk ke baris yang menyala, lalu pindah ke baris berikutnya. Klik sebuah baris untuk memilihnya. Baris yang dikosongkan tetap berupa teks [GAMBAR: ...].</p>
          <div id="gambarHitung"></div>
          <div id="gambarList"></div>
        </div>
        <div class="aksi"><button id="btnJson">Buat berkas template</button></div>
        <div id="hasilM" class="hasil"></div>
      </div>
    </li>
  </ol>
</main>
</div>

<script>
let CSRF_NAME = '<?= $csrf_name ?>', CSRF = '<?= $csrf_hash ?>', TOKEN = '';
let KOREKSI = []; // jawaban baca ulang Gemini yang sudah dipakai (dikirim ulang tiap kali berkas dibuat)
let NAMA_ASAL = ''; // nama berkas soal yang diunggah (tanpa ekstensi), dipakai untuk nama berkas unduhan
const JENIS = {1:'Pilihan Ganda',2:'PG Kompleks',3:'Menjodohkan',4:'Isian Singkat',5:'Uraian'};
const $ = id => document.getElementById(id);
const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

async function kirim(url, fd, out, btn, label) {
  fd.append(CSRF_NAME, CSRF);
  fd.append('id_template', $('tpl').value);
  if ($('tebak').checked) fd.append('tebak_kunci', '1');
  btn.disabled = true; const lama = btn.textContent; btn.textContent = label;
  out.style.display = 'block'; out.className = 'hasil'; out.textContent = 'Memproses, jangan tutup halaman ini.';
  try {
    const j = await (await fetch(url, { method: 'POST', body: fd })).json();
    if (j.csrf) CSRF = j.csrf;
    btn.disabled = false; btn.textContent = lama;
    return j;
  } catch (e) {
    btn.disabled = false; btn.textContent = lama;
    out.className = 'hasil err'; out.textContent = 'Gagal menghubungi server: ' + e; return null;
  }
}

function tampilHasil(j, out) {
  if (!j) return;
  if (!j.ok) {
    out.className = 'hasil err';
    out.textContent = j.pesan + (j.log && j.log.length ? '\n\nLog:\n' + j.log.join('\n') : '');
    return;
  }
  const belumLengkap = j.diharapkan && j.jumlah < j.diharapkan * 0.85;
  const chips = Object.keys(j.per_jenis).filter(k => j.per_jenis[k] > 0)
    .map(k => '<span class="chip">' + JENIS[k] + ': ' + j.per_jenis[k] + '</span>').join('');
  const cat = [];
  if (belumLengkap) cat.push('Hanya ' + j.jumlah + ' dari sekitar ' + j.diharapkan + ' soal yang terdeteksi. Hasil belum lengkap, jangan diimpor dulu. Cek log di bawah.');
  if (j.gambar) cat.push(j.gambar + ' gambar ikut disisipkan.');
  if (j.gambar_kosong) cat.push(j.gambar_kosong + ' tempat gambar masih berupa teks [GAMBAR: ...] karena gambarnya belum dipasang.');
  if (j.gambar_dilewati) cat.push(j.gambar_dilewati + ' gambar tidak terbaca (format tidak didukung).');
  if (j.persamaan) cat.push(j.persamaan + ' persamaan matematika (Equation Word) terbaca dan ikut dipasang.');
  if (j.kunci_ai) cat.push(j.kunci_ai + ' soal kuncinya ditentukan AI karena tidak ada di dokumen. Mohon dicek.');
  if (j.tanpa_kunci) cat.push(j.tanpa_kunci + ' soal belum punya kunci atau jawaban.');

  out.className = 'hasil ' + (belumLengkap ? 'warn' : 'ok');
  if (!belumLengkap) $('blokJson').classList.add('selesai');
  out.innerHTML =
    '<div class="judul">' + (belumLengkap ? 'Hasil belum lengkap: ' : 'Berhasil: ') + j.jumlah + ' soal dikonversi</div>' +
    '<div class="chips">' + chips + '</div>' +
    (cat.length ? '<ul class="catatan">' + cat.map(c => '<li>' + esc(c) + '</li>').join('') + '</ul>' : '') +
    '<a class="btn" id="lnkUnduh" href="' + esc(j.unduh) + '" download="' + esc(namaUnduh()) + '">Unduh berkas siap import (.docx)</a>' +
    '<p class="hint">Setelah diunduh, impor lewat menu Import Word di aplikasi.</p>' +
    (j.log && j.log.length ? '<details class="log"><summary>Lihat log</summary><pre>' + esc(j.log.join('\n')) + '</pre></details>' : '');
  pasangUnduh($('lnkUnduh'));
  if (j.prompt_ulang) {
    const kotak = document.createElement('div');
    kotak.className = 'ulang';
    kotak.innerHTML =
      '<h3>Ada soal yang opsinya masih ragu ([CEK])</h3>' +
      '<p class="hint">Agar lebih akurat: tempel teks di bawah ke percakapan Gemini yang SAMA (PDF masih terlampir), lalu tempel jawabannya di kotak berikutnya dan tekan &quot;Buat berkas template&quot; lagi. Langkah ini opsional.</p>' +
      '<textarea id="ulangPaket" readonly style="min-height:120px" aria-label="Teks baca ulang untuk Gemini"></textarea>' +
      '<div class="aksi"><button type="button" id="btnSalinUlang">Salin teks baca ulang</button></div>' +
      '<textarea id="koreksiIn" style="min-height:90px" placeholder=\'{"soal":[{"id":4,"opsi":[ ... ]}]}\' aria-label="Jawaban baca ulang Gemini"></textarea>';
    out.appendChild(kotak);
    $('ulangPaket').value = j.prompt_ulang;
    $('btnSalinUlang').onclick = async () => {
      try { await navigator.clipboard.writeText($('ulangPaket').value); }
      catch (e) { $('ulangPaket').select(); document.execCommand('copy'); }
      $('btnSalinUlang').textContent = 'Tersalin';
      setTimeout(() => { const b = $('btnSalinUlang'); if (b) b.textContent = 'Salin teks baca ulang'; }, 2000);
    };
  }
}

/* Nama berkas unduhan mengikuti nama berkas soal yang diunggah */
function namaUnduh() {
  const dasar = (NAMA_ASAL || 'soal').replace(/[\\\/:*?"<>|\x00-\x1f]/g, '_').trim().slice(0, 120) || 'soal';
  return dasar + '.docx';
}
function pasangUnduh(a) {
  if (!a) return;
  a.addEventListener('click', async e => {
    e.preventDefault();
    try {
      const r = await fetch(a.href, { credentials: 'same-origin' });
      if (!r.ok) throw new Error(r.status);
      const u = URL.createObjectURL(await r.blob());
      const t = document.createElement('a');
      t.href = u; t.download = namaUnduh();
      document.body.appendChild(t); t.click(); t.remove();
      setTimeout(() => URL.revokeObjectURL(u), 10000);
    } catch (err) {
      location.href = a.href; // cadangan: unduh biasa dengan nama bawaan server
    }
  });
}

/* Zona unggah: tampilkan nama berkas, dukung seret & lepas */
function ukuran(b) { return b > 1048576 ? (b / 1048576).toFixed(1) + ' MB' : Math.max(1, Math.round(b / 1024)) + ' KB'; }
const MAKS_BERKAS = 10;
let BERKAS = [];
function tambahBerkas(files) {
  const out = $('hasilEks');
  Array.from(files).forEach(f => {
    if (BERKAS.length >= MAKS_BERKAS) { out.style.display = 'block'; out.className = 'hasil err'; out.textContent = 'Maksimal ' + MAKS_BERKAS + ' berkas sekali proses.'; return; }
    if (!BERKAS.some(b => b.name === f.name && b.size === f.size)) BERKAS.push(f);
  });
  tandaiBerkas();
}
function tandaiBerkas() {
  const d = $('drop'), ul = $('daftarBerkas'), n = BERKAS.length;
  d.classList.toggle('terisi', n > 0);
  $('dropJudul').textContent = n === 0 ? 'Pilih berkas atau seret ke sini' : (n === 1 ? BERKAS[0].name : n + ' berkas dipilih');
  $('dropInfo').textContent = n === 0 ? '.docx, .pdf, .png, .jpg, .webp, .txt (boleh lebih dari satu)' : 'Klik untuk menambah berkas lain.';
  ul.hidden = n < 1;
  ul.innerHTML = BERKAS.map((f, i) => '<li><span>' + esc(f.name) + '</span><small>' + ukuran(f.size) + '</small>' +
    '<button type="button" data-hapus="' + i + '" aria-label="Hapus ' + esc(f.name) + '">&times;</button></li>').join('');
}
$('daftarBerkas').addEventListener('click', e => {
  const b = e.target.closest('[data-hapus]'); if (!b) return;
  BERKAS.splice(+b.dataset.hapus, 1); tandaiBerkas();
});
$('berkasM').addEventListener('change', () => { tambahBerkas($('berkasM').files); $('berkasM').value = ''; });
['dragenter', 'dragover'].forEach(ev => $('drop').addEventListener(ev, e => { e.preventDefault(); $('drop').classList.add('over'); }));
['dragleave', 'drop'].forEach(ev => $('drop').addEventListener(ev, e => { e.preventDefault(); $('drop').classList.remove('over'); }));
$('drop').addEventListener('drop', e => { if (e.dataTransfer.files.length) tambahBerkas(e.dataTransfer.files); });

/* Langkah 1 */
$('btnEks').onclick = async () => {
  const out = $('hasilEks');
  if (!BERKAS.length) { out.style.display = 'block'; out.className = 'hasil err'; out.textContent = 'Pilih berkas soal dulu.'; return; }
  const f = BERKAS[0]; const banyak = BERKAS.length > 1;
  const fd = new FormData(); BERKAS.forEach(b => fd.append('berkas[]', b));
  const j = await kirim('<?= site_url('aiimport/ekstrak') ?>', fd, out, $('btnEks'), 'Menyiapkan…');
  if (!j) return;
  if (!j.ok) { out.className = 'hasil err'; out.textContent = j.pesan; return; }
  TOKEN = j.token; $('paket').value = j.paket;
  NAMA_ASAL = f.name.replace(/\.[^.]+$/, '') + (banyak ? '_dan_' + (BERKAS.length - 1) + '_berkas_lain' : '');
  $('blok1').classList.remove('aktif'); $('blok1').classList.add('selesai');
  ['blokSalin', 'blokJson'].forEach(i => { $(i).classList.remove('terkunci', 'selesai'); $(i).classList.add('aktif'); $(i).removeAttribute('inert'); });
  out.className = 'hasil ok';
  if (j.lampiran) {
    $('infoLangkah2').innerHTML = '<b>Berkas ini PDF atau foto.</b> Salin prompt di bawah, tempel ke Gemini, lalu <b>lampirkan berkas soalnya di Gemini</b> (tombol + atau ikon klip) sebelum menekan kirim.';
    out.textContent = 'Prompt siap. Lampirkan ' + (banyak ? BERKAS.length + ' berkas (' + BERKAS.map(b => '"' + b.name + '"').join(', ') + ')' : '"' + f.name + '"') + ' langsung di Gemini. Gambar di dalam PDF tidak ikut dipasang ke template; Gemini akan menulis [GAMBAR: deskripsi] pada posisinya, pasang gambar aslinya setelah impor.';
  } else {
    $('infoLangkah2').textContent = 'Salin teks di bawah, tempel ke Gemini (percakapan baru), lalu kirim.';
    out.textContent = 'Teks siap' + ((j.gambar || j.persamaan) ? ' (' + (j.gambar ? j.gambar + ' gambar' : '') + (j.gambar && j.persamaan ? ' dan ' : '') + (j.persamaan ? j.persamaan + ' persamaan' : '') + ' akan dipasang otomatis di langkah 3)' : '') + '.';
    if (j.gambar) out.innerHTML += '<br>Catatan: Gemini hanya melihat penanda gambar, bukan isinya. Cek kunci soal yang bergantung pada gambar.';
    if (j.gambar_dilewati) { out.className = 'hasil warn'; out.innerHTML += '<br>Peringatan: ' + j.gambar_dilewati + ' gambar tidak terbaca (format lama seperti .emf/.wmf, biasanya rumus MathType). Ganti ke PNG/JPG atau tulis ulang dengan Equation Word, lalu unggah lagi.'; }
  }
  $('blokSalin').scrollIntoView({ behavior: 'smooth', block: 'start' });
};
$('btnSalin').onclick = async () => {
  $('blokSalin').classList.add('selesai');
  try { await navigator.clipboard.writeText($('paket').value); }
  catch (e) { $('paket').select(); document.execCommand('copy'); }
  $('btnSalin').textContent = 'Tersalin';
  setTimeout(() => $('btnSalin').textContent = 'Salin semua', 2000);
};
/* Langkah 3 */
/* Tempel gambar untuk penanda [GAMBAR: ...] */
let TTD_GAMBAR = '', TMR = null, BARIS_AKTIF = null;
const semuaBaris = () => Array.from(document.querySelectorAll('#gambarList .gbr'));
function setAktif(row) {
  BARIS_AKTIF = row;
  semuaBaris().forEach(r => r.classList.toggle('aktif', r === row));
  if (row) row.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
}
function hitungGambar() {
  const rows = semuaBaris(), n = rows.filter(r => r.classList.contains('terisi')).length;
  $('gambarHitung').textContent = n + ' dari ' + rows.length + ' gambar terpasang';
}
function isiBaris(row, file) {
  if (!file || file.type.indexOf('image/') !== 0) return;
  const inp = row.querySelector('input'), img = row.querySelector('img');
  const dt = new DataTransfer(); dt.items.add(file); inp.files = dt.files;
  if (img.src) URL.revokeObjectURL(img.src);
  img.src = URL.createObjectURL(file); img.hidden = false;
  row.querySelector('.gbr-teks').hidden = true;
  row.querySelector('[data-aksi=hapus]').hidden = false;
  row.classList.add('terisi');
  setAktif(semuaBaris().find(r => !r.classList.contains('terisi')) || null);
  hitungGambar();
}
function kosongkanBaris(row) {
  const inp = row.querySelector('input'), img = row.querySelector('img');
  inp.value = ''; img.removeAttribute('src'); img.hidden = true;
  row.querySelector('.gbr-teks').hidden = false;
  row.querySelector('[data-aksi=hapus]').hidden = true;
  row.classList.remove('terisi');
  setAktif(row); hitungGambar();
}
function renderGambar(tempat) {
  const sig = JSON.stringify(tempat);
  if (sig === TTD_GAMBAR) return;
  TTD_GAMBAR = sig;
  const list = $('gambarList'); list.innerHTML = '';
  $('gambarBox').style.display = tempat.length ? 'block' : 'none';
  tempat.forEach((t, k) => {
    const row = document.createElement('div'); row.className = 'gbr'; row.tabIndex = 0;
    row.innerHTML = '<div class="gbr-info"><b>Soal ' + t.no + '</b><small>' + esc(t.ket || '(tanpa deskripsi)') + '</small></div>' +
      '<div class="gbr-zona"><img class="gbr-thumb" alt="" hidden><span class="gbr-teks">Tempel di sini<br>Ctrl+V</span></div>' +
      '<div class="gbr-aksi">' +
        '<button type="button" class="btn2" data-aksi="tempel">Tempel</button>' +
        '<label class="btn btn2">Berkas<input type="file" accept="image/*" data-k="' + k + '" hidden></label>' +
        '<button type="button" class="btn2" data-aksi="hapus" hidden>Hapus</button>' +
      '</div>';
    row.addEventListener('click', e => { if (!e.target.closest('button,label')) setAktif(row); });
    row.querySelector('input').addEventListener('change', e => isiBaris(row, e.target.files[0]));
    row.querySelector('[data-aksi=hapus]').addEventListener('click', () => kosongkanBaris(row));
    row.querySelector('[data-aksi=tempel]').addEventListener('click', async () => {
      setAktif(row);
      try {
        for (const it of await navigator.clipboard.read()) {
          const tipe = it.types.find(tp => tp.indexOf('image/') === 0);
          if (tipe) { isiBaris(row, new File([await it.getType(tipe)], 'tempel.png', { type: tipe })); return; }
        }
        alert('Tidak ada gambar di clipboard. Salin gambarnya dulu.');
      } catch (err) { alert('Browser tidak mengizinkan membaca clipboard. Tekan Ctrl+V langsung di halaman ini.'); }
    });
    ['dragenter', 'dragover'].forEach(ev => row.addEventListener(ev, e => { e.preventDefault(); row.classList.add('seret'); }));
    ['dragleave', 'drop'].forEach(ev => row.addEventListener(ev, e => { e.preventDefault(); row.classList.remove('seret'); }));
    row.addEventListener('drop', e => { const f = Array.from(e.dataTransfer.files).find(f => f.type.indexOf('image/') === 0); if (f) isiBaris(row, f); });
    list.appendChild(row);
  });
  setAktif(semuaBaris()[0] || null); hitungGambar();
}
async function cekGambar() {
  const v = $('jsonIn').value;
  // Mode PDF/foto (TOKEN kosong): selalu periksa ke server, karena soal bergambar bisa tanpa penanda dan slotnya dibuat server.
  if (!v.trim() || (TOKEN !== '' && v.indexOf('GAMBAR') === -1)) { renderGambar([]); return; }
  const fd = new FormData(); fd.append('json', v); fd.append('token', TOKEN); fd.append(CSRF_NAME, CSRF);
  $('btnJson').disabled = true;
  try {
    const j = await (await fetch('<?= site_url('aiimport/cek_gambar') ?>', { method: 'POST', body: fd })).json();
    if (j.csrf) CSRF = j.csrf;
    renderGambar(j.tempat || []);
  } catch (e) { /* abaikan: pengguna tetap bisa membuat berkas tanpa gambar */ }
  $('btnJson').disabled = false;
}
$('jsonIn').addEventListener('input', () => {
  // JSON diubah/ditempel ulang: pesan hasil percobaan sebelumnya sudah tidak berlaku
  KOREKSI = [];
  const h = $('hasilM'); h.style.display = 'none'; h.className = 'hasil'; h.textContent = '';
  clearTimeout(TMR); TMR = setTimeout(cekGambar, 700);
});
/* Ctrl+V di mana saja pada halaman: gambar masuk ke baris terpilih (atau baris kosong pertama) */
document.addEventListener('paste', e => {
  if ($('gambarBox').style.display === 'none' || !e.clipboardData) return;
  let f = Array.from(e.clipboardData.files || []).find(f => f.type.indexOf('image/') === 0);
  if (!f) { const it = Array.from(e.clipboardData.items || []).find(i => i.type.indexOf('image/') === 0); if (it) f = it.getAsFile(); }
  if (!f) return;
  const row = (e.target.closest && e.target.closest('.gbr')) || BARIS_AKTIF || semuaBaris().find(r => !r.classList.contains('terisi'));
  if (!row) return;
  e.preventDefault();
  isiBaris(row, f);
});

$('btnJson').onclick = async () => {
  const kor = $('koreksiIn') ? $('koreksiIn').value.trim() : '';   // dibaca sebelum kotak hasil dikosongkan oleh kirim()
  if (kor && KOREKSI.indexOf(kor) < 0) KOREKSI.push(kor);
  const fd = new FormData(); fd.append('json', $('jsonIn').value);
  KOREKSI.forEach(k => fd.append('koreksi[]', k)); fd.append('token', TOKEN); fd.append('nama_unduh', namaUnduh());
  document.querySelectorAll('#gambarList input[type=file]').forEach(i => { if (i.files[0]) fd.append('gbr[' + i.dataset.k + ']', i.files[0]); });
  tampilHasil(await kirim('<?= site_url('aiimport/dari_json') ?>', fd, $('hasilM'), $('btnJson'), 'Membuat…'), $('hasilM'));
};
</script>
<script>
(function () {
  var g = function (i) { return document.getElementById(i); };
  function jam() {
    var n = new Date(), p = function (x) { return String(x).padStart(2, '0'); };
    g('jam').textContent = p(n.getHours()) + ':' + p(n.getMinutes()) + ':' + p(n.getSeconds());
    g('tgl').textContent = n.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
  }
  jam(); setInterval(jam, 1000);
  /* Tombol ☰: desktop = sidebar menciut jadi ikon / melebar; layar kecil = buka-tutup overlay */
  g('btnSide').addEventListener('click', function () {
    if (window.innerWidth <= 900) { var c = g('navToggle'); c.checked = !c.checked; }
    else { document.body.classList.toggle('side-tutup'); }
  });
  function tandai() {
    var d = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    document.querySelectorAll('[data-tema]').forEach(function (b) { b.classList.toggle('on', b.dataset.tema === d); });
  }
  document.querySelectorAll('[data-tema]').forEach(function (b) {
    b.addEventListener('click', function () {
      var t = b.dataset.tema;
      if (t === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
      else document.documentElement.removeAttribute('data-theme');
      try { localStorage.setItem('garudaCBT.theme', t); } catch (e) {}
      tandai();
    });
  });
  tandai();
})();
</script>
</body>
</html>