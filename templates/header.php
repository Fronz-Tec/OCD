<header>
    <?php
    include_once "source/controller/db_controller.php";

    session_start();

    if(isset($_SESSION["username"])){
    ?>

    <form action="source/controller/db_controller.php" method="post">
        <input type="hidden" name="logout" id="logout" value="true">
        <button onclick="this.form.submit()">Logout</button>
    </form>

    <?php
    }

    if(isAdmin()){
        ?>

        <a  href="http://localhost/OCD?site=admin"><button>Admin Panel</button></a>
    <?php
    }

    ?>

</header>