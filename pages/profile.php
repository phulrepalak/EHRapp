<!-- 
<div>
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">My Profile</h1>
    <div id="main" class="flex flex-col gap-5">
       Profile Section -->
<!-- <div id="upper" class="w-full h-32 border rounded-xl bg-white flex flex-row items-center gap-5">
            <div id="profile" class="w-24 h-24 border rounded-full ml-8">
                <img class="h-full w-full object-fill rounded-full" src="/ehr-app/assets/images/a.png" alt="">
            </div>
            <div class="flex flex-col items-start ml-8">
                <div class="font-semibold">
                    <p>Harsh Bhagat</p>
                </div>
                <div class="font-thin text-sm">
                    <p>Admin</p>
                </div>
                <div class="font-thin text-sm">
                    <p>Globus Hospital</p>
                </div>
            </div>
        </div> -->

<!-- Form -->
<!-- <div id="mid" class="w-full h-full border rounded-xl p-4 bg-white">
            <div class="w-full h-full mx-auto border rounded-xl p-6 bg-white shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Personal Information</h3>
                    <button id="editBtn" type="button"
                        class="cursor-pointer bg-blue-500 text-white px-4 py-1 rounded-md text-sm">
                        Edit
                    </button>
                </div>
                <div class="h-px bg-gray-300 mb-4"></div>
                <form id="profileForm" class="w-xl grid grid-cols-1 md:grid-cols-3 gap-8">
                    Input fields -->
<!-- </form>
            </div>
        </div>
    </div>
</div>  -->

<!-- <script> -->
<!-- /*
    const editBtn = document.getElementById('editBtn');
    const inputs = document.querySelectorAll('#profileForm input');

    let isEditing = false;

    editBtn.addEventListener('click', () => {
        isEditing = !isEditing;

        inputs.forEach(input => {
            if (isEditing) {
                input.removeAttribute('readonly');
                input.classList.remove('read-only:bg-gray-100', 'read-only:text-gray-500');
                input.classList.add('bg-white', 'text-black', 'border-blue-500');
            } else {
                input.setAttribute('readonly', true);
                input.classList.add('read-only:bg-gray-100', 'read-only:text-gray-500');
                input.classList.remove('bg-white', 'text-black', 'border-blue-500');
            }
        });

        if (isEditing) {
            editBtn.textContent = 'Save';
            editBtn.classList.remove('bg-blue-500');
            editBtn.classList.add('bg-green-500');
        } else {
            editBtn.textContent = 'Edit';
            editBtn.classList.remove('bg-green-500');
            editBtn.classList.add('bg-blue-500');
        }
    });
*/ -->
<!-- </script> -->


<?php
include 'db.php';

$email = $_SESSION['email'] ?? '';
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $contact = $_POST['contact'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE user SET name = ?, dob = ?, contact = ?, role = ? WHERE email = ?");
    $stmt->bind_param("sssss", $name, $dob, $contact, $role, $email);
    $stmt->execute();

    if ($stmt->affected_rows >= 0) {
        $success = "Your profile has been updated successfully!";
    }
    $stmt->close();
}

// Fetch latest user data
$stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">My Profile</h1>

        <!-- Success message -->
        <?php if (!empty($success)): ?>
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <!-- Profile Card -->
        <div class="bg-white border rounded-xl p-6 shadow-md flex gap-6 items-center relative">
            <div class="relative w-32 h-32">
                <img src="<?php echo !empty($user['profile_img']) ? htmlspecialchars($user['profile_img']) : 'https://cdn-icons-png.flaticon.com/512/9131/9131529.png'; ?>"
                    class="w-full h-full object-cover rounded-full border-2 border-gray-300 shadow" alt="Profile Image">

                <label for="profile_img"
                    class="absolute bottom-0 right-0 bg-white p-2 rounded-full shadow-md cursor-pointer hover:bg-gray-100 border border-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 5a2 2 0 012-2h1l1-1h4l1 1h1a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm6 3a3 3 0 100 6 3 3 0 000-6z" />
                    </svg>
                    <input type="file" id="profile_img" name="profile_img" class="hidden">
                </label>
            </div>

            <div class="flex flex-col">
                <p class="text-xl font-bold"><?php echo htmlspecialchars($user['name']); ?></p>
                <p class="text-sm text-gray-500"><?php echo htmlspecialchars($user['role']); ?></p>
                <p class="text-sm text-gray-400">Globus Hospital</p>
            </div>
        </div>

        <!-- Editable Form -->
        <div class="mt-6 bg-white border rounded-xl p-6 shadow-md">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Personal Information</h2>
                <button id="editBtn" type="button" class="bg-blue-500 text-white px-4 py-1 rounded-md text-sm">Edit</button>
            </div>

            <div class="h-px bg-gray-300 mb-4"></div>

            <form method="POST" id="profileForm" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" readonly
                        class="read-only:bg-gray-100 read-only:text-gray-500 w-full border border-gray-300 rounded px-3 py-1 text-sm">
                </div>
                <div>
                    <label for="dob" class="block text-sm font-medium">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" readonly
                        class="read-only:bg-gray-100 read-only:text-gray-500 w-full border border-gray-300 rounded px-3 py-1 text-sm">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly
                        class="bg-gray-100 text-gray-500 w-full border border-gray-300 rounded px-3 py-1 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label for="contact" class="block text-sm font-medium">Contact</label>
                    <input type="tel" id="contact" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>" readonly
                        class="read-only:bg-gray-100 read-only:text-gray-500 w-full border border-gray-300 rounded px-3 py-1 text-sm">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium">Role</label>
                    <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($user['role']); ?>" readonly
                        class="read-only:bg-gray-100 read-only:text-gray-500 w-full border border-gray-300 rounded px-3 py-1 text-sm">
                </div>
                <div class="md:col-span-3 text-right">
                    <button type="submit" id="saveBtn" style="display: none;"
                        class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md text-sm">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');
        const inputs = document.querySelectorAll('#profileForm input:not([type="email"])');

        let editing = false;

        editBtn.addEventListener('click', () => {
            editing = !editing;

            inputs.forEach(input => {
                input.readOnly = !editing;
                input.classList.toggle('bg-white', editing);
                input.classList.toggle('text-black', editing);
                input.classList.toggle('border-blue-500', editing);
                input.classList.toggle('read-only:bg-gray-100', !editing);
                input.classList.toggle('read-only:text-gray-500', !editing);
            });

            saveBtn.style.display = editing ? 'inline-block' : 'none';
            editBtn.textContent = editing ? 'Cancel' : 'Edit';
        });
    </script>
</body>
</html>
