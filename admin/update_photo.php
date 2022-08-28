<?php
session_start();
if (!$_SESSION['name_admin']) {
    header('location: /SecurePrimeInvestment/index.php');
}
if (isset($_POST['submit'])) {
    //Establish connection to DB
    $conn = mysqli_connect('localhost', 'root', '', 'secureprimeinvestment');

    $id = $_SESSION['name_admin'];

    $fileInfo = PATHINFO($_FILES["image"]["name"]);
    
    if ($fileInfo['extension'] == "jpg" OR $fileInfo['extension'] == "png" OR $fileInfo['extension'] == "jpeg") {
        $newFilename = $id . "_" . date("Y_m_d H-i-s") . "." . $fileInfo['extension'];
        if (move_uploaded_file($_FILES["image"]["tmp_name"], "profiles/" . $newFilename)) {
            $location = "profiles/" . $newFilename;

            $sql0 = "SELECT * FROM users WHERE Username = '$id' ";
            $result0 = mysqli_query($conn, $sql0);
            $row0 = mysqli_fetch_assoc($result0);
            unlink($row0['Profile']);

            $sql = "UPDATE users SET Profile = '$location' WHERE Username = '$id'";
            $result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
            if ($result) {
                $_SESSION['profile'] = $location;
                if (empty($_SESSION['profile'])) {
                    $picture = "profiles/default.jpg";
                }else{
                    $picture = $_SESSION['profile'];
                }
                ?>
            		<script>
                		window.alert('Profile photo successfully updated!');
                		window.history.back();
            		</script>
        		<?php
            }else{
            	?>
            		<script>
                		window.alert('Failed to update profile photo, please try again!');
                		window.history.back();
            		</script>
        		<?php
            }
        }else{
        	?>
            	<script>
                	window.alert('Failed to upload profile photo, please try again!');
                	window.history.back();
            	</script>
        	<?php
        }
    }else{
    	?>
            <script>
                window.alert('Photo not uploaded. Please upload JPG or PNG files only!');
                window.history.back();
            </script>
        <?php
    }

}