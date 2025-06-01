<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Create a new repository</h2>
            <p>A repository contains all project files, including the revision history.</p>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>repo/create" method="post" enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="file" class="form-label">Upload files</label>
                    <input type="file" class="form-control" id="file" name="files[]" multiple webkitdirectory directory>
                <div class="form-text">You can upload multiple files at once.</div>

                <div class="mb-3">
                    <label for="name" class="form-label">Repository name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="form-text">Great repository names are short and memorable.</div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description (optional)</label>
                    <input type="text" class="form-control" id="description" name="description">
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_public" name="is_public" checked>
                    <label class="form-check-label" for="is_public">Public repository</label>
                    <div class="form-text">Anyone on the internet can see this repository.</div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create repository</button>
                    <a href="<?= BASE_URL ?>repo" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>