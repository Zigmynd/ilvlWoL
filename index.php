<html>
	<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
	</head>
	
	<body>
<?php
	//Error_Reporting(E_ALL & ~E_NOTICE);
	Error_Reporting(E_ERROR);
	
	//system define
	$deep = "1";
	$player = array();
	$region = array();
	$realm = array();
	$c = 0;
	
	//"http://www.worldoflogs.com/rankings/players/Mogu'shan_Vaults/Will_of_the_Emperor/10H/Affliction_Warlock/?page=";
	$url = "http://worldoflogs.com/rankings/players/US-EU/Siege_of_Orgrimmar/Sha_of_Pride/25H/Affliction_Warlock/?page=";
	$deeplvl = "3";

	while($deep <= $deeplvl){
		$str = @file_get_contents($url.$deep++);
		preg_match_all("/(<a href='\/reports\/.*>(.*?)<\/a>)/", $str, $matches);
		$player = array_merge ($player, $matches[2]);

		preg_match_all("/<a href='\/realms\/\w.*>(.*)-(.*)<\/a>/", $str, $matches);
		$region = array_merge ($region, $matches[1]);
		$realm = array_merge ($realm, $matches[2]);
	}

	//print_r (rawurlencode(htmlspecialchars_decode($realm[8],ENT_QUOTES)));
	
	$GLOBALS['wowarmory']['db']['driver'] = 'mysql'; // Dont change. Only mysql supported so far.
    	$GLOBALS['wowarmory']['db']['hostname'] = '127.0.0.1'; // Hostname of server. 
    	$GLOBALS['wowarmory']['db']['dbname'] = 'wowarmoryapi'; //Name of your database
    	$GLOBALS['wowarmory']['db']['username'] = 'root'; //Insert your database username
    	$GLOBALS['wowarmory']['db']['password'] = ''; //Insert your database password
    	// Only use the two below if you have received API keys from Blizzard.
    	$GLOBALS['wowarmory']['keys']['private'] = ''; // if you have an API key from Blizzard
    	$GLOBALS['wowarmory']['keys']['public'] = ''; // if you have an API key from Blizzard
    	include('./wowarmoryapi/BattlenetArmory.class.php'); //include the main class 


	for($i=0;$i<count($player);$i++){
		$armory = new BattlenetArmory(htmlspecialchars_decode($region[$i], ENT_QUOTES), htmlspecialchars_decode($realm[$i],ENT_QUOTES)); 
		$character = $armory->getCharacter(htmlspecialchars_decode($player[$i], ENT_QUOTES)); 
		
		$gear = $character->getGear();		
		
		if ($gear["averageItemLevel"] > 490 && $gear["averageItemLevelEquipped"] > 490){
			$mAIL[$c] = $gear["averageItemLevel"];
			$mAILE[$c] = $gear["averageItemLevelEquipped"];
			$c++;
		}
	}
	
	
	MTest($mAIL);
	
	function MTest($numbers)
	{
		$j=0;
		$middle = round(array_sum($numbers)/count($numbers),2);
		
		for($i=0;$i<count($numbers);$i++){
			$MN[$i] = $numbers[$i] - $middle;
		}
		
		echo "<br />Среднее значение: ".$middle;
		require_once("./phpChart_Lite/conf.php");
	
	
		$ticks = array();
		$pc = new C_PhpChartX(array($MN),'chart7');
		$pc->set_animate(true, true);
		$pc->add_plugins(array('highlighter','pointLabels'));
		$pc->set_series_default(array('renderer'=>'plugin::BarRenderer',
                                  'rendererOptions'=>array('fillToZero'=>true),
                                  'pointLabels'=>array('show'=>true)));
		$pc->set_axes(array(
         		//'yaxis'=>array('autoscale'=>true),
         		'xaxis'=>array('renderer'=>'plugin::CategoryAxisRenderer','ticks'=>$ticks)
         		));

		$pc->draw(1200,300);
	}
?>
	</body>
</html>
