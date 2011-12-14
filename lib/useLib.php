<?
class useLib
{
    public static function loadLib($name)
    {
        if (include_once '/var/www/neo4j/lib/' . $name . '.php') {
   			return new $name;		
        } else {
           // throw new Exception('Драйвер не найден');
        }
    }
}
