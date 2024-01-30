
<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

//------------------------SAVE--------------------------------------------------

if(isset($_POST['save'])){
    
  $studentName = mysqli_real_escape_string($conn, $_POST['studentName']);
    $selectedClasses = $_POST['classSelection'];

    // Insert student into 'students' table
    $insertStudentQuery = "INSERT INTO students (student_name) VALUES ('$studentName')";
    $result = mysqli_query($conn, $insertStudentQuery);

    if ($result) {
        $studentId = mysqli_insert_id($conn);

        foreach ($selectedClasses as $classId) {
            $insertStudentClassQuery = "INSERT INTO student_classes (student_id, class_id) VALUES ($studentId, $classId)";
            mysqli_query($conn, $insertStudentClassQuery);
        }

        mysqli_close($conn);

        // Set a success message in a session variable
        $_SESSION['success_message'] = "Student created successfully!";
        
        header("Location: createStudents.php");
        exit();
    } else {
        header("Location: createStudents.php?error=1");
        exit();
    }
}

//---------------------------------------EDIT-------------------------------------------------------------






//--------------------EDIT------------------------------------------------------------

if (isset($_GET['student_id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
  $Id = $_GET['student_id'];

  $query = mysqli_query($conn, "SELECT * FROM students WHERE student_id ='$Id'");
  $row = mysqli_fetch_array($query);

  if (isset($_POST['update'])) {
      $student_name = $_POST['studentName'];

      // Log the value of $_POST['student_name']
      error_log('$_POST[\'student_name\'] value: ' . print_r($student_name, true));

      $newClasses = $_POST['classSelection']; // Assuming class IDs are in an array from the form

      // Update the student's name
      $updateStudentQuery = mysqli_query($conn, "UPDATE students SET student_name='$student_name' WHERE student_id='$Id'");

      if ($updateStudentQuery) {
          // Update the student's classes in student_classes table

          // Delete existing class associations
          $deleteClassesQuery = mysqli_query($conn, "DELETE FROM student_classes WHERE student_id='$Id'");

          if ($deleteClassesQuery) {
              // Insert new class associations
              foreach ($newClasses as $class_id) {
                  $insertClassQuery = mysqli_query($conn, "INSERT INTO student_classes (student_id, class_id) VALUES ('$Id', '$class_id')");
                  if (!$insertClassQuery) {
                      $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error updating classes!</div>";
                      break; // Exit loop if insertion fails
                  }
              }

              if ($insertClassQuery) {
                  echo "<script type='text/javascript'>window.location = 'createStudents.php';</script>";
              }
          } else {
              $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error deleting existing classes!</div>";
          }
      } else {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating student name!</div>";
      }
  }
}




//--------------------------------DELETE------------------------------------------------------------------







if (isset($_GET['student_id']) && isset($_GET['action']) && $_GET['action'] == "delete") {
  $Id = $_GET['student_id'];

  $query = mysqli_query($conn, "DELETE FROM students WHERE student_id='$Id'");

  if ($query) {
      if (mysqli_affected_rows($conn) > 0) {
          // Successfully deleted rows
          ?>
          <script>
              alert('Successfully deleted student with ID <?php echo $Id; ?>');
              setTimeout(function() {
                  window.location.href = "createStudents.php";
              }, 1000); // Delay in milliseconds
          </script>
          <?php
      } else {
          // No rows deleted despite successful query execution
          ?>
          <script>
              alert('No rows deleted for student ID <?php echo $Id; ?>');
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
            <h1 class="h3 mb-0 text-gray-800">Create Students</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Students</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Students</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  





                  <form method="post">
                    <label for="studentName" class="form-control-label">Student Name:</label>
                    <input type="text" id="studentName" class="form-control" name="studentName" value="<?php echo $row['student_name'];?>"  placeholder="Name" style="width: 500px;" required><br>

                    <label for="classSelection">Select Classes:</label>
                    <select id="classSelection" name="classSelection[]" multiple required class="form-control">
    <?php
    // Connect to your database
    include '../Includes/dbcon.php';

    // Fetch available classes from the 'classes' table
    $queryClasses = "SELECT class_id, class_name FROM classes";
    $resultClasses = mysqli_query($conn, $queryClasses);

    // Fetch selected classes for the current student
    $studentId = $_GET['student_id']; // Replace with your way of getting student ID

    $querySelected = "SELECT class_id FROM student_classes WHERE student_id = $studentId";
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
            // Check if the class is selected for the student
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

                    <!-- <input type="submit" value="Create Student"> -->





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


                    
                    <script>
                    $(document).ready(function() {
                        $('#createStudentForm').submit(function(event) {
                            event.preventDefault(); // Prevent the default form submission

                            // Serialize form data
                            var formData = $(this).serialize();

                            // Send form data using AJAX
                            $.ajax({
                                type: 'POST',
                                url: 'process_student.php', // URL to process form data
                                data: formData,
                                success: function(response) {
                                    // Handle success if needed
                                    console.log(response);
                                    // You can perform additional actions or display messages on success
                                },
                                error: function(xhr, status, error) {
                                    // Handle errors if needed
                                    console.error(error);
                                    // You can display an error message to the user
                                }
                            });
                        });
                    });
                    </script>


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
                  <h6 class="m-0 font-weight-bold text-primary">All Students</h6>
                </div>
                <div class="table-responsive p-3">






                

                  <?php
                  // Include your database connection file
                  include '../Includes/dbcon.php';

                  // Fetch student data from the database
                  $query = "SELECT * FROM students";
                  $result = mysqli_query($conn, $query);

                  ?>


                  



                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                      <thead class="thead-light">
                          <tr>
                              <th>Student ID</th>
                              <th>Student Name</th>
                              <th>Edit</th>
                              <th>Delete</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                          $query = "SELECT * FROM students"; // Fetch all student records
                          $result = mysqli_query($conn, $query);

                          if ($result && mysqli_num_rows($result) > 0) {
                              while ($row = mysqli_fetch_assoc($result)) {
                                  ?>
                                  <tr>
                                      <td><?php echo $row['student_id']; ?></td>
                                      <td><?php echo $row['student_name']; ?></td>
                                      <td><a href='?action=edit&student_id=<?php echo $row['student_id']; ?>'><i class='fas fa-fw fa-edit'></i></a></td>
                                      <td><a href='?action=delete&student_id=<?php echo $row['student_id']; ?>'><i class='fas fa-fw fa-trash'></i></a></td>
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