<?php
session_start();
if (!isset($_SESSION['usuarioDAW203LogInLogOut'])) {
    header('Location: LogIn.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Programa</title>
        <link href="../webroot/css/style.css" rel="stylesheet"> 
    </head>
    <body>
        <header>
            <div class="titulo">DETALLES</div>
        </header>
        <main>
            <div class="mainDetalles">
            <h2>$_SESSION</h2>
            <table>
                <?php
                foreach ($_SESSION as $key => $value) {
                    echo '<tr>';
                    echo "<td>$key:  </td>";
                    echo "<td> $value</td>";
                    echo '</tr>';
                }
                ?>
            </table>
            <h2>$_COOKIE</h2>
            <table>
                <?php
                foreach ($_COOKIE as $key => $value) {
                    echo '<tr>';
                    echo "<td>$key:</td>";
                    echo "<td> $value</td>";
                    echo '</tr>';
                }
                ?>
            </table>
            <h2>$_SERVER</h2>
            <table>
                <?php
                foreach ($_SERVER as $key => $value) {
                    echo '<tr>';
                    echo "<td>$key:</td>";
                    echo "<td> $value</td>";
                    echo '</tr>';
                }
                ?>
            </table>  
            </div>
        </main>
    </body>
</html>