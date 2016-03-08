<?php

require_once('plugins/core/_table.class.php');
require_once('plugins/core/_plugin.class.php');
require_once('plugins/core/_page.class.php');
require_once('plugins/core/_auth.class.php');
require_once('plugins/core/_auth_method.class.php');
require_once('plugins/core/_group.class.php');
require_once('plugins/core/_user.class.php');
require_once('plugins/core/_parameter.class.php');
require_once('plugins/core/_locale.class.php');
require_once('plugins/core/_translation.class.php');
require_once('plugins/core/_processus.class.php');
require_once('plugins/core/_access.class.php');
require_once('plugins/core/_object.class.php');
require_once('plugins/core/_event_log.class.php');

/*
	Classe de connexion
	Utilisation du singleton pour plus de sécurité (la classe est chargé une fois sans possibilité de modification après coup)
*/
class DbLink
{
    private static $link;
    private $connexion;

    public static function getLink()
    {
        if (!self::$link)
            self::$link = new DbLink();
        return self::$link;
    }

    public function init($method,$host,$port,$basename,$login,$pass) {
        if (!$this->connexion) {
			// On initialise la connexion à la base de données si elle n'existe pas déjà
			try {
				if(strtoupper($method)=='MYSQL'){
					$this->connexion = new PDO('mysql:host='.$host.';port='.$port.';dbname='.$basename, $login, $pass);
				}
				elseif(strtoupper($method)=='ORACLE'){
					$this->connexion = new PDO('oci:dbname=//'.$host.':'.$port.'/'.$basename.';charset=UTF8', $login, $pass);
				}
				$this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(Exception $e)
			{
				echo 'Erreur : '.$e->getMessage().'<br />';
				echo 'N° : '.$e->getCode();
			}			
		}
    }
	
	public function get() {
		return $this->connexion;
	}
}

function get_link() {
	return DbLink::getLink()->get();
}

// Abstract class for other class which use database result
abstract class dbEntry {
	public $id; 
	public $createdDate; 
	public $createdId;
	public $editedDate;
	public $editedId;
	public $deletedDate;
	public $deletedId;
}

// Initialisation class
class initialisation {
	var $connexion; // Connexion link
	
    private static $ini;
    
    public $pluginsIndex;
    private static $plugins;
    private static $tables;
	
    private static $objects;
    
    private static $auth;
    
    private static $authMethodsIndex;
    private static $authMethods;
    
    private static $eventLog;
    
    // For function lockIt
    private static $accessM;
    private static $userAccessArray;
    
	function initialisation(){
		
		// On parse le fichier .ini
		self::$ini=array();
		$iniFilePath='conf/config.ini';
		self::$ini = parse_ini_file($iniFilePath);
	
		// On ouvre la connexion à la BDD
		DbLink::getLink()->init(self::$ini['BDD_TYPE'],self::$ini['BDD_HOTE'],self::$ini['BDD_PORT'],self::$ini['BDD_BASENAME'],self::$ini['BDD_LOGIN'],self::$ini['BDD_PASSWORD']);
		
	    // On récupère la liste des plugins et des tables
	    $this->pluginsIndex = array();
	    self::$tables = array();
	    try {
			$q0=get_link()->prepare("SELECT id AS ID,name AS NAME FROM ".get_ini('BDD_PREFIX')."core_plugins WHERE activated=1 AND deleted_date=0");
			$q0->execute(array());
			while($r0 = $q0->fetch(PDO::FETCH_OBJ)) {
        	    self::$plugins[$r0->NAME]=$r0->ID;
        	    array_push($this->pluginsIndex,$r0->NAME);
			}
			$q0=get_link()->prepare("SELECT id AS ID,name AS NAME FROM ".get_ini('BDD_PREFIX')."core_tables WHERE deleted_date=0");
			$q0->execute(array());
			while($r0 = $q0->fetch(PDO::FETCH_OBJ)) {
        	    self::$tables[$r0->NAME]=$r0->ID;
			}
			
			// Load the accessManager class for security function
			self::$accessM = new accessManager();
		} 
		catch(Exception $e)
		{
			echo 'Erreur : '.$e->getMessage().'<br />';
			echo 'N° : '.$e->getCode();
		}	
			
		$userAccessArray = array();
		
		// Load local
		if(!isset($_SESSION["lang"])) {
			if(get_ini('DEFAULT_LANGUAGE')!=''){$_SESSION["lang"]=get_ini('DEFAULT_LANGUAGE');} else {$_SESSION["lang"]='en_US';}
		}
  		if (isset($_GET["lang"])) {
		  $lang = $_GET["lang"];
		  $_SESSION["lang"] = $lang;
		  self::$ini['DEFAULT_LANGUAGE']=$lang;
  		}
  		elseif (isset($_SESSION["lang"])) {
		  $lang  = $_SESSION["lang"];
  		}
  		else {
		  $lang = "en_US";
  		}
  		
		if(function_exists('bindtextdomain'))
		{
			putenv("LANG=$lang"); 
			setlocale(LC_ALL, $lang); 
			bindtextdomain('lang', 'locale'); // Le nom de nos fichiers .mo et le folder qui contient les folders de langue
			bind_textdomain_codeset("lang", 'UTF-8'); 
			textdomain('lang');
		}
  		//$domain2 = "example2";
  		//bindtextdomain($domain2, "Locale"); 
  		//bind_textdomain_codeset($domain2, 'UTF-8');
  		//$user = "Curious gettext tester";
	}
    
