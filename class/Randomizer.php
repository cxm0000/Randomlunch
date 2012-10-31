<?php

class Randomizer{
	
	private $ranNo = 0;
	
	function Randomizer(){
		
	}
	
	public static function generateNo($max, $min=0){
		
		return rand($max, $min);
	}
	
	
}

	 



?>