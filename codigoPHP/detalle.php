<?php
session_start();
if (!isset($_SESSION['usuarioDAW203LogInLogOut'])) {
    header('Location: LogIn.php');
}
if (!isset($_COOKIE['idioma'])) {
    setcookie("idioma", 'es', time() + 2592000); //Ponemos que el idioma sea español;
    header('Location: LogIn.php');
    exit;
}

if (isset($_REQUEST['idiomaSeleccionado'])) {
    setcookie("idioma", $_REQUEST['idiomaSeleccionado'], time() + 2592000); //Ponemos que el idioma sea español
    header('Location: LogIn.php');
    exit;
}

$aIdiomas = array(
    'es' => array(
        'bienvenido' => 'Bienvenido',
        'usuario' => 'Usuario',
        'pass' => 'Contraseña',
        'iniciar'=>'Iniciar Sesion',
        'volver'=>'Volver',
        'programa'=>'Programa',
        'detalles'=>'Detalles'
    ),
    'en' => array(
        'bienvenido' => 'Welcome',
        'usuario' => 'User',
        'pass' => 'Password',
        'iniciar' => 'Log In',
        'volver'=>'Close',
        'programa'=>'Program',
        'detalles'=>'Summary'
    )
);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>detalle</title>
        <link href="../webroot/css/style.css" rel="stylesheet"> 
    </head>
    <body>
        <header>
            <div class="titulo"><?php echo $aIdiomas[$_COOKIE['idioma']]['detalles']; ?></div>
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