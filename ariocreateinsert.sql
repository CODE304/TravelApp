drop table stopsBy;
drop table trip;
drop table transportaton;
drop table airplane;
drop table taxi;
drop table bus;

create table stopsBy (
	transID int,
	accID char (30),
	roomNum int,
	firstBus int,
	lastBus int,
	primary key (transID,accID,roomNum),
	foreign key (transID) references bus,
	foreign key (accID) references accomodation ON DELETE CASCADE,
	foreign key (roomNum references hotelRoom)
);

grant select on stopsBy to public;

create table trip(
	tripID char (50) primary key,
	origin char (20) not null,
	destination char(20) not null,
	departureDate int not null,
	arrivalDate int not null,
	fee: real not null,
	transID: int,
	foreign key (transID) references transportation ON DELETE CASCADE
);

grant select on trip to public;

create table transportation(
	transID int priamry key,
	city char (50) not null,
	country char (50) not null,
	capacity int
);

grant select on transportation to public;

create table airplane(
	transID int primary key,
	airLine char(50),
	model char(50),
	foreign key (transID) references transportation ON DELETE CASCADE
);

grant select on airplane to public;

create table Taxi(
	transID int primary key,
	taxiCompany char (50),
	foreign key(transID) references transportation ON DELETE CASCADE
);

grant select on taxi to public;

create table bus(
	transID int primary key,
	busLine char (50),
	primary key (transID),
	foreign key (transID) references transportation ON DELETE CASCADE)
);

grant select on taxi to public;


insert into stopsBy
values(00034, abc123, 304, 0700, 2200);

insert into stopsBy
values(67890212, duuw9, 1428, 0623, 2032);

insert into stopsBy
values(2156365, eagl76, 1969, 0618, 1500);

insert into stopsBy
values(1414126222, duuw9, 1428, 0543, 1500);

insert into stopsBy
values(1, waf14, 201, 0923, 1922);

insert into trip
values("sv123", "Vancouver", "Tokyo", 1603421429, 1603421430, 1600, 00034);

insert into trip
values("wv987", "Vancouver", "Berlin", 1603421429, 1603421430, 6700, 00034);

insert into trip
values("vd182", "Beijing", "Tokyo", 1603421429, 1603671429, 5555, 04500);

insert into trip
values("st234", "Madrid", "Buenos Aires", 1603454028, 1603421429, 5, 42060);

insert into trip
values("mor918", "Mordor", "Skyrim", 4603454028, 5503454028, 69, 10101111111);

insert into transportation
values(00034, "Vancouver", "Canada", 40);

insert into transportation
values(00034, "Vancouver", "Canada", 40);

insert into transportation
values(04500, "New York", "USA", 300);

insert into transportation
values(42060, "Honolulu", "USA", 2);

insert into transportation
values(10101111111, "Vancouver", "Canada", 4);

insert into airplane
values(10101111111, "Air Canada", "Boeing 747");

insert into airplane
values(67890212, "Air Somalia", "Sopwith 2F.1 Camel");

insert into airplane
values(4539311, "Air Bruh", "Covid-69");

insert into airplane
values(42069, "Air Patrice", "Bruh Moment 101");

insert into airplane
values(1111111111, "Airplane Air", "Plane Model 1");

insert into taxi
values(42060,"F in chat Taxis");

insert into taxi
values(67890212,"Big Taxi");

insert into taxi
values(2156365,"EdRules Taxi");

insert into taxi
values(13663,"Patrice Taxis");

insert into taxi
values(111100000111,"Raymond Taxi Company");

insert into bus
values("00034", "Sea buses");

insert into bus
values("67890212", "Memory bus");

insert into bus
values("2156365 ", "Bus ted");

insert into bus
values("1414126222", "Translink");

insert into bus
values("1", "Toronto Buslines");
