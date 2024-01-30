
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
  $volunteerName = mysqli_real_escape_string($conn, $_POST['volunteerName']);
  $volunteerEmail = mysqli_real_escape_string($conn, $_POST['volunteerEmail']);
  $volunteerNumber = mysqli_real_escape_string($conn, $_POST['volunteerNumber']);

    $selectedClasses = $_POST['classSelection'];
    
    // Insert volunteer into 'volunteers' table
    $insertVolunteerQuery = "INSERT INTO volunteers (volunteer_name, volunteer_email, volunteer_number) VALUES ('$volunteerName', '$volunteerEmail', '$volunteerNumber')";
    $result = mysqli_query($conn, $insertVolunteerQuery);

    if ($result) {
        $volunteerId = mysqli_insert_id($conn);

        foreach ($selectedClasses as $classId) {
            $insertVolunteerClassQuery = "INSERT INTO volunteer_classes (volunteer_id, class_id) VALUES ($volunteerId, $classId)";
            mysqli_query($conn, $insertVolunteerClassQuery);
        }

        mysqli_close($conn);

        // Set a success message in a session variable
        $_SESSION['success_message'] = "Volunteer created successfully!";
        
        header("Location: createVolunteers.php");
        exit();
    } else {
        header("Location: createVolunteers.php?error=1");
        exit();
    }
}

//---------------------------------------EDIT-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

