<?php
function checkFile($label, $path, $extraTests = []) {
    $exists = false;
    $details = [];

    if (filter_var($path, FILTER_VALIDATE_URL)) {
        $ch = curl_init($path);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 5
        ]);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $exists = ($httpCode == 200);
        $details['HTTP Durum Kodu'] = $httpCode;
        $details['Erişilebilirlik'] = $exists ? '✓ Çevrimiçi' : '✗ Kapalı';
    } else {
        $exists = file_exists($path);
        if ($exists) {
            $details['Boyut'] = round(filesize($path) / 1024, 2) . ' KB';
            $details['Son Değişim'] = date('d.m.Y H:i:s', filemtime($path));
            $details['İzinler'] = substr(sprintf('%o', fileperms($path)), -4);
        }
    }

    foreach ($extraTests as $testName => $testFunc) {
        $details[$testName] = $testFunc($path) ? '✓' : '✗';
    }

    $status = $exists ? 'ÇALIŞIYOR' : 'ERİŞİLEMİYOR';
    $color = $exists ? 'green' : 'red';

    return [
        'label' => $label,
        'path' => $path,
        'status' => $status,
        'color' => $color,
        'exists' => $exists,
        'details' => $details
    ];
}

// Sistem bilgileri
$systemInfo = [
    'Sunucu Yazılımı' => $_SERVER['SERVER_SOFTWARE'] ?? 'Bilinmiyor',
    'PHP Versiyonu' => phpversion(),
    'SQLite Versiyonu' => class_exists('SQLite3') ? SQLite3::version()['versionString'] : 'Yüklü değil',
    'Bellek Limiti' => ini_get('memory_limit'),
    'Maks. Çalışma Süresi' => ini_get('max_execution_time') . ' sn'
];

// Kontrol edilecek dosya ve URL'ler
$checks = [
    checkFile('Ana Sayfa', __DIR__ . '/index.php'),
    checkFile('Veritabanı', __DIR__ . '/api/games/games.db', [
        'Geçerli DB' => function($path) {
            try {
                $db = new SQLite3($path);
                return $db->querySingle("SELECT COUNT(*) FROM sqlite_master") > 0;
            } catch (Exception $e) {
                return false;
            }
        }
    ]),
    checkFile('Oyun API', 'https://api.capsule.net.tr/games/api.php'),
    checkFile('Capsule Studio', 'https://studio.capsule.net.tr/index.php', [
        'Panel Erişimi' => function($url) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT => 5
            ]);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $httpCode == 200;
        }
    ])
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sistem Durumu - Capsule</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .status-badge {
            @apply px-3 py-1 rounded-full text-sm font-medium;
        }
        .status-badge.green {
            @apply bg-green-100 text-green-800;
        }
        .status-badge.red {
            @apply bg-red-100 text-red-800;
        }
        .detail-item {
            @apply border-b border-gray-200 py-2;
        }
        .detail-item:last-child {
            @apply border-b-0;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-10 max-w-4xl">
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-8">
            <div class="p-6 bg-indigo-700 text-white">
                <h1 class="text-3xl font-bold flex items-center">
                    <i class="fas fa-heartbeat mr-3"></i>
                    Capsule Sistem Durumu
                </h1>
                <p class="mt-2 opacity-90">Sistem bileşenlerinin detaylı durum raporu</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-indigo-50 rounded-lg p-4">
                        <h2 class="text-xl font-semibold text-indigo-800 mb-3">
                            <i class="fas fa-server mr-2"></i>Sunucu Bilgileri
                        </h2>
                        <div class="space-y-2">
                            <?php foreach ($systemInfo as $label => $value): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600"><?= $label ?></span>
                                    <span class="font-medium"><?= $value ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <h2 class="text-xl font-semibold text-green-800 mb-3">
                            <i class="fas fa-chart-pie mr-2"></i>Genel Durum
                        </h2>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Toplam Bileşen</span>
                                <span class="font-medium"><?= count($checks) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Çalışan</span>
                                <span class="font-medium text-green-600">
                                    <?= count(array_filter($checks, fn($item) => $item['exists'])) ?>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Hatalı</span>
                                <span class="font-medium text-red-600">
                                    <?= count(array_filter($checks, fn($item) => !$item['exists'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="text-2xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-cogs mr-2"></i>Bileşen Kontrolleri
                </h2>

                <div class="space-y-4">
                    <?php foreach ($checks as $item): ?>
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="flex items-center justify-between p-4 bg-gray-50">
                            <div class="flex items-center">
                                <i class="fas <?= $item['exists'] ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' ?> mr-3 text-xl"></i>
                                <div>
                                    <h3 class="font-medium text-lg"><?= htmlspecialchars($item['label']) ?></h3>
                                    <p class="text-sm text-gray-500"><?= $item['path'] ?></p>
                                </div>
                            </div>
                            <span class="status-badge <?= $item['color'] ?>"><?= $item['status'] ?></span>
                        </div>
                        <?php if (!empty($item['details'])): ?>
                        <div class="p-4 border-t border-gray-200">
                            <h4 class="font-medium text-gray-700 mb-2">Detaylar:</h4>
                            <div class="space-y-2">
                                <?php foreach ($item['details'] as $detailLabel => $detailValue): ?>
                                <div class="detail-item flex justify-between">
                                    <span class="text-gray-600"><?= $detailLabel ?></span>
                                    <span class="font-medium"><?= $detailValue ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="text-center text-sm text-gray-500">
            <p>Son kontrol: <?= date('d.m.Y H:i:s') ?> | 
            <a href="#" onclick="location.reload()" class="text-indigo-600 hover:underline">
                <i class="fas fa-sync-alt mr-1"></i>Yenile
            </a></p>
            <p class="mt-1">Capsule v1.0 - Sistem İzleme Paneli</p>
        </div>
    </div>
    <script>
        setTimeout(() => location.reload(), 30000);
    </script>
</body>
</html>
