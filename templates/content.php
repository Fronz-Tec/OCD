<?php
if(isset($_SESSION["username"])){
    if($_GET["site"] == "chat") {
?>
<aside>
    <?php

    include_once "templates/aside.php";

    ?>
</aside>
<?php
    }
}
?>




<main>
    <?php
//TODO Kevin
//        if (!dbController.isUserChatter()) {
//            //forward to user is in verification site
//        } else
        if(isset($_SESSION["username"])){
            if ($_GET["site"] == "chat"){
                include_once("chat/content.php");
            }elseif ($_GET["site"] == "admin"){
                include_once("admin/users.php");
            }
        }else{
            if($_GET["site"] == "register"){
                include_once("usermanagement/registration.php");
            }elseif($_GET["site"] == "login"){
                include_once("usermanagement/login.php");
            } else{
                header('location: http://localhost/OCD?site=login');
            }
        }


    ?>
</main>