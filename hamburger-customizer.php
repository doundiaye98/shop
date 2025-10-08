<?php 
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍔 Personnalisateur Menu Hamburger - Ma Boutique</title>
    
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .customizer-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .customizer-header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        
        .customizer-header h1 {
            font-size: 2.5rem;
            margin: 0 0 10px 0;
        }
        
        .customizer-header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .customizer-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .control-panel {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            height: fit-content;
        }
        
        .preview-panel {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .control-group {
            margin-bottom: 25px;
        }
        
        .control-group label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .control-group input[type="range"] {
            width: 100%;
            margin: 10px 0;
        }
        
        .control-group input[type="color"] {
            width: 60px;
            height: 40px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .control-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .value-display {
            display: inline-block;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: #666;
            margin-left: 10px;
        }
        
        /* Menu Hamburger Personnalisable */
        .custom-hamburger {
            display: inline-flex;
            flex-direction: column;
            cursor: pointer;
            padding: 15px;
            background: var(--bg-color, #fff);
            border: var(--border-width, 2px) solid var(--border-color, #ddd);
            border-radius: var(--border-radius, 8px);
            transition: all 0.3s ease;
            justify-content: center;
            align-items: center;
        }
        
        .custom-hamburger:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .custom-burger-line {
            display: block;
            background: var(--line-color, #333);
            transition: all var(--animation-speed, 0.3s) ease;
            border-radius: var(--line-radius, 2px);
        }
        
        /* Animation vers X */
        .custom-hamburger.active .custom-burger-line:nth-child(1) {
            transform: rotate(45deg) translate(var(--translate-distance, 6px), var(--translate-distance, 6px));
        }
        
        .custom-hamburger.active .custom-burger-line:nth-child(2) {
            opacity: 0;
        }
        
        .custom-hamburger.active .custom-burger-line:nth-child(3) {
            transform: rotate(-45deg) translate(var(--translate-distance, 6px), calc(-1 * var(--translate-distance, 6px)));
        }
        
        .preview-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .preview-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        
        .size-demo {
            display: flex;
            gap: 20px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin: 30px 0;
        }
        
        .size-demo-item {
            text-align: center;
        }
        
        .size-demo-item span {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 8px;
        }
        
        .code-output {
            background: #2d3748;
            color: #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            margin: 20px 0;
            overflow-x: auto;
        }
        
        .code-title {
            color: #81c784;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .apply-btn {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: transform 0.3s ease;
        }
        
        .apply-btn:hover {
            transform: translateY(-2px);
        }
        
        .preset-buttons {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .preset-btn {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .preset-btn:hover {
            background: #e9ecef;
            border-color: #667eea;
        }
        
        .preset-btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        @media (max-width: 768px) {
            .customizer-grid {
                grid-template-columns: 1fr;
            }
            
            .customizer-header h1 {
                font-size: 2rem;
            }
            
            .size-demo {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="customizer-container">
        <div class="customizer-header">
            <h1>🍔 Personnalisateur Menu Hamburger</h1>
            <p>Créez le menu hamburger parfait pour votre boutique !</p>
        </div>
        
        <div class="customizer-grid">
            <!-- Panneau de Contrôle -->
            <div class="control-panel">
                <h3 style="margin-top: 0; color: #2c3e50;">🎨 Personnalisation</h3>
                
                <!-- Presets -->
                <div class="control-group">
                    <label>🚀 Styles Prédéfinis</label>
                    <div class="preset-buttons">
                        <button class="preset-btn active" onclick="applyPreset('default')">Défaut</button>
                        <button class="preset-btn" onclick="applyPreset('modern')">Moderne</button>
                        <button class="preset-btn" onclick="applyPreset('minimal')">Minimal</button>
                        <button class="preset-btn" onclick="applyPreset('colorful')">Coloré</button>
                    </div>
                </div>
                
                <!-- Dimensions -->
                <div class="control-group">
                    <label>📏 Largeur des barres</label>
                    <input type="range" id="lineWidth" min="20" max="40" value="25" oninput="updateHamburger()">
                    <span class="value-display" id="lineWidthValue">25px</span>
                </div>
                
                <div class="control-group">
                    <label>📐 Hauteur des barres</label>
                    <input type="range" id="lineHeight" min="2" max="6" value="3" oninput="updateHamburger()">
                    <span class="value-display" id="lineHeightValue">3px</span>
                </div>
                
                <div class="control-group">
                    <label>📊 Espacement entre barres</label>
                    <input type="range" id="lineSpacing" min="2" max="8" value="3" oninput="updateHamburger()">
                    <span class="value-display" id="lineSpacingValue">3px</span>
                </div>
                
                <!-- Couleurs -->
                <div class="control-group">
                    <label>🎨 Couleur des barres</label>
                    <input type="color" id="lineColor" value="#333333" oninput="updateHamburger()">
                </div>
                
                <div class="control-group">
                    <label>🎨 Couleur de fond</label>
                    <input type="color" id="bgColor" value="#ffffff" oninput="updateHamburger()">
                </div>
                
                <div class="control-group">
                    <label>🎨 Couleur bordure</label>
                    <input type="color" id="borderColor" value="#dddddd" oninput="updateHamburger()">
                </div>
                
                <!-- Style -->
                <div class="control-group">
                    <label>🔄 Arrondi des barres</label>
                    <input type="range" id="lineRadius" min="0" max="10" value="2" oninput="updateHamburger()">
                    <span class="value-display" id="lineRadiusValue">2px</span>
                </div>
                
                <div class="control-group">
                    <label>🔄 Arrondi du conteneur</label>
                    <input type="range" id="borderRadius" min="0" max="20" value="8" oninput="updateHamburger()">
                    <span class="value-display" id="borderRadiusValue">8px</span>
                </div>
                
                <!-- Animation -->
                <div class="control-group">
                    <label>⚡ Vitesse d'animation</label>
                    <input type="range" id="animationSpeed" min="0.1" max="1" step="0.1" value="0.3" oninput="updateHamburger()">
                    <span class="value-display" id="animationSpeedValue">0.3s</span>
                </div>
                
                <button class="apply-btn" onclick="generateCode()">
                    🚀 Générer le Code CSS
                </button>
            </div>
            
            <!-- Panneau de Prévisualisation -->
            <div class="preview-panel">
                <h3 style="margin-top: 0; color: #2c3e50;">👀 Prévisualisation</h3>
                
                <div class="preview-section">
                    <h4>🍔 Votre Menu Hamburger</h4>
                    <div class="custom-hamburger" id="customHamburger" onclick="toggleCustomHamburger()">
                        <span class="custom-burger-line"></span>
                        <span class="custom-burger-line"></span>
                        <span class="custom-burger-line"></span>
                    </div>
                    <p style="color: #666; font-size: 14px; margin-top: 15px;">
                        Cliquez pour voir l'animation !
                    </p>
                </div>
                
                <div class="size-demo">
                    <div class="size-demo-item">
                        <div class="custom-hamburger" style="transform: scale(0.8);" onclick="toggleCustomHamburger()">
                            <span class="custom-burger-line"></span>
                            <span class="custom-burger-line"></span>
                            <span class="custom-burger-line"></span>
                        </div>
                        <span>Mobile</span>
                    </div>
                    
                    <div class="size-demo-item">
                        <div class="custom-hamburger" onclick="toggleCustomHamburger()">
                            <span class="custom-burger-line"></span>
                            <span class="custom-burger-line"></span>
                            <span class="custom-burger-line"></span>
                        </div>
                        <span>Tablette</span>
                    </div>
                    
                    <div class="size-demo-item">
                        <div class="custom-hamburger" style="transform: scale(1.2);" onclick="toggleCustomHamburger()">
                            <span class="custom-burger-line"></span>
                            <span class="custom-burger-line"></span>
                            <span class="custom-burger-line"></span>
                        </div>
                        <span>Desktop</span>
                    </div>
                </div>
                
                <!-- Code généré -->
                <div id="generatedCode" style="display: none;">
                    <h4>📝 Code CSS Généré</h4>
                    <div class="code-output" id="cssOutput"></div>
                    
                    <h4>📝 Code HTML</h4>
                    <div class="code-output">
                        <div class="code-title">HTML :</div>
&lt;div class="mobile-menu-toggle" id="mobileMenuToggle"&gt;
    &lt;span class="burger-line"&gt;&lt;/span&gt;
    &lt;span class="burger-line"&gt;&lt;/span&gt;
    &lt;span class="burger-line"&gt;&lt;/span&gt;
&lt;/div&gt;
                    </div>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="background: white; color: #667eea; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                🏠 Retour au site
            </a>
        </div>
    </div>
    
    <script>
        // Configuration actuelle
        let currentConfig = {
            lineWidth: 25,
            lineHeight: 3,
            lineSpacing: 3,
            lineColor: '#333333',
            bgColor: '#ffffff',
            borderColor: '#dddddd',
            lineRadius: 2,
            borderRadius: 8,
            animationSpeed: 0.3
        };
        
        // Presets
        const presets = {
            default: {
                lineWidth: 25, lineHeight: 3, lineSpacing: 3,
                lineColor: '#333333', bgColor: '#ffffff', borderColor: '#dddddd',
                lineRadius: 2, borderRadius: 8, animationSpeed: 0.3
            },
            modern: {
                lineWidth: 28, lineHeight: 3, lineSpacing: 4,
                lineColor: '#667eea', bgColor: '#f8f9fa', borderColor: '#667eea',
                lineRadius: 6, borderRadius: 12, animationSpeed: 0.4
            },
            minimal: {
                lineWidth: 24, lineHeight: 2, lineSpacing: 4,
                lineColor: '#666666', bgColor: '#ffffff', borderColor: '#f0f0f0',
                lineRadius: 0, borderRadius: 4, animationSpeed: 0.2
            },
            colorful: {
                lineWidth: 30, lineHeight: 4, lineSpacing: 3,
                lineColor: '#e74c3c', bgColor: '#fff3cd', borderColor: '#ffc107',
                lineRadius: 8, borderRadius: 15, animationSpeed: 0.5
            }
        };
        
        function applyPreset(presetName) {
            // Retirer active de tous les boutons
            document.querySelectorAll('.preset-btn').forEach(btn => btn.classList.remove('active'));
            
            // Ajouter active au bouton cliqué
            event.target.classList.add('active');
            
            const preset = presets[presetName];
            currentConfig = {...preset};
            
            // Mettre à jour les contrôles
            Object.keys(preset).forEach(key => {
                const element = document.getElementById(key);
                if (element) {
                    element.value = preset[key];
                }
            });
            
            updateHamburger();
        }
        
        function updateHamburger() {
            // Récupérer les valeurs
            currentConfig.lineWidth = document.getElementById('lineWidth').value;
            currentConfig.lineHeight = document.getElementById('lineHeight').value;
            currentConfig.lineSpacing = document.getElementById('lineSpacing').value;
            currentConfig.lineColor = document.getElementById('lineColor').value;
            currentConfig.bgColor = document.getElementById('bgColor').value;
            currentConfig.borderColor = document.getElementById('borderColor').value;
            currentConfig.lineRadius = document.getElementById('lineRadius').value;
            currentConfig.borderRadius = document.getElementById('borderRadius').value;
            currentConfig.animationSpeed = document.getElementById('animationSpeed').value;
            
            // Mettre à jour les affichages de valeur
            document.getElementById('lineWidthValue').textContent = currentConfig.lineWidth + 'px';
            document.getElementById('lineHeightValue').textContent = currentConfig.lineHeight + 'px';
            document.getElementById('lineSpacingValue').textContent = currentConfig.lineSpacing + 'px';
            document.getElementById('lineRadiusValue').textContent = currentConfig.lineRadius + 'px';
            document.getElementById('borderRadiusValue').textContent = currentConfig.borderRadius + 'px';
            document.getElementById('animationSpeedValue').textContent = currentConfig.animationSpeed + 's';
            
            // Appliquer les styles CSS
            const root = document.documentElement;
            root.style.setProperty('--line-width', currentConfig.lineWidth + 'px');
            root.style.setProperty('--line-height', currentConfig.lineHeight + 'px');
            root.style.setProperty('--line-spacing', currentConfig.lineSpacing + 'px');
            root.style.setProperty('--line-color', currentConfig.lineColor);
            root.style.setProperty('--bg-color', currentConfig.bgColor);
            root.style.setProperty('--border-color', currentConfig.borderColor);
            root.style.setProperty('--line-radius', currentConfig.lineRadius + 'px');
            root.style.setProperty('--border-radius', currentConfig.borderRadius + 'px');
            root.style.setProperty('--animation-speed', currentConfig.animationSpeed + 's');
            root.style.setProperty('--translate-distance', (parseInt(currentConfig.lineHeight) + parseInt(currentConfig.lineSpacing)) + 'px');
            
            // Mettre à jour les dimensions des barres
            document.querySelectorAll('.custom-burger-line').forEach(line => {
                line.style.width = currentConfig.lineWidth + 'px';
                line.style.height = currentConfig.lineHeight + 'px';
                line.style.margin = currentConfig.lineSpacing + 'px 0';
            });
        }
        
        function toggleCustomHamburger() {
            document.querySelectorAll('.custom-hamburger').forEach(burger => {
                burger.classList.toggle('active');
            });
        }
        
        function generateCode() {
            const css = `
/* Menu Hamburger Personnalisé */
.mobile-menu-toggle {
    display: flex;
    flex-direction: column;
    cursor: pointer;
    padding: 15px;
    background: ${currentConfig.bgColor};
    border: 2px solid ${currentConfig.borderColor};
    border-radius: ${currentConfig.borderRadius}px;
    transition: all 0.3s ease;
}

.mobile-menu-toggle:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.burger-line {
    width: ${currentConfig.lineWidth}px;
    height: ${currentConfig.lineHeight}px;
    background: ${currentConfig.lineColor};
    margin: ${currentConfig.lineSpacing}px 0;
    transition: all ${currentConfig.animationSpeed}s ease;
    border-radius: ${currentConfig.lineRadius}px;
}

/* Animation vers X */
.mobile-menu-toggle.active .burger-line:nth-child(1) {
    transform: rotate(45deg) translate(${parseInt(currentConfig.lineHeight) + parseInt(currentConfig.lineSpacing)}px, ${parseInt(currentConfig.lineHeight) + parseInt(currentConfig.lineSpacing)}px);
}

.mobile-menu-toggle.active .burger-line:nth-child(2) {
    opacity: 0;
}

.mobile-menu-toggle.active .burger-line:nth-child(3) {
    transform: rotate(-45deg) translate(${parseInt(currentConfig.lineHeight) + parseInt(currentConfig.lineSpacing)}px, -${parseInt(currentConfig.lineHeight) + parseInt(currentConfig.lineSpacing)}px);
}`;
            
            document.getElementById('cssOutput').innerHTML = `<div class="code-title">CSS :</div><pre>${css}</pre>`;
            document.getElementById('generatedCode').style.display = 'block';
            
            // Scroll vers le code
            document.getElementById('generatedCode').scrollIntoView({ behavior: 'smooth' });
        }
        
        // Initialiser
        updateHamburger();
        
        console.log('🍔 Personnalisateur Menu Hamburger chargé !');
        console.log('🎨 Personnalisez votre menu hamburger et générez le code CSS');
    </script>
</body>
</html>
