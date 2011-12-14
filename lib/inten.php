<?
class intence {
	//у класса есть 3 основных параметра: имя, описание и набор атрибутов 
	private $id          = 0;
	private $name        = '';
	private $description = '';
	//атрибуты это массив из id объектовы и отношения к ним
	private $arrAttr     = array();
	//массив элементов, для которых этот элемент является атрибутом
	//private $arrAttr4    = array();

	private $mySqlBuddy;	
	private $myNeoBuddy;
	
	public function getName(){
		return $this->name;
	}
	
	public function getDescr(){
		return $this->description;
	}
	
	public function getAttr(){
		return $this->arrAttr();
	}

	public function addAttr($id, $relation, $from = 'neo'){
		$myBuddy   = $this->choose($from); 
		$arrAttr[] = array($id, $myBuddy->loadName, $relation);
	} 
	
	function __construct($id, $name = '', $description = '', $arrAttr = array()/*, $arrAttr4 = array()*/){
		$this->id          = $id;
		$this->name        = $name;
		$this->description = $description;
		$this->arrAttr     = $arrAttr;
		$this->mySqlBuddy  = useLib::loadLib('mySqlBuddy');
		$this->myNeoBuddy  = useLib::loadLib('myNeoBuddy');
		//$this->arrAttr4    = $arrAttr4
	}

	function construct($id, $name, $description, $arrAttr){
		return new intence($id, $name, $description, $arrAttr);
	}
	
	function load($id, $from){
		$myBuddy  = $this->choose($from);
		$object   = $myBuddy->load($id);
		$name     = $object['name']  //функция получения имени по id из neo
		$desc     = $object['desc'];  //функция получения описания по id из neo
		$arrAttr  = $object['arrAttr'];  //функция получения атрибутов по id из neo
		//$arrAttr4 = getAttr4ById($id, $from); //функция получения атрибутов для которых объект является атрибутом по id из neo
		return new intence($id, $name, $description, $arrAttr/*, $arrAttr4*/);
	}
	
	
	function save($where = 'neo'){
		$myBuddy = $this->choose($where);
		$myBuddy->save($this->id, $this->name, $this->description, $this->arrAttr/*, $this->arrAttr4*/);
	}
	
	function choose($name){
		switch($where){
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
