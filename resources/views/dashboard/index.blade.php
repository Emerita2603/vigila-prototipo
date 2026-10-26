<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIGILA | Panel de videovigilancia</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #172033;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 240px;
            padding: 28px 18px;
            background: #111827;
            color: white;
        }

        .brand {
            font-size: 25px;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 38px;
        }

        .nav-title {
            font-size: 11px;
            color: #9ca3af;
            margin: 24px 10px 12px;
            text-transform: uppercase;
        }

        .nav-item {
            display: block;
            padding: 13px 12px;
            margin-bottom: 6px;
            border-radius: 7px;
            color: #d1d5db;
            text-decoration: none;
            font-size: 14px;
        }

        .nav-item:hover { background: #1f2937; }
        .nav-item.active { background: #2563eb; color: white; }

        .main {
            margin-left: 240px;
            padding: 32px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 32px;
        }

        .topbar h1 {
            margin: 0 0 8px;
            font-size: 27px;
        }

        .muted { color: #64748b; font-size: 14px; }

        .status {
            padding: 10px 14px;
            border-radius: 8px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 13px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .card, .panel {
            background: white;
            padding: 22px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }

        .card-label { color: #64748b; font-size: 13px; }

        .card-value {
            font-size: 30px;
            font-weight: bold;
            margin: 15px 0 8px;
        }

        .card-note { font-size: 12px; color: #64748b; }

        .section-title {
            font-size: 18px;
            margin: 0 0 8px;
        }

        .camera-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-top: 20px;
        }

        .camera-card {
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #fff;
        }

        .camera-header {
            padding: 15px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .camera-header strong { font-size: 14px; }

        .camera-badge {
            padding: 6px 9px;
            border-radius: 20px;
            background: #fef3c7;
            color: #92400e;
            font-size: 11px;
            white-space: nowrap;
        }

        .camera-badge.online {
            background: #dcfce7;
            color: #166534;
        }

        .camera-badge.offline {
            background: #fee2e2;
            color: #991b1b;
        }

        .video-container {
            position: relative;
            background: #020617;
            aspect-ratio: 16 / 9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .video-container video {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #020617;
        }

        .video-message {
            padding: 20px;
            text-align: center;
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.6;
        }

        .video-message .camera-icon {
            display: block;
            font-size: 35px;
            margin-bottom: 10px;
        }

        .camera-footer {
            padding: 12px 16px;
            color: #64748b;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .empty {
            padding: 30px 10px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }

        .button {
            border: 0;
            border-radius: 7px;
            padding: 10px 13px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-size: 12px;
        }

        .button:hover { background: #1d4ed8; }

        footer {
            padding: 25px 0 5px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 1100px) {
            .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 700px) {
            .sidebar {
                position: static;
                width: 100%;
                padding: 20px;
            }

            .sidebar .nav-title { display: none; }

            .sidebar nav {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .nav-item { margin: 0; padding: 10px; }

            .main {
                margin-left: 0;
                padding: 20px 14px;
            }

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .cards, .camera-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">VIGILA</div>
        <div class="subtitle">Sistema de videovigilancia</div>

        <div class="nav-title">Principal</div>

        <nav>
            <a class="nav-item active" href="/">Panel principal</a>
            <a class="nav-item" href="#camaras">Cámaras</a>
            <a class="nav-item" href="#eventos">Eventos</a>
            <a class="nav-item" href="#sistema">Sistema</a>
        </nav>
    </aside>

    <main class="main">
        <header class="topbar">
            <div>
                <h1>Panel de control</h1>
                <div class="muted">
                    Resumen general del sistema de videovigilancia
                </div>
            </div>
            <div class="status" id="platform-status">
                ● Comprobando transmisión
            </div>
        </header>

        <section class="cards">
            <article class="card">
                <div class="card-label">Cámaras configuradas</div>
                <div class="card-value">1</div>
                <div class="card-note">Canal laptop configurado</div>
            </article>

            <article class="card">
                <div class="card-label">Cámaras reproduciendo</div>
                <div class="card-value" id="online-count">0</div>
                <div class="card-note" id="online-note">
                    Comprobando reproducción
                </div>
            </article>

            <article class="card">
                <div class="card-label">Eventos registrados</div>
                <div class="card-value">0</div>
                <div class="card-note">Historial pendiente de implementar</div>
            </article>

            <article class="card">
                <div class="card-label">Estado de la plataforma</div>
                <div class="card-value" style="font-size:22px">Activa</div>
                <div class="card-note">Panel web VIGILA</div>
            </article>
        </section>

        <section class="panel" id="camaras">
            <h2 class="section-title">Vista de cámaras</h2>
            <p class="muted">
                Transmisiones de vídeo en directo.
            </p>

            <div class="camera-grid">
                <article class="camera-card">
                    <div class="camera-header">
                        <strong>Cámara 01 · Laptop</strong>
                        <span class="camera-badge" id="camera-badge">
                            Conectando...
                        </span>
                    </div>

                    <div class="video-container">
                        <video id="camera-video"
                               controls
                               autoplay
                               muted
                               playsinline
                               preload="auto">
                            Tu navegador no admite la reproducción de vídeo.
                        </video>

                        <div class="video-message" id="video-message">
                            <span class="camera-icon">▣</span>
                            Conectando con la transmisión de la cámara...
                        </div>
                    </div>

                    <div class="camera-footer">
                        Fuente: MediaMTX · HLS · 640 × 360
                        <button class="button"
                                id="retry-button"
                                type="button"
                                style="float:right">
                            Reconectar
                        </button>
                    </div>
                </article>

                <article class="camera-card">
                    <div class="camera-header">
                        <strong>Cámara 02</strong>
                        <span class="camera-badge">Sin configurar</span>
                    </div>
                    <div class="video-container">
                        <div class="video-message">
                            <span class="camera-icon">▣</span>
                            Este canal estará disponible cuando se configure
                            otra fuente de vídeo.
                        </div>
                    </div>
                    <div class="camera-footer">Fuente no configurada</div>
                </article>
            </div>
        </section>

        <section class="panel" id="eventos" style="margin-top:24px">
            <h2 class="section-title">Eventos recientes</h2>
            <div class="empty">
                No hay eventos registrados. El historial se habilitará
                durante la siguiente etapa del desarrollo.
            </div>
        </section>

        <section class="panel" id="sistema" style="margin-top:24px">
            <h2 class="section-title">Estado del sistema</h2>
            <p class="muted">
                La reproducción depende de que la cámara publique vídeo
                y de que MediaMTX y Nginx estén disponibles.
            </p>
        </section>

        <footer>
            VIGILA · Sistema de videovigilancia
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/hls.js@1.6.13/dist/hls.min.js"></script>
    <script>
        (() => {
            const video = document.getElementById('camera-video');
            const message = document.getElementById('video-message');
            const badge = document.getElementById('camera-badge');
            const count = document.getElementById('online-count');
            const onlineNote = document.getElementById('online-note');
            const platformStatus = document.getElementById('platform-status');
            const retryButton = document.getElementById('retry-button');

            const streamUrl =
                'https://3.145.175.191/hls/laptop/index.m3u8';

            let hls = null;
            let started = false;

            function setStatus(state, text) {
                badge.textContent = text;
                badge.className = 'camera-badge';

                if (state === 'online') {
                    badge.classList.add('online');
                    count.textContent = '1';
                    onlineNote.textContent = 'Vídeo reproduciéndose';
                    platformStatus.textContent = '● Transmisión activa';
                    platformStatus.style.background = '#dcfce7';
                    platformStatus.style.color = '#166534';
                } else if (state === 'offline') {
                    badge.classList.add('offline');
                    count.textContent = '0';
                    onlineNote.textContent = 'Sin reproducción';
                    platformStatus.textContent = '● Revisar transmisión';
                    platformStatus.style.background = '#fee2e2';
                    platformStatus.style.color = '#991b1b';
                } else {
                    count.textContent = '0';
                    onlineNote.textContent = 'Esperando vídeo';
                    platformStatus.textContent = '● Conectando transmisión';
                    platformStatus.style.background = '#dbeafe';
                    platformStatus.style.color = '#1d4ed8';
                }
            }

            function showMessage(text) {
                message.textContent = text;
                message.style.display = 'block';
            }

            function startStream() {
                started = false;
                setStatus('loading', 'Conectando...');
                showMessage('Conectando con la transmisión de la cámara...');
                
                if (hls) {
                    hls.destroy();
                    hls = null;
                }

                video.pause();
                video.removeAttribute('src');
                video.load();

                if (window.Hls && Hls.isSupported()) {
                    hls = new Hls({
                        enableWorker: true,
                        lowLatencyMode: true,
                        liveSyncDurationCount: 2,
                        liveMaxLatencyDurationCount: 5,
                        maxLiveSyncPlaybackRate: 1.15,
                        backBufferLength: 10
                    });

                    hls.loadSource(streamUrl);
                    hls.attachMedia(video);

                    hls.on(Hls.Events.MANIFEST_PARSED, () => {
                        video.play().catch(() => {
                            showMessage(
                                'La transmisión está cargada. Pulsa Play para comenzar.'
                            );
                        });
                    });

                    hls.on(Hls.Events.ERROR, (event, data) => {
                        if (!data.fatal) return;

                        console.error('Error HLS:', data.type, data.details);
                        setStatus('offline', 'Error de conexión');

                        showMessage(
                            'No se pudo cargar el vídeo. Comprueba que FFmpeg ' +
                            'siga transmitiendo y pulsa Reconectar.'
                        );

                        if (hls) {
                            if (data.type === Hls.ErrorTypes.NETWORK_ERROR) {
                                hls.startLoad();
                            } else if (
                                data.type === Hls.ErrorTypes.MEDIA_ERROR
                            ) {
                                hls.recoverMediaError();
                            }
                        }
                    });
                } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    video.src = streamUrl;
                    video.play().catch(() => {
                        showMessage(
                            'La transmisión está cargada. Pulsa Play para comenzar.'
                        );
                    });
                } else {
                    setStatus('offline', 'Navegador incompatible');
                    showMessage(
                        'Este navegador no admite HLS directamente. ' +
                        'Prueba con Chrome actualizado y comprueba que ' +
                        'la conexión a Internet esté disponible.'
                    );
                }
            }

            video.addEventListener('playing', () => {
                started = true;
                setStatus('online', 'Reproduciendo');
                message.style.display = 'none';
            });

            video.addEventListener('waiting', () => {
                if (started) {
                    showMessage('Recibiendo vídeo...');
                }
            });

            video.addEventListener('pause', () => {
                if (started && video.ended === false) {
                    // La pausa manual no significa que la cámara esté desconectada.
                    badge.textContent = 'Pausado';
                }
            });

            video.addEventListener('error', () => {
                if (!window.Hls || !Hls.isSupported()) {
                    setStatus('offline', 'Error de vídeo');
                    showMessage(
                        'No se pudo reproducir el vídeo. Revisa la conexión.'
                    );
                }
            });

            retryButton.addEventListener('click', startStream);

            startStream();
        })();
    </script>
</body>
</html>
