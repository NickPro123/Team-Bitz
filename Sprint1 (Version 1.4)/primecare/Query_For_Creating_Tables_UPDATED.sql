# Uncomment below if want to drop the tables.
# Drop table DoctorAssignedToPatient;
# Drop table PATIENT;
# Drop table PRESCRIPTION;
# Drop table DRUG;
# Drop table ROOM;
# Drop table `user`;
# Drop table DEPARTMENT;

create table DEPARTMENT(
departmentID	int primary key not null auto_increment,
departmentName	varchar(100)	not null
);
ALTER TABLE DEPARTMENT auto_increment = 1;

create table `user`(
userID	int primary key not null auto_increment,
firstName	varchar(100) not null,
lastName	varchar(100) not null,
`type`	varchar(100) null,
departmentID	int null,
password	varchar(100) not null,
salt1	varchar(100) not null,
salt2	varchar(100) not null,
userName	varchar(100) not null,
constraint FK_departmentID foreign key(departmentID) references DEPARTMENT(departmentID) on update No action
						   on delete no action
);
ALTER TABLE user auto_increment = 1;

create table ROOM(
roomNumber	int primary key not null,
departmentID	int	not null,
description	varchar(200)	not null,
maxCapacity	int	not null,
patientsAssigned	int	null,
constraint FK_departmentID foreign key(departmentID) references DEPARTMENT(departmentID) on update No action
						   on delete no action
);

create table DRUG(
drugID	int	primary key	not null auto_increment,
medicineName	varchar(100)	not null,
amountRemaining	int	not null,
dose	decimal(20,1)	not null,
warning	varchar(200)	not null,
description	varchar(200)	not null
);
ALTER TABLE DRUG auto_increment = 1;

create table PRESCRIPTION(
doctorOrderNumber int primary key not null,
orderDetails	varchar(200)	not null,
drugID	int not null,
constraint FK_drugID foreign key(drugID) references DRUG(drugID) on update no action on delete no action
);

# For this table, i do not know what the foreign key mainTreatment is referencing, so I did not include it.
create table PATIENT(
patientID	int primary key	not null auto_increment,
firstName	varchar(100)	not null,
lastName	varchar(100)	not null,
roomNumber	int not null,
constraint FK_roomNumber foreign key(roomNumber) references ROOM(roomNumber) on update no action on delete no action
);
ALTER TABLE PATIENT auto_increment = 1;

create table DoctorAssignedToPatient(
patientID	int not null,
userID	int not null,
primary key(patientID, userID),
constraint FK_patient foreign key(patientID) references PATIENT(patientID) on update No action on delete no action,
constraint FK_User foreign key(userID) references user(userID) on update No action on delete no action
);