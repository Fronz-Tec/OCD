<?php

$connection = null;

if (isset($_POST["username"]) && isset($_POST["password"])) {
    verifyUser($_POST["username"], $_POST["password"]);
} elseif (isset($_POST["username_reg"]) && isset($_POST["password_reg"]) && isset($_POST["email_reg"])) {
    registerUser($_POST["username_reg"], $_POST["password_reg"], $_POST["email_reg"]);
} elseif (isset($_POST["allChatter"])) {
    getAllChatters();
} elseif (isset($_POST["newMessage"])) {
    sendMessage($_POST["newMessage"]);
} elseif (isset($_POST["currentChatter"])) {
    getMessage($_POST["currentChatter"]);
}else {
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

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $statement = "SELECT * FROM user WHERE username = '".$username."'";

        $result = mysqli_query(getDBConnection(),$statement);

        if($result-> num_rows >= 1){
            header('location: http://localhost/OCD?site=register%20message=sorry_this_user_already_exists');

        }else{
            error_log("Succesfully checked username");
            $statement = "SELECT * FROM user WHERE email = '".$email."'";

            $result = mysqli_query(getDBConnection(),$statement);

            if($result->num_rows >= 1){
                header('location: http://localhost/OCD?site=register%20message=sorry_this_email_already_exists');
            }else{
                error_log("Succesfully checked email");
                error_log("INSERT INTO user (username, email, password) VALUES ('".$username."','".$email."','".$password_hashed."',6)");
                $statement = "INSERT INTO user (username, email, password, roles_idroles) VALUES ('".$username."','".$email."','".$password_hashed."',6)";
                mysqli_query(getDBConnection(),$statement);
                header('location: http://localhost/OCD?site=login%20message=successful');
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
        getDBConnection()->query("INSERT INTO user(username,password, roles_idroles) VALUES ('admin',".password_hash('password',PASSWORD_DEFAULT)."'1')");

        mysqli_query(getDBConnection(), $statement);

    }

    error_log($password);
    $statement = "SELECT * FROM user WHERE username = '".$username."'";
    if($result = mysqli_query(getDBConnection(),$statement)->num_rows == 1){

        $statement = "SELECT password FROM user WHERE  username = '".$username."'";
        error_log("SELECT * FROM user WHERE username = '".$username."' AND password = '".password_verify($password, PASSWORD_DEFAULT)."'");

        $result = mysqli_query(getDBConnection(),$statement);

        $value = mysqli_fetch_array($result)["password"];

        if (password_verify($password,$value)) {
            session_start();
            $_SESSION["username"] = $username;

            header('location: http://localhost/OCD?site=chat');
        }else{
            //ToDo Inform User
            header('location: http://localhost/OCD?site=login');
        }

    }else{
        error_log("More than one user with same name, or none with that name found");
        header('location: http://localhost/OCD?site=login%20message=failed');
    }

}

function logout(){
    session_start();
    session_destroy();
    header('location: http://localhost/OCD?site=login');
}

function resetPW($email, $username){

}

function sendMessage($message){
    session_start();
    $sender = $_SESSION["username"];
    $empfanger = $_SESSION["currentChatter"];

    mysqli_query(getDBConnection(), "START TRANSACTION;");

    //sender id
    $sender_id_query = "SELECT idUser FROM user WHERE username = '".$sender."'";
    $result = mysqli_query(getDBConnection(),$sender_id_query);
    $sender_id = mysqli_fetch_array($result)["idUser"];

    //insert message
    $statement = "INSERT INTO messages (sender, text) VALUES ('".$sender_id."','".$message."')";

    //message id
    $result = mysqli_query(getDBConnection(),$statement);
    $idmessages = mysqli_insert_id(getDBConnection());

    //receiver id
    $reveiver_id_query = "SELECT idUser FROM user WHERE username = '".$empfanger."'";
    $result = mysqli_query(getDBConnection(),$reveiver_id_query);
    $reveiver_id = mysqli_fetch_array($result)["idUser"];

    //insert reveiver 1-n
    $statement = "INSERT INTO reciever (user_fsid, message_reciever) VALUES (".$reveiver_id.",".$idmessages.")";
    $result = mysqli_query(getDBConnection(),$statement);

    mysqli_query(getDBConnection(), "COMMIT;");
//    error_log($statement);

    header('location: http://localhost/OCD?site=chat');
}

function getMessage($user){
    session_start();
    $_SESSION["currentChatter"] = $user;
    error_log($user);
    header('location: http://localhost/OCD?site=chat');

    $sender_id_query = "SELECT idUser FROM user WHERE username = '".$user."'";
    $result = mysqli_query(getDBConnection(),$sender_id_query);
    $sender_id = mysqli_fetch_array($result)["idUser"];

    error_log("SELECT * FROM reciever WHERE user_fsid ='".$sender_id."'");
    $statement = "SELECT * FROM reciever WHERE user_fsid ='".$user."'";
    $message = mysqli_query(getDBConnection(),$statement)["message_reciever"];

    $messages = mysqli_fetch_array($message)["message_reciever"];

    foreach ($messages as $currentMessage){
        error_log("SELECT * FROM messages WHERE idmessages ='".$currentMessage."'");
        $statement = "SELECT * FROM messages WHERE idmessages ='".$currentMessage."'";
        $result = mysqli_query(getDBConnection(),$statement);


        return $result;
    }

}

function setRole($user, $role){

}


function getAllChatters(){

    $statement = "SELECT username FROM user WHERE roles_idroles = 1 OR roles_idroles = 5";

    $result = mysqli_query(getDBConnection(),$statement);



    return $result;

}

