<?php

class Comment
{
    public $id;
    public $comment;
    public $topic_id;
    public $user_id;

    public function __construct($comment, $topic_id, $user_id)
    {
        $this->comment = $comment;
        $this->topic_id = $topic_id;
        $this->user_id = $user_id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public static function get_by_topic($topic_id)
    {
        try {
            $connection = Connection::Connection();

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
            return Connection::messages($e->getCode());
        }
    }

    public static function create($comment)
    {
        try {
            $connection = Connection::Connection();

            if (gettype($connection) == 'string') {
                return $connection;
            }

            $sql = 'INSERT INTO comments (comment, topic_id, user_id) VALUES (:comment, :topic_id, :user_id)';

            $stmt = $connection->prepare($sql);
            $stmt->execute(array(
                ':comment' => $comment->comment,
                ':topic_id' => $comment->topic_id,
                ':user_id' => $comment->user_id
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

            $sql = 'DELETE FROM comments WHERE id = :id';

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