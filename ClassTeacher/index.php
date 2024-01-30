
<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';


    $query = "SELECT classes.class_name 
    FROM teacher_classes
    INNER JOIN classes ON classes.class_id = teacher_classes.class_id
    
    Where teacher_classes.teacher_id = '$_SESSION[userId]'";

    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rrw = $rs->fetch_assoc();


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
            <h1 class="h3 mb-0 text-gray-800">Class Teacher Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">






 <!-- Earnings (Monthly) Card Example -->
 <?php 
// Assuming $conn is your database connection

// Get the teacher's ID based on their login information (you need to implement this part)
$teacher_id = $_SESSION['userId']; // Replace this with the actual logged-in teacher's ID

// Modify the SQL query to count distinct classes taught by the specific teacher
$query_classes = "SELECT COUNT(DISTINCT class_id) AS class_count
                  FROM teacher_classes
                  WHERE teacher_id = $teacher_id";

$result_classes = mysqli_query($conn, $query_classes);

if ($result_classes) {
    $row_classes = mysqli_fetch_assoc($result_classes);
    $classes_taught_by_teacher = $row_classes['class_count'];
} else {
    // Handle the query error if necessary
    $classes_taught_by_teacher = 0;
}
?>

<div class="col-xl-3 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-uppercase mb-1">Classes Taught by Teacher</div>
          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $classes_taught_by_teacher;?></div>
          <div class="mt-2 mb-0 text-muted text-xs">
            <!-- Additional details if needed -->
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-chalkboard-teacher fa-2x text-info"></i>
        </div>
      </div>
    </div>
  </div>
</div>











          <!-- Students Card -->
          <?php 
// Assuming $conn is your database connection

// Get the teacher's ID based on their login information (you need to implement this part)
$teacher_id = $_SESSION['userId']; // Replace this with the actual logged-in teacher's ID


// Modify the SQL query to count distinct students in classes taught by the specific teacher
$query = "SELECT COUNT(DISTINCT student_classes.student_id) AS student_count
          FROM student_classes
          INNER JOIN teacher_classes ON student_classes.class_id = teacher_classes.class_id
          WHERE teacher_classes.teacher_id = $teacher_id";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $students_in_teacher_classes = $row['student_count'];
} else {
    // Handle the query error if necessary
    $students_in_teacher_classes = 0;
}
?>

<div class="col-xl-3 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-uppercase mb-1">Students in Teacher's Classes</div>
          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students_in_teacher_classes;?></div>
          <div class="mt-2 mb-0 text-muted text-xs">
            <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
            <span>Since last month</span> -->
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-users fa-2x text-info"></i>
        </div>
      </div>
    </div>
  </div>
</div>






<!-- Students Card -->
<?php 
// Assuming $conn is your database connection

// Get the teacher's ID based on their login information (you need to implement this part)
$teacher_id = $_SESSION['userId']; // Replace this with the actual logged-in teacher's ID


// Modify the SQL query to count distinct volunteers in classes taught by the specific teacher
$query = "SELECT COUNT(DISTINCT volunteer_classes.volunteer_id) AS volunteer_count
          FROM volunteer_classes
          INNER JOIN teacher_classes ON volunteer_classes.class_id = teacher_classes.class_id
          WHERE teacher_classes.teacher_id = $teacher_id";

$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $volunteers_in_teacher_classes = $row['volunteer_count'];
} else {
    // Handle the query error if necessary
    $volunteers_in_teacher_classes = 0;
}
?>

<div class="col-xl-3 col-md-6 mb-4">
  <div class="card h-100">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-uppercase mb-1">Volunteers in Teacher's Classes</div>
          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $volunteers_in_teacher_classes;?></div>
          <div class="mt-2 mb-0 text-muted text-xs">
            <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
            <span>Since last month</span> -->
          </div>
        </div>
        <div class="col-auto">
          
          <i class="fas fa-hands-helping fa-2x text-info"></i>
        </div>
      </div>
    </div>
  </div>
</div>














           


            <!-- Earnings (Annual) Card Example -->
            
            
            <!-- Pending Requests Card Example -->
            <?php 
$query1=mysqli_query($conn,"SELECT * from tblattendance where status = 1");                       
$totAttendance = mysqli_num_rows($query1);
?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Student Attendance</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totAttendance;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                        <span>Since yesterday</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>






            <!-- Pending Requests Card Example -->
            <?php 
$query1=mysqli_query($conn,"SELECT * from tblvolunteerattendance where status = 1");                       
$totAttendance = mysqli_num_rows($query1);
?>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Volunteer Attendance</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totAttendance;?></div>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                        <span>Since yesterday</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-warning"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          
          <!--Row-->

          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>Do you like this template ? you can download from <a href="https://github.com/indrijunanda/RuangAdmin"
                  class="btn btn-primary btn-sm" target="_blank"><i class="fab fa-fw fa-github"></i>&nbsp;GitHub</a></p>
            </div>
          </div> -->

        </div>
        <!---Container Fluid-->
      
  </div>
  </div>
      <!-- Footer -->
      <?php include 'includes/footer.php';?>
      <!-- Footer -->
    </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>  
</body>

</html>