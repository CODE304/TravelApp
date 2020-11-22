<?php
    require_once '_db.php';
    $tuple = array (
        ":bind1" => $_POST['scheduleID'],
        ":bind2" => $_POST['newStart'],
        ":bind3" => $_POST['newEnd']
    );

    $alltuples = array (
        $tuple
    );

    $cmdstr = "update Schedule SET STARTDATETIME = :bind2, ENDDATETIME = :bind3 WHERE scheduleID = :bind1";

    $statement = OCIParse($db_conn, $cmdstr); 

    foreach ($alltuples as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
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
    OCICommit($db_conn);
// require_once '_db.php';

// $insert = "UPDATE events SET start = :start, end = :end WHERE id = :id";

// $stmt = $db->prepare($insert);

// $stmt->bindParam(':start', $_POST['newStart']);
// $stmt->bindParam(':end', $_POST['newEnd']);
// $stmt->bindParam(':id', $_POST['id']);

// $stmt->execute();

// class Result {}

// $response = new Result();
// $response->result = 'OK';
// $response->message = 'Update successful';

// header('Content-Type: application/json');
// echo json_encode($response);

?>
