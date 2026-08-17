<?php

/**
 * Generate or return the current session CSRF token.
 */
function csrf_token(): string
{
    if (
        empty($_SESSION['csrf_token']) ||
        !is_string($_SESSION['csrf_token'])
    ) {
        $_SESSION['csrf_token'] = bin2hex(
            random_bytes(32)
        );
    }

    return $_SESSION['csrf_token'];
}


/**
 * Generate a hidden CSRF field for forms.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars(
            csrf_token(),
            ENT_QUOTES,
            'UTF-8'
        ) .
        '">';
}


/**
 * Verify the CSRF token submitted with a request.
 */
function verify_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $submittedToken = $_POST['csrf_token'] ?? '';

    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (
        !is_string($submittedToken) ||
        !is_string($sessionToken) ||
        $submittedToken === '' ||
        $sessionToken === '' ||
        !hash_equals(
            $sessionToken,
            $submittedToken
        )
    ) {
        http_response_code(403);

        exit('Invalid security token.');
    }
}


/**
 * Rotate the CSRF token after a sensitive authentication event.
 */
function rotate_csrf_token(): void
{
    unset($_SESSION['csrf_token']);

    csrf_token();
}