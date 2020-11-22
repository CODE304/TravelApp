<?php
require_once '_db.php';
$startms = strtotime($_GET['start'])*1000;
$endms = strtotime($_GET['end'])*1000;

$tuple = array (
  ":bind1" => $startms,
  ":bind2" => $endms
);

$alltuples = array (
  $tuple
);

$cmdstr = 'select * FROM Schedule WHERE NOT ((endDateTime <= :bind1 ) OR (startDateTime >= :bind2))';

$statement = OCIParse($db_conn, $cmdstr); 
foreach ($alltuples as $tuple) {
  foreach ($tuple as $bind => $val) {
      OCIBindByName($statement, $bind, $val);
      unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
  }

  $r = OCIExecute($statement, OCI_DEFAULT);
  if (!$r) {
      echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
      $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
      echo htmlentities($e['message']);
      echo "<br>";
      $success = False;
  }
}



class Event {}
$events = array();

while ($row = OCI_Fetch_Array($statement, OCI_BOTH)) {
  $e = new Event();
  $e->id = $row['SCHEDULEID'];
  $e->text = $row['DESCRIPTION'];
  $startTimeStamp = $row['STARTDATETIME']/1000 - 8*3600;
  $pos1 = strpos(gmdate(DATE_ATOM, $startTimeStamp), "+");
  $e->start = substr(gmdate(DATE_ATOM, $startTimeStamp), 0, $pos1);

  $endTimeStamp = $row['ENDDATETIME']/1000 - 8*3600;
  $pos2 = strpos(gmdate(DATE_ATOM, $endTimeStamp), "+");
  $e->end = substr(gmdate(DATE_ATOM, $endTimeStamp), 0, $pos2);
  $events[] = $e;
  
}


header('Content-Type: application/json');
echo json_encode($events);
?>
