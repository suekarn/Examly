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
</style>
</head>
<body>

<input type="hidden" id="tpl" value="<?= !empty($templates) ? (int)$templates[0]->id : 0 ?>">

<?php
$menu_samping = [
  ['HOME', [
    ['Dashboard','dashboard','fas fa-desktop','c-dash'],
    ['Data Umum',[['Tahun Pelajaran','datatahun','far fa-calendar-check'],['Mata Pelajaran','datamapel','fa fa-book'],['Jurusan','datajurusan','fa fa-flask'],['Siswa','datasiswa','fa fa-users'],['Kelas / Rombel','datakelas','fa fa-school'],['Ekstrakurikuler','dataekstra','fa fa-chess'],['Guru','dataguru','fa fa-chalkboard-teacher']],'fas fa-server','c-umum'],
    ['Data Ujian',[['Jenis Ujian','cbtjenis','fa fa-project-diagram'],['Sesi','cbtsesi','far fa-clock'],['Ruang','cbtruang','fa fa-door-open'],['Atur Ruang/Sesi','cbtsesisiswa','fa fa-user-clock'],['Nomor Peserta','cbtnomorpeserta','far fa-id-card'],['Bank Soal','cbtbanksoal','far fa-folder-open'],['Jadwal','cbtjadwal','far fa-calendar-alt'],['Alokasi Waktu','cbtalokasi','fa fa-clock-o'],['Pengawas','cbtpengawas','fa fa-briefcase'],['Token','cbttoken','fa fa-key']],'fa fa-user-graduate','c-ujian'],
    ['Pengumuman','pengumuman','fas fa-bullhorn','c-info'],
    ['AI Soal','aiimport','fa fa-magic','c-ai','New'],
  ]],
  ['PELAKSANAAN', [
    ['Pelaksanaan Ujian',[['Cetak','cbtcetak','fa fa-print'],['Status Siswa','cbtstatus','fa fa-user-clock'],['Hasil Ujian','cbtnilai','fa fa-file-alt'],['Analisis Soal','cbtanalisis','fa fa-chart-line'],['Rekap Nilai','cbtrekap','fas fa-trophy']],'fas fa-graduation-cap','c-ujian'],
  ]],
  ['PENGATURAN', [
    ['Profile Sekolah','settings','fas fa-university','c-set'],
    ['User Management',[['Administrator','useradmin','fas fa-cog'],['Guru','userguru','fas fa-user-tie'],['Siswa','usersiswa','fas fa-users']],'fa fa-users-cog','c-set'],
    ['Database',[['Backup','dbmanager','fas fa-database'],['Data Manager','dbclear','fas fa-database']],'fa fa-database','c-set'],
  ]],
];
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
            <summary><i class="ik <?= $m[2] ?> <?= $m[3] ?>"></i><span class="nm"><?= htmlspecialchars($m[0]) ?></span><?php if (!empty($m[4])): ?><span class="baru"><?= $m[4] ?></span><?php endif; ?></summary>
            <div class="sub">
              <?php foreach ($m[1] as $x): ?>
                <a href="<?= base_url($x[1]) ?>" class="<?= $x[1] === 'aiimport' ? 'aktif' : '' ?>"><i class="ik <?= $x[2] ?>"></i><span class="nm"><?= htmlspecialchars($x[0]) ?></span></a>
              <?php endforeach; ?>
            </div>
          </details>
        <?php else: ?>
          <a href="<?= base_url($m[1]) ?>" class="<?= $m[1] === 'aiimport' ? 'aktif' : '' ?>"><i class="ik <?= $m[2] ?> <?= $m[3] ?>"></i><span class="nm"><?= htmlspecialchars($m[0]) ?></span><?php if (!empty($m[4])): ?><span class="baru"><?= $m[4] ?></span><?php endif; ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endforeach; ?>
    <a class="keluar" href="<?= base_url('login/logout_admin') ?>"><i class="ik fas fa-sign-out-alt"></i><span class="nm">LOGOUT</span></a>
  </nav>
  <div class="lan" title="Alamat akses dari komputer lain (LAN)"><i class="fas fa-network-wired"></i>LAN: <a href="<?= htmlspecialchars($lan_url) ?>"><?= htmlspecialchars($lan_url) ?></a></div>
</aside>

<div class="konten">
<header class="nav-atas">
  <button type="button" class="tgl" id="btnSide" aria-label="Buka / tutup menu"><span></span><span></span><span></span></button>
  <span class="jdl">Konversi Soal Ujian</span>
  <div class="kanan">
    <div class="kotak"><i class="far fa-clock"></i><span id="jam">00:00:00</span></div>
    <div class="kotak kt-tgl"><i class="far fa-calendar-alt"></i><span id="tgl"></span></div>
    <div class="tema" role="group" aria-label="Pilih tema">
      <button type="button" data-tema="light" title="Tema siang"><i class="fas fa-sun"></i></button>
      <button type="button" data-tema="dark" title="Tema malam"><i class="fas fa-moon"></i></button>
    </div>
  </div>
</header>

<main class="sheet">
  <ol class="steps">
    <li class="step aktif" id="blok1">
      <span class="marker" aria-hidden="true">1</span>
      <h2>Unggah berkas soal</h2>
      <p class="ket">Format yang bisa dipakai: Word, PDF, foto, atau teks biasa.</p>
      <div class="panel">
        <label class="drop" id="drop" for="berkasM">
          <span class="ikon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M12 17v-6"/><path d="m9.5 13.5 2.5-2.5 2.5 2.5"/></svg></span>
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