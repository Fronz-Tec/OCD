<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'assets/php/PHPMailer/src/Exception.php';
require 'assets/php/PHPMailer/src/PHPMailer.php';
require 'assets/php/PHPMailer/src/SMTP.php';


$connection = null;

if (isset($_POST["username"]) && isset($_POST["password"])) {
    verifyUser($_POST["username"], $_POST["password"]);
} elseif (isset($_POST["username"]) && isset($_POST["email"])) {
    resetPW($_POST["email"],$_POST["username"]);
}elseif (isset($_POST["username_reg"]) && isset($_POST["password_reg"]) && isset($_POST["email_reg"])) {
    registerUser($_POST["username_reg"], $_POST["password_reg"], $_POST["email_reg"]);
} elseif (isset($_POST["allChatter"])) {
    getAllChatters();
} elseif (isset($_POST["newMessage"])) {
    sendMessage($_POST["newMessage"]);
} elseif (isset($_POST["currentChatter"])) {
//    getMessages($_POST["currentChatter"]);

    session_start();
    $_SESSION["currentChatter"] = $_POST["currentChatter"];
    error_log($_SESSION["currentChatter"]);
    header('location: http://localhost/OCD?site=chat');
} elseif (isset($_POST["logout"])) {
    logout();
} elseif (isset($_POST["user"]) && isset($_POST["role"])) {
    error_log("UPDATE user SET roles_idroles = " . $_POST["role"] . " WHERE username = '" . $_POST["user"] . "'");
    $user_role_query = "UPDATE user SET roles_idroles = " . $_POST["role"] . " WHERE username = '" . $_POST["user"] . "'";
    $resultuserrole = mysqli_query(getDBConnection(), $user_role_query);

    header('location: http://localhost/OCD?site=admin');

} else {


}

function dbConnect()
{
    global $connection;
    $server = "localhost";
    $password = "root";
    $user = "root";
    $db = "mydb";

    $connection = new mysqli($server, $user, $password, $db);

    if ($connection->connect_error) {
        die($connection->connect_error);
    }

}

function dbDisconnect()
{

    global $connection;
    mysqli_close($connection);
}

function getDBConnection()
{
    global $connection;

    if ($connection == null) {
        dbConnect();
    }

    return $connection;
}

function getUser()
{

}

function getAllUsers()
{
    $users_query = "SELECT * FROM user";
    $result = mysqli_query(getDBConnection(), $users_query);

    $userlist = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $user_role_query = "SELECT name FROM roles WHERE idroles = '" . $row["roles_idroles"] . "'";
        $resultuserrole = mysqli_query(getDBConnection(), $user_role_query);
        $userrolename = mysqli_fetch_array($resultuserrole)["name"];

        $userItem = array($row["username"], $userrolename);
        array_push($userlist, $userItem);
    }
    return $userlist;
}

function registerUser($username, $password, $email)
{

    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $statement = "SELECT * FROM user WHERE username = '" . $username . "'";

    $result = mysqli_query(getDBConnection(), $statement);

    if ($result->num_rows >= 1) {
        header('location: http://localhost/OCD?site=register%20message=sorry_this_user_already_exists');

    } else {
        error_log("Succesfully checked username");
        $statement = "SELECT * FROM user WHERE email = '" . $email . "'";

        $result = mysqli_query(getDBConnection(), $statement);

        if ($result->num_rows >= 1) {
            header('location: http://localhost/OCD?site=register%20message=sorry_this_email_already_exists');
        } else {
            error_log("Succesfully checked email");
            error_log("INSERT INTO user (username, email, password) VALUES ('" . $username . "','" . $email . "','" . $password_hashed . "',6)");
            $statement = "INSERT INTO user (username, email, password, roles_idroles) VALUES ('" . $username . "','" . $email . "','" . $password_hashed . "',6)";
            mysqli_query(getDBConnection(), $statement);
            header('location: http://localhost/OCD?site=login%20message=successful');
        }

    }


}

