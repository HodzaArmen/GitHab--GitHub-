<div class="container mt-4">
    <div class="jumbotron bg-light p-5 rounded">
        <h1 class="display-4">Welcome to GitHab</h1>
        <p class="lead">The simplest way to host and collaborate on code.</p>
        <hr class="my-4">
        <p>GitHab is a platform for version control and collaboration. It lets you and others work together on projects from anywhere.</p>
        <?php if (!isset($_SESSION["user_id"])): ?>
            <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>register" role="button">Sign up for free</a>
            <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>login" role="button">Sign in</a>
        <?php else: ?> 
            <a class="btn btn-primary btn-lg btn-danger" href="<?= BASE_URL ?>logout" role="button">Logout</a>
            <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>repo/create" role="button">Create a repository</a>
            <a class="btn btn-primary btn-lg" href="<?= BASE_URL ?>dashboard" role="button">Go to Dashboard</a>
        <?php endif; ?>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-git"></i> Version Control</h5>
                    <p class="card-text">Track changes to your code with our powerful version control system.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-people-fill"></i> Collaboration</h5>
                    <p class="card-text">Work together with your team on projects of any size.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-bug-fill"></i> Issue Tracking</h5>
                    <p class="card-text">Manage bugs and feature requests with our integrated issue tracker.</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="mt-5">
    <h2>Public Repositories</h2>

    <?php if (!empty($publicRepos)): ?>
        <ul class="list-group">
            <?php foreach ($publicRepos as $repo): ?>
                <li class="list-group-item">
                    <a href="<?= BASE_URL ?>repo/detail?id=<?= $repo["id"] ?>">
                        <?= htmlspecialchars($repo["name"]) ?>
                    </a>
                    by <strong><?= htmlspecialchars($repo["username"]) ?></strong>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No public repositories available.</p>
    <?php endif; ?>
</div>
