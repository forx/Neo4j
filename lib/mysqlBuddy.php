<?
class mysqlBuddy{
	
	private $link;

	function __construct($newLink = false){
    	$vars = useLib::loadLib('vars');
    	$this->link = mysql_connect($vars->get('mysqlHost'), $vars->get('mysqlLogin'), $vars->get('mysqlPass'), $newLink);
    	mysql_select_db($vars->get('mysqlDB'), $this->link);
    }
    
	public function test(){
		print 'test';
	}
	
	public function load($id){
		$result = mysql_query("SELECT int.name, int.desc, attr.name,  FROM ( (
										intence as int LEFT JOIN int2int ON int.id = int2int.intId
									) LEFT JOIN relation ON int2int.relId = relation.id
								) LEFT JOIN intence as attr ON as attr.id = int2int.attrId
								WHERE int.id = {$id}
								");
		//$result =
	}
	
	public function loadName(){
		$result = mysql_query("SELECT int.name FROM intence	WHERE int.id = {$id}");
	}
	
	public function save(){
		
	}

}
