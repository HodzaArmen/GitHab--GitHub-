<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>">GitHab</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>repo">Repositories</a>
                </li>
                <?php if (isset($_SESSION["user_id"])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>repo/create">New</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION["user_id"])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>user/profile?id=<?= $_SESSION["user_id"] ?>">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION["username"]) ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>register">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>