<?
class mySqlBuddy{

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
		$return = array(); //результат в виде массива, котрый мы вытаскиваем из php
		$query  = "SELECT int.name, int.desc, attr.id, attr.name, relation.name FROM ( (
										intence as int LEFT JOIN int2int ON int.id = int2int.intId
									) LEFT JOIN relation ON int2int.relId = relation.id
								) LEFT JOIN intence as attr ON as attr.id = int2int.attrId
								WHERE int.id = {$id}";

		$result = mysql_query($query, $this->link);

		if (mysql_num_rows($result) != 0){	
			$row = mysql_fetch_row($result);
			$return['name']    = $row[0];
			$return['desc']    = $row[1];
			$return['arrAttr'] = array( array('id' => $row[2], 'name' => $row[3], 'relation' => $row[4]) );
				
			while($row = mysql_fetch_row($query)){
				$return['arrAttr'][] = array('id' => $row[2], 'name' => $row[3], 'relation' => $row[4]);
			}
		} else{
			$return = false;
		}		
		return($return);
	}

	public function loadName(){
		$return = '';
		$result = mysql_query("SELECT int.name FROM intence	WHERE int.id = {$id}", $this->link);
		if (mysql_num_rows($result) != 0){
			$row    = mysql_fetch_row($result);
			$return = $row[0];
		} else {
			$return = false;
		}
		return $return;
	}

	public function save($id, $name, $desc, $arrAttr){
		$query = "SELECT id, name FROM intence WHERE id = {$id}";
		$result = mysql_query($query, $this->link);
		$query = false;
		if (mysql_num_rows($result) == 0) {
			$query = "INSERT INTO intence (id, name, desc) VALUES ({$id}, '{$name}', '{$desc}')";
		} else {
			$row = mysql_fetch_row($result);
			if ($row['name'] != $name && $row['desc'] != $desc){
				$query = "UPDATE intence SET name = '{$name}', desc = '{$desc}' WHERE id = {$id}";
			} elseif($row['name'] != $name) {
				$query = "UPDATE intence SET name = '{$name}' WHERE id = {$id}";
			} elseif($row['desc'] != $desc) {
				$query = "UPDATE intence SET desc = '{$desc}' WHERE id = {$id}";
			}
		}
		if ($query){
			mysql_query($query, $this->link);
		}
		foreach ($arrAttr as $attr){
			$query  = "SELECT id FROM relation WHERE name = '{$name}'";
			$result = mysql_query($query, $this->link);
			if (mysql_num_rows($result) == 0){
				$query = "INSERT INTO relation (id, name) VALUES ({$id}, '{$name}')";
				mysql_query($query, $this->link);
				$relId = mysql_insert_id($this->link);
			} else {
				$row = mysql_fetch_row($result);
				$relId = $row['id'];
			}
			$query = "SELECT * FROM int2int WHERE intId = {$id} AND relId = {$relId} AND attrId = {$attr['id']}";
			$result = mysql_query($query, $this->result);
			if (mysql_num_rows($result) == 0){
				$query = "INSERT INTO int2int (intId, relId, attrId) VALUES ({$id}, {$relId}, {$attrId})";
				mysql_query($query);
			}
		}
	}
	
	public function reserv(){
		$query = 'INSERT INTO intence (id) VALUES (0)';
		mysql_query($query, $this->link);
		return mysql_insert_id();
	}
}
