
<?php
require_once '../config/confDBPDO.php'; //Archivo con configuracion de PDO
try {

    $miDB = new PDO(HOST, USER, PASSWORD);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = <<<EOD
use dbs4868791;
create user 'dbu2267458'@'%' IDENTIFIED BY 'daw2_Sauces';
grant all privileges on dbs4868791.* to 'dbu2267458'@'%';
CREATE TABLE IF NOT EXISTS T01_Usuario (
    T01_CodUsuario VARCHAR(8) PRIMARY KEY,
    T01_Password VARCHAR(255) NOT NULL,
    T01_DescUsuario VARCHAR(255) NOT NULL,
    T01_NumConexiones INT DEFAULT 0 NOT NULL,
    T01_FechaHoraUltimaConexionAnterior DATETIME NULL ,
    T01_Perfil enum('administrador', 'usuario') DEFAULT 'usuario', 
    T01_ImagenUsuario mediumblob
) ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS T02_Departamento (
    T02_CodDepartamento CHAR(3) PRIMARY KEY,
    T02_DescDepartamento VARCHAR(255) NOT NULL,
    T02_FechaCreacionDepartamento INT NOT NULL,
    T02_VolumenNegocio FLOAT NOT NULL,
    T02_FechaBajaDepartamento INT NULL
) ENGINE=INNODB; 
EOD;

    $miDB->exec($sql);
} catch (PDOException $excepcion) {//Codigo que se ejecuta si hay algun error
    $errorExcepcion = $excepcion->getCode(); //Obtengo el codigo del error y lo almaceno en la variable errorException
    $mensajeException = $excepcion->getMessage(); //Obtengo el mensaje del error y lo almaceno en la variable mensajeException

    echo "<p style='color: red'>Codigo del error: </p>" . $errorExcepcion; //Muestro el codigo del error
    echo "<p style='color: red'>Mensaje del error: </p>" . $mensajeException; //Muestro el mensaje del error
} finally {
    //Cierro la conexion
    unset($miDB);
}
?>