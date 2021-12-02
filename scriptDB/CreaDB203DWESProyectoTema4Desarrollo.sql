/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  daw2
 * Created: 4 nov. 2021
 */

create database DB203DWESProyectoTema4;
use DB203DWESProyectoTema4;

create user 'User203DWESProyectoTema4'@'%' IDENTIFIED BY 'P@ssw0rd';
grant all privileges on DB203DWESProyectoTema4.* to 'User203DWESProyectoTema4'@'%';

CREATE TABLE IF NOT EXISTS T01_Usuario (
    T01_CodUsuario VARCHAR(8) PRIMARY KEY,
    T01_Password VARCHAR(255) NOT NULL,
    T01_DescUsuario VARCHAR(255) NOT NULL,
    T01_NumConexiones INT DEFAULT 0 NOT NULL,
    T01_FechaHoraUltimaConexionAnterior DATETIME NULL ,
    T01_Perfil enum('administrador', 'usuario') DEFAULT 'usuario', 
    T01_ImagenUsuario mediumblob
) ENGINE=INNODB;

/* Creamos la tabla Departamento*/

CREATE TABLE IF NOT EXISTS T02_Departamento (
    T02_CodDepartamento CHAR(3) PRIMARY KEY,
    T02_DescDepartamento VARCHAR(255) NOT NULL,
    T02_FechaCreacionDepartamento INT NOT NULL,
    T02_VolumenNegocio FLOAT NOT NULL,
    T02_FechaBajaDepartamento INT NULL
) ENGINE=INNODB;