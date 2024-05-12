<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website Title</title>
    <style>
        /* Adjusting sidebar for mobile screens */
        @media (max-width: 768px) {
            .navbar-nav.sidebar {
                display: none; /* Hide sidebar by default on mobile */
                position: fixed;
                top: 0;
                left: 0;
                width: 250px;
                height: 100%;
                background-color: #fff;
                overflow-y: auto;
                border-right: 1px solid #ddd;
            }
            .sidebar-toggle-btn {
                display: block; /* Ensure button is visible */
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 9999; /* Ensure button appears above other content */
            }
        }
    </style>
</head>
<body>
    <button class="sidebar-toggle-btn" onclick="toggleSidebar()">Toggle Sidebar</button>
    <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center bg-gradient-primary justify-content-center" href="index.php">
            <div class="sidebar-brand-icon">
                <img src="img/logo/attnlg.jpg">
            </div>
            <div class="sidebar-brand-text mx-3">Xilin Northwest</div>
        </a>
        <hr class="sidebar-divider my-0">
        <li class="nav-item active">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            Attendance
        </div>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon"
                aria-expanded="true" aria-controls="collapseBootstrapcon">
                <i class="fa fa-calendar-alt"></i>
                <span>Manage Attendance</span>
            </a>
            <div id="collapseBootstrapcon" class="collapse" aria-labelledby="headingBootstrap"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Manage Attendance</h6>
                    <a class="collapse-item" href="takeAttendance.php" style="font-size: 13px;">Take Student Attendance</a>
                    <a class="collapse-item" href="takeVolunteerAttendance.php" style="font-size: 13px;">Take Volunteer Attendance</a>
                    <a class="collapse-item" href="viewAttendance.php" style="font-size: 13px;">View Class Attendance</a>
                    <a class="collapse-item" href="viewStudentAttendance.php" style="font-size: 13px;">View Student Attendance</a>
                    <a class="collapse-item" href="viewVolunteerAttendance.php" style="font-size: 13px;">View Volunteer Attendance</a>
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">
    </ul>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("accordionSidebar");
            if (sidebar.style.display === "none" || sidebar.style.display === "") {
                sidebar.style.display = "block";
            } else {
                sidebar.style.display = "none";
            }
        }
    </script>
</body>
</html>
