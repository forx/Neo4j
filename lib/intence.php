<?
class intence {
	
	private $where;
	
	//у класса есть 3 основных параметра: имя, описание и набор атрибутов 
	private $id          = 0;
	private $name        = '';
	private $description = '';
	//атрибуты это массив из id объектовы и отношения к ним
	private $arrAttr     = array();
	//массив элементов, для которых этот элемент является атрибутом
	private $arrAttr4    = array();
	private $myBuddy;	
	
	//private $mySqlBuddy;	
	//private $myNeoBuddy;
	
	public static function getIntByName($name, $from){
		$data = useLib::loadLib('my' . $from . 'Buddy')->getByName($name);
		$result = array();
		foreach($data as $id => $obj){
			$result[] = new intence($from, $id, $obj['name'], $obj['desc'], $obj['arrAttr'], $obj['arrAttr4']);
		}
		return $result;
	}
	
	public static function getIntById($id, $from){
		$data = useLib::loadLib('my' . $from . 'Buddy')->getById($id);
		$result = new intence($from, $id, $data['name'], $data['desc'], $data['arrAttr'], $data['arrAttr4']);
		return $result;
	}
	
	
	
	public function getParam($name){
		return $this->$$name;
	}
	
	public function chnParam($name, $value = ''){
		$this->$$name = $value;
	}
	
	public function addAttr($id, $relation){
		//$myBuddy   = $this->choose($from); 
		$this->arrAttr[] = array('id' => $id, 'name' => $this->myBuddy->loadName($id), 'relation' => $relation);
	} 
	
	public function delAttr($id, $relation){
		foreach($this->arrAttr as $key => $arr){
			if ($arr['id'] == $id && $arr['relation'] == $relation){
				unset($this->arrAttr[$key]);
			}
		}
	}
	
	function __construct($where, $id, $name, $description = '', $arrAttr = array(), $arrAttr4 = array()){
		$this->where = $where;
		
		if ( $id == 0){
			$this->myBuddy = useLib::loadLib('my' . $this->where . 'Buddy');
			$this->id      = $this->myBuddy->reserv();
		} else {
			$this->id = $id;
		}
		
		$this->name        = $name;
		$this->description = $description;
		$this->arrAttr     = $arrAttr;
		$this->arrAttr4    = $arrAttr4;
	}
	

	static function construct($where, $name, $description = '', $arrAttr = array(), $arrAttr4 = array()){
		return new intence($where, 0, $name, $description, $arrAttr, $arrAttr4);
	}
	
	function save(){
		//$myBuddy = $this->choose($where);
		$this->myBuddy->save($this->id, $this->name, $this->description, $this->arrAttr);
	}
	
	function deleteWithRelation(){
		$this->myBuddy->deleteWithRelation($this->id);
	}
	
	function refresh(){
		//$myBuddy  = $this->choose($from);
		$object   = $this->myBuddy->load($this->id);
		$this->name     = $object['name'];     //функция получения имени по id из neo
		$this->desc     = $object['desc'];     //функция получения описания по id из neo
		$this->arrAttr  = $object['arrAttr'];  //функция получения атрибутов по id из neo
		$this->arrAttr4 = $object['arrAttr4']; //функция получения атрибутов для которых объект является атрибутом по id из neo
	}
	
	
	function choose($name){
		$myBuddy = false;
		switch($name){
			case 'Neo':
				$myBuddy = myNeoBuddy;
				break;	
			case 'Sql':
				$myBuddy = mySqlBuddy;
				break;
		}
		return $myBuddy;
	}	
}
