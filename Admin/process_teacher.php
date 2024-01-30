<?php
// Start or resume a session
session_start();

include '../Includes/dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacherName = mysqli_real_escape_string($conn, $_POST['teacherName']);
    $selectedClasses = $_POST['classSelection'];

    // Insert teacher into 'teachers' table
    $insertTeacherQuery = "INSERT INTO teachers (teacher_name) VALUES ('$teacherName')";
    $result = mysqli_query($conn, $insertTeacherQuery);

    if ($result) {
        $teacherId = mysqli_insert_id($conn);

        foreach ($selectedClasses as $classId) {
            $insertTeacherClassQuery = "INSERT INTO teacher_classes (teacher_id, class_id) VALUES ($teacherId, $classId)";
            mysqli_query($conn, $insertTeacherClassQuery);
        }

        mysqli_close($conn);

        // Set a success message in a session variable
        $_SESSION['success_message'] = "Teacher created successfully!";
        
        header("Location: createClassTeacher.php");
        exit();
    } else {
        header("Location: createClassTeacher.php?error=1");
        exit();
    }
} else {
    header("Location: createClassTeacher.php");
    exit();
}
?>
