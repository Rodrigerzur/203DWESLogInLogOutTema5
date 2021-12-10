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

if (isset($_REQUEST['editarPerfil'])) {
    header('Location: editarPerfil.php');
    exit;
}

if (isset($_REQUEST['idiomaSeleccionado'])) {
    setcookie("idioma", $_REQUEST['idiomaSeleccionado'], time() + 2592000);
    header('Location: LogIn.php');
    exit;
}

$aIdiomas = array(
    'es' => array(
        'bienvenido' => 'Bienvenido',
        'usuario' => 'Usuario',
        'pass' => 'Contraseña',
        'iniciar' => 'Iniciar Sesion',
        'volver' => 'Volver',
        'programa' => 'Interfaz con sesion iniciada',
        'detalles' => 'Detalles',
        'logout' => 'Cerrar Sesion',
        '1mensaje' => 'Has iniciado sesion como ' . $_SESSION['usuarioDAW203LogInLogOut'],
        '2mensaje' => 'Esta es tu conexion nº ',
        '3mensaje' => 'La ultima conexion se realizo en ',
        '4mensaje' => 'Fecha-Hora de la primera conexion:   ',
        'editarPerfil' => 'Editar Perfil'
    ),
    'en' => array(
        'bienvenido' => 'Welcome',
        'usuario' => 'User',
        'pass' => 'Password',
        'iniciar' => 'Log In',
        'volver' => 'Close',
        'programa' => '"Logged In" interface',
        'detalles' => 'Summary',
        'logout' => 'Log Out',
        '1mensaje' => 'You are logged in as ' . $_SESSION['usuarioDAW203LogInLogOut'],
        '2mensaje' => 'This is your log in nº ',
        '3mensaje' => 'Last connection was made on ',
        '4mensaje' => 'First connection was made on   ',
        'editarPerfil' => 'Edit Profile'
    )
);
// Si se selecciona cerrar sesión, se cierra y destruye, y vuelve a la página de login.
if (isset($_REQUEST['LogOut'])) {
    session_unset();
    session_destroy();
    header("Location: LogIn.php");
    exit;
}

// Cuando se pulse el boton de detalles
if (isset($_REQUEST['Detalles'])) {
    header("Location: detalle.php");
    exit;
}

require_once "../config/confDBPDO.php";

try {
    /* Establecemos la connection con pdo */
    $miDB = new PDO(HOST, USER, PASSWORD);
    /* configurar las excepcion */
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = <<<QUERY
        SELECT T01_NumConexiones FROM T01_Usuario
        WHERE T01_CodUsuario='{$_SESSION['usuarioDAW203LogInLogOut']}';
    QUERY;

    $oSelect = $miDB->prepare($sql);
    $oSelect->execute();

    $oResultado = $oSelect->fetchObject();
} catch (PDOException $exception) {
    echo '<div>Se han encontrado errores:</div><ul>';
    echo '<li>' . $exception->getCode() . ' : ' . $exception->getMessage() . '</li>';
    echo '</ul>';
} finally {
    unset($miDB);
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
            <div class="titulo"><?php echo $aIdiomas[$_COOKIE['idioma']]['programa']; ?></div>
        </header>
        <main class="mainEditar">
            <div class="contenido">
                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="formularioAlta">
                    <h3 style="text-align: center;"><?php echo $aIdiomas[$_COOKIE['idioma']]['bienvenido']; ?></h3>
                    <br>
                    <div class="mensajebienvenida">
                        <?php echo $aIdiomas[$_COOKIE['idioma']]['1mensaje']; ?>.</br>
                        <?php echo $aIdiomas[$_COOKIE['idioma']]['2mensaje']; ?><?php echo $oResultado->T01_NumConexiones ?>.</br>
                        <?php
                        if (!is_null($_SESSION['FechaHoraUltimaConexion'])) {
                            ?> <?php if (($oResultado->T01_NumConexiones) > 1) {
                            echo $aIdiomas[$_COOKIE['idioma']]['3mensaje'];
                        }
                            ?>  
                            <?php
                            echo $_SESSION['FechaHoraUltimaConexion'];
                        } else {
                            echo $aIdiomas[$_COOKIE['idioma']]['4mensaje'];
                            echo ($oFechaHoraActual = new DateTime)->format('d-m-Y H:i:s');
                        }
                        ?>.
                    </div>

                    <div>
                        <input type="submit" style="background-color: rgba(242, 231, 87, 0.78);" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['editarPerfil']; ?>" name="editarPerfil" class="Aceptar">
                        </br></br>
                        <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['detalles']; ?>" style="background-color: rgba(17, 188, 20, 0.8)" name="Detalles" class="Aceptar">
                        <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['logout']; ?>" style="background-color: rgba(207, 16, 16, 0.8)" name="LogOut" class="Aceptar">
                    </div>
                </form>
            </div>
        </main>
        <footer>
            <div><a href="https://daw203.ieslossauces.es/index.php">Rodrigo Geras Zurrón</a></div><div><a href="https://github.com/Rodrigerzur/203DWESLogInLogOutTema5">Github</a></div>
        </footer>
    </body>
</html>