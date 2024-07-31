<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="hub.php">Mon Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Gestion
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="sites-categories.php">Gestion des Sites/Catégories</a></li>
                        <li><a class="dropdown-item" href="data.php">Gestion des données</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="account.php">Compte</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">A propos</a>
                </li>
            </ul>
        </div>
        <!-- Ajoutez ce bouton dans votre rôle="menu" -->
        <a href="php/logout.php" class="btn btn-primary">Déconnexion</a>
    </div>
</nav>