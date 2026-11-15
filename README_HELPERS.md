# Cisco Craft Helpers

The helper classes live in `src/Helpers/` and use the `App\\Helpers` namespace. Composer's existing PSR-4 mapping (`App\\` => `src/`) loads them automatically after `composer install` and `composer dump-autoload`.

## Available classes

- `Session`: starts the PHP session with HTTP-only, SameSite=Lax cookie settings.
- `Auth`: stores a minimal authenticated-user record in the session and provides login-state checks.
- `Csrf`: creates and verifies CSRF tokens for state-changing forms and requests.
- `Validation`: validates common input rules and returns field-specific errors.
- `Upload`: validates and stores JPG, PNG, GIF and WebP uploads using generated filenames.
- `Response`: sends JSON/text responses or redirects.
- `Flash`: stores one-time success and error messages in the session.

## Examples

```php
use App\\Helpers\\Auth;
use App\\Helpers\\Csrf;
use App\\Helpers\\Flash;
use App\\Helpers\\Validation;

// In a form:
echo Csrf::field();

// In a POST handler, before processing the action:
Csrf::validate((string) ($_POST['_token'] ?? ''));

$errors = Validation::validate($_POST, [
    'name' => ['required', 'string', 'min:2', 'max:100'],
    'email' => ['required', 'email', 'max:255'],
]);

// After verifying credentials against the users table:
Auth::login($user); // $user must contain an id
Flash::success('Welcome back.');

// On protected pages:
Auth::requireAuth('/admin/login');
```

`Auth::login()` must only be called after the controller has verified the submitted password against the stored password hash. Never place a password or password hash in the session user array.

`Upload::storeImage()` expects a real PHP HTTP upload (`is_uploaded_file`) and an application-controlled destination directory. It returns the generated filename; store that filename/path in the relevant database record. SVG files are intentionally not accepted by this image upload helper.

Sessions are started automatically by the session-dependent helper classes. Do not output HTML or whitespace before calling them.
