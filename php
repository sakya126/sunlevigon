//Date function of PHP
<?php echo date('d F Y',strtotime($event_date)); ?>
//Output - 6 April 2019 


//Multiple table delete 
<?php

	require_once('../inc/header.php'); 

	if(isset($_GET['id'])){

		$id = $_GET['id'];

		$delete_shipment = mysqli_query($conn, "DELETE bill_to, booking_number, parcel_info, receiver_location, sender_location, services_payment_info, shipment_date_time, shipment_info, shipment_type FROM bill_to, booking_number, parcel_info, receiver_location, sender_location, services_payment_info, shipment_date_time, shipment_info, shipment_type WHERE booking_number.bid = '$id' AND booking_number.bid = bill_to.bid AND booking_number.bid = parcel_info.bid AND  booking_number.bid = receiver_location.bid AND booking_number.bid = sender_location.bid AND booking_number.bid = services_payment_info.bid AND booking_number.bid = shipment_date_time.bid AND booking_number.bid = shipment_info.bid AND booking_number.bid = shipment_type.bid ") or die(mysqli_error($conn));

		if($delete_shipment){

			header('Location: undelivered_shipment.php');
		}
	}

?>

//Form Value with Image insert to database
<form method="POST" action="image_upload.php" enctype="multipart/form-data">
                            <div class="messenger-add">
                                
                                <label>Name</label>     
                                <input type="text" name="messenger_name">
                                <label>Code</label>     
                                <input type="text" name="messenger_code">
                                <?php if($errorCode){ ?>
                                <span><?php echo $errorCode; ?></span>
                                <?php } ?>
                                <label>Branch</label>     
                                <input type="text" name="messenger_branch">
                                <label>Country</label>
                                <select name="messenger_country">
                                    <option value="">--SELECT--</option>
                                        <?php 
                                            $query = mysqli_query($conn, "SELECT * FROM country") or die(mysqli_error($conn)); 
                                                if(mysqli_num_rows($query)>0){
                                                    while ($row = mysqli_fetch_assoc($query)){
                                        ?>

                                        <option value="<?php echo $row['country_name'];?>"><?php echo $row['country_name'];?></option>

                                        <?php           
                                                }
                                            }
                                        ?>    
                                </select>
                                <label>City</label>
                                <input type="text" name="messenger_city">
                                <label>Mobile</label>
                                <input type="number" name="messenger_mobile" maxlength="10" min="1">
                                <label>Email</label>
                                <input type="email" name="messenger_email">
                                <label>Password</label>
                                <input type="password" name="messenger_password">
                                <label>Vehicle Number</label>
                                <input type="text" name="messenger_vehicle">
                                <label>Image</label>
                                <input type='file' onchange="readURL(this);" class="file-styled-primary" name="fileToUpload" id="fileToUpload" />

                            </div>
                            <div class="button-shipment">
                                <div class="row">
                                    <div class="container">
                                        <div class="col-md-12 text-center">
                                            <input type="submit" name="add_messenger" class="btn btn-default btn-add-shipment" value="Add Messenger">
                                            <input type="submit" name="cancel" class="btn btn-default btn-cancel-shipment" value="Cancel">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    </form>

//image_upload.php
<?php require_once('../inc/header.php'); ?>

