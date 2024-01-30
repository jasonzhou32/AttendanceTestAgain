<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
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
  <link href="css/ruang-admin.min.css" rel="stylesheet">

  <script>
    function typeDropDown(str) {
      if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
      } else { 
        if (window.XMLHttpRequest) {
          xmlhttp = new XMLHttpRequest();
        } else {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
          }
        };
        xmlhttp.open("GET","ajaxCallTypes.php?tid="+str,true);
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
            <h1 class="h3 mb-0 text-gray-800">View Volunteer Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Volunteer Attendance</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">View Volunteer Attendance</h6>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                    <div class="col-xl-6">
    <label class="form-control-label">Select Volunteer<span class="text-danger ml-2">*</span></label>
    <?php
    $query = "SELECT DISTINCT volunteer_id FROM tblvolunteerattendance"; // Fetch distinct volunteer IDs from tblvolunteerattendance
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo '<select required name="volunteerID" class="form-control mb-3">';
        echo '<option value="">--Select Volunteer--</option>';

        while ($row = $result->fetch_assoc()) {
            $volunteerID = $row['volunteer_id'];

            // Fetch the volunteer's name from your 'volunteers' table based on the volunteer ID
            $volunteerNameQuery = "SELECT volunteer_name FROM volunteers WHERE volunteer_id = '$volunteerID'";
            $volunteerNameResult = $conn->query($volunteerNameQuery);

            if ($volunteerNameResult->num_rows > 0) {
                $volunteerData = $volunteerNameResult->fetch_assoc();
                $volunteerName = $volunteerData['volunteer_name'];
                echo '<option value="' . $volunteerID . '">' . $volunteerName . '</option>';
            }
        }
        echo '</select>';
    } else {
        echo 'No volunteers found in attendance records.';
    }
    ?>
</div>

                      <div class="col-xl-6">
                        <label class="form-control-label">Type<span class="text-danger ml-2">*</span></label>
                        <select required name="type" onchange="typeDropDown(this.value)" class="form-control mb-3">
                          <option value="">--Select--</option>
                          <option value="1">All</option>
                          <option value="2">By Single Date</option>
                          <option value="3">By Date Range</option>
                        </select>
                      </div>
                    </div>
                    <?php
                    echo "<div id='txtHint'></div>";
                    ?>
                    <button type="submit" name="view" class="btn btn-primary">View Attendance</button>
                  </form>
                </div>
              </div>

              <!-- Attendance Table -->
<div class="row">
  <div class="col-lg-12">
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Volunteer Attendance</h6>
      </div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush table-hover" id="dataTableHover">
          <thead class="thead-light">
            <tr>
              <th>#</th>
              <th>Volunteer ID</th>
              <th>Class ID</th>
              <th>Status</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
          <?php
if (isset($_POST['view'])) {
    $volunteerID = $_POST['volunteerID']; // Changed variable name to match the dropdown's name
    $type = $_POST['type'];

    // Define the SQL query based on the selected type
    if ($type == "1") { // All Attendance
        $query = "SELECT * FROM tblvolunteerattendance WHERE volunteer_id = '$volunteerID'";
    } elseif ($type == "2") { // Single Date Attendance
        $singleDate = $_POST['singleDate'];
        $query = "SELECT * FROM tblvolunteerattendance WHERE volunteer_id = '$volunteerID' AND dateTimeTaken = '$singleDate'";
    } elseif ($type == "3") { // Date Range Attendance
        $fromDate = $_POST['fromDate'];
        $toDate = $_POST['toDate'];
        $query = "SELECT * FROM tblvolunteerattendance WHERE volunteer_id = '$volunteerID' AND dateTimeTaken BETWEEN '$fromDate' AND '$toDate'";
    }

    $rs = $conn->query($query);
    $sn = 0;
    if ($rs->num_rows > 0) {
        while ($rows = $rs->fetch_assoc()) {
            $sn++;
            $status = ($rows['status'] == '1') ? "Present" : "Absent";

            // Fetch volunteer name based on volunteer_id
            $volunteerNameQuery = "SELECT volunteer_name FROM volunteers WHERE volunteer_id = '$volunteerID'";
            $volunteerNameResult = $conn->query($volunteerNameQuery);

            if ($volunteerNameResult->num_rows > 0) {
                $volunteerData = $volunteerNameResult->fetch_assoc();
                $volunteerName = $volunteerData['volunteer_name'];

                echo "
                    <tr>
                        <td>" . $sn . "</td>
                        <td>" . $volunteerName . "</td>
                        <td>" . $rows['class_id'] . "</td>
                        <td>" . $status . "</td>
                        <td>" . $rows['dateTimeTaken'] . "</td>
                    </tr>";
            } else {
                echo "<tr><td colspan='5'>No Records Found!</td></tr>";
            }
        }
    } else {
        echo "<tr><td colspan='5'>No Records Found!</td></tr>";
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


              <!-- ... (existing HTML content) ... -->
            </div>
          </div>
          <!--Row-->

          <!-- ... (existing HTML content) ... -->
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

  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>
