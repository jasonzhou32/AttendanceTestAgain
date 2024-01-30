<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$teacherId = $_SESSION['userId'];

// Initialize an empty variable to store the HTML output
$htmlOutput = '';

if (isset($_POST['classFilter']) && !empty($_POST['classFilter'])) {
    $className = $_POST['classFilter'];
    $query = "SELECT s.student_id, s.student_name, c.class_id, c.class_name
              FROM students s
              INNER JOIN student_classes sc ON s.student_id = sc.student_id
              INNER JOIN classes c ON c.class_id = sc.class_id
              INNER JOIN teacher_classes tc ON c.class_id = tc.class_id
              WHERE tc.teacher_id = '$teacherId' AND c.class_name = '$className'";
} else {
    $query = "SELECT s.student_id, s.student_name, c.class_id, c.class_name
              FROM students s
              INNER JOIN student_classes sc ON s.student_id = sc.student_id
              INNER JOIN classes c ON c.class_id = sc.class_id
              INNER JOIN teacher_classes tc ON c.class_id = tc.class_id
              WHERE tc.teacher_id = '$teacherId'";
}

$rs = $conn->query($query);
if ($rs && $rs->num_rows > 0) {
    $sn = 0;
    while ($rows = $rs->fetch_assoc()) {
        $sn++;
        $studentId = $rows['student_id'];
        $classId = $rows['class_id'];
        $studentName = $rows['student_name'];

        $htmlOutput .= "
            <tr>
                <td>".$sn."</td>
                <td>".$studentId."</td>
                <td>".$studentName."</td>
                <td>".$classId."</td>
                <td>
                    <!-- Create checkboxes with class_id as array index and student_id as value -->
                    <input type='checkbox' name='check[$classId][]' value='$studentId' class='form-control'>
                </td>
            </tr>";
    }
    // Return the HTML output to the AJAX call
    echo $htmlOutput;
} else {
    $htmlOutput .= "<tr><td colspan='5'>No Records Found!</td></tr>";
    // Return the HTML output to the AJAX call
    echo $htmlOutput;
}

?>
