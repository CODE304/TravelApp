drop table bookBy;
drop table trip;
drop table airplane;
drop table taxi;
drop table bus;
drop table transportation;
drop table userTable;

create table bookBy
(
    tripID char(50) references trip,
    email  char(50) references userTable,
    bookDate int,
    primary key (tripID, email)
);

grant select on bookBy to public;

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
    fee          real      not null,
    transID      int,
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

grant select on bus to public;

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


insert into bookBy
values ('mor918', 'bill_ager@yahoo.ca',1603421429);

insert into bookBy
values ('st234', 'bill_ager@yahoo.ca',4603454028);

insert into bookBy
values ('mor918', 'wh1tney@gmail.com',1603454028);

insert into bookBy
values ('wv987', 'fauna_16@gmail.com',4603454028);

insert into bookBy
values ('sv123', 'mar_shal@outlook.com',1603454028);

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
