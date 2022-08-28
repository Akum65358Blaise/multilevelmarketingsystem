<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
if (isset($_POST['submit'])) {
    //Establish connection to DB
    $conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

    $id = $_SESSION['name_admin'];

    $sql0 = "SELECT * FROM members WHERE Username = '$id' OR Email = '".$_SESSION['email_admin']."' ";
    $result0 = mysqli_query($conn, $sql0);
    $row0 = mysqli_fetch_assoc($result0);

    if (empty($_POST['firstname'])) {
        $firstname = $row0['FirstName'];
    }else{
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    }

    if (empty($_POST['lastname'])) {
        $lastname = $row0['LastName'];
    }else{
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    }

    if (empty($_POST['email'])) {
        $email = $row0['Email'];
    }else{
        $email = mysqli_real_escape_string($conn, $_POST['email']);
    }

    if (empty($_POST['phone'])) {
        $phone = $row0['Phone'];
    }else{
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    }

    if (empty($_POST['username'])) {
        $username = $row0['Username'];
    }else{
        $username = mysqli_real_escape_string($conn, $_POST['username']);
    }


    $sql1 = "UPDATE members SET FirstName = '$firstname', LastName = '$lastname', Username = '$username', Email = '$email', Phone = '$phone'  WHERE Username = '$id' ";
    $result1 = mysqli_query($conn, $sql1) or die(mysqli_error($conn));
    if ($result1) {
        $sql2 = "UPDATE users SET Username = '$username', Email = '$email' WHERE Username = '$id' ";
        $result2 = mysqli_query($conn, $sql2);

        $_SESSION['name_admin'] = $username;
        $_SESSION['email_admin'] = $email;

        ?>
            <script>
                window.alert('Profile details successfully updated!');
                window.history.back();
            </script>
        <?php
    }else{
    	?>
            <script>
                window.alert('Failed to update profile details, please try again!');
                window.history.back();
            </script>
        <?php
    }

}