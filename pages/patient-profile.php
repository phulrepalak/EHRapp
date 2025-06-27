<?php
include "db.php";

// Get the patient ID safely
$id = $_GET["id"] ?? null;

if ($id && is_numeric($id)) {
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
    $stmt->bind_param("i", $id); // 'i' = integer
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "patient not found";
    }
    $stmt->close();
    $conn->close();
}
?>


<div class="  w-full h-full bg-white rounded-xl  text-left p-8">
    <div class="mb-2">
        <a href="admin-panel.php?page=patients"
            class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold px-4 py-2 rounded shadow">
            ← Back
        </a>
    </div>

    <div id="upper" class=" flex flex-row gap-8  w-full h-80 bg-grey-400 rounded-xl border-2 border-blue-400  shadow-xl text-left p-8 ">
        <div id="profile photo" class="w-48 h-48  space-xl rounded-xl mb-2  p-8 ">
            <img class="h-full w-full object-fill rounded shadow-2xl" src="/ehr-app/assets/images/a.png" alt="">
        </div>
        <div id="detailsec" class="w-full h-64 bg-white flex flex-row gap-8 rounded-xl ">
            <div id="contect" class=" w-96 h-64  justify-items-start pl-4">
                <h2 class="font-bold  pt-4 ">Contact details:</h2>
                <div class="  flex flex-col gap-2 pt-6">
                    <p> <?php echo $row["contact"]; ?></p>
                    <p> <?php echo $row["name"]; ?></p>
                    <p> harshbhagat679@gmail.com</p>
                    <p> Weight-58</p>
                    <p> </p>
                </div>

            </div>

            <div id="overview" class="w-full h-64    pl-4 pt-4">
                <h1 class="font-bold">Overview</h1>
                <div class="grid grid-cols-3 gap-2 pt-6">
                    <div id="gender" class=" w-32 h-24">Gender
                        <p class="font-bold"><?php echo $row["gender"]; ?></p>
                    </div>
                    <div id="dob" class="size-fit  ">Death of Birth
                        <p class="font-bold ">01/07/2003</p>
                    </div>
                    <div id="doc" class="size-fit ">Doctor
                        <p class="font-bold ">Manish Jain</p>
                    </div>
                    <div id="visit" class="size-fit "> Visit
                        <p class="font-bold ">26/05/2025</p>
                    </div>
                    <div id="nextvisit" class="size-fit">Next Visit
                        <p class="font-bold ">26/06/2025</p>
                    </div>
                    <div id="Disease" class="size-fit ">Disease
                        <p class="font-bold ">Dengue</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- charts -->
    <div id="middle" class=" flex flex-row gap-8  w-full h-80 rounded-xl 64 text-left mt-4 p-8 overflow-hiZZ ">
        <div id="healthscore" class="w-96 h-64  space-xl  rounded-xl mb-2  p-8 border-4 ">healthscore</div>
        <div id="healthscore" class="w-1/2 h-64 space-xl      rounded-xl mb-2  p-8 border-4 ">healthscore</div>
        <div id="healthscore" class="w-48 h-64  space-xl   rounded-xl mb-2  p-8 border-4 ">healthscore</div>
    </div>





</div>