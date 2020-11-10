drop table Schedule;
drop table Reservation;
drop table Schedules;
drop table Activities;
drop table PaymentType;
drop table bookBy;
drop table BookDirectly;
drop table StopsBy;
drop table AmenitiesIn; 
drop table House;
drop table Hotel;
drop table HotelRoom;
drop table Apartment;
drop table ApartmentRoom;
drop table Accommodation;
drop table Host;
drop table Payment;
drop table userTable;

drop table trip;
drop table airplane;
drop table taxi;
drop table bus;
drop table transportation;


create table transportation
(
    transID  int primary key,
    city     char(50) not null,
    country  char(50) not null,
    capacity int
);

grant select on transportation to public;

create table trip
(
    tripID        char(50) primary key,
    origin        char(20) not null,
    destination   char(20) not null,
    departureDate int      not null,
    arrivalDate   int      not null,
    fee real              not null,
    transID int,
    foreign key (transID) references transportation ON DELETE CASCADE
);

grant select on trip to public;

create table airplane
(
    transID int primary key,
    airLine char(50),
    model   char(50),
    foreign key (transID) references transportation ON DELETE CASCADE
);

grant select on airplane to public;

create table Taxi
(
    transID     int primary key,
    taxiCompany char(50),
    foreign key (transID) references transportation ON DELETE CASCADE
);

grant select on taxi to public;

create table bus
(
    transID int primary key,
    busLine char(50),
    foreign key (transID) references transportation ON DELETE CASCADE
);

grant select on taxi to public;


create table userTable
	(email char(50) PRIMARY KEY, 
    phoneNum int, 
    address char(50), 
    dob int, 
    fname char(20), 
    lname char(20), 
    city char(20), 
    country char (20));
 
grant select on userTable to public;

create table Schedule 
    (scheduleID char(50) PRIMARY KEY, 
    description char(50), 
    startDateTime int, 
    endDateTime int, 
    event_type char(20), 
    email char(50), 
    FOREIGN KEY (email) REFERENCES userTable(email) 
    ON DELETE CASCADE);

grant select on Schedule to public;

create table Payment 
    (paymentID char(50) PRIMARY KEY, 
    cardNum int, 
    cvv int, 
    email char(50), 
    FOREIGN KEY (email) REFERENCES userTable(email) 
    ON DELETE CASCADE);

grant select on Payment to public;

create table PaymentType 
    (cardNum int PRIMARY KEY, 
    paymentType char(20));

grant select on PaymentType to public;

create table Activities 
    (activityID char(50) PRIMARY KEY, 
    timePeriod int, 
    address char(50), 
    city char(20), 
    country char(20), 
    description char(50));

grant select on Activities to public;

create table Schedules 
    (email char(50), 
    activityID char(50), 
    startDateTime int NOT NULL, 
    endDateTime int NOT NULL, 
    PRIMARY KEY (email, activityID), 
    FOREIGN KEY (email) REFERENCES userTable(email) ON DELETE CASCADE, 
    FOREIGN KEY (activityID) REFERENCES Activities(activityID) ON DELETE CASCADE);

grant select on Schedules to public;

create table Host 
    (email char(50) PRIMARY KEY,
     phoneNum int unique,
     dob int);

 grant select on Host to public; 

create table Accommodation
    (accID char(30) PRIMARY KEY,
     addr char(80) unique, 
     city char(30) not null, 
     country char(30) not null, 
     capacity int,
     fee float    not null, 
     HostEmail char(50), 
     FOREIGN KEY (HostEmail) REFERENCES Host(email) 
     ON DELETE CASCADE);

 grant select on Accommodation to public; 

 create table Reservation 
    (resId char(50) PRIMARY KEY, 
    guestNum int, 
    startDateTime int, 
    endDateTime int, 
    paymentID char(50), 
    accID char(30), 
    email char(50), 
    FOREIGN KEY (paymentID) REFERENCES Payment ON DELETE CASCADE, 
    FOREIGN KEY (accID) REFERENCES Accommodation ON DELETE CASCADE, 
    FOREIGN KEY (email) REFERENCES userTable(email) ON DELETE CASCADE);

