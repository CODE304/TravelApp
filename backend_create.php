<?php
    require_once '_db.php';
    $tuple = array (
        ":bind1" => $_POST['scheduleID'],
        ":bind2" => $_POST['description'],
        ":bind3" => $_POST['startDateTime'],
        ":bind4" => $_POST['endDateTime'],
        ":bind5" => $_POST['event_type'],
        ":bind6" => $_POST['email'],
    );

    $alltuples = array (
        $tuple
    );

    $cmdstr = "insert into Schedule values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)";

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

