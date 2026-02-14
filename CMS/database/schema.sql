CREATE DATABASE IF NOT EXISTS cms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cms_db;

DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','editor','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    slug VARCHAR(140) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NULL,
    title VARCHAR(220) NOT NULL,
    slug VARCHAR(240) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_posts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_posts_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    author_name VARCHAR(120) NOT NULL,
    author_email VARCHAR(180) NOT NULL,
    body TEXT NOT NULL,
    status ENUM('pending','approved') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

CREATE INDEX idx_posts_created ON posts(created_at);
CREATE INDEX idx_posts_slug ON posts(slug);
CREATE INDEX idx_comments_status ON comments(status);

INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$1zmWwwdJaAYGMHRhmXMWZ.hHr7B.p4KypMK9uFAkQmoqbFkSUIaJe', 'admin'),
('editor', '$2y$10$1zmWwwdJaAYGMHRhmXMWZ.hHr7B.p4KypMK9uFAkQmoqbFkSUIaJe', 'editor'),
('user', '$2y$10$1zmWwwdJaAYGMHRhmXMWZ.hHr7B.p4KypMK9uFAkQmoqbFkSUIaJe', 'user');

INSERT INTO categories (name, slug) VALUES
('Technology', 'technology'),
('Design', 'design'),
('Business', 'business');
