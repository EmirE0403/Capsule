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
  </style>

  <style></style>
</head>
<body>
  <div id="gui">
    <button class="hud-button" id="menuToggle">☰</button>
    <button class="hud-button" id="btn1">1</button>
    <button class="hud-button" id="btn2">2</button>
  </div>

  <div id="menu" class="center">
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
        "three/addons/": "https://cdn.jsdelivr.net/npm/three/examples/jsm/"
      }
    }
  </script>

  <script type="module">
    import * as THREE from 'three';

    const scene = new THREE.Scene();
    const skyColor = new THREE.Color(0x0099FF)
    scene.background = skyColor

    const camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 5;

    const canvas = document.getElementById('c');
    const renderer = new THREE.WebGLRenderer({ canvas });
    renderer.setSize(window.innerWidth, window.innerHeight);

    const geometry = new THREE.CapsuleGeometry(1, 2, 20, 20);
    const material = new THREE.MeshBasicMaterial( {color: 0x0099FFFF} );
    const capsule = new THREE.Mesh(geometry, material);
    scene.add(capsule);

    window.addEventListener('resize', () => {
      renderer.setSize(window.innerWidth, window.innerHeight);
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
    });

    function animate() {
      requestAnimationFrame(animate);
      capsule.rotation.x += 0.01;
      capsule.rotation.y += 0.01;
      renderer.render(scene, camera);
    }
    animate();

    // Buton işlemleri
    document.getElementById('btn1').addEventListener('click', () => alert("Buton 1 tıklandı"));
    document.getElementById('btn2').addEventListener('click', () => alert("Buton 2 tıklandı"));

    // Menü aç/kapat
    document.getElementById('menuToggle').addEventListener('click', () => {
      const menu = document.getElementById('menu');
      menu.classList.toggle('menu-visible');
    });
  </script>
</body>
</html>
