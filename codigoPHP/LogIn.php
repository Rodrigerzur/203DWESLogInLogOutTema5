<?php
if (isset($_REQUEST['Volver'])) {
    header('Location:' . '../../../../203DWESProyectoTema5/indexProyectoTema5.php'); //Link al indexProyectoTema5
    exit;
}
if (!isset($_COOKIE['idioma'])) {
    setcookie("idioma", 'es', time() + 2592000); //Ponemos que el idioma sea español;
    header('Location: LogIn.php');
    exit;
}

if (isset($_REQUEST['registrarse'])) {
    header('Location: registro.php'); //Link al indexProyectoTema5
    exit;
}

if (isset($_REQUEST['idiomaElegido'])) {
    setcookie("idioma", $_REQUEST['idiomaElegido'], time() + 2592000); //Ponemos que el idioma sea español
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
        'registrarse'=>'Registrarme',
        'registrarse2'=>'¿No tienes cuenta?'
    ),
    'en' => array(
        'bienvenido' => 'Welcome',
        'usuario' => 'User',
        'pass' => 'Password',
        'iniciar' => 'Log In',
        'volver'=>'Close',
        'registrarse'=>'Sign In',
        'registrarse2'=>'New to this app?'
    )
);

require_once '../core/210322ValidacionFormularios.php';
require_once "../config/confDBPDO.php";

//Variables universales
define("OBLIGATORIO", 1);
define("OPCIONAL", 0);
$entradaOK = true;

/* Información del formulario */
$aFormulario = [
    'usuario' => '',
    'password' => ''
];

/**
 * Si se ha enviado el formulario.
 */
if (isset($_REQUEST['IniciarSesion'])) {
    $bEntradaOK = true;

//si el usuario o la contraseña no estan bien introducidos
    if (validacionFormularios::comprobarAlfaNumerico($_REQUEST['CodUsuario'], 8, 4, OBLIGATORIO) || validacionFormularios::comprobarAlfaNumerico($_REQUEST['Password'], 8, 4, OBLIGATORIO)) {
        $bEntradaOK = false;
    }

//si no encuentra ningun error pasa a hacer la validacion de campos
    if ($bEntradaOK) {
        $aFormulario['usuario'] = $_REQUEST['CodUsuario'];
        $aFormulario['password'] = $_REQUEST['Password'];
        try {
            /* Establecemos la connection con pdo */
            $miDB = new PDO(HOST, USER, PASSWORD);
            /* configurar las excepciones */
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Query de selección.
            $sSelect = <<<QUERY
                SELECT *  FROM T01_Usuario
                WHERE T01_CodUsuario='{$aFormulario['usuario']}' AND
                T01_Password=SHA2("{$aFormulario['usuario']}{$aFormulario['password']}", 256);
QUERY;

// Preparación y ejecución de la consulta.
            $oResultadoSelect = $miDB->prepare($sSelect);
            $oResultadoSelect->execute();
            $oResultado = $oResultadoSelect->fetchObject();
        } catch (PDOException $exception) {
//recarga el login si ocurre alguna excepcion
            header('Location: LogIn.php');
            exit;
        } finally {
            unset($miDB);
        }

//si el usuario o la contraseña no coinciden/existen en la base de datos
        if (!$oResultado) {
            $bEntradaOK = false;
        }
    }
}
//si no se ha enviado el formulario
else {
    $bEntradaOK = false;
}

//Solo si los datos introducidos son correctos
if ($bEntradaOK) {
//INICIA LA SESION
    session_start();

    try {
        /* Establecemos la connection con pdo */
        $miDB = new PDO(HOST, USER, PASSWORD);
        /* configurar las excepcion */
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fecha-hora actual.
        $oDateTime = new DateTime();

//Actualizacion en la BD.
        $sUpdate = <<<QUERY
            UPDATE T01_Usuario SET T01_NumConexiones=T01_NumConexiones+1,
            T01_FechaHoraUltimaConexionAnterior = '{$oDateTime->format("y-m-d h:i:s")}'
            WHERE T01_CodUsuario='{$aFormulario['usuario']}';
QUERY;

        $oUpdateBD = $miDB->prepare($sUpdate);
        $oUpdateBD->execute();
    } catch (PDOException $exception) {
//otra vez se recargara la pagina si sucede alguna excepcion
        header('Location: LogIn.php');
        exit;
    } finally {
        unset($miDB);
    }

// Variables de sesión para el usuario.
    $_SESSION['usuarioDAW203LogInLogOut'] = $aFormulario['usuario'];
    $_SESSION['FechaHoraUltimaConexionAnterior'] = $oResultado->T01_FechaHoraUltimaConexionAnterior;

// una vez finalizado el login se envia al usuario a la pagina del programa
    header('Location: programa.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>IndexLogInLogOut</title>
        <link href="../webroot/css/style.css" rel="stylesheet"> 
    </head>
    <body>
        <header>
            <div class="titulo"><?php echo $aIdiomas[$_COOKIE['idioma']]['iniciar']; ?></div><form name="formIdioma" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="formularioAlta">
            <button type="submit" name="idiomaElegido" value="es" style="background-color: transparent; border: 0px;"><img src="../webroot/media/español.png" width="35px" height="23.33px"></button>
            <button type="submit" name="idiomaElegido" value="en" style="background-color: transparent; border: 0px;"><img src="../webroot/media/ingles.png" width="35px" height="23.25px"></button>
        </form>
        </header>
        
        <main class="mainEditar">
            <div class="contenido">
                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="formularioAlta">
                    <h3 style="text-align: center;"><?php echo $aIdiomas[$_COOKIE['idioma']]['bienvenido']; ?></h3>
                    <br>
                    <div>
                        <label style="font-weight: bold;" class="CodigoDepartamento" for="CodUsuario"><?php echo $aIdiomas[$_COOKIE['idioma']]['usuario']; ?></label>
                        <input type="text" style="background-color: #D2D2D2" name="CodUsuario" value="<?php echo(isset($_REQUEST['CodUsuario']) ? $_REQUEST['CodUsuario'] : null); ?>">
                        <br><br> 
                        <label style="font-weight: bold;" class="DescripcionDepartamento" for="Password"><?php echo $aIdiomas[$_COOKIE['idioma']]['pass']; ?></label>
                        <input type="password" style="background-color: #D2D2D2" name="Password" value="<?php echo(isset($_REQUEST['Password']) ? $_REQUEST['Password'] : null); ?>">
                        <br><br>
                    </div>
                    <div>
                        <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['iniciar']; ?>" style="background-color: rgba(17, 188, 20, 0.8)" name="IniciarSesion" class="Aceptar">
                        <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['volver']; ?>" style="background-color: rgba(207, 16, 16, 0.8)" name="Volver" class="Aceptar">
                        <br><br>
                        <label style="font-weight: bold;" class="CodigoDepartamento" for="registrarse"><?php echo $aIdiomas[$_COOKIE['idioma']]['registrarse2']; ?></label>
                        <br>
                        <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['registrarse']; ?>" name="registrarse" style="background-color: rgba(0, 215, 230, 0.78);" class="Aceptar">
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>
