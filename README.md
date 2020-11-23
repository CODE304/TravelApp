# TravelApp

## How to run:
* clone the repo on your local machine
  * change all instances of OCI_logon with your respective username
* transfer the whole Travelapp folder to your ugrad server
  * scp -r TravelApp <CWLid>@remote.students.cs.ubc.ca:/home/n/<CWLid>/public_html
* extract the folder to public_html 
  * mv ~/public_html/TravelApp/* ~/public_html/
* Go to your public_html folder and run the following to change file permissions
  * ./execute.sh
* open sqlplus and run the sql script to create and populate the tables
  * sqlplus ora_<CWLid>@stu
  * start travelapp.sql 
* quit sqlplus to commit all the changes you made 

## How to visit the pages of the project
After doing all the steps above, you are now ready to view the pages that builds up this project.

* Login page:
  *  a working version of it: https://www.students.cs.ubc.ca/~nicerca/login.php

* Calendar page:
  *  a working version of it : https://www.students.cs.ubc.ca/~nicerca/index.php
  *  please make sure you run the ./execute file to give permissions to all needed php files, if this doesn't work run all commands found in the execute.sh         individually

* User Query page:
  * a working version of it : https://www.students.cs.ubc.ca/~nicerca/userquery.php

* Accomodations page: 
  * a working version of it : https://www.students.cs.ubc.ca/~kwonny/accommodations.php

* Trip page: 
  * a working version of it : https://www.students.cs.ubc.ca/~rostam2/trip.php
  
## Acknowledgements/ Licenses/ special thanks:
* open source code for Log in page can be found in: https://www.tutorialrepublic.com/codelab.php?topic=bootstrap&file=elegant-modal-login-form-with-avatar-icon
* calendar interface (licence included in repo) can be found in: https://code.daypilot.org/17910/html5-event-calendar-open-source
