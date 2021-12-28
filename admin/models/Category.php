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
            }
            return $stmt->fetchAll(PDO::FETCH_OBJ);

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
}