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
    class CountryModel extends Model
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
        public $name;

        /**
         * 
         * @param Integer $id
         */
        public function __construct($id = null)
        {
            parent::__construct();	

            if ($id != null) 
            {
                $st = $this->connection->prepare('select id, name from country where id = :id');
                $st->bindParam(':id', $this->id, PDO::PARAM_INT);
                $st->execute();
                $rs = $st->fetch(PDO::FETCH_OBJ);

                //Insert value
                $this->id = $rs->id;
                $this->name = $rs->name;
            }
        }

        /**
         * Save model into database
         * @return boolean May be true or throw HttpError
         */
        public function save() 
        {
            try
            {
                $st = $this->connection->prepare('insert into country (id, name) values (:id, :name)');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                $st->bindParam(':name', $this->name, \PDO::PARAM_INT);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            }catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }

        /**
         * Update model into database
         * @return boolean May be true or throw HttpError
         */
        public function update(){

            try 
            {
                $st = $this->connection->prepare('UPDATE Country SET name=:name where id = :id');
                $st->bindParam(':name', $this->name, \PDO::PARAM_STR);
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            }catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }

        /**
         * Delete model from database
         * @return boolean May be true or throw HttpError
         */
        public function delete()
        {
            try
            {
                $st = $this->connection->prepare('DELETE FROM country where id = :id');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            }catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }
    }