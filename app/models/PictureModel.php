<?php

    namespace touristiamo\models;
    
    use touristiamo\Model as Model;
    use touristiamo\error\HttpError as HttpError;
    
    /**
     * The class has protected or private variables , so you can protect them. 
     * We will use the get methods to obtain the value and set methods to change the value.
     * However, our variables are public, because we use json. 
     * It will get the variable. If the variable is public, it won't get it.
     */
    class PictureModel extends Model
    {
        /**
         *
         * @var integer
         */
        public $id;
        
        /**
         *
         * @var string
         */
        public $image;
        
        /**
         *
         * @var integer
         */
        public $routeId;

        /**
         * 
         * @param integer $id
         */
        public function __construct( $id = null)
        {
            parent::__construct();	
            if ($id != null) 
            {
                $st = $this->connection->prepare('select id, image, routeId from pictures where id = :id');
                $st->bindParam(':id', $this->id, PDO::PARAM_INT);
                $st->execute();
                $rs = $st->fetch(PDO::FETCH_OBJ);

                //Insert value
                $this->id = $rs->id;
                $this->image = $rs->image;
                $this->routeId = $rs->routeId;
            }
        }

        /**
         * Save the model into database
         * @return boolean May be true or throw HttpError
         */
        public function save() 
        {
            try
            {
                $st = $this->connection->prepare('insert into pictures (id, image, routeId) values (:id, :image, :routeId)');
                $st->bindParam(':id', $this->id);
                $st->bindParam(':image', $this->image);
                $st->bindParam(':routeId', $this->routeId);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            } catch(\PDOException $e)
            {
                \HttpError::send(400, $e->getMessage());
            }
        }

        /**
         * Update model into data base
         * @return boolean
         */
        public function update()
        {
            try
            {
                $st = $this->connection->prepare('UPDATE pictures SET image = :image ,'
                        . 'routeId = :routeId where id = :id');
                $st->bindParam(':image', $this->image, \PDO::PARAM_STR);
                $st->bindParam(':routeId', $this->routeId, \PDO::PARAM_INT);
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            }catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }

        /**
         * delete model from database
         * @return boolean
         */
        public function delete()
        {
            try
            {
                $st = $this->connection->prepare('delete FROM pictures where id = :id');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            }catch(\PDOException $e)
            {
                \HttpError::send(400, $e->getMessage());
            }
        }	
    }