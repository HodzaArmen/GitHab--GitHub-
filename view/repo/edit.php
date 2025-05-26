<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Edit repository</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form action="<?= BASE_URL ?>repo/edit" method="post">
                <input type="hidden" name="id" value="<?= $repo['id'] ?>">
                
                <div class="mb-3">
                    <label for="name" class="form-label">Repository name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?= htmlspecialchars($repo['name']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description" 
                           value="<?= htmlspecialchars($repo['description']) ?>">
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_public" name="is_public" 
                           <?= $repo['is_public'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="is_public">Public repository</label>
                    <div class="form-text">Anyone on the internet can see this repository.</div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <a href="<?= BASE_URL ?>repo/detail?id=<?= $repo['id'] ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>