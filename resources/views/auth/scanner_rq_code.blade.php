<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, viewport-fit=cover">
    <title>AFRICTECHAPP - QR CODE (AUTO-DÉMARRAGE)</title>
    <link rel="icon" type="image/png" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23800020'%3E%3Cpath d='M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z'/%3E%3C/svg%3E">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0a192f;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .header {
            background: #800020 !important;
            padding: 12px 20px;
            border-bottom: 3px solid #6c757d !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header__logo h1 {
            margin: 0;
            font-size: clamp(1rem, 5vw, 1.2rem);
        }

        .header__logo h1 a {
            color: white;
            text-decoration: none;
        }

        .header__logo p {
            font-size: clamp(0.55rem, 3vw, 0.65rem);
            color: rgba(255, 255, 255, 0.9);
            margin-top: 3px;
            letter-spacing: 1.5px;
        }

        .qr-reader-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .qr-card {
            width: 100%;
            max-width: 700px;
            background: white;
            border-radius: 20px;
            padding: clamp(15px, 5vw, 30px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin: 0 auto;
        }

        .qr-card h2 {
            font-size: clamp(1.3rem, 6vw, 1.8rem);
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 10px;
            position: relative;
            padding-bottom: 15px;
        }

        .qr-card h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #800020;
            border-radius: 2px;
        }

        .qr-card p {
            color: #555;
            margin: 15px 0 20px;
            font-size: clamp(0.85rem, 4vw, 0.95rem);
        }

        #qr-reader {
            width: 100%;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        #qr-reader video,
        #qr-reader canvas {
            width: 100% !important;
            height: auto !important;
            object-fit: cover;
        }

        .btn-scan {
            background: #800020 !important;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 50px;
            font-weight: 600;
            font-size: clamp(0.9rem, 4vw, 1rem);
            cursor: pointer;
            margin-top: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-width: 200px;
            justify-content: center;
        }

        .btn-scan:hover {
            background: #5a0017 !important;
            transform: translateY(-2px);
        }

        .info-message {
            margin-top: 25px;
            padding: 12px;
            border-radius: 10px;
            font-size: clamp(0.8rem, 3.5vw, 0.9rem);
            font-weight: 500;
            word-break: break-word;
        }

        .info-message.success {
            background: #e6ffe6;
            color: #28a745;
            border-left: 4px solid #28a745;
        }

        .info-message.error {
            background: #ffe6e6;
            color: #800020;
            border-left: 4px solid #800020;
        }

        .info-message.info {
            background: #e6f3ff;
            color: #0a5c8e;
            border-left: 4px solid #0a5c8e;
        }

        #footer {
            background: #800020 !important;
            padding: 12px 20px;
            border-top: 3px solid #6c757d !important;
            text-align: center;
            color: white;
            font-size: clamp(0.6rem, 3vw, 0.7rem);
        }

        @media (max-width: 480px) {
            .qr-reader-container {
                padding: 15px;
            }
            .btn-scan {
                min-width: 160px;
                padding: 10px 18px;
            }
        }

        @media (max-height: 500px) and (orientation: landscape) {
            .qr-reader-container {
                padding: 10px;
            }
            .qr-card {
                padding: 15px;
            }
            .qr-card h2 {
                margin-bottom: 5px;
                padding-bottom: 10px;
            }
            .qr-card p {
                margin: 8px 0 12px;
            }
            .btn-scan {
                margin-top: 12px;
                padding: 8px 20px;
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0a192f;
        }
        ::-webkit-scrollbar-thumb {
            background: #800020;
            border-radius: 4px;
        }
    </style>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
</head>

<body>

    <header class="header">
        <div class="header__logo">
            <h1><a href="#">📱 CONTROLAPP</a></h1>
            <p>ALL IN ONE - LECTEUR QR CODE</p>
        </div>
    </header>

    <div class="qr-reader-container">
        <div class="qr-card">
            <h2>📷 Scanner QR Code</h2>
            <p>Cadre très large – placez le code au centre</p>
            
            <div id="qr-reader"></div>
            
            <div class="controls">
                <button class="btn-scan" id="start-scan-btn">⏹ Arrêter la caméra</button>
            </div>
            
            <div id="info-message" class="info-message info">
                🔍 Démarrage automatique de la caméra arrière...
            </div>
        </div>
    </div>

    <div id="footer">CONTROLAPP © 2026 - Lecteur QR code</div>

    <script>
        let html5QrCode = null;
        let isScanning = false;
        let autoStartDone = false; // éviter double appel
        const qrReaderElement = document.getElementById("qr-reader");
        const infoDiv = document.getElementById("info-message");
        const startBtn = document.getElementById("start-scan-btn");

        function showMessage(message, type = "info") {
            infoDiv.textContent = message;
            infoDiv.className = "info-message " + type;
            if (type === "error" || type === "info") {
                setTimeout(() => {
                    if (infoDiv.className !== "info-message success") {
                        infoDiv.textContent = "📷 Caméra prête – Scannez un QR code";
                        infoDiv.className = "info-message info";
                    }
                }, 5000);
            }
        }

        async function stopScanning() {
            if (html5QrCode && isScanning) {
                try {
                    await html5QrCode.stop();
                    isScanning = false;
                } catch (err) {
                    console.warn(err);
                }
            }
        }

        async function startScanning() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                showMessage("❌ Votre navigateur ne supporte pas l'accès à la caméra.", "error");
                return false;
            }

            if (html5QrCode) {
                await stopScanning();
                if (qrReaderElement) qrReaderElement.innerHTML = "";
            }

            // Cadre très grand : 90% de la plus petite dimension
            const qrboxFunction = (viewfinderWidth, viewfinderHeight) => {
                const minDimension = Math.min(viewfinderWidth, viewfinderHeight);
                const size = Math.floor(minDimension * 0.9);
                return { width: size, height: size };
            };

            const config = {
                fps: 10,
                qrbox: qrboxFunction,
                aspectRatio: 1.0,
                rememberLastUsedCamera: false,
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            };

            html5QrCode = new Html5Qrcode("qr-reader");
            
            try {
                await html5QrCode.start(
                    { facingMode: { exact: "environment" } },
                    config,
                    (decodedText) => handleQrCode(decodedText),
                    (errorMessage) => {}
                );
                isScanning = true;
                showMessage("✅ Caméra arrière active – Grand cadre", "success");
                startBtn.textContent = "⏹ Arrêter la caméra";
                return true;
            } catch (err) {
                try {
                    await html5QrCode.start(
                        { facingMode: "environment" },
                        config,
                        (decodedText) => handleQrCode(decodedText),
                        (errorMessage) => {}
                    );
                    isScanning = true;
                    showMessage("✅ Caméra arrière active", "success");
                    startBtn.textContent = "⏹ Arrêter la caméra";
                    return true;
                } catch (err2) {
                    let errorMsg = "Impossible d'utiliser la caméra arrière. ";
                    if (err2.name === "NotAllowedError") {
                        errorMsg += "Autorisez l'accès à la caméra.";
                    } else if (err2.name === "NotFoundError") {
                        errorMsg += "Aucune caméra arrière détectée.";
                    } else {
                        errorMsg += err2.message;
                    }
                    showMessage(errorMsg, "error");
                    startBtn.textContent = "▶ Démarrer la caméra";
                    html5QrCode = null;
                    return false;
                }
            }
        }

        function handleQrCode(qrText) {
            stopScanning().catch(e => console.warn(e));
            isScanning = false;
            startBtn.textContent = "▶ Démarrer la caméra";

            let url = qrText.trim();
            let isValidUrl = false;
            try {
                if (!url.startsWith("http://") && !url.startsWith("https://")) {
                    if (url.match(/^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}(:\d+)?(\/.*)?$/)) {
                        url = "https://" + url;
                    } else {
                        throw new Error("Pas une URL");
                    }
                }
                const testUrl = new URL(url);
                isValidUrl = (testUrl.protocol === "http:" || testUrl.protocol === "https:");
            } catch (e) {
                isValidUrl = false;
            }

            if (isValidUrl) {
                showMessage(`✅ Redirection vers : ${url}`, "success");
                setTimeout(() => {
                    window.location.href = url;
                }, 1000);
            } else {
                showMessage(`⚠️ QR code invalide (pas d'URL). Texte : "${qrText.substring(0, 100)}"`, "error");
                setTimeout(() => {
                    if (!isScanning && startBtn.textContent === "▶ Démarrer la caméra") {
                        showMessage("🔍 Cliquez sur 'Démarrer' pour relancer le scan", "info");
                    }
                }, 2000);
            }
        }

        // Gestion du bouton : arrêter / redémarrer
        startBtn.addEventListener("click", async () => {
            if (isScanning) {
                await stopScanning();
                isScanning = false;
                startBtn.textContent = "▶ Démarrer la caméra";
                showMessage("🔴 Caméra arrêtée.", "info");
                if (qrReaderElement) qrReaderElement.innerHTML = "";
                html5QrCode = null;
            } else {
                await startScanning();
            }
        });

        // Auto-démarrage au chargement de la page
        window.addEventListener("load", async () => {
            // Petit délai pour que l'élément #qr-reader soit bien prêt
            setTimeout(async () => {
                if (!autoStartDone) {
                    autoStartDone = true;
                    await startScanning();
                }
            }, 500);
        });

        window.addEventListener("beforeunload", async () => {
            if (html5QrCode && isScanning) {
                try { await html5QrCode.stop(); } catch(e) {}
            }
        });

        // Réajuster si l'orientation change
        window.addEventListener("resize", () => {
            if (isScanning && html5QrCode) {
                stopScanning().then(() => {
                    startScanning();
                });
            }
        });
    </script>
</body>

</html>