	public static function getIni($param) {
		$param=strtoupper($param);
        if(!isset(self::$ini[$param])){
        	try {
            	$q0=get_link()->prepare("SELECT parameter_value AS PARAMETER_VALUE FROM ".get_ini('BDD_PREFIX')."core_parameters WHERE name=:name AND deleted_date=0");
            	$q0->execute(array( 'name' => $param ));
		    	$r0 = $q0->fetch(PDO::FETCH_OBJ); 
        	} catch(Exception $e) {}
            if(isset($r0->PARAMETER_VALUE)) {
                self::$ini[$param]=$r0->PARAMETER_VALUE;
            } else {
                self::$ini[$param]='';
            }
        }
		return self::$ini[$param];
	}

	public static function getTableId($name) {
		if(isset(self::$tables[$name]))
		{
			return self::$tables[$name];
		}
		else
		{
			return 0;
		}
	}
	
	public static function getPluginId($name) {
		return self::$plugins[strtolower($name)];
	}

	public static function getObjectId($table_name,$id_ext) {
	    // Check if the ID is already known
	    if(!isset(self::$objects[$table_name][$id_ext])) {
            $objM = new objectManager();
            self::$objects[$table_name][$id_ext]=$objM->getId(self::getTableId($table_name),$id_ext);
	    }
	    
		return self::$objects[$table_name][$id_ext];
	}
	
	function get_user_timezone () {
		return 'Europe/Paris';
	}
	
	public static function getAuthMethods($method) {
	    if(!isset($authMethodsIndex)) {
	    	self::$authMethodsIndex=array();
		    $q0=get_link()->prepare("SELECT id AS ID,name AS NAME,icon AS ICON FROM ".get_ini('BDD_PREFIX')."core_user_auth_methods WHERE deleted_date=0");
		    $q0->execute(array());
		    while($r0 = $q0->fetch(PDO::FETCH_OBJ)) {
		        array_push(self::$authMethodsIndex,$r0->NAME);
                self::$authMethods[$r0->NAME]['ID']=$r0->ID;
                self::$authMethods[$r0->NAME]['ICON']=$r0->ICON;
		    }
	    }
	}
	
	public static function getAuthMethodId($method) {
	    // On vérifie que l'id de l'objet n'est pas déjà connu
	    if(!isset(self::$authMethods[$method]['ID'])) {
			$this->getAuthMethods();
	    }
	    
		return self::$authMethods[$method]['ID'];
	}
	
	public static function eventLog($idPlugin,$log) {
	    if(self::$eventLog=='') {
			self::$eventLog = new eventLogManager();
	    }
	    
		return self::$eventLog->create($idPlugin,$log);
	}
	
	public static function getAccess($targetObjectId) {
		if($targetObjectId>0)
		{
	    	if(!isset(self::$userAccessArray[$targetObjectId])) {
				self::$userAccessArray[$targetObjectId] = self::$accessM->getLevel($targetObjectId);
	    	}
	    	
			return self::$userAccessArray[$targetObjectId];
		}
		else // If the object does not exist.
		{
			return -1;
		}
	}
	
