<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

    <?php if (isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 max-w-sm mx-auto text-center">
            This email is already registered!
        </div>
    <?php endif; ?>

    <form action="register_user.php" method="POST" enctype="multipart/form-data"
        class="p-6 bg-white rounded-xl shadow max-w-sm mx-auto">
        <!-- Avatar Display -->
        <div class="w-24 h-24 rounded-full mx-auto mb-4 bg-gray-200 flex items-center justify-center overflow-hidden"
            id="avatarWrapper">
            <img id="avatarPreview" src="https://cdn-icons-png.flaticon.com/512/9131/9131529.png" alt="Default Avatar"
                class="w-full h-full object-cover">
        </div>

        <!-- File Input -->
        <input type="file" name="profile_img" id="profile_img" class="hidden" accept="image/*"
            onchange="previewImage(event)">
        <label for="profile_img" class="block text-center text-blue-500 cursor-pointer">Upload Image</label>

        <!-- Hidden Input for remove image (can be handled later if needed) -->
        <input type="hidden" name="remove_image" id="remove_image" value="0">

        <!-- User Info Inputs -->
        <input type="text" name="name" placeholder="Name" class="w-full border p-2 rounded mt-4" required>
        <input type="email" name="email" placeholder="Email" class="w-full border p-2 rounded mt-2" required>
        <input type="password" name="password" placeholder="Password" class="w-full border p-2 rounded mt-2" required>
        <input type="date" name="dob" class="w-full border p-2 rounded mt-2" required>
        <input type="text" name="contact" placeholder="Contact" class="w-full border p-2 rounded mt-2" required>
        <input type="text" name="role" placeholder="Role" class="w-full border p-2 rounded mt-2" required>

        <!-- Submit -->
        <button type="submit" class="mt-4 w-full bg-blue-500 text-white p-2 rounded">Register</button>

        <!-- Already have account text -->
        <p class="mt-4 text-center text-m text-gray-800">
            Already have an account?
            <a href="login.php" class="text-blue-600 font-medium hover:underline">Login</a>
        </p>


    </form>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
                document.getElementById('remove_image').value = "0";
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>

</html>