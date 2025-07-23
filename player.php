<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title>Three.js + CoreGui Menü</title>
  <style>
    body {
      margin: 0;
      overflow: hidden;
    }

    #gui {
      position: absolute;
      top: 20px;
      left: 20px;
      display: flex;
      flex-direction: row;
      gap: 10px;
      z-index: 10;
    }

    .hud-button {
      font-size: 2em;
      width: 1.5em;
      height: 1.5em;
      background: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-family: sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      box-sizing: border-box;
    }

    .hud-button:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    #menu {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%); /* tam ortalama */
      width: 300px;
      padding: 20px;
      background: rgba(0, 0, 0, 0.8);
      color: white;
      border-radius: 8px;
      font-family: sans-serif;
      display: none;
      z-index: 9;
      text-align: center;
    }

    #chat {
      display: none;
    }

    #menu ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    #menu li {
      margin: 10px 0;
      cursor: pointer;
    }

    .menu-visible {
      display: block !important;
    }

    .chat-visible {
      display: block !important;
    }
  </style>

  <style></style>
</head>
<body>
  <div id="gui">
    <button class="hud-button" id="menuToggle">☰</button>
    <button class="hud-button" id="btn1">🗨</button>
  </div>

  <div id="menu" class="center">
    <h3>Oyun Menüsü</h3>
    <ul>
      <li>Envanter</li>
      <li>Seçenekler</li>
      <li>Çıkış</li>
    </ul>
  </div>

  <div id="chat">
    <h3>Oyun Menüsü</h3>
    <ul>
      <li>Envanter</li>
      <li>Seçenekler</li>
      <li>Çıkış</li>
    </ul>
  </div>  

  <canvas id="c"></canvas>

  <script type="importmap">
    {
      "imports": {
        "three": "https://cdn.jsdelivr.net/npm/three/build/three.module.js",
        "three/addons/": "https://cdn.jsdelivr.net/npm/three/examples/jsm/",
        "three/examples/jsm/controls/OrbitControls": "https://cdn.jsdelivr.net/npm/three/examples/jsm/controls/OrbitControls.js"
      }
    }
  </script>
  <script src="https://mrdoob.github.io/stats.js/build/stats.js"></script>

  <script type="module" src="js/games/player.js"></script>
</body>
</html>