	public static function secThis($targetTable,$targetId,$accessLevel) {
		if(self::getAccess(self::getObjectId($targetTable,$targetId))>=$accessLevel)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public static function secFile($filePath,$accessLevel) {
		$filePath = strtolower(str_replace('\\', '/',$filePath));
		
		$page = substr($filePath,strrpos($filePath,'/')+1,strlen($filePath)-strrpos($filePath,'/')-5);
		$plug = substr($filePath,strrpos($filePath,'/',-(strlen($page)+6))+1,strrpos($filePath,'/')-strrpos($filePath,'/',-(strlen($page)+6))-1);
		
		if(self::secThis('core_plugins',self::getPluginId($plug),$accessLevel) || secThis('core_pages',self::getTableId($page),$accessLevel))
		{
			return true;
		}
		else
		{
			include('plugins/core/403.php');
			return false;
		}
	}
}

function get_ini($param) {
	return initialisation::getIni($param);
}

function get_table_id($name) {
	return initialisation::getTableId(strtolower($name));
}

function getTableId($name) {
	return initialisation::getTableId(strtolower($name));
}

function getPluginId($name) {
	return initialisation::getPluginId(strtolower($name));
}

function getPageId($pluginId,$name) {
	$pageM = new pageManager();
	return $pageM->getId($pluginId,$name);
}

function get_object_id($table_name,$id_ext) {
	return initialisation::getObjectId(strtolower($table_name),$id_ext);
}

function get_auth_methods($method='') {
	return initialisation::getAuthMethods(strtoupper($method));
}

function get_auth_method_id($method) {
	return initialisation::getAuthMethodId(strtoupper($method));
}

function logIt($idPlugin,$log) {
	return initialisation::eventLog($idPlugin,$log);
}

function getAccess($targetObjectId) {
	return initialisation::getAccess($targetObjectId);
}

function secFile($filePath,$accessLevel) {
	return initialisation::secFile($filePath,$accessLevel);
}

function secThis($targetTable,$targetId,$accessLevel) {
	return initialisation::secThis($targetTable,$targetId,$accessLevel);
}

/*
	Convert date to timestamp
*/
function toTime($date) {
	    if(preg_match('#[0-9]{4}/[0-9]{2}/[0-9]{2}[ _-]{1}[0-9]{2}:[0-9]{2}:[0-9]{2}#',$date)){return mktime(substr($date,11,2),substr($date,14,2),substr($date,17,2),substr($date,5,2),substr($date,8,2),substr($date,0,4));}
	elseif(preg_match('#[0-9]{2}/[0-9]{2}/[0-9]{4}[ _-]{1}[0-9]{2}:[0-9]{2}:[0-9]{2}#',$date)){return mktime(substr($date,11,2),substr($date,14,2),substr($date,17,2),substr($date,3,2),substr($date,0,2),substr($date,6,4));}
	else{return time();}
}

/*
	Mail address check
*/
function mailCheck($mail) {
	if(preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#',$mail)) {
		return TRUE;
	}
	else {
		return FALSE;
	}
}

/*
    Générer une chaine aléatoire
*/
function stringGenerate($length = 8)
{
        $string='';
       
        $pattern = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+_@#*!$%?&';
        $pattern_length = strlen($pattern);
       
        for($i = 1; $i <= $length; $i++)
        {
            $string .= $pattern[mt_rand(0,($pattern_length-1))];
        }

        return $string;   
}

/*
	Calcul the optimal width/height for a picture
*/
function getNewSizePicture($path,$targetWidth,$targetHeight) {
	list($originalWidth, $originalHeight) = getimagesize($path);
	$ratio = $originalWidth / $originalHeight;
	

	if ($originalWidth > $originalHeight) {
		return array ( $targetWidth , floor(($originalHeight / $originalWidth)*$targetWidth) );
	} else {
		return array ( floor(($originalWidth / $originalHeight)*$targetHeight) , $targetHeight );
	}
}

function hashWithSalt($string) {
    return hash(get_ini('HASH_METHOD'), $string . get_ini('PASSWORD_SALT'));
}

function getOsAgent(){
	$agent=$_SERVER['HTTP_USER_AGENT'];

	if(stristr($agent,'Macintosh')){$os="Mac";}
	elseif(stristr($agent,'Win')){$os="PC";}

	//- smartphones-----------------
	elseif(stristr($agent,'iPhone')){$os="iPhone";}
	elseif(stristr($agent,'iPod')){$os="iPod";}
	elseif(stristr($agent,'Android')){$os="Android";}
	elseif(stristr($agent,'iPad')){$os="iPad";}

	else{$os="Linux";}
	
	return $os;
}

function getBrowserAgent(){
	$agent=$_SERVER['HTTP_USER_AGENT'];
	
	if(stristr($agent,'Chrome')){$browser="Chrome";}
	elseif(stristr($agent,'Camino')){$browser="Camino";}
	elseif(stristr($agent,'Firefox')){$browser="Firefox";}
	elseif(stristr($agent,'Safari')){$browser="Safari";}
	elseif(stristr($agent,'MSIE')){$browser="Explorer";}
	elseif(stristr($agent,'Opera')){$browser="Opera";}
	elseif(stristr($agent,'Epiphany')){$browser="Epiphany";}
	elseif(stristr($agent,'ChromePlus')){$browser="ChromePlus";}
	elseif(stristr($agent,'Lynx')){$browser="Lynx";}

	else{$browser="inconnu";}
	
	return $browser;
}

function getBrowserAgentVersion(){
	$agent=$_SERVER['HTTP_USER_AGENT'];
	
	// TODO - if(stristr($agent,'Chrome')){$browser="Chrome";}
	// TODO - elseif(stristr($agent,'Camino')){$browser="Camino";}
	// TODO - elseif(stristr($agent,'Safari')){$browser="Safari";}
	// TODO - elseif(stristr($agent,'Opera')){$browser="Opera";}
	// TODO - elseif(stristr($agent,'Epiphany')){$browser="Epiphany";}
	// TODO - elseif(stristr($agent,'ChromePlus')){$browser="ChromePlus";}
	// TODO - elseif(stristr($agent,'Lynx')){$browser="Lynx";}
	if(stristr($agent,'MSIE')){
		$version=substr($agent,strpos($agent,'MSIE')+5,strlen($agent)-strpos('MSIE',$agent));
		$version=substr($version,0,strpos($version,'.'));
	} elseif(stristr($agent,'Firefox')){
		$version=substr($agent,strpos($agent,'Firefox')+8,strlen($agent)-strpos('Firefox',$agent));
		$version=substr($version,strpos($version,'/'),strpos($version,'.')-strpos($version,'/'));
	}
	else{$version="inconnu";}
	
	return $version;
}

// http://www.finalclap.com/tuto/php-cryptage-aes-chiffrement-85/
// Use:
// $clair   = "this is a data";
// $crypt   = rijn::crypt($clair);
// $decrypt = rijn::decrypt($crypt);
class rijn {
    private static $cipher  = MCRYPT_RIJNDAEL_256;          // Algorithme utilisé pour le cryptage des blocs
    private static $key; 			    							// Clé de cryptage
    private static $mode    = 'cbc';                        // Mode opératoire (traitement des blocs)
 
