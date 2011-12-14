<?
class vars{
	private static $vars = array(
		'mysqlLogin' => 'script',
		'mysqlPass'  => 'JTpjuhL9wfQ8YasT',		
		'mysqlDB'    => 'ontology',		
		'mysqlHost'  => 'localhost',		
	);	
	
	function get($name){
		return $this->vars[$name];
	}
}
