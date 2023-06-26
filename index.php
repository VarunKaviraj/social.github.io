<?php
require_once 'assets/php/functions.php';

if(isset($_GET['newfp'])){
    unset($_SESSION['auth_temp']);
    unset($_SESSION['forgot_email']);
    unset($_SESSION['forgot_code']);
}

if(isset($_SESSION['Auth'])){
    $user = getUser($_SESSION['userdata']['id']);
    $posts = getPost();
}

$pagecount = count($_GET);


if(isset($_SESSION['Auth']) && $user['ac_status']==1 && !$pagecount){
    showPage('header',['page_title'=>'Home']);
    showPage('navbar');
    showPage('wall');
    
}elseif(isset($_SESSION['Auth']) && $user['ac_status']==0 && !$pagecount){
    showPage('header',['page_title'=>'Verify your email']);
    showPage('verify_email');
}
elseif(isset($_SESSION['Auth']) && $user['ac_status']==2 && !$pagecount){
    showPage('header',['page_title'=>'blocked']);
    showPage('blocked');
}elseif(isset($_SESSION['Auth']) && isset($_GET['editprofile'])){
    showPage('header',['page_title'=>'Edit Profile']);
    showPage('navbar');
    showPage('edit_profile');
    
}elseif(isset($_SESSION['Auth']) && isset($_GET['u'])){
    showPage('header',['page_title'=>'Edit Profile']);
    showPage('navbar');
    showPage('profile');
    
}
else 
if(isset($_GET['signup'])){
    showPage('header',['page_title'=>'sociallyactive - SignUp']);
    showPage('signup');
    
}else if(isset($_GET['login'])){
    
    showPage('header',['page_title'=>'sociallyactive - Login']);
    showPage('login');
}else if(isset($_GET['forgotpassword'])){
    
    showPage('header',['page_title'=>'sociallyactive - Forgot Password']);
    showPage('forgot_password');
}
else{
    if(isset($_SESSION['Auth']) ){
    showPage('header',['page_title'=>'Home']);
    showPage('navbar');
    showPage('wall');
    }else{
    showPage('header',['page_title'=>'sociallyactive - Login']);
    showPage('login');
    }
    
}

showPage('footer');
unset($_SESSION['error'] );
unset($_SESSION['formdata']);