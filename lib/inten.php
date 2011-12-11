<?
class intence {
	//у класса есть 3 основных параметра: имя, описание и набор атрибутов 
	private $id          = 0;
	private $name        = '';
	private $description = '';
	//атрибуты это массив из ссылок на объекты и отношения к ним
	private $arrAttr     = array();
	
	public function getName(){
		return $this->name;
	}
	
	public function getDecr(){
		return $this->description;
	}
	
	function __construct($id, $name = '', $description = '', $arrAttr = array()){
		$this->id   = $id;
		$this->name = $name;
	}

	function construct($id, $name, $description, $arrAttr){
		return new intence($id, $name, $description, $arrAttr);
	}
	
	function constructFromDB($id, $from){
		$name    = getNameById($id, $from);  //функция получения имени по id из neo
		$desc    = getDescById($id, $from);  //функция получения описания по id из neo
		$arrAttr = getAttrById($id, $from); //функция получения атрибутов по id из neo
		return new intence($id, $name, $description, $arrAttr);
	}
	
	function save($where = 'neo'){
		switch($where){
			case 'neo':
				saveToNeo($this->id, $this->name, $this->description, $this->arrAttr);
				break;	
			case 'sql':
				saveToSql($this->id, $this->name, $this->description, $this->arrAttr);
				break;
		}
	}
}
