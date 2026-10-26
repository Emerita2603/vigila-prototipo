# VIGILA — Sistema de vigilancia en tiempo real

Prototipo web para visualizar una transmisión de video en vivo desde una cámara conectada a un equipo remoto. El sistema utiliza Laravel para la interfaz web, MediaMTX para recibir y distribuir el video, FFmpeg para transmitir desde la cámara y HLS.js para reproducir el contenido en el navegador.

> **Estado:** prototipo funcional en desarrollo. La transmisión y visualización en vivo han sido probadas. La grabación y el almacenamiento de video no forman parte de las funcionalidades confirmadas de esta versión.

## 1. Funcionalidades actuales

- Interfaz web desarrollada con Laravel.
- Visualización de una cámara en vivo desde el navegador.
- Recepción de video mediante RTSP.
- Distribución de video mediante MediaMTX.
- Reproducción HLS mediante HLS.js.
- Servidor web Nginx con acceso HTTPS.
- Configuración del reproductor para ajustar el comportamiento de la reproducción en vivo.

## 2. Tecnologías utilizadas

| Tecnología | Función |
|---|---|
| PHP 8.3 o compatible con el proyecto | Lenguaje del backend |
| Laravel 13 | Framework web |
| MySQL | Base de datos |
| JavaScript | Lógica del reproductor |
| HLS.js | Reproducción HLS en el navegador |
| MediaMTX | Recepción y distribución del video |
| FFmpeg | Captura y transmisión desde la cámara |
| Nginx | Servidor web y proxy inverso |
| Ubuntu Server | Sistema operativo del servidor |
| Git y GitHub | Control de versiones |

## 3. Arquitectura del sistema

El flujo general de transmisión es el siguiente:

1. La cámara conectada al equipo emisor captura el video.
2. FFmpeg procesa el video y lo envía mediante RTSP al servidor.
3. MediaMTX recibe la transmisión en la ruta configurada.
4. MediaMTX genera la salida HLS.
5. Nginx permite acceder a la aplicación y a la salida HLS mediante HTTPS.
6. Laravel presenta la interfaz web y HLS.js reproduce el video.

```text
Cámara del equipo emisor
          |
          v
        FFmpeg
          |
          | RTSP
          v
       MediaMTX
          |
          | HLS
          v
        Nginx
          |
          | HTTPS
          v
   Aplicación Laravel
          |
          v
      HLS.js
          |
          v
  Video en el navegador
```

## 4. Requisitos previos

Para ejecutar el proyecto se necesita:

- Un servidor Ubuntu compatible con las versiones de las dependencias.
- PHP 8.3 o una versión admitida por Laravel 13.
- Composer.
- MySQL.
- Node.js y npm.
- Nginx.
- Git.
- MediaMTX.
- FFmpeg en el equipo que transmitirá la cámara.
- Una cámara compatible con FFmpeg.
- Certificado TLS/SSL válido para el acceso HTTPS en un entorno de producción.

Los puertos y reglas de red deben adaptarse a la configuración de cada servidor.

## 5. Clonar el repositorio

En el servidor donde se instalará el proyecto:

```bash
git clone https://github.com/Emerita2603/vigila-prototipo.git
cd vigila-prototipo
```

Si se utiliza otra carpeta de instalación, hay que adaptar las rutas de los siguientes pasos.

## 6. Instalar las dependencias de Laravel

Instalar las dependencias de PHP:

```bash
composer install
```

Crear el archivo de configuración local:

```bash
cp .env.example .env
```

Generar la clave de Laravel:

```bash
php artisan key:generate
```

Instalar las dependencias de JavaScript:

```bash
npm install --ignore-scripts
```

Compilar los recursos frontend:

```bash
npm run build
```

No se debe subir el archivo `.env` real al repositorio, ya que contiene información privada del entorno.

## 7. Configurar la base de datos

Crear una base de datos MySQL para el proyecto y un usuario con los permisos necesarios.

Ejemplo orientativo:

