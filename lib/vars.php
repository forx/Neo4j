<?
class vars{
	private $vars = array(
		'mysqlLogin' => 'script',
		'mysqlPass'  => 'JTpjuhL9wfQ8YasT',		
		'mysqlDB'    => 'ontology',		
		'mysqlHost'  => 'localhost',		
		'neoHost'    => 'localhost:9999',
	);	
	
	function get($name){
		return $this->vars[$name];
	}
}
