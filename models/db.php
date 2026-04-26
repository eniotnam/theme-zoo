<?php

Class Db
{
    private static $instance = null;
    private $host = 'localhost';
//    private $dbname = 'id329684_zoo';
        private $dbname = 'zoo';
//    private $user = 'id329684_zoo';
      private $user = 'root';
//    private $password = 'Ga0ubenat';
    private $password = 'root';


    protected $_sDbh;

    public function __construct()
    {
        try {
            $dsn = 'mysql:dbname='.$this->dbname.';host='.$this->host;
            $this->_sDbh = new PDO($dsn, $this->user, $this->password);
        } catch (PDOException $e) {
            echo 'MYSQL Connection error' . (!empty($_GET['debug_error']) ? ' : ' . $e->getMessage() : '');
            exit();
        }
    }

    public static function getInstance() {
        if (null === self::$instance) self::$instance = new self();
        return self::$instance->getDb();
    }

    public function getDb(){
      if(empty($this->_sDbh)){
        throw new Exception('Error : class db is not initialized');
      }
      return $this->_sDbh;
    }

    public function query($sSql, $aParam = array(), $bLastInsertId = false)
    {
        $sSth = $this->_sDbh->prepare($sSql);
        $sSth->execute($aParam);
        $aError = $sSth->errorInfo();
        if (!empty($aError[2])) {
            echo '<br />Error SQL : ' . $aError[2] . '<br />';
        }
        if ($bLastInsertId)
            return $this->_sDbh->lastInsertId();
        else
            return $sSth;
    }
}
