<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>EHR Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-sm bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">EHR Login</h2>


        <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 max-w-sm mx-auto">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
        <?php endif; ?>

        <form action="login-handler.php" method="POST" class="space-y-4">
            <div>
                <input type="text" name="username" placeholder="Username" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
                Login
            </button>
        </form>
    </div>
</body>

</html>