
<div>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>

        <?php
        include_once "source/controller/db_controller.php";
        $users = getAllUsers();

        foreach ($users as $user){
            echo "<tr>
            <td>".$user[0]."</td>"

        ?>
            <td>
                <form action="source/controller/db_controller.php" method="post">
                    <input type="hidden" name="user" id="user" value="<?php echo $user[0] ?>">
                    <select id="role" name="role">
                        <option value="1" <?php if($user[1]=="admin"){echo"selected='selected'";} ?>>Admin</option>
                        <option value="5" <?php if($user[1]=="chatter"){echo"selected='selected'";} ?>>Chatter</option>
                        <option value="6" <?php if($user[1]=="newbie"){echo"selected='selected'";} ?>>Newbie</option>
                    </select>
                    <button type="submit">Save</button>
                </form>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>

</div>
<!--<div class="chatBoxContainer">-->
<!--    <form action="source/controller/db_controller.php" method="post">-->
<!--        <textarea class="textInput" id="newMessage" name="newMessage"></textarea>-->
<!--        <button type="submit">Send</button>-->
<!--    </form>-->
<!---->
<!---->
<!--</div>-->