```sql
CREATE DATABASE vigila
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Configurar las variables correspondientes en `.env`:

```dotenv
APP_NAME=VIGILA
APP_ENV=production
APP_DEBUG=false
APP_URL=https://TU_DOMINIO_O_IP

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vigila
DB_USERNAME=TU_USUARIO
DB_PASSWORD=TU_CLAVE
```

Sustituir los valores de ejemplo por los correspondientes al entorno. No utilizar contraseñas reales en el README.

Ejecutar las migraciones:

```bash
php artisan migrate --force
```

Antes de ejecutar migraciones en una instalación existente, verificar que se apunta a la base de datos correcta y disponer de una copia de seguridad.

## 8. Configurar MediaMTX

MediaMTX recibe el flujo RTSP y proporciona la salida HLS que consume el reproductor web.

En la instalación de referencia se utiliza el archivo:

```text
/etc/mediamtx.yml
```

La configuración debe adaptarse al entorno e incluir, según corresponda:

- Recepción RTSP.
- HLS habilitado.
- Ruta de transmisión, por ejemplo `laptop`.
- Autenticación para los usuarios que publican o leen flujos.
- Duración y cantidad de segmentos HLS.
- Interfaces y puertos de escucha.
- Opciones de red necesarias para el acceso remoto.

Los nombres y valores concretos deben verificarse en el archivo de configuración real y en la documentación de la versión instalada.

Comprobar el estado del servicio:

```bash
sudo systemctl status mediamtx
```

Consultar los registros:

```bash
sudo journalctl -u mediamtx -n 100 --no-pager
```

No habilitar puertos públicos innecesarios. La exposición de RTSP, HLS y otros servicios debe limitarse según las necesidades del despliegue.

## 9. Transmitir la cámara con FFmpeg

En el equipo que dispone de la cámara, instalar FFmpeg y comprobar que el dispositivo es reconocido.

En Windows, se puede listar las cámaras disponibles mediante PowerShell:

```powershell
ffmpeg -list_devices true -f dshow -i dummy
```

El nombre de la cámara depende del equipo. En la configuración probada se utilizó `Integrated Camera`.

Ejemplo orientativo de transmisión para Windows:

```powershell
ffmpeg -thread_queue_size 512 -rtbufsize 32M -f dshow -pixel_format nv12 -video_size 640x360 -framerate 30 -i video="Integrated Camera" -an -vf "fps=15" -c:v libx264 -preset ultrafast -tune zerolatency -pix_fmt yuv420p -b:v 300k -g 30 -f rtsp -rtsp_transport tcp "rtsp://USUARIO:CLAVE@IP_DEL_SERVIDOR:8554/laptop"
```

Antes de ejecutarlo:

- Sustituir `USUARIO` y `CLAVE` por las credenciales configuradas en MediaMTX.
- Sustituir `IP_DEL_SERVIDOR` por la dirección del servidor.
- Verificar que la ruta `laptop` está configurada para recibir publicaciones.
- Confirmar que el puerto RTSP está permitido para el equipo emisor.
- Ajustar resolución, frecuencia de cuadros y bitrate según la capacidad de la red y del servidor.

Esta configuración captura a 30 fps y reduce la salida a 15 fps. La transmisión se mantiene activa mientras FFmpeg continúa ejecutándose.

**Importante:** no compartir la URL RTSP completa cuando incluya credenciales.

## 10. Configurar Nginx y HTTPS

En el servidor de referencia se utiliza Nginx con configuraciones ubicadas en:

```text
/etc/nginx/sites-available/
```

Los sitios habilitados se encuentran en:

```text
/etc/nginx/sites-enabled/
```

La instalación de referencia contiene configuraciones para VIGILA, incluyendo un sitio HTTPS y otro asociado al puerto 8080.

La configuración de Nginx debe:

- Apuntar la aplicación al directorio `public` del proyecto Laravel.
- Usar el mecanismo PHP-FPM compatible con la versión de PHP instalada.
- Permitir el acceso HTTPS con un certificado válido.
- Configurar correctamente el acceso a la salida HLS de MediaMTX.
- Evitar exponer archivos internos del proyecto.

Validar los archivos de configuración:

```bash
sudo nginx -t
```

Si la validación es correcta, recargar Nginx:

```bash
sudo systemctl reload nginx
```

Los nombres de los archivos de configuración, certificados, dominios y rutas deben ajustarse a cada instalación. No copiar configuraciones de producción sin revisar sus directivas.

## 11. Configuración del reproductor

La vista principal del dashboard se encuentra en:

```text
resources/views/dashboard/index.blade.php
```

El reproductor utiliza HLS.js para cargar el manifiesto HLS generado por MediaMTX.

La configuración actual incluye parámetros de sincronización y búfer para controlar la reproducción en vivo:

```javascript
hls = new Hls({
    enableWorker: true,
    lowLatencyMode: true,
    liveSyncDurationCount: 2,
    liveMaxLatencyDurationCount: 5,
    maxLiveSyncPlaybackRate: 1.15,
    backBufferLength: 10
});
```

Estos parámetros influyen en el comportamiento del reproductor, pero no garantizan por sí solos una latencia determinada. El retraso también depende de la segmentación HLS, la red, el equipo emisor y la configuración de MediaMTX.

Después de modificar las vistas de Laravel, puede limpiarse la caché de vistas con:

```bash
php artisan view:clear
```

## 12. Permisos y optimización de Laravel

Verificar que el usuario utilizado por PHP-FPM tenga los permisos adecuados sobre los directorios necesarios, especialmente:

```text
storage/
bootstrap/cache/
```

En un despliegue de producción, se pueden optimizar las configuraciones con:

```bash
php artisan optimize
```

Si se necesitan limpiar las cachés durante el diagnóstico:

```bash
php artisan optimize:clear
```

Utilizar estos comandos con conocimiento de su efecto sobre las cachés del entorno.

## 13. Comprobaciones y diagnóstico

Consultar el estado de los servicios:

```bash
sudo systemctl status nginx
sudo systemctl status mediamtx
```

Comprobar la configuración de Nginx:

```bash
sudo nginx -t
```

Comprobar la respuesta HTTPS local:

```bash
curl -k -I https://localhost
```

La opción `-k` permite realizar una prueba sin validar el certificado; no sustituye la comprobación de un certificado válido desde el navegador.

Consultar los registros de MediaMTX:

```bash
sudo journalctl -u mediamtx --since "10 minutes ago" --no-pager
```

Si el video no aparece, comprobar en este orden:

1. Que FFmpeg sigue ejecutándose en el equipo emisor.
2. Que MediaMTX recibe la publicación RTSP.
3. Que la ruta configurada coincide con la ruta solicitada por el reproductor.
4. Que el manifiesto HLS es accesible desde el servidor.
5. Que Nginx permite acceder a la ruta HLS.
6. Que el navegador no muestra errores de red o reproducción en las herramientas de desarrollo.

## 14. Seguridad

- No subir `.env`, contraseñas, tokens ni claves privadas.
- No incluir credenciales en capturas, registros públicos o documentación.
- Usar contraseñas robustas y rotarlas si se han expuesto.
- Mantener `APP_DEBUG=false` en producción.
- Utilizar HTTPS con un certificado válido.
- Limitar las reglas de entrada del firewall y del grupo de seguridad de AWS.
- No habilitar servicios de grabación si no se han evaluado sus implicaciones de privacidad y almacenamiento.
- Proteger las credenciales de publicación RTSP.
- Evitar exponer MySQL directamente a Internet.
- Revisar las reglas de acceso antes de reproducir el despliegue en otro servidor.

## 15. Limitaciones conocidas

Esta versión es un prototipo. Las siguientes funcionalidades no deben considerarse confirmadas sin una implementación y prueba adicional:

- Grabación y almacenamiento persistente de video.
- Administración avanzada de múltiples cámaras.
- Alertas automáticas y detección inteligente.
- Gestión avanzada de usuarios y permisos.
- Alta disponibilidad y escalado de infraestructura.
- Garantías de latencia ultrabaja.

## 16. Contribuciones

Para proponer cambios, se recomienda crear una rama de trabajo, probar las modificaciones y enviar una solicitud de integración.

```bash
git checkout -b mejora/nombre-del-cambio
```

Después de implementar y probar los cambios:

```bash
git add ARCHIVOS_MODIFICADOS
git commit -m "Describir el cambio realizado"
git push -u origin mejora/nombre-del-cambio
```

## 17. Licencia

Consultar la licencia y los avisos de terceros incluidos en el repositorio antes de redistribuir el proyecto.

---

**VIGILA** — Prototipo de vigilancia en tiempo real. Proyecto en desarrollo.