function verifyUser($username, $password)
{
//    $temp = getDBConnection() -> query("Select * From User");
//    $row = $temp -> fetch_array(MYSQLI_ASSOC);
//    printf($row);
    //ToDo: Check if any user exists, else add roles and create admin user


    $statement = "SELECT 1 FROM user";

    $result = mysqli_query(getDBConnection(), $statement);

    if ($result->num_rows == 0) {

        getDBConnection()->query("INSERT INTO roles(name) VALUES ('admin')");
        getDBConnection()->query("INSERT INTO roles(name) VALUES ('chatter')");
        getDBConnection()->query("INSERT INTO roles(name) VALUES ('newbie')");
        getDBConnection()->query("INSERT INTO user(username,password, roles_idroles) VALUES ('admin'," . password_hash('password', PASSWORD_DEFAULT) . "'1')");
        getDBConnection()->query("INSERT INTO user(username,password, roles_idroles) VALUES ('all'," . password_hash('123', PASSWORD_DEFAULT) . "'1')");

        mysqli_query(getDBConnection(), $statement);

    }

    error_log($password);
    $statement = "SELECT * FROM user WHERE username = '" . $username . "'";
    if ($result = mysqli_query(getDBConnection(), $statement)->num_rows == 1) {

        $statement = "SELECT password FROM user WHERE  username = '" . $username . "'";
        error_log("SELECT * FROM user WHERE username = '" . $username . "' AND password = '" . password_verify($password, PASSWORD_DEFAULT) . "'");

        $result = mysqli_query(getDBConnection(), $statement);

        $value = mysqli_fetch_array($result)["password"];

        if (password_verify($password, $value)) {
            session_start();
            $_SESSION["username"] = $username;

            header('location: http://localhost/OCD?site=chat');
        } else {
            //ToDo Inform User
            header('location: http://localhost/OCD?site=login');
        }

    } else {
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

    $pw_reset_query = "SELECT * FROM user WHERE username = '" . $username . "' AND email = '".$email."'";
    $result = mysqli_query(getDBConnection(), $pw_reset_query);

    if ($result->num_rows == 1) {
        error_log("PW RESET LOG");
        $new_pw = randomPassword();

        $new_pw_hashed = password_hash($new_pw, PASSWORD_DEFAULT);

        $update_user = "UPDATE user SET password = " .  $new_pw_hashed . " WHERE username = '" . $username . "'";
        $pw_result = mysqli_query(getDBConnection(), $update_user);

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'fronztec@gmail.com';                     // SMTP username
            $mail->Password   = 'Werfv_Cxyaq1$';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('admin@localhost.com', 'OCD');
            $mail->addAddress($email, $username);     // Add a recipient


            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Password Reset';
            $mail->Body    = 'Your new password: '.$new_pw.'';
            $mail->AltBody    = 'Your new password: '.$new_pw.'';

            $mail->send();
            echo 'Message has been sent';

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        header('location: http://localhost/OCD?site=login%20message=pwresetsuccess');


    }else{
        header('location: http://localhost/OCD?site=reset%20message=pwresetfailed');
    }

}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function sendMessage($message)
{
    session_start();
    $sender = $_SESSION["username"];
    $empfanger = $_SESSION["currentChatter"];
    error_log($empfanger);

    mysqli_query(getDBConnection(), "START TRANSACTION;");

    //sender id
    $sender_id_query = "SELECT idUser FROM user WHERE username = '" . $sender . "'";
    $result = mysqli_query(getDBConnection(), $sender_id_query);
    $sender_id = mysqli_fetch_array($result)["idUser"];

    //insert message
    $statement = "INSERT INTO messages (sender, text) VALUES ('" . $sender_id . "','" . $message . "')";

    //message id

    $result = mysqli_query(getDBConnection(), $statement);
    $idmessages = mysqli_insert_id(getDBConnection());


//    if ($empfanger != "all") {
        //receiver id
        $reveiver_id_query = "SELECT idUser FROM user WHERE username = '" . $empfanger . "'";
        $result = mysqli_query(getDBConnection(), $reveiver_id_query);
        $reveiver_id = mysqli_fetch_array($result)["idUser"];

        //insert reveiver 1-n
        $statement = "INSERT INTO reciever (user_fsid, message_reciever) VALUES (" . $reveiver_id . "," . $idmessages . ")";
        $result = mysqli_query(getDBConnection(), $statement);
        $statement = "INSERT INTO reciever (user_fsid, message_reciever) VALUES (" . $sender_id . "," . $idmessages . ")";
        $result = mysqli_query(getDBConnection(), $statement);

    mysqli_query(getDBConnection(), "COMMIT;");
//    error_log($statement);

    session_abort();
    header('location: http://localhost/OCD?site=chat');
}

function getMessages(){

    $messageList = array();
    if (isset($_SESSION["currentChatter"])) {

        $user = $_SESSION["currentChatter"];
        error_log($user);

        $self_id_query = "SELECT idUser FROM user WHERE username = '" . $_SESSION["username"] . "'";
        $result = mysqli_query(getDBConnection(), $self_id_query);
        $self_id = mysqli_fetch_array($result)["idUser"];


        $sender_id_query = "SELECT idUser FROM user WHERE username = '" . $user . "'";
        $result = mysqli_query(getDBConnection(), $sender_id_query);
        $sender_id = mysqli_fetch_array($result)["idUser"];

        if($user == "all"){
            $statement = "SELECT * FROM reciever WHERE user_fsid ='" . $sender_id . "'";
        }else{
            $statement = "SELECT * FROM reciever WHERE user_fsid ='" . $sender_id . "' OR user_fsid ='" . $self_id . "'";
        }

        $result = mysqli_query(getDBConnection(), $statement);

        $last_message_id = 0;

        $added_message = 0;

        //loop through receiver table get fitting receiver combinations
        //message_reciever needs to have 2 fitting user_fsids --> 1:1 chat
        //message_reciever needs to have 2+ fitting user_fsids --> public chat
        while ($row = mysqli_fetch_assoc($result)) {

            if ($last_message_id == $row['message_reciever'] && $user != "all") {
                $statement = "SELECT * FROM messages WHERE idmessages ='" . $row["message_reciever"] . "'";
                $resultMessage = mysqli_query(getDBConnection(), $statement);
                $messageRow = mysqli_fetch_array($resultMessage);

                if (isset($messageRow["sender"])) {
                    $sender_name_query = "SELECT username FROM user WHERE idUser = '" . $messageRow["sender"] . "'";
                    $resultSenderUsername = mysqli_query(getDBConnection(), $sender_name_query);
                    $sender_username = mysqli_fetch_array($resultSenderUsername)["username"];

                    $messageElement = array($sender_username,  $messageRow["date"], $messageRow["text"]);
                    array_push($messageList, $messageElement);
//                    $added_message += 1;
                }

            }else if($user == "all"){
                $statement = "SELECT * FROM messages WHERE idmessages ='" . $row["message_reciever"] . "'";
                $resultMessage = mysqli_query(getDBConnection(), $statement);
                $messageRow = mysqli_fetch_array($resultMessage);
                if (isset($messageRow["sender"])) {
                    $sender_name_query = "SELECT username FROM user WHERE idUser = '" . $messageRow["sender"] . "'";
                    $resultSenderUsername = mysqli_query(getDBConnection(), $sender_name_query);
                    $sender_username = mysqli_fetch_array($resultSenderUsername)["username"];

                    $messageElement = array($sender_username,  $messageRow["date"], $messageRow["text"]);
                    array_push($messageList, $messageElement);
//                    $added_message += 1;
                }

            }

            //for groupchats so messages don't show up multiple times
//            if ($last_message_id !== $row['message_reciever']) {
//                $added_message = 0;
//
//            }

            $last_message_id = $row['message_reciever'];

        }
    }
    return $messageList;
}

function getAllChatters()
{


    if (isNewbie()) {

        return null;

    } else {

        $statement = "SELECT username FROM user WHERE roles_idroles = 1 OR roles_idroles = 5";
        $temp_result = mysqli_query(getDBConnection(), $statement);

        $result = array();


        while ($user = mysqli_fetch_array($temp_result)) {

            if ($user['username'] !== "all") {
                array_push($result, $user['username']);
            }

        }

        return $result;


    }
}

function isNewbie()
{
    if (isset($_SESSION["username"])) {
        $self_role_query = "SELECT roles_idroles FROM user WHERE username = '" . $_SESSION["username"] . "'";
        $result = mysqli_query(getDBConnection(), $self_role_query);
        $self_role = mysqli_fetch_array($result)["roles_idroles"];

        $role_query = "SELECT idroles FROM roles WHERE name = 'newbie'";
        $result = mysqli_query(getDBConnection(), $role_query);
        $self_role_name = mysqli_fetch_array($result)["idroles"];

        return $self_role == $self_role_name;
    }
    return false;
}

function isAdmin()
{
    if (isset($_SESSION["username"])) {
        $self_role_query = "SELECT roles_idroles FROM user WHERE username = '" . $_SESSION["username"] . "'";
        $result = mysqli_query(getDBConnection(), $self_role_query);
        $self_role = mysqli_fetch_array($result)["roles_idroles"];

        $role_query = "SELECT idroles FROM roles WHERE name = 'admin'";
        $result = mysqli_query(getDBConnection(), $role_query);
        $self_role_name = mysqli_fetch_array($result)["idroles"];

        return $self_role == $self_role_name;
    }

    return false;
}