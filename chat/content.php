<div class="chatContainer" id="chatContainer">

<!---->
    <ul>
        <?php
        include_once "source/controller/db_controller.php";
        $messages = getMessages();
//        $value = mysqli_fetch_array($messages)["username"];


        foreach ($messages as $message){
            echo "<div>
            <span>".$message[0]."</span><br>
            <span>".$message[1]."</span><br>
            </div><br>";


        }
        ?>
    </ul>

</div>

<div class="chatBoxContainer">
    <form action="source/controller/db_controller.php" method="post">
        <textarea class="textInput" id="newMessage" name="newMessage"></textarea>
        <button type="submit">Send</button>
    </form>
</div>