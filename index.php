<?
//print 'test';
include_once('/var/www/neo4j/lib/useLib.php');
useLib::inclLib('intence');
useLib::inclLib('JsonClient');

function test($what, $countTest, $countWrite){

	$startI = 1;

	$file = fopen('/var/www/neo4j/log/' . $what . 'Test' . date('Y-m-d_H:i:s'), 'w');
	fwrite($file, 'Start test for ' . $what . ' at ' . date('Y-m-d H:i:s') . "\n");
	$startTime = microtime(true);
	for ($i = 0; $i < $countTest ; $i++ ){
		$testIntence = intence::construct($what, 'test_' . $i, md5(time()));
		$testIntence->save();
		if (($i + 1) % $countWrite == 0){
			fwrite($file, 'Created ' . ($i + 1) . ' nodes:' . "\t" . (microtime(true) - $startTime) . "\n");
		}
	}

	$startTime = microtime(true);
	for ($i = $startI; $i < $countTest + $startI; $i ++){
		$testIntence = intence::getIntById($i, $what);
		for ($j = $startI; $j < $i; $j ++){
			$testIntence->addAttr($j, 'relation from ' . $i);
		}
		$testIntence->save();
		if (($i - $startI + 1) % $countWrite == 0){
			fwrite($file, 'Readed and added relations for ' . ($i - $startI + 1 )  . ' nodes:' . "\t" . (microtime(true) - $startTime) . "\n");
		}
	}

	$startTime = microtime(true);
	for($i = $startI; $i < $countTest + $startI; $i ++){
		$testIntence = intence::getIntById($i, $what);
		if (($i - $startI + 1) % $countWrite == 0){
			fwrite($file, 'Readed with relations ' . ($i - $startI + 1)  . ' nodes:' . "\t" . (microtime(true) - $startTime) . "\n");
		}
	}
	fwrite($file, 'End test at ' . date('Y-m-d H:i:s') . "\n");
	fclose($file);
}

test('Neo', 500, 50);
test('Sql', 500, 50);
