<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Post Array</title>
        <script>
        </script>
    </head>
    <body>
        <form action="" method="post">
            <?php
            for ($i = 0; $i < 5; $i++) {
                echo "<input type='text' value='$i' name='txt[]'> ";
            }
            echo "<br>";
            for ($i = 0; $i < 5; $i++) {
                $checked = isset($chk[$i]) ? "checked" : "";
                echo "<input type='checkbox' value='$i' name='chk[$i]' $checked> item " . $i;
            }
            ?>
            <br>
            <input type="submit">
        </form>
    </body>
</html>
