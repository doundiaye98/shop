<?php 
// Page Contact
require_once 'backend/db.php';
include 'backend/navbar.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact - Ma Boutique</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .contact-container {
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .contact-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            padding: 4rem 0;
            text-align: center;
        }
        
        .contact-header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .contact-header p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .contact-section {
            padding: 4rem 0;
        }
        
        .contact-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            height: 100%;
        }
        
        .contact-card h3 {
            color: #1a1a1a;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .contact-info {
            margin-bottom: 2rem;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .contact-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        
        .contact-icon {
            font-size: 1.5rem;
            color: #1a1a1a;
            margin-right: 1rem;
            width: 40px;
            text-align: center;
        }
        
        .contact-details h5 {
            color: #1a1a1a;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .contact-details p {
            color: #666;
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1a1a1a;
            font-weight: 600;
            font-size: 0.95rem;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #1a1a1a;
            background: white;
            box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .submit-btn:hover {
            background: linear-gradient(135deg, #333 0%, #1a1a1a 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            color: white;
        }
        
        .map-section {
            padding: 4rem 0;
            background: white;
        }
        
        .map-container {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #ccc;
        }
        
        .map-placeholder {
            color: #666;
            font-size: 1.1rem;
        }
        
        .faq-section {
            padding: 4rem 0;
            background: #f8f9fa;
        }
        
        .faq-item {
            background: white;
            border-radius: 10px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            color: #1a1a1a;
            transition: all 0.3s ease;
        }
        
        .faq-question:hover {
            background: #f8f9fa;
        }
        
        .faq-answer {
            padding: 1.5rem;
            color: #666;
            line-height: 1.6;
            display: none;
        }
        
        .faq-answer.active {
            display: block;
        }
        
        @media (max-width: 768px) {
            .contact-header h1 {
                font-size: 2.5rem;
            }
            
            .contact-card {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Header Contact -->
    <div class="contact-header">
        <div class="container">
            <h1><i class="bi bi-envelope-fill me-3"></i>Contactez-nous</h1>
            <p>Notre équipe est là pour vous accompagner et répondre à toutes vos questions</p>
        </div>
    </div>

    <div class="contact-container">
        <!-- Section Contact -->
        <section class="contact-section">
            <div class="container">
                <div class="row">
                    <!-- Informations de contact -->
                    <div class="col-lg-4">
                        <div class="contact-card">
                            <h3><i class="bi bi-info-circle me-2"></i>Nos Coordonnées</h3>
                            
                            <div class="contact-info">
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div class="contact-details">
                                        <h5>Adresse</h5>
                                        <p>123 Rue de la Mode<br>75001 Paris, France</p>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <div class="contact-details">
                                        <h5>Téléphone</h5>
                                        <p>+33 6 10 95 17 33</p>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="contact-details">
                                        <h5>Email</h5>
                                        <p>contact@maboutique.fr</p>
                                    </div>
                                </div>
                                
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div class="contact-details">
                                        <h5>Horaires</h5>
                                        <p>Lun-Sam: 9h-19h<br>Dimanche: 10h-18h</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Formulaire de contact -->
                    <div class="col-lg-8">
                        <div class="contact-card">
                            <h3><i class="bi bi-chat-dots me-2"></i>Envoyez-nous un message</h3>
                            
                            <form id="contactForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstName">Prénom *</label>
                                            <input type="text" id="firstName" name="firstName" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastName">Nom *</label>
                                            <input type="text" id="lastName" name="lastName" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email *</label>
                                            <input type="email" id="email" name="email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Téléphone</label>
                                            <input type="tel" id="phone" name="phone">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject">Sujet *</label>
                                    <select id="subject" name="subject" required>
                                        <option value="">Choisissez un sujet</option>
                                        <option value="commande">Question sur une commande</option>
                                        <option value="produit">Information sur un produit</option>
                                        <option value="retour">Retour/Remboursement</option>
                                        <option value="livraison">Livraison</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message">Message *</label>
                                    <textarea id="message" name="message" placeholder="Décrivez votre demande..." required></textarea>
                                </div>
                                
                                <button type="submit" class="submit-btn">
                                    <i class="bi bi-send me-2"></i>
                                    Envoyer le message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Carte -->
        <section class="map-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center mb-4">Notre Localisation</h2>
                        <div class="map-container">
                            <div class="map-placeholder">
                                <i class="bi bi-map me-2"></i>
                                Carte interactive - 123 Rue de la Mode, 75001 Paris
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="faq-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="display-5 fw-bold text-dark">Questions Fréquentes</h2>
                        <p class="lead text-muted">Trouvez rapidement des réponses à vos questions</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <i class="bi bi-question-circle me-2"></i>
                                Comment puis-je suivre ma commande ?
                            </div>
                            <div class="faq-answer">
                                Vous recevrez un email de confirmation avec un numéro de suivi dès que votre commande sera expédiée. Vous pouvez également suivre votre commande dans votre espace client.
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <i class="bi bi-question-circle me-2"></i>
                                Quels sont les délais de livraison ?
                            </div>
                            <div class="faq-answer">
                                Les délais de livraison varient entre 2-5 jours ouvrés en France métropolitaine. Pour les DOM-TOM et l'international, comptez 7-15 jours selon la destination.
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <i class="bi bi-question-circle me-2"></i>
                                Comment retourner un article ?
                            </div>
                            <div class="faq-answer">
                                Vous disposez de 30 jours pour retourner un article. Connectez-vous à votre espace client, sélectionnez la commande concernée et suivez les instructions de retour.
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <i class="bi bi-question-circle me-2"></i>
                                Les produits sont-ils garantis ?
                            </div>
                            <div class="faq-answer">
                                Tous nos produits bénéficient d'une garantie de 2 ans minimum. Certains articles ont des garanties étendues selon les fabricants.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fonction pour la FAQ
        function toggleFAQ(element) {
            const answer = element.nextElementSibling;
            const isActive = answer.classList.contains('active');
            
            // Fermer toutes les réponses
            document.querySelectorAll('.faq-answer').forEach(item => {
                item.classList.remove('active');
            });
            
            // Ouvrir la réponse cliquée si elle n'était pas active
            if (!isActive) {
                answer.classList.add('active');
            }
        }
        
        // Gestion du formulaire
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Récupération des données du formulaire
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Simulation d'envoi (remplacer par votre logique d'envoi)
            alert('Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.');
            
            // Réinitialisation du formulaire
            this.reset();
        });
    </script>
</body>
</html> 