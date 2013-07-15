<?php

class formTextarea extends formField {
	
	public function addCols($cols) {
		
		if(!is_int($cols)) {
			//new Exception();	
		}
	
		$this->addAttribute('cols', $cols);
		
	}
	
	public function addRows($rows) {
		
		if(!is_int($rows)) {
			//new Exception();	
		}
	
		$this->addAttribute('rows', $rows);
		
	}
	
	public function get() {
		
		$this->addAttribute('name', $this->name);
		
		if(!$this->hasAttribute('cols')) {
			$this->addCols(70);	
		}
		
		if(!$this->hasAttribute('rows')) {
			$this->addRows(5);	
		}
		
		return '<textarea'.$this->convertAttr().'>'.$this->value.'</textarea>';
		
	}
	
}

?>