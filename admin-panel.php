<?php
include("session.php");
$title = 'CareYug EHR';
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
include 'includes/header.php';

?>
<!-- Admin Panel -->
<div class="flex">
    <!-- Sidebar here   -->
    <?php include 'includes/sidebar.php'; ?>
    <div class="flex flex-col flex-1 bg-gray-100 ">
        <div class="flex flex-1 bg-white p-4">
            <div class="h-full w-full bg-white rounded-xl shadow-lg p-6 text-center">

                <!-- Page Content Here -->

                <?php
                if ($page == 'dashboard') {
                    echo '<h2 class="text-2xl font-semibold text-gray-800 mb-4">Welcome to CareYug EHR Dashboard</h2>';
                } elseif ($page == 'patients') {
                    include 'pages/patients.php';
                } elseif ($page == 'documents') {
                    include 'pages/documents.php';
                } elseif ($page == 'profile') {
                    include 'pages/profile.php';
                } elseif ($page == 'settings') {
                    include 'pages/settings.php';
                } elseif ($page == 'add-patient') {
                    include 'pages/add-patient.php';
                } else {
                    echo '<p class="text-red-500">Page not found.</p>';
                }
                ?>
            </div>
        </div>
        <!-- Copyrights -->
        <footer class="text-grey text-center py-4 mt-auto border-t">
            <p>&copy; 2025 CareYug EHR. All rights reserved.</p>
        </footer>
    </div>
</div>
<?php include 'includes/footer.php'; ?>