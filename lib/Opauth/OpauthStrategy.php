<?php
/**
 * Opauth Strategy
 * - Individual strategies to be extended from this class
 *
 */
class OpauthStrategy{	
	
/**
 * Compulsory config keys, listed as unassociative arrays
 * eg. array('app_id', 'app_secret');
 */
	public $expects;
	
/**
 * Optional config keys with respective default values, listed as associative arrays
 * eg. array('scope' => 'email');
 */
	public $defaults;
	
	public $name;
	protected $Opauth;
	protected $strategy;
	
	public function __construct(&$Opauth, $strategy){
		$this->Opauth = $Opauth;
		$this->strategy = $strategy;
		
		$this->name = $strategy['name'];
		
		if (is_array($this->expects)){
			foreach ($this->expects as $key){
				$this->expects($key);
			}
		}
		
		if (is_array($this->defaults)){
			foreach ($this->defaults as $key => $value){
				$this->optional($key, $value);
			}
		}
	}
	
	public function request(){
	}
	
	public function callback(){
	}

	
/**
 * Ensures that a compulsory value is set, throws an error if it's not set
 * 
 * @return mixed The loaded value
 */
	protected function expects($key, $not = null){
		if (!array_key_exists($key, $this->strategy)){
			trigger_error($this->name." config value ($key) expected.", E_USER_ERROR);
			exit();
		}
		
		$value = $this->strategy[$key];
		if (empty($value) || $value == $not){
			trigger_error($this->name." config value ($key) expected.", E_USER_ERROR);
			exit();
		}
		
		return $value;
	}
	
/**
 * Loads a default value into $strategy if the associated key is not found
 * 
 * @return mixed The loaded value
 */
	protected function optional($key, $default = null){
		if (!array_key_exists($key, $this->strategy)){
			$this->strategy[$key] = $default;
			return $default;
		}
		
		else return $this->strategy[$key];
	}
}