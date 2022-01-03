<?php
require_once('connection/Connection_admin.php');

class Topic_admin
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
            $connection = Connection_admin::Connection();

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
            return Connection_admin::messages($e->getCode());
        }
    }

    public static function get_by_category($category_id)
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

            if ($stmt->rowCount() == 0) {
                return false;
            } else {
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            return Connection_admin::messages($e->getCode());
        }
    }

    public static function get_first($category_id)
    {
        try {
            $connection = Connection_admin::Connection();

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
            return Connection_admin::messages($e->getCode());
        }
    }

    public static function count_comments($topic_id)
    {
        try {
            $connection = Connection_admin::Connection();

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
            return Connection_admin::messages($e->getCode());
        }
    }

    public static function update($topic)
    {
        try {
            $connection = Connection_admin::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'UPDATE topics SET topic = :topic, category_id = :category_id WHERE id = :id';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':topic' => $topic->topic,
                ':category_id' => $topic->category_id,
                ':id' => $topic->id
            ));

            return true;

        } catch (PDOException $e) {
            return Connection_admin::messages($e->getCode());
        }
    }

    public static function create($topic)
    {
        try {
            $connection = Connection_admin::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'INSERT INTO topics (topic, category_id) VALUES (:topic, :category_id)';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':topic' => $topic->topic,
                ':category_id' => $topic->category_id
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

            $sql = 'DELETE FROM topics WHERE id = :id';

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