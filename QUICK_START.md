# Quick Start Guide - Dynamedia

## Installation & Setup

### 1. Database Setup
Ensure MySQL is running and you have a database named `dynamedia`:

```bash
mysql -u root -p
CREATE DATABASE dynamedia;
EXIT;
```

### 2. Environment Setup
The `.env` file is already configured for public file storage:
- `FILESYSTEM_DISK=public` ✓
- Database connection configured ✓
- App key generated ✓

### 3. Install Dependencies
```bash
composer install
npm install
```

### 4. Database Migrations
```bash
# Mark existing migrations as run
php artisan tinker
DB::table('migrations')->insert([
    ['migration' => '2014_10_12_000000_create_users_table', 'batch' => 1],
    ['migration' => '2014_10_12_100000_create_password_reset_tokens_table', 'batch' => 1],
    ['migration' => '2019_08_19_000000_create_failed_jobs_table', 'batch' => 1],
    ['migration' => '2019_12_14_000001_create_personal_access_tokens_table', 'batch' => 1],
    ['migration' => '2026_02_08_051004_create_profiles_table', 'batch' => 1],
    ['migration' => '2026_02_08_051008_create_posts_table', 'batch' => 1],
    ['migration' => '2026_02_08_051013_create_comments_table', 'batch' => 1],
]);
exit()
```

### 5. Storage Symlink
```bash
php artisan storage:link
```

### 6. Start Development Server
```bash
php artisan serve
```

Access at: **http://localhost:8000**

---

## Testing the System

### Create Test Users

Open http://localhost:8000/register and create 2-3 test accounts:

**User 1:**
- Name: John Doe
- Email: john@example.com
- Password: password123

**User 2:**
- Name: Jane Smith
- Email: jane@example.com
- Password: password123

### Test User Registration
1. Go to /register
2. Fill in name, email, password (min 8 chars, must confirm)
3. Should redirect to /login with success message
4. Login with new account

### Test Authentication
1. Go to /login
2. Enter email and password
3. Check "remember me" checkbox
4. Should redirect to gallery
5. Logout via the dropdown menu

### Test Password Reset
1. Go to /login
2. Click "Lupa password?"
3. Enter email address
4. Should show "Check your email for reset link"
5. To test reset in development, check email log at `/storage/logs/`

### Test Profile Management
1. Login as user
2. Click on "Profil" in header
3. Click "Edit Profil"
4. Update name and bio
5. Upload avatar image (JPEG, PNG, GIF)
6. Click "Simpan Perubahan"
7. Verify changes saved and avatar displays

### Test Post Upload
1. Login as user
2. Click "Upload" button
3. Fill in:
   - **Judul Foto** (required): "My First Photo"
   - **Deskripsi** (optional): "Beautiful sunset"
   - **Foto** (required): Select image file
4. Click "Upload Foto"
5. Should redirect to post view

### Test Gallery & Post Viewing
1. Go to "/" (home) or "/posts"
2. See all posts in grid layout
3. Click on any post card
4. View full post details
5. See author name and avatar
6. See comments section

### Test Commenting
1. Browse to any post as logged-in user
2. Scroll to "Komentar" section
3. Type a comment (1-1000 chars)
4. Click "Kirim Komentar"
5. Comment appears in list
6. See your avatar and timestamp

### Test Comment Deletion
1. Find a comment you made
2. Click "Hapus" button below comment
3. Confirm deletion
4. Comment should disappear
5. Try to delete someone else's comment - should not see delete button

### Test Post Deletion
1. Go to own profile
2. Click on own post
3. See "Hapus Post" button at top
4. Click it and confirm
5. Post deleted, redirect to gallery
6. Try to delete someone else's post - should not see delete button

### View Other User Profiles
1. Click on any user's name or avatar
2. See their profile page
3. See their bio and avatar
4. See all their posts
5. If it's your own profile, see "Edit Profil" button

---

## File Structure Created

```
app/
├── Http/Controllers/
│   ├── Auth/
│   │   ├── LoginController.php (updated with password reset)
│   │   └── RegisterController.php
│   ├── ProfileController.php
│   ├── PostController.php
│   └── CommentController.php
├── Models/
│   ├── User.php (updated)
│   ├── Profile.php
│   ├── Post.php
│   └── Comment.php
├── Policies/
│   ├── PostPolicy.php
│   └── CommentPolicy.php
└── Providers/
    └── AuthServiceProvider.php (updated)

routes/
└── web.php (updated)

resources/views/
├── auth/
│   ├── login.blade.php (updated)
│   ├── register.blade.php (updated)
│   ├── forgot-password.blade.php
│   └── reset-password.blade.php
├── profile/
│   ├── show.blade.php
│   └── edit.blade.php
└── posts/
    ├── index.blade.php (gallery)
    ├── show.blade.php (single post)
    └── create.blade.php (upload form)

database/migrations/
├── 2026_02_08_051004_create_profiles_table.php
├── 2026_02_08_051008_create_posts_table.php
└── 2026_02_08_051013_create_comments_table.php
```

---

## Database Schema

### users
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### profiles
```sql
CREATE TABLE profiles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    bio TEXT NULL,
    avatar VARCHAR(255) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### posts
```sql
CREATE TABLE posts (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### comments
```sql
CREATE TABLE comments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    post_id BIGINT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);
```

---

## API Endpoints Summary

### Public Endpoints
```
GET  /                    - Landing/Home
GET  /posts               - Gallery (all posts, paginated)
GET  /posts/{id}          - Single post view
GET  /login               - Login form
GET  /register            - Register form
POST /login               - Process login
POST /register            - Process registration
GET  /forgot-password     - Forgot password form
POST /forgot-password     - Send reset email
GET  /reset-password/{token} - Reset password form
POST /reset-password      - Process password reset
```

### Authenticated Endpoints
```
GET  /profile/{id}            - View profile (public)
GET  /profile/edit            - Edit profile form
PUT  /profile                 - Update profile
POST /logout                  - Logout

GET  /posts/create            - Upload form
POST /posts                   - Create post
DELETE /posts/{id}            - Delete post (owner only)

POST /posts/{id}/comments     - Add comment
DELETE /comments/{id}         - Delete comment (owner only)
```

---

## Troubleshooting

### "Could not open input file: artisan"
Make sure you're in the `C:\Jordi\Dynamedia\Dynamedia` directory:
```bash
cd "C:\Jordi\Dynamedia\Dynamedia"
php artisan serve
```

### Database connection error
Check `.env`:
- `DB_HOST=127.0.0.1`
- `DB_PORT=3306`
- `DB_DATABASE=dynamedia`
- `DB_USERNAME=root`
- MySQL is running

### Storage/permissions error
Run:
```bash
php artisan storage:link
```

Ensure `storage/` and `bootstrap/cache/` directories are writable.

### Uploads not showing
1. Check `FILESYSTEM_DISK=public` in `.env`
2. Verify storage symlink exists: `php artisan storage:link`
3. Files should be in `/storage/app/public/`
4. Access via `asset('storage/' . $path)`

---

## Email Configuration (Password Reset)

By default, uses file-based `MAIL_MAILER=log` in `.env`.

Check sent emails in: `/storage/logs/`

To test with real email, configure in `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@dynamedia.test
```

---

## Performance Notes

- Posts are paginated (12 per page)
- Eager loading on gallery (loads user profiles)
- Indexes on foreign keys (user_id, post_id)
- File uploads have size limits (avatars 2MB, posts 5MB)

---

## Security Checklist

- [x] Bcrypt password hashing
- [x] CSRF protection on forms
- [x] Authorization policies for delete operations
- [x] Input validation on all endpoints
- [x] Session regeneration on login/logout
- [x] Guest middleware on auth forms
- [x] Auth middleware on protected routes
- [x] File upload validation

---

**Ready to develop!** 🚀
