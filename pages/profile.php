<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "db.php";

// Get user ID from session
$userId = $_SESSION['user_id'] ?? null;
$success = "";
$error = "";
$user = [];

// Default avatar
$defaultAvatar = "https://cdn-icons-png.flaticon.com/512/9131/9131529.png";

// Fetch user data
if ($userId) {
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Handle profile removal
if (isset($_POST['remove_photo'])) {
    $removeStmt = $conn->prepare("UPDATE user SET profile_img = ? WHERE id = ?");
    $removeStmt->bind_param("si", $defaultAvatar, $userId);
    if ($removeStmt->execute()) {
        $success = "Profile photo removed.";
        $user['profile_img'] = $defaultAvatar;
    } else {
        $error = "Failed to remove photo.";
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_profile'])) {
    $name = $_POST['name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $email = $_POST['email'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $role = $_POST['role'] ?? '';

    // Use existing profile or default
    $profileImgPath = $user['profile_img'] ?? $defaultAvatar;

    // New image upload
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $fileName = basename($_FILES["profile_img"]["name"]);
        $targetFilePath = $uploadDir . time() . "_" . $fileName;
        move_uploaded_file($_FILES["profile_img"]["tmp_name"], $targetFilePath);
        $profileImgPath = $targetFilePath;
    }

    // Update data
    $update = $conn->prepare("UPDATE user SET name = ?, dob = ?, email = ?, contact = ?, role = ?, profile_img = ? WHERE id = ?");
    $update->bind_param("ssssssi", $name, $dob, $email, $contact, $role, $profileImgPath, $userId);

    if ($update->execute()) {
        $success = "Profile updated successfully!";
        $user['profile_img'] = $profileImgPath;
        $user['name'] = $name;
        $user['dob'] = $dob;
        $user['email'] = $email;
        $user['contact'] = $contact;
        $user['role'] = $role;
    } else {
        $error = "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-4">My Profile</h1>

        <?php if ($success): ?>
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <!-- Profile Image -->
            <div class="flex items-center gap-4">
                <img src="<?= $user['profile_img'] ?? $defaultAvatar ?>" class="w-24 h-24 rounded-full object-cover border">
                <input type="file" name="profile_img" accept="image/*" class="text-sm">
                <?php if (!empty($user['profile_img']) && $user['profile_img'] !== $defaultAvatar): ?>
                    <button type="submit" name="remove_photo" class="text-red-600 text-sm underline">Remove Photo</button>
                <?php endif; ?>
            </div>

            <!-- Name -->
            <div>
                <label class="block font-medium">Name</label>
                <input type="text" name="name" value="<?= $user['name'] ?? '' ?>" required class="w-full border rounded p-2">
            </div>

            <!-- Date of Birth -->
            <div>
                <label class="block font-medium">Date of Birth</label>
                <input type="date" name="dob" value="<?= $user['dob'] ?? '' ?>" class="w-full border rounded p-2">
            </div>

            <!-- Email -->
            <div>
                <label class="block font-medium">Email</label>
                <input type="email" name="email" value="<?= $user['email'] ?? '' ?>" required class="w-full border rounded p-2">
            </div>

            <!-- Contact -->
            <div>
                <label class="block font-medium">Contact</label>
                <input type="text" name="contact" value="<?= $user['contact'] ?? '' ?>" class="w-full border rounded p-2">
            </div>

            <!-- Role -->
            <div>
                <label class="block font-medium">Role</label>
                <input type="text" name="role" value="<?= $user['role'] ?? '' ?>" class="w-full border rounded p-2">
            </div>

            <!-- Save Button -->
            <button type="submit" name="save_profile" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Save</button>
        </form>
    </div>
</body>
</html>
