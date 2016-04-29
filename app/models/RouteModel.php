<?php
    
    namespace touristiamo\models;
    
    use touristiamo\Model as Model;
    use touristiamo\error\HttpError as HttpError;
    use touristiamo\exception\BDException as BDException;

    /**
     * The class has protected or private variables , so you can protect them. 
     * We will use the get methods to obtain the value and set methods to change the value. 
     * However, our variables are public, because we use json. It will get the variable. 
     * If the variable is public, it won't get it.
     */
    class RouteModel extends Model
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
         * Construct a new Model with the data from the data base if pass id.
         * @param integer $id
         * @throws BDException
         */
        public function __construct( $id = null)
        {
            parent::__construct();	
            if ($id != null) 
            {
                $st = $this->connection->prepare('select id, handicapped, name, description, '
                        . 'slogan, cityId, userId from route where id = :id');
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
                $this->handicapped = $rs->handicapped;
                $this->name = $rs->name;
                $this->description = $rs->description;	
                $this->slogan = $rs->slogan;	
                $this->cityId = $rs->cityId;
                $this->userId = $rs->userId;
            }
        }

        /**
         * Save into the data base the current values in this object.
         * @return boolean
         * @throws BDException
         */
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
                $st->bindParam(':cityId', $this->cityId, \PDO::PARAM_INT);
                $st->bindParam(':userId', $this->userId, \PDO::PARAM_INT);
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
         * Update values from the data base with the current values in this object
         * @return boolean
         * @throws BDException
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
         * Delete route from database
         * @return boolean
         * @throws BDException
         */
        public function delete()
        {
            try
            {
                $st = $this->connection->prepare('delete from route where id = :id');
                $st->bindParam(':id', $this->id);
                if (!$st->execute()) 
                {
                    throw new BDException($st->errorInfo());
                }
                return true;
            } catch(\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }
        
        /**
         * Return all routes from the data base.
         * @return \ArrayIterator
         * @throws BDException
         */
        public function getAll()
        {
            try
            {
                $st = $this->connection->prepare('select * from route');
                if (!$st->execute()) 
                {
                    throw new BDException($st->errorInfo());
                }
                
                return $st->fetchAll(\PDO::FETCH_OBJ);
            } catch (\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }
        
        
        /**
         * Return all routes from the data base by country id.
         * @return \ArrayIterator
         * @throws BDException
         */
        public function getAllByCountry($countryId)
        {
            try
            {
                $st = $this->connection->prepare('select route.id, route.name, '
                    . 'route.slogan, route.description, '
                    . 'route.handicapped, route.cityId, route.userId from route '
                    . 'inner join city on route.cityId = city.id '
                    . 'inner join country on city.countryId = country.id '
                    . 'where country.id = :countryId');
                $st->bindParam(':countryId', $countryId, \PDO::PARAM_INT);
                if (!$st->execute()) 
                {
                    throw new BDException($st->errorInfo());
                }
                
                return $st->fetchAll(\PDO::FETCH_OBJ);
            } catch (\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }
        
        /**
         * Return all routes from the data base by city id.
         * @return \ArrayIterator
         * @throws BDException
         */
        public function getAllByCity($cityId)
        {
            try
            {
                $st = $this->connection->prepare('select * from route '
                    . 'where cityId = :cityId');
                $st->bindParam(':cityId', $cityId, \PDO::PARAM_INT);
                if (!$st->execute()) 
                {
                    throw new BDException($st->errorInfo());
                }
                
                return $st->fetchAll(\PDO::FETCH_OBJ);
            } catch (\PDOException $e)
            {
                HttpError::send(400, $e->getMessage());
            }
        }
        
    }