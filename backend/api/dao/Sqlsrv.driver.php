<?php
class SqlException extends Exception {}

class sqlServerStatement
{
    CONST PARAM_STR     = 0xA01;  
    CONST PARAM_INT     = 0xA02;
    CONST PARAM_FLOAT   = 0xA03;
    CONST PARAM_BIN     = 0xA04;
    
    CONST FETCH_ARRAY   = 0xB01;
    CONST FETCH_ASSOC   = 0xB02;
    CONST FETCH_BATCH   = 0xB03;
    CONST FETCH_FIELD   = 0xB04;
    CONST FETCH_OBJ     = 0xB05;
    CONST FETCH_ROW     = 0xB06;
    
    protected $query, $objCon, $resource, $cacheKey;
    public function __construct($query, $objCon)
    {
        $this->query = $query;
        $this->objCon = $objCon;
    }
    public function bindValue($parameter, $value, $data_type)
    {
        switch($data_type)
        {
            case self::PARAM_STR:
                $this->query = str_replace($parameter, $this->escapeCharacters($value), $this->query); 
                break;
            case self::PARAM_INT:
                $this->query = str_replace($parameter, (integer)$value, $this->query); 
                break;
            case self::PARAM_FLOAT:
                $this->query = str_replace($parameter, (float)$value, $this->query); 
                break;
            case self::PARAM_BIN:
                $this->query = str_replace($parameter, "0x".bin2hex($value), $this->query); 
                break;
            default:
                throw new SqlException("Invalid DataType(".$data_type.").");
                break;
        } 
    }
    public function escapeCharacters($str)
    {
        return "'".str_replace("'", "''", $str)."'";
    }
    public function execute()
    {
        $this->resource = sqlsrv_query( $this->objCon, $this->query, array(), array('Scrollable' => 'buffered') );
        if($this->resource == false)
        {
            throw new SqlException("Error query. <!-- SQL Message: {$this->query} - ". $this->errorsToString() ." -->");
        }
    } 
    public function columnCount()
    {                             
        return sqlsrv_num_fields($this->resource);
    }
    public function rowsCount()
    {
        return sqlsrv_num_rows($this->resource);
    }
    public function fetchObject()
    {
        return sqlsrv_fetch_object($this->resource); 
    }
    public function fetchAll($fetch_style)
    {
        switch($fetch_style)
        {
            case self::FETCH_ARRAY:
                $results = array();
                while($result = sqlsrv_fetch_array($this->resource))
                {
                    $results[] = $result; 
                }
                break;
            case self::FETCH_ASSOC:
                $results = array();
                //while($result = sqlsrv_fetch_assoc($this->resource))
                while($result = sqlsrv_fetch_array($this->resource))
                {
                    $results[] = $result; 
                }
                break;
            case self::FETCH_BATCH:
                $results = array();
                while($result = sqlsrv_fetch_batch($this->resource))
                {
                    $results[] = $result; 
                }
                break;
            case self::FETCH_FIELD:
                $results = array();
                while($result = sqlsrv_fetch_field($this->resource))
                {
                    $results[] = $result; 
                }
                break;
            case self::FETCH_OBJ:
                $results = array();
                while($result = sqlsrv_fetch_object($this->resource))
                {
                    $results[] = $result; 
                }
                break;
            case self::FETCH_ROW:
                $results = array();
                //while($result = sqlsrv_fetch_row($this->resource))
                while($result = sqlsrv_fetch_array($this->resource))
                {
                    $results[] = $result; 
                }
                break;
        }
        return $results;
    }
    public function closeCursor()
    {
        unset($this->resource);
    }
    public function errorInfo()
    {
        return sqlsrv_errors();   
    }
    public function errorCode()
    {
        return sqlsrv_errors();  
    }
    public function getLastMessage()
    {
        return sqlsrv_errors();  
    }
    public function errorsToString()
    {
        return print_r(sqlsrv_errors(), true);
    }
}

class pdoLib
{
    private $objCon;
    public $databaseSettings;
    public function __construct($databaseSettings)
    {
        $this->databaseSettings = $databaseSettings;

        if($this->objCon == false)
            $this->connection(); 
    }
    private function connection()
    {
        $this->objCon = sqlsrv_connect($this->databaseSettings["address"], array("UID" => $this->databaseSettings["username"], "PWD" => $this->databaseSettings["password"], "Database" => $this->databaseSettings["database"]["account"]));
        if($this->objCon == false)
        {
            throw new SqlException("Connection error.\nSQL Message: ". print_r(sqlsrv_errors()));
        }
    }
    public function disconnect()
    {
        @sqlsrv_close($this->objCon);   
    }
    public function prepare($query = "")
    {
        if(empty($query) == true)
            throw new SqlException("Query empty.");   
        
        return new sqlServerStatement($query, $this->objCon);
    }
}