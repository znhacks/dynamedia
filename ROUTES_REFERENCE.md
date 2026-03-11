# API & Routes Reference - Dynamedia

## Complete Route Map

### Landing & Public Gallery

```
GET  /
├─ View: landing.blade.php
├─ Controller: (static redirect to /posts)
├─ Auth Required: No
└─ Purpose: Site homepage
```

```
GET  /posts
├─ Controller: PostController@index
├─ View: posts/index.blade.php
├─ Auth Required: No
├─ Methods:
│  ├─ Query: page (default: 1)
│  └─ Data: 12 posts per page with user profiles
└─ Purpose: Display museum gallery
```

---

## Authentication Routes

### Login

```
GET  /login
├─ Controller: LoginController@show
├─ View: auth/login.blade.php
├─ Auth Required: No (guest only)
├─ Middleware: guest
└─ Purpose: Display login form
```

```
POST /login
├─ Controller: LoginController@login
├─ Auth Required: No (guest only)
├─ Middleware: guest
├─ Form Parameters:
│  ├─ email (required, email format)
│  ├─ password (required, min 1 char)
│  └─ remember (optional, remember session)
├─ Validation:
│  ├─ email: required|email
│  └─ password: required
├─ Success: Redirect to intended or /
├─ Failure: Redirect back with errors
└─ Purpose: Authenticate user via session
```

---

### Registration

```
GET  /register
├─ Controller: RegisterController@show
├─ View: auth/register.blade.php
├─ Auth Required: No (guest only)
├─ Middleware: guest
└─ Purpose: Display registration form
```

```
POST /register
├─ Controller: RegisterController@register
├─ Auth Required: No (guest only)
├─ Middleware: guest
├─ Form Parameters:
│  ├─ name (required, string)
│  ├─ email (required, email, unique)
│  ├─ password (required, min 8)
│  └─ password_confirmation (required, match)
├─ Validation:
│  ├─ name: required|string|max:255
│  ├─ email: required|email|unique:users
│  └─ password: required|min:8|confirmed
├─ Success: Create user, redirect to login
├─ Failure: Redirect back with errors
└─ Purpose: Create new user account
```

---

### Logout

```
POST /logout
├─ Controller: LoginController@logout
├─ Auth Required: Yes
├─ Middleware: auth
├─ Methods:
│  └─ Auth::logout()
│ ├─ session()->invalidate()
│  └─ session()->regenerateToken()
├─ Redirect: /
└─ Purpose: Logout and clear session
```

---

### Password Reset

```
GET  /forgot-password
├─ Controller: LoginController@forgotPasswordForm
├─ View: auth/forgot-password.blade.php
├─ Auth Required: No (guest only)
├─ Middleware: guest
└─ Purpose: Show password reset request form
```

```
POST /forgot-password
├─ Controller: LoginController@sendPasswordReset
├─ Auth Required: No (guest only)
├─ Middleware: guest
├─ Form Parameters:
│  └─ email (required, must exist in users)
├─ Validation:
│  └─ email: required|email|exists:users
├─ Method:
│  └─ Password::sendResetLink($request->only('email'))
├─ MAIL_MAILER: Configured in .env
├─ Success: Redirect back with status message
├─ Failure: Redirect back with errors
└─ Purpose: Send password reset link via email
```

```
GET  /reset-password/{token}
├─ Controller: LoginController@resetPasswordForm
├─ View: auth/reset-password.blade.php
├─ Auth Required: No (guest only)
├─ Middleware: guest
├─ Parameters:
│  └─ token (from email link)
├─ View Data:
│  └─ token (passed to form, stored in hidden input)
└─ Purpose: Show password reset form
```

```
POST /reset-password
├─ Controller: LoginController@resetPassword
├─ Auth Required: No (guest only)
├─ Middleware: guest
├─ Form Parameters:
│  ├─ token (required)
│  ├─ email (required, format: email, exists)
│  ├─ password (required, min 8)
│  └─ password_confirmation (required, match)
├─ Validation:
│  ├─ token: required
│  ├─ email: required|email|exists:users
│  └─ password: required|min:8|confirmed
├─ Method:
│  └─ Password::reset() with custom callback
├─ Success: Update password, redirect to login
├─ Failure: Redirect back with errors
└─ Purpose: Update password with reset token
```

---

## Profile Routes

### View Profile

