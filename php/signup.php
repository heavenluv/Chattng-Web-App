<?php
    session_start();
    include_once "config.php";
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        //check validity of email
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            //check email inside database or not
            $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}' ");
            if(mysqli_num_rows($sql) > 0) {
                echo "$email -  This email already existed!";
            } else {
                //let's check user upload file or not
                if(isset($_FILES['image'])){
                    $img_name = $_FILES['image']['name'];//getting user uploaded img name;
                    $img_type = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];//temp name to save/move file in our folder
                    //explode image and get the last extension like jpg png
                    $img_explode = explode('.', $img_name);
                    $img_ext = end($img_explode);//get the img format

                    $extensions = ["png", "jpeg", "jpg"];//declare the valid img extension and store in an array
                    if(in_array($img_ext, $extensions) === true){
                        $time = time();//get current time and use it to rename the img so that each img file will have an unique file name
                        //move user uploaded img to particular folder
                        $new_img_name = $time.$img_name;
                        if(move_uploaded_file($tmp_name,"images/".$new_img_name)){ //if uploaded img move to folder succesfully
                            $status = "Active now";//once signed up then the status will be active
                            $random_id = rand(time(), 100000000);
                            //$encrypt_pass = md5($password);
                            //insert user data into database
                            /*
                            $try = "INSERT INTO users (user_id, unique_id, fname, lname, email, password, img, status)
                                    VALUES ({$random_id}, {$random_id}, '{$fname}','{$lname}', '{$email}', '{$encrypt_pass}', '{$new_img_name}', '{$status}')";
                            if ($conn->query($try)){
                                echo "records inserted successfully";
                            } else {
                                echo "ERROR: Could not able to execute $try.".$conn->error; 
                            }
                            */
                            $sql2 = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                    VALUES ({$random_id}, '{$fname}','{$lname}', '{$email}', '{$password}', '{$new_img_name}', '{$status}')");
                            if($sql2) {
                                //if data inserted
                                $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}' ");
                                if(mysqli_num_rows($sql3) > 0) {
                                    $row = mysqli_fetch_assoc($sql3);
                                    $_SESSION['unique_id'] = $row['unique_id'];  //we can use the user unique_id in other file by this session
                                    echo "success";
                                } 
                            } else {
                                echo "Something went wrong! Please try again!";

                            }
                        } else {
                            echo "Folder permission problem";
                        }
                        
                        
                    } else{
                        echo "Please select an image file --- jpeg, jpg, png!";
                    }

                } else {
                    echo "Please upload an image file!";
                }
            }
        } else {
            echo "$email - This is not a valid email!";
        }
    } else {
        echo "All input field are required!";
    }
?>