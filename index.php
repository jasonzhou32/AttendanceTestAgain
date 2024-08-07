<?php 
ob_start();
session_start();
include 'Includes/dbcon.php';
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
    <title>Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('img/logo/loral1.jpe00g');
            background-size: cover;
            background-repeat: no-repeat;
        }

        .container-login {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 21.5%);
            width: 50%;
            margin: 0 auto; /* Center horizontally */
            padding: 10px; /* Add some padding for better look */
            box-sizing: border-box; /* Include padding in the element's total width and height */
        }
        
        /* Media query for devices with a max width of 768px (common breakpoint for tablets and mobile devices) */
        @media (max-width: 768px) {
            .container-login {
                width: 80%; /* Increase the width to 80% on smaller screens */
            }
        }
        
        /* Media query for devices with a max width of 480px (common breakpoint for mobile devices) */
        @media (max-width: 480px) {
            .container-login {
                width: 90%; /* Increase the width to 90% on the smallest screens */
            }
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-form {
            padding: 20px;
        }

        .form-control {
            border-radius: 20px;
            padding: 10px;
        }

        .btn-login {
            border-radius: 20px;
            padding: 10px;
        }

        .alert {
            border-radius: 10px;
        }

        /* Updated footer styling */
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #fff;
            padding: 10px 0;
            text-align: center;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body class="bg-gradient-login">
    <div class="container-login">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow-sm my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="login-form">
                                <h5 align="center">STUDENT ATTENDANCE SYSTEM</h5>
                                <div class="text-center">
                                    <img src="img/logo/attnlg.jpg" style="width:100px;height:100px">
                                    <br><br>
                                    <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                                </div>
                                <form class="user" method="post" action="">
                                    <div class="form-group">
                                        <select required name="userType" class="form-control mb-3">
                                            <option value="">--Select User Role--</option>
                                            <option value="Administrator">Administrator</option>
                                            <option value="ClassTeacher">Class Teacher</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                                            <input type="checkbox" class="custom-control-input" id="customCheck">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-success btn-block btn-login" value="Login" name="login" />
                                    </div>
                                </form>

                                <?php
                                if(isset($_POST['login'])){
                                    $userType = $_POST['userType'];
                                    $username = $_POST['username'];
                                    $password = $_POST['password'];
                                    
                                    // Using prepared statement instead
                                    if($userType == "Administrator"){
                                        $query = "SELECT * FROM tbladmin WHERE emailAddress = ? AND password = ?";
                                    } elseif($userType == "ClassTeacher"){
                                        $query = "SELECT * FROM teachers WHERE teacher_email = ? AND teacher_password = ?";
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>Invalid User Role!</div>";
                                        exit;
                                    }

                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("ss", $username, $password);

                                    $stmt->execute();

                                    $result = $stmt->get_result();

                                    if ($result->num_rows > 0) {
                                        $rows = $result->fetch_assoc();

                                        if($userType == "Administrator"){
                                            $_SESSION['userId'] = $rows['Id'];
                                            $_SESSION['firstName'] = $rows['firstName'];
                                            $_SESSION['lastName'] = $rows['lastName'];
                                            $_SESSION['emailAddress'] = $rows['emailAddress'];
                                            echo "<script>window.location.href='Admin/index.php';</script>";
                                        } elseif($userType == "ClassTeacher"){
                                            $_SESSION['userId'] = $rows['teacher_id'];
                                            $_SESSION['firstName'] = $rows['teacher_name'];
                                            $_SESSION['emailAddress'] = $rows['teacher_email'];
                                            $_SESSION['teacher_number'] = $rows['teacher_number'];
                                            $_SESSION['classId'] = $rows['classId'];
                                            echo "<script>window.location.href='ClassTeacher/index.php';</script>";
                                        }
                                        exit();
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
                                    }

                                    $stmt->close();
                                }
                                ?>
                                <div class="text-center">
                                    <!-- Add social login buttons if required -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span> &copy; <script> document.write(new Date().getFullYear()); </script> Xilin Northwest Chinese School. All Rights Reserved. | Developed by Jason Zhou.
                </span>
            </div>
        </div>
    </footer>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
</body>

</html>
