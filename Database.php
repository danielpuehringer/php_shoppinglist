<?php
class Database{
    private static $oMySQLi;

    public static function loadConfig(string $sConfigFile){
        require_once($sConfigFile);
    }

    public static function selectQuery(string $sQuery):mysqli_result{
        if(Database::connect()){
            $oResult = Database::$oMySQLi->query($sQuery);
            Database::disconnect(); //TODO: check return value
            return $oResult;
        }
        else return null;
    }
    //returns id of inserted entry or 0 if not inserted
    public static function insertQuery(string $sQuery):int{
        if(Database::connect()){
            $oResult = Database::$oMySQLi->query($sQuery);
            $iID = Database::$oMySQLi->insert_id;
            Database::disconnect(); //TODO: check return value
            return $iID;
        }
        else return -1;
    }

    //updates certain line
    public static function updateQuery(string $sQuery):bool{
        if(Database::connect()){
            $oResult = Database::$oMySQLi->query($sQuery);
            Database::disconnect();
            return true;
        }else{
            return false;
        }
    }

    public static function deleteQuery(string $sQuery):bool{
        if(Database::connect()){
            $oResult = Database::$oMySQLi->query($sQuery);
            Database::disconnect(); //TODO: check return value
            return $oResult;
        }
        else return false;
    }

    private static function connect():bool{
        Database::$oMySQLi = @new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
        if(Database::$oMySQLi->connect_error){
            return false; //TODO: could throw exception here
        }
        return true;
    }

    private static function disconnect():bool{
        if(Database::$oMySQLi != null){
            return (Database::$oMySQLi->close());
        }
        return true;
    }
}

?>