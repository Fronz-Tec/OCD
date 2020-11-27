<!Doctype html>
<html>

<?php
include "templates/head.php"
?>

<body>

    <?php
        include "templates/header.php";
        //Login stuff
    ?>
    <main>
        <h1 class="logTitle"><span class="ocdTitle">OCD</span> Registration</h1>

        <div class="formContainer">
            <form>
                <label for="username">Username: </label>
                <input type="text" id="username"><br>

                <label for="password">Password: </label>
                <input type="password" id="password"><br>

                <p><a href="#">Register</a> or <a href="#">Forgot Password</a></p>
            </form>
        </div>
    </main>


    <?php
        include "templates/footer.php"
    ?>
</body>

</html>
