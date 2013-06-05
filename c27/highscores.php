<?php
$user = 'icstars_reduser';
$pwd = '6IL934;-[!}?';
$db = 'icstars_redball';
  $link = mysql_connect('localhost', $user, $pwd);
  if(!$link) {
    die('Could not connect: ' . mysql_error());
  } else {
    mysql_select_db($db, $link);
    $query = <<<SQL
select * from highscore order by thedate desc;
SQL;

    $result = mysql_query($query, $link);
  }
  
    $output = array(
		"aaData" => array()
	);
  
$aColumns = array( 'id', 'username', 'score', 'thedate');
  while ( $aRow = mysql_fetch_array( $result ) )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] == "version" )
			{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
			}
			else if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );



?>
