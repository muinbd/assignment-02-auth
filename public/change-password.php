<?php
session_start();

require_once __DIR__ . '/../app/Core/Auth.php';
Auth::check();

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Validator.php';

$db = Database::getInstance();

$errors  = [];
$success = false;

/**
 * Handle password change
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword     = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $validator = new Validator();
    $validator->validateRequired('current_password', $currentPassword);
    $validator->validateRequired('new_password', $newPassword);
    $validator->validateRequired('confirm_password', $confirmPassword);

    if ($newPassword !== '') {
        $validator->validateMinLength('new_password', $newPassword, 6);
    }

    if ($newPassword !== $confirmPassword) {
        $errors['confirm_password'] = 'New passwords do not match.';
    }

    if (!$validator->hasErrors() && empty($errors)) {
        // Fetch current password hash
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $errors['current_password'] = 'Current password is incorrect.';
        } else {
            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $stmt = $db->prepare(
                "UPDATE users SET password = ? WHERE id = ?"
            );
            $stmt->execute([$hashedPassword, $_SESSION['user_id']]);

            // Security: regenerate session ID
            session_regenerate_id(true);

            $success = true;
        }
    }

    $errors = array_merge($validator->getErrors(), $errors);
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Change Password | Interactive Cares</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex flex-col lg:flex-row min-h-screen">

        <!-- Sidebar -->
        <aside class="lg:w-64 bg-white shadow-lg lg:h-screen lg:sticky lg:top-0">
            <div class="p-6 border-b">
                <h1 class="font-bold text-lg text-indigo-600">Interactive Cares</h1>
                <p class="text-xs text-gray-500">Dashboard</p>
            </div>

            <nav class="p-4 space-y-1">
                <a href="dashboard.php" class="block p-3 rounded-lg hover:bg-gray-100">My Profile</a>
                <a href="edit-profile.php" class="block p-3 rounded-lg hover:bg-gray-100">Edit Profile</a>
                <a href="change-password.php" class="block p-3 rounded-lg bg-gray-100 font-medium">Change Password</a>
                <a href="logout.php" class="block p-3 rounded-lg hover:bg-gray-100 text-red-600">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Change Password</h2>

            <div class="max-w-3xl bg-white rounded-xl shadow p-6">

                <!-- Success Message -->
                <?php if ($success): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded text-green-700">
                        Password updated successfully.
                    </div>
                <?php endif; ?>

                <!-- Error Messages -->
                <?php if (!empty($errors)): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                        <ul class="text-sm text-red-600 space-y-1">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Current Password</label>
                        <input
                            type="password"
                            name="current_password"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="••••••••" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">New Password</label>
                        <input
                            type="password"
                            name="new_password"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="••••••••" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Confirm New Password</label>
                        <input
                            type="password"
                            name="confirm_password"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500"
                            placeholder="••••••••" />
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Update Password
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>

</html>