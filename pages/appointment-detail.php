      <!-- Form -->
        <div id="mid" class="w-full h-full border rounded-xl p-4 bg-white">
            <div class="w-full h-auto mx-auto border rounded-xl p-6 bg-white shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-800">Appointment detail</h3>

                    <button id="editBtn" type="button"
                        class="cursor-pointer bg-blue-500 text-white px-4 py-1 rounded-md text-sm">
                        Edit
                    </button>
                </div>

                <div class="h-px bg-gray-300 mb-4"></div>

                <form id="profileForm" class="w-xl grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="flex flex-col">
                        <label class="font-semibold mb-1" for="firstname"> Name</label>
                        <input id="firstname" name="firstname" value="Harsh" type="text" readonly
                            class="read-only:bg-gray-100 read-only:text-gray-500 border border-gray-300 rounded px-3 py-1 text-sm" />
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold mb-1" for="lastname">Doctor</label>
                        <input id="" name="Doctor" value=" Dr.Manish Jain" type="text" readonly
                            class="read-only:bg-gray-100 read-only:text-gray-500 border border-gray-300 rounded px-3 py-1 text-sm" />
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold mb-1" for="dob">Date </label>
                        <input id="date" name="date" value="2003-07-01" type="date" readonly
                            class="read-only:bg-gray-100 read-only:text-gray-500 border border-gray-300 rounded px-3 py-1 text-sm" />
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold mb-1" for="email">Email</label>
                        <input id="email" name="email" value="harshbhagat679@gmail.com" type="email" readonly
                            class="read-only:bg-gray-100 read-only:text-gray-500 border border-gray-300 rounded px-3 py-1 text-sm" />
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold mb-1" for="contact">Contact No.</label>
                        <input id="contact" name="contact" value="7666511499" type="tel" readonly
                            class="read-only:bg-gray-100 read-only:text-gray-500 border border-gray-300 rounded px-3 py-1 text-sm" />
                    </div>

                    <div class="flex flex-col">
                        <label class="font-semibold mb-1" for="role">Time</label>
                        <input id="time" name="time" value="time" type="time" readonly
                            class="read-only:bg-gray-100 read-only:text-gray-500 border border-gray-300 rounded px-3 py-1 text-sm" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Simple JS to Make Form Editable -->
<script>
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

        //  button text and color
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
</script>