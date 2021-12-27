<?php
include('connection/Connection.php');

class Category
{
    public $id;
    public $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function get_category()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function set_category($category)
    {
        $this->category = $category;
    }

    public function get_all_categories()
    {
        try {

        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
//        try{
//            $password = self::cryptconmd5($password);
//            $conexion = Conectar::Conexion();
//
//            //Si $conexion es de tipo String, es porque se produjo una excepción. Para la ejecución de la función devolviendo el mensaje de la excepción.
//            if(gettype($conexion) == "string"){
//                return $conexion;
//            }
//
//            $sql = "SELECT USUARIO, NOMBRE, APELLIDO, EMAIL FROM USUARIOS WHERE USUARIO=:usuario AND PASSWORD=:password";
//            $respuesta = $conexion->prepare($sql);
//            $respuesta->execute(array(':usuario'=>$alias, ':password'=>$password));
//            $respuesta = $respuesta->fetch(PDO::FETCH_ASSOC);
//
//            // Si el array no está vacío, crea y devuelve un objeto Usuario.
//            if($respuesta){
//                $usuario = new Usuarios_modelo($respuesta["USUARIO"], $respuesta["NOMBRE"], $respuesta["APELLIDO"], $respuesta["EMAIL"]);
//                return $usuario;
//            }else{
//                return $usuario = null;
//            }
//            $respuesta->closeCursor();
//            $conexion = null;
//
//        }catch(PDOException $e){
//            return Conectar::mensajes($e->getCode());
//        }
    }
}