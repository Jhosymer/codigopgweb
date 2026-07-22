<div id="navidad-overlay" class="navidad-overlay">
    <div class="contenido-navideno">
        <div class="tree">
            <div class="estrella">⭐</div>
            </div>
        <div class="mensaje-navideno">
            <h1 class="titulo text-white">Feliz Navidad y Próspero Año Nuevo 2026</h1>
        
        </div>
    </div>
</div>
<style>.navidad-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(112, 110, 110, 0.75); /* Gris claro traslúcido */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    backdrop-filter: blur(4px); 
    transition: opacity 1.2s ease-in-out;
}

.contenido-navideno {
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.tree {
    position: relative;
    height: 50vmin;
    width: 35vmin;
    transform-style: preserve-3d;
    animation: spin 4s infinite linear;
    margin-bottom: 30px;
}

@keyframes spin {
    from { transform: rotateY(0deg); }
    to { transform: rotateY(360deg); }
}

.tree__light {
    position: absolute;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    left: 50%;
    transform-style: preserve-3d;
    filter: drop-shadow(0 0 5px currentColor);
}

.estrella {
    position: absolute;
    top: -40px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 2.5rem;
    /* --- Nuevas líneas para hacerla blanca --- */
    filter: brightness(0) invert(1); /* Convierte el emoji a blanco */
    text-shadow: 0 0 15px white;    /* Le da un resplandor blanco */
}



@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Contenedor para los fuegos artificiales */
.firework {
    position: absolute;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    z-index: 9999;
}

@keyframes explode {
    0% { transform: scale(1); opacity: 1; }
    100% { transform: scale(25); opacity: 0; }
}


@keyframes shine {
    to { background-position: 200% center; }
}
</style>

<script>
   document.addEventListener("DOMContentLoaded", function() {
    const tree = document.querySelector('.tree');
    const overlay = document.getElementById('navidad-overlay');
    const totalLights = 100;
    //const colors = ['#e74c3c', '#2ecc71', '#3498db', '#f1c40f'];
     const colors = ['#ffffff'];
    // --- Generar Árbol ---
    for (let i = 0; i < totalLights; i++) {
        const light = document.createElement('div');
        light.className = 'tree__light';
        const progress = i / totalLights; 
        const y = progress * 100; 
        const radius = (1 - progress) * 140; 
        const rotate = i * 25; 
        const color = colors[i % colors.length];
        
        light.style.bottom = `${y}%`;
        light.style.backgroundColor = color;
        light.style.color = color;
        light.style.transform = `rotateY(${rotate}deg) translateZ(${radius}px)`;
        tree.appendChild(light);
    }

    // --- FUNCIÓN DE FUEGOS ARTIFICIALES ---
    function createFirework() {
        const firework = document.createElement('div');
        firework.className = 'firework';
        
        // Posición aleatoria
        const posX = Math.random() * window.innerWidth;
        const posY = Math.random() * (window.innerHeight / 2); // Solo en la mitad superior
        const color = colors[Math.floor(Math.random() * colors.length)];
        
        firework.style.left = posX + 'px';
        firework.style.top = posY + 'px';
        firework.style.backgroundColor = color;
        firework.style.boxShadow = `0 0 15px ${color}`;
        firework.style.animation = 'explode 1.5s ease-out forwards';
        
        overlay.appendChild(firework);
        
        // Limpiar el elemento después de la explosión
        setTimeout(() => firework.remove(), 1500);
    }

    // Lanzar un fuego artificial cada 600ms
    const fireworkInterval = setInterval(createFirework, 600);

    // --- Cierre ---
    setTimeout(() => {
        clearInterval(fireworkInterval); // Detener fuegos artificiales
        overlay.style.opacity = '0';
        setTimeout(() => {
            overlay.remove();
        }, 1200);
    }, 8000);
});
</script>
