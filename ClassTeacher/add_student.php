<?php
// Include database connection and session handling
include '../Includes/dbcon.php';
include '../Includes/session.php';

if(isset($_POST['addStudent'])){
    // Retrieve student name and selected classes from the form
    $studentName = mysqli_real_escape_string($conn, $_POST['studentName']);
    $selectedClasses = $_POST['studentClasses'];

    // Insert the new student into the students table
    $insertStudentQuery = "INSERT INTO students (student_name) VALUES ('$studentName')";
    mysqli_query($conn, $insertStudentQuery);
    $newStudentId = mysqli_insert_id($conn); // Get the auto-generated student ID

    // Insert the student-class associations into the student_classes table
    foreach ($selectedClasses as $classId) {
        $insertStudentClassQuery = "INSERT INTO student_classes (student_id, class_id) VALUES ('$newStudentId', '$classId')";
        mysqli_query($conn, $insertStudentClassQuery);
    }


    echo "Student added successfully!";
    $_SESSION['success_message'] = "Student added successfully!";

    header("Location: takeAttendance.php");
    exit();
}
?>
