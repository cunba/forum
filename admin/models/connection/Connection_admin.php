<?php

require_once('db-properties-admin.php');

class Connection_admin
{
    public function __construct()
    {
    }

    public static function Connection()
    {
        try {
            $dsn = 'mysql:host=' . HOST . '; dbname=' . DBNAME;
            $connection = new PDO($dsn, USER, PASSWORD, array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));
            return $connection;
        } catch (PDOException $e) {
            return self::messages($e->getCode());
        }
    }

    public static function messages($e)
    {
        switch ($e) {
            case "2002":
                return "<p class='error'>Error al conectar!! El host es incorrecto: (" . $e . ")</p>";
                break;
            case "1049":
                return "<p class='error'>Error al conectar!! No se encuentra la Base de datos: (" . $e . ")</p>";
                break;
            case "42000":
            case "1045":
                return "<p class='error'>Error al conectar!! Usuario y/o Contraseña incorrecta: (" . $e . ")</p>";
                break;
            case "42S02":
                return "<p class='error'>Error en la consulta!! No se encuentra la Tabla en la DDBB: (" . $e . ")</p>";
                break;
            case "23000":
                return "<p class='error'>Ya existe el usuario. Prueba con otro alias (" . $e . ")</p>";
                break;
            default:
                return "<p class='error'>Error al conectar!! ERROR INESPERADO " . $e . "</p>";
        }
    }
}