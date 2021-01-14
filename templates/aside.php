
<ul>
    <?php
    $_POST["allChatter"] = "";

    include_once "source/controller/db_controller.php";
    $chatters = getAllChatters();
    $value = mysqli_fetch_array($chatters)["username"];


    foreach ($chatters as $chatter){

//        if($chatter["username"] != $_SESSION["currentChatter"]) {
            if($chatter["username"] != $_SESSION["username"]) {
                echo "<form method='post' action='source/controller/db_controller.php'>
                  <input type='hidden' id='currentChatter' name='currentChatter' value='" . $chatter["username"] . "'>
                  <button type='submit' class='link-button'>
                      " . $chatter["username"] . "
                 </button>
                </form>";
            }
//        }
    }
    ?>
</ul>


