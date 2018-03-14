<?php
define('ROOT', __DIR__);
define('BN', 'survey');

$urls = array(
    "main" =>  "/" . BN . "/src/main.php",
    "survey_tpl_edit" =>  "/" . BN . "/src/main.php?a=edit&id=",
    "survey_tpl" =>  "/" . BN . "/survey-tpl.php?id=",
    "survey_edit" =>  "/" . BN . "/src/main.php?a=e-survey&id=",
    "survey" =>  "/" . BN . "/survey.php?id="
);

class CF {
    const TBL_SURVEY_TEMPLATE = 'survey_templates';
    const TBL_SURVEYS = 'surveys';
    const TBL_USERS = 'users';

    public static function getAllTemplates($id=0)
    {
        return self::getAll(CF::TBL_SURVEY_TEMPLATE, 'id, `name`, actived', $id);
    }

    public static function getAll($tbl, $fields='', $id=0)
    {
        global $dbConn;
        $aTemplates = array();
        if ($result = $dbConn->query("SELECT $fields FROM " . $tbl . ($id > 0 ? " WHERE id = $id " : "") . ";")) {
            while ($row = $result->fetch_assoc()) {
                $aTemplates[] = $row;
            }
            /* free result set */
            mysqli_free_result($result);
        }
        return $aTemplates;
    }

    public static function getActivedSurvey($id=0)
    {
        global $dbConn;
        $item = array();
        $sExtra = '';
        if($id > 0) {
            $sExtra = ' AND id = ' . $id;
        }
        if ($result = $dbConn->query("SELECT id, name FROM " . CF::TBL_SURVEY_TEMPLATE . " WHERE actived = 1 $sExtra")) {
            while ($row = $result->fetch_assoc()) {$item = $row;}
            /* free result set */
            mysqli_free_result($result);
        }
        return $item;
    }
    public static function v($aData, $sField, $sDefault='') {
        return isset($aData[$sField]) ? $aData[$sField] : ($sDefault ? $sDefault : '');
    }

    public static function tz() {
        return new DateTimeZone('Asia/Ho_Chi_Minh');
    }

    public static function db() {
        global $dbConn;
        $sTbl = self::v($_REQUEST, 'tbl', 'users');

        if(!in_array($sTbl, array('users', 'surveys', 'survey_templates'))) {
            die('OOps!');
        }
        $sSql = "SHOW COLUMNS FROM `{$sTbl}`";
        $aCols = array();
        if ($result = $dbConn->query($sSql)) {
            while ($row = $result->fetch_assoc()) {
                $aCols[] = $row;
            }
            /* free result set */
            mysqli_free_result($result);
        }

        $aTblCols = array();
        $aIgnoredCols = array('content');
        $sSelect = "";
        foreach ($aCols as $i => $oCol) {
            if(in_array($oCol['Field'], $aIgnoredCols)) {
                unset($aCols[$i]);
                continue;
            }
            $aTblCols[] = $oCol['Field'];
            $sSelect .= ",".$oCol['Field'];
        }
        $sSelect = trim($sSelect, ',');
        $aItems = array();

        $sSql = "SELECT {$sSelect} FROM {$sTbl} WHERE 1";
        if ($result = $dbConn->query($sSql)) {
            while ($row = $result->fetch_assoc()) {
                $aItems[] = $row;
            }
            /* free result set */
            mysqli_free_result($result);
        }
        return array($aCols, $aItems);
    }
}

/*
* Mysql database class - only one connection alowed
*/
class Database {
    private $_connection;
    private static $_instance; //The single instance
    private $_host = "localhost";
    private $_username = "root";
    private $_password = "";
    private $_database = "survey";
    /*
    Get an instance of the Database
    @return Instance
    */
    public static function getInstance() {
        if(!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    // Constructor
    private function __construct() {
        $this->_connection = new mysqli($this->_host, $this->_username,
            $this->_password, $this->_database);

        // Error handling
        if(mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
                E_USER_ERROR);
        }
    }
    // Magic method clone is empty to prevent duplication of connection
    private function __clone() { }
    // Get mysqli connection
    public function getConnection() {
        return $this->_connection;
    }
}

$db = Database::getInstance();
$dbConn = $db->getConnection();