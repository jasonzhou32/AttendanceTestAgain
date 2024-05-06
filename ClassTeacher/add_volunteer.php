<?php
// Include database connection and session handling
include '../Includes/dbcon.php';
include '../Includes/session.php';

if(isset($_POST['addVolunteer'])){
    // Retrieve volunteer name and selected classes from the form
    $volunteerName = mysqli_real_escape_string($conn, $_POST['volunteerName']);
    $volunteerEmail = mysqli_real_escape_string($conn, $_POST['volunteerEmail']);
    $volunteerNumber = mysqli_real_escape_string($conn, $_POST['volunteerNumber']);
    $selectedClasses = $_POST['volunteerClasses'];

    // Insert the new volunteer into the volunteers table
    $insertVolunteerQuery = "INSERT INTO volunteers (volunteer_name, volunteer_email, volunteer_number)
                     VALUES ('$volunteerName', '$volunteerEmail', '$volunteerNumber')";
    mysqli_query($conn, $insertVolunteerQuery);
    $newVolunteerId = mysqli_insert_id($conn); // Get the auto-generated volunteer ID

    // Insert the volunteer-class associations into the volunteer_classes table
    foreach ($selectedClasses as $classId) {
        $insertVolunteerClassQuery = "INSERT INTO volunteer_classes (volunteer_id, class_id) VALUES ('$newVolunteerId', '$classId')";
        mysqli_query($conn, $insertVolunteerClassQuery);
    }

    // Redirect back to the attendance page with a success message
    echo "Volunteer added successfully!";
    $_SESSION['success_message'] = "Volunteer added successfully!";
    header("Location: takeVolunteerAttendance.php");
    exit();
}
?>
