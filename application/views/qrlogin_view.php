!DOCTYPE html
html lang=id
head
meta charset=utf-8
titleQR Login Siswatitle
meta name=viewport content=width=device-width, initial-scale=1
style
  body{font-familySegoe UI,Arial,sans-serif;background#f1f5f9;margin0;
       displayflex;align-itemscenter;justify-contentcenter;min-height100vh}
  .card{background#fff;border-radius16px;padding32px 40px;text-aligncenter;
        box-shadow0 8px 30px rgba(0,0,0,.1);max-width420px}
  h2{margin0 0 4px} p{color#64748b;margin4px 0 20px}
  #qr{displayinline-block;padding12px;background#fff;border1px solid #e2e8f0;border-radius12px}
  .url{margin-top16px;font-weight600;color#0f766e;word-breakbreak-all}
  button{margin-top18px;padding10px 20px;border0;border-radius8px;
         background#20c997;color#fff;font-weight600;cursorpointer}
  @media print{button{displaynone}}
style
head
body
  div class=card
    h2Login Siswah2
    pScan QR ini dengan kamera HP untuk membuka halaman ujianp
    div id=qrdiv
    div class=url= htmlspecialchars($url) div
    button onclick=window.print()Cetakbutton
  div

  script src== base_url('assetsjsqrcode.min.js') script
  script
    new QRCode(document.getElementById('qr'), {
      text = json_encode($url) ,
      width 260, height 260,
      correctLevel QRCode.CorrectLevel.M
    });
  script
body
html