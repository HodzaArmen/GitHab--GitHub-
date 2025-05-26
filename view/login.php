<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Sign in to GitHab</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form action="<?= BASE_URL ?>login" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username or email address</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?= htmlspecialchars($username ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">New to GitHab? <a href="<?= BASE_URL ?>register">Create an account</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>