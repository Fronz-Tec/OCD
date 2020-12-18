<?php

?>


<aside>

</aside>

<main>
    <?php

        if(isset($_SESSION["username"])){
            if ($_GET["site"] == "chat"){
                include("chat/content.php");
            }
        }else{
            if($_GET["site"] == "register"){
                include("usermanagement/register.php");
            }elseif($_GET["site"] == "login"){
                include("usermanagement/login.php");
            } else{
                header('location: http://localhost/OCD?site=login');
            }
        }


    ?>
</main>