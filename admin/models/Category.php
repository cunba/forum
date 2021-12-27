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
            } else {
                $sql = 'SELECT * FROM categories';

                $response = $connection->prepare($sql);
                $response->execute();
                return $response->fetchAll(PDO::FETCH_CLASS, 'Category');
            }
        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }
}