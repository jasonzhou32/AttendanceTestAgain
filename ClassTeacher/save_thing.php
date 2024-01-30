<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (isset($_POST['save'])) {
    $dateTaken = date("Y-m-d");
    $teacher_id = $_SESSION['userId'];

    foreach ($_POST['check'] as $class_id => $checked_volunteers) {
        $class_id = intval($class_id);
        // Delete previous entries for the specific class and date
        $delete_query = "DELETE FROM tblvolunteerattendance WHERE class_id = $class_id AND dateTimeTaken = '$dateTaken'";
        mysqli_query($conn, $delete_query);

        foreach ($checked_volunteers as $volunteer_id) {
            $volunteer_id = intval($volunteer_id);
            if (!empty($volunteer_id)) {
                // Insert status for selected volunteers in this class for today's date
                $insert_query = "INSERT INTO tblvolunteerattendance (volunteer_id, class_id, status, dateTimeTaken)
                                 VALUES ($volunteer_id, $class_id, 1, '$dateTaken')
                                 ON DUPLICATE KEY UPDATE status = 1";
                mysqli_query($conn, $insert_query);
            }
        }

        // Set status to 0 for unselected volunteers in this class for today's date
        $unselected_volunteers_query = "INSERT INTO tblvolunteerattendance (volunteer_id, class_id, status, dateTimeTaken)
                                      SELECT sc.volunteer_id, $class_id, 0, '$dateTaken'
                                      FROM volunteer_classes sc
                                      WHERE sc.class_id = $class_id AND sc.volunteer_id NOT IN (" . implode(',', array_map('intval', $checked_volunteers)) . ")
                                      ON DUPLICATE KEY UPDATE status = 0";
        mysqli_query($conn, $unselected_volunteers_query);
    }

    echo "Attendance taken successfully!";

    $_SESSION['success_message'] = "Attendance taken successfully!";

    header("Location: takeVolunteerAttendance.php");
    exit();
}

?>
