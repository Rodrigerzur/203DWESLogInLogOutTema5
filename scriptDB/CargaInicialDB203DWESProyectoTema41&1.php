<?php
require_once '../config/confDBPDO.php'; //Archivo con configuracion de PDO
try {

    $miDB = new PDO(HOST, USER, PASSWORD);
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = <<<EOD
USE dbs4868791;
INSERT INTO T02_Departamento(T02_CodDepartamento,T02_DescDepartamento,T02_FechaCreacionDepartamento,T02_VolumenNegocio) VALUES 
('FOL', 'departamento FOL', 1406149672, 102.4),
('DAW', 'departamento DAW', 1406149672, 1000.3),
('DIW', 'departamento DIW', 1406149672, 289.3);

INSERT INTO T01_Usuario(T01_CodUsuario, T01_Password, T01_DescUsuario) VALUES
('albertoF',SHA2('albertoFpaso',256),'AlbertoF'),
('outmane',SHA2('outmanepaso',256),'Outmane'),
('rodrigo',SHA2('rodrigopaso',256),'Rodrigo'),
('isabel',SHA2('isabelpaso',256),'Isabel'),
('david',SHA2('davidpaso',256),'David'),
('aroa',SHA2('aroapaso',256),'Aroa'),
('johanna',SHA2('johannapaso',256),'Johanna'),
('oscar',SHA2('oscarpaso',256),'Oscar'),
('sonia',SHA2('soniapaso',256),'Sonia'),
('heraclio',SHA2('heracliopaso',256),'Heraclio'),
('amor',SHA2('amorpaso',256),'Amor'),
('antonio',SHA2('antoniopaso',256),'Antonio'),
('albertoB',SHA2('albertoBpaso',256),'AlbertoB');
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
