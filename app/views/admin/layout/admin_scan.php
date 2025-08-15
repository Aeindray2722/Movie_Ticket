<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Scan Ticket</title>
  <style>
    #status {
      margin-top: 12px;
      font-weight: bold;
    }
    video, canvas {
      width: 100%;
      max-width: 520px;
      border-radius: 12px;
    }
    .ok { color: green; }
    .err { color: crimson; }
  </style>
</head>
<body>
  <h2>Scan Ticket (Code128)</h2>

  <video id="video" autoplay playsinline></video>
  <div id="status">Initializing camera…</div>

  <script src="https://cdn.jsdelivr.net/npm/@zxing/browser@0.1.5/umd/index.min.js"></script>
  <script>
    const videoElem = document.getElementById('video');
    const statusEl = document.getElementById('status');
    const reader = new ZXing.BrowserMultiFormatReader();

    async function start() {
      try {
        const controls = await reader.decodeFromVideoDevice(null, videoElem, (result, err) => {
          if (result) {
            const code = result.getText();
            statusEl.textContent = 'Code detected: ' + code + ' … verifying';
            verify(code).then(ok => {
              if (ok) {
                statusEl.className = 'ok';
                statusEl.innerHTML += '<br>✓ Checked-in success';
                controls.stop(); // stop camera after success
              } else {
                statusEl.className = 'err';
              }
            });
          }
          // log errors except NotFoundException
          if (err && !(err instanceof ZXing.NotFoundException)) {
            console.error(err);
          }
        });
      } catch (e) {
        statusEl.textContent = 'Camera init error: ' + e.message;
        statusEl.className = 'err';
      }
    }

    async function verify(code) {
      try {
        const form = new FormData();
        form.append('code', code);
        const res = await fetch('/booking/verifyByBarcode', { method: 'POST', body: form });
        const json = await res.json();
        if (json.success) {
          statusEl.innerHTML = `✓ ${json.message}<br>
            Booking #${json?.data?.booking_id || ''} / ${json?.data?.movie || ''} /
            ${json?.data?.date || ''}<br>Seats: ${json?.data?.seats || ''}`;
          return true;
        } else {
          statusEl.textContent = '× ' + json.message;
          return false;
        }
      } catch (e) {
        statusEl.textContent = 'Network error';
        return false;
      }
    }

    start();
  </script>
</body>
</html>