	// Builder
	function rijn() {
		self::$key = get_ini('BDD_CRYPT_PASS');
	}
 
    public static function crypt($data){
        $keyHash = md5(self::$key);
        $key = substr($keyHash, 0,   mcrypt_get_key_size(self::$cipher, self::$mode) );
        $iv  = substr($keyHash, 0, mcrypt_get_block_size(self::$cipher, self::$mode) );
 
        $data = mcrypt_encrypt(self::$cipher, $key, $data, self::$mode, $iv);
        return base64_encode($data);
    }
 
    public static function decrypt($data){
        $keyHash = md5(self::$key);
        $key = substr($keyHash, 0,   mcrypt_get_key_size(self::$cipher, self::$mode) );
        $iv  = substr($keyHash, 0, mcrypt_get_block_size(self::$cipher, self::$mode) );
 
        $data = base64_decode($data);
        $data = mcrypt_decrypt(self::$cipher, $key, $data, self::$mode, $iv);
        return rtrim($data);
    }
}

// $rijn = new rijn(); // Cryp call
// echo strlen(rijn::crypt($server->creds['login'])).'<BR>'.
// rijn::decrypt(rijn::crypt($server->creds['login'])).'<BR>'.$server->creds['password'].$server->creds['subLogin'].$server->creds['subPassword'].'<BR>';
		

?>