```
GET  /profile/{id}
├─ Controller: ProfileController@show
├─ View: profile/show.blade.php
├─ Auth Required: No (public)
├─ Parameters:
│  └─ id (user ID, must exist)
├─ View Data:
│  ├─ user (Model\User)
│  ├─ profile (Model\Profile or null)
│  └─ posts (Collection of user's posts, ordered by latest)
├─ Methods:
│  ├─ User::findOrFail($id)
│  ├─ user->profile (relationship or null)
│  └─ user->posts()->latest()->get()
└─ Purpose: Display user profile with posts
```

---

### Edit Profile Form

```
GET  /profile/edit
├─ Controller: ProfileController@edit
├─ View: profile/edit.blade.php
├─ Auth Required: Yes
├─ Middleware: auth
├─ View Data:
│  ├─ user (auth()->user())
│  └─ profile (user->profile or new empty)
└─ Purpose: Show profile edit form
```

---

### Update Profile

```
PUT  /profile
├─ Controller: ProfileController@update
├─ HTTP Method: PUT (via form _method field)
├─ Auth Required: Yes
├─ Middleware: auth
├─ Form Parameters:
│  ├─ name (required, string, max 255)
│  ├─ bio (optional, string, max 1000)
│  └─ avatar (optional, image file)
├─ Form Data: multipart/form-data
├─ Validation:
│  ├─ name: required|string|max:255
│  ├─ bio: nullable|string|max:1000
│  └─ avatar: nullable|image|mimes:jpeg,png,jpg,gif|max:2048
├─ File Upload:
│  ├─ Disk: public
│  ├─ Path: /storage/avatars/
│  ├─ Deletes: Old avatar if exists
│  └─ Stores: New avatar with storage path
├─ Methods:
│  ├─ auth()->user()->update(['name' => ...])
│  ├─ user->profile()->create() or save()
│  └─ Storage::disk('public')->delete(old_path)
├─ Redirect: route('profile.show', $user->id)
├─ Success Message: "Profile berhasil diperbarui"
└─ Purpose: Update user profile and avatar
```

---

## Post Routes (Gallery)

### View All Posts (Gallery)

```
GET  /posts
├─ Controller: PostController@index
├─ View: posts/index.blade.php
├─ Auth Required: No (public)
├─ Query Parameters:
│  └─ page (default: 1)
├─ Data:
│  ├─ posts->with('user.profile')->latest()->paginate(12)
│  └─ Includes: User info with avatar
├─ View Data:
│  └─ posts (Collection, paginated)
└─ Purpose: Display gallery grid (12 per page)
```

---

### View Single Post

```
GET  /posts/{post}
├─ Controller: PostController@show
├─ View: posts/show.blade.php
├─ Auth Required: No (public)
├─ Route Model Binding: Post $post
├─ Parameters:
│  └─ post (ID or slug)
├─ Methods:
│  └─ post->load('user.profile', 'comments.user.profile')
├─ View Data:
│  ├─ post (Model\Post with relationships)
│  ├─ user (post->user)
│  └─ comments (post->comments with authors)
├─ Features:
│  ├─ Display full image
│  ├─ Show author with avatar
│  ├─ Display all comments
│  ├─ Allow commenting (if auth)
│  ├─ Show delete button (if owner)
│  └─ Allow comment deletion (if owner)
└─ Purpose: View post details and comments
```

---

### Create Post Form

```
GET  /posts/create
├─ Controller: PostController@create
├─ View: posts/create.blade.php
├─ Auth Required: Yes
├─ Middleware: auth
├─ Methods:
│  └─ Except: ['index', 'show']
└─ Purpose: Show post creation form
```

---

### Store New Post

```
POST /posts
├─ Controller: PostController@store
├─ Auth Required: Yes
├─ Middleware: auth
├─ Form Parameters:
│  ├─ title (required, string, max 255)
│  ├─ description (optional, string, max 2000)
│  └─ image (required, image file)
├─ Form Data: multipart/form-data
├─ Validation:
│  ├─ title: required|string|max:255
│  ├─ description: nullable|string|max:2000
│  └─ image: required|image|mimes:jpeg,png,jpg,gif|max:5242880
├─ File Upload:
│  ├─ Disk: public
│  ├─ Path: /storage/posts/
│  ├─ Max Size: 5MB (5242880 bytes)
│  ├─ Formats: jpeg, png, jpg, gif
│  └─ Stores: Original filename with new path
├─ Methods:
│  ├─ Auth::user()->posts()->create(data)
│  └─ Storage disk('public')->put('posts', file)
├─ Redirect: route('posts.show', $post)
├─ Success Message: "Post berhasil dibuat"
└─ Purpose: Save new post with image
```

