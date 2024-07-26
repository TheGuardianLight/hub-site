<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Mon Hub</title>
    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-auth.js"></script>
</head>
<body>

<header class="bg-dark text-white text-center py-3">
    <h1>Bienvenue sur mon Hub</h1>
</header>
<main class="container my-5">
    <div class="text-center" id="login-container">
        <h2>Connexion</h2>
        <input class="form-control my-2" id="email" placeholder="Email" type="email">
        <input class="form-control my-2" id="password" placeholder="Mot de passe" type="password">
        <button class="btn btn-primary" id="login-btn">Se connecter</button>
        <button class="btn btn-secondary" id="signup-btn">S'inscrire</button>
    </div>

    <div id="hub-container" class="d-none">
        <h3>Mes sites internet&nbsp;:</h3>
        <br/>
        <div class="row">
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://noaledet.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Noaledet <span class="badge bg-primary">Wordpress</span></h5>
                            <p class="card-text">Il s'agit de mon CV en ligne.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://characters.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Neodraco's Characters <span class="badge bg-primary">WordPress</span>
                            </h5>
                            <p class="card-text">Site où sont hébergé toutes les fiches de mes personnages.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://docs.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Neodraco's Docs <span class="badge bg-primary">Bookstack</span>
                            </h5>
                            <p class="card-text">Gestion de la documentation technique.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://roleplay.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Neodraco's Roleplay <span class="badge bg-danger">Indisponible</span>
                            </h5>
                            <p class="card-text">Hébergement de l'historique de mes Roleplay.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <br/>

        <h3>Logiciel de gestion&nbsp;:</h3>
        <br/>
        <div class="row">
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://prox.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Proxmox VE <span class="badge bg-primary">Proxmox</span> <span
                                    class="badge bg-primary">OVH</span></h5>
                            <p class="card-text">Hyperviseur Proxmox. Hébergé chez OVH.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://mngt.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Nagios <span class="badge bg-primary">Nagios XI</span></h5>
                            <p class="card-text">Système de supervision de mes serveurs virtualisé.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://dcim.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Neodraco's DCIM <span class="badge bg-primary">Netbox</span></h5>
                            <p class="card-text">Logiciel de gestion d'infrastructure IPAM et DCIM.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://dock.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Neodraco's Docker <span class="badge bg-primary">Portainer</span>
                                <span class="badge bg-primary">Docker</span></h5>
                            <p class="card-text">Gestion des conteneurs docker.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://orchinventor.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Orchinventor <span class="badge bg-primary">Dolibarr</span></h5>
                            <p class="card-text">ERP pour l'Harmonie de Waldhambach.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <br/>

        <h3>Autres services&nbsp;:</h3>
        <br/>
        <div class="row">
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://kanban.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Kanban <span class="badge bg-primary">Taiga</span> <span
                                    class="badge bg-primary">Docker</span></h5>
                            <p class="card-text">Logiciel de gestion de projet.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://joplin.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Joplin <span class="badge bg-primary">Joplin</span> <span
                                    class="badge bg-primary">Docker</span></h5>
                            <p class="card-text">Serveur de stockage de note, tâches, etc...</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://dcim.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Wallos <span class="badge bg-primary">Wallos</span> <span
                                    class="badge bg-primary">Docker</span></h5>
                            <p class="card-text">Logiciel de suivis d'abonnement.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a class="text-decoration-none text-dark" href="https://vault.neodraco.fr">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Vaultwarden <span class="badge bg-primary">Vaultwarden</span> <span
                                    class="badge bg-primary">Docker</span></h5>
                            <p class="card-text">Gestionnaire de mot de passe basé sur Bitwarden.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>
<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2024 Veivneorul</p>
</footer>
<script crossorigin="anonymous"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js">
</script>
<script crossorigin="anonymous"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js">
</script>

<script>
    // Récupérer la configuration Firebase depuis l'API PHP
    fetch('config.php')
        .then(response => response.json())
        .then(config => {
            firebase.initializeApp(config);
        });

    // Références aux éléments HTML
    const loginContainer = document.getElementById('login-container');
    const hubContainer = document.getElementById('hub-container');
    const loginBtn = document.getElementById('login-btn');
    const signupBtn = document.getElementById('signup-btn');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    // Fonction de connexion
    loginBtn.addEventListener('click', () => {
        const email = emailInput.value;
        const password = passwordInput.value;
        firebase.auth().signInWithEmailAndPassword(email, password)
            .then((userCredential) => {
                loginContainer.classList.add('d-none');
                hubContainer.classList.remove('d-none');
            })
            .catch((error) => {
                console.error('Erreur de connexion:', error);
            });
    });

    // Fonction d'inscription
    signupBtn.addEventListener('click', () => {
        const email = emailInput.value;
        const password = passwordInput.value;
        firebase.auth().createUserWithEmailAndPassword(email, password)
            .then((userCredential) => {
                loginContainer.classList.add('d-none');
                hubContainer.classList.remove('d-none');
            })
            .catch((error) => {
                console.error('Erreur d\'inscription:', error);
            });
    });
</script>

</body>

</html>
