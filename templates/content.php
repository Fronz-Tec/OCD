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

    include_once "source/controller/db_controller.php";

        if(isset($_SESSION["username"])){
            if ($_GET["site"] == "chat"){
                include_once("chat/content.php");
            }elseif ($_GET["site"] == "admin"){
                if(isAdmin()){
                    include_once("admin/users.php");
                }else{
                    header('location: http://localhost/OCD?site=chat');
                }
            }
        }else{
            if($_GET["site"] == "register"){
                include_once("usermanagement/registration.php");
            }elseif($_GET["site"] == "login"){
                include_once("usermanagement/login.php");
            }elseif($_GET["site"] == "reset"){
                include_once("usermanagement/pwRecover.php");
            } else{
                header('location: http://localhost/OCD?site=login');
            }
        }


    ?>
</main>