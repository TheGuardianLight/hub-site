<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        // Récupérer la configuration Firebase depuis l'API PHP
        var config = <?php echo json_encode($config); ?>;
        firebase.initializeApp(config);

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
                    // L'utilisateur est connecté, recharger la page pour mettre à jour l'état de la session PHP
                    location.reload();
                })
                .catch((error) => {
                    console.error('Erreur de connexion:', error);
                });
        });

        <?php if ($config['allowSignup'] == "true"): ?>
        // Fonction d'inscription
        signupBtn.addEventListener('click', () => {
            const email = emailInput.value;
            const password = passwordInput.value;
            firebase.auth().createUserWithEmailAndPassword(email, password)
                .then((userCredential) => {
                    // L'utilisateur est inscrit, recharger la page pour mettre à jour l'état de la session PHP
                    location.reload();
                })
                .catch((error) => {
                    console.error('Erreur d\'inscription:', error);
                });
        });
        <?php endif ?>

        // Vérifier si l'utilisateur est connecté
        firebase.auth().onAuthStateChanged((user) => {
            if (user) {
                // L'utilisateur est connecté, afficher le conteneur du hub
                loginContainer.classList.add('d-none');
                hubContainer.classList.remove('d-none');
            } else {
                // L'utilisateur n'est pas connecté, afficher le conteneur de connexion
                loginContainer.classList.remove('d-none');
                hubContainer.classList.add('d-none');
            }
        });
    });
</script>