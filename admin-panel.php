<?php
include("session.php");
$title = 'CareYug EHR';
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
include 'includes/header.php';

?>
<!-- Admin Panel -->
<div class="flex  h-screen">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex flex-col flex-1 bg-white  overflow-hidden">

        <!-- Main Page Content -->
        <div class="flex-1 overflow-y-auto ">
            <div class="w-full    p-6 text-center h-full">

                <?php
                if ($page == 'dashboard') {
                    include 'pages/dashboard.php';
                } elseif ($page == 'patients') {
                    include 'pages/patients.php';
                } elseif ($page == 'appointment-detail') {
                    include 'pages/appointment-detail.php';
                } elseif ($page == 'appointments') {
                    include 'pages/appointments.php';
                } elseif ($page == 'documents') {
                    include 'pages/documents.php';
                } elseif ($page == 'profile') {
                    include 'pages/profile.php';
                } elseif ($page == 'settings') {
                    include 'pages/settings.php';
                } elseif ($page == 'add-patient') {
                    include 'pages/add-patient.php';
                } elseif ($page == 'patient-profile') {
                    include 'pages/patient-profile.php';
                } else {
                    echo '<p class="text-red-500">Page not found.</p>';
                }
                ?>

            </div>
        </div>

        <!-- Footer -->
        <footer class="text-gray-600 text-center py-4 border-t">
            <p>&copy; 2025 CareYug EHR. All rights reserved.</p>
        </footer>
    </div>
</div>

<?php include 'includes/footer.php'; ?>