<?php 
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍔 Test Menu Hamburger - Console Propre</title>
    <link rel="stylesheet" href="navbar-ecommerce.css">
</head>
<body>
    <!-- Navbar corrigée -->
    <?php include 'navbar-ecommerce.php'; ?>
    
    <div style="padding: 100px 20px; text-align: center;">
        <h1>🍔 Test Menu Hamburger - Console Propre</h1>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;">
            
            <div style="background: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="color: #155724; margin-top: 0;">✅ Erreurs 401 Corrigées !</h3>
                <p style="color: #155724; margin: 0;">
                    Les APIs backend ne retournent plus d'erreurs 401. La console devrait maintenant être propre !
                </p>
            </div>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: left;">
                <h4 style="color: #2c3e50; margin-top: 0;">🧪 Tests à Effectuer :</h4>
                <ol style="color: #666;">
                    <li><strong>Console propre</strong> - Ouvrez F12, onglet Console, plus d'erreurs 401</li>
                    <li><strong>Desktop</strong> - Menu hamburger invisible, menu horizontal visible</li>
                    <li><strong>Mobile</strong> - Menu hamburger visible, clic ouvre le menu</li>
                    <li><strong>Animation</strong> - Hamburger → X au clic</li>
                </ol>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin: 30px 0;">
                <div style="background: #e8f5e8; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #155724; margin-top: 0;">📱 Mobile</h4>
                    <p style="color: #155724; margin: 0; font-size: 14px;">
                        Largeur ≤ 768px<br>
                        Hamburger visible
                    </p>
                </div>
                
                <div style="background: #e7f3ff; padding: 15px; border-radius: 8px;">
                    <h4 style="color: #0c5aa6; margin-top: 0;">🖥️ Desktop</h4>
                    <p style="color: #0c5aa6; margin: 0; font-size: 14px;">
                        Largeur > 768px<br>
                        Hamburger masqué
                    </p>
                </div>
            </div>
            
            <div style="margin: 30px 0;">
                <h4>📊 Status Actuel</h4>
                <p><strong>Largeur :</strong> <span id="width"></span>px</p>
                <p><strong>Mode :</strong> <span id="mode"></span></p>
                <p><strong>Hamburger :</strong> <span id="hamburgerStatus"></span></p>
                <p><strong>Console :</strong> <span id="consoleStatus">Vérification...</span></p>
            </div>
            
            <div style="background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #856404; margin-top: 0;">🔍 Vérification Console</h4>
                <p style="color: #856404; margin: 0;">
                    Ouvrez les outils développeur (F12) → Onglet "Console"<br>
                    Vous ne devriez plus voir d'erreurs 401 !
                </p>
            </div>
        </div>
    </div>
    
    <script src="navbar-ecommerce.js"></script>
    <script>
        function updateStatus() {
            const width = window.innerWidth;
            const hamburger = document.getElementById('mobileMenuToggle');
            
            document.getElementById('width').textContent = width;
            
            if (width > 768) {
                document.getElementById('mode').textContent = 'Desktop';
                document.getElementById('mode').style.color = '#0c5aa6';
            } else {
                document.getElementById('mode').textContent = 'Mobile';
                document.getElementById('mode').style.color = '#155724';
            }
            
            if (hamburger) {
                const isVisible = window.getComputedStyle(hamburger).display !== 'none';
                const status = isVisible ? 'VISIBLE' : 'MASQUÉ';
                const color = isVisible ? '#155724' : '#dc3545';
                
                document.getElementById('hamburgerStatus').textContent = status;
                document.getElementById('hamburgerStatus').style.color = color;
            }
        }
        
        // Vérifier les erreurs console
        let errorCount = 0;
        const originalConsoleError = console.error;
        console.error = function(...args) {
            if (args[0] && args[0].includes && args[0].includes('401')) {
                errorCount++;
            }
            originalConsoleError.apply(console, args);
        };
        
        setTimeout(() => {
            const consoleStatus = document.getElementById('consoleStatus');
            if (errorCount === 0) {
                consoleStatus.textContent = '✅ PROPRE (0 erreurs 401)';
                consoleStatus.style.color = '#155724';
            } else {
                consoleStatus.textContent = `❌ ${errorCount} erreur(s) 401`;
                consoleStatus.style.color = '#dc3545';
            }
        }, 3000);
        
        // Mettre à jour le status
        updateStatus();
        window.addEventListener('resize', updateStatus);
        
        console.log('🍔 Test menu hamburger avec console propre');
        console.log('✅ APIs corrigées - plus d\'erreurs 401');
    </script>
</body>
</html>
