<?php
session_start();

require_once __DIR__ . '/../app/Core/Auth.php';
Auth::check();

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Validator.php';

$db = Database::getInstance();

/**
 * Fetch current user data
 */
$stmt = $db->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$errors = [];
$success = false;

/**
 * Handle form submission
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $validator = new Validator();
    $validator->validateRequired('name', $name);
    $validator->validateRequired('email', $email);

    if ($email !== '') {
        $validator->validateEmail('email', $email);
    }

    // Check email uniqueness (excluding current user)
    if (!$validator->hasErrors()) {
        $stmt = $db->prepare(
            "SELECT id FROM users WHERE email = ? AND id != ?"
        );
        $stmt->execute([$email, $_SESSION['user_id']]);

        if ($stmt->fetch()) {
            $errors['email'] = 'Email already in use.';
        }
    }

    // Update profile
    if (!$validator->hasErrors() && empty($errors)) {
        $stmt = $db->prepare(
            "UPDATE users SET name = ?, email = ? WHERE id = ?"
        );
        $stmt->execute([$name, $email, $_SESSION['user_id']]);

        // Update session
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;

        // Update local variable so form reflects changes immediately
        $user['name']  = $name;
        $user['email'] = $email;

        $success = true;
    }

    $errors = array_merge($validator->getErrors(), $errors);
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile | Interactive Cares</title>
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
                <a href="edit-profile.php" class="block p-3 rounded-lg bg-gray-100 font-medium">Edit Profile</a>
                <a href="change-password.php" class="block p-3 rounded-lg hover:bg-gray-100">Change Password</a>
                <a href="logout.php" class="block p-3 rounded-lg hover:bg-gray-100 text-red-600">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profile</h2>

            <div class="max-w-3xl bg-white rounded-xl shadow p-6">

                <!-- Success Message -->
                <?php if ($success): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded text-green-700">
                        Profile updated successfully.
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
                        <label class="block text-sm font-semibold mb-1">Full Name</label>
                        <input
                            type="text"
                            name="name"
                            value="<?= htmlspecialchars($user['name']) ?>"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500" />
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-indigo-500" />
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            Save Changes
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>
</body>

</html>