grant select on Reservation to public;

create table HotelRoom
    (roomNum int PRIMARY KEY, 
     hFloor int   not null);

 grant select on HotelRoom to public; 

create table Hotel 
    (accID char(30),
     roomNum int,
     hName char(30),
     PRIMARY KEY (accID, roomNum),
     FOREIGN KEY (accID) REFERENCES Accommodation(accID) ON DELETE CASCADE,
     FOREIGN KEY (roomNum) REFERENCES HotelRoom(roomNum) ON DELETE CASCADE);

 grant select on Hotel to public; 

create table ApartmentRoom
    (roomNum int PRIMARY KEY,
     aFloor int    not null);

 grant select on ApartmentRoom to public; 

create table Apartment 
    (accID char(30),
     roomNum int,
     petFriendly char(1),
     parkingFee real,
     PRIMARY KEY (accID, roomNum),
     FOREIGN KEY (accID) REFERENCES Accommodation(accID) ON DELETE CASCADE,
     FOREIGN KEY (roomNum) REFERENCES ApartmentRoom(roomNum) ON DELETE CASCADE);

 grant select on Apartment to public; 

create table House
    (accID char(30),
     yardSize real,
     parking char(20),
     PRIMARY KEY (accID),
     FOREIGN KEY (accID) REFERENCES Accommodation(accID) 
     ON DELETE CASCADE);

 grant select on House to public; 

 create table AmenitiesIn
    (accID char(30),
     type char(30), 
     hours char(50) default '24/7',
     cost real default 0.00,
     isPrivate char(1),
     PRIMARY KEY (accID, type),
     FOREIGN KEY (accID) REFERENCES Accommodation(accID) 
     ON DELETE CASCADE);

 grant select on AmenitiesIn to public; 


create table BookDirectly 
    (userEmail char(50),
    accID char(30),
    roomNum int,
    startDate int not null,
    endDate int not null,
    paymentID char(50) default 'Cash',
    PRIMARY KEY (userEmail, accID, roomNum),
    FOREIGN KEY (userEmail) REFERENCES userTable(email) ON DELETE CASCADE,
    FOREIGN KEY (accID) REFERENCES Accommodation(accID) ON DELETE CASCADE,
    FOREIGN KEY (roomNum) REFERENCES HotelRoom,
    FOREIGN KEY (paymentID) REFERENCES Payment); 

grant select on BookDirectly to public; 

create table StopsBy
    (transID int, 
    accID char(30), 
    roomNum int,
    firstBus int not null,
    lastBus int not null,
    PRIMARY KEY (transID, accID, roomNum),
    FOREIGN KEY (transID) REFERENCES bus,
    FOREIGN KEY (accID) REFERENCES Accommodation(accID) ON DELETE CASCADE,
    FOREIGN KEY (roomNum) REFERENCES HotelRoom);
grant select on StopsBy to public; 


create table bookBy(
    tripID char(50) references trip, 
    email  char(50) references userTable, 
    bookDate int, 
    primary key (tripID, email));

insert into userTable
values('mar_shal@outlook.com', 7786544789, '1509-5100 Granville Street, Vancouver',
967852800, 'Marshal', 'Squirret', 'Vancouver', 'Canada');

insert into userTable
values('fauna_16@gmail.com', 6046534781, '1403-5100 Granville Street, Vancouver',
954028800, 'Fauna', 'Decena', 'Burnaby', 'Canada');

insert into userTable
values('wh1tney@gmail.com', 7784446389, '965 No 5 Road, Richmond',
969148800, 'Whitney', 'Foster', 'Toronto', 'Canada');

insert into userTable
values('bill_ager@yahoo.ca', 7785123698, '304 Baker Street, Burnaby',
949363200, 'Bill', 'Dillar', 'Seattle', 'USA');

insert into userTable
values('kylefox@gmail.com', 7789874236, '2100 Eventide Ave, Vancouver',
976060800, 'Kyle', 'Foster', 'Vancouver', 'Canada');


