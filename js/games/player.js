import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';
import { GUI } from 'three/addons/libs/lil-gui.module.min.js';


const scene = new THREE.Scene();
const skyColor = new THREE.Color(0x0099FF)
//scene.background = skyColor

const camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.z = 5;

const canvas = document.getElementById('c');
const renderer = new THREE.WebGLRenderer({ canvas });
renderer.setPixelRatio( window.devicePixelRatio );
renderer.setSize( window.innerWidth, window.innerHeight );
renderer.setAnimationLoop(rendererLoop);
document.body.appendChild(renderer.domElement);

const geometry = new THREE.CapsuleGeometry(1, 2, 20, 20);
const capsuleColor = new THREE.Color(0xFFFFFFFF)
const material = new THREE.MeshStandardMaterial(capsuleColor);
const capsule = new THREE.Mesh(geometry, material);
scene.add(capsule);

const boxGeometry = new THREE.BoxGeometry(10,1,10)
const boxMaterial = new THREE.MeshStandardMaterial()
const box = new THREE.Mesh(boxGeometry, boxMaterial);
box.position.x = 20
scene.add(box)

const pointLight = new THREE.PointLight(0xffffff, 100)
pointLight.position.set(5,5,5)

scene.add(pointLight)

const lightHelper = new THREE.PointLightHelper(pointLight)
const gridHelper = new THREE.GridHelper(200,50)
//scene.add(gridHelper)
scene.add(lightHelper)

const controls = new OrbitControls(camera, renderer.domElement);

const axesHelper = new THREE.AxesHelper( 5 );
scene.add( axesHelper );

const helper = new THREE.CameraHelper( camera );
//scene.add( helper );

const gui = new GUI()
gui.add(capsule.rotation, "x", 0, Math.PI * 2)
gui.add(capsule.rotation, "y", 0, Math.PI * 2)
gui.add(capsule.rotation, "z", 0, Math.PI * 2)

var stats = new Stats();
stats.showPanel( 1 ); // 0: fps, 1: ms, 2: mb, 3+: custom
document.body.appendChild( stats.dom );

window.addEventListener('resize', () => {
    renderer.setSize(window.innerWidth, window.innerHeight);
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    helper.update()
});

function animate() {
    requestAnimationFrame(animate);

    stats.begin();

    //capsule.rotation.x += 0.01;
    //capsule.rotation.y += 0.005;
    //capsule.rotation.z += 0.01;

    controls.update()
}

function rendererLoop() {
    renderer.render(scene, camera);

    stats.end();
}

animate();

// Buton işlemleri
document.getElementById('btn1').addEventListener('click', () => alert("Buton 1 tıklandı")); 

// Menü aç/kapat
document.getElementById('menuToggle').addEventListener('click', () => {
    const menu = document.getElementById('menu');
    menu.classList.toggle('menu-visible');
});