<?php
	$numbers = array (507, 501, 506, 504, 507, 511, 502, 506, 499, 501, 508, 501, 505, 498);
	$middle = array_sum($numbers)/count($numbers);
	
	
	for($i=0;$i<count($numbers);$i++)
	{
		$MN[$i] = $numbers[$i] - $middle;
	}
	
	print_r ($MN);
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

    $pc->draw(600,300);

?>