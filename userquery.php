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
        <title>User query</title>

    </head>

        <hr />

        <h2>Project User Payment</h2>
        <p>Find all cards relating to given email</p>

        <form method="POST" action="userquery.php"> <!--refresh page when submitted-->
            <input type="hidden" id="projectQueryRequest" name="projectQueryRequest">
            <input type="text" name="projectEmail" placeholder="email" ><br /><br />
            <input type="submit" value="view cards" name="projectSubmit"></p>
        </form>

        <hr />

        <h2>Join User Payment and Payment Type</h2>
        <p>Label card type accordingly</p>
        <form method="POST" action="userquery.php"> <!--refresh page when submitted-->
            <input type="hidden" id="joinQueryRequest" name="joinQueryRequest">
            <input type="text" name="projectEmail" placeholder="email" ><br /><br />
            <input type="submit" value="view cards with type" name="joinSubmit"></p>
        </form>

        <hr />

        <h2>Delete User payment given cardnumber</h2>
        <p>Label card type accordingly</p>
        <form method="POST" action="userquery.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteRequest" name="deleteRequest">
            <input type="text" name="cardNumber" placeholder="card number" ><br /><br />
            <input type="submit" value="delete payment" name="deleteSubmit"></p>
        </form>

        <hr />

        <h2>Show the total time for activities in each city</h2>
        <form method="GET" action="userquery.php"> <!--refresh page when submitted-->
            <input type="hidden" id="totalTimeCity" name="totalTimeCity">
            <input type="submit" name="totalTimeCity"></p>
        </form>

        <hr />

        <h2>Show accomodations in a city which had been reserved</h2>
        <p>Find accomodation in a given city which resevations more than specified number</p>
        <form method="POST" action="userquery.php"> <!--refresh page when submitted-->
            <input type="hidden" id="havingRequest" name="havingRequest">
            <input type="text" name="city" placeholder="city" ><br /><br />
            <input type="text" name="reservation" placeholder="0" ><br /><br />
            <input type="submit" value="Find Accomodations" name="havingSubmit"></p>
        </form>

        <hr />

        <h2>Find the city that has the least total time for activities amoung all cities</h2>
        <form method="GET" action="userquery.php"> <!--refresh page when submitted-->
            <input type="hidden" id="leastTime" name="leastTime">
            <input type="submit" name="leastTime"></p>
        </form>

        <hr />

        <h2>Find people who have experience all activities in a city</h2>
        <p>Display contact info of people to ask</p>
        <form method="POST" action="userquery.php"> <!--refresh page when submitted-->
            <input type="hidden" id="divisionRequest" name="divisionRequest">
            <input type="text" name="city" placeholder="city" ><br /><br />
            <input type="submit" value="Find People" name="divisionSubmit"></p>
        </form>

        <hr />


        <?php

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) {
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr); 
            

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); 
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); 
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

                    OCIBindByName($statement, $bind, $val);
                    unset ($val);
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement);
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
            $db_conn = OCILogon("ora_nicerca", "a24600165", "dbhost.students.cs.ubc.ca:1522/stu");

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

        function handleProjectRequest() {
            global $db_conn;

            $projectEmail = $_POST['projectEmail'];


            $result = executePlainSQL("SELECT email, cardNum, cvv  FROM Payment WHERE email='" . $projectEmail . "'");

            echo "<br>Payment info for email $projectEmail <br>";
            echo "<table>";
            echo "<tr><th>Email</th><th>Card Number</th><th>cvv</th></tr>"; // headings

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] .  "</td><td>" . $row[1] . "</td><td>" . $row[2] ."</td></tr>"; //or just use "echo $row[0]" per column
            }


            echo "</table>";
        }

        function handleJoinRequest() {
            global $db_conn;

            $projectEmail = $_POST['projectEmail'];


            $result = executePlainSQL("SELECT P.cardnum, PT.PAYMENTTYPE FROM Payment P, PaymentType PT WHERE email='" . $projectEmail . "' and P.cardNum = PT.cardNum");
            echo "<br>Payment info with type for email $projectEmail <br>";
            echo "<table>";
            echo "<tr><th>Card Number</th></tr>"; 

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; 
            }

            echo "</table>";
        }

        function handleHavingRequest() {
            global $db_conn;
            $city = $_POST['city'];
            $reservation = $_POST['reservation'];
   

            $result = executePlainSQL("SELECT a.accid, count (*) from reservation r, accommodation a where r.accid = a.accid and a.city = '" . $city . "' group by a.accid having count(*) >  $reservation order by count(*) desc");

            echo "<br>Cities with more than $reservation reservations <br>";
            echo "<table>";
            echo "<tr><th>Accomodation</th><th># of Reservations</th></tr>"; 



            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
            }

            echo "</table>";
        }



        function handletotalTimeCityRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT city, sum(timePeriod) from Activities group by city");

            echo "<br>Total time for activities per city<br>";
            echo "<table>";
            echo "<tr><th>City</th><th>Total Time</th></tr>"; // headings

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]" per column
            }

            echo "</table>";
        }

        function handleleastTimeRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT city, sum(timePeriod) from Activities group by city having sum(timePeriod)  < = all ( select sum(timePeriod) from Activities group by city )");

            echo "<br>City with the least total time for activities<br>";
            echo "<table>";
            echo "<tr><th>City</th><th>Total Time</th></tr>"; // headings

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]" per column
            }

            echo "</table>";
        }

        function handledivisionRequest() {
            global $db_conn;
            $city = $_POST['city'];
   

            $result = executePlainSQL("SELECT u.fname, u.lname, u.email from userTable u where not exists((Select a.activityID from activities a where city = '" . $city . "' ) minus ( select s.activityID from schedules s where s.email = u.email ))");

            echo "<br> People to contact for $city <br>";
            echo "<table>";
            echo "<tr><th>First Name</th><th>Last Name</th><th>email</th></tr>"; // headings



            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] ."</td></tr>"; //or just use "echo $row[0]" per column
            }

            echo "</table>";
        }


        function handleDeleteRequest() {
            global $db_conn;
            $cardNum = $_POST['cardNumber'];
            $result = executePlainSQL("DELETE from payment where cardnum = $cardNum");
            OCICommit($db_conn);

        }


        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('projectQueryRequest', $_POST)) {
                    handleProjectRequest();
                } else if (array_key_exists('joinQueryRequest', $_POST)) {
                    handleJoinRequest();
                } else if (array_key_exists('havingRequest', $_POST)) {
                    handleHavingRequest();
                } else if (array_key_exists('divisionRequest', $_POST)) {
                    handledivisionRequest();
                } else if (array_key_exists('deleteRequest', $_POST)) {
                    handleDeleteRequest();
                }

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
               if (array_key_exists('totalTimeCity', $_GET)){
                    handletotalTimeCityRequest();
                } else if (array_key_exists('leastTime', $_GET)){
                    handleleastTimeRequest();
                }
                    

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['joinSubmit']) || isset($_POST['insertSubmit'])|| isset($_POST['projectSubmit']) || isset($_POST['havingSubmit']) || isset($_POST['divisionSubmit']) || isset($_POST['deleteSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['totalTimeCity'])) {
            handleGETRequest();
        } else if (isset($_GET['leastTime'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>

