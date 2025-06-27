<!-- Sidebar -->
<div class=" flex flex-col h-screen w-64 bg-blue-800 text-white rounded-r-lg shadow-lg p-4 pr-0">
    <!-- sidebar Header -->
    <div class="  text-xl font-semibold mb-6 text-center">
        CareYug EHR
    </div>
    <!-- navigation links -->
    <nav class="flex-1 space-y-2 font-bold">
        <a href="?page=dashboard" class="block px-4 py-2 <?php echo $page == 'dashboard' ? 'bg-white text-blue-800 rounded-l-lg' : 'hover:bg-white hover:text-blue-800 hover:rounded-l-lg'; ?>">Dashboard</a>
        <a href="?page=patients" class="block px-4 py-2 <?php echo $page == 'patients' ? 'bg-white text-blue-800 rounded-l-lg' : 'hover:bg-white hover:text-blue-800 hover:rounded-l-lg'; ?>">Patients</a>
        <a href="?page=appointments" class="block px-4 py-2 <?php echo $page == 'appointments' ? 'bg-white text-blue-800 rounded-l-lg' : 'hover:bg-white hover:text-blue-800 hover:rounded-l-lg'; ?>">Appointments</a>
        <a href="?page=documents" class="block px-4 py-2 <?php echo $page == 'documents' ? 'bg-white text-blue-800 rounded-l-lg' : 'hover:bg-white hover:text-blue-800 hover:rounded-l-lg'; ?>">Documents</a>
        <a href="?page=profile" class="block px-4 py-2   <?php echo $page == 'profile' ? 'bg-white text-blue-800 rounded-l-lg' : 'hover:bg-white hover:text-blue-800 hover:rounded-l-lg '; ?>">Profile</a>
        <a href="?page=settings" class="block px-4 py-2 <?php echo $page == 'settings' ? 'bg-white text-blue-800 rounded-l-lg' : 'hover:bg-white hover:text-blue-800 hover:rounded-l-lg'; ?>">Settings</a>
    </nav>
    <form action="logout.php" method="post" class="mt-auto pr-4">
        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded">Logout</button>
    </form>
</div>