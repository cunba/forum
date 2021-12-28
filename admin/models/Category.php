<?php
require_once('connection/Connection.php');

class Category
{
    public $id;
    public $category;

    public function __construct($id, $category)
    {
        $this->id = $id;
        $this->category = $category;
    }

    public static function get_all_categories()
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM categories';

            $stmt = $connection->prepare($sql);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return 'No hay categorías';
            } else {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }

        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function count_topics($category_id)
    {
        try {
            $connection = Connection::Connection();

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
            return Connection::messages($e->getCode());
        }
    }

    public static function update_category($id, $category)
    {
        try {
            $connection = Connection::Connection();

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
            return Connection::messages($e->getCode());
        }
    }

    public static function create_category($category)
    {
        try {
            $connection = Connection::Connection();

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
            return Connection::messages($e->getCode());
        }
    }

    public static function delete_category($id)
    {
        try {
            $connection = Connection::Connection();

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
            return Connection::messages($e->getCode());
        }
    }
}