<!Doctype html>
<html>

<?php
include "templates/head.php"
?>

<body>

    <?php
        include "templates/header.php";
        //registration stuff
    ?>

    <main>
        <h1 class="logTitle"><span class="ocdTitle">OCD</span> Registration</h1>

        <div class="formContainer">
            <form method="post" action="source/controller/db_controller.php">

                <div class="row">

                    <div class="col"></div>

                    <div class="col">
                        <label for="username">Username: </label>
                    </div>

                    <div class="col">
                        <input type="text" id="username" name="username" required><br>
                    </div>

                    <div class="col"></div>
                </div>

                <div class="row">

                    <div class="col"></div>

                    <div class="col">
                        <label for="email">Email: </label>
                    </div>

                    <div class="col">
                        <input type="email" id="email" name="email" required><br>
                    </div>

                    <div class="col"></div>
                </div>

                <div class="row">

                    <div class="col"></div>

                    <div class="col">
                        <label for="password">Password: </label>
                    </div>

                    <div class="col">
                        <input type="password" id="password" name="password" required><br>
                    </div>

                    <div class="col"></div>
                </div>

<!--                <div class="row">-->
<!---->
<!--                    <div class="col"></div>-->
<!---->
<!--                    <div class="col">-->
<!--                        <label for="password-confirm">Confirm Password: </label>-->
<!--                    </div>-->
<!---->
<!--                    <div class="col">-->
<!--                        <input type="password" id="password-confirm" required><br>-->
<!--                    </div>-->
<!---->
<!--                    <div class="col"></div>-->
<!--                </div>-->

                <button type="submit">
                    Register
                </button>



            </form>
        </div>
    </main>


    <?
    include "templates/footer.php"
    ?>
</body>

</html>
