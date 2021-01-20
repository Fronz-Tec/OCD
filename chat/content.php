<div class="chatContainer" id="chatContainer">

<!---->
        <?php
        include_once "source/controller/db_controller.php";
        $messages = getMessages();
//        $value = mysqli_fetch_array($messages)["username"];


        foreach ($messages as $message){
            echo "<div style='width: 100%;height: 200px;'>";
            if($message[0] == $_SESSION["username"]){
                echo "<div style='float: right; width: 50%; min-height: 150px;'>";
            }else{
                echo "<div style='float: left; width: 50%; min-height: 150px;'>";
            }
            echo "
            <span>".$message[0]."</span><br>
            <span>".$message[1]."</span><br>
            <span>".$message[2]."</span><br>
            </div></div><br>";


        }
        ?>

</div>
<?php
    if (!isNewbie()){
?>
<div class="chatBoxContainer">
    <form action="source/controller/db_controller.php" method="post">
        <textarea class="textInput" id="newMessage" name="newMessage"></textarea>
        <button type="submit">Send</button>
    </form>
</div>

<?php

    }

?>