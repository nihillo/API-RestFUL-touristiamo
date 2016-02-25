<?php

    namespace touristiamo\models;
    
    use touristiamo\Model as Model;
    use touristiamo\error\HttpError as HttpError;
    use touristiamo\exception\BDException as BDException;

    /**
     * The class has protected or private variables , so you can protect them. 
     * We will use the get methods to obtain the value and set methods to change the value.
     * However, our variables are public, because we use json. 
     * It will get the variable. If the variable is public, it won't get it.
     */
    class UsersModel extends Model
    {
        /**
         *
         * @var string 
         */
        public $name;
        
        /**
         *
         * @var string
         */
        public $pass;
        
        /**
         *
         * @var string
         */
        public $email;
        
        /**
         *
         * @var string
         */
        public $token;
        
        /**
         *
         * @var string
         */
        public $accessLevel;
        
        /**
         *
         * @var integer
         */
        public $id;
        
        /**
         *
         * @var boolean
         */
        public $activated;
        
        /**
         *
         * @var boolean
         */
        public $banned;

        /**
         * Create a new model with values from data base if you pass the unique id
         * @param integer $id
         * @return boolean
         * @throws BDException
         */
        public function __construct($id = null)
        {
            parent::__construct();	
            if ($id != null) 
            {
                $st = $this->connection->prepare('select id, name, password, mail, token, accessLevel, activated, banned from users where id = :id');
                $st->bindParam(':id', $id, \PDO::PARAM_INT);
                if (!$st->execute()) 
                {
                    throw new BDException($st->errorInfo());
                }
                if (!$rs = $st->fetch(\PDO::FETCH_OBJ))
                {
                    return false;
                }

                $this->id = $rs->id;
                $this->name = $rs->name;
                $this->pass = $rs->password;
                $this->email =$rs->mail;
                $this->token = $rs->token;
                $this->accessLevel = $rs->accessLevel;
                $this->activated = $rs->activated; 
                $this->banned = $rs->banned;
            }
        }

        /**
         * Save model into data base
         * @return boolean
         * @throws BDException
         */
        public function save() 
        {
            try
            {
                $st = $this->connection->prepare('insert into users (name, password, mail, token, accessLevel, activated, banned) 
                values (:name, :pass, :email, :token, :accessLevel, :activated, :banned)');
                $st->bindParam(':name', $this->name, \PDO::PARAM_STR);
                $st->bindParam(':pass', $this->pass, \PDO::PARAM_STR);
                $st->bindParam(':email', $this->email, \PDO::PARAM_STR);
                $st->bindParam(':token', $this->token, \PDO::PARAM_STR);
                $st->bindParam(':accessLevel', (!$this->accessLevel) ? 3 : $this->accessLevel, \PDO::PARAM_INT);
                $st->bindParam(':activated', (!$this->activated) ? false : $this->activated, \PDO::PARAM_BOOL);
                $st->bindParam(':banned', (!$this->banned) ? false : $this->banned, \PDO::PARAM_BOOL);

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
         * Update model from data base
         * @return boolean
         * @throws BDException
         */
        public function update()
        {
            try
            {
                $st = $this->connection->prepare('update users set name = :name, '
                        . 'password = :pass, mail = :email, token = :token, accessLevel = :accessLevel, '
                        . 'activated = :activated, banned = :banned where id = :id');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                $st->bindParam(':name', $this->name, \PDO::PARAM_STR);
                $st->bindParam(':pass', $this->pass, \PDO::PARAM_STR);
                $st->bindParam(':email', $this->email, \PDO::PARAM_STR);
                $st->bindParam(':token', $this->token, \PDO::PARAM_STR);
                $st->bindParam(':accessLevel', $this->accessLevel, \PDO::PARAM_INT);
                $st->bindParam(':activated', $this->activated, \PDO::PARAM_BOOL);
                $st->bindParam(':banned', $this->banned, \PDO::PARAM_BOOL);
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
         * Delete model from database
         * @return boolean
         * @throws BDException
         */
        public function delete()
        {
            try
            {
                $st = $this->connection->prepare('DELETE FROM users where id = :id');
                $st->bindParam(':id', $this->id, \PDO::PARAM_INT);
                $st->execute();	
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
         * Fill all variables with data from data base
         * @param String $token
         * @return bool Return false if the user doesn't exist.
         */
        public function fillByToken($token)
        {
            $st = $this->connection->prepare('select id, name, password, mail, token, accessLevel, activated, banned from users where token = :token');
            $st->bindParam(':token', $token, \PDO::PARAM_STR);
            $st->execute();
            
            if (!$rs = $st->fetch(\PDO::FETCH_OBJ))
            {
                return false;
            }
            
            $this->id = $rs->id;
            $this->name = $rs->name;
            $this->pass = $rs->password;
            $this->email =$rs->mail;
            $this->token = $rs->token;
            $this->accessLevel = $rs->accessLevel;
            $this->activated = $rs->activated; 
            $this->banned = $rs->banned;
               
            return true;
            
        }
        
        /**
         * Fill all variables with data from data base
         * @param String $mail
         * @return bool Return false if the user doesn't exist.
         */
        public function fillByEmail($mail)
        {
            $st = $this->connection->prepare('select id, name, password, mail, token, accessLevel, activated, banned from users where mail = :mail');
            $st->bindParam(':mail', $mail, \PDO::PARAM_STR);
            $st->execute();
            
            if (!$rs = $st->fetch(\PDO::FETCH_OBJ))
            {
                return false;
            }

            $this->id = $rs->id;
            $this->name = $rs->name;
            $this->pass = $rs->password;
            $this->email =$rs->mail;
            $this->token = $rs->token;
            $this->accessLevel = $rs->accessLevel;
            $this->activated = $rs->activated; 
            $this->banned = $rs->banned;
            
            return true;
        }
    }	