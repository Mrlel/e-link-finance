<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demander une Démonstration - DSID Gestion du Patrimoine Informatique</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <style>
        :root {
            --orange-ci: #FF8200;
            --white-ci: #FFFFFF;
            --green-ci: #009E60;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        .country-banner {
            display: flex;
            height: 8px;
        }
        
        .banner-orange {
            background-color: var(--orange-ci);
            flex: 1;
        }
        
        .banner-white {
            background-color: var(--white-ci);
            flex: 1;
        }
        
        .banner-green {
            background-color: var(--green-ci);
            flex: 1;
        }
        
        .navbar {
            padding: 1rem 2rem;
            background-color: rgba(255, 255, 255, 0.95);
            width: 100%;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }
        
        .logo-container {
            display: flex;
            align-items: center;
        }
        
        .logo-container img {
            height: 45px;
            margin-right: 10px;
        }
        
        .logo-text {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--green-ci);
        }
        
        .slogan {
            font-size: 1rem;
            color: var(--orange-ci);
            font-style: italic;
        }
        
        .page-header {
            background: linear-gradient(135deg, rgba(0, 158, 96, 0.9), rgba(255, 130, 0, 0.8)), url('/assets/img/tech-background.jpg');
            background-size: cover;
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }
        
        .section-title {
            font-size: 2.2rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 1rem;
            position: relative;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }
        
        .section-subtitle {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }
        
        .demo-form-section {
            padding: 4rem 0;
            background-color: white;
        }
        
        .custom-form {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        
        .form-header {
            border-bottom: 2px solid var(--orange-ci);
            padding-bottom: 1rem;
            margin-bottom: 2rem;
            color: #333;
        }
        
        .required label:after {
            content: '*';
            color: var(--orange-ci);
            margin-left: 4px;
        }
        
        .ui.button.btn-submit {
            background-color: var(--green-ci);
            color: white;
        }
        
        .ui.button.btn-submit:hover {
            background-color: var(--orange-ci);
        }
        
        .benefits-section {
            background-color: #f9f9f9;
            padding: 4rem 2rem;
        }
        
        .benefits-title {
            color: #333;
            text-align: center;
            margin-bottom: 3rem;
            font-size: 1.8rem;
            position: relative;
        }
        
        .benefits-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--orange-ci), var(--green-ci));
        }
        
        .benefit-card {
            height: 100%;
            transition: transform 0.3s ease;
            border-top: 3px solid transparent !important;
        }
        
        .benefit-card:hover {
            transform: translateY(-5px);
        }
        
        .benefit-card.orange-card {
            border-top-color: var(--orange-ci) !important;
        }
        
        .benefit-card.green-card {
            border-top-color: var(--green-ci) !important;
        }
        
        .icon-accent-orange {
            color: var(--orange-ci);
        }
        
        .icon-accent-green {
            color: var(--green-ci);
        }
        
        .faq-section {
            padding: 4rem 2rem;
            background-color: white;
        }
        
        .faq-title {
            color: #333;
            text-align: center;
            margin-bottom: 3rem;
            font-size: 1.8rem;
            position: relative;
        }
        
        .faq-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--orange-ci), var(--green-ci));
        }
        
        .accordion .title {
            color: #333 !important;
            font-weight: 600 !important;
        }
        
        .accordion .active.title {
            color: var(--green-ci) !important;
        }
        
        footer {
            background-color: #333;
            color: white;
            padding: 3rem 2rem 2rem;
        }
        
        .footer-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            color: var(--orange-ci);
        }
        
        .footer-link {
            color: #ddd;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.2s ease;
        }
        
        .footer-link:hover {
            color: white;
            text-decoration: none;
        }
        
        .copyright {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.9rem;
            color: #aaa;
        }
        
        .step-item {
            text-align: center;
            padding: 1rem;
        }
        
        .step-number {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: var(--green-ci);
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .step-text {
            font-size: 1.1rem;
            color: #333;
        }
        
        @media (max-width: 768px) {
            .section-title {
                font-size: 1.8rem;
            }
            
            .navbar {
                padding: 0.8rem;
            }
            
            .page-header {
                padding: 3rem 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Bande aux couleurs du drapeau -->
    <div class="country-banner">
        <div class="banner-orange"></div>
        <div class="banner-white"></div>
        <div class="banner-green"></div>
    </div>
    
    
    
    <!-- En-tête de la page -->
    <section class="page-header">
        <div class="ui container">
            <h1 class="section-title">Demander l'application</h1>
            <p class="section-subtitle">Découvrez comment notre solution de gestion du patrimoine informatique peut transformer votre service IT et optimiser vos ressources</p>
            <div class="ui horizontal divider" style="color: white; margin: 2rem 0;">
                <i class="laptop icon"></i>
            </div>
            <div class="ui stackable three column grid">
                <div class="column step-item">
                    <div class="step-number">1</div>
                    <div class="step-text">Remplissez le formulaire de demande</div>
                </div>
                <div class="column step-item">
                    <div class="step-number">2</div>
                    <div class="step-text">Vous serez contacté dans 24h/48h</div>
                </div>
                <div class="column step-item">
                    <div class="step-number">3</div>
                    <div class="step-text">Profitez d'une application adaptée</div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section de formulaire -->
    <section class="demo-form-section">
        <div class="ui container">
            <div class="custom-form">
                <h2 class="form-header">Formulaire de demande de l'application</h2>
                <form class="ui form">
                    <h4 class="ui dividing header">Informations personnelles</h4>
                    <div class="two fields">
                        <div class="required field">
                            <label>Nom</label>
                            <input type="text" placeholder="Votre nom">
                        </div>
                        <div class="required field">
                            <label>Prénom</label>
                            <input type="text" placeholder="Votre prénom">
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="required field">
                            <label>Email professionnel</label>
                            <input type="email" placeholder="exemple@organisation.ci">
                        </div>
                        <div class="required field">
                            <label>Téléphone</label>
                            <input type="tel" placeholder="+225 XX XX XX XX XX">
                        </div>
                    </div>
                    
                    <h4 class="ui dividing header">Informations sur l'organisation</h4>
                    <div class="two fields">
                        <div class="required field">
                            <label>Nom de l'organisation</label>
                            <input type="text" placeholder="Nom de votre organisation">
                        </div>
                        <div class="field">
                            <label>Secteur d'activité</label>
                            <select class="ui dropdown">
                                <option value="">Sélectionnez un secteur</option>
                                <option value="gouvernement">Administration publique</option>
                                <option value="education">Éducation</option>
                                <option value="sante">Santé</option>
                                <option value="finance">Finance</option>
                                <option value="telecom">Télécommunications</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <label>Poste/Fonction</label>
                            <input type="text" placeholder="Votre fonction dans l'organisation">
                        </div>
                        <div class="field">
                            <label>Taille du parc informatique</label>
                            <select class="ui dropdown">
                                <option value="">Sélectionnez une taille</option>
                                <option value="petit">Moins de 50 équipements</option>
                                <option value="moyen">50 à 200 équipements</option>
                                <option value="grand">200 à 500 équipements</option>
                                <option value="tres-grand">Plus de 500 équipements</option>
                            </select>
                        </div>
                    </div>
                    
                    <!--<h4 class="ui dividing header">Informations sur la démonstration</h4>
                    <div class="field">
                        <label>Date de démonstration souhaitée</label>
                        <div class="two fields">
                            <div class="field">
                                <input type="date">
                            </div>
                            <div class="field">
                                <select class="ui dropdown">
                                    <option value="">Sélectionnez une plage horaire</option>
                                    <option value="morning">Matin (9h - 12h)</option>
                                    <option value="afternoon">Après-midi (14h - 17h)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Format préféré</label>
                        <div class="ui selection dropdown">
                            <input type="hidden" name="format">
                            <i class="dropdown icon"></i>
                            <div class="default text">Sélectionnez un format</div>
                            <div class="menu">
                                <div class="item" data-value="online">
                                    <i class="video icon icon-accent-green"></i> Démonstration en ligne
                                </div>
                                <div class="item" data-value="onsite">
                                    <i class="building icon icon-accent-orange"></i> Démonstration sur site
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Modules d'intérêt particulier (sélectionnez tous ceux qui s'appliquent)</label>
                        <div class="ui segment">
                            <div class="ui five column grid">
                                <div class="column">
                                    <div class="ui checkbox">
                                        <input type="checkbox" name="module" value="inventory">
                                        <label>Inventaire</label>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="ui checkbox">
                                        <input type="checkbox" name="module" value="maintenance">
                                        <label>Maintenance</label>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="ui checkbox">
                                        <input type="checkbox" name="module" value="user-management">
                                        <label>Gestion utilisateurs</label>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="ui checkbox">
                                        <input type="checkbox" name="module" value="reporting">
                                        <label>Rapports</label>
                                    </div>
                                </div>
                                <div class="column">
                                    <div class="ui checkbox">
                                        <input type="checkbox" name="module" value="dashboard">
                                        <label>Tableaux de bord</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <div class="field">
                        <label>Questions ou informations supplémentaires</label>
                        <textarea rows="3" placeholder="Partagez vos attentes ou questions spécifiques..."></textarea>
                    </div>
                    <div class="field">
                        <div class="ui checkbox">
                            <input type="checkbox" name="terms">
                            <label>J'accepte que mes informations soient utilisées pour me contacter au sujet de ma demande de démonstration</label>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 2rem;">
                        <button class="ui large button btn-submit">
                            <i class="paper plane icon"></i> Envoyer ma demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    <section class="benefits-section">
        <div class="ui container">
            <h2 class="benefits-title">Pourquoi Opter pour cette solution ?</h2>
            <div class="ui stackable three column grid">
                <div class="column">
                    <div class="ui card benefit-card orange-card">
                        <div class="content">
                            <i class="handshake icon icon-accent-orange" style="font-size: 2rem;"></i>
                            <div class="header" style="margin-top: 1rem;">Solution adaptée</div>
                            <div class="description" style="margin-top: 1rem;">
                                o	Utilisation de technologies telles que les codes-barres, les QR codes pour faciliter l'identification et le suivi des équipements.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="ui card benefit-card green-card">
                        <div class="content">
                            <i class="lightbulb icon icon-accent-green" style="font-size: 2rem;"></i>
                            <div class="header" style="margin-top: 1rem;">gestion des maintenances automatisé</div>
                            <div class="description" style="margin-top: 1rem;">
                            o	Cette solution permettra de gérer les demandes de maintenance, de planifier les interventions, de suivre l'état des réparations et de générer des rapports sur les maintenances effectuées.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column">
                    <div class="ui card benefit-card orange-card">
                        <div class="content">
                            <i class="cloud icon icon-accent-orange" style="font-size: 2rem;"></i>
                            <div class="header" style="margin-top: 1rem;">Automatisation de la gestion des Logiciels </div>
                            <div class="description" style="margin-top: 1rem;">
                            o	Mise en place d'un système de gestion automatisée des Logiciels logicielles pour garantir la conformité et optimiser l'utilisation des Logiciels.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Section FAQ -->
    <section class="faq-section">
        <div class="ui container">
            <h2 class="faq-title">Questions fréquentes</h2>
                <!--<div class="ui styled fluid accordion">
                <div class="title">
                    <i class="dropdown icon"></i>
                    Combien de temps dure une démonstration ?
                </div>
                <div class="content">
                    <p>Nos démonstrations durent généralement entre 45 minutes et 1 heure, avec un temps supplémentaire pour répondre à vos questions. Nous adaptons la durée selon vos besoins et disponibilités.</p>
                </div>
                
                <div class="title">
                    <i class="dropdown icon"></i>
                    Puis-je inviter plusieurs collègues à la démonstration ?
                </div>
                <div class="content">
                    <p>Absolument ! Nous encourageons la participation de toutes les parties prenantes concernées par la gestion du patrimoine informatique. Vous pouvez mentionner le nombre de participants dans le formulaire.</p>
                </div>
                
                <div class="title">
                    <i class="dropdown icon"></i>
                    La démonstration est-elle gratuite et sans engagement ?
                </div>
                <div class="content">
                    <p>Oui, la démonstration est totalement gratuite et sans aucun engagement. Notre objectif est de vous présenter les fonctionnalités de notre solution et de répondre à vos questions.</p>
                </div>
                
                <div class="title">
                    <i class="dropdown icon"></i>
                    Peut-on personnaliser la démonstration selon nos besoins spécifiques ?
                </div>
                <div class="content">
                    <p>Tout à fait ! C'est pourquoi nous vous demandons de préciser vos modules d'intérêt dans le formulaire. Nous adapterons la démonstration pour mettre l'accent sur les aspects qui vous intéressent le plus.</p>
                </div>
                
                <div class="title">
                    <i class="dropdown icon"></i>
                    Faut-il une préparation technique pour une démonstration en ligne ?
                </div>
                <div class="content">
                    <p>Non, aucune préparation technique particulière n'est nécessaire. Pour les démonstrations en ligne, nous utilisons des outils de visioconférence simples et accessibles. Un lien vous sera envoyé avant la session avec toutes les instructions.</p>
                </div>
            </div>  -->
        </div>
    </section>
    
    <!-- Pied de page -->
    <footer>
        <div class="ui container">
            <div class="ui stackable four column grid">
                <div class="column">
                    <h3 class="footer-title">À propos</h3>
                    <p>La Direction des Systèmes d'Information et de la Digitalisation (DSID) est responsable de la stratégie numérique et de la gestion des infrastructures informatiques.</p>
                </div>
                <div class="column">
                    <h3 class="footer-title">Liens rapides</h3>
                    <a href="index.html" class="footer-link">Accueil</a>
                    <a href="index.html#fonctionnalites" class="footer-link">Fonctionnalités</a>
                    <a href="index.html#apercu" class="footer-link">Aperçu</a>
                    <a href="#" class="footer-link">FAQ</a>
                    <a href="#" class="footer-link">Support technique</a>
                </div>
                <div class="column">
                    <h3 class="footer-title">Contact</h3>
                    <p>
                        <i class="map marker alternate icon"></i> Plateau, Abidjan<br>
                        <i class="phone icon"></i> +225 27 20 XX XX XX<br>
                        <i class="mail icon"></i> contact@dsid.gouv.ci
                    </p>
                </div>
                <div class="column">
                    <h3 class="footer-title">Horaires</h3>
                    <p>
                        <i class="clock icon"></i> Lundi - Vendredi<br>
                        8h00 - 16h30<br>
                        Fermé les weekends et jours fériés
                    </p>
                </div>
            </div>
            <div class="copyright">
                © 2025 DSID - Direction des Systèmes d'Information et de la Digitalisation | Tous droits réservés - developper par @Lela G. dominique
            </div>
        </div>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>
    <script>
        // Initialiser les dropdowns
        $('.ui.dropdown').dropdown();
        
        // Initialiser les checkboxes
        $('.ui.checkbox').checkbox();
        
        // Initialiser l'accordéon
        $('.ui.accordion').accordion();
        
        // Validation du formulaire
        $('.ui.form').form({
            fields: {
                nom: 'empty',
                prenom: 'empty',
                email: 'email',
                telephone: 'empty',
                organisation: 'empty',
                terms: 'checked'
            }
        });
    </script>
</body>
</html>