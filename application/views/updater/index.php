<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pembaruan Aplikasi</title>
<style>
  :root { --bg:#f4f6f9; --card:#fff; --text:#1f2937; --muted:#6b7280; --line:#e5e7eb; --accent:#2563eb; --ok:#15803d; --bad:#b91c1c; --warn:#b45309; }
  @media (prefers-color-scheme: dark) {
    :root { --bg:#111827; --card:#1f2937; --text:#f3f4f6; --muted:#9ca3af; --line:#374151; --accent:#3b82f6; --ok:#4ade80; --bad:#f87171; --warn:#fbbf24; }
  }
  * { box-sizing:border-box; }
  body { margin:0; font:15px/1.5 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; background:var(--bg); color:var(--text); }
  .wrap { max-width:760px; margin:0 auto; padding:24px 16px 48px; }
  h1 { font-size:22px; margin:0 0 16px; }
  .card { background:var(--card); border:1px solid var(--line); border-radius:12px; padding:20px; margin-bottom:16px; }
  .row { display:flex; gap:24px; flex-wrap:wrap; }
  .row > div { min-width:120px; }
  .lbl { font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:.04em; }
  .val { font-size:24px; font-weight:600; }
  .status { margin-top:14px; padding:10px 12px; border-radius:8px; border:1px solid var(--line); }
  .ok { color:var(--ok); } .bad { color:var(--bad); } .warn { color:var(--warn); }
  button { font:inherit; padding:9px 16px; border-radius:8px; border:1px solid var(--line); background:var(--card); color:var(--text); cursor:pointer; }
  button.primary { background:var(--accent); border-color:var(--accent); color:#fff; }
  button:disabled { opacity:.5; cursor:not-allowed; }
  .actions { display:flex; gap:8px; margin-top:16px; flex-wrap:wrap; }
  pre { margin:0; padding:12px; background:var(--bg); border:1px solid var(--line); border-radius:8px; white-space:pre-wrap; word-break:break-word; font:13px/1.5 ui-monospace,Consolas,monospace; max-height:280px; overflow:auto; }
  table { width:100%; border-collapse:collapse; font-size:14px; }
  th, td { text-align:left; padding:8px 6px; border-bottom:1px solid var(--line); }
  th { font-size:12px; color:var(--muted); font-weight:500; }
  h2 { font-size:16px; margin:0 0 12px; }
  .muted { color:var(--muted); }
  .hide { display:none; }
</style>
</head>
<body>
<div class="wrap">
  <h1>Pembaruan Aplikasi</h1>

  <div class="card">
    <div class="row">
      <div><div class="lbl">Versi terpasang</div><div class="val" id="cur"><?php echo htmlspecialchars($current); ?></div></div>
      <div><div class="lbl">Versi terbaru</div><div class="val" id="latest">-</div></div>
    </div>
    <div class="status" id="status">Memeriksa pembaruan...</div>
    <div id="changelogBox" class="hide" style="margin-top:12px">
      <div class="lbl">Catatan perubahan</div>
      <div id="changelog" style="white-space:pre-wrap"></div>
    </div>
    <div class="actions">
      <button id="btnCheck">Cek ulang</button>
      <button id="btnRun" class="primary" disabled>Update sekarang</button>
    </div>
  </div>

  <div class="card hide" id="logCard">
    <h2>Proses</h2>
    <pre id="log"></pre>
  </div>

  <div class="card">
    <h2>Backup &amp; pengembalian versi</h2>
    <div id="backups" class="muted">Belum ada backup.</div>
  </div>

  <p class="muted" style="font-size:13px">Jangan menjalankan update saat ujian sedang berlangsung. Pastikan koneksi internet stabil dan jangan tutup halaman selama proses berjalan.</p>
</div>

<script>
(function () {
  var BASE = <?php echo json_encode(site_url('updater')); ?>;
  var CSRF = { name: <?php echo json_encode($csrf_name); ?>, hash: <?php echo json_encode($csrf_hash); ?> };
  var $ = function (id) { return document.getElementById(id); };
  var busy = false;

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

  function setStatus(text, cls) {
    var s = $('status');
    s.textContent = text;
    s.className = 'status ' + (cls || '');
  }

  function showLog(lines) {
    $('logCard').classList.remove('hide');
    $('log').textContent = (lines || []).join('\n');
  }

  function renderBackups(list) {
    var box = $('backups');
    if (!list || !list.length) { box.className = 'muted'; box.textContent = 'Belum ada backup.'; return; }
    box.className = '';
    var t = document.createElement('table');
    t.innerHTML = '<tr><th>Waktu</th><th>Dari &rarr; ke</th><th>Ukuran</th><th></th></tr>';
    list.forEach(function (b) {
      var tr = document.createElement('tr');
      [b.time, b.from + ' → ' + b.to, Math.max(1, Math.round(b.size / 1024)) + ' KB'].forEach(function (v) {
        var td = document.createElement('td'); td.textContent = v; tr.appendChild(td);
      });
      var td = document.createElement('td');
      var btn = document.createElement('button');
      btn.textContent = 'Kembalikan';
      btn.onclick = function () { rollback(b); };
      td.appendChild(btn); tr.appendChild(td); t.appendChild(tr);
    });
    box.innerHTML = ''; box.appendChild(t);
  }

  function check(force) {
    if (busy) return;
    $('btnRun').disabled = true;
    setStatus('Memeriksa pembaruan...', '');
    return api('check' + (force ? '?force=1' : ''), 'GET').then(function (j) {
      if (!j.ok) { setStatus(j.error || 'Gagal memeriksa.', 'bad'); return; }
      var i = j.info;
      $('cur').textContent = i.current;
      $('latest').textContent = i.latest;
      renderBackups(i.backups);
      if (i.changelog) { $('changelog').textContent = i.changelog; $('changelogBox').classList.remove('hide'); }
      if (i.blocked) { setStatus(i.blocked, 'warn'); }
      else if (i.available) { setStatus('Tersedia versi baru ' + i.latest + '.', 'warn'); $('btnRun').disabled = false; }
      else { setStatus('Aplikasi sudah versi terbaru.', 'ok'); }
    }).catch(function (e) { setStatus(e.message, 'bad'); });
  }

  function run() {
    if (busy) return;
    if (!confirm('Jalankan update sekarang?\n\nPastikan tidak ada ujian yang sedang berlangsung.')) return;
    busy = true; $('btnRun').disabled = true; $('btnCheck').disabled = true;
    setStatus('Update sedang berjalan, jangan tutup halaman ini...', 'warn');
    showLog(['Memulai...']);
    api('run', 'POST').then(function (j) {
      showLog(j.steps || []);
      if (j.ok) setStatus('Update berhasil ke versi ' + j.version + '. Muat ulang halaman aplikasi.', 'ok');
      else setStatus(j.error || 'Update gagal.', 'bad');
    }).catch(function (e) { setStatus(e.message, 'bad'); })
      .then(function () { busy = false; $('btnCheck').disabled = false; return check(true); });
  }

  function rollback(b) {
    if (busy) return;
    if (!confirm('Kembalikan aplikasi ke versi ' + b.from + '?\n\nDatabase tidak ikut dikembalikan.')) return;
    busy = true;
    api('rollback', 'POST', { file: b.file }).then(function (j) {
      showLog(j.steps || []);
      if (j.ok) setStatus('Berhasil dikembalikan ke versi ' + j.version + '.', 'ok');
      else setStatus(j.error || 'Gagal mengembalikan.', 'bad');
    }).catch(function (e) { setStatus(e.message, 'bad'); })
      .then(function () { busy = false; return check(true); });
  }

  $('btnCheck').onclick = function () { check(true); };
  $('btnRun').onclick = run;
  check(false);
})();
</script>
</body>
</html>
