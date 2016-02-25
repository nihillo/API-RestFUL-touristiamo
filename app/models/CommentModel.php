<?php
    
    namespace touristiamo\models;
    
    use touristiamo\Model as Model;
    use touristiamo\error\HttpError;
    
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
        public $userID;
        
        /**
         *
         * @var \DateTime 
         */
        public $date;

        
        /**
         * 
         * @param integer $id
         * @throws BDException
         */
        public function __construct( $id = null){

            parent::__construct();	

            if ($id != null) 
            {
                $st = $this->connection->prepare('select id, comment, score, date, routeId, userId from Comment where id = :id');
                $st->bindParam(':id', $id, \PDO::PARAM_INT);
                if (!$st->execute()) 
                {
                    throw new BDException($st->errorInfo());
                }
                $rs = $st->fetch(\PDO::FETCH_OBJ);

                //Insert value
                $this->id = $rs->id;
                $this->comment = $rs->comment;
                $this->score = $rs->score;
                $this->date = $rs->date;
                $this->routeId = $rs->routeId;
                $this->userId = $rs->userId;
            }
        }

        
        /**
         * This method sve into data base the current values.
         * @return Bool May be true or throw HttpError
         */
        public function save() 
        {
            try
            {
                $st = $this->conexion->prepare('insert into comment (id, comment, score, date, routeId, userId) 
                values (:id, :comment, :score, :routeId, :userId, :day)');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                $st->bindParam(':comment', $this->comment, \PDO::PARAM_STR);
                $st->bindParam(':score', $this->score, \PDO::PARAM_INT);
                $st->bindParam(':routeId', $this->routeId, \PDO::PARAM_INT);
                $st->bindParam(':userId', $this->userId, \PDO::PARAM_INT);
                $st->bindParam(':date', $this->date, \PDO::PARAM_STR);
                if (!$st->execute()) 
                {
                    throw new BDException($st->errorInfo());
                }
                
            }catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
}


        /**
         * Update data with current values in this model.
         * @return Bool May be true or throw HttpError
         */
        public function update()
        {
            try
            {
                $st = $this->connection->prepare('update comments set comment = :comment, '
                        . 'score = :score, date = :date, routeId = :routeId, '
                        . 'userId = :userId where id = :id');
                $st->bindParam(':comment', $this->comment, \PDO::PARAM_STR);
                $st->bindParam(':score', $this->score, \PDO::PARAM_INT);
                $st->bindParam(':date', $this->date, \PDO::PARAM_STR);
                $st->bindParam(':routeId', $this->routeId, \PDO::PARAM_INT);
                $st->bindParam(':userId', $this->userID, \PDO::PARAM_INT);
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                if (!$st->execute()) 
                {
                    throw new BDException($st->errorInfo());
                }
            }catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }


        /**
         * This method delete this model from the data base.
         * @return Bool May be true or throw HttpError
         */
        public function delete(){

            try
            {
                $st = $this->connection->prepare('DELETE FROM comment where id = :id');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                if (!$st->execute()) 
                {
                    throw new BDException($st->errorInfo());
                }
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
    }