insert into Schedule
values('a1oz6','Grouse Grind', 1601942400, 1601946000, 'activity', 'mar_shal@outlook.com');

insert into Schedule
values('a1ps0','Skiing class', 1599354000, 1599361200, 'activity', 'bill_ager@yahoo.ca');

insert into Schedule
values('z9ch3','Meeting', 1601942400, 1601946000, 'work', 'fauna_16@gmail.com');

insert into Schedule
values('g7pe8','Bus commute', 1599354000, 1599361200, 'travel', 'mar_shal@outlook.com');

insert into Schedule
values('m9wd9','Fancy dinner', 1601942400, 1601946000, 'activity', 'wh1tney@gmail.com');


insert into Payment
values('asd456', 4156239461332, 693, 'wh1tney@gmail.com');

insert into Payment
values('qwe789', 5516114627626359, 462, 'kylefox@gmail.com');

insert into Payment
values('efu264', 4651186395121, 963, 'bill_ager@yahoo.ca');

insert into Payment
values('ojk128', 341596324589621, 556, 'fauna_16@gmail.com');

insert into Payment
values('edf890', 6596832145698526, 762, 'bill_ager@yahoo.ca');


insert into PaymentType
values(4156239461332, 'Visa');

insert into PaymentType
values(5516114627626359, 'Mastercard');

insert into PaymentType
values(4651186395121, 'Visa');

insert into PaymentType
values(341596324589621, 'American Express');

insert into PaymentType
values(6596832145698526, 'Discover');



insert into Activities
values('jk89l', 120, 'Blackcomb mountain', 'Whistler', 'Canada', 'Ice Skiing class');

insert into Activities
values('to23d', 180, 'Whistler village', 'Whistler', 'Canada', 'Snowboarding');

insert into Activities
values('sn70e', 180, 'Grouse Mountain', 'Vancouver', 'Canada', 'Hiking');

insert into Activities
values('qp29l', 60, 'Downtown Vancouver', 'Vancouver', 'Canada', 'Hop-on hop-off');

insert into Activities
values('wp65s', 120, 'Downtown Vancouver', 'Vancouver', 'Canada', 'Dining out');


insert into Schedules
values('wh1tney@gmail.com', 'to23d', 1604566800, 1604577600);

insert into Schedules
values('wh1tney@gmail.com', 'sn70e', 1604577600, 1604588400);

insert into Schedules
values('kylefox@gmail.com', 'qp29l', 1604588400, 1604592000);

insert into Schedules
values('bill_ager@yahoo.ca', 'to23d', 1604592000, 1604602800);

insert into Schedules
values('fauna_16@gmail.com', 'qp29l', 1604563200, 1604566800);


insert into Host
values ('fairmontdude@gmail.com', 7786544789, 954028800);

insert into Host
values ('hostestofthemostest@hotmail.com', 6048394662, 909125040);

insert into Host
values ('hyattservice@gmail.com', 2668550710, 954028800);

insert into Host
values ('stevebuscemi@fakemail.com', 6204541799, 618129840);

insert into Host
values ('canadiehn@shaw.ca', 6042831261, 949363200);

insert into Host
values ('enya@gmail.com', 6042831111, 949363200);

/*Hotels*/ 
insert into Accommodation
values ('abc123', '900 W Georgia St', 'Vancouver', 'Canada', 4, 300.00, 'fairmontdude@gmail.com');

insert into Accommodation
values ('duuq9', '2424 Kalakaua Ave', 'Honolulu', 'USA', 2, 431.25, 'hyattservice@gmail.com');

insert into Accommodation 
values ('eagl76', 'Boomer Street', 'Los Angeles', 'USA', 4, 100.00, 'hostestofthemostest@hotmail.com');

insert into Accommodation 
values ('jdis32', '100 Front St', 'Toronto', 'Canada', 4, 100.00, 'fairmontdude@gmail.com');

insert into Accommodation
values ('waf14', '8510 Prospect St', 'New York City', 'USA', 3, 1234.56, 'stevebuscemi@fakemail.com');

/*Apartments*/
insert into Accommodation
values ('ydm89', '18222 89th Ave', 'London', 'United Kingdom', 1, 150.00, 'hostestofthemostest@hotmail.com');

