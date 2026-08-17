<?php

/*
|--------------------------------------------------------------------------
| Admin Session Configuration
|--------------------------------------------------------------------------
*/

const ADMIN_SESSION_TIMEOUT = 1800; // 30 minutes
const ADMIN_SESSION_REGENERATION = 900; // 15 minutes


/*
|--------------------------------------------------------------------------
| Session Fingerprint
|--------------------------------------------------------------------------
|
| We intentionally use User-Agent rather than IP address.
|
| IP addresses can legitimately change during a user's session,
| especially on mobile networks, so binding sessions to IPs can
| unnecessarily log legitimate users out.
|
*/

function getSessionFingerprint(): string
{
    return hash(
        'sha256',
        $_SERVER['HTTP_USER_AGENT'] ?? ''
    );
}


/*
|--------------------------------------------------------------------------
| Check Admin Login
|--------------------------------------------------------------------------
*/

function isAdminLoggedIn(): bool
{
    return isset($_SESSION['admin_id'])
        && is_numeric($_SESSION['admin_id']);
}


/*
|--------------------------------------------------------------------------
| Initialize Authenticated Session
|--------------------------------------------------------------------------
*/

function initializeAdminSession(int $adminId, string $adminName): void
{
    $_SESSION['admin_id'] = $adminId;
    $_SESSION['admin_name'] = $adminName;

    $_SESSION['admin_login_time'] = time();
    $_SESSION['admin_last_activity'] = time();

    $_SESSION['admin_fingerprint'] =
        getSessionFingerprint();

    $_SESSION['admin_last_regeneration'] = time();
}


/*
|--------------------------------------------------------------------------
| Validate Admin Session
|--------------------------------------------------------------------------
*/

function validateAdminSession(): bool
{
    if (!isAdminLoggedIn()) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Session Timeout
    |--------------------------------------------------------------------------
    */

    $lastActivity =
        $_SESSION['admin_last_activity'] ?? 0;

    if (
        !is_numeric($lastActivity) ||
        (time() - (int)$lastActivity) >
            ADMIN_SESSION_TIMEOUT
    ) {

        destroyAdminSession();

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Session Fingerprint
    |--------------------------------------------------------------------------
    */

    $storedFingerprint =
        $_SESSION['admin_fingerprint'] ?? '';

    $currentFingerprint =
        getSessionFingerprint();

    if (
        $storedFingerprint === '' ||
        !hash_equals(
            $storedFingerprint,
            $currentFingerprint
        )
    ) {

        destroyAdminSession();

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Update Activity
    |--------------------------------------------------------------------------
    */

    $_SESSION['admin_last_activity'] = time();


    /*
    |--------------------------------------------------------------------------
    | Periodic Session ID Regeneration
    |--------------------------------------------------------------------------
    */

    $lastRegeneration =
        $_SESSION['admin_last_regeneration'] ?? 0;

    if (
        !is_numeric($lastRegeneration) ||
        (time() - (int)$lastRegeneration) >=
            ADMIN_SESSION_REGENERATION
    ) {

        session_regenerate_id(true);

        $_SESSION['admin_last_regeneration'] = time();
    }

    return true;
}


/*
|--------------------------------------------------------------------------
| Require Admin
|--------------------------------------------------------------------------
*/

function requireAdmin(): void
{
    if (!validateAdminSession()) {

        header('Location: login.php');

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Destroy Admin Session
|--------------------------------------------------------------------------
*/

function destroyAdminSession(): void
{
    $_SESSION = [];

    /*
    |--------------------------------------------------------------------------
    | Remove Session Cookie
    |--------------------------------------------------------------------------
    */

    if (ini_get('session.use_cookies')) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            [
                'expires' => time() - 42000,
                'path' => $params['path'] ?? '/',
                'domain' => $params['domain'] ?? '',
                'secure' => $params['secure'] ?? false,
                'httponly' => $params['httponly'] ?? true,
                'samesite' => $params['samesite'] ?? 'Lax'
            ]
        );
    }

    session_destroy();
}