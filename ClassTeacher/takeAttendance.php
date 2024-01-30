
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


if(isset($_POST['save'])){
  
    
  // Get the teacher's ID based on their login information (you need to implement this part)
$teacher_id = $_SESSION['userId']; // Replace this with the actual logged-in teacher's ID

// Get the current date
$dateTaken = date("Y-m-d");

// Check if attendance has been taken for today for any class associated with the teacher
$query_attendance = "SELECT COUNT(*) AS attendance_count
                    FROM tblattendance
                    WHERE teacher_id = $teacher_id
                    AND dateTimeTaken = '$dateTaken'";

$result_attendance = mysqli_query($conn, $query_attendance);

var_dump($result_attendance); // Shows detailed information about the variable

if ($result_attendance) {
    $row_attendance = mysqli_fetch_assoc($result_attendance);
    $attendance_count = $row_attendance['attendance_count'];

    if ($attendance_count == 0) {
        // Insert the students' attendance records for the teacher's classes if not already taken
        $insert_query = "INSERT INTO tblattendance (student_id, class_id, status, dateTimeTaken)
                         SELECT sc.student_id, sc.class_id, 0, '$dateTaken'
                         FROM student_classes sc
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
    function classArmDropdown(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
        xmlhttp.send();
    }
}
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
            <h1 class="h3 mb-0 text-gray-800">Take Attendance (Today's Date : <?php echo $todaysDate = date("m-d-Y");?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Student in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->


              <!-- Input Group -->
              <form method="post" action="save_attendance.php">
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Filter Students by Class</h6>
                    <div>
                      <label for="classFilter">Select Class:</label>
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
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Class ID</th>
                                <th>Check</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (isset($_POST['classFilter']) && !empty($_POST['classFilter'])) {
                              $className = $_POST['classFilter'];
                              
                          
                              $query = "SELECT s.student_id, s.student_name, c.class_id, c.class_name
                                        FROM students s
                                        INNER JOIN student_classes sc ON s.student_id = sc.student_id
                                        INNER JOIN classes c ON c.class_id = sc.class_id
                                        INNER JOIN teacher_classes tc ON c.class_id = tc.class_id
                                        WHERE tc.teacher_id = '$teacherId' AND c.class_name = '$className'";
                          } else {

                              
                              $query = "SELECT s.student_id, s.student_name, c.class_id, c.class_name
                                        FROM students s
                                        INNER JOIN student_classes sc ON s.student_id = sc.student_id
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
                                $studentId = $rows['student_id'];
                                $classId = $rows['class_id'];
                                $studentName = $rows['student_name'];
                            
                                echo "
                                    <tr>
                                        <td>".$sn."</td>
                                        <td>".$studentId."</td>
                                        <td>".$studentName."</td>
                                        <td>".$classId."</td>
                                        <td>
                                            <!-- Create checkboxes with class_id as array index and student_id as value -->
                                            <input type='checkbox' name='check[$classId][]' value='$studentId' class='form-control'>
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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        // AJAX call to fetch students based on the selected class
        $.ajax({
            type: 'POST',
            url: 'get_students.php', // Replace with your PHP file to fetch students
            data: { classFilter: selectedClass },
            success: function(response) {
                // Update the student table with the new data
                $('.table-responsive tbody').html(response);
            }
        });
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




</body>

</html>