<?php
class Facebook extends OpauthStrategy{
	
	public function __construct(&$Opauth){
		parent::__construct($Opauth);
	}
}