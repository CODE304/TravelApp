/*drop table BookDirectly;*/
/*drop table StopsBy; */

drop table AmenitiesIn; 
drop table House;
drop table Hotel;
drop table HotelRoom;
drop table Apartment;
drop table ApartmentRoom;
drop table Accommodation;
drop table Host;


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

/*
create table BookDirectly 
    (userEmail char(50),
    accID char(30),
    roomNum int,
    startDate int not null,
    endDate int not null,
    paymentID char(50) default 'Cash',
    PRIMARY KEY (userEmail, accID, roomNum),
    FOREIGN KEY (userEmail) REFERENCES user(email) ON DELETE CASCADE,
    FOREIGN KEY (accID) REFERENCES Accommodation(accID) ON DELETE CASCADE,
    FOREIGN KEY (roomNum) REFERENCES HotelRoom,
    FOREIGN KEY (paymentID) REFERENCES paymentmethod ON DELETE SET default); 

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
*/


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

/*
insert into BookDirectly
values ('kylefox@gmail.com', 'abc123', 304, 1607266800, 1607338800, 'qwe789');

insert into BookDirectly
values ('bill_ager@yahoo.ca', 'duuq9', 1428, 1607526000, 1607594400, 'efu264');

insert into BookDirectly
values ('fauna_16@gmail.com', 'waf14', 201, 1607526000, 1607594400, 'ojk128');

insert into BookDirectly
values ('someboomer@gmail.com', 'eagl76', 1969, 1607266800, 1607338800, 'Cash');

insert into BookDirectly
values ('fauna_16@gmail.com', 'waf14', 201, 1607266800, 1607511600, 'ojk128');

insert into StopsBy
values (00034, 'abc123', 304, 0700, 2200);

insert into StopsBy
values (67890212, 'duuw9', 1428, 0623, 2032);

insert into StopsBy
values (2156365, 'eagl76', 1969, 0618, 1500);

insert into StopsBy
values (1414126222, 'duuw9', 1428, 0543, 1500);

insert into StopsBy
values (1, 'waf14', 201, 0923, 1922);
*/

