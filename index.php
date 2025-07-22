<?php
$config = require 'config.php';

if ($config['maintenance_mode'] && !isset($_GET['admin'])) {
    echo <<<HTML
    <!DOCTYPE html>
    <html lang="tr">
    <head>
        <meta charset="UTF-8">
        <title>Capsule - Güncelleniyor</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="bg-gray-100 flex items-center justify-center min-h-screen">
            <div id="error" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded"></div>
            <div id="warning" class="hidden bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded"></div>
            <div class="text-center bg-white shadow-lg rounded-lg p-8 max-w-md">
                <h1 class="text-2xl font-bold text-red-600 mb-4">{$config['maintenance_name']}</h1>
                <p class="text-gray-600">{$config['maintenance_message']}</p>
                <p class="text-sm text-gray-400">Yönetici giriş yaparak devam edebilir.</p>
            </div>
        </div>
        
        <script>
           // const warningElement = document.getElementById('warning');
           // warningElement.textContent = `Capsule Sitesi ve Sistemleri Şu Anlık Güncelleniyor! Bazı Özellikler Çalışmayabilir!`;
           // warningElement.classList.remove('hidden');
        </script>
    </body>
    </html>
    HTML;
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Capsule</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .game-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .skeleton {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
    </style>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <img src="res/images/CapsuleLogo.png" alt="Capsule Logo" class="h-14">
                <p class="text-gray-500 mt-2">Prototip</p>
            </div>
            <nav class="flex space-x-4">
                <a href="index.php" class="text-gray-600 hover:text-indigo-600 font-medium">Ana Sayfa</a>
                <!-- <a href="studio/studio.php" class="text-gray-600 hover:text-indigo-600 font-medium">Studio</a>
                <a href="status.php" class="text-gray-600 hover:text-indigo-600 font-medium">Status</a> -->
                
                <!-- Kullanıcı adı veya Misafir yazısı -->
                <div class="flex items-center space-x-2">
                    <span>|</span>
                    <!--<img src="res/images/CapsuleMisafir.png" alt="Misafir" class="h-8 w-8 rounded-full">
                    <span id="user-status" class="text-gray-600 hover:text-indigo-600 font-medium cursor-pointer">
                        Misafir
                    </span> -->

                    <button class="text-gray-600 hover:text-indigo-600 font-medium">
                        Giriş Yap
                    </button>

                    <button class="text-gray-600 hover:text-indigo-600 font-medium">
                        Kayıt Ol
                    </button>
                </div>
            </nav>
        </header>

        <!-- Hata mesajı -->
        <div id="error" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded"></div>
        <div id="warning" class="hidden bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded"></div>
        <div id="good" class="hidden bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded"></div>

        <!-- Yükleme durumu -->
        <div id="loading" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Skeleton loader -->
            <div class="skeleton bg-gray-200 rounded-lg h-64"></div>
            <div class="skeleton bg-gray-200 rounded-lg h-64"></div>
            <div class="skeleton bg-gray-200 rounded-lg h-64"></div>
            <div class="skeleton bg-gray-200 rounded-lg h-64"></div>
            <div class="skeleton bg-gray-200 rounded-lg h-64"></div>
            <div class="skeleton bg-gray-200 rounded-lg h-64"></div>
            <div class="skeleton bg-gray-200 rounded-lg h-64"></div>
            <div class="skeleton bg-gray-200 rounded-lg h-64"></div>
        </div>

        <!-- Oyun listesi -->
        <div id="games-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"></div>
    </div>

    <script>
        const API_URL = 'https://api.capsule.net.tr/games/api.php';
        
        async function fetchGames() {
            try {
                const response = await fetch(API_URL, {
                    headers: {
                        'Accept': 'application/json',
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.status !== 'success') {
                    throw new Error(data.message || 'Invalid response from server');
                }
                
                return data.data || [];
                
            } catch (error) {
                console.error('Fetch error:', error);
                throw error;
            }
        }

        function renderGames(games) {
            const container = document.getElementById('games-container');
            
            if (!games || games.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-10">
                        <p class="text-gray-500">Henüz oyun eklenmemiş</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = games.map(game => ` 
                <div class="game-card bg-white rounded-lg overflow-hidden shadow-md">
                    <img src="${game.image_url}" 
                         alt="${game.name}"
                         class="w-full h-48 object-cover"
                         loading="lazy"
                         onerror="this.src='https://placehold.co/600x400'">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 truncate">${game.name}</h3>
                        <p class="text-gray-600 text-sm mt-2 line-clamp-2">${game.description || 'Açıklama yok'}</p>
                        
                        <div class="flex justify-between items-center mt-3 text-sm text-gray-500">
                            <span class="flex items-center">
                                👥 ${game.players || 0}
                            </span>
                            <span class="flex items-center">
                                ❤️ ${game.likes || 0}
                            </span>
                        </div>
                        
                        <a href="${game.link}" 
                           target="_blank"
                           class="mt-4 block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded transition">
                            Oyna
                        </a>
                    </div>
                </div>
            `).join('');
        }

        async function init() {
            try {
                const games = await fetchGames();
                renderGames(games);
            } catch (error) {
                const errorElement = document.getElementById('error');
                errorElement.textContent = `Oyunlar yüklenirken hata oluştu: ${error.message}`;
                errorElement.classList.remove('hidden');
            } finally {
                document.getElementById('loading').style.display = 'none';
            }
        }

        //Dikkat Penceresi
        const warningElement = document.getElementById('good');
        warningElement.textContent = `Şu An Test Aşamasında`;
        warningElement.classList.remove('hidden');

        // Uygulamayı başlat
        document.addEventListener('DOMContentLoaded', init);
    </script>
</body>
</html>
