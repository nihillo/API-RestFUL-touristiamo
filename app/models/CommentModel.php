<?php
    
namespace touristiamo\models;

use touristiamo\Model as Model;
use touristiamo\error\HttpError;
use touristiamo\exception\BDException as BDException;

/**
 * The class has protected or private variables , so you can protect them. 
 * We will use the get methods to obtain the value and set methods to change the value. 
 * However, our variables are public, because we use json. It will get the variable. 
 * If the variable is public, it won't get it.
 */
class CommentModel extends Model
{
    /**
     *
     * @var Integer
     */
    public $id;

    /**
     *
     * @var String
     */
    public $comment;

    /**
     *
     * @var Integer 
     */
    public $score;

    /**
     *
     * @var Integer 
     */
    public $routeId;

    /**
     *
     * @var Integer 
     */
    public $userId;

    /**
     *
     * @var \DateTime 
     */
    public $date;


    /**
     * Create a model from the data base
     * @param integer $id
     * @return boolean
     * @throws BDException
     */
    public function __construct( $id = null){

        parent::__construct();	

        if ($id != null) 
        {
            $st = $this->connection->prepare('select id, comentary, score, date, routeId, userId from comments where id = :id');
            $st->bindParam(':id', $id, \PDO::PARAM_INT);
            if (!$st->execute()) 
            {
                throw new BDException($st->errorInfo());
            }
            if ( !($rs = $st->fetch(\PDO::FETCH_OBJ)))
            {
                throw new BDException('Not exist any '. get_class($this). ' with id '. $id);
            }

            //Insert value
            $this->id = $rs->id;
            $this->comment = $rs->comentary;
            $this->score = $rs->score;
            $this->date = $rs->date;
            $this->routeId = $rs->routeId;
            $this->userId = $rs->userId;
        }
    }


    /**
     * This method sve into data base the current values.
     * @return boolean
     * @throws BDException
     */
    public function save() 
    {
        try
        {
            $st = $this->connection->prepare('insert into comments (comentary, score, date, routeId, userId) 
            values (:comment, :score, :date, :routeId, :userId)');
            $st->bindParam(':comment', $this->comment, \PDO::PARAM_STR);
            $st->bindParam(':score', $this->score, \PDO::PARAM_INT);
            $st->bindParam(':routeId', $this->routeId, \PDO::PARAM_INT);
            $st->bindParam(':userId', $this->userId, \PDO::PARAM_INT);
            $st->bindParam(':date', $this->date, \PDO::PARAM_STR);
            if (!$st->execute()) 
            {
                throw new BDException($st->errorInfo());
            }
            return true;
        }catch(\PDOException $e)
        {
            HttpError::send(400, $e->getMessage());
        }
    }


    /**
     * Update the model from the data base
     * @return boolean
     * @throws BDException
     */
    public function update()
    {
        try
        {
            $st = $this->connection->prepare('update comments set comentary = :comment, '
                    . 'score = :score, date = :date, routeId = :routeId, '
                    . 'userId = :userId where id = :id');
            $st->bindParam(':comment', $this->comment, \PDO::PARAM_STR);
            $st->bindParam(':score', $this->score, \PDO::PARAM_INT);
            $st->bindParam(':date', $this->date, \PDO::PARAM_STR);
            $st->bindParam(':routeId', $this->routeId, \PDO::PARAM_INT);
            $st->bindParam(':userId', $this->userId, \PDO::PARAM_INT);
            $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
            if (!$st->execute()) 
            {
                throw new BDException($st->errorInfo());
            }
            return true;
        }catch(\PDOException $e)
        {
            HttpError::send(400, $e->getMessage());
        }
    }


    /**
     * Delete the model from the database
     * @return boolean
     * @throws BDException
     */
    public function delete()
    {
        try
        {
            $st = $this->connection->prepare('DELETE FROM comments where id = :id');
            $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
            if (!$st->execute()) 
            {
                throw new BDException($st->errorInfo());
            }
            return true;
        }catch(\PDOException $e)
        {
            HttpError::send(400, $e->getMessage());
        }
    }	

    /**
     * Return all comments by user
     * @param integer $userId
     * @return array Return an object array or false if there is a fail
     * @throws BDException
     */
    public function getAllByUserId($userId)
    {
        try
        {
            $st = $this->connection->prepare('select id, comentary, score, '
                    . 'date, routeId, userId from comments where userId = :userId');
            $st->bindParam(':userId', $userId, \PDO::PARAM_INT);
            if (!$st->execute()) 
            {
                throw new BDException($st->errorInfo());
            }

            return $st->fetchAll(\PDO::FETCH_OBJ);
        }catch(\PDOException $e)
        {
            HttpError::send(400, $e->getMessage());
        }
    }

    /**
     * Return all comments by route
     * @param integer $routeId
     * @return array Return an object array or false if there is a fail
     * @throws BDException
     */
    public function getAllByRouteId($routeId)
    {
        try
        {
            $st = $this->connection->prepare('select id, comentary, score, '
                    . 'date, routeId, userId from comments where routeId = :routeId');
            $st->bindParam(':routeId', $routeId, \PDO::PARAM_INT);
            if (!$st->execute()) 
            {
                throw new BDException($st->errorInfo());
            }

            return $st->fetchAll(\PDO::FETCH_OBJ);
        }catch(\PDOException $e)
        {
            HttpError::send(400, $e->getMessage());
        }
    }

    /**
     * Delete all comments from a route.
     * @param integer $routeId
     * @return boolean
     * @throws BDException
     */
    public function deleteAllByRouteId($routeId)
    {
        try 
        {
            $st = $this->connection->prepare('delete from comments where routeId = :routeId');
            $st->bindParam(':routeId', $routeId, \PDO::PARAM_INT);
            if (!$st->execute()) 
            {
                throw new BDException($st->errorInfo());
            }
            return true;
        } catch (\PDOException $e) 
        {
            HttpError::send(400, $e->getMessage());
        }
    }
}
