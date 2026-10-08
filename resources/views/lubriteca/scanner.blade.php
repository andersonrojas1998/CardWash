<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Escáner QR — Lubriteca</title>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #12122a 0%, #1a1a3a 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 30px 16px;
            color: #fff;
        }

        .scanner-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .scanner-header .logo-circle {
            width: 64px;
            height: 64px;
            background: #c0392b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 1.8rem;
        }
        .scanner-header h2 {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .scanner-header p {
            font-size: .85rem;
            color: rgba(255,255,255,0.55);
            margin-top: 4px;
        }

        .scanner-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 20px;
            width: 100%;
            max-width: 420px;
        }

        #qr-reader {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }

        /* Override librería */
        #qr-reader video { border-radius: 10px; }
        #qr-reader__dashboard_section_csr button {
            background: #c0392b !important;
            color: #fff !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 8px 18px !important;
            font-weight: 700 !important;
            cursor: pointer !important;
        }
        #qr-reader__dashboard_section_fsr { display: none !important; }

        .scanner-status {
            margin-top: 16px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: .88rem;
            text-align: center;
            display: none;
        }
        .scanner-status.scanning {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.7);
            display: block;
        }
        .scanner-status.success {
            background: rgba(39,174,96,0.15);
            border: 1px solid rgba(39,174,96,0.4);
            color: #2ecc71;
            display: block;
        }
        .scanner-status.error {
            background: rgba(192,57,43,0.15);
            border: 1px solid rgba(192,57,43,0.4);
            color: #e74c3c;
            display: block;
        }

        .btn-toggle-camera {
            margin-top: 14px;
            width: 100%;
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.15);
            color: #fff;
            border-radius: 10px;
            padding: 10px;
            font-size: .88rem;
            cursor: pointer;
            transition: background .2s;
        }
        .btn-toggle-camera:hover { background: rgba(255,255,255,0.14); }

        .btn-back-pos {
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.5);
            font-size: .82rem;
            text-decoration: none;
            transition: color .2s;
        }
        .btn-back-pos:hover { color: #fff; text-decoration: none; }

        .result-redirect {
            margin-top: 14px;
            text-align: center;
            font-size: .82rem;
            color: rgba(255,255,255,0.5);
        }
        .result-redirect a {
            color: #f5a623;
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="scanner-header">
    <div class="logo-circle">&#128247;</div>
    <h2>Escáner QR Lubriteca</h2>
    <p>Apunta la cámara al QR del sticker para ver el historial del vehículo</p>
</div>

<div class="scanner-card">
    <div id="qr-reader"></div>

    <div class="scanner-status scanning" id="scanner-status">
        &#9654; Esperando código QR...
    </div>

    <div class="result-redirect" id="result-redirect" style="display:none;">
        QR detectado: <a href="#" id="result-link" target="_blank"></a>
    </div>

    <button class="btn-toggle-camera" id="btn-toggle-camera" style="display:none;">
        &#8635; Cambiar cámara
    </button>
</div>

<a href="/lubriteca" class="btn-back-pos">&#8592; Volver al POS</a>

<script>
var html5QrCode;
var currentCameraId = null;
var cameraList = [];
var cameraIndex = 0;

function onScanSuccess(decodedText, decodedResult) {
    var status = document.getElementById('scanner-status');
    var resultDiv = document.getElementById('result-redirect');
    var resultLink = document.getElementById('result-link');

    status.className = 'scanner-status success';
    status.innerHTML = '&#10003; QR detectado!';

    resultDiv.style.display = 'block';
    resultLink.href = decodedText;
    resultLink.textContent = decodedText;

    html5QrCode.stop();

    // Redirigir automáticamente después de 1 segundo
    setTimeout(function() {
        window.location.href = decodedText;
    }, 1000);
}

function startCamera(cameraId) {
    if (html5QrCode) {
        html5QrCode.stop().catch(function(){});
    }
    html5QrCode = new Html5Qrcode("qr-reader");
    html5QrCode.start(
        cameraId,
        { fps: 10, qrbox: { width: 220, height: 220 } },
        onScanSuccess,
        function(errorMessage) { /* silencioso */ }
    ).catch(function(err) {
        var status = document.getElementById('scanner-status');
        status.className = 'scanner-status error';
        status.innerHTML = '&#9888; No se pudo acceder a la cámara. Verifica los permisos.';
    });
}

Html5Qrcode.getCameras().then(function(devices) {
    if (devices && devices.length) {
        cameraList = devices;
        currentCameraId = devices.length > 1 ? devices[1].id : devices[0].id;
        startCamera(currentCameraId);

        if (devices.length > 1) {
            document.getElementById('btn-toggle-camera').style.display = 'block';
        }
    } else {
        var status = document.getElementById('scanner-status');
        status.className = 'scanner-status error';
        status.innerHTML = '&#9888; No se encontraron cámaras en este dispositivo.';
    }
}).catch(function(err) {
    var status = document.getElementById('scanner-status');
    status.className = 'scanner-status error';
    status.innerHTML = '&#9888; Error al acceder a la cámara: ' + err;
});

document.getElementById('btn-toggle-camera').addEventListener('click', function() {
    cameraIndex = (cameraIndex + 1) % cameraList.length;
    currentCameraId = cameraList[cameraIndex].id;
    startCamera(currentCameraId);
});
</script>
</body>
</html>