if (isset($_GET['volunteer_id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['volunteer_id'];

  $query = mysqli_query($conn, "SELECT * FROM volunteers WHERE volunteer_id ='$Id'");
  $row = mysqli_fetch_array($query);

  if (isset($_POST['update'])) {
      $volunteer_name = $_POST['volunteerName'];
      $volunteer_email = $_POST['volunteerEmail'];
      $volunteer_number = $_POST['volunteerNumber'];

      // Log the value of $_POST['volunteer_name']
      error_log('$_POST[\'volunteer_name\'] value: ' . print_r($volunteer_name, true));

      $newClasses = $_POST['classSelection']; // Assuming class IDs are in an array from the form



      
      // Update the volunteer's name
      $updateVolunteerQuery = mysqli_query($conn, "UPDATE volunteers SET volunteer_name='$volunteer_name', volunteer_email='$volunteer_email', volunteer_number='$volunteer_number' WHERE volunteer_id='$Id'");

      if ($updateVolunteerQuery) {
          // Update the volunteer's classes in volunteer_classes table

          // Delete existing class associations
          $deleteClassesQuery = mysqli_query($conn, "DELETE FROM volunteer_classes WHERE volunteer_id='$Id'");

          if ($deleteClassesQuery) {
              // Insert new class associations
              foreach ($newClasses as $class_id) {
                  $insertClassQuery = mysqli_query($conn, "INSERT INTO volunteer_classes (volunteer_id, class_id) VALUES ('$Id', '$class_id')");
                  if (!$insertClassQuery) {
                      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error updating classes!</div>";
                      break; // Exit loop if insertion fails
                  }
              }

              if ($insertClassQuery) {
                  echo "<script type='text/javascript'>window.location = 'createVolunteers.php';</script>";
              }
          } else {
              $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error deleting existing classes!</div>";
          }
      } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating volunteer name!</div>";
      }
  }
}




//--------------------------------DELETE------------------------------------------------------------------







if (isset($_GET['volunteer_id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['volunteer_id'];

  $query = mysqli_query($conn, "DELETE FROM volunteers WHERE volunteer_id='$Id'");

  if ($query) {
      if (mysqli_affected_rows($conn) > 0) {
          // Successfully deleted rows
          ?>
          <script>
              alert('Successfully deleted volunteer with ID <?php echo $Id; ?>');
              setTimeout(function() {
                  window.location.href = "createVolunteers.php";
              }, 1000); // Delay in milliseconds
          </script>
          <?php
      } else {
          // No rows deleted despite successful query execution
          ?>
          <script>
              alert('No rows deleted for volunteer ID <?php echo $Id; ?>');
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
            <h1 class="h3 mb-0 text-gray-800">Create Volunteers</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Volunteers</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Volunteers</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  





                  <form method="post">
                    <label for="volunteerName" class="form-control-label">Volunteer Name:</label>
                    <input type="text" id="volunteerName" class="form-control" name="volunteerName" value="<?php echo $row['volunteer_name'];?>"  placeholder="Name" style="width: 500px;" required><br>


                    <label for="volunteerEmail" class="form-control-label">Volunteer Email:</label>
                    <input type="text" id="volunteerEmail" class="form-control" name="volunteerEmail" value="<?php echo $row['volunteer_email'];?>"  placeholder="Email" style="width: 500px;" required><br>

                    <label for="volunteerNumber" class="form-control-label">Volunteer Phone Number:</label>
                    <input type="text" id="volunteerNumber" class="form-control" name="volunteerNumber" value="<?php echo $row['volunteer_number'];?>"  placeholder="Number" style="width: 500px;" required><br>



                    <label for="classSelection">Select Classes:</label>
                    <select id="classSelection" name="classSelection[]" multiple required class="form-control">
    <?php
    // Connect to your database
    include '../Includes/dbcon.php';

    // Fetch available classes from the 'classes' table
    $queryClasses = "SELECT class_id, class_name FROM classes";
    $resultClasses = mysqli_query($conn, $queryClasses);

    // Fetch selected classes for the current volunteer
    $volunteerId = $_GET['volunteer_id']; // Replace with your way of getting volunteer ID

    $querySelected = "SELECT class_id FROM volunteer_classes WHERE volunteer_id = $volunteerId";
    $resultSelected = mysqli_query($conn, $querySelected);

    $selectedClasses = array();

    if ($resultSelected && mysqli_num_rows($resultSelected) > 0) {
        while ($rowSelected = mysqli_fetch_assoc($resultSelected)) {
            // Store the selected class IDs in an array
            $selectedClasses[] = $rowSelected['class_id'];
        }
    }

    if ($resultClasses && mysqli_num_rows($resultClasses) > 0) {
        while ($row = mysqli_fetch_assoc($resultClasses)) {
            // Check if the class is selected for the volunteer
            $selected = in_array($row['class_id'], $selectedClasses) ? 'selected' : '';
            
            // Output options with the selected attribute if necessary
            echo "<option value='" . $row['class_id'] . "' $selected>" . $row['class_name'] . "</option>";
        }
    } else {
        echo "<option value=''>No classes available</option>";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
</select>

<br><br>

                    <!-- <input type="submit" value="Create Volunteer"> -->





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
                  <h6 class="m-0 font-weight-bold text-primary">All Volunteers</h6>
                </div>
                <div class="table-responsive p-3">






                

                  <?php
                  // Include your database connection file
                  include '../Includes/dbcon.php';

                  // Fetch volunteer data from the database
                  $query = "SELECT * FROM volunteers";
                  $result = mysqli_query($conn, $query);

                  ?>


                  



                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                      <thead class="thead-light">
                          <tr>
                              <th>Volunteer ID</th>
                              <th>Volunteer Name</th>
                              <th>Email</th>
                              <th>Phone Number</th>
                              <th>Edit</th>
                              <th>Delete</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                          $query = "SELECT * FROM volunteers"; // Fetch all volunteer records
                          $result = mysqli_query($conn, $query);

                          if ($result && mysqli_num_rows($result) > 0) {
                              while ($row = mysqli_fetch_assoc($result)) {
                                  ?>
                                  <tr>
                                      <td><?php echo $row['volunteer_id']; ?></td>
                                      <td><?php echo $row['volunteer_name']; ?></td>
                                      <td><?php echo $row['volunteer_email']; ?></td>
                                      <td><?php echo $row['volunteer_number']; ?></td>
                                      <td><a href='?action=edit&volunteer_id=<?php echo $row['volunteer_id']; ?>'><i class='fas fa-fw fa-edit'></i></a></td>
                                      <td><a href='?action=delete&volunteer_id=<?php echo $row['volunteer_id']; ?>'><i class='fas fa-fw fa-trash'></i></a></td>
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