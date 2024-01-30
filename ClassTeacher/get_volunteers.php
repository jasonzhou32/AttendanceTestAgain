<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$teacherId = $_SESSION['userId'];

// Initialize an empty variable to store the HTML output
$htmlOutput = '';

if (isset($_POST['classFilter']) && !empty($_POST['classFilter'])) {
    $className = $_POST['classFilter'];
    $query = "SELECT s.volunteer_id, s.volunteer_name, c.class_id, c.class_name
              FROM volunteers s
              INNER JOIN volunteer_classes sc ON s.volunteer_id = sc.volunteer_id
              INNER JOIN classes c ON c.class_id = sc.class_id
              INNER JOIN teacher_classes tc ON c.class_id = tc.class_id
              WHERE tc.teacher_id = '$teacherId' AND c.class_name = '$className'";
} else {
    $query = "SELECT s.volunteer_id, s.volunteer_name, c.class_id, c.class_name
              FROM volunteers s
              INNER JOIN volunteer_classes sc ON s.volunteer_id = sc.volunteer_id
              INNER JOIN classes c ON c.class_id = sc.class_id
              INNER JOIN teacher_classes tc ON c.class_id = tc.class_id
              WHERE tc.teacher_id = '$teacherId'";
}

$rs = $conn->query($query);
if ($rs && $rs->num_rows > 0) {
    $sn = 0;
    while ($rows = $rs->fetch_assoc()) {
        $sn++;
        $volunteerId = $rows['volunteer_id'];
        $classId = $rows['class_id'];
        $volunteerName = $rows['volunteer_name'];

        $htmlOutput .= "
            <tr>
                <td>".$sn."</td>
                <td>".$volunteerId."</td>
                <td>".$volunteerName."</td>
                <td>".$classId."</td>
                <td>
                    <!-- Create checkboxes with class_id as array index and volunteer_id as value -->
                    <input type='checkbox' name='check[$classId][]' value='$volunteerId' class='form-control'>
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