<?php
    if(isset($_POST['add_messenger'])){

            $messenger_name     = mysqli_real_escape_string($conn, $_POST['messenger_name']);
            $messenger_code     = mysqli_real_escape_string($conn, $_POST['messenger_code']);
            $messenger_branch   = mysqli_real_escape_string($conn, $_POST['messenger_branch']);
            $messenger_country  = mysqli_real_escape_string($conn, $_POST['messenger_country']);
            $messenger_city     = mysqli_real_escape_string($conn, $_POST['messenger_city']);
            $messenger_mobile   = mysqli_real_escape_string($conn, $_POST['messenger_mobile']);
            $messenger_email    = mysqli_real_escape_string($conn, $_POST['messenger_email']);
            $messenger_password = mysqli_real_escape_string($conn, $_POST['messenger_password']);
            $messenger_vehicle  = mysqli_real_escape_string($conn, $_POST['messenger_vehicle']);

            $salt               = uniqid('', true);
            $algo               = '6'; // CRYPT_SHA512
            $rounds             = '5042';
            $cryptSalt          = '$'.$algo.'$rounds='.$rounds.'$'.$salt;

            $hashedPassword     = crypt($messenger_password, $cryptSalt);

            
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            

                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                    // echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo '<script language="javascript">';
                    echo 'alert("File is not an image")';
                    echo '</script>';
                    $uploadOk = 0;
                }

                // Check if file already exists
                if (file_exists($target_file)) {
                    echo '<script language="javascript">';
                    echo 'alert("Sorry, file already exists")';
                    echo '</script>';
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["fileToUpload"]["size"] > 500000) {
                    echo '<script language="javascript">';
                    echo 'alert("Sorry, your file is too large")';
                    echo '</script>';
                    $uploadOk = 0;
                }
                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    echo '<script language="javascript">';
                    echo 'alert("Sorry, only JPG, JPEG, PNG & GIF files are allowed")';
                    echo '</script>';
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo '<script language="javascript">';
                    echo 'alert("Sorry, your file was not uploaded")';
                    echo '</script>';
                // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                    } else {
                        echo '<script language="javascript">';
                        echo 'alert("Sorry, there was an error uploading your file")';
                        echo '</script>';
                    }
                }

                $image = $_FILES["fileToUpload"]["name"];
                
                $check_code = mysqli_query($conn, "SELECT * FROM messenger WHERE messenger_code = '$messenger_code'") or die(mysqli_error($conn));
                $checkRows  = mysqli_num_rows($check_code);
                if($checkRows > 0){
                    $_SESSION['msg']="Sorry, Messenger Code already exists!";
                    header('Location: add_messenger.php');
                }else{

                    $messenger_insert = mysqli_query($conn, "INSERT INTO messenger (messenger_name, messenger_code, messenger_branch, messenger_country, messenger_city, messenger_mobile, messenger_email, messenger_password, messenger_vehicle, messenger_image) VALUES ('".$messenger_name."', '".$messenger_code."', '".$messenger_branch."', '".$messenger_country."', '".$messenger_city."', '".$messenger_mobile."', '".$messenger_email."', '".$hashedPassword."', '".$messenger_vehicle."', '".$image."' ) ") or die(mysqli_error($conn));

                        if($messenger_insert){
                            header('Location: show_messenger.php');
                        }
                    }
            
            
        }

?>


//Edit Form with image
<?php require_once('../inc/header.php'); ?>

