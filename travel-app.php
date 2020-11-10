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
        <title>CPSC 304 PHP/Oracle Demonstration</title>
    </head>

    <body>
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="travel-app.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <h2>Insert Values into User Table</h2>
        <form method="POST" action="travel-app.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Email: <input type="text" name="userEmail"> <br /><br />
            Phone#: <input type="text" name="userPhone"> <br /><br />
            Address: <input type="text" name="userAddress"> <br /><br />
            dob: <input type="text" name="userdob"> <br /><br />
            FirstName: <input type="text" name="userfname"> <br /><br />
            LastName: <input type="text" name="userlname"> <br /><br />
            Country: <input type="text" name="userCountry"> <br /><br />
            City: <input type="text" name="userCity"> <br /><br />
            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Update Name in DemoTable</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="travel-app.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Name: <input type="text" name="oldName"> <br /><br />
            New Name: <input type="text" name="newName"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Count the Tuples in DemoTable</h2>
        <form method="GET" action="travel-app.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>

        <h2>Show the Tuples in User Table</h2>
        <form method="GET" action="travel-app.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayEntry" name="displayEntry">
            <input type="submit" name="displayEntry"></p>
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

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            echo "<tr><th>EMAIL</th><th>Phone#</th></tr>Address</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row["EMAIL"] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]" 
            }

            echo "</table>";
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

        function handleUpdateRequest() {
            global $db_conn;

            $old_name = $_POST['oldName'];
            $new_name = $_POST['newName'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE demoTable SET name='" . $new_name . "' WHERE name='" . $old_name . "'");
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE demoTable");
            executePlainSQL("DROP TABLE Schedule");
            executePlainSQL("DROP TABLE Reservation");
            executePlainSQL("DROP TABLE Schedules");
            executePlainSQL("DROP TABLE Activities");
            executePlainSQL("DROP TABLE PaymentType");
            executePlainSQL("DROP TABLE Payment");
            executePlainSQL("DROP TABLE userTable");

            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");
            executePlainSQL("CREATE TABLE userTable (email char(50) PRIMARY KEY, phoneNum int, address char(50), dob int, fname char(20), lname char(20), city char(20), country char (20))");            
            executePlainSQL("CREATE TABLE Schedule (scheduleID char(50) PRIMARY KEY, description char(50), startDateTime int, endDateTime int, event_type char(20), email char(50), FOREIGN KEY (email) REFERENCES userTable(email) ON DELETE CASCADE)");
            executePlainSQL("CREATE TABLE Payment (paymentID char(50) PRIMARY KEY, cardNum int, cvv int, email char(50), FOREIGN KEY (email) REFERENCES userTable(email) ON DELETE CASCADE)");
            executePlainSQL("CREATE TABLE PaymentType (cardNum int PRIMARY KEY, paymentType char(20))");
            executePlainSQL("CREATE TABLE Activities (activityID char(50) PRIMARY KEY, timePeriod int, address char(50), city char(20), country char(20), description char(50))");
            executePlainSQL("CREATE TABLE Schedules (email char(50), activityID char(50), startDateTime int NOT NULL, endDateTime int NOT NULL, PRIMARY KEY (email, activityID), FOREIGN KEY (email) REFERENCES userTable(email) ON DELETE CASCADE, FOREIGN KEY (activityID) REFERENCES Activities(activityID) ON DELETE CASCADE)");
            executePlainSQL("CREATE TABLE Reservation (resId char(50) PRIMARY KEY, guestNum int, startDateTime int, endDateTime int, paymentID char(50), accID char(30), email char(50), FOREIGN KEY (paymentID) REFERENCES Payment ON DELETE CASCADE, FOREIGN KEY (email) REFERENCES userTable(email) ON DELETE CASCADE)");
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['userEmail'],
                ":bind2" => $_POST['userPhone'],
                ":bind3" => $_POST['userAddress'],
                ":bind4" => $_POST['userdob'],
                ":bind5" => $_POST['userfname'],
                ":bind6" => $_POST['userlname'],
                ":bind7" => $_POST['userCountry'],
                ":bind8" => $_POST['userCity'],
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into userTable values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8)", $alltuples);
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['userEmail'],
                ":bind2" => $_POST['userPhone'],
                ":bind3" => $_POST['userAddress'],
                ":bind4" => $_POST['userdob'],
                ":bind5" => $_POST['userfname'],
                ":bind6" => $_POST['userlname'],
                ":bind7" => $_POST['userCountry'],
                ":bind8" => $_POST['userCity'],
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into userTable values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7, :bind8)", $alltuples);
            OCICommit($db_conn);
        }


        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM demoTable");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in demoTable: " . $row[0] . "<br>";
            }
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('updateQueryRequest', $_POST)) {
                    handleUpdateRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                }

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                }else if (array_key_exists('displayEntry', $_GET)){
                 $result = executePlainSQL("SELECT * FROM userTable");
                 printResult($result);
                }
                    

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest'])) {
            handleGETRequest();
        } else if (isset($_GET['displayEntry'])) {
          handleGETRequest();
        }
		?>
	</body>
</html>
