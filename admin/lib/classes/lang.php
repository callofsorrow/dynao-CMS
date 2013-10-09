<?php

class lang {
	
	static $lang;
	static $langs = array();
	static $default = array();
	
	static private $isDefaultSet = false;
	static $defaultLang = 'en_gb';
	
	/**
	 * Die Sprache ersetzen, mit automaitschen laden der main Datei
	 *
	 * @param	string	$lang			Die Sprache
	 * @return	string
	 *
	 */
	static public function setLang($lang = 'en_gb') {
		
		if(is_dir(dir::lang($lang))) {
			
			self::$lang = $lang;	
			self::loadLang(dir::lang(self::getLang(), 'main'));
			
		}
		
		// throw new Exception();
		
	}
	
	/**
	 * String in der ensprechende Sprache bekommen, falls nicht gefunden, wird die DefaultSprache genommmen
	 *
	 * @param	string	$name			Der Sprachstring
	 * @return	string
	 *
	 */
	static public function get($name) {
		
		if(isset(self::$langs[$name])) {
			return self::$langs[$name];	
		}
		
		if(isset(self::$default[$name])) {
			return self::$default[$name];
		}
		
		return $name;
		
	}
	
	/**
	 * Gibt die aktuelle Sprache zurück
	 *
	 * @return	string
	 *
	 */
	static public function getLang() {
		
		return self::$lang;
			
	}
	
	/**
	 * Lädt die entsprechende Datei und fügt sie zur "Datenbank" hinzu
	 *
	 * @param	string	$file			Der Dateipfad ohne .json ende
	 * @return	string
	 *
	 */
	static public function loadLang($file) {
		
		$file = file_get_contents($file.'.json');
		
		// Alle Kommentare löschen (mit Raute beginnen
		$file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);	
		$array = json_decode($file, true);
				
		self::$langs = array_merge((array)$array,self::$langs);
		
	}
	
	/**
	 * Standardsprache setzen
	 *
	 */
	static public function setDefault() {
		
		if(!self::$isDefaultSet) {
			
			$file = file_get_contents(dir::lang(self::$defaultLang, 'main.json'));
			
			// Alle Kommentare löschen (mit Raute beginnen
			$file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);	
			self::$default = json_decode($file, true);
			
			self::$isDefaultSet = true;
		
		}
		
	}
	
}

?>
