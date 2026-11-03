<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Pantauan Ujian Langsung</title>
<style>
:root{--bg:#0a1024;--panel:#111a38;--line:#22305f;--text:#eef2ff;--mute:#8d9bc8;--live:#34d399;--done:#60a5fa;--gold:#fbbf24;--warn:#fbbf24;--crit:#fb7185}
*{box-sizing:border-box}
html,body{margin:0;background:var(--bg);color:var(--text);font-family:'Bahnschrift','Segoe UI Variable Display','Segoe UI',system-ui,-apple-system,Roboto,sans-serif}
body{min-height:100vh;background:radial-gradient(60em 28em at 90% -12%,rgba(59,91,219,.30),transparent 62%),var(--bg)}
.wrap{max-width:1500px;margin:0 auto;padding:1.4rem 1.4rem 2rem}
header{display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-end;justify-content:space-between;margin-bottom:1rem}
h1{margin:0;font-size:clamp(1.4rem,2.6vw,2.2rem);line-height:1.1}
.live{display:inline-flex;align-items:center;gap:.5em;margin-left:.7rem;padding:.3em .8em;border-radius:99em;background:rgba(52,211,153,.14);color:var(--live);font-size:.85rem;font-weight:700;vertical-align:middle}
.live i{width:.6em;height:.6em;border-radius:50%;background:var(--live);animation:ping 1.8s infinite}
.live.off{background:rgba(251,113,133,.14);color:var(--crit)}.live.off i{background:var(--crit);animation:none}
@keyframes ping{0%{box-shadow:0 0 0 0 rgba(52,211,153,.75)}80%,100%{box-shadow:0 0 0 .6em rgba(52,211,153,0)}}
.sync{color:var(--mute);font-size:.85rem;margin-top:.4rem}
.filters{display:flex;flex-wrap:wrap;gap:.7rem}
.filters input,.filters select{font:inherit;font-size:.95rem;color:var(--text);background:#0f1830;border:1px solid var(--line);border-radius:.6rem;padding:.5rem .8rem;min-width:10rem;outline:none}
.filters input:focus,.filters select:focus{border-color:#5b7cfa;box-shadow:0 0 0 .2rem rgba(91,124,250,.25)}
.stats{display:grid;grid-template-columns:repeat(4,1fr);background:var(--panel);border:1px solid var(--line);border-radius:1rem;margin-bottom:1rem}
.stat{padding:.8rem 1.2rem;border-left:1px solid var(--line);min-width:0}.stat:first-child{border-left:0}
.stat b{display:block;font-size:clamp(1.6rem,3vw,2.4rem);line-height:1.05;font-variant-numeric:tabular-nums}
.stat span{color:var(--mute);font-size:.9rem}
.s1 b{color:var(--live)}.s2 b{color:var(--done)}.s4 b{color:var(--gold)}
.cols,.row{display:grid;align-items:center;column-gap:1rem;grid-template-columns:minmax(12rem,2.4fr) 5.5rem minmax(7rem,1.2fr) minmax(9rem,1.3fr) 6rem 3.2rem 3.2rem 4.4rem}
.cols{padding:0 1.2rem 0 1.4rem;color:var(--mute);font-size:.82rem;font-weight:600;margin-bottom:.4rem}
.cols div:nth-child(2),.cols div:nth-child(n+5){text-align:center}
.list{display:flex;flex-direction:column;gap:.5rem}
.row{position:relative;padding:.7rem 1.2rem .7rem 1.4rem;background:var(--panel);border:1px solid var(--line);border-radius:.9rem;--stripe:var(--done)}
.row::before{content:'';position:absolute;left:0;top:.6rem;bottom:.6rem;width:.28rem;border-radius:0 .3rem .3rem 0;background:var(--stripe)}
.row.aktif{--stripe:var(--live)}
.nm{display:flex;align-items:center;gap:.7rem;min-width:0}
.nm img{width:2.4rem;height:2.4rem;border-radius:50%;object-fit:cover;background:#1b2650;border:2px solid #2b3b78;flex:0 0 auto}
.nm span{font-size:1.1rem;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.kelas{text-align:center;font-weight:600}
.mapel{color:#7dd3fc;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.prog{display:flex;align-items:center;gap:.7rem}
.bar{flex:1;height:.8rem;border-radius:1rem;background:#1c2954;overflow:hidden}
.bar i{display:block;position:relative;height:100%;width:0;overflow:hidden;border-radius:1rem;background:linear-gradient(90deg,#3b82f6,#22d3ee 55%,#34d399);transition:width 1.1s cubic-bezier(.2,.8,.2,1)}
.row.aktif .bar i::after{content:'';position:absolute;inset:0;background:linear-gradient(100deg,transparent 30%,rgba(255,255,255,.5) 50%,transparent 70%);transform:translateX(-100%);animation:shine 2.6s ease-in-out infinite}
@keyframes shine{0%{transform:translateX(-100%)}55%,100%{transform:translateX(100%)}}
.prog b{width:3rem;text-align:right;font-variant-numeric:tabular-nums}
.c{text-align:center;font-variant-numeric:tabular-nums}
.time{font-size:1.2rem;font-weight:700;color:#e8fff6}
.time.warn{color:var(--warn)}.time.crit{color:var(--crit);animation:blink 1s steps(2,start) infinite}
.time.end{font-size:.95rem;font-weight:600;color:var(--done)}.time.over{font-size:.95rem;font-weight:600;color:var(--crit)}
@keyframes blink{50%{opacity:.35}}
.ok{color:#6ee7b7;font-size:1.2rem;font-weight:700}.bad{color:#fda4af;font-size:1.2rem;font-weight:700}
.skor{font-size:1.7rem;font-weight:800;line-height:1}
.skor.hi{color:#34d399}.skor.mid{color:var(--gold)}.skor.lo{color:var(--crit)}
.row.flash{animation:flash 1.8s ease-out}
@keyframes flash{0%{box-shadow:0 0 0 .18rem rgba(125,211,252,.9),0 0 1.6rem rgba(125,211,252,.45)}100%{box-shadow:0 0 0 0 transparent}}
.empty{display:none;text-align:center;color:var(--mute);padding:3rem 1rem;font-size:1.1rem;line-height:1.6}
.note{margin-top:1.4rem;color:var(--mute);font-size:.8rem;line-height:1.5}
.lbl{display:none}

@media (max-width:820px){
  .wrap{padding:1rem .8rem 2rem}
  .stats{grid-template-columns:1fr 1fr}.stat:nth-child(3){border-left:0}.stat:nth-child(n+3){border-top:1px solid var(--line)}
  .filters{width:100%}.filters>*{flex:1 1 9rem}
  .cols{display:none}
  .nm{grid-area:nama}.row>.kelas{grid-area:kelas}.row>.mapel{grid-area:mapel}.prog{grid-area:prog}.time{grid-area:time}.ok{grid-area:benar}.bad{grid-area:salah}.skor{grid-area:skor}
  .row{grid-template-columns:repeat(4,1fr);row-gap:.55rem;padding:.8rem .9rem .8rem 1.1rem;
       grid-template-areas:"nama nama nama nama" "kelas kelas mapel mapel" "prog prog prog prog" "time benar salah skor"}
  .kelas{text-align:left;color:var(--mute)}.mapel{text-align:right}
  .lbl{display:block;font-size:.68rem;font-weight:600;color:var(--mute);margin-bottom:.1rem}
  .time.end,.time.over{font-size:.85rem}
}
@media (prefers-reduced-motion:reduce){*,*::before,*::after{animation:none!important;transition:none!important}}
</style>
</head>
<body>
<div class="wrap">
  <header>
    <div>
      <h1>Pantauan Ujian Langsung <span class="live" id="live"><i></i><em style="font-style:normal" id="live-t">Langsung</em></span></h1>
      <div class="sync" id="sync">Menghubungkan...</div>
    </div>
    <div class="filters">
      <input type="search" id="q" placeholder="Cari nama siswa" aria-label="Cari nama siswa">
      <select id="fk" aria-label="Kelas"></select>
      <select id="fm" aria-label="Mata pelajaran"></select>
    </div>
  </header>

  <section class="stats">
    <div class="stat s1"><b id="s-aktif">0</b><span>Sedang mengerjakan</span></div>
    <div class="stat s2"><b id="s-selesai">0</b><span>Sudah selesai</span></div>
    <div class="stat s3"><b id="s-avg">0.0</b><span>Rata-rata skor</span></div>
    <div class="stat s4"><b id="s-top">0</b><span>Skor tertinggi</span></div>
  </section>

  <div class="cols"><div>Nama</div><div>Kelas</div><div>Mata pelajaran</div><div>Progres</div><div>Sisa waktu</div><div>Benar</div><div>Salah</div><div>Skor</div></div>
  <div class="list" id="list"></div>
  <div class="empty" id="empty">Belum ada peserta yang mengerjakan ujian.<br>Data muncul otomatis saat siswa mulai.</div>

  <p class="note">Halaman ini diperbarui otomatis tiap 5 detik. Nama disingkat demi menjaga privasi siswa. Mohon tidak membagikan tautan ini kepada pihak lain.</p>
</div>

<script>
(function(){
  var URL_DATA = <?= json_encode($data_url) ?>;
  var INTERVAL = 5000;
  var $ = function(i){return document.getElementById(i)};
  var list=$('list'), empty=$('empty'), q=$('q'), fk=$('fk'), fm=$('fm');
  var rows=[], prev={}, first=true, offset=0, lastOk=0, failed=0, srcK='', srcM='';
  var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  var pad=function(n){return n<10?'0'+n:''+n};
  var setText=function(el,v){v=String(v);if(el.textContent!==v)el.textContent=v};
  var clock=function(d){return pad(d.getHours())+':'+pad(d.getMinutes())+':'+pad(d.getSeconds())};

  function build(){
    var el=document.createElement('div'); el.className='row';
    el.innerHTML='<div class="nm"><img alt=""><span></span></div><div class="kelas"></div><div class="mapel"></div>'+
      '<div class="prog"><div class="bar"><i></i></div><b></b></div>'+
      '<div class="c time"><span class="lbl">Sisa waktu</span><span class="v"></span></div>'+
      '<div class="c ok"><span class="lbl">Benar</span><span class="v"></span></div>'+
      '<div class="c bad"><span class="lbl">Salah</span><span class="v"></span></div>'+
      '<div class="c skor"><span class="lbl">Skor</span><span class="v"></span></div>';
    el._ = {img:el.querySelector('img'),nm:el.querySelector('.nm span'),kelas:el.querySelector('.kelas'),mapel:el.querySelector('.mapel'),
      bar:el.querySelector('.bar i'),pct:el.querySelector('.prog b'),time:el.querySelector('.time'),tv:el.querySelector('.time .v'),
      ok:el.querySelector('.ok .v'),bad:el.querySelector('.bad .v'),skor:el.querySelector('.skor'),sv:el.querySelector('.skor .v')};
    return el;
  }
  function fill(el,r,isNew){
    var c=el._;
    el.classList.toggle('aktif',r.status==='aktif');
    setText(c.nm,r.nama); c.nm.title=r.nama;
    if(c.img.getAttribute('src')!==r.avatar)c.img.setAttribute('src',r.avatar);
    setText(c.kelas,r.kelas); setText(c.mapel,r.mapel);
    setText(c.pct,Math.round(r.progress)+'%');
    setText(c.ok,r.benar); setText(c.bad,r.salah); setText(c.sv,r.skor);
    c.skor.className='c skor '+(r.skor>=75?'hi':(r.skor>=50?'mid':'lo'));
    el.setAttribute('data-status',r.status); el.setAttribute('data-selesai',r.selesai);
    var w=Math.max(0,Math.min(100,r.progress))+'%';
    if(isNew&&!reduce){c.bar.style.width='0%';requestAnimationFrame(function(){requestAnimationFrame(function(){c.bar.style.width=w})})}
    else c.bar.style.width=w;
  }

  function syncSel(sel,vals,label,key){
    var html='<option value="">'+label+'</option>'+vals.map(function(v){
      return '<option value="'+v.replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;')+'">'+v.replace(/&/g,'&amp;').replace(/</g,'&lt;')+'</option>'}).join('');
    if(key!==html){var cur=sel.value;sel.innerHTML=html;sel.value=cur}
    return html;
  }

  function render(){
    var kelasSet={},mapelSet={};
    rows.forEach(function(r){kelasSet[r.kelas]=1;mapelSet[r.mapel]=1});
    srcK=syncSel(fk,Object.keys(kelasSet).sort(),'Semua kelas',srcK);
    srcM=syncSel(fm,Object.keys(mapelSet).sort(),'Semua mapel',srcM);

    var term=q.value.trim().toLowerCase(), vis=rows.filter(function(r){
      return (!fk.value||r.kelas===fk.value)&&(!fm.value||r.mapel===fm.value)&&(!term||r.nama.toLowerCase().indexOf(term)>-1)});

    var exist={},seen={},next={},i;
    for(i=0;i<list.children.length;i++)exist[list.children[i].getAttribute('data-id')]=list.children[i];
    var aktif=0,selesai=0,sum=0,max=0;
    vis.forEach(function(r,i){
      var el=exist[r.id],isNew=!el,key=JSON.stringify(r);seen[r.id]=1;
      if(isNew){el=build();el.setAttribute('data-id',r.id)}
      if(isNew||prev[r.id]!==key){
        fill(el,r,isNew);
        if(!isNew&&!first&&!reduce){el.classList.remove('flash');void el.offsetWidth;el.classList.add('flash')}
      }
      var cur=list.children[i]; if(cur!==el)list.insertBefore(el,cur||null);
      next[r.id]=key;
      if(r.status==='aktif')aktif++;else selesai++;
      sum+=r.skor; if(r.skor>max)max=r.skor;
    });
    for(var id in exist)if(!seen[id]&&exist[id].parentNode)list.removeChild(exist[id]);
    prev=next; first=false;
    list.style.display=vis.length?'flex':'none';
    empty.style.display=vis.length?'none':'block';
    setText($('s-aktif'),aktif); setText($('s-selesai'),selesai);
    setText($('s-avg'),vis.length?(Math.round(sum/vis.length*10)/10).toFixed(1):'0.0');
    setText($('s-top'),vis.length?Math.round(max*10)/10:'0');
    tick();
  }

  function tick(){
    var now=Math.floor(Date.now()/1000)+offset;
    for(var i=0;i<list.children.length;i++){
      var el=list.children[i],t=el._.time,v=el._.tv,txt,cls='c time';
      var st=el.getAttribute('data-status'),end=parseInt(el.getAttribute('data-selesai'),10)||0;
      if(st==='selesai'){txt='Selesai';cls+=' end'}
      else if(!end){txt='--:--'}
      else{var s=end-now;
        if(s>0){var h=Math.floor(s/3600),m=Math.floor(s%3600/60);txt=(h>0?pad(h)+':':'')+pad(m)+':'+pad(s%60);
          if(s<=60)cls+=' crit';else if(s<=300)cls+=' warn'}
        else{txt='Waktu habis';cls+=' over'}}
      setText(v,txt); if(t.className!==cls)t.className=cls;
    }
    var off=failed>=2||(lastOk&&Date.now()-lastOk>20000);
    $('live').className='live'+(off?' off':''); setText($('live-t'),off?'Terputus':'Langsung');
    setText($('sync'),lastOk?(off?'Koneksi terputus, mencoba lagi. Data terakhir pukul ':'Diperbarui pukul ')+clock(new Date(lastOk)):'Menghubungkan...');
  }

  function ambil(){
    fetch(URL_DATA,{cache:'no-store'}).then(function(r){
      if(!r.ok)throw new Error(r.status); return r.json();
    }).then(function(d){
      offset=d.now-Math.floor(Date.now()/1000); rows=d.rows||[]; lastOk=Date.now(); failed=0; render();
    }).catch(function(){failed++;tick()});
  }

  [q,fk,fm].forEach(function(e){e.addEventListener(e===q?'input':'change',function(){first=true;render()})});
  setInterval(tick,1000); setInterval(ambil,INTERVAL); ambil();
})();
</script>
</body>
</html>