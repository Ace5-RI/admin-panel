<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disco Party - EXTREME SPEED + 1000 WARNA! 🚀🪩</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', 'Segoe UI', sans-serif;
            transition: background-color 0.05s ease;
            position: relative;
        }

        .container {
            text-align: center;
            padding: 20px;
            z-index: 2;
        }

        h1 {
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            margin-bottom: 20px;
            font-size: 3rem;
        }

        .disco-btn {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
            background-size: 300% 300%;
            border: none;
            color: white;
            padding: 18px 45px;
            font-size: 26px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            animation: gradientShift 3s ease infinite;
            margin: 10px;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .disco-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .stop-btn {
            background: #dc3545;
            border: none;
            color: white;
            padding: 12px 35px;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
            margin: 10px;
            transition: all 0.3s ease;
        }

        .stop-btn:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        .speed-control {
            background: rgba(0,0,0,0.7);
            padding: 15px;
            border-radius: 20px;
            margin: 15px auto;
            display: inline-block;
        }

        .speed-control label {
            color: white;
            margin: 0 10px;
            font-weight: bold;
        }

        .speed-control input {
            cursor: pointer;
            width: 200px;
        }

        .speed-value {
            color: #ffd700;
            font-weight: bold;
            margin-left: 10px;
        }

        .upload-section {
            background: rgba(0,0,0,0.7);
            padding: 20px;
            border-radius: 20px;
            margin: 20px auto;
            max-width: 400px;
        }

        .upload-section label {
            color: white;
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
        }

        .upload-section input {
            display: block;
            margin: 10px auto;
            padding: 10px;
            background: white;
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
        }

        .current-song {
            color: #ffd700;
            margin-top: 10px;
            font-size: 14px;
            word-break: break-all;
        }

        .volume-control {
            margin-top: 20px;
            background: rgba(0,0,0,0.6);
            padding: 10px 20px;
            border-radius: 30px;
            display: inline-block;
        }

        .volume-control label {
            color: white;
            margin-right: 10px;
            font-weight: bold;
        }

        input[type="range"] {
            width: 150px;
            cursor: pointer;
        }

        .volume-value {
            color: white;
            margin-left: 10px;
            font-weight: bold;
        }

        .info {
            color: white;
            background: rgba(0,0,0,0.5);
            padding: 10px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 14px;
        }

        .song-info {
            color: #ffd700;
            font-weight: bold;
            margin-top: 10px;
            font-size: 16px;
            text-shadow: 1px 1px 2px black;
        }

        .status {
            color: white;
            background: rgba(0,0,0,0.7);
            padding: 8px 15px;
            border-radius: 20px;
            margin-top: 15px;
            font-size: 14px;
            display: inline-block;
        }

        @keyframes discoText {
            0%, 100% { text-shadow: 0 0 5px red, 0 0 10px red; }
            25% { text-shadow: 0 0 5px yellow, 0 0 10px yellow; }
            50% { text-shadow: 0 0 5px lime, 0 0 10px lime; }
            75% { text-shadow: 0 0 5px cyan, 0 0 10px cyan; }
        }

        .disco-text {
            animation: discoText 0.15s infinite;
        }

        .particle {
            position: fixed;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 999;
            animation: particleAnim 1s ease-out forwards;
        }

        @keyframes particleAnim {
            0% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) scale(0);
                opacity: 0;
            }
        }

        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .rainbow-border {
            animation: borderRainbow 0.1s infinite;
        }

        @keyframes borderRainbow {
            0% { box-shadow: 0 0 20px red; }
            25% { box-shadow: 0 0 20px yellow; }
            50% { box-shadow: 0 0 20px lime; }
            75% { box-shadow: 0 0 20px blue; }
            100% { box-shadow: 0 0 20px purple; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 id="title">⚡ DISCO EXTREME ⚡</h1>
        <div class="song-info" id="songInfo">🎵 Upload lagu disco lo! 🎵</div>
        <div class="status" id="status">⚪ Siap</div>
        
        <button class="disco-btn" id="discoBtn" disabled>🎧 MULAI DISCO 🎧</button>
        <button class="stop-btn" id="stopBtn">⏹️ STOP MANUAL</button>
        
        <div class="speed-control">
            <label>⚡ KECEPATAN WARNA:</label>
            <input type="range" id="speedSlider" min="10" max="200" value="50">
            <span class="speed-value" id="speedValue">50ms</span>
            <div style="color: white; font-size: 12px; margin-top: 5px;">⬅️ Lebih lambat | Lebih cepat ➡️</div>
        </div>
        
        <div class="upload-section">
            <label>📁 1. Upload Lagu Disco Lo (MP3/WAV)</label>
            <input type="file" id="songUpload" accept="audio/*">
            <div class="current-song" id="currentSong">Belum ada lagu</div>
            
            <label style="margin-top: 15px;">🔊 2. Upload Sound Effect "Hidup Cangcut"</label>
            <input type="file" id="sfxUpload" accept="audio/*">
            <div class="current-song" id="currentSFX">Belum ada SFX</div>
        </div>
        
        <div class="volume-control">
            <label>🔊 Volume:</label>
            <input type="range" id="volumeSlider" min="0" max="100" value="70">
            <span class="volume-value" id="volumeValue">70%</span>
        </div>
        
        <div class="info">
            ✨ UPDATE TERBARU! ✨<br>
            🎨 <strong>10 JUTA+ KOMBINASI WARNA</strong> (RGB FULL RANDOM!)<br>
            ⚡ <strong>KECEPATAN BISA DIATUR</strong> - dari 10ms (super cepat) sampai 200ms<br>
            🌈 <strong>WARNA GRADIENT + STROBE EFFECT</strong><br><br>
            Cara pake:<br>
            1. Upload lagu disco lo<br>
            2. Upload SFX "hidup cangcut"<br>
            3. Atur kecepatan warna (makin kecil makin cepat)<br>
            4. Klik MULAI → lagu jalan + WARNA BERUBAH SUPER CEPAT!<br>
            5. Pas lagu abis → play SFX → disco berhenti<br>
            💃 Tekan SPASI untuk start/stop 🕺
        </div>
    </div>

    <script>
        // ============ VARIABEL ============
        let discoInterval = null;
        let currentAudio = null;
        let currentSFXAudio = null;
        let isDiscoPlaying = false;
        let songBlob = null;
        let sfxBlob = null;
        let songUrl = null;
        let sfxUrl = null;
        let currentSpeed = 50;
        
        // ============ FUNGSI WARNA YANG SUPER BANYAK ============
        
        // Cara 1: RGB Acak - Hasilnya 16 JUTA lebih kombinasi warna!
        function getRandomColorRGB() {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            return `rgb(${r}, ${g}, ${b})`;
        }
        
        // Cara 2: Warna neon terang (lebih cerah)
        function getRandomNeonColor() {
            const r = Math.floor(Math.random() * 155) + 100; // 100-255
            const g = Math.floor(Math.random() * 155) + 100;
            const b = Math.floor(Math.random() * 155) + 100;
            return `rgb(${r}, ${g}, ${b})`;
        }
        
        // Cara 3: Warna HSL (Hue, Saturation, Lightness) - efek pelangi smooth
        let hue = 0;
        function getRainbowColor() {
            hue = (hue + 15) % 360;
            return `hsl(${hue}, 100%, 55%)`;
        }
        
        // Mode warna: random (pilih salah satu dari 3 metode di atas)
        let colorMode = 0;
        function getExplosiveColor() {
            colorMode = (colorMode + 1) % 3;
            switch(colorMode) {
                case 0: return getRandomColorRGB();      // 16 juta warna
                case 1: return getRandomNeonColor();     // warna neon terang
                case 2: return getRainbowColor();        // efek pelangi
                default: return getRandomColorRGB();
            }
        }
        
        // Fungsi buat ngubah warna background dengan efek ekstra
        function changeBackgroundColorExtreme() {
            // Pilih warna random dari 3 metode
            const randomColor = getExplosiveColor();
            document.body.style.backgroundColor = randomColor;
            
            // Ubah warna teks (kontras biar keliatan)
            const title = document.getElementById('title');
            const songInfo = document.getElementById('songInfo');
            
            // Teks warna komplementer (biar keliatan jelas)
            title.style.color = getRandomColorRGB();
            songInfo.style.color = getRandomColorRGB();
            
            // Efek disco text
            title.classList.add('disco-text');
            songInfo.classList.add('disco-text');
            
            // Efek tambahan: ubah warna border tombol (opsional)
            const discoBtn = document.getElementById('discoBtn');
            if (discoBtn && isDiscoPlaying) {
                discoBtn.style.boxShadow = `0 0 20px ${getRandomColorRGB()}`;
            }
        }
        
        // Versi super cepat (tanpa efek tambahan biar makin cepet)
        function changeColorUltraFast() {
            document.body.style.backgroundColor = getExplosiveColor();
        }
        
        // ============ FUNGSI UTILITY ============
        function stopColorInterval() {
            if (discoInterval) {
                clearInterval(discoInterval);
                discoInterval = null;
            }
            // Reset warna ke putih
            document.body.style.backgroundColor = '#ffffff';
            const title = document.getElementById('title');
            const songInfo = document.getElementById('songInfo');
            title.style.color = 'white';
            songInfo.style.color = '#ffd700';
            title.classList.remove('disco-text');
            songInfo.classList.remove('disco-text');
            
            const discoBtn = document.getElementById('discoBtn');
            if (discoBtn) discoBtn.style.boxShadow = '';
        }

        function createParticle(x, y) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.backgroundColor = getRandomColorRGB();
            particle.style.left = x + 'px';
            particle.style.top = y + 'px';
            document.body.appendChild(particle);
            setTimeout(() => particle.remove(), 1000);
        }

        function burstParticles(e) {
            for(let i = 0; i < 30; i++) {
                const offsetX = (Math.random() - 0.5) * 100;
                const offsetY = (Math.random() - 0.5) * 100;
                createParticle(e.clientX + offsetX, e.clientY + offsetY);
            }
        }
        
        // Fungsi buat mulai interval warna dengan kecepatan tertentu
        function startColorInterval() {
            if (discoInterval) clearInterval(discoInterval);
            
            // Pake fungsi yang lebih ringan buat kecepatan ekstrim
            const useUltraFast = currentSpeed <= 30;
            
            discoInterval = setInterval(() => {
                if (isDiscoPlaying) {
                    if (useUltraFast) {
                        changeColorUltraFast();
                    } else {
                        changeBackgroundColorExtreme();
                    }
                }
            }, currentSpeed);
        }
        
        // Update kecepatan dari slider
        function updateSpeed() {
            const speedSlider = document.getElementById('speedSlider');
            currentSpeed = parseInt(speedSlider.value);
            document.getElementById('speedValue').textContent = currentSpeed + 'ms';
            
            // Jika disco sedang jalan, update intervalnya
            if (isDiscoPlaying) {
                startColorInterval();
            }
        }
        
        // ============ FUNGSI UTAMA ============
        
        function fullStop(resetStatus = true) {
            stopColorInterval();
            
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.currentTime = 0;
                if (currentAudio.removeEventListener) {
                    currentAudio.removeEventListener('ended', onSongEnd);
                }
                currentAudio = null;
            }
            
            if (currentSFXAudio) {
                currentSFXAudio.pause();
                currentSFXAudio = null;
            }
            
            isDiscoPlaying = false;
            
            if (resetStatus) {
                document.getElementById('status').innerHTML = '⚪ Siap';
                document.getElementById('status').style.background = 'rgba(0,0,0,0.7)';
                const songInfoDiv = document.getElementById('songInfo');
                if (songBlob) {
                    songInfoDiv.innerHTML = '🎵 Siap mainin lagu! Klik MULAI 🎵';
                } else {
                    songInfoDiv.innerHTML = '🎵 Upload lagu dulu ya! 🎵';
                }
            }
            
            const discoBtn = document.getElementById('discoBtn');
            discoBtn.disabled = !songBlob;
            if (songBlob) {
                discoBtn.textContent = '🎧 MULAI DISCO 🎧';
                discoBtn.style.animation = 'gradientShift 3s ease infinite';
            }
        }
        
        function onSongEnd() {
            console.log('Lagu selesai, play SFX hidup cangcut...');
            
            // Matikan warna
            if (discoInterval) {
                clearInterval(discoInterval);
                discoInterval = null;
            }
            
            document.getElementById('status').innerHTML = '🔊 Playing: HIDUP CANGCUT! 🔊';
            document.getElementById('status').style.background = 'rgba(255, 100, 0, 0.9)';
            document.getElementById('songInfo').innerHTML = '💥 HIDUP CANGCUT!!! 💥';
            
            if (sfxBlob && sfxUrl) {
                if (currentSFXAudio) {
                    currentSFXAudio.pause();
                    currentSFXAudio = null;
                }
                
                currentSFXAudio = new Audio(sfxUrl);
                currentSFXAudio.volume = document.getElementById('volumeSlider').value / 100;
                
                currentSFXAudio.addEventListener('ended', function onSFXEnd() {
                    console.log('SFX selesai, disco berhenti total');
                    currentSFXAudio.removeEventListener('ended', onSFXEnd);
                    
                    stopColorInterval();
                    isDiscoPlaying = false;
                    
                    document.getElementById('status').innerHTML = '✅ Disco selesai! Klik MULAI lagi';
                    document.getElementById('status').style.background = 'rgba(0,128,0,0.8)';
                    document.getElementById('songInfo').innerHTML = '🎵 Disco berhenti. Klik MULAI buat repeat 🎵';
                    
                    const discoBtn = document.getElementById('discoBtn');
                    discoBtn.disabled = false;
                    discoBtn.textContent = '🎧 MULAI DISCO 🎧';
                    discoBtn.style.animation = 'gradientShift 3s ease infinite';
                    
                    document.body.style.backgroundColor = '#ffffff';
                    const title = document.getElementById('title');
                    title.style.color = 'white';
                    title.classList.remove('disco-text');
                    document.getElementById('songInfo').style.color = '#ffd700';
                    document.getElementById('songInfo').classList.remove('disco-text');
                });
                
                currentSFXAudio.play().catch(e => console.log('Error play SFX:', e));
            } else {
                stopColorInterval();
                isDiscoPlaying = false;
                document.getElementById('status').innerHTML = '✅ Disco selesai!';
                const discoBtn = document.getElementById('discoBtn');
                discoBtn.disabled = false;
                discoBtn.textContent = '🎧 MULAI DISCO 🎧';
                document.body.style.backgroundColor = '#ffffff';
            }
        }
        
        async function startDisco() {
            if (!songBlob) {
                alert('Upload lagu disco lo dulu ya!');
                return;
            }
            
            if (isDiscoPlaying) {
                console.log('Disco lagi jalan');
                return;
            }
            
            console.log('Starting disco with speed:', currentSpeed, 'ms');
            
            if (currentAudio) {
                currentAudio.pause();
                currentAudio = null;
            }
            if (currentSFXAudio) {
                currentSFXAudio.pause();
                currentSFXAudio = null;
            }
            if (discoInterval) {
                clearInterval(discoInterval);
            }
            
            document.body.style.backgroundColor = '#ffffff';
            
            currentAudio = new Audio(songUrl);
            currentAudio.volume = document.getElementById('volumeSlider').value / 100;
            currentAudio.loop = false;
            currentAudio.addEventListener('ended', onSongEnd);
            
            // Mulai warna dengan kecepatan yang diatur
            startColorInterval();
            
            try {
                await currentAudio.play();
                isDiscoPlaying = true;
                
                const discoBtn = document.getElementById('discoBtn');
                discoBtn.textContent = '⚡ DISCO EXTREME ON ⚡';
                discoBtn.style.animation = 'none';
                discoBtn.disabled = true;
                
                document.getElementById('status').innerHTML = `🎵 Lagu diputar... (kecepatan: ${currentSpeed}ms) 🎵`;
                document.getElementById('status').style.background = 'rgba(0,0,0,0.7)';
                document.getElementById('songInfo').innerHTML = '⚡ WARNA BERUBAH SUPER CEPAT! ⚡';
                
                const stopBtn = document.getElementById('stopBtn');
                stopBtn.style.animation = 'pulse 0.5s infinite';
                
                if (!document.querySelector('#pulseStyle')) {
                    const style = document.createElement('style');
                    style.id = 'pulseStyle';
                    style.textContent = `
                        @keyframes pulse {
                            0% { transform: scale(1); }
                            50% { transform: scale(1.05); }
                            100% { transform: scale(1); }
                        }
                    `;
                    document.head.appendChild(style);
                }
                
            } catch (error) {
                console.error('Error play audio:', error);
                alert('Gagal play lagu. Coba upload ulang.');
                fullStop(true);
            }
        }
        
        function stopDiscoManual(e) {
            if (e) burstParticles(e);
            
            if (currentAudio) {
                currentAudio.pause();
                currentAudio.removeEventListener('ended', onSongEnd);
            }
            if (currentSFXAudio) {
                currentSFXAudio.pause();
                currentSFXAudio = null;
            }
            
            fullStop(true);
        }
        
        // ============ UPLOAD FILE ============
        
        document.getElementById('songUpload').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                if (songUrl) URL.revokeObjectURL(songUrl);
                songBlob = file;
                songUrl = URL.createObjectURL(file);
                document.getElementById('currentSong').innerHTML = `✅ ${file.name}`;
                document.getElementById('songInfo').innerHTML = '🎵 Lagu siap! Upload SFX lalu klik MULAI 🎵';
                
                const discoBtn = document.getElementById('discoBtn');
                discoBtn.disabled = false;
            }
        });
        
        document.getElementById('sfxUpload').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                if (sfxUrl) URL.revokeObjectURL(sfxUrl);
                sfxBlob = file;
                sfxUrl = URL.createObjectURL(file);
                document.getElementById('currentSFX').innerHTML = `✅ ${file.name} (akan diputar pas lagu abis)`;
            }
        });
        
        // ============ EVENT LISTENER ============
        const discoBtn = document.getElementById('discoBtn');
        discoBtn.addEventListener('click', (e) => {
            burstParticles(e);
            startDisco();
        });
        
        const stopBtn = document.getElementById('stopBtn');
        stopBtn.addEventListener('click', (e) => {
            burstParticles(e);
            stopDiscoManual(e);
        });
        
        const speedSlider = document.getElementById('speedSlider');
        speedSlider.addEventListener('input', (e) => {
            updateSpeed();
        });
        
        const volumeSlider = document.getElementById('volumeSlider');
        const volumeValue = document.getElementById('volumeValue');
        volumeSlider.addEventListener('input', (e) => {
            const val = e.target.value;
            volumeValue.textContent = val + '%';
            if (currentAudio) currentAudio.volume = val / 100;
            if (currentSFXAudio) currentSFXAudio.volume = val / 100;
        });
        
        document.addEventListener('keydown', (event) => {
            if (event.code === 'Space') {
                event.preventDefault();
                if (isDiscoPlaying) {
                    stopDiscoManual();
                } else {
                    if (songBlob) startDisco();
                    else alert('Upload lagu dulu ya!');
                }
            }
        });
        
        console.log('🎉 EXTREME DISCO READY! 🎉');
        console.log('🔥 FITUR BARU:');
        console.log('- 16 JUTA+ kombinasi warna (RGB random)');
        console.log('- Kecepatan bisa diatur dari 10ms (super cepat!) sampai 200ms');
        console.log('- Efek neon + rainbow + strobe');
        console.log('- Geser slider kecepatan untuk makin kenceng!');
    </script>
</body>
</html>