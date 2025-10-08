<?php 
session_start();
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍔 Test Menu Hamburger Simple</title>
    <link rel="stylesheet" href="navbar-ecommerce.css">
</head>
<body>
    <!-- Navbar corrigée -->
    <?php include 'navbar-ecommerce.php'; ?>
    
    <div style="padding: 100px 20px; text-align: center;">
        <h1>🍔 Test Menu Hamburger</h1>
        
        <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto;">
            <h3>📱 Instructions de Test</h3>
            
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: left;">
                <h4 style="color: #2c3e50; margin-top: 0;">🖥️ Sur Desktop :</h4>
                <ul style="color: #666;">
                    <li>Le menu hamburger doit être <strong>INVISIBLE</strong></li>
                    <li>Le menu horizontal doit être <strong>VISIBLE</strong></li>
                </ul>
            </div>
            
            <div style="background: #e8f5e8; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: left;">
                <h4 style="color: #155724; margin-top: 0;">📱 Sur Mobile :</h4>
                <ul style="color: #155724;">
                    <li>Le menu hamburger doit être <strong>VISIBLE</strong></li>
                    <li>Cliquer dessus doit ouvrir le menu latéral</li>
                    <li>Les 3 barres doivent se transformer en X</li>
                </ul>
            </div>
            
            <div style="background: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h4 style="color: #856404; margin-top: 0;">🔧 Pour Tester :</h4>
                <p style="color: #856404; margin: 0;">
                    <strong>1.</strong> Ouvrez les outils développeur (F12)<br>
                    <strong>2.</strong> Activez le mode responsive<br>
                    <strong>3.</strong> Testez différentes tailles d'écran<br>
                    <strong>4.</strong> Vérifiez que le hamburger apparaît/disparaît correctement
                </p>
            </div>
            
            <div style="margin: 30px 0;">
                <p><strong>Largeur actuelle :</strong> <span id="currentWidth"></span>px</p>
                <p><strong>Mode :</strong> <span id="currentMode"></span></p>
                <p><strong>Hamburger visible :</strong> <span id="hamburgerStatus"></span></p>
            </div>
        </div>
    </div>
    
    <script src="navbar-ecommerce.js"></script>
    <script>
        function updateStatus() {
            const width = window.innerWidth;
            const hamburger = document.getElementById('mobileMenuToggle');
            
            document.getElementById('currentWidth').textContent = width;
            
            if (width > 768) {
                document.getElementById('currentMode').textContent = 'Desktop';
                document.getElementById('currentMode').style.color = '#007bff';
            } else {
                document.getElementById('currentMode').textContent = 'Mobile';
                document.getElementById('currentMode').style.color = '#28a745';
            }
            
            if (hamburger) {
                const isVisible = window.getComputedStyle(hamburger).display !== 'none';
                document.getElementById('hamburgerStatus').textContent = isVisible ? 'OUI' : 'NON';
                document.getElementById('hamburgerStatus').style.color = isVisible ? '#28a745' : '#dc3545';
            }
        }
        
        // Mettre à jour au chargement et redimensionnement
        updateStatus();
        window.addEventListener('resize', updateStatus);
        
        // Log pour debug
        console.log('🍔 Page de test chargée');
        console.log('📱 Redimensionnez pour tester le comportement');
        
        // Vérifier si les éléments existent
        setTimeout(() => {
            const hamburger = document.getElementById('mobileMenuToggle');
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileOverlay');
            
            console.log('🔍 Éléments trouvés:', {
                hamburger: !!hamburger,
                menu: !!menu,
                overlay: !!overlay
            });
        }, 1000);
    </script>
</body>
</html>
