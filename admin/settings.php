<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

verify_csrf();

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | Basic validation
    |--------------------------------------------------------------------------
    */

    if (
        $currentPassword === '' ||
        $newPassword === '' ||
        $confirmPassword === ''
    ) {
        $error = 'All password fields are required.';
    }

    /*
    |--------------------------------------------------------------------------
    | Password length
    |--------------------------------------------------------------------------
    */

    elseif (strlen($newPassword) < 8) {

        $error = 'New password must be at least 8 characters long.';

    }

    /*
    |--------------------------------------------------------------------------
    | Confirm password
    |--------------------------------------------------------------------------
    */

    elseif ($newPassword !== $confirmPassword) {

        $error = 'New password and confirmation password do not match.';

    }

    else {

        /*
        |--------------------------------------------------------------------------
        | Get currently logged-in admin
        |--------------------------------------------------------------------------
        */

        $stmt = $pdo->prepare("
            SELECT id, password
            FROM admins
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $_SESSION['admin_id']
        ]);

        $admin = $stmt->fetch();

        if (!$admin) {

            $error = 'Admin account could not be found.';

        }

        /*
        |--------------------------------------------------------------------------
        | Verify current password
        |--------------------------------------------------------------------------
        */

        elseif (!password_verify($currentPassword, $admin['password'])) {

            $error = 'Current password is incorrect.';

        }

        /*
        |--------------------------------------------------------------------------
        | Prevent using same password
        |--------------------------------------------------------------------------
        */

        elseif (password_verify($newPassword, $admin['password'])) {

            $error = 'New password must be different from the current password.';

        }

        else {

            /*
            |--------------------------------------------------------------------------
            | Generate secure password hash
            |--------------------------------------------------------------------------
            */

            $newPasswordHash = password_hash(
                $newPassword,
                PASSWORD_DEFAULT
            );

            if ($newPasswordHash === false) {

                $error = 'Unable to secure the new password.';

            } else {

                /*
                |--------------------------------------------------------------------------
                | Update database
                |--------------------------------------------------------------------------
                */

                $stmt = $pdo->prepare("
                    UPDATE admins
                    SET password = :password
                    WHERE id = :id
                ");

                $stmt->execute([
                    'password' => $newPasswordHash,
                    'id'       => $_SESSION['admin_id']
                ]);

                /*
                |--------------------------------------------------------------------------
                | Regenerate session after sensitive account change
                |--------------------------------------------------------------------------
                */

                session_regenerate_id(true);

                rotate_csrf_token();

                $success = 'Password changed successfully.';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Admin Settings — Nitya Ritual E-Store</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f6f6f6;
    color: #222;
}

.page {
    max-width: 700px;
    margin: 60px auto;
    padding: 0 20px;
}

.card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 10px;
    padding: 30px;
}

h1 {
    margin-top: 0;
    margin-bottom: 8px;
}

.subtitle {
    color: #666;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: 600;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

.form-group input:focus {
    outline: none;
    border-color: #8B1E1E;
}

.btn {
    width: 100%;
    border: none;
    background: #8B1E1E;
    color: #fff;
    padding: 13px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 600;
}

.btn:hover {
    background: #741818;
}

.success {
    background: #eaf7ee;
    color: #1d6b35;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.error {
    background: #fdecec;
    color: #a00000;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.back {
    display: inline-block;
    margin-bottom: 20px;
    color: #8B1E1E;
    text-decoration: none;
    font-weight: 600;
}

</style>

</head>

<body>

<div class="page">

    <a href="index.php" class="back">
        ← Back to Dashboard
    </a>

    <div class="card">

        <h1>Admin Security</h1>

        <p class="subtitle">
            Change your administrator account password.
        </p>

        <?php if ($success): ?>

            <div class="success">
                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
            </div>

        <?php endif; ?>

        <?php if ($error): ?>

            <div class="error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>

        <?php endif; ?>

        <form method="POST">

        <?= csrf_field() ?>

            <div class="form-group">

                <label for="current_password">
                    Current Password
                </label>

                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    autocomplete="current-password"
                    required
                >

            </div>

            <div class="form-group">

                <label for="new_password">
                    New Password
                </label>

                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

            </div>

            <div class="form-group">

                <label for="confirm_password">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    minlength="8"
                    autocomplete="new-password"
                    required
                >

            </div>

            <button type="submit" class="btn">
                Change Password
            </button>

        </form>

    </div>

</div>

</body>

</html>