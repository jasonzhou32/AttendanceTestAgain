<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (isset($_POST['save'])) {
    $dateTaken = date("Y-m-d");
    $teacher_id = $_SESSION['userId'];

    foreach ($_POST['check'] as $class_id => $checked_students) {
        $class_id = intval($class_id);
        // Delete previous entries for the specific class and date
        $delete_query = "DELETE FROM tblattendance WHERE class_id = $class_id AND dateTimeTaken = '$dateTaken'";
        mysqli_query($conn, $delete_query);

        foreach ($checked_students as $student_id) {
            $student_id = intval($student_id);
            if (!empty($student_id)) {
                // Insert status for selected students in this class for today's date
                $insert_query = "INSERT INTO tblattendance (student_id, class_id, status, dateTimeTaken)
                                 VALUES ($student_id, $class_id, 1, '$dateTaken')
                                 ON DUPLICATE KEY UPDATE status = 1";
                mysqli_query($conn, $insert_query);
            }
        }

        // Set status to 0 for unselected students in this class for today's date
        $unselected_students_query = "INSERT INTO tblattendance (student_id, class_id, status, dateTimeTaken)
                                      SELECT sc.student_id, $class_id, 0, '$dateTaken'
                                      FROM student_classes sc
                                      WHERE sc.class_id = $class_id AND sc.student_id NOT IN (" . implode(',', array_map('intval', $checked_students)) . ")
                                      ON DUPLICATE KEY UPDATE status = 0";
        mysqli_query($conn, $unselected_students_query);
    }

    echo "Attendance taken successfully!";

    $_SESSION['success_message'] = "Attendance taken successfully!";

    header("Location: takeAttendance.php");
    exit();
}

?>
