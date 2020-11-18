<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.  
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values
 
  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED

  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the 
  OCILogon below to be your ORACLE username and password -->

  <html>

    <head>
        <title>Trip page</title>
        <style>
    body {background-image: url('https://i.giphy.com/media/jpbnoe3UIa8TU8LM13/giphy.webp');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-size: 100% 100%;
  }  
    </style>

    </head>

    <body>
        <!-- <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="trip.php"> -->
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <!-- <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p> -->
        <!-- </form> -->

        <h1>Trip Queries</h1>
        <hr />

        <h2>Insert Values into Trip Table</h2>
        <form method="POST" action="trip.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            tripID: <input type="text" name="tripid"> <br /><br />
            Origin: <input type="text" name="origin"> <br /><br />
            Destination: <input type="text" name="destination"> <br /><br />
            Departure Date: <input type="text" name="departuredate"> <br /><br />
            Arrival Date: <input type="text" name="arrivaldate"> <br /><br />
            fee: <input type="text" name="fee"> <br /><br />
            transID: <input type="text" name="transid"> <br /><br />

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h3>Delete in Trip Table</h3>
        <form method="POST" action="trip.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">

            Trip ID: <input type="text" name="tripid"> <br /><br />

            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

        <hr />

        <h2>Update departure and arrival times of a trip</h2>
        <form method="POST" action="trip.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Trip ID: <input type="text" name="tripid"> <br /><br />
            Old Departure Date: <input type="text" name="departuredate"> <br /><br />
            Old Arrival Date: <input type="text" name="arrivaldate"> <br /><br />
            New Departure Date: <input type="text" name="newdeparturedate"> <br /><br />
            New Arrival Date: <input type="text" name="newarrivaldate"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <h3>Select trips originating from a given city</h3>
        <form method="POST" action="trip.php"> <!--refresh page when submitted-->
            <input type="hidden" id="selectQueryRequest" name="selectQueryRequest">
            
            City: <input type="text" name="enterorigin"> <br /><br />

            <input type="submit" value="Select" name="selectSubmit"></p>
        </form>

        <hr />

        <h3> Given your trip, project its departure and arrival times, as well as its fee.  </h3>
        <form method="POST" action="trip.php"> <!--refresh page when submitted-->
            <input type="hidden" id="projectQueryRequest" name="projectQueryRequest">

            Trip ID: <input type="text" name="tripid"> <br /><br />

            <input type="submit" value="Project" name="projectSubmit"></p>
        </form>

        <hr />

        <h3> Find all trip IDs and airplane IDs, originating in a given city that has given departure time </h3>
        <form method="POST" action="trip.php"> <!--refresh page when submitted-->
            <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">

            City: <input type="text" name="givenorigin"> <br /><br />
            Departure Time: <input type="text" name="givendeparturedate"> <br /><br />

            <input type="submit" value="Join" name="joinSubmit"></p>
        </form>

        <hr />

        <h3> Find the cheapest trip to each listed city  </h3>
        <form method="GET" action="trip.php"> <!--refresh page when submitted-->
            <input type="hidden" id="groupByRequest" name="groupByRequest">
            <input type="submit" name="aggGroupBy"></p>
        </form>

  
        <hr />

        <h3> List Cities and the number of trips which originate there, but only if they have at least two trips </h3>
        <form method="GET" action="trip.php"> <!--refresh page when submitted-->
            <input type="hidden" id="havingRequest" name="havingRequest">
            <input type="submit" name="aggHaving"></p>
        </form>

        <!-- nested agg and division to be added here -->

        <h2>Show the Tuples in Trip Table</h2>
        <form method="GET" action="trip.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTripRequest" name="displayTripRequest">
            <input type="submit" name="displayTripTuples"></p>
        </form>

        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr); 
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection. 
		See the sample code below for how this function is used */

			global $db_conn, $success;
			$statement = OCIParse($db_conn, $cmdstr);

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn);
                echo htmlentities($e['message']);
                $success = False;
            }

            foreach ($list as $tuple) {
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
        }
        
        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example, 
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_rostam2", "a96968680", "dbhost.students.cs.ubc.ca:1522/stu");

            if ($db_conn) {
                debugAlertMessage("Database is Connected");
                return true;
            } else {
                debugAlertMessage("Cannot connect to Database");
                $e = OCI_Error(); // For OCILogon errors pass no handle
                echo htmlentities($e['message']);
                return false;
            }
        }

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }


        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['tripid'],
                ":bind2" => $_POST['origin'],
                ":bind3" => $_POST['destination'],
                ":bind4" => $_POST['departuredate'],
                ":bind5" => $_POST['arrivaldate'],
                ":bind6" => $_POST['fee'],
                ":bind7" => $_POST['transid'],
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into tripTable values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)", $alltuples);
            OCICommit($db_conn);
        }

        function handleDeleteRequest() {
            global $db_conn;

            $tripID =  $_POST['tripid'];

            executePlainSQL("DELETE FROM tripTable WHERE tripid='" . $tripID . "'");
            OCICommit($db_conn);
        }

            
        function handleUpdateRequest() {
            global $db_conn;

            $tripID =  $_POST['tripid'];
            $newArr =  $_POST['newarrivaldate'];
            $newDep =  $_POST['newdeparturedate'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE tripTable SET departuredate='" . $newDep . "', arrivaldate='" . $newArr . "' WHERE tripid='" . $tripID . "'");
            
            OCICommit($db_conn);
        }

        function handleSelectRequest() {
            global $db_conn;

            $city = $_POST['enterorigin'];

            $result = executePlainSQL("SELECT * FROM tripTable WHERE origin='" . $city . "'");

            echo "<br>Retrieved data from table tripTable:<br>";
            echo "<table>";
            echo "<tr><th>trip ID </th><th> origin </th><th> destination </th><th> departure date </th><th> arrival date </th><th> fee </th><th> transID </th></tr>";
         
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] ."</td></td>" ; 
            }

            echo "</table>";
        }

        function handleProjectRequest() {
            global $db_conn;

            $tripID =  $_POST['tripid'];

            $result = executePlainSQL("SELECT tripid, departuredate, arrivaldate, fee FROM tripTable WHERE tripid='" . $tripID . "'");

            echo "<br>Showing dep. and arr. dates, fee for for given trip:<br>";
            echo "<table>";
            echo "<tr><th>Trip ID </th><th>departure date </th><th> arrival date </th><th> fee </th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" ; 
            }
            echo "</table>";    
        }

        function handleJoinRequest() {
            global $db_conn;

            $city =  $_POST['givenorigin'];
            $dep =  $_POST['givendeparturedate'];

            $result = executePlainSQL("SELECT DISTINCT T.tripid, A.transid FROM Airplane A, tripTable T WHERE T.transid = A.transid AND T.origin='" . $city . "' AND T.departuretime='" . $dep . "' ");

            echo "<br>Showing airplane trip and trans ID from city at time:<br>";
            echo "<table>";
            echo "<tr><th> trip ID </th><th> airplane ID </th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] .  "</td></td>" ; 
            }
            echo "</table>";

        }

        function handleGroupByRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT destination, Min(fee) FROM tripTable GROUP BY destination");
            echo "<br>Finding cheapest trip to each city:<br>";
            echo "<table>";
            echo "<tr><th> Destination </th><th> Fee </th></tr>";
         
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></td>" ; 
            }

            echo "</table>";

        }

        function handleHavingRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT city, Count(*) FROM tripTable GROUP BY city HAVING Count(*) > 1 ");
            echo "<br>Listing # originating trips by city with at least two trips:<br>";
            echo "<table>";
            echo "<tr><th> City </th><th> # trips from here </th></tr>";
         
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] .  "</td></td>" ;
            }

            echo "</table>";
        }

        function handleDisplayTripRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM tripTable");
            
            echo "<br>Retrieved data from table tripTable:<br>";
            echo "<table>";
            echo "<tr><th>trip ID </th><th> origin </th><th> destination </th><th> departure date </th><th> arrival date </th><th> fee </th><th> transID </th></tr>";
         
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] ."</td></td>" ; 
            }

            echo "</table>";
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest() {
        if (connectToDB()) {
            if (array_key_exists('insertQueryRequest', $_POST)) {
                handleInsertRequest();
            } else if (array_key_exists('updateQueryRequest', $_POST)) {
                handleUpdateRequest();
            } else if (array_key_exists('deleteQueryRequest', $_POST)) {
                handleDeleteRequest();
            }else if (array_key_exists('selectQueryRequest', $_POST)) {
                handleSelectRequest();
            } else if (array_key_exists('projectQueryRequest', $_POST)) {
                handleProjectRequest();
            } else if (array_key_exists('joinQueryRequest', $_POST)) {
                handleJoinRequest();
            }

            disconnectFromDB();
        }
    }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('displayAccTuples', $_GET)){
                    handleDisplayAccRequest();
                } else if (array_key_exists('displayBDTuples', $_GET)){
                    handleDisplayBDRequest();
                } else if (array_key_exists('aggGroupBy', $_GET)){
                    handleGroupByRequest();
                }else if (array_key_exists('aggHaving', $_GET)){
                    handleHavingRequest();
                }/* else if (array_key_exists('nestedAggRequest', $_GET)){
                    handleNestedAggRequest();
                }else if (array_key_exists('dividing', $_GET)){
                    handleDivisionRequest();
                } */
                disconnectFromDB();
            }
        }

		if (isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['selectSubmit']) || isset($_POST['projectSubmit']) || isset($_POST['joinSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['displayAccRequest']) || isset($_GET['displayBDRequest']) || isset($_GET['groupByRequest']) || isset($_GET['havingRequest']) /* || isset($_GET['nestedAggRequest']) || isset($_GET['divisionRequest']) */) {
            handleGETRequest();
        } 
		?>
	</body>
</html>
