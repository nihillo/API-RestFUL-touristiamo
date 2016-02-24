<?php
    
    namespace touristiamo\models;
    
    use touristiamo\Model as Model;
    use touristiamo\error\HttpError as HttpError;

    /**
     * The class has protected or private variables , so you can protect them. 
     * We will use the get methods to obtain the value and set methods to change the value. 
     * However, our variables are public, because we use json. It will get the variable. 
     * If the variable is public, it won't get it.
     */
    class Route extends Model
    {
        /**
         *
         * @var integer 
         */
        public $id;
        
        /**
         *
         * @var boolean 
         */
        public $handicapped;
        
        /**
         *
         * @var string
         */
        public $name;
        
        /**
         *
         * @var string 
         */
        public $description;
        
        /**
         *
         * @var string
         */
        public $slogan;
        
        /**
         *
         * @var integer 
         */
        public $cityId;
        
        /**
         *
         * @var integer 
         */
        public $userId;

        /**
         * 
         * @param integer $id
         */
        public function __construct( $id = null)
        {
            parent::__construct();	
            if ($id != null) 
            {
                $st = $this->conn->prepare('select id, handicapped, name, description, '
                        . 'slogan, cityId, userId from Route where id = :id');
                $st->bindParam(':id', $this->id, PDO::PARAM_INT);
                $st->execute();
                $rs = $st->fetch(PDO::FETCH_OBJ);

                //Insert value
                $this->id = $rs->id;
                $this->handicapped = $rs->handicapped;
                $this->name = $rs->name;
                $this->description = $rs->description;	
                $this->slogan = $rs->slogan;	
                $this->cityId = $rs->cityId;
                $this->userId = $rs->userId;
            }
        }

        
        public function save()
        {
            try
            {
                $st = $this->connection->prepare('insert into route '.
                    '(id, handicapped, name, description, slogan, cityId, userId) '.
                    ' values (:id, :handicapped, :name, :description, :slogan, :cityId, :userId)');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                $st->bindParam(':handicapped', $this->handicapped, \PDO::PARAM_BOOL);
                $st->bindParam(':name', $this->name, \PDO::PARAM_STR);
                $st->bindParam(':description', $this->description, \PDO::PARAM_STR);
                $st->bindParam(':slogan', $this->slogan, \PDO::PARAM_STR);
                $st->bindParam(':cityId', $this->cityId, \pdo::PARAM_INT);
                $st->bindParam(':userId', $this->userId, \pdo::PARAM_INT);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            }catch(PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
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
                // id, handicapped, name, description, slogan, cityId, userId
                $st = $this->connection->prepare('UPDATE route SET id = :id ,'
                        . 'name = :name, description = :description, slogan = :slogan, '
                        . 'cityId = :cityId, userId = :userId, handicapped = :handicapped where id = :id');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                $st->bindParam(':name', $this->name, \PDO::PARAM_STR);
                $st->bindParam(':description', $this->description, \PDO::PARAM_STR);
                $st->bindParam(':slogan', $this->slogan, \PDO::PARAM_STR);
                $st->bindParam(':cityId', $this->cityId, \PDO::PARAM_INT);
                $st->bindParam(':userId', $this->userId, \PDO::PARAM_INT);
                $st->bindParam(':handicapped', $this->handicapped, \PDO::PARAM_STR);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            }catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }


        /**
         * Delete model from database
         */
        public function delete()
        {
            try
            {
                $st = $this->connection->prepare('delete from route where id = :id');
                $st->bindParam(':id', $this->id);
                return (!$st->execute()) ? HttpError::send(400, $st->errorInfo()[2]) : true;
            } catch(PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }
    }