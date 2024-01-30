<?php
// Start or resume a session
session_start();

include '../Includes/dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
} else {
    header("Location: createStudents.php");
    exit();
}
?>
