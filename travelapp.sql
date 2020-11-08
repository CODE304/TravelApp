drop table Schedule;
drop table Reservation;
drop table Schedules;
drop table Activities;
drop table PaymentType;
drop table Payment;
drop table userTable;

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

create table Reservation 
    (resId char(50) PRIMARY KEY, 
    guestNum int, 
    startDateTime int, 
    endDateTime int, 
    paymentID char(50), 
    accID char(30), 
    email char(50), 
    FOREIGN KEY (paymentID) REFERENCES Payment ON DELETE CASCADE, 
    FOREIGN KEY (email) REFERENCES userTable(email) ON DELETE CASCADE);

grant select on Reservation to public;

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


insert into Reservation
values('7gyk90', 2, 1607266800, 1607338800, 'qwe789',
'kol12', 'kylefox@gmail.com');

insert into Reservation
values('1sth50', 5, 1607526000, 1607594400, 'efu264',
'gyj63', 'bill_ager@yahoo.ca');

insert into Reservation
values('5dtb37', 1, 1607526000, 1607594400, 'ojk128',
'swe963', 'fauna_16@gmail.com');

insert into Reservation
values('5fh49', 3, 1607266800, 1607338800, 'efu264',
'gil456', 'bill_ager@yahoo.ca');

insert into Reservation
values('2asd67', 4, 1607266800, 1607511600, 'ojk128',
'lp163', 'fauna_16@gmail.com');
