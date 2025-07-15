<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

    <!-- PHP error message for duplicate email -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 max-w-sm mx-auto text-center">
            This email is already registered!
        </div>
    <?php endif; ?>

    <form action="register_user.php" method="POST" class="bg-white p-6 rounded shadow-md w-full max-w-sm mx-auto">
        <input type="text" name="name" placeholder="Name" required class="block w-full mb-4 p-2 border rounded">
        <input type="email" name="email" placeholder="Email" required class="block w-full mb-4 p-2 border rounded">
        <input type="password" name="password" placeholder="Password" required
            class="block w-full mb-4 p-2 border rounded">

        <!-- Register Button -->
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full mb-2">Register</button>

        <!-- Login Button (link to login.php) -->
        <a href="login.php" class="block text-center bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Already
            have an account? Login</a>
    </form>
</body>

</html>