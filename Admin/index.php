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
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div id="wrapper" class="col-lg-3 col-12">
        <?php include "Includes/sidebar.php";?>
      </div>
      
      <!-- Content Wrapper -->
      <div id="content-wrapper" class="col-lg-9 col-12 d-flex flex-column">
        <div id="content">
          <!-- TopBar -->
          <?php include "Includes/topbar.php";?>
          <!-- Topbar -->

          <!-- Container Fluid-->
          <div class="container-fluid" id="container-wrapper">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800">Administrator Dashboard</h1>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
              </ol>
            </div>

            <div class="row mb-3">
              <!-- Class Card -->
              <?php 
              $query1=mysqli_query($conn,"SELECT * from classes");                       
              $class = mysqli_num_rows($query1);
              ?>
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Classes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $class;?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-chalkboard fa-2x text-primary"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Class Arm Card -->

              <!-- Teachers Card  -->
              <?php 
              $query1=mysqli_query($conn,"SELECT * from teachers");                       
              $classTeacher = mysqli_num_rows($query1);
              ?>
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Class Teachers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classTeacher;?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher fa-2x text-danger"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Students Card -->
              <?php 
              $query1=mysqli_query($conn,"SELECT * from students");                       
              $students = mysqli_num_rows($query1);
              ?>
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students;?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-users fa-2x text-info"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Volunteers Card  -->
              <?php 
              $query1=mysqli_query($conn,"SELECT * from volunteers");                       
              $termonly = mysqli_num_rows($query1);
              ?>
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Volunteers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $termonly;?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-hands-helping fa-2x text-info"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Row -->

            <!-- Std Att Card  -->
            <?php 
            $query1=mysqli_query($conn,"SELECT * from tblattendance where status = 1");                       
            $totAttendance = mysqli_num_rows($query1);
            ?>
            <div class="row mb-3">
              <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Student Attendance</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totAttendance;?></div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-warning"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!---Container Fluid-->
        </div>
      </div>
      <!-- Content Wrapper -->
    </div>
  </div>

  <!-- Footer -->
  <?php include 'includes/footer.php';?>
  <!-- Footer -->

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script
