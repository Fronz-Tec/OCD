

<div class="chatContainer">
    <?php
    //Pseudocode
//    for every message where sender || empfänger fits{
//
//        if message from  user{
//
//            add next to list
//            ?>
<!---->
<!--            <div class="recieverContainer">-->
<!--                <span id=""-->
<!--            </div>-->
<!---->
<!---->
<!---->
<!--            --><?php
//        }else{
//            add next to list
//            ?>
<!---->
<!--                <div class="senderContainer">-->
<!--                    <span id="user"></span>-->
<!--                    <span id="time"></span>-->
<!--                    <span id="message"></span>-->
<!--                </div>-->
<!---->
<!---->
<!---->
<!--            --><?php
//        }
//    }


    ?>

    <ul>
        <?php
        include "source/controller/db_controller.php";
        $messages = getAllMessages();
        $value = mysqli_fetch_array($messages)["message"];


        foreach ($messages as $message){
            echo "<span></span>";
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