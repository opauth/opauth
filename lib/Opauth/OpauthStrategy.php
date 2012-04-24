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

/**
 * Configurations and settings unique to a particular strategy
 */
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
				$this->optional($key, $this->envReplace($value));
			}
		}
	}
	
/**
 * Auth request
 * aka Log in or Register
 */
	public function request(){
	}
	
	public function callback(){
	}
	
/**
 * Call an action from a defined strategy
 *
 * @param string $action Action name to call
 * @param string $defaultAction If an action is not defined in a strategy, calls $defaultAction
 */
	public function callAction($action, $defaultAction = 'request'){
		return $this->{$action}();
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
	
/**
 * Replace defined env values enclused in {} with actual values
 */
	protected function envReplace($value){
		if (is_string($value) && preg_match_all('/{([A-Za-z0-9-_]+)}/', $value, $matches)){
			foreach ($matches[1] as $key){
				if (array_key_exists($key, $this->Opauth->env)){
					$value = str_replace('{'.$key.'}', $this->Opauth->env[$key], $value);
				}
			}
			
			return $value;
		}
		
		return $value;
	}
	
}