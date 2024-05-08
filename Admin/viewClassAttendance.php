<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';

// Fetch all classes for the select dropdown
$classesQuery = "SELECT class_id, class_name FROM classes";
$classesResult = $conn->query($classesQuery);
if (!$classesResult) {
    $error_message = "Error fetching classes: " . $conn->error;
    error_log($error_message);
}

$classes = [];
while ($row = $classesResult->fetch_assoc()) {
    $classes[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <link href="css/ruang-admin.min.css" rel="stylesheet">

  <style>
    .select2-container .select2-selection--single {
        height: 43px; /* Adjust the height as needed */
        padding-top: 7.5px; /* Adjust the padding top to vertically center the text */
        line-height: normal; /* Reset line height */
    }
</style>
</head>
<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Class Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Class Attendance</li>
            </ol>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Class Attendance</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Select Date<span class="text-danger ml-2">*</span></label>
                            <input type="date" class="form-control" name="dateTaken" required>
                        </div>
                        <div class="col-xl-6">
    <label class="form-control-label d-block">Select Class<span class="text-danger ml-2">*</span></label>
    <div>
        <select id="classSelection" name="classId" required class="form-control select2" style="width: 100%;">
            <option value="">Select Class</option>
            <?php foreach ($classes as $class) {
                echo "<option value=\"{$class['class_id']}\">{$class['class_name']}</option>";
            } ?>
        </select>
    </div>
</div>



                    </div>
                    <button type="submit" name="view" class="btn btn-primary">View Attendance</button>
                  </form>
                </div>
              </div>

              <!-- Attendance Table -->
              <div class="row">
                <div class="col-lg-12">
                  <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                      <h6 class="m-0 font-weight-bold text-primary">Class Attendance</h6>
                    </div>
                    <div class="table-responsive p-3">
                      <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                        <thead class="thead-light">
                          <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Class Name</th>
                            <th>Status</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          if(isset($_POST['view'])) {
                            $dateTaken = $_POST['dateTaken'];
                            $classId = $_POST['classId'];
                            $query = "SELECT a.*, s.student_name, c.class_name 
                                      FROM tblattendance a 
                                      INNER JOIN students s ON a.student_id = s.student_id 
                                      INNER JOIN classes c ON a.class_id = c.class_id 
                                      WHERE a.dateTimeTaken = '$dateTaken' AND c.class_id = '$classId'";
                            $rs = $conn->query($query);
                            if (!$rs) {
                                $error_message = "Error executing SQL query: " . $conn->error;
                                error_log($error_message);
                            } else {
                                $sn = 0;
                                if($rs->num_rows > 0) {
                                    while ($rows = $rs->fetch_assoc()) {
                                        $sn++;
                                        $status = ($rows['status'] == '1') ? "Present" : "Absent";
                                        echo "
                                            <tr>
                                                <td>".$sn."</td>
                                                <td>".$rows['student_name']."</td>
                                                <td>".$rows['class_name']."</td>
                                                <td>".$status."</td>
                                                <td>".$rows['dateTimeTaken']."</td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>No Records Found!</td></tr>";
                                }
                            }
                          }
                          ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Attendance Table -->
            </div>
          </div>
          <!--Row-->
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include "Includes/footer.php";?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  
  <script src="js/ruang-admin.min.js"></script>
  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

  <script>
    $(document).ready(function () {
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
      // Initialize Select2 with search enabled
      $('.select2').select2({
        minimumResultsForSearch: Infinity
      });
    });
  </script>

<script>
    $(document).ready(function() {
        $('#classSelection').select2({
            placeholder: 'Select class', // Placeholder text
            allowClear: true, // Adds a clear button
            // Add any additional configurations you want
        });
    });
  </script>
</body>
</html>
