<?php

class user {
	
	protected static $userID;
	protected static $entrys;

	public static function getUser($id = false) {
	
		if(!$id)
			$id = userLogin::getUser();
			
		if(self::$userID != $id)
			self::getEntrys($id);
			
		self::$userID = $id;
			
		return new user();
		
	}
	
	protected static function getEntrys($id) {
	
		$sql = new sql();
		$sql->query('SELECT * FROM user WHERE id='.$id)->result();
		
		self::$entrys = $sql->result;
		
	}
	
	public function get($name) {
		
		return self::$entrys[$name];
		
	}
	
	public function getID() {
	
		return $this->get('id');
		
	}
	
	public function hasPerm($perm) {
	
			
		
	}
	
	public static function getAll() {
	
		return self::$entrys;	
		
	}
	
}

?>