insert into Accommodation 
values ('j1092', '123 Geography Rd', 'Beijing', 'China', 4, 100.00, 'stevebuscemi@fakemail.com');

insert into Accommodation 
values ('pais89', '222 Cold St', 'Squamish', 'Canada', 4, 100.00, 'canadiehn@shaw.ca');

insert into Accommodation 
values ('ja121', '55 Yellow Brick Rd', 'Yellowknife', 'Canada', 4, 100.00, 'canadiehn@shaw.ca');

insert into Accommodation 
values ('cpsc304', '666 Some Street', 'Vancouver', 'Canada', 4, 100.00, 'canadiehn@shaw.ca');

/*Houses*/
insert into Accommodation
values ('no06', '81 Guentzelstrasse St', 'Hosenfeld', 'Germany', 1, 150.00, 'hostestofthemostest@hotmail.com');

insert into Accommodation 
values ('ci92', '128 Via del Pontier St', 'Granitola', 'Italy', 4, 100.00, 'stevebuscemi@fakemail.com');

insert into Accommodation 
values ('ci111', '03-73A 68 Orchard Rd', 'Singapore', 'Singapore', 4, 100.00, 'hostestofthemostest@hotmail.com');

insert into Accommodation 
values ('ptnt9', '100 Sing St', 'Dublin', 'Ireland', 4, 100.00, 'enya@gmail.com');

insert into Accommodation 
values ('luxr2341', '3628 Ave de Port-Royal', 'Quebec City', 'Canada', 4, 100.00, 'canadiehn@shaw.ca');

insert into Reservation
values('7gyk90', 2, 1607266800, 1607338800, 'qwe789',
'abc123', 'kylefox@gmail.com');

insert into Reservation
values('1sth50', 5, 1607526000, 1607594400, 'efu264',
'duuq9', 'bill_ager@yahoo.ca');

insert into Reservation
values('5dtb37', 1, 1607526000, 1607594400, 'ojk128',
'j1092', 'fauna_16@gmail.com');

insert into Reservation
values('5fh49', 3, 1607266800, 1607338800, 'efu264',
'pais89', 'bill_ager@yahoo.ca');

insert into Reservation
values('2asd67', 4, 1607266800, 1607511600, 'ojk128',
'ja121', 'fauna_16@gmail.com');

insert into HotelRoom
values (304,3);

insert into HotelRoom
values (1428, 14);

insert into HotelRoom
values (1969, 19);

insert into HotelRoom
values (203,2);

insert into HotelRoom
values (2110, 21);

insert into Hotel
values ('abc123', 304, 'Fairmont Hotels and Resorts');

insert into Hotel
values ('duuq9', 1428, 'Hyatt Regency');

insert into Hotel
values ('eagl76', 1969, 'Hotel California');

insert into Hotel
values ('jdis32', 203, 'Fairmont Hotels and Resorts');

insert into Hotel
values ('waf14', 203, 'The Grand Budapest Hotel');

insert into ApartmentRoom
values (1412, 14);

insert into ApartmentRoom
values (302, 3);

insert into ApartmentRoom
values (102, 1);

insert into ApartmentRoom
values (203, 2);

insert into ApartmentRoom
values (2110, 21);

insert into Apartment
values ('ydm89', 1412, 1, 20.00);

insert into Apartment
values ('j1092', 302, 1, 0.00);

insert into Apartment
values ('pais89', 102, 0, 18.00);

insert into Apartment
values ('ja121', 203, 0, 100.23);

insert into Apartment
values ('cpsc304', 2110, 1, 587.94);

insert into House
values ('no06', 500.0, 'garage');

insert into House
values ('ci92', 20.5, 'street parking');

insert into House
values ('ci111', 0.00, 'none');

insert into House
values ('ptnt9', 51.231, 'driveway');

insert into House
values ('luxr2341', 100.0, 'driveway');


insert into AmenitiesIn
values ('abc123', 'pool', 'Weekdays: 7am-9pm, Weekends: 8am-12pm', 0.00, 0);

