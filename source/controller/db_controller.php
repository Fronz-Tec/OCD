<?php

$connection = null;

if(isset($_POST["username"])&& isset($_POST["password"])) {

    verifyUser($_POST["username"], $_POST["password"]);
}else{
    logout();
}

function dbConnect(){
    global $connection;
    $server = "localhost";
    $password = "root";
    $user = "root";
    $db = "mydb";

    $connection = new mysqli($server, $user,$password, $db);

    if($connection -> connect_error){
        die($connection -> connect_error);
    }

}

function dbDisconnect(){

    global $connection;
    mysqli_close($connection);
}

function getDBConnection(){
    global $connection;

    if($connection == null){
        dbConnect();
    }

    return $connection;
}

function getUser(){

}

function getAllUsers(){

}

function registerUser($username, $password, $email){

    if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])) {

        $email = $_POST["email"];
        $username = $_POST["username"];
        $password = $_POST["password"];

        $statement = "SELECT 1 FROM user WHERE username = '".$username."'";

        $result = mysqli_query(getDBConnection(),$statement);

        if($result-> num_rows >= 1){
            echo "Sorry, this user already exists";
        }else{
            $statement = "SELECT 1 FROM user WHERE email = '".$email."'";

            $result = mysqli_query(getDBConnection(),$statement);

        }

    }
}
function verifyUser($username, $password){
//    $temp = getDBConnection() -> query("Select * From User");
//    $row = $temp -> fetch_array(MYSQLI_ASSOC);
//    printf($row);
    //ToDo: Check if any user exists, else add roles and create admin user

    $statement = "SELECT 1 FROM user";

    $result = mysqli_query(getDBConnection(), $statement);

    if($result -> num_rows == 0){

        getDBConnection()->query("INSERT INTO roles(name) VALUES ('admin')");
        getDBConnection()->query("INSERT INTO roles(name) VALUES ('chatter')");
        getDBConnection()->query("INSERT INTO roles(name) VALUES ('newbie')");
        getDBConnection()->query("INSERT INTO user(username,password, roles_idroles) VALUES ('admin','password','1')");

        mysqli_query(getDBConnection(), $statement);

    }

    $statement = "SELECT 1 FROM user WHERE username = '".$username."' AND password = '".$password."'";

    $result = mysqli_query(getDBConnection(),$statement);

    echo $result->num_rows;


    if($result -> num_rows == 1){
        //ToDo create user session

        session_start();
        $_SESSION["username"] = $username;

        header('location: http://localhost/OCD?site=chat');
    }else{
        //ToDo Inform User
        header('location: http://localhost/OCD?site=login');
    }

}

function logout(){
    session_start();
    session_destroy();
    header('location: http://localhost/OCD?site=login');
}

function resetPW($email, $username){

}

function sendMessage($user, $message){

}

function getMessage($user){

}

function setRole($user, $role){

}

function getAllChatters(){

}

