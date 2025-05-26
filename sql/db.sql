DROP DATABASE IF EXISTS githab;
CREATE DATABASE githab;
USE githab;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    full_name VARCHAR(100),
    bio TEXT,
    avatar_url VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE repositories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    is_public BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, name)
);

CREATE TABLE branches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repo_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repo_id) REFERENCES repositories(id) ON DELETE CASCADE,
    UNIQUE KEY (repo_id, name)
);

CREATE TABLE commits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repo_id INT NOT NULL,
    branch_id INT NOT NULL,
    user_id INT NOT NULL,
    parent_commit_id INT,
    message TEXT NOT NULL,
    hash VARCHAR(64) UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repo_id) REFERENCES repositories(id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_commit_id) REFERENCES commits(id) ON DELETE SET NULL
);

CREATE TABLE stars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    repo_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (repo_id) REFERENCES repositories(id) ON DELETE CASCADE,
    UNIQUE KEY (user_id, repo_id)
);

CREATE TABLE forks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    repo_id INT NOT NULL,
    parent_repo_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (repo_id) REFERENCES repositories(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_repo_id) REFERENCES repositories(id) ON DELETE CASCADE
);

CREATE TABLE issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repo_id INT NOT NULL,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    closed_at DATETIME,
    FOREIGN KEY (repo_id) REFERENCES repositories(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE issue_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    issue_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (issue_id) REFERENCES issues(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE uploads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    repo_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (repo_id) REFERENCES repositories(id) ON DELETE CASCADE
);
/*
-- Insert sample data
INSERT INTO users (username, password, email, full_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 'Admin User'),
('johndoe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'john@example.com', 'John Doe'),
('janedoe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jane@example.com', 'Jane Doe');

INSERT INTO repositories (user_id, name, description, is_public) VALUES 
(1, 'my-project', 'My first project', TRUE),
(1, 'private-repo', 'My private repository', FALSE),
(2, 'awesome-app', 'An awesome application', TRUE),
(3, 'website', 'Company website', TRUE);

INSERT INTO branches (repo_id, name, is_default) VALUES
(1, 'main', TRUE),
(1, 'dev', FALSE),
(2, 'master', TRUE),
(3, 'main', TRUE),
(4, 'production', TRUE);

INSERT INTO commits (repo_id, branch_id, user_id, message, hash) VALUES
(1, 1, 1, 'Initial commit', 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0'),
(1, 1, 1, 'Added README', 'b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0u'),
(1, 2, 1, 'Started development', 'c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0uv'),
(3, 4, 2, 'Initial commit', 'd4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0uvw'),
(4, 5, 3, 'First version', 'e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0uvwx');

INSERT INTO stars (user_id, repo_id) VALUES
(2, 1),
(3, 1),
(1, 3);

INSERT INTO forks (user_id, repo_id, parent_repo_id) VALUES
(2, 4, 1),
(3, 3, 1);

INSERT INTO issues (repo_id, user_id, title, description, status) VALUES
(1, 2, 'Bug in login page', 'The login page is not working properly', 'open'),
(1, 1, 'Add user profile', 'We need to add user profile pages', 'open'),
(3, 3, 'Performance issues', 'The app is slow on mobile devices', 'closed');

INSERT INTO issue_comments (issue_id, user_id, comment) VALUES
(1, 1, 'I will look into this issue'),
(1, 2, 'Thanks! Let me know if you need more info'),
(3, 2, 'This has been fixed in the latest version');
*/