# Configuration Management System Upgrade (MVC)

## Included Features
1. Secure DB authentication with `password_hash`/`password_verify`.
2. Role-based access (`admin`, `editor`, `user`).
3. Categories CRUD + category assignment on posts.
4. Public pagination (`5` posts/page).
5. Rich editor via TinyMCE in post form.
6. SEO-friendly slug URLs (`/post/{slug}`).
7. Search on title + content.
8. Image upload validation (type + size).
9. Admin dashboard with cards + chart.
10. Toast notifications.
11. Professional glossy UI with animations.
12. Dark/Light mode toggle with `localStorage`.
13. Comments + moderation.
14. CSRF protection for all POST forms.
15. Clean MVC-style structure.

## Setup
1. Import `database/schema.sql` in phpMyAdmin.
2. Verify database values in `config/app.php`:
   - `name`, `user`, `pass`, `port`.
3. Ensure Apache rewrite is enabled (`mod_rewrite`) and `.htaccess` is allowed.
4. Open: `http://localhost/cms/`

## Default Credentials
- Username: `admin` | Password: `Admin@123`
- Username: `editor` | Password: `Admin@123`
- Username: `user` | Password: `Admin@123`

## Key Paths
- Front controller: `index.php`
- Core: `app/Core`
- Controllers: `app/Controllers`
- Models: `app/Models`
- Views: `views`
- Styles/JS: `assets/pro.css`, `assets/pro.js`
