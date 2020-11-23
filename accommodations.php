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
        <title>Accommodations page</title>
    </head>

    <body>
        <h1>Accommodations queries </h1>
        <hr />

        <h3>Insert Tuple into the Accommodation Table</h3>
        <form method="POST" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">

            Accommodation ID: <input type="text" name="accID"> <br /><br />
            Address: <input type="text" name="accAddr"> <br /><br />
            City: <input type="text" name="accCity"> <br /><br />
            Country: <input type="text" name="accCountry"> <br /><br />
            Capacity: <input type="text" name="accCapacity"> <br /><br />
            Fee: <input type="text" name="accFee"> <br /><br />
            Host Email: <input type="text" name="hostEmail"> <br /><br />
            
            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h3>Delete Tuple in the Accommodation Table</h3>
        <form method="POST" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">

            Accommodation ID: <input type="text" name="accID"> <br /><br />

            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

        <hr />

        <h3>Update the check-in and check-out dates of a direct hotel booking. </h3>
        <form method="POST" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">

            User: <input type="text" name="userEmail"> <br /><br />
            Hotel ID: <input type="text" name="hotelID"> <br /><br />
            roomNum: <input type="text" name="roomNum"> <br /><br />
            New check-in date: <input type="text" name="in"> <br /><br />
            New check-out date: <input type="text" name="out"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />


        <h3>Select an accommodation located at your travel destination</h3>
        <form method="POST" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="selectQueryRequest" name="selectQueryRequest">
            
            City: <input type="text" name="enterCity"> <br /><br />
            Country: <input type="text" name="enterCountry"> <br /><br />

            <input type="submit" value="Select" name="selectSubmit"></p>
        </form>

        <hr />

        <h3> For a given accommodation, project its ID, all of its amenities and their available hours </h3>
        <form method="POST" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="projectQueryRequest" name="projectQueryRequest">

            Accommodation ID: <input type="text" name="accID"> <br /><br />

            <input type="submit" value="Project" name="projectSubmit"></p>
        </form>

        <hr />

        <h3> Find all hotel names and addresses in a given city and country that has a pool (join)</h3>
        <form method="POST" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">

            City: <input type="text" name="city"> <br /><br />
            Country: <input type="text" name="country"> <br /><br />

            <input type="submit" value="Join" name="joinSubmit"></p>
        </form>

        <hr />

        <h3> Find the cheapest accommodation for each country (aggr with Group By) </h3>
        <form method="GET" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="groupByRequest" name="groupByRequest">
            <input type="submit" name="aggGroupBy"></p>
        </form>

  
        <hr />

        <h3> List countries and the number of accommodations they have, but only if they have at least three accommodations (aggr with Having) </h3>
        <form method="GET" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="havingRequest" name="havingRequest">
            <input type="submit" name="aggHaving"></p>
        </form>

        <hr />
      
        <h3>  Find the average accommodation fee of each host owning at least three accommodations (nested aggr) </h3>
        <form method="GET" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="nestedAggRequest" name="nestedAggRequest">
            <input type="submit" name="nestedAgg"></p>
        </form>

        <hr />

        <h3> Find the names of hotels that have every amenity that any Fairmont hotel has (division) </h3>
        <form method="GET" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="divisionRequest" name="divisionRequest">
            <input type="submit" name="dividing"></p>
        </form>

        <hr />

        <h2>Show the Tuples in the Accommodation Table (use to see Insertion and Deletion) </h2>
        <form method="GET" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayAccRequest" name="displayAccRequest">
            <input type="submit" name="displayAccTuples"></p>
        </form>

        <h2>Show the Tuples in the BookDirectly Table (use to see Update) </h2>
        <form method="GET" action="accommodations.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayBDRequest" name="displayBDRequest">
            <input type="submit" name="displayBDTuples"></p>
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
            $db_conn = OCILogon("ora_kwonny", "a25744160", "dbhost.students.cs.ubc.ca:1522/stu");

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
            
            $tuple = array (
                ":bind1" => $_POST['accID'],
                ":bind2" => $_POST['accAddr'],
                ":bind3" => $_POST['accCity'],
                ":bind4" => $_POST['accCountry'],
                ":bind5" => $_POST['accCapacity'],
                ":bind6" => $_POST['accFee'],
                ":bind7" => $_POST['hostEmail'],
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("INSERT INTO Accommodation VALUES (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)", $alltuples);
            OCICommit($db_conn);
        }

        function handleDeleteRequest() {
            global $db_conn;

            $accID =  $_POST['accID'];

            executePlainSQL("DELETE FROM Accommodation WHERE accID='" . $accID . "'");
            OCICommit($db_conn);
        }


        function handleUpdateRequest() {
            global $db_conn;

            $user =  $_POST['userEmail'];
            $hotelID =  $_POST['hotelID'];
            $roomNum =  $_POST['roomNum'];
            $in =  $_POST['in'];
            $out =  $_POST['out'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE BookDirectly SET startDate='" . $in . "', endDate='" . $out . "' WHERE userEmail='" . $user . "' AND accID='" . $hotelID . "' AND roomNum='" . $roomNum . "'" );
            
            OCICommit($db_conn);
        }

        function handleSelectRequest() {
            global $db_conn;

            $city = $_POST['enterCity'];
            $country = $_POST['enterCountry'];

            $result = executePlainSQL("SELECT * FROM Accommodation WHERE city='" . $city . "' AND country='" . $country . "'");

            echo "<br>Retrieved data from the Accommodation table:<br>";
            echo "<table>";
            echo "<tr><th>Accommodation ID </th><th> Address </th><th> City </th><th> Country </th><th> Capacity </th><th> Fee </th><th> Host </th></tr>";
         
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] ."</td></td>" ; 
            }

            echo "</table>";
        }

        function handleProjectRequest() {
            global $db_conn;

            $accID =  $_POST['accID'];

            $result = executePlainSQL("SELECT accID, type, hours FROM AmenitiesIn WHERE accID='" . $accID . "'");

            echo "<br>Showing all amenities for given accommodation:<br>";
            echo "<table>";
            echo "<tr><th>Accommodation ID </th><th> Type </th><th> Hours </th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td></td>" ; 
            }
            echo "</table>";    
        }

        function handleJoinRequest() {
            global $db_conn;

            $city =  $_POST['city'];
            $country =  $_POST['country'];

            $result = executePlainSQL("SELECT DISTINCT H.hname, A.addr FROM Accommodation A, AmenitiesIn AI, Hotel H WHERE H.accID = A.accID AND A.accID = AI.accID AND AI.type= 'pool' AND A.city='" . $city . "' AND A.country='" . $country . "' ");

            echo "<br>Showing accommodations with a pool:<br>";
            echo "<table>";
            echo "<tr><th> Name </th><th> Street Address </th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] .  "</td></td>" ; 
            }
            echo "</table>";

        }

        function handleGroupByRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT country, Min(fee) FROM Accommodation GROUP BY country");
            echo "<br>Finding cheapest accommodation for each country:<br>";
            echo "<table>";
            echo "<tr><th>Country ID </th><th> Fee </th></tr>";
         
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></td>" ; 
            }

            echo "</table>";

        }

        function handleHavingRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT country, Count(*) FROM Accommodation GROUP BY country HAVING Count(*) > 2 ");
            echo "<br>Listing by countries with at least three accommodations:<br>";
            echo "<table>";
            echo "<tr><th> Country </th><th> # accommodations </th></tr>";
         
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] .  "</td></td>" ;
            }

            echo "</table>";
        }


        function handleNestedAggRequest() {
            global $db_conn;
            
            $result = executePlainSQL("SELECT A.HostEmail, AVG(A.fee)
                                        FROM Accommodation A
                                        GROUP BY A.HostEmail
                                        HAVING 3 < (SELECT Count(*) 
                                                    FROM Accommodation A1
                                                    WHERE A1.HostEmail = A.HostEmail)");

            echo "<br>Listing by host:<br>";
            echo "<table>";
            echo "<tr><th> Host </th><th> Average fee </th></tr>";
         
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] .  "</td></td>" ;
            }

            echo "</table>";

        }


        function handleDivisionRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT H.hname 
                                       FROM  Hotel H
                                       WHERE NOT EXISTS ( (SELECT DISTINCT AI.type 
                                                           FROM Hotel H1, AmenitiesIn AI
                                                           WHERE H1.accID = AI.accID AND H1.hname = 'Fairmont Hotels and Resorts')
                                                            MINUS
                                                          (SELECT  AI1.type 
                                                           FROM  AmenitiesIn AI1
                                                           WHERE H.accID = AI1.accID ))" );

            echo "<br>Listing hotel names:<br>";
            echo "<table>";
            echo "<tr><th> Hotel Names </th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></td>" ; 
            }
            echo "</table>";

        }
        

        function handleDisplayAccRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM Accommodation");
            
            echo "<br>Retrieved data from table Accommodation:<br>";
            echo "<table>";
            echo "<tr><th>Accommodation ID </th><th> Address </th><th> City </th><th> Country </th><th> Capacity </th><th> Fee </th><th> Host </th></tr>";
         
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td><td>" . $row[6] ."</td></td>" ; 
            }

            echo "</table>";
        }

        function handleDisplayBDRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM BookDirectly");
            
            echo "<table>";
            echo "<tr><th> User </th><th> Accommodation ID </th><th> Room# </th><th> Check-in </th><th> Check-out </th><th> Payment </th></tr>";
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td><td>" . $row[4] . "</td><td>" . $row[5] . "</td></td>" ; 
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
                }else if (array_key_exists('nestedAggRequest', $_GET)){
                    handleNestedAggRequest();
                }else if (array_key_exists('dividing', $_GET)){
                    handleDivisionRequest();
                }
                disconnectFromDB();
            }
        }

		if (isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['selectSubmit']) || isset($_POST['projectSubmit']) || isset($_POST['joinSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['displayAccRequest']) || isset($_GET['displayBDRequest']) || isset($_GET['groupByRequest']) || isset($_GET['havingRequest']) || isset($_GET['nestedAggRequest']) || isset($_GET['divisionRequest'])) {
            handleGETRequest();
        } 

		?>
	</body>
</html> 

