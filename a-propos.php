<?php
// Page À propos
require_once 'backend/db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>À propos - Mada Kids</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-color: #e74c3c;
            --secondary-color: #3498db;
            --accent-color: #9b59b6;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --text-color: #333;
            --gradient-primary: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --gradient-secondary: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            --gradient-dark: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%);
            --gradient-accent: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
        }

        .about-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .about-header {
            background: var(--gradient-dark);
            color: white;
            padding: 6rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .about-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .about-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .about-header .subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            font-weight: 300;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
        }

        .floating-icon {
            position: absolute;
            font-size: 2rem;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-icon:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-icon:nth-child(2) { top: 60%; right: 15%; animation-delay: 2s; }
        .floating-icon:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }
        .floating-icon:nth-child(4) { top: 40%; right: 30%; animation-delay: 1s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        .about-section {
            padding: 5rem 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
            position: relative;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--gradient-accent);
            border-radius: 2px;
        }

        .section-title p {
            font-size: 1.2rem;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .about-card {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            margin-bottom: 3rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border: 1px solid rgba(231, 76, 60, 0.1);
            position: relative;
            overflow: hidden;
        }

        .about-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-accent);
        }
        
        .about-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .about-card h3 {
            color: var(--dark-color);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .about-card p {
            color: var(--text-color);
            line-height: 1.8;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .about-card .highlight {
            background: linear-gradient(120deg, #e74c3c20 0%, #3498db20 100%);
            padding: 1.5rem;
            border-radius: 15px;
            border-left: 4px solid var(--primary-color);
            margin: 2rem 0;
        }
        
        .about-icon {
            font-size: 3.5rem;
            background: var(--gradient-accent);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }

        .story-timeline {
            position: relative;
            padding: 2rem 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 3rem;
            margin-bottom: 3rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            height: 20px;
            background: var(--gradient-accent);
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 0 0 4px var(--primary-color);
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: 9px;
            top: 20px;
            width: 2px;
            height: calc(100% + 1rem);
            background: var(--gradient-accent);
        }

        .timeline-item:last-child::after {
            display: none;
        }

        .timeline-year {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .timeline-content {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stats-section {
            background: var(--gradient-dark);
            color: white;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        }
        
        .stat-item {
            text-align: center;
            padding: 2rem 1rem;
            position: relative;
            z-index: 2;
        }

        .stat-item:hover .stat-number {
            transform: scale(1.1);
        }
        
        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 0.5rem;
            transition: transform 0.3s ease;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .stat-label {
            font-size: 1.2rem;
            opacity: 0.9;
            font-weight: 500;
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }
        
        .values-section {
            padding: 5rem 0;
            background: var(--light-color);
        }
        
        .value-item {
            text-align: center;
            padding: 3rem 2rem;
            background: white;
            border-radius: 20px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .value-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-secondary);
        }
        
        .value-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .value-icon {
            font-size: 3rem;
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
        }
        
        .value-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }
        
        .value-description {
            color: #666;
            line-height: 1.7;
            font-size: 1.1rem;
        }

        .team-section {
            padding: 5rem 0;
            background: white;
        }

        .team-member {
            text-align: center;
            padding: 2rem;
            background: var(--light-color);
            border-radius: 20px;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .team-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--gradient-accent);
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }

        .team-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .team-role {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .testimonials-section {
            padding: 5rem 0;
            background: var(--light-color);
        }

        .testimonial-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            position: relative;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 4rem;
            color: var(--primary-color);
            opacity: 0.3;
        }

        .testimonial-text {
            font-style: italic;
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .author-info h5 {
            margin: 0;
            color: var(--dark-color);
            font-weight: 600;
        }

        .author-info p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .cta-section {
            padding: 5rem 0;
            background: var(--gradient-dark);
            color: white;
            text-align: center;
        }

        .cta-content h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-cta {
            background: var(--gradient-accent);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-cta:hover {
            background: transparent;
            color: white;
            border-color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
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

            .section-title h2 {
                font-size: 2rem;
            }

            .about-header {
                padding: 4rem 0;
            }
        }

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mode sombre optionnel */
        @media (prefers-color-scheme: dark) {
            :root {
                --light-color: #1a1a1a;
                --text-color: #e9ecef;
                --dark-color: #f8f9fa;
            }
            
            body {
                background-color: #121212;
                color: var(--text-color);
            }
            
            .about-container {
                background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%);
            }
            
            .about-card,
            .value-item,
            .team-member,
            .testimonial-card {
                background: #2c2c2c;
                border-color: rgba(231, 76, 60, 0.2);
            }
            
            .timeline-content {
                background: #2c2c2c;
            }
        }

        /* Améliorations supplémentaires */
        .about-header h1 {
            color: #000;
            text-shadow: 2px 2px 4px rgba(255,255,255,0.5);
        }

        .stat-number {
            background: linear-gradient(45deg, #fff, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <?php include 'backend/navbar.php'; ?>

    <!-- Header À propos -->
    <div class="about-header">
        <div class="floating-elements">
            <i class="bi bi-heart floating-icon"></i>
            <i class="bi bi-star floating-icon"></i>
            <i class="bi bi-gift floating-icon"></i>
            <i class="bi bi-emoji-smile floating-icon"></i>
        </div>
        <div class="container">
            <h1><i class="bi bi-heart-fill me-3"></i>Bienvenue chez Mada Kids</h1>
            <p class="subtitle">Une boutique dédiée à l'univers des enfants, où mode, qualité et héritage familial se rencontrent pour créer des moments magiques</p>
        </div>
    </div>

    <div class="about-container">
        <!-- Notre Histoire -->
        <section class="about-section">
            <div class="container">
                <div class="section-title fade-in">
                    <h2>Notre Histoire</h2>
                    <p>Découvrez l'histoire touchante qui se cache derrière Mada Kids</p>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="about-card fade-in">
                            <h3><i class="bi bi-heart"></i> Une Histoire de Cœur</h3>
                            <p><strong>Mada Kids</strong> est bien plus qu'une simple boutique de vêtements pour enfants. C'est l'aboutissement d'un rêve familial, d'une passion transmise de génération en génération, et d'un amour profond pour l'univers de l'enfance.</p>
                            
                            <div class="highlight">
                                <p><strong>Le nom "Mada"</strong> rend hommage à ma maman, qui, bien avant moi, a consacré son énergie et sa passion à la vente de vêtements pour enfants. Qu'il s'agisse de prêt-à-porter ou de friperie, elle a toujours su choisir les plus beaux vêtements pour habiller les petits avec amour et attention.</p>
                            </div>
                            
                            <p>À travers cette boutique, je poursuis avec fierté son héritage, en y apportant ma touche personnelle et une vision moderne de la mode enfantine. Chaque collection est soigneusement sélectionnée pour offrir aux enfants de 0 à 15 ans des tenues qui allient style, confort et qualité.</p>
                            
                            <p>Chez <strong>Mada Kids</strong>, nous croyons que chaque enfant mérite de se sentir beau, confiant et à l'aise dans ses vêtements. C'est pourquoi nous proposons une gamme complète allant du quotidien aux grandes occasions, en passant par les looks tendance qui font briller les yeux des petits et des grands.</p>
                            
                            <p><em>Mode, qualité, confort et héritage familial — c'est l'univers unique de Mada Kids, où chaque vêtement raconte une histoire d'amour.</em></p>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="story-timeline fade-in">
                            <div class="timeline-item">
                                <div class="timeline-year">2010</div>
                                <div class="timeline-content">
                                    <h5>Les Premiers Pas</h5>
                                    <p>Mada commence son aventure dans la vente de vêtements pour enfants, avec une passion débordante et un œil expert pour la qualité.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2015</div>
                                <div class="timeline-content">
                                    <h5>L'Expansion</h5>
                                    <p>Développement de la clientèle et élargissement de la gamme de produits, toujours avec le même souci du détail et de la satisfaction client.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2020</div>
                                <div class="timeline-content">
                                    <h5>La Transmission</h5>
                                    <p>Passage de relais à la nouvelle génération, avec l'ambition de moderniser tout en préservant les valeurs familiales.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-year">2024</div>
                                <div class="timeline-content">
                                    <h5>Mada Kids Aujourd'hui</h5>
                                    <p>Une boutique moderne qui allie tradition familiale et innovation, pour offrir la meilleure expérience shopping aux familles.</p>
                                </div>
                            </div>
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
                        <div class="stat-item fade-in">
                            <div class="stat-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="stat-number">15+</div>
                            <div class="stat-label">Années d'expérience</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item fade-in">
                            <div class="stat-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-number">5K+</div>
                            <div class="stat-label">Familles satisfaites</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item fade-in">
                            <div class="stat-icon">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <div class="stat-number">2000+</div>
                            <div class="stat-label">Produits sélectionnés</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-item fade-in">
                            <div class="stat-icon">
                                <i class="bi bi-headset"></i>
                            </div>
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
                <div class="section-title fade-in">
                    <h2>Nos Valeurs</h2>
                    <p>Les principes qui guident chacune de nos actions et choix</p>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="value-item fade-in">
                            <div class="value-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="value-title">Qualité & Sécurité</div>
                            <div class="value-description">Tous nos produits respectent les normes de sécurité les plus strictes et sont testés pour garantir le bien-être et la sécurité de vos enfants. Nous ne proposons que des marques de confiance.</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="value-item fade-in">
                            <div class="value-icon">
                                <i class="bi bi-leaf"></i>
                            </div>
                            <div class="value-title">Éco-responsabilité</div>
                            <div class="value-description">Nous privilégions les matériaux naturels, les fabricants engagés dans une démarche environnementale responsable, et encourageons la mode durable pour un avenir meilleur.</div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="value-item fade-in">
                            <div class="value-icon">
                                <i class="bi bi-heart"></i>
                            </div>
                            <div class="value-title">Service Personnalisé</div>
                            <div class="value-description">Notre équipe est là pour vous accompagner et vous conseiller dans vos choix, avec bienveillance, expertise et une attention particulière à vos besoins spécifiques.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Notre Équipe -->
        <section class="team-section">
            <div class="container">
                <div class="section-title fade-in">
                    <h2>Notre Équipe</h2>
                    <p>Des passionnés à votre service</p>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="team-member fade-in">
                            <div class="team-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="team-name">Mada</div>
                            <div class="team-role">Fondatrice & Directrice</div>
                            <p>Passionnée de mode enfantine depuis plus de 15 ans, Mada transmet son expertise et son amour pour les beaux vêtements.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="team-member fade-in">
                            <div class="team-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="team-name">L'Équipe Mada Kids</div>
                            <div class="team-role">Conseillères Mode</div>
                            <p>Nos conseillères expertes vous accompagnent dans vos choix avec bienveillance et professionnalisme.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="team-member fade-in">
                            <div class="team-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <div class="team-name">Service Client</div>
                            <div class="team-role">Support & Satisfaction</div>
                            <p>Notre équipe dédiée est là pour répondre à toutes vos questions et assurer votre satisfaction.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Témoignages -->
        <section class="testimonials-section">
            <div class="container">
                <div class="section-title fade-in">
                    <h2>Ce Que Disent Nos Clients</h2>
                    <p>La satisfaction de nos familles est notre plus belle récompense</p>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="testimonial-card fade-in">
                            <div class="testimonial-text">
                                "Mada Kids a habillé mes enfants depuis leur naissance. La qualité est exceptionnelle et le service toujours impeccable. Je recommande vivement !"
                            </div>
                            <div class="testimonial-author">
                                <div class="author-avatar">S</div>
                                <div class="author-info">
                                    <h5>Sarah M.</h5>
                                    <p>Maman de 2 enfants</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="testimonial-card fade-in">
                            <div class="testimonial-text">
                                "Une boutique qui allie parfaitement style et confort. Mes enfants adorent leurs vêtements et moi j'apprécie la qualité et le rapport qualité-prix."
                            </div>
                            <div class="testimonial-author">
                                <div class="author-avatar">M</div>
                                <div class="author-info">
                                    <h5>Marie L.</h5>
                                    <p>Maman de 3 enfants</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="testimonial-card fade-in">
                            <div class="testimonial-text">
                                "L'histoire de Mada Kids me touche beaucoup. On sent vraiment l'amour et la passion dans chaque vêtement proposé. Une boutique de cœur !"
                            </div>
                            <div class="testimonial-author">
                                <div class="author-avatar">A</div>
                                <div class="author-info">
                                    <h5>Amélie D.</h5>
                                    <p>Maman de 1 enfant</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content fade-in">
                    <h2>Rejoignez la Famille Mada Kids</h2>
                    <p>Découvrez notre collection et offrez à vos enfants des vêtements qui les feront briller</p>
                    <a href="index.php" class="btn-cta">
                        <i class="bi bi-shop me-2"></i>Découvrir la Boutique
                    </a>
                </div>
            </div>
        </section>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation d'apparition au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observer tous les éléments avec la classe fade-in
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Animation des statistiques
        const animateNumbers = () => {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const target = parseInt(stat.textContent.replace(/\D/g, ''));
                const suffix = stat.textContent.replace(/\d/g, '');
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        stat.textContent = target + suffix;
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(current) + suffix;
                    }
                }, 30);
            });
        };

        // Déclencher l'animation des chiffres quand la section stats est visible
        const statsSection = document.querySelector('.stats-section');
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateNumbers();
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        if (statsSection) {
            statsObserver.observe(statsSection);
        }
    </script>
</body>
</html>