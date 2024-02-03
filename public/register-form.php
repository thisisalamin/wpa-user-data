<?php
    if(isset($_POST['register'])){
        global $wpdb;
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $confirm_password = sanitize_text_field($_POST['confirm_password']);

        if($password == $confirm_password){

            $result = wp_create_user($username, $password, $email);
            if(!is_wp_error($result)){
                echo "User created successfully" . $result;
            }else{
                echo $result->get_error_message();
            }

        }else{
            echo "Password does not match";
        }

    }
?>

<form id="wpaud-register-form" action="<?php echo get_the_permalink();?>" method="post">
    <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name">
    </div>
    <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name">
    </div>
 
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Email Address">
    </div>
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control" placeholder="Username">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
    </div>

    <div class="form-group">
        <input type="submit" name="register" id="register" class="btn btn-primary" value="Register">
    </div>
</form>
