<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';


// Check if there's a success message
if (isset($_SESSION['success_message'])) {
  // Store the success message in a JavaScript variable
  echo "<script>var successMessage = '{$_SESSION['success_message']}';</script>";
  // Unset the success message to prevent it from displaying again
  unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
  echo "<script type='text/javascript'>alert('{$_SESSION['error_message']}');</script>";
  
  unset($_SESSION['error_message']); // Clear the message to prevent it from popping up again on refresh
}


if(isset($_POST['save'])){
  
    
  // Get the teacher's ID based on their login information (you need to implement this part)
$teacher_id = $_SESSION['userId']; // Replace this with the actual logged-in teacher's ID

// Get the current date
$dateTaken = date("Y-m-d");

// Check if attendance has been taken for today for any class associated with the teacher
$query_attendance = "SELECT COUNT(*) AS attendance_count
                    FROM tblvolunteerattendance
                    WHERE teacher_id = $teacher_id
                    AND dateTimeTaken = '$dateTaken'";

$result_attendance = mysqli_query($conn, $query_attendance);

var_dump($result_attendance); // Shows detailed information about the variable

if ($result_attendance) {
    $row_attendance = mysqli_fetch_assoc($result_attendance);
    $attendance_count = $row_attendance['attendance_count'];

    if ($attendance_count == 0) {
        // Insert the volunteers' attendance records for the teacher's classes if not already taken
        $insert_query = "INSERT INTO tblvolunteerattendance (volunteer_id, class_id, status, dateTimeTaken)
                         SELECT sc.volunteer_id, sc.class_id, 0, '$dateTaken'
                         FROM volunteer_classes sc
                         INNER JOIN teacher_classes tc ON sc.class_id = tc.class_id
                         WHERE tc.teacher_id = $teacher_id";

        $insert_result = mysqli_query($conn, $insert_query);

        if (!$insert_result) {
            // Handle insertion error if needed
            echo "Attendance insertion failed!";
        } else {
            // Attendance taken successfully
            echo "Attendance taken successfully!";
        }
    } else {
        // Attendance for today has already been taken
        echo "Attendance for today has already been taken!";
    }
} else {
    // Handle query error if necessary
    echo "Query error!";
}
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Select2 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

   <!-- Select2 JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

  <!-- jQuery UI Datepicker CSS -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- jQuery UI Datepicker -->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

  <script>
    $(document).ready(function() {
      // Initialize Datepicker
      $('#attendanceDate').datepicker({
        dateFormat: 'yy-mm-dd', // Set the date format to match your PHP date format
        // onSelect: function(dateText) {
        //   // Automatically submit the form when a date is selected
        //   $('form').submit();
        // }
      });
    });
  </script>
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Take Attendance (Today's Date: <?php echo $todaysDate = date("m-d-Y");?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Volunteers in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->


              <!-- Input Group -->
              <form method="post" action="save_thing.php">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
               <!-- Calendar Date Picker -->
    <label for="attendanceDate" style="margin-left: 20px;margin-top: 15px">Select Date for Attendance:</label>
    <input type="text" id="attendanceDate" name="attendanceDate" class="form-control" style="width: 200px; margin-left: 20px;" readonly>
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Volunteers by Class</h6>
                    <div>
                      <label for="classFilter" >Select Class:</label>
                      <select id="classFilter" name="classFilter" class="form-control">
                      <option value="">All Classes</option>
    <?php
    include '../Includes/dbcon.php';
    include '../Includes/session.php';

    $teacherId = $_SESSION['userId'];

    $queryClasses = "SELECT c.class_id, c.class_name
                    FROM classes c
                    INNER JOIN teacher_classes tc ON c.class_id = tc.class_id
                    WHERE tc.teacher_id = '$teacherId'";

    $resultClasses = mysqli_query($conn, $queryClasses);

    

    if ($resultClasses && mysqli_num_rows($resultClasses) > 0) {
     
        while ($row = mysqli_fetch_assoc($resultClasses)) {
            $classId = $row['class_id'];
            $className = $row['class_name'];
            $selected = (isset($_POST['classFilter']) && $_POST['classFilter'] == $className) ? 'selected' : '';
            echo "<option value='$className' $selected>$className</option>";
        }
    }
    ?>
                      </select>
                  </div>
                </div>
                <div class="table-responsive p-3">
                    <?php echo $statusMsg; ?>
                    <table class="table align-items-center table-flush table-hover">
                        
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Volunteer ID</th>
                                <th>Volunteer Name</th>
                                <th>Class Name</th>
                                <th>
                                    Check All <input type="checkbox" id="checkAll" style="vertical-align: middle; margin-left: 4px; margin-bottom: 5px; transform: scale(1.3);">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_POST['classFilter']) && !empty($_POST['classFilter'])) {
                              $className = $_POST['classFilter'];
                              
                          
                              $query = "SELECT s.volunteer_id, s.volunteer_name, c.class_id, c.class_name
                                        FROM volunteers s
                                        INNER JOIN volunteer_classes sc ON s.volunteer_id = sc.volunteer_id
                                        INNER JOIN classes c ON c.class_id = sc.class_id
                                        INNER JOIN teacher_classes tc ON c.class_id = tc.class_id
                                        WHERE tc.teacher_id = '$teacherId' AND c.class_name = '$className'";
                          } else {

                              
                              $query = "SELECT s.volunteer_id, s.volunteer_name, c.class_id, c.class_name
                                        FROM volunteers s
                                        INNER JOIN volunteer_classes sc ON s.volunteer_id = sc.volunteer_id
                                        INNER JOIN classes c ON c.class_id = sc.class_id
                                        INNER JOIN teacher_classes tc ON c.class_id = tc.class_id
                                        WHERE tc.teacher_id = '$teacherId'";
                          }
                          
                          

                            $rs = $conn->query($query);
                            $num = $rs->num_rows;
                            $sn = 0;
                            $status = "";
                            if ($num > 0) {
                              while ($rows = $rs->fetch_assoc()) {
                                $sn = $sn + 1;
                                $volunteerId = $rows['volunteer_id'];
                                // changed from classId to class_name, but just kept it called $classId
                                $className = $rows['class_name'];
                                $classId = $rows['class_id'];
                                $volunteerName = $rows['volunteer_name'];
                            
                                echo "
                                    <tr>
                                        <td>".$sn."</td>
                                        <td>".$volunteerId."</td>
                                        <td>".$volunteerName."</td>
                                        <td>".$className."</td>
                                        <td>
                                            <!-- Create checkboxes with class_id as array index and volunteer_id as value -->
                                            <input type='checkbox' name='check[$classId][]' value='$volunteerId' class='form-control'>
                                        </td>
                                    </tr>";
                            }
                            
                            
                          } else {
                              echo "<tr><td colspan='5'>No Records Found!</td></tr>";
                          }
                            ?>
                        </tbody>
                    </table>
                    <br>
                    <button type="submit" name="save" class="btn btn-primary">Take Attendance</button>
                </div>
            </div>
        </div>
    </div>

   

