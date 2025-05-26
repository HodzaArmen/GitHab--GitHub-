<div class="container mt-4">
    <h1>Edit Profile</h1>

    <form action="<?= BASE_URL ?>user/update" method="post">
        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>">
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>