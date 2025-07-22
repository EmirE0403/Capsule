<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <title>Three.js + CoreGui</title>
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
      width: 50px;
      height: 50px;
      background: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-family: sans-serif;
      font-size: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      box-sizing: border-box;
    }

    .hud-button:hover {
      background: rgba(255, 255, 255, 0.2);
    }
  </style>
</head>
<body>
  <div id="gui">
    <button class="hud-button" id="btn1">Menü</button>
    <button class="hud-button" id="btn2">Sohbet</button>
  </div>

  <canvas id="c"></canvas>

  <script type="module">
    import * as THREE from 'https://cdn.skypack.dev/three@0.154.0';

    const canvas = document.getElementById('c');
    const renderer = new THREE.WebGLRenderer({ canvas });
    renderer.setSize(window.innerWidth, window.innerHeight);

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.z = 5;

    const geometry = new THREE.BoxGeometry();
    const material = new THREE.MeshNormalMaterial();
    const cube = new THREE.Mesh(geometry, material);
    scene.add(cube);

    window.addEventListener('resize', () => {
      renderer.setSize(window.innerWidth, window.innerHeight);
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
    });

    function animate() {
      requestAnimationFrame(animate);
      cube.rotation.x += 0.01;
      cube.rotation.y += 0.01;
      renderer.render(scene, camera);
    }
    animate();

    document.getElementById('btn1').addEventListener('click', () => alert("Buton 1 tıklandı"));
    document.getElementById('btn2').addEventListener('click', () => alert("Buton 2 tıklandı"));
  </script>
</body>
</html>