</form>
<!-- End of Form -->


<form method="post" action="add_volunteer.php">
    <div class="card mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Volunteer</h6>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="volunteerName">Volunteer Name:</label>
                <input type="text" class="form-control" id="volunteerName" name="volunteerName" required>
            </div>
            <div class="form-group">
                <label for="volunteerEmail">Volunteer Email:</label>
                <input type="email" class="form-control" id="volunteerEmail" name="volunteerEmail" required>
            </div>
            <div class="form-group">
                <label for="volunteerNumber">Volunteer Phone Number:</label>
                <input type="tel" class="form-control" id="volunteerNumber" name="volunteerNumber" required>
            </div>


            <div class="form-group">
                <label for="volunteerClasses">Select Classes:</label>
                <select id="volunteerClasses" name="volunteerClasses[]" class="form-control" multiple required>
                    <?php
                    // Fetch classes associated with the teacher
                    $queryClasses = "SELECT c.class_id, c.class_name
                                     FROM classes c
                                     INNER JOIN teacher_classes tc ON c.class_id = tc.class_id
                                     WHERE tc.teacher_id = '$teacherId'";

                    $resultClasses = mysqli_query($conn, $queryClasses);

                    if ($resultClasses && mysqli_num_rows($resultClasses) > 0) {
                        while ($row = mysqli_fetch_assoc($resultClasses)) {
                            $classId = $row['class_id'];
                            $className = $row['class_name'];
                            echo "<option value='$classId'>$className</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="addVolunteer" class="btn btn-primary">Add Volunteer</button>
        </div>
    </div>
</form>




          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

          </div>
        </div>
        <!---Container Fluid-->
      
  </div>

  </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
    </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  


  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>

<script>
$(document).ready(function() {
    $('#classFilter').change(function() {
        var selectedClass = $(this).val();

        // AJAX call to fetch volunteers based on the selected class
        $.ajax({
            type: 'POST',
            url: 'get_volunteers.php', // Replace with your PHP file to fetch volunteers
            data: { classFilter: selectedClass },
            success: function(response) {
                // Update the volunteer table with the new data
                $('.table-responsive tbody').html(response);
            }
        });
    });
});
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {
        $('#volunteerClasses').select2({
            placeholder: 'Select classes', // Placeholder text
            allowClear: true, // Adds a clear button
            // Add any additional configurations you want
        });
    });
  </script>

<script>
// Check if the success message variable is set and not empty
if (successMessage && successMessage.trim() !== '') {
    // Display the success message in a pop-up
    alert(successMessage);
}
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var checkAll = document.getElementById('checkAll');
        checkAll.addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name^="check"]');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = this.checked;
            }
        });
    });
</script>

</body>

</html>
