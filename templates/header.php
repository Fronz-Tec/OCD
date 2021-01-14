<header>
    <?php

    session_start();

    if(isset($_SESSION["username"])){
    ?>

    <form action="source/controller/db_controller.php" method="post">
        <input type="hidden" name="logout" id="logout" value="true">
        <button onclick="this.form.submit()">Logout</button>
    </form>

    <?php
    }


    ?>

</header>