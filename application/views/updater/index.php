<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pembaruan Aplikasi</title>
<style>
  :root {
    --bg:#f3f5f9; --surface:#fff; --text:#18202e; --muted:#667085; --line:#e4e7ec;
    --accent:#2563eb; --accent-soft:#e8effd; --ok:#15803d; --ok-soft:#e6f4ea;
    --bad:#b42318; --bad-soft:#fdeceb; --warn:#b45309; --warn-soft:#fdf3e1;
  }
  @media (prefers-color-scheme: dark) {
    :root {
      --bg:#0f1520; --surface:#18202e; --text:#eef1f6; --muted:#98a2b3; --line:#2a3445;
      --accent:#5b8def; --accent-soft:#1b2a4a; --ok:#4ade80; --ok-soft:#163222;
      --bad:#f87171; --bad-soft:#3a1d1d; --warn:#fbbf24; --warn-soft:#3a2b10;
    }
  }
  * { box-sizing:border-box; }
  body { margin:0; font:15px/1.55 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; background:var(--bg); color:var(--text); }
  .wrap { max-width:780px; margin:0 auto; padding:28px 16px 56px; }
  .muted { color:var(--muted); }
  .hide { display:none !important; }

  .top { display:flex; align-items:center; gap:14px; margin-bottom:20px; }
  .appicon { width:46px; height:46px; border-radius:12px; background:var(--accent); color:#fff; display:grid; place-items:center; flex:none; }
  h1 { font-size:21px; line-height:1.25; margin:0; }
  .top p { margin:2px 0 0; font-size:14px; }

  button { font:inherit; font-size:14px; padding:9px 18px; border-radius:9px; border:1px solid var(--line); background:var(--surface); color:var(--text); cursor:pointer; }
  button:hover:not(:disabled) { border-color:var(--muted); }
  button.primary { background:var(--accent); border-color:var(--accent); color:#fff; font-weight:600; }
  button.danger { background:var(--bad); border-color:var(--bad); color:#fff; font-weight:600; }
  button.small { padding:6px 12px; font-size:13px; }
  button:disabled { opacity:.5; cursor:not-allowed; }
  button:focus-visible, summary:focus-visible { outline:2px solid var(--accent); outline-offset:2px; }

  /* Kartu status utama */
  .hero { display:flex; gap:18px; align-items:center; flex-wrap:wrap; background:var(--surface); border:1px solid var(--line); border-radius:16px; padding:22px; }
  .badge { width:60px; height:60px; border-radius:50%; display:grid; place-items:center; flex:none; background:var(--accent-soft); color:var(--accent); }
  .hero.ok .badge, .hero.done .badge { background:var(--ok-soft); color:var(--ok); }
  .hero.warn .badge { background:var(--warn-soft); color:var(--warn); }
  .hero.bad .badge { background:var(--bad-soft); color:var(--bad); }
  .hero-main { flex:1 1 280px; min-width:0; }
  .hero h2 { font-size:19px; line-height:1.3; margin:0 0 2px; }
  .hero p { margin:0; font-size:14px; }
  .vers { display:flex; align-items:center; gap:8px; margin-top:10px; flex-wrap:wrap; font-variant-numeric:tabular-nums; }
  .chip { padding:2px 11px; border-radius:999px; border:1px solid var(--line); font-size:13px; background:var(--bg); }
  .chip.new { background:var(--accent-soft); border-color:transparent; color:var(--accent); font-weight:600; }
  .acts { display:flex; gap:8px; flex-wrap:wrap; }

  .panel { background:var(--surface); border:1px solid var(--line); border-radius:16px; padding:20px 22px; margin-top:16px; }
  .panel h3 { font-size:16px; margin:0 0 4px; }
  .panel .sub { margin:0 0 12px; font-size:13px; }

  /* Catatan versi */
  .notes { border-left:3px solid var(--accent); border-radius:6px 16px 16px 6px; }
  .notes ul { margin:8px 0 0; padding-left:18px; }
  .notes li { margin:3px 0; }

  /* Progres */
  .ptop { display:flex; justify-content:space-between; align-items:baseline; gap:12px; margin-bottom:12px; }
  .ptop strong { font-size:16px; }
  .bar { height:10px; border-radius:999px; background:var(--line); overflow:hidden; }
  .fill { height:100%; width:0; border-radius:999px; background:var(--accent); transition:width .4s ease; position:relative; overflow:hidden; }
  .fill.done { background:var(--ok); } .fill.bad { background:var(--bad); }
  .fill.run::after { content:""; position:absolute; inset:0; background:linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent); transform:translateX(-100%); animation:shine 1.4s linear infinite; }
  .pinfo { display:flex; justify-content:space-between; gap:12px; margin-top:8px; font-size:14px; }
  .pinfo span { min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
  .pinfo strong { font-variant-numeric:tabular-nums; }

  .steps { list-style:none; margin:16px 0 0; padding:0; }
  .steps li { display:grid; grid-template-columns:26px 1fr; gap:12px; padding:8px 0; position:relative; }
  .steps li:not(:last-child)::after { content:""; position:absolute; left:12px; top:34px; bottom:-8px; width:2px; background:var(--line); }
  .steps li.done:not(:last-child)::after { background:var(--ok); }
  .ico { width:26px; height:26px; border-radius:50%; display:grid; place-items:center; font-size:13px; font-weight:700; border:2px solid var(--line); background:var(--surface); z-index:1; }
  .done .ico { background:var(--ok); border-color:var(--ok); color:#fff; } .done .ico::before { content:"\2713"; }
  .fail .ico { background:var(--bad); border-color:var(--bad); color:#fff; } .fail .ico::before { content:"\2715"; }
  .skip .ico { border-style:dashed; color:var(--muted); } .skip .ico::before { content:"\2013"; }
  .active .ico { border-color:var(--accent); border-top-color:transparent; animation:spin .8s linear infinite; }
  .st-t { font-weight:500; line-height:26px; }
  .pending .st-t, .skip .st-t { color:var(--muted); font-weight:400; }
  .fail .st-t { color:var(--bad); }
  .st-d { font-size:13px; color:var(--muted); margin-top:-3px; font-variant-numeric:tabular-nums; }

  .keep { margin-top:14px; padding:10px 12px; border-radius:10px; background:var(--warn-soft); color:var(--warn); font-size:14px; }
  .counts { display:flex; gap:8px; flex-wrap:wrap; margin-top:16px; }
  .cnt { padding:6px 12px; border-radius:10px; background:var(--bg); font-size:14px; }
  .cnt b { font-size:16px; }
  details { margin-top:14px; }
  summary { cursor:pointer; font-size:14px; color:var(--muted); }
  .files { max-height:260px; overflow:auto; border:1px solid var(--line); border-radius:10px; margin-top:8px; }
  .files .it { display:flex; gap:10px; align-items:baseline; padding:6px 10px; border-bottom:1px solid var(--line); font:13px/1.4 ui-monospace,Consolas,monospace; word-break:break-all; }
  .files .it:last-child { border-bottom:0; }
  .tag { flex:0 0 62px; text-align:center; font:600 12px/1.6 system-ui,sans-serif; border-radius:6px; border:1px solid currentColor; }
  .tag.baru { color:var(--ok); } .tag.diubah { color:var(--accent); } .tag.dihapus { color:var(--bad); }
  pre { margin:8px 0 0; padding:12px; background:var(--bg); border:1px solid var(--line); border-radius:10px; white-space:pre-wrap; word-break:break-word; font:13px/1.5 ui-monospace,Consolas,monospace; max-height:240px; overflow:auto; }
  .rb .only-install { display:none; }

  /* Riwayat pemulihan */
  .bk { display:flex; align-items:center; gap:12px; padding:12px 0; border-top:1px solid var(--line); flex-wrap:wrap; }
  .bk:first-of-type { border-top:0; }
  .bk .who { flex:1 1 200px; min-width:0; }
  .bk .v { font-weight:600; font-variant-numeric:tabular-nums; }
  .bk .m { font-size:13px; color:var(--muted); }

  .foot { margin-top:20px; font-size:13px; }

  dialog { border:1px solid var(--line); border-radius:16px; background:var(--surface); color:var(--text); padding:0; width:calc(100% - 32px); max-width:430px; }
  dialog::backdrop { background:rgba(15,21,32,.55); }
  dialog form { padding:22px; }
  dialog h3 { margin:0 0 10px; font-size:18px; }
  dialog ul { margin:0 0 18px; padding-left:18px; font-size:14px; }
  dialog li { margin:4px 0; }
  .dlg-actions { display:flex; gap:8px; justify-content:flex-end; }

  .spin { width:26px; height:26px; border-radius:50%; border:3px solid currentColor; border-top-color:transparent; animation:spin .8s linear infinite; display:block; }
  @keyframes spin { to { transform:rotate(360deg); } }
  @keyframes shine { to { transform:translateX(100%); } }
  @media (prefers-reduced-motion: reduce) {
    * { animation:none !important; transition:none !important; }
    .active .ico { border-top-color:var(--accent); }
  }
</style>
</head>
<body>
<div class="wrap">

  <header class="top">
    <div class="appicon" aria-hidden="true">
      <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v10m0 0l-4-4m4 4l4-4M5 19h14"/></svg>
    </div>
    <div>
      <h1>Pembaruan Aplikasi</h1>
      <p class="muted">Versi terpasang <span id="cur"><?php echo htmlspecialchars($current); ?></span><span id="checked"></span></p>
    </div>
  </header>

  <section class="hero" id="hero" aria-live="polite">
    <div class="badge" id="badge"></div>
    <div class="hero-main">
      <h2 id="heroTitle">Memeriksa pembaruan…</h2>
      <p class="muted" id="heroText">Menghubungi server pembaruan.</p>
      <div class="vers hide" id="vers"></div>
    </div>
    <div class="acts">
      <button class="primary hide" id="btnRun">Unduh dan pasang</button>
      <button class="primary hide" id="btnReload">Muat ulang halaman</button>
      <button class="hide" id="btnCheck">Periksa lagi</button>
    </div>
  </section>

  <section class="panel notes hide" id="notes">
    <h3 id="notesTitle">Catatan versi</h3>
    <p class="muted sub" id="notesMeta"></p>
    <ul id="noteList"></ul>
  </section>

  <section class="panel hide" id="runPanel">
    <div class="ptop">
      <strong id="pTitle">Memasang pembaruan</strong>
      <span class="muted only-install" id="pTime">00:00</span>
    </div>
    <div class="only-install">
      <div class="bar"><div class="fill run" id="barFill"></div></div>
      <div class="pinfo"><span id="pMsg">Memulai…</span><strong id="pPct">0%</strong></div>
      <ol class="steps" id="steps"></ol>
      <div class="keep hide" id="keepOpen">Jangan tutup atau muat ulang halaman ini sampai proses selesai.</div>
      <div class="counts" id="counts"></div>
    </div>
    <details class="only-install" id="fileBox" open>
      <summary>Rincian file (<span id="fileCount">0</span>)</summary>
      <div class="files" id="files"></div>
    </details>
    <details id="logBox" open>
      <summary>Log teknis</summary>
      <pre id="log"></pre>
    </details>
  </section>

  <section class="panel">
    <h3>Riwayat &amp; pemulihan</h3>
    <p class="muted sub">Cadangan dibuat otomatis sebelum setiap pembaruan dipasang. Gunakan jika ada masalah setelah update.</p>
    <div id="backups" class="muted">Belum ada cadangan.</div>
  </section>

  <p class="muted foot">Jangan menjalankan pembaruan saat ujian sedang berlangsung. Pastikan koneksi internet stabil selama proses berjalan.</p>
</div>

<dialog id="dlg">
  <form method="dialog">
    <h3 id="dlgTitle"></h3>
    <ul id="dlgList"></ul>
    <div class="dlg-actions">
      <button value="cancel">Batal</button>
      <button value="ok" id="dlgOk" class="primary">Lanjutkan</button>
    </div>
  </form>
</dialog>

<script>
(function () {
  var BASE = <?php echo json_encode(site_url('updater')); ?>;
  var CSRF = { name: <?php echo json_encode($csrf_name); ?>, hash: <?php echo json_encode($csrf_hash); ?> };
  var $ = function (id) { return document.getElementById(id); };

  var PHASES = [
    { k: 'download', t: 'Mengunduh paket pembaruan' },
    { k: 'verify',   t: 'Memeriksa keamanan paket' },
    { k: 'backup',   t: 'Membuat cadangan' },
    { k: 'write',    t: 'Memasang file' },
    { k: 'migrate',  t: 'Memperbarui database' },
    { k: 'done',     t: 'Selesai' }
  ];
  var SVG = function (d) {
    return '<svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="' + d + '"/></svg>';
  };
  var ICON = {
    spin:  '<span class="spin"></span>',
    ok:    SVG('M5 12.5l4.5 4.5L19 7.5'),
    avail: SVG('M12 4v11m0 0l-4.5-4.5M12 15l4.5-4.5M5 20h14'),
    warn:  SVG('M12 8v5m0 3.5h.01M10.3 4.2L2.8 17.5A2 2 0 004.5 20.5h15a2 2 0 001.7-3L13.7 4.2a2 2 0 00-3.4 0z'),
    bad:   SVG('M6 6l12 12M18 6L6 18')
  };
  var HERO_ICON = { checking: 'spin', run: 'spin', ok: 'ok', done: 'ok', avail: 'avail', warn: 'warn', bad: 'bad' };

  var busy = false, postPending = false, seenRunning = false;
  var info = null, lastP = null, poller = null, shown = 0, dlSample = null, speed = 0;

  /* ---------- util ---------- */
  function api(path, method, data) {
    var opts = { method: method, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } };
    if (method === 'POST') {
      var b = new URLSearchParams();
      b.append(CSRF.name, CSRF.hash);
      for (var k in (data || {})) b.append(k, data[k]);
      opts.body = b;
    }
    return fetch(BASE + '/' + path, opts)
      .then(function (r) { return r.text(); })
      .then(function (t) {
        var j;
        try { j = JSON.parse(t); } catch (e) { throw new Error('Respon server tidak valid. Pastikan Anda masih login.'); }
        if (j.csrf) CSRF.hash = j.csrf;
        return j;
      });
  }
  function mb(n) { return (n / 1048576).toFixed(1).replace('.', ','); }
  function size(n) { return n >= 1048576 ? mb(n) + ' MB' : Math.max(1, Math.round(n / 1024)) + ' KB'; }
  function clock(sec) { sec = Math.max(0, sec | 0); return ('0' + ((sec / 60) | 0)).slice(-2) + ':' + ('0' + (sec % 60)).slice(-2); }
  var BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  function when(s) {
    var m = /^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}:\d{2})/.exec(s || '');
    return m ? (+m[3]) + ' ' + BULAN[+m[2] - 1] + ' ' + m[1] + ', ' + m[4] : (s || '');
  }
  function el(tag, cls, text) {
    var n = document.createElement(tag);
    if (cls) n.className = cls;
    if (text !== undefined) n.textContent = text;
    return n;
  }

  /* ---------- dialog konfirmasi ---------- */
  function ask(o) {
    return new Promise(function (res) {
      var d = $('dlg');
      if (typeof d.showModal !== 'function') { res(window.confirm(o.title + '\n\n' + o.items.join('\n'))); return; }
      $('dlgTitle').textContent = o.title;
      var ul = $('dlgList'); ul.innerHTML = '';
      o.items.forEach(function (t) { ul.appendChild(el('li', '', t)); });
      var ok = $('dlgOk'); ok.textContent = o.ok; ok.className = o.danger ? 'danger' : 'primary';
      d.returnValue = 'cancel';
      d.onclose = function () { res(d.returnValue === 'ok'); };
      d.showModal();
    });
  }

  /* ---------- kartu status ---------- */
  function setHero(kind, o) {
    o = o || {};
    $('hero').className = 'hero ' + kind;
    $('badge').innerHTML = ICON[HERO_ICON[kind]];
    $('heroTitle').textContent = o.title || '';
    $('heroText').textContent = o.text || '';
    var v = $('vers'); v.innerHTML = '';
    if (o.from && o.to) {
      v.appendChild(el('span', 'chip', o.from));
      v.appendChild(el('span', 'muted', '\u2192'));
      v.appendChild(el('span', 'chip new', o.to));
      v.classList.remove('hide');
    } else v.classList.add('hide');
    $('btnRun').classList.toggle('hide', !o.run);
    $('btnReload').classList.toggle('hide', !o.reload);
    var c = $('btnCheck');
    c.classList.toggle('hide', !o.check);
    if (o.check) c.textContent = o.check;
    c.disabled = busy; $('btnRun').disabled = busy;
  }

  function renderNotes(i) {
    var text = (i.changelog || '').trim();
    if (!text) { $('notes').classList.add('hide'); return; }
    $('notesTitle').textContent = 'Yang baru di versi ' + i.latest;
    $('notesMeta').textContent = i.released_at ? 'Dirilis ' + when(i.released_at) : '';
    $('notesMeta').classList.toggle('hide', !i.released_at);
    var ul = $('noteList'); ul.innerHTML = '';
    text.split(/\r?\n|;\s+/).forEach(function (l) {
      l = l.replace(/^\s*[-*\u2022]\s*/, '').trim();
      if (l) ul.appendChild(el('li', '', l));
    });
    $('notes').classList.remove('hide');
  }

  function renderBackups(list) {
    var box = $('backups');
    box.innerHTML = '';
    if (!list || !list.length) { box.className = 'muted'; box.textContent = 'Belum ada cadangan. Cadangan pertama dibuat saat pembaruan dipasang.'; return; }
    box.className = '';
    list.forEach(function (b) {
      var row = el('div', 'bk'), who = el('div', 'who');
      who.appendChild(el('div', 'v', (b.from || '?') + ' \u2192 ' + (b.to || '?')));
      who.appendChild(el('div', 'm', when(b.time) + ' \u00b7 ' + size(b.size)));
      var btn = el('button', 'small', 'Kembalikan');
      btn.onclick = function () { rollback(b); };
      row.appendChild(who); row.appendChild(btn); box.appendChild(row);
    });
  }

  /* ---------- pemeriksaan ---------- */
  function applyInfo(i) {
    info = i;
    $('cur').textContent = i.current;
    renderBackups(i.backups);
    renderNotes(i);
    $('checked').textContent = ' \u00b7 diperiksa ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  }

  function check(force) {
    if (busy) return;
    setHero('checking', { title: 'Memeriksa pembaruan\u2026', text: 'Menghubungi server pembaruan.' });
    return api('check' + (force ? '?force=1' : ''), 'GET').then(function (j) {
      if (!j.ok) { setHero('bad', { title: 'Gagal memeriksa pembaruan', text: j.error || 'Server pembaruan tidak merespons.', check: 'Coba lagi' }); return; }
      var i = j.info; applyInfo(i);
      if (i.blocked) setHero('warn', { title: 'Pembaruan belum bisa dipasang', text: i.blocked, from: i.current, to: i.latest, check: 'Periksa lagi' });
      else if (i.available) setHero('avail', { title: 'Pembaruan tersedia', text: 'Versi ' + i.latest + ' siap dipasang.', from: i.current, to: i.latest, run: true, check: 'Periksa lagi' });
      else setHero('ok', { title: 'Aplikasi sudah yang terbaru', text: 'Versi ' + i.current + ' adalah versi terbaru yang tersedia.', check: 'Periksa lagi' });
    }).catch(function (e) { setHero('bad', { title: 'Gagal memeriksa pembaruan', text: e.message, check: 'Coba lagi' }); });
  }

  // Perbarui data (cadangan, versi) tanpa mengubah kartu status
  function refreshData() {
    return api('check?force=1', 'GET').then(function (j) { if (j.ok) applyInfo(j.info); }).catch(function () {});
  }

  /* ---------- panel proses ---------- */
  function buildSteps() {
    var ol = $('steps'); ol.innerHTML = '';
    PHASES.forEach(function (p) {
      var li = el('li', 'pending'); li.id = 'st-' + p.k;
      li.appendChild(el('span', 'ico'));
      var box = el('div');
      box.appendChild(el('div', 'st-t', p.t));
      box.appendChild(el('div', 'st-d hide'));
      li.appendChild(box); ol.appendChild(li);
    });
  }

  function showPanel(mode) {
    $('runPanel').classList.remove('hide');
    $('runPanel').classList.toggle('rb', mode === 'rollback');
    $('pTitle').textContent = mode === 'rollback' ? 'Mengembalikan versi' : 'Memasang pembaruan';
  }

  function resetRun() {
    shown = 0; dlSample = null; speed = 0; lastP = null; seenRunning = false;
    $('files').innerHTML = ''; $('fileCount').textContent = '0'; $('counts').innerHTML = '';
    $('log').textContent = '';
    var f = $('barFill'); f.style.width = '0%'; f.className = 'fill run';
    $('pPct').textContent = '0%'; $('pMsg').textContent = 'Memulai\u2026'; $('pTime').textContent = '00:00';
    buildSteps();
  }

  function setStep(k, state, detail) {
    var li = $('st-' + k); if (!li) return;
    li.className = state;
    var d = li.querySelector('.st-d');
    d.textContent = detail || '';
    d.classList.toggle('hide', !detail);
  }

  function renderSteps(p) {
    var cur = 0;
    PHASES.forEach(function (ph, i) { if (ph.k === p.phase) cur = i; });
    var noSql = p.has_sql === false;
    PHASES.forEach(function (ph, i) {
      var st;
      if (p.ok === true) st = (ph.k === 'migrate' && noSql) ? 'skip' : 'done';
      else if (i < cur) st = (ph.k === 'migrate' && noSql) ? 'skip' : 'done';
      else if (i === cur) st = p.ok === false ? 'fail' : 'active';
      else st = (ph.k === 'migrate' && noSql) ? 'skip' : 'pending';
      var d = '';
      if (ph.k === 'download' && p.dl && p.dl[1] > 0) {
        d = (st === 'done') ? mb(p.dl[1]) + ' MB' : mb(p.dl[0]) + ' dari ' + mb(p.dl[1]) + ' MB' + (st === 'active' && speed > 0 ? ' \u00b7 ' + speed.toFixed(1).replace('.', ',') + ' MB/s' : '');
      } else if (ph.k === 'verify' && st === 'done') d = 'Hash SHA-256 cocok';
      else if (ph.k === 'write' && p.wt > 0 && st !== 'pending') d = p.wi + ' dari ' + p.wt + ' file diperiksa';
      else if (ph.k === 'migrate' && st === 'skip') d = 'Tidak ada perubahan database';
      setStep(ph.k, st, d);
    });
  }

  function renderFiles(files, same) {
    var box = $('files');
    if (files.length < shown) { shown = 0; box.innerHTML = ''; }
    for (; shown < files.length; shown++) {
      var row = el('div', 'it');
      row.appendChild(el('span', 'tag ' + files[shown].s, files[shown].s));
      row.appendChild(el('span', '', files[shown].n));
      box.appendChild(row);
      box.scrollTop = box.scrollHeight;
    }
    if (!files.length && !box.firstChild) box.appendChild(el('div', 'it muted', 'Belum ada file yang diubah.'));
    else if (files.length && box.firstChild && box.firstChild.className === 'it muted') box.removeChild(box.firstChild);
    $('fileCount').textContent = files.length;
    var c = { baru: 0, diubah: 0, dihapus: 0 };
    files.forEach(function (f) { if (c[f.s] !== undefined) c[f.s]++; });
    var ct = $('counts'); ct.innerHTML = '';
    [['baru', 'baru'], ['diubah', 'diubah'], ['dihapus', 'dihapus']].forEach(function (x) {
      var s = el('span', 'cnt'); s.appendChild(el('b', '', String(c[x[0]]))); s.appendChild(document.createTextNode(' ' + x[1])); ct.appendChild(s);
    });
    var s2 = el('span', 'cnt muted'); s2.appendChild(el('b', '', String(same || 0))); s2.appendChild(document.createTextNode(' tidak berubah')); ct.appendChild(s2);
  }

  function renderRun(p) {
    if (!p) return;
    var pct = Math.max(0, Math.min(100, p.percent || 0));
    var f = $('barFill');
    f.style.width = pct + '%';
    f.className = 'fill' + (p.ok === true ? ' done' : (p.ok === false ? ' bad' : ' run'));
    $('pPct').textContent = pct + '%';
    var steps = p.steps || [];
    $('pMsg').textContent = p.ok === true ? 'Pembaruan selesai' : (p.ok === false ? 'Proses dihentikan' : (steps.length ? steps[steps.length - 1] : 'Memulai\u2026'));
    if (p.started && p.updated) $('pTime').textContent = clock(p.updated - p.started);

    if (p.dl && p.dl[0] > 0) {
      var now = Date.now();
      if (dlSample && p.dl[0] > dlSample.b && now > dlSample.t) {
        var inst = ((p.dl[0] - dlSample.b) / 1048576) / ((now - dlSample.t) / 1000);
        speed = speed ? speed * 0.6 + inst * 0.4 : inst;
      }
      if (!dlSample || p.dl[0] !== dlSample.b) dlSample = { t: now, b: p.dl[0] };
    }
    renderSteps(p);
    renderFiles(p.files || [], p.same);
    var pre = $('log'); pre.textContent = steps.join('\n'); pre.scrollTop = pre.scrollHeight;
  }

  function setBusy(v) {
    busy = v;
    $('keepOpen').classList.toggle('hide', !v);
    $('btnCheck').disabled = v; $('btnRun').disabled = v;
  }

  /* ---------- polling ---------- */
  function poll() {
    api('progress', 'GET').then(function (j) {
      if (!j.ok || !j.progress || j.progress.updated === undefined) return;
      var p = j.progress;
      if (p.running) seenRunning = true;
      if (postPending && !seenRunning && !p.running) return; // data lama dari update sebelumnya
      lastP = p;
      renderRun(p);
      if (!p.running && p.ok !== null && busy && !postPending) {
        finish({ ok: p.ok, error: p.error, version: p.version, files: p.files, same: p.same, steps: p.steps });
      }
    }).catch(function () {});
  }
  function startPolling() { if (!poller) { poll(); poller = setInterval(poll, 800); } }
  function stopPolling() { if (poller) { clearInterval(poller); poller = null; } }

  /* ---------- pasang update ---------- */
  function run() {
    if (busy || !info) return;
    ask({
      title: 'Pasang versi ' + info.latest + '?',
      items: [
        'Pastikan tidak ada ujian yang sedang berlangsung.',
        'Cadangan file dibuat otomatis sebelum pembaruan dipasang.',
        'Proses biasanya memakan waktu beberapa menit.'
      ],
      ok: 'Pasang sekarang'
    }).then(function (yes) { if (yes) start(); });
  }

  function start() {
    setBusy(true); postPending = true;
    resetRun(); showPanel('install');
    setHero('run', { title: 'Memasang versi ' + info.latest, text: 'Jangan tutup halaman ini sampai proses selesai.', from: info.current, to: info.latest });
    startPolling();
    api('run', 'POST').then(function (j) {
      postPending = false;
      finish(j);
    }).catch(function () {
      postPending = false; // koneksi terputus: terus pantau lewat progres
      $('heroText').textContent = 'Koneksi terputus, memantau proses di server\u2026';
    });
  }

  function finish(r) {
    if (!busy) return;
    stopPolling();
    var p = lastP || {};
    var files = r.files || p.files || [];
    var steps = r.steps || p.steps || [];
    renderRun({
      updated: p.updated || 1, started: p.started || 0, percent: r.ok ? 100 : (p.percent || 0),
      phase: r.ok ? 'done' : p.phase, ok: !!r.ok, steps: steps, files: files,
      same: (r.same !== undefined && r.same !== null) ? r.same : p.same,
      dl: p.dl, wi: p.wi, wt: p.wt, has_sql: p.has_sql
    });
    setBusy(false);
    var from = info ? info.current : '';
    if (r.ok) {
      setHero('done', { title: 'Pembaruan berhasil dipasang', text: 'Aplikasi sekarang berjalan di versi ' + r.version + '. Muat ulang halaman agar perubahan terlihat.', from: from, to: r.version, reload: true, check: 'Periksa lagi' });
    } else {
      var restored = steps.some(function (s) { return /dikembalikan otomatis/.test(s); });
      setHero('bad', { title: 'Pembaruan gagal', text: (r.error || 'Terjadi kesalahan saat memasang pembaruan.') + (restored ? ' File sudah dikembalikan otomatis dari cadangan.' : ''), check: 'Coba lagi' });
    }
    refreshData();
  }

  /* ---------- pemulihan ---------- */
  function rollback(b) {
    if (busy) return;
    ask({
      title: 'Kembalikan ke versi ' + b.from + '?',
      items: [
        'File yang diubah oleh pembaruan ' + b.to + ' dikembalikan ke versi ' + b.from + '.',
        'Database tidak ikut dikembalikan.',
        'Lakukan saat tidak ada ujian yang berlangsung.'
      ],
      ok: 'Kembalikan', danger: true
    }).then(function (yes) {
      if (!yes) return;
      setBusy(true); resetRun(); showPanel('rollback');
      setHero('run', { title: 'Mengembalikan ke versi ' + b.from, text: 'Mohon tunggu sebentar.' });
      api('rollback', 'POST', { file: b.file }).then(function (j) {
        setBusy(false);
        $('log').textContent = (j.steps || []).join('\n');
        if (j.ok) setHero('done', { title: 'Versi dikembalikan', text: 'Aplikasi kembali ke versi ' + j.version + '. Muat ulang halaman agar perubahan terlihat.', reload: true, check: 'Periksa lagi' });
        else setHero('bad', { title: 'Gagal mengembalikan versi', text: j.error || 'Terjadi kesalahan.', check: 'Periksa lagi' });
        refreshData();
      }).catch(function (e) {
        setBusy(false);
        setHero('bad', { title: 'Gagal mengembalikan versi', text: e.message, check: 'Periksa lagi' });
      });
    });
  }

  /* ---------- mulai ---------- */
  window.addEventListener('beforeunload', function (e) { if (busy) { e.preventDefault(); e.returnValue = ''; } });
  $('btnCheck').onclick = function () { check(true); };
  $('btnRun').onclick = run;
  $('btnReload').onclick = function () { window.location.reload(); };
  setHero('checking', { title: 'Memeriksa pembaruan\u2026', text: 'Menghubungi server pembaruan.' });
  buildSteps();

  // Jika ada update yang sedang berjalan (misalnya halaman dibuka ulang), sambung ke progresnya
  api('progress', 'GET').then(function (j) {
    if (j.ok && j.progress && j.progress.running && j.progress.updated !== undefined) {
      info = { current: $('cur').textContent, latest: '' };
      setBusy(true); postPending = false; seenRunning = true;
      showPanel('install');
      setHero('run', { title: 'Pembaruan sedang berjalan', text: 'Memantau proses yang sedang dikerjakan server.' });
      startPolling();
    } else check(false);
  }).catch(function () { check(false); });
})();
</script>
</body>
</html>