
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
  $className = mysqli_real_escape_string($conn, $_POST['className']);
    $selectedClasses = $_POST['classSelection'];

    // Insert class into 'classes' table
    $insertClassQuery = "INSERT INTO classes (class_name) VALUES ('$className')";
    $result = mysqli_query($conn, $insertClassQuery);


    if ($result) {

        mysqli_close($conn);

        // Set a success message in a session variable
        $_SESSION['success_message'] = "Class created successfully!";
        
        header("Location: createClass.php");
        exit();
    } else {
        header("Location: createClass.php?error=1");
        exit();
    }
}

//---------------------------------------EDIT-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

if (isset($_GET['class_id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['class_id'];

  $query = mysqli_query($conn, "SELECT * FROM classes WHERE class_id ='$Id'");
  $row = mysqli_fetch_array($query);

  if (isset($_POST['update'])) {
      $class_name = $_POST['className'];

      // Log the value of $_POST['class_name']
      error_log('$_POST[\'class_name\'] value: ' . print_r($class_name, true));

      

      // Update the class's name
      $updateClassQuery = mysqli_query($conn, "UPDATE classes SET class_name='$class_name' WHERE class_id='$Id'");




      if ($updateClassQuery) {
        // Update the teacher's classes in teacher_classes table

        echo "<script type='text/javascript'>window.location = 'createClass.php';</script>";
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating teacher name!</div>";
    }
      
  }





}




//--------------------------------DELETE------------------------------------------------------------------







if (isset($_GET['class_id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['class_id'];

  $query = mysqli_query($conn, "DELETE FROM classes WHERE class_id='$Id'");

  if ($query) {
      if (mysqli_affected_rows($conn) > 0) {
          // Successfully deleted rows
          ?>
          <script>
              alert('Successfully deleted class with ID <?php echo $Id; ?>');
              setTimeout(function() {
                  window.location.href = "createClass.php";
              }, 1000); // Delay in milliseconds
          </script>
          <?php
      } else {
          // No rows deleted despite successful query execution
          ?>
          <script>
              alert('No rows deleted for class ID <?php echo $Id; ?>');
          </script>
          <?php
      }
  } else {
      // Query execution failed
      ?>
      <script>
          alert('Deletion query failed: <?php echo mysqli_error($conn); ?>');
      </script>
      <?php
  }
}


















?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">




  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />




  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
<?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">



   <script>
    function classDropdown(str) {
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
        xmlhttp.open("GET","ajaxClasss2.php?cid="+str,true);
        xmlhttp.send();
    }
}
</script>
</head>

<body id="page-top">

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
            <h1 class="h3 mb-0 text-gray-800">Create Classes</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Classes</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Classes</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  





                  <form method="post">
                    <label for="className" class="form-control-label">Class Name:</label>
                    <input type="text" id="className" class="form-control" name="className" value="<?php echo $row['class_name'];?>"  placeholder="Name" style="width: 500px;" required><br>

                    



                    <!-- <input type="submit" value="Create Class"> -->





                    <?php
                    if (isset($Id))
                    {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php
                    }         
                    ?>



                    </form>


                    
                    


                    <!-- Your HTML and form elements -->

                    <script>
                    <?php if (isset($_SESSION['success_message'])) : ?>
                        setTimeout(function() {
                            alert("<?php echo $_SESSION['success_message']; ?>");
                            <?php unset($_SESSION['success_message']); ?>
                        }, 3000);
                    <?php endif; ?>
                    </script>














                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
              <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Classes</h6>
                </div>
                <div class="table-responsive p-3">






                

                  <?php
                  // Include your database connection file
                  include '../Includes/dbcon.php';

                  // Fetch class data from the database
                  $query = "SELECT * FROM classes";
                  $result = mysqli_query($conn, $query);

                  ?>


                  



                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                      <thead class="thead-light">
                          <tr>
                              <th>Class ID</th>
                              <th>Class Name</th>
                              <th>Edit</th>
                              <th>Delete</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                          $query = "SELECT * FROM classes"; // Fetch all class records
                          $result = mysqli_query($conn, $query);

                          if ($result && mysqli_num_rows($result) > 0) {
                              while ($row = mysqli_fetch_assoc($result)) {
                                  ?>
                                  <tr>
                                      <td><?php echo $row['class_id']; ?></td>
                                      <td><?php echo $row['class_name']; ?></td>
                                      <td><a href='?action=edit&class_id=<?php echo $row['class_id']; ?>'><i class='fas fa-fw fa-edit'></i></a></td>
                                      <td><a href='?action=delete&class_id=<?php echo $row['class_id']; ?>'><i class='fas fa-fw fa-trash'></i></a></td>
                                  </tr>
                                  <?php
                              }
                          } else {
                              ?>
                              <tr>
                                  <td colspan="4">No Records Found!</td>
                              </tr>
                              <?php
                          }
                          ?>
                      </tbody>
                  </table>

                </div>
              </div>
            </div>
            </div>
          </div>
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
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

  <script>
    $(document).ready(function() {
        $('#classSelection').select2({
            placeholder: 'Select classes', // Placeholder text
            allowClear: true, // Adds a clear button
            // Add any additional configurations you want
        });
    });
  </script>

  
</body>

</html>