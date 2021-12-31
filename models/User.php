<?php
require_once('connection/Connection.php');

class User
{
    public $id;
    public $user;
    public $name;
    public $surname;
    public $email;
    public $password;

    public function __construct($user, $name, $surname, $email, $password)
    {
        $this->user = $user;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public static function get_user($user, $password)
    {
        try {
            $password = self::cryptconmd5($password);
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM users WHERE user = :user AND password = :password';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':user' => $user,
                ':password' => $password
            ));

            if ($stmt->rowCount() == 0) {
                return false;
            } else {
                return $stmt->fetch(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function get_by_id($id)
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM users WHERE id = :id';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':id' => $id
            ));

            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function get_by_user($user)
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM users WHERE user = :user';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':user' => $user
            ));

            if ($stmt->rowCount() == 0) {
                return false;
            } else {
                return true;
            }

        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function get_id_by_user($user) {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM users WHERE user = :user';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':user' => $user
            ));

            if ($stmt->rowCount() == 0) {
                return false;
            } else {
                return $stmt->fetch(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function get_all()
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM users WHERE user <> "admin" AND user <> "comments_admin"';

            $stmt = $connection->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return false;
            } else {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }

        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function count_comments($user_id)
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM comments WHERE user_id = :user_id';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':user_id' => $user_id
            ));
            return $stmt->rowCount();

        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function create($user)
    {
        try {
            $password = self::cryptconmd5($user->password);
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'INSERT INTO users (user, name, surname, email, password, birthday) VALUES (:user, :name, :surname, :email, :password, :birthday)';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':user' => $user->user,
                ':name' => $user->name,
                ':surname' => $user->surname,
                ':email' => $user->email,
                ':password' => $password,
                ':birthday' => $user->birthday
            ));

            return true;
        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function update_password($id, $password)
    {
        try {
            $password = self::cryptconmd5($password);
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'UPDATE users SET password = :password WHERE id = :id';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':password' => $password,
                ':id' => $id
            ));

            return true;

        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function delete($id)
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'DELETE FROM users WHERE id = :id';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':id' => $id
            ));

            return true;

        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function cryptconmd5($password)
    {
        //Crea un salt
        $salt = md5($password . "%*4!#$;.k~’(_@");
        $password = md5($salt . $password . $salt);
        return $password;
    }
}