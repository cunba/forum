<?php

class Topic
{
    public $id;
    public $topic;
    public $category_id;

    public function __construct($topic, $category_id)
    {
        $this->topic = $topic;
        $this->category_id = $category_id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public static function get_all()
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM topics';

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

    public static function get_by_category($category_id)
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

            if ($stmt->rowCount() == 0) {
                return false;
            } else {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function count_comments($topic_id)
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM comments WHERE topic_id = :topic_id';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':topic_id' => $topic_id
            ));
            return $stmt->rowCount();

        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function get_by_user($category_id, $user_id)
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM topics WHERE category_id = :category_id AND id IN (SELECT topic_id FROM comments WHERE user_id = :user_id)';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':category_id' => $category_id,
                ':user_id' => $user_id
            ));

            if ($stmt->rowCount() == 0) {
                return false;
            } else {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }

        } catch (PDOException $e) {
            return Connection::messages($e->getCode());
        }
    }

    public static function get_first($category_id)
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM topics WHERE category_id = :category_id ORDER BY id ASC LIMIT 1';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':category_id' => $category_id
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
}