<?php

    namespace touristiamo\models;
    
    use touristiamo\error\HttpError as HttpError;
    use touristiamo\Model as Model;

    /**
     *  The class has protected or private variables , so you can protect them. 
     *  We will use the get methods to obtain the value and set methods to change the value.  
     *  However, our variables are public, because we use json. It will get the variable. 
     *  If the variable is public, it won't get it.
     */
    class CityModel extends Model {
        
        /**
         *
         * @var Integer
         */
        public $id;
        
        /**
         *
         * @var String
         */
        public $name;
        
        /**
         *
         * @var Integer
         */
        public $countryId;


        /**
         * 
         * @param Integer $id
         */
        public function __construct($id = null)
        {
            parent::__construct();	
            if ($id != null) 
            {
                $st = $this->connection->prepare('select id, name, countryId from city where id = :id');
                $st->bindParam(':id', $id, \PDO::PARAM_INT);
                $st->execute();
                $rs = $st->fetch(\PDO::FETCH_OBJ);

                // Load values into model
                $this->id = $rs->id;	
                $this->name = $rs->name;
                $this->countryId = $rs->countryId;
            }

        }


        /**
         * Save model into database
         * @return Bool May be true or throw HttpError
         */
        public function save() 
        {
            try
            {
                $st = $this->connection->prepare('insert into city (id, name, countryId) values (:id, :name, :countryId)');
                $st->bindParam(':id', $this->id);
                $st->bindParam(':name', $this->name);
                $st->bindParam(':countryId', $this->countryId);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            } catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }
        
        /**
         * Update the data base with the current values in the memory
         * @return Bool May be true or throw HttpError
         */
        public function update()
        {
            try
            {
                $st = $this->connection->prepare('update FROM city '
                        . 'set name = :name, '
                        . 'countryId = :countryId '
                        . 'where id = :id');
                $st->bindParam(':id', $this->id);
                $st->bindParam(':name', $this->name, \PDO::PARAM_STR);
                $st->bindParam(':countryId', $this->countryId, \PDO::PARAM_INT);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            }catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }


        /**
         * Delete model in BD that is equal than this object
         * @return Bool May be true or throw HttpError
         */
        public function delete()
        {
            try
            {
                $st = $this->connection->prepare('DELETE FROM city where id = :id');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            }catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }	

    }