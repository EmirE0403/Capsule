<?php
// Bağlantı
$db = new SQLite3(__DIR__ . '/database/games/games.db');

// ID'yi al
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Oyun verisini al
$stmt = $db->prepare("SELECT * FROM games WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result = $stmt->execute();
$game = $result->fetchArray(SQLITE3_ASSOC);

// Sayfa başlığı ayarı
$pageTitle = $game ? $game['name'] : 'Oyun Bulunamadı';
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($pageTitle) ?> | Capsule Studio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="max-w-5xl mx-auto py-10 px-4">
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
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <a href="/studio/studio.php" class="text-indigo-600 font-semibold text-lg">&larr; Geri Dön</a>
      <h1 class="text-3xl font-bold">Capsule Game Viewer</h1>
    </div>

    <?php if ($game): ?>
      <?php if (!empty($game['deleted'])): ?>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-6">
          <strong>Bu oyun silinmiş olabilir.</strong>
          ID: <?= $id ?>
        </div>
      <?php endif; ?>

      <!-- Game Box -->
      <div class="bg-white shadow rounded-lg overflow-hidden flex flex-col md:flex-row">
        <div class="md:w-1/2">
          <img src="<?= htmlspecialchars($game['image_url'] ?: 'https://placehold.co/600x400') ?>" alt="<?= htmlspecialchars($game['name']) ?>" class="object-cover w-full h-full">
        </div>
        <div class="p-6 md:w-1/2 flex flex-col justify-between">
          <div>
            <h2 class="text-2xl font-bold mb-2"><?= htmlspecialchars($game['name']) ?></h2>
            <p class="mb-4 text-gray-700"><?= nl2br(htmlspecialchars($game['description'])) ?></p>
            <p class="text-sm text-gray-500">ID: <?= $id ?> • <?= $game['created_at'] ?></p>
          </div>
          <div class="mt-6">
            <a href="<?= htmlspecialchars($game['link']) ?>" target="_blank" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded">
              Oyna
            </a>
          </div>
        </div>
      </div>
    <?php else: ?>
      <!-- Not found -->
      <div class="bg-red-100 text-red-800 p-6 rounded text-center">
        <h2 class="text-2xl font-bold mb-2">Oyun bulunamadı</h2>
        <p>Geçersiz bir ID girdiniz veya bu oyun silinmiş.</p>
        <p>ID: <?= $id ?></p>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