insert into AmenitiesIn
values ('abc123', 'bar', '18:00-02:00', 15.00, 0);

insert into AmenitiesIn
values ('abc123', 'snacks', '24/7', 10.00, 1);

insert into AmenitiesIn
values ('j1092', 'pool', '24/7', 0.00, 1);

insert into AmenitiesIn
values ('pais89', 'television', '24/7', 0.00, 1);

insert into AmenitiesIn
values ('abc123', 'gardens', '24/7', 0.00, 0);


insert into BookDirectly
values ('kylefox@gmail.com', 'abc123', 304, 1607266800, 1607338800, 'qwe789');

insert into BookDirectly
values ('bill_ager@yahoo.ca', 'duuq9', 1428, 1607526000, 1607594400, 'efu264');

insert into BookDirectly
values ('fauna_16@gmail.com', 'waf14', 304, 1607526000, 1607594400, 'ojk128');

insert into BookDirectly
values ('mar_shal@outlook.com', 'eagl76', 203, 1607266800, 1607338800 , null);

insert into BookDirectly
values ('fauna_16@gmail.com', 'waf14', 1428, 1607266800, 1607511600, 'ojk128');


insert into transportation
values (00034, 'Vancouver', 'Canada', 00034);

insert into transportation
values (00035, 'Vancouver', 'Canada', 40);

insert into transportation
values (04500, 'New York', 'USA', 300);

insert into transportation
values (42060, 'Honolulu', 'USA', 2);

insert into transportation
values (10101111111, 'Vancouver', 'Canada', 4);

insert into trip
values ('sv123', 'Vancouver', 'Tokyo', 1603421429, 1603421430, 1600, 00034);

insert into trip
values ('wv987', 'Vancouver', 'Berlin', 1603421429, 1603421430, 6700, 00035);

insert into trip
values ('vd182', 'Beijing', 'Tokyo', 1603421429, 1603671429, 5555, 04500);

insert into trip
values ('st234', 'Madrid', 'Buenos Aires', 1603454028, 1603421429, 5, 42060);

insert into trip
values ('mor918', 'Mordor', 'Skyrim', 4603454028, 5503454028, 69, 10101111111);

insert into airplane
values (00034, 'Air Canada', 'Boeing 747');

insert into airplane
values (00035, 'Air Somalia', 'Sopwith 2F.1 Camel');

insert into airplane
values (04500, 'Air Bruh', 'Covid-69');

insert into airplane
values (42060, 'Air Patrice', 'Bruh Moment 101');

insert into airplane
values (10101111111, 'Airplane Air', 'Plane Model 1');

insert into taxi
values (00034, 'F in chat Taxis');

insert into taxi
values (00035, 'Big Taxi');

insert into taxi
values (04500, 'EdRules Taxi');

insert into taxi
values (42060, 'Patrice Taxis');

insert into taxi
values (10101111111, 'Raymond Taxi Company');

insert into bus
values (00034, 'Sea buses');

insert into bus
values (00035, 'Memory bus');

insert into bus
values (04500, 'Bus ted');

insert into bus
values (42060, 'Translink');

insert into bus
values (10101111111, 'Toronto Buslines');

insert into StopsBy
values (00034, 'abc123', 304, 0700, 2200);

insert into StopsBy
values (04500, 'duuq9', 1428, 0623, 2032);

insert into StopsBy
values (42060, 'jdis32', 1969, 0618, 1500);

insert into StopsBy
values (10101111111, 'duuq9', 1428, 0543, 1500);

insert into StopsBy
values (00035, 'waf14', 203, 0923, 1922);

insert into bookBy
values ('mor918', 'bill_ager@yahoo.ca',1603421429, 90);
insert into bookBy
values ('st234', 'bill_ager@yahoo.ca',4603454028, 5);
insert into bookBy
values ('mor918', 'wh1tney@gmail.com',1603454028, 10);
insert into bookBy
values ('wv987', 'fauna_16@gmail.com',4603454028, 2);
insert into bookBy
values ('sv123', 'mar_shal@outlook.com',1603454028, 120);