<?php
    
    $login_id = $_SESSION['user'];

    if(isset($_GET['id'])){

        $id = $_GET['id'];

        $messenger_query = mysqli_query($conn, "SELECT * FROM messenger WHERE mid = '$id'") or die(mysqli_error($conn));
        $messenger_result = mysqli_fetch_assoc($messenger_query);

        if(isset($_POST['edit_messenger'])){

            $messenger_name     = mysqli_real_escape_string($conn, $_POST['messenger_name']);
            //$messenger_code     = mysqli_real_escape_string($conn, $_POST['messenger_code']);
            $messenger_branch   = mysqli_real_escape_string($conn, $_POST['messenger_branch']);
            $messenger_country  = mysqli_real_escape_string($conn, $_POST['messenger_country']);
            $messenger_city     = mysqli_real_escape_string($conn, $_POST['messenger_city']);
            $messenger_mobile   = mysqli_real_escape_string($conn, $_POST['messenger_mobile']);
            $messenger_email    = mysqli_real_escape_string($conn, $_POST['messenger_email']);
            $messenger_password = mysqli_real_escape_string($conn, $_POST['messenger_password']);
            $messenger_vehicle  = mysqli_real_escape_string($conn, $_POST['messenger_vehicle']);
            $messenger_image    = $messenger_result['messenger_image'];

            $salt           = uniqid('', true);
            $algo           = '6'; // CRYPT_SHA512
            $rounds         = '5042';
            $cryptSalt      = '$'.$algo.'$rounds='.$rounds.'$'.$salt;

            $hashedPassword = crypt($messenger_password, $cryptSalt);

            if(isset($_FILES['messenger_image']['tmp_name'])){

                $file       =   $_FILES['messenger_image']['tmp_name'];
                $image      =   addslashes(file_get_contents($_FILES['messenger_image']['tmp_name']));
                $image_name =   addslashes($_FILES['messenger_image']['name']);
                move_uploaded_file($_FILES["messenger_image"]["tmp_name"], "uploads/" . $_FILES["messenger_image"]["name"]);
                $messenger_image = $_FILES["messenger_image"]["name"];

            }

            $messenger_update = mysqli_query($conn, "UPDATE messenger SET messenger_name = '$messenger_name', messenger_branch = '$messenger_branch', messenger_country = '$messenger_country', messenger_city = '$messenger_city',   messenger_mobile = '$messenger_mobile', messenger_email = '$messenger_email', messenger_password = '$hashedPassword', messenger_vehicle = '$messenger_vehicle', messenger_image = '$messenger_image' WHERE mid = '$id'") or die(mysqli_error($conn));

            if($messenger_update){
                header('Location: show_messenger.php');
            }
            
        }

?>

<body class="materialdesign">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <!-- Header top area start-->
    <div class="wrapper-pro">

        <?php include('../inc/sidebar.php'); ?>

        <div class="content-inner-all">
            <div class="header-top-area">
                <div class="fixed-header-top">
                    <div class="container-fluid">
                        <div class="row">
                            <?php include('../inc/nav.php'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header top area end-->

            <!-- Breadcome start-->

            <?php include('../inc/breadcumb.php'); ?>

            <!-- Breadcome End-->

            <!-- Mobile Menu start -->

            <?php include('../inc/mobile_nav.php'); ?>

            <!-- Mobile Menu end -->

            <!-- Mobile Breadcome start-->
            
            <?php include('../inc/mobile_breadcumb.php'); ?>

            <!-- Mobile Breadcome End-->

            <!-- welcome Project, sale area start-->
            <div class="welcome-adminpro-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="add-shipment">
                                <h1>Add Messenger</h1>    
                            </div>

                            <form method="POST" enctype="multipart/form-data">
                            <div class="messenger-add">
                                
                                <label>Name</label>     
                                <input type="text" name="messenger_name" value="<?php echo $messenger_result['messenger_name']; ?>">
                                <label>Code</label>     
                                <input type="text" name="messenger_code" value="<?php echo $messenger_result['messenger_code']; ?>" readonly>
                                <label>Branch</label>     
                                <input type="text" name="messenger_branch" value="<?php echo $messenger_result['messenger_branch']; ?>">
                                <label>Country</label>
                                <select name="messenger_country">
                                    <option value="">--SELECT--</option>
                                        <?php 
                                            $query = mysqli_query($conn, "SELECT * FROM country") or die(mysqli_error($conn)); 
                                                if(mysqli_num_rows($query)>0){
                                                    while ($row = mysqli_fetch_assoc($query)){
                                        ?>
                                        <?php if($messenger_result['messenger_country'] == $row['country_name']){ ?>
                                        <option value="<?php echo $row['country_name'];?>" selected><?php echo $row['country_name'];?></option>
                                        <?php } ?>

                                        <?php           
                                                }
                                            }
                                        ?>    
                                </select>
                                <label>City</label>
                                <input type="text" name="messenger_city" value="<?php echo $messenger_result['messenger_city']; ?>">
                                <label>Mobile</label>
                                <input type="number" name="messenger_mobile" maxlength="10" min="1" value="<?php echo $messenger_result['messenger_mobile']; ?>">
                                <label>Email</label>
                                <input type="email" name="messenger_email" value="<?php echo $messenger_result['messenger_email']; ?>">
                                <label>Password</label>
                                <input type="password" name="messenger_password">
                                <label>Vehicle Number</label>
                                <input type="text" name="messenger_vehicle" value="<?php echo $messenger_result['messenger_vehicle']; ?>">
                                <label>Image</label>
                                <input type='file' onchange="readURL(this);" class="file-styled-primary" name="messenger_image" id="fileToUpload" />
                                <div class="messenger_img"><img src="uploads/<?php echo $messenger_result['messenger_image']; ?>"></div>

                            </div>
                            <div class="button-shipment">
                                <div class="row">
                                    <div class="container">
                                        <div class="col-md-12 text-center">
                                            <input type="submit" name="edit_messenger" class="btn btn-default btn-add-shipment" value="Update Messenger">
                                            <input type="submit" name="cancel" class="btn btn-default btn-cancel-shipment" value="Cancel">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- welcome Project, sale area start-->
        </div>
    </div>

    <?php include('../inc/footer.php'); ?>
    <?php } ?>
