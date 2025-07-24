<?php
include 'db.php'; 

$email = $_SESSION['email'] ?? ''; 
$success = $error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$email) {
        $error = "User not logged in.";
    } else {
        // Fetch the current hashed password from DB
        $stmt = $conn->prepare("SELECT password FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $hashedPassword = $row['password'];

            if (!password_verify($old_password, $hashedPassword)) {
                $error = "Old password is incorrect.";
            } elseif ($new_password !== $confirm_password) {
                $error = "New passwords do not match.";
            } else {
                $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
                $update->bind_param("ss", $new_hashed_password, $email);

                if ($update->execute()) {
                    $success = "Password changed successfully.";
                } else {
                    $error = "Failed to update password. Try again.";
                }
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 ">
     <h1 class="text-2xl font-semibold text-gray-800 mb-4">Settings</h1>    
    <div id="main" class="w-full max-w-xl bg-white border border-xl shadow rounded-xl p-8">
        <div id="heading">
           <h2 class="text-xl font-bold text-gray-800 mb-4 p-2">Change Password</h2>
        </div>

        <!-- Success or Error Message -->
        <?php if (!empty($success)): ?>
            <p class="text-green-600 font-medium mb-4"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p class="text-red-600 font-medium mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-4 my-6 ">
            <div class="flex flex-col gap-2">
                <label for="old_password" class="mb-1 font-medium text-gray-700">Old Password</label>
                <input type="password" id="old_password" name="old_password" required
                    class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                <label for="new_password" class="mb-1 font-medium text-gray-700">New Password</label>
                <input type="password" id="new_password" name="new_password" required
                    class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                <label for="confirm_password" class="mb-1 font-medium text-gray-700">Re-enter New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                    class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-40 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg">
                    Save Password
                </button>
            </div>
        </form>
    </div>
</body>
