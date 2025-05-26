<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>user/profile?id=<?= $repo['user_id'] ?>"><?= htmlspecialchars($repo['username']) ?></a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>"><?= htmlspecialchars($repo['name']) ?></a></li>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>issue?repo_id=<?= $repo['id'] ?>">Issues</a></li>
            <li class="breadcrumb-item active" aria-current="page">New issue</li>
        </ol>
    </nav>
    
    <h2>Create new issue</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form action="<?= BASE_URL ?>issue/create" method="post">
        <input type="hidden" name="repo_id" value="<?= $repo['id'] ?>">
        
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="8" required></textarea>
        </div>
        
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Submit new issue</button>
            <a href="<?= BASE_URL ?>issue?repo_id=<?= $repo['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>