<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$filename = "Attendance_list"; // File name for download
$dateTaken = date("Y-m-d");

// Fetching required data from tables for the user's associated classes
$classIds = array(); // Array to store multiple class IDs
$teacher_id = $_SESSION['userId']; // Assuming this holds the teacher's identifier

// Query to fetch associated class IDs for a teacher from teacher_classes table
$classQuery = "SELECT class_id FROM teacher_classes WHERE teacher_id = '$teacher_id'";
$classResult = mysqli_query($conn, $classQuery);

if ($classResult) {
    while ($row = mysqli_fetch_assoc($classResult)) {
        $classIds[] = $row['class_id']; // Storing multiple class IDs
    }
}

if (!empty($classIds)) {
    $classIdString = implode(',', $classIds); // Convert class IDs array to string

    $query = "SELECT a.status, a.dateTimeTaken, s.student_id, s.student_name, c.class_name, sc.class_id
              FROM tblattendance a
              INNER JOIN students s ON s.student_id = a.student_id
              INNER JOIN student_classes sc ON sc.student_id = s.student_id
              INNER JOIN classes c ON c.class_id = sc.class_id
              WHERE a.dateTimeTaken = '$dateTaken' AND sc.class_id IN ($classIdString)";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $filename . "-report.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '
        <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Other Name</th>
                <th>Admission No</th>
                <th>Class</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>';

        $cnt = 1;
        while ($row = mysqli_fetch_array($result)) {
            $status = ($row['status'] == '1') ? "Present" : "Absent";
            $studentID = $row['student_id'];
            $studentName = $row['student_name'];
            $className = $row['class_name'];
            $dateTimeTaken = $row['dateTimeTaken'];

            echo '
            <tr>  
                <td>' . $cnt . '</td> 
                <td>' . $studentName . '</td> 
                <td></td> 
                <td></td> 
                <td>' . $studentID . '</td> 
                <td>' . $className . '</td> 
                <td>' . $status . '</td> 
                <td>' . $dateTimeTaken . '</td>
            </tr>';

            $cnt++;
        }

        echo '</table>';
    } else {
        echo "No records found!";
    }
} else {
    echo "No classes associated with the user!";
}
?>
