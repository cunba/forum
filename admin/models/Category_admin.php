<?php
require_once('connection/Connection_admin.php');

class Category_admin
{
    public $id;
    public $category;

    public function __construct($id, $category)
    {
        $this->id = $id;
        $this->category = $category;
    }

    public static function get_all()
    {
        try {
            $connection = Connection_admin::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM categories';

            $stmt = $connection->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return false;
            } else {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }

        } catch (PDOException $e) {
            return Connection_admin::messages($e->getCode());
        }
    }

    public static function count_topics($category_id)
    {
        try {
            $connection = Connection_admin::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM topics WHERE category_id = :category_id';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':category_id' => $category_id
            ));
            return $stmt->rowCount();

        } catch (PDOException $e) {
            return Connection_admin::messages($e->getCode());
        }
    }

    public static function update($id, $category)
    {
        try {
            $connection = Connection_admin::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'UPDATE categories SET category = :category WHERE id = :id';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':category' => $category,
                ':id' => $id
            ));

            return true;

        } catch (PDOException $e) {
            return Connection_admin::messages($e->getCode());
        }
    }

    public static function create($category)
    {
        try {
            $connection = Connection_admin::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'INSERT INTO categories (category) VALUES (:category)';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':category' => $category
            ));

            return true;

        } catch (PDOException $e) {
            return Connection_admin::messages($e->getCode());
        }
    }

    public static function delete($id)
    {
        try {
            $connection = Connection_admin::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'DELETE FROM categories WHERE id = :id';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':id' => $id
            ));

            return true;

        } catch (PDOException $e) {
            return Connection_admin::messages($e->getCode());
        }
    }
}