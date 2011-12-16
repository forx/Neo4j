<?
class mySqlBuddy{

	private $link;

	function __construct($newLink = false){
    	$vars = useLib::loadLib('vars');
    	$this->link = mysql_connect($vars->get('mysqlHost'), $vars->get('mysqlLogin'), $vars->get('mysqlPass'), $newLink);
    	mysql_select_db($vars->get('mysqlDB'), $this->link);
    }

	public function getById($id){
		$return = array(); //результат в виде массива, котрый мы вытаскиваем из php
		
		$query  = "SELECT name, descr FROM intence WHERE id = {$id}";
		$result = mysql_query($query, $this->link);
		if (mysql_num_rows($result) != 0){
			$row = mysql_fetch_array($result);
			$return['id'] = $id;
			$return['name'] = $row['name'];
			$return['desc'] = $row['descr'];
			
			$query = "SELECT intence.id, intence.name, relation.name FROM ( (
							int2int LEFT JOIN intence ON intence.id = int2int.attrId 
							) LEFT JOIN relation ON relation.id = relId 
						) WHERE intId = {$id}";
			$result = mysql_query($query, $this->link);
			while($row = mysql_fetch_row($query)){
				$return['arrAttr'][] = array('id' => $row[0], 'name' => $row[1], 'relation' => $row[2]);
			}
			
			$query = "SELECT intence.id, intence.name, relation.name FROM ( (
							int2int LEFT JOIN intence ON intence.id = int2int.intId 
							) LEFT JOIN relation ON relation.id = relId 
						) WHERE attrId = {$id}";
			$result = mysql_query($query, $this->link);
			while($row = mysql_fetch_row($query)){
				$return['arrAttr4'][] = array('id' => $row[0], 'name' => $row[1], 'relation' => $row[2]);
			}
		} else {
			$return = false;
		}
		return($return);
	}
	
	public function getByName($name){
		
	}

	public function loadName($id){
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
		$query = "SELECT id, name, descr FROM intence WHERE id = {$id}";
		$result = mysql_query($query, $this->link);
		$query = false;
		if (mysql_num_rows($result) == 0) {
			$query = "INSERT INTO intence (id, name, descr) VALUES ({$id}, '{$name}', '{$desc}')";
		} else {
			$row = mysql_fetch_array($result);
			if ($row['name'] != $name && $row['descr'] != $desc){
				$query = "UPDATE intence SET name = '{$name}', descr = '{$desc}' WHERE id = {$id}";
			} elseif($row['name'] != $name) {
				$query = "UPDATE intence SET name = '{$name}' WHERE id = {$id}";
			} elseif($row['descr'] != $desc) {
				$query = "UPDATE intence SET descr = '{$desc}' WHERE id = {$id}";
			}
		}
		if ($query){
			mysql_query($query, $this->link);
		}
		foreach ($arrAttr as $attr){
			$query  = "SELECT id FROM relation WHERE name = '{$attr['relation']}'";
			$result = mysql_query($query, $this->link);
			if (mysql_num_rows($result) == 0){
				$query = "INSERT INTO relation (name) VALUES ('{$attr['relation']}')";
				mysql_query($query, $this->link);
				$relId = mysql_insert_id($this->link);
			} else {
				$row = mysql_fetch_assoc($result);
				$relId = $row['id'];
			}
			$query = "SELECT * FROM int2int WHERE intId = {$id} AND relId = {$relId} AND attrId = {$attr['id']}";
			$result = mysql_query($query, $this->result);
			if (mysql_num_rows($result) == 0){
				$query = "INSERT INTO int2int (intId, relId, attrId) VALUES ({$id}, {$relId}, {$attr['id']})";
				mysql_query($query);
			}
		}
	}
			
	public function deleteWithRelation($id){
		$query = "DELETE FROM relation WHERE attrId = {$id} OR intId = {$id}";
		mysql_query($query);
		
		$query = "DELETE FROM intence WHERE id = {$id}";
		mysql_query($query);
	}
	
	public function reserv(){
		$query = 'INSERT INTO intence (id) VALUES (0)';
		mysql_query($query, $this->link);
		return mysql_insert_id();
	}
}
