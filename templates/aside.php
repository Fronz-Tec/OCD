
<ul>
    <?php
    $_POST["allChatter"] = "";

    include_once "source/controller/db_controller.php";
    $chatters = getAllChatters();
//    $value = mysqli_fetch_array($chatters)["username"];

    if($chatters !== null) {

        echo "<form method='post' action='source/controller/db_controller.php'>
                      <input type='hidden' id='currentChatter' name='currentChatter' value='all'>
                      <button type='submit' class='link-button'>
                          General Chat
                     </button>
                    </form>";

        foreach ($chatters as $chatter) {

    //        if($chatter["username"] != $_SESSION["currentChatter"]) {
            if ($chatter != $_SESSION["username"]) {
                echo "<form method='post' action='source/controller/db_controller.php'>
                      <input type='hidden' id='currentChatter' name='currentChatter' value='" . $chatter. "'>
                      <button type='submit' class='link-button'>
                          " . $chatter. "
                     </button>
                    </form>";
            }
    //        }
        }
    }else{
        echo "<p>No Access Rights</p>";
    }
    ?>
</ul>


