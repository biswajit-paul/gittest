<?php 
/*echo '[[1370131200000, 3], [1370217600000, 0.7648], [1370304000000, 0.7645], [1370390400000, 0.7638], [1370476800000, 0.7549], [1370563200000, 0.7562], [1370736000000, 0.7574], [1370822400000, 0.7543], [1370908800000, 0.751], [1370995200000, 0.7498], [1371081600000, 0.7477], [1371168000000, 0.7492], [1371340800000, 0.7487], [1371427200000, 0.748], [1371513600000, 0.7466], [1371600000000, 0.7521], [1371686400000, 0.7564], [1371772800000, 0.7621], [1371945600000, 0.763], [1372032000000, 0.7623], [1372118400000, 0.7644], [1372204800000, 0.7685], [1372291200000, 0.7671], [1372377600000, 0.7687], [1372550400000, 0.7687], [1372636800000, 0.7654], [1372723200000, 0.7705], [1372809600000, 0.7687], [1372896000000, 0.7744], [1372982400000, 0.7793], [1373155200000, 0.7804], [1373241600000, 0.777], [1373328000000, 0.7824], [1373414400000, 10], [1373500800000, 0.7635], [1373587200000, 0.7652], [1373760000000, 0.7656], [1373846400000, 0.7655], [1373932800000, 0.7598], [1374019200000, 0.7619], [1374105600000, 0.7628], [1374192000000, 0.7609], [1374364800000, 0.7599], [1374451200000, 0.7584], [1374537600000, 0.7562], [1374624000000, 0.7575], [1374710400000, 0.7531], [1374796800000, 0.753]]';*/

$arr = array();

for( $i = 0; $i < 50; $i++ )
{
	$arr[] = array( rand(1370131200000, 1375131200000), rand(0,15) );	
}

echo json_encode( $arr );