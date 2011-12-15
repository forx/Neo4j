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
	
	public function getParam($name){
		return $this->$$name;
	}
	
	/*public function getDescr(){
		return $this->description;
	}
	
	public function getAttr(){
		return $this->arrAttr();
	}
	
	public function getAttr4(){
		return $this->arr
	}*/

	public function addAttr($id, $relation){
		//$myBuddy   = $this->choose($from); 
		$arrAttr[] = array($id, $this->myBuddy->loadName, $relation);
	} 
	
	public function delAttr($id, $relation){
		foreach($this->arrAttr as $key => $arr){
			if ($arr['id'] == $id && $arr['relation'] == $relation){
				unset($this->arrAttr[$key]);
			}
		}
	}
	
	function __construct($where, $name, $description = '', $arrAttr = array(), $arrAttr4 = array()){
		$this->where = $where;
		
		$this->myBuddy = useLib::loadLib('my' . $this->where . 'Buddy');
			
		$this->id          = $this->myBuddy->reserv();
		$this->name        = $name;
		$this->description = $description;
		$this->arrAttr     = $arrAttr;
		$this->arrAttr4    = $arrAttr4;
	}
	

	function construct($where, $name, $description = '', $arrAttr = array(), $arrAttr4 = array()){
		return new intence($where, $name, $description, $arrAttr, $arrAttr4);
	}
	
	
	function load($id){
		$object   = $this->myBuddy->load($id);
		$name     = $object['name'];     //функция получения имени по id из neo
		$desc     = $object['desc'];     //функция получения описания по id из neo
		$arrAttr  = $object['arrAttr'];  //функция получения атрибутов по id из neo
		$arrAttr4 = $object['arrAttr4']; //функция получения атрибутов для которых объект является атрибутом по id из neo
		return new intence($id, $name, $desc, $arrAttr, $arrAttr4);
	}
	
	
	function save(){
		//$myBuddy = $this->choose($where);
		$this->myBuddy->save($this->id, $this->name, $this->description, $this->arrAttr, $this->arrAttr4);
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
			case 'neo':
				$myBuddy = $myNeoBuddy;
				break;	
			case 'sql':
				$myBuddy = $myNeoBuddy;
				break;
		}
		return $myBuddy;
	}	
}
