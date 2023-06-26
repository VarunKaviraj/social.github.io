<?php

require_once 'functions.php';
require_once 'send_code.php';

if (isset($_GET['signup'])) {
    $response = validateSignupForm($_POST);
    if ($response['status']) {
        if (createUser($_POST)) {
            header('location: ../../?login');
            exit();
        } else {
            echo "<script>alert('something is wrong')</script>";
            exit();
        }
    } else {
        $_SESSION['error'] = $response;
        $_SESSION['formdata'] = $_POST;
        header("location: ../../?signup");
        exit();
    }
}

if (isset($_GET['login'])) {
    $response = validateLoginForm($_POST);
    if ($response['status']) {
        $_SESSION['Auth'] = true;
        $_SESSION['userdata'] = $response['user'];

        if ($response['user']['ac_status'] == 0) {
            $_SESSION['code'] = $code = rand(111111, 999999);
            sendCode($response['user']['email'], 'verify your email', $code);
        }

        header("location: ../../");
        exit();
    } else {
        $_SESSION['error'] = $response;
        $_SESSION['formdata'] = $_POST;
        header("location: ../../?login");
        exit();
    }
}

if (isset($_GET['resend_code'])) {
    $_SESSION['code'] = $code = rand(111111, 999999);
    sendCode($_SESSION['userdata']['email'], 'verify your email', $code);
    header('location: ../../?resended');
    exit();
}

if (isset($_GET['verify_email'])) {
    $user_code = $_POST['code'];
    $code = $_SESSION['code'];
    if ($code == $user_code) {
        if (verifyEmail($_SESSION['userdata']['email'])) {
            header('location: ../../');
            exit();
        } else {
            echo "something is wrong";
            exit();
        }
    } else {
        $response['msg'] = 'Incorrect verification code';
        if ($_POST['code']) {
            $response['msg'] = 'enter 6 digit code';
        }
        $response['field'] = 'email_verify';
        $_SESSION['error'] = $response;
        header('location: ../../');
        exit();
    }
}

if (isset($_GET['forgotpassword'])) {
    if (!$_POST['email']) {
        $response['msg'] = "enter your email id";
        $response['field'] = 'email';
        $_SESSION['error'] = $response;
        header('location: ../../?forgotpassword');
        exit();
    } else
    if(!isEmailRegistered($_POST['email'])) {
        $response['msg'] = "email id is NOT registered";
        $response['field'] = 'email';
        $_SESSION['error'] = $response;
        header('location: ../../?forgotpassword');
        exit();
    } else {
        $_SESSION['forgot_email'] = $_POST['email'];
        $_SESSION['forgot_code'] = $code = rand(111111, 999999);
        sendCode($_POST['email'], 'Forgot your password?', $code);
        header('location: ../../?forgotpassword&resended');
        exit();
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('location: ../../');
    exit();
}

if (isset($_GET['verifycode'])) {
    $user_code = $_POST['code'];
    $code = $_SESSION['forgot_code'];
    if($code == $user_code) {
        $_SESSION['auth_temp'] = true;
        header('location: ../../');
        
    }
else{
    $response['msg'] = 'Incorrect verification code';
    if(!$_POST['code']) {
        $response['msg'] = 'enter 6 digit code';
       
    }
    
        $response['field'] = 'email_verify';
    $_SESSION['error'] = $response;
    header('location:../../?forgotpassword');
    

    
    
}
}

if (isset($_GET['changepassword'])) {
    if (!$_POST['password']) {
        $response['msg'] = "enter your new password";
        $response['field'] = 'password';
        $_SESSION['error'] = $response;
        header('location: ../../?forgotpassword');
        exit();
    } else {
        resetPassword($_SESSION['forgot_email'], $_POST['password']);
        header('location: ../../?reseted');
        exit();
    }
}

if(isset($_GET['updateprofile'])){
   
    
    
    $response = validateUpdateForm($_POST,$_FILES['profile_pic']);
    
    if ($response['status']) {
        if(updateprofile($_POST,$_FILES['profile_pic'])){
            header("location: ../../?editprofile&success");
        }
        else{
            echo "something is wrong";
        }
    } else {
        $_SESSION['error'] = $response;
        
        header("location: ../../?editprofile");
        exit();
    }
}

if(isset($_GET['addpost'])){
    $response = validatePostImage($_FILES['post_img']);

    if($response['status']){
        if(createPost($_POST,$_FILES['post_img'])){
            header("location: ../../?new_post_added");
        }else{
            echo "something went wrong";
        }
    }else{
        $_SESSION['error'] = $response;
        
        header("location: ../../");
    }
}