---

### Delete Post

```
DELETE /posts/{post}
├─ Controller: PostController@destroy
├─ HTTP Method: DELETE (via form _method field)
├─ Auth Required: Yes
├─ Middleware: auth
├─ Route Model Binding: Post $post
├─ Authorization: PostPolicy@delete (owner only)
├─ Methods:
│  ├─ authorize('delete', $post)
│  ├─ Storage::disk('public')->delete(post->image)
│  └─ post->delete()
├─ Redirect: route('posts.index')
├─ Success Message: "Post berhasil dihapus"
├─ Failure: 403 Forbidden (if not owner)
└─ Purpose: Delete post and image (owner only)
```

---

## Comment Routes

### Create Comment

```
POST /posts/{post}/comments
├─ Controller: CommentController@store
├─ Route Model Binding: Post $post
├─ Auth Required: Yes
├─ Middleware: auth
├─ Form Parameters:
│  └─ content (required, string, 1-1000 chars)
├─ Form Data: urlencoded
├─ Validation:
│  └─ content: required|string|min:1|max:1000
├─ Methods:
│  ├─ post->comments()->create(data)
│  └─ Auth::id() as user_id
├─ Redirect: route('posts.show', $post)
├─ Success Message: "Komentar berhasil ditambahkan"
└─ Purpose: Add comment to post
```

---

### Delete Comment

```
DELETE /comments/{comment}
├─ Controller: CommentController@destroy
├─ HTTP Method: DELETE (via form _method field)
├─ Auth Required: Yes
├─ Middleware: auth
├─ Route Model Binding: Comment $comment
├─ Authorization: CommentPolicy@delete (owner only)
├─ Methods:
│  ├─ authorize('delete', $comment)
│  ├─ post = comment->post
│  └─ comment->delete()
├─ Redirect: route('posts.show', $post)
├─ Success Message: "Komentar berhasil dihapus"
├─ Failure: 403 Forbidden (if not owner)
└─ Purpose: Delete comment (owner only)
```

---

## HTTP Status Codes

### Success (2xx)
- **200 OK** - GET requests
- **201 Created** - POST requests (implicit)
- **204 No Content** - DELETE success

### Client Error (4xx)
- **302 Found** - Redirects (validation failures, logout)
- **403 Forbidden** - Authorization failure (not owner)
- **404 Not Found** - Resource not found
- **419 Page Expired** - CSRF token invalid
- **422 Unprocessable** - Validation failure

### Server Error (5xx)
- **500 Internal Error** - Server error
- **503 Service Unavailable** - App down

---

## Request/Response Examples

### Login Request
```http
POST /login HTTP/1.1
Content-Type: application/x-www-form-urlencoded

email=user@example.com&password=password123&remember=on
```

### Create Post Request
```http
POST /posts HTTP/1.1
Content-Type: multipart/form-data

title=My Photo&description=A nice photo&image=[binary image data]
```

### Add Comment Request
```http
POST /posts/1/comments HTTP/1.1
Content-Type: application/x-www-form-urlencoded

content=Great photo!
```

---

## Middleware Stack

### Guest Routes (auth forms)
- `web` (session, CSRF)
- `guest` (redirect if authenticated)

### Public Routes (gallery)
- `web` (session, CSRF)

### Authenticated Routes
- `web` (session, CSRF)
- `auth` (require login)

### Authorization
- `can` middleware for policies (if needed)
- Policies: PostPolicy, CommentPolicy

---

## Response Messages

### Success Messages
- "Akun berhasil dibuat" - Registration
- "Email atau password salah" - Login failure
- "Profile berhasil diperbarui" - Profile update
- "Post berhasil dibuat" - Post creation
- "Post berhasil dihapus" - Post deletion
- "Komentar berhasil ditambahkan" - Comment creation
- "Komentar berhasil dihapus" - Comment deletion

### Error Messages
- Validation errors (field-specific)
- Authorization errors (403 Forbidden)
- File upload errors (size, type)

---

**Complete Route Reference** ✓
Last Updated: February 8, 2026
