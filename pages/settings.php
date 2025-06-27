<div id="main ">
    <div id="heading">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4 ">Settings</h1>

    </div>
    <div id="form" class="w-full  bg-white border border-xl  shadow rounded-xl p-8  ml-4 ">
        <h2 class="text-xl font-bold text-gray-800 mb-4  max-w-sm p-2 ">Change Password</h2>
        <div class="h-px bg-gray-300 mb-4"></div>

        <form action="#" method="POST" class="" space-y-4>

            <div class="flex flex-col max-w-sm gap-2">
                <label for="old_password" class="mb-1 font-medium text-gray-700">Old Password</label>
                <input type="password" id="old_password" name="old_password" required
                    class="border max-w-sm border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                <label for="new_password" class="mb-1 font-medium text-gray-700">New Password</label>
                <input type="password" id="new_password" name="new_password" required
                    class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 ">

                <label for="confirm_password" class="mb-1 font-medium text-gray-700">Re-enter New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                    class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 ">

            </div>
            <div class="pt-4 max-w-sm">
                
                <button type="submit"
                    class="w-40 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg">
                    Save Password
                </button>
                
            </div>

        </form>
    </div>

</div>