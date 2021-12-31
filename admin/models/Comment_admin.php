<?php
require_once('connection/Connection_admin.php');

class Comment_admin
{
    public $id;
    public $comment;
    public $topic_id;

    public function __construct($comment, $topic_id)
    {
        $this->comment = $comment;
        $this->topic_id = $topic_id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public static function get_by_topic($topic_id)
    {
        try {
            $connection = Connection_admin::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'SELECT * FROM comments WHERE topic_id = :topic_id ORDER BY creation_date DESC';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':topic_id' => $topic_id
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

    public static function create($comment)
    {
        try {
            $connection = Connection_admin::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'INSERT INTO comments (comment, topic_id, user_id) VALUES (:comment, :topic_id, 1)';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':comment' => $comment->comment,
                ':topic_id' => $comment->topic_id
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

            $sql = 'DELETE FROM comments WHERE id = :id';

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