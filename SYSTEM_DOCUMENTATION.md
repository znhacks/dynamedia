# Dynamedia - Production-Ready Laravel Authentication & Content System

## Overview
Complete authentication and gallery system with user profiles, image posts, and comments.

## Database Tables

### users
- id, name, email, password, email_verified_at, remember_token, timestamps

### profiles
- id, user_id (foreign key), bio (text), avatar (string path), timestamps

### posts
- id, user_id (foreign key), title, description, image (string path), timestamps

### comments
- id, user_id (foreign key), post_id (foreign key), content (text), timestamps

### password_reset_tokens
- email (primary), token, created_at

## Features Implemented

### Authentication
✅ User Registration (with password confirmation)
✅ User Login (with remember option)
✅ User Logout
✅ Password Reset via Email Token
✅ Session-based auth (not API tokens)
✅ bcrypt password hashing
✅ Input validation on all forms

### User Profiles
✅ View any user profile with posts
✅ Edit own profile (name, bio, avatar)
✅ Avatar upload to /storage/avatars
✅ Profile image display
✅ User posts feed on profile

### Posts (Museum Gallery)
✅ Create posts with title, description, image
✅ Store images in /storage/posts
✅ View all posts in gallery (paginated)
✅ View individual post details
✅ Delete own posts only (owner authorization)
✅ Display post author info with avatar

### Comments
✅ Add comments to posts
✅ View all comments on a post
✅ Delete own comments only (owner authorization)
✅ Display comment author info
✅ Comment timestamps

### Security
✅ Bcrypt password hashing
✅ CSRF token protection on all forms
✅ Authorization policies for editing/deleting
✅ Auth middleware on protected routes
✅ Session regeneration on login/logout
✅ Guest middleware on auth forms
✅ Input validation on all endpoints
✅ File upload validation (image types, max size)

## Controllers

### AuthController (LoginController)
Location: `app/Http/Controllers/Auth/LoginController.php`

Methods:
- `show()` - Display login form
- `login(Request $request)` - Process login
- `logout(Request $request)` - Process logout
- `forgotPasswordForm()` - Show forgot password form
- `sendPasswordReset(Request $request)` - Send reset email
- `resetPasswordForm($token)` - Show reset password form
- `resetPassword(Request $request)` - Process password reset

### RegisterController
Location: `app/Http/Controllers/Auth/RegisterController.php`

Methods:
- `show()` - Display registration form
- `register(Request $request)` - Process registration

### ProfileController
Location: `app/Http/Controllers/ProfileController.php`

Methods:
- `show($id)` - Display user profile and posts
- `edit()` - Show edit profile form (auth only)
- `update(Request $request)` - Update user profile (auth only)

File Upload:
- Validates image file (jpeg, png, jpg, gif, max 2MB)
- Stores to /storage/avatars
- Deletes old avatar on update

### PostController
Location: `app/Http/Controllers/PostController.php`

Methods:
- `index()` - Display all posts (paginated, public)
- `show(Post $post)` - Display single post with comments (public)
- `create()` - Show create post form (auth only)
- `store(Request $request)` - Create new post (auth only)
- `destroy(Post $post)` - Delete post (owner only)

File Upload:
- Validates image file (jpeg, png, jpg, gif, max 5MB)
- Stores to /storage/posts
- Deletes old image on post deletion

### CommentController
Location: `app/Http/Controllers/CommentController.php`

Methods:
- `store(Request $request, Post $post)` - Create comment (auth only)
- `destroy(Comment $comment)` - Delete comment (owner only)

## Models

### User (app/Models/User.php)
Relationships:
- `profile()` - hasOne Profile
- `posts()` - hasMany Post
- `comments()` - hasMany Comment

### Profile (app/Models/Profile.php)
Fillable: user_id, bio, avatar
Relationships:
- `user()` - belongsTo User

### Post (app/Models/Post.php)
Fillable: user_id, title, description, image
Relationships:
- `user()` - belongsTo User
- `comments()` - hasMany Comment

### Comment (app/Models/Comment.php)
Fillable: user_id, post_id, content
Relationships:
- `user()` - belongsTo User
- `post()` - belongsTo Post

## Authorization Policies

### PostPolicy (app/Policies/PostPolicy.php)
- `delete()` - Only owner (user_id must match)

### CommentPolicy (app/Policies/CommentPolicy.php)
- `delete()` - Only owner (user_id must match)

## Routes

```
GET  /                          - Landing page
GET  /posts                     - Gallery (all posts)
GET  /posts/{post}              - View post with comments

Guest Only:
GET  /login                     - Login form
POST /login                     - Process login
GET  /register                  - Register form
POST /register                  - Process registration
GET  /forgot-password           - Forgot password form
POST /forgot-password           - Send reset email
GET  /reset-password/{token}    - Reset password form
POST /reset-password            - Process password reset

Auth Only:
POST /logout                    - Process logout
GET  /profile/{id}              - View user profile
GET  /profile/edit              - Edit own profile form
PUT  /profile                   - Update own profile

Auth Only - Posts:
GET  /posts/create              - Create post form
POST /posts                     - Store new post
DELETE /posts/{post}            - Delete post (owner only)

Auth Only - Comments:
POST /posts/{post}/comments     - Create comment
DELETE /comments/{comment}      - Delete comment (owner only)
```

## Blade Views

### Auth Views
- `resources/views/auth/login.blade.php` - Login form
- `resources/views/auth/register.blade.php` - Registration form
- `resources/views/auth/forgot-password.blade.php` - Forgot password form
- `resources/views/auth/reset-password.blade.php` - Reset password form

### Profile Views
- `resources/views/profile/show.blade.php` - User profile with posts
- `resources/views/profile/edit.blade.php` - Edit profile form

### Post Views
- `resources/views/posts/index.blade.php` - Gallery (all posts)
- `resources/views/posts/show.blade.php` - Single post with comments
- `resources/views/posts/create.blade.php` - Create post form

## File Storage

All files are stored in `/storage/app/public` and symlinked to `/public/storage`

- **Avatar uploads**: `/storage/avatars/` (max 2MB)
- **Post images**: `/storage/posts/` (max 5MB)

Access files via:
```
asset('storage/' . $path)
```

## Configuration

Ensure `.env` has:
```
FILESYSTEM_DISK=public
```

Storage symlink created:
```bash
php artisan storage:link
```

## Required Validations

### Registration
- name: required, string, max 255
- email: required, email, unique:users
- password: required, min 8, confirmed

### Login
- email: required, email
- password: required

### Profile Update
- name: required, string, max 255
- bio: nullable, string, max 1000
- avatar: nullable, image (jpeg, png, jpg, gif), max 2MB

### Post Create
- title: required, string, max 255
- description: nullable, string, max 2000
- image: required, image (jpeg, png, jpg, gif), max 5MB

### Comment Create
- content: required, string, min 1, max 1000

## Development

Start the server:
```bash
php artisan serve
```

Access at: http://localhost:8000

## Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Generate app key: `php artisan key:generate`
- [ ] Set secure session settings in `config/session.php`
- [ ] Configure password reset email in `config/mail.php`
- [ ] Set up proper email provider (Mailgun, SendGrid, etc.)
- [ ] Run migrations: `php artisan migrate`
- [ ] Create storage symlink: `php artisan storage:link`
- [ ] Set proper file permissions on storage and bootstrap/cache directories

## Security Features

✅ CSRF token protection
✅ Bcrypt password hashing
✅ Authorization policies for CRUD
✅ Input validation on all endpoints
✅ Session security (regeneration on login)
✅ File upload validation (type and size)
✅ SQL injection prevention (Eloquent ORM)
✅ XSS protection (Blade escaping)
✅ Soft delete not implemented (permanent deletion)

## No Features (As Per Requirements)

❌ Likes/favorites
❌ Follow/followers
❌ Direct messaging
❌ Notifications
❌ API tokens
❌ Rate limiting
❌ Post editing (create/delete only)

## HTML/CSS

All views use simple, semantic HTML with inline CSS for easy deployment.
No JavaScript frameworks (vanilla JS for file upload preview).
Mobile-responsive design.

---

**Version:** 1.0.0
**Last Updated:** February 8, 2026
**Status:** Production Ready
