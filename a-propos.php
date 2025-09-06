<?php
// Page À propos
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>À propos - Mada's Kids</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .about-container {
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .about-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        
        .about-header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .about-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .about-section {
            padding: 4rem 0;
        }
        
        .about-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .about-card:hover {
            transform: translateY(-5px);
        }
        
        .about-card h3 {
            color: #1a1a1a;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .about-card p {
            color: #666;
            line-height: 1.8;
            font-size: 1.1rem;
        }
        
        .about-icon {
            font-size: 3rem;
            color: #1a1a1a;
            margin-bottom: 1.5rem;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 4rem 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 2rem 1rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .values-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .value-item {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
        }
        
        .value-icon {
            font-size: 2.5rem;
            color: #1a1a1a;
            margin-bottom: 1rem;
        }
        
        .value-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 1rem;
        }
        
        .value-description {
            color: #666;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .about-header h1 {
                font-size: 2.5rem;
            }
            
            .about-card {
                padding: 2rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>

    <!-- Header À propos -->
    <div class="about-header">
        <div class="container">
            <h1><i class="bi bi-heart-fill me-3"></i>Bienvenue chez Mada's Kids</h1>
            <p>Une boutique dédiée à l'univers des enfants, où mode, qualité et héritage familial se rencontrent</p>
        </div>
    </div>

    <div class="about-container">
        <!-- Notre Histoire -->
        <section class="about-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="about-card">
                            <div class="about-icon">
                                <i class="bi bi-heart"></i>
                            </div>
                            <h3>Notre Histoire</h3>
                            <p><strong>Mada's Kids</strong> est une boutique dédiée à l'univers des enfants, proposant une large gamme de vêtements pour les petits de 0 à 15 ans. Du nouveau-né à l'ado, vous y trouverez des tenues de qualité, du quotidien aux looks pour les grandes occasions, à la fois tendance, stylés, confortables et adaptées à chaque étape de leur croissance.</p>
                            
                            <p><strong>Mada's Kids</strong>, c'est plus qu'une simple boutique. C'est aussi une histoire de cœur et de transmission familiale. Le nom "Mada" rend hommage à ma maman, qui, bien avant moi, a consacré son énergie et sa passion à la vente de vêtements pour enfants, qu'il s'agisse de prêt-à-porter ou de friperie. À travers cette boutique, je poursuis avec fierté son héritage, en y apportant ma touche et une vision moderne de la mode enfantine.</p>
                            
                            <p>Chez <strong>Mada's Kids</strong>, chaque article est choisi avec soin, pour que vos enfants soient bien habillés au quotidien comme pour les grandes occasions.</p>
                            
                            <p><em>Mode, qualité, confort et héritage familial — c'est l'univers Mada's Kids.</em></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistiques -->
        <section class="stats-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number">10+</div>
                            <div class="stat-label">Années d'expérience</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Clients satisfaits</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Produits sélectionnés</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Service client</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Nos Valeurs -->
        <section class="values-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold text-dark">Nos Valeurs</h2>
                        <p class="lead text-muted">Les principes qui guident chacune de nos actions</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="value-title">Qualité & Sécurité</div>
                            <div class="value-description">Tous nos produits respectent les normes de sécurité les plus strictes et sont testés pour garantir la sécurité de vos enfants.</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="bi bi-leaf"></i>
                            </div>
                            <div class="value-title">Éco-responsabilité</div>
                            <div class="value-description">Nous privilégions les matériaux naturels et les fabricants engagés dans une démarche environnementale responsable.</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="value-item">
                            <div class="value-icon">
                                <i class="bi bi-heart"></i>
                            </div>
                            <div class="value-title">Service Personnalisé</div>
                            <div class="value-description">Notre équipe est là pour vous accompagner et vous conseiller dans vos choix, avec bienveillance et expertise.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="text-center py-4 bg-light mt-5">
        <p class="mb-0">&copy; 2024 Mada's Kids. Tous droits réservés.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
