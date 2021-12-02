<?php
session_start();
if (!isset($_SESSION['usuarioDAW203LogInLogOut'])) {
    header('Location: LogIn.php');
}

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
        <title>Detalles</title>
        <link href="../webroot/css/style.css" rel="stylesheet"> 
    </head>
    <body>
        <header>
             <div class="titulo">PROGRAMA SESIÓN INICIADA</div>
        </header>
        <main class="mainEditar">
            <div class="contenido">
                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="formularioAlta">
                    <h3 style="text-align: center;">Bienvenido</h3>
                    <br>
                    <div class="mensajebienvenida">Has iniciado sesion como <span class="user"><?php echo $_SESSION['usuarioDAW203LogInLogOut'] ?></span>, esta es la <?php echo $oResultado->T01_NumConexiones ?>ª vez que te conectas.<?php
                if (!is_null($_SESSION['FechaHoraUltimaConexionAnterior'])) {
                    ?> <?php if(($oResultado->T01_NumConexiones) >1){echo'</br>La ultima conexion se realizo en ' ;}?>  
                        <?php
                    echo $_SESSION['FechaHoraUltimaConexionAnterior'];
                }else{
                        echo '</br>Se acaba de realizar la conexion a  ';echo ($oFechaHoraActual = new DateTime)->format('d-m-Y H:i:s');
                    }
                ?>.</div>
                    <div>
                        <input type="submit" value="Detalles" style="background-color: rgba(17, 188, 20, 0.8)" name="Detalles" class="Aceptar">
                        <input type="submit" value="LogOut" style="background-color: rgba(207, 16, 16, 0.8)" name="LogOut" class="Aceptar">
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>