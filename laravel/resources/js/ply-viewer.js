import * as THREE from 'three';
import { PLYLoader } from 'three/examples/jsm/loaders/PLYLoader.js';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

function initViewer(container) {
    //console.log('Initializing viewer for container:', container.id);

    // Skip if already initialized
    if (container.dataset.initialized) {
        console.log('Container already initialized, skipping');
        return;
    }
    container.dataset.initialized = 'true';

    // Check container dimensions
    //console.log('Container dimensions:', container.clientWidth, 'x', container.clientHeight);
    if (container.clientWidth === 0 || container.clientHeight === 0) {
        console.error('Container has zero dimensions!');
        return;
    }

    // 1. Scene setup
    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0xeeeeee);

    // 2. Camera setup
    const camera = new THREE.PerspectiveCamera(
        75,
        container.clientWidth / container.clientHeight,
        0.1,
        1000
    );
    camera.position.z = 150;

    // 3. Renderer setup
    const renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    // 4. Controls
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;

    // 5. Lighting
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.5);
    scene.add(ambientLight);
    const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
    directionalLight.position.set(5, 5, 5);
    scene.add(directionalLight);

    // 6. Load model
    console.log('Loading model from:', container.dataset.model);
    const loader = new PLYLoader();
    const modelPath = container.dataset.model;
		//console.log("Progressbar: " + container.dataset.progressbar); 
    
		loader.load(
        modelPath,
        (geometry) => {
            console.log('Model loaded successfully');
            geometry.computeVertexNormals();
            const material = new THREE.MeshStandardMaterial({
                color: 0x0055ff,
                flatShading: true
            });
            const mesh = new THREE.Mesh(geometry, material);

            // Center and scale
            const box = new THREE.Box3().setFromObject(mesh);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());
            const maxDim = Math.max(size.x, size.y, size.z);
            const scale = 100 / maxDim;

            mesh.scale.set(scale, scale, scale);
            mesh.position.sub(center.multiplyScalar(scale));
            scene.add(mesh);
            console.log('Model added to scene');
        },
        (progress) => {
            //console.log('Loading progress:', (progress.loaded / progress.total * 100).toFixed(2) + '%');
        },
        (error) => {
            console.error('Error loading PLY model:', error);
        }
    );

    // 7. Animation loop
    function animate() {
        requestAnimationFrame(animate);
        controls.update();
        renderer.render(scene, camera);
    }
    animate();

    // 8. Resize handler
    function handleResize() {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    }
    window.addEventListener('resize', handleResize);
}

// Initialize all viewers
document.querySelectorAll('.ply-viewer-container').forEach(container => {
    initViewer(container);
});

// Also initialize any viewers added dynamically
console.log('Setting up MutationObserver for dynamic content');
const observer = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
        mutation.addedNodes.forEach(node => {
            if (node.nodeType === Node.ELEMENT_NODE) {
                if (node.matches('.ply-viewer-container')) {
                    console.log('Found new container via MutationObserver:', node.id);
                    initViewer(node);
                }
                node.querySelectorAll('.ply-viewer-container').forEach(container => {
                    console.log('Found new container in subtree:', container.id);
                    initViewer(container);
                });
            }
        });
    });
});

observer.observe(document.body, {
    childList: true,
    subtree: true
});