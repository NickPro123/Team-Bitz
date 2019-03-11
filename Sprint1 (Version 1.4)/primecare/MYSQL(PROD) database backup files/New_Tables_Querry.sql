-- Creates treatment table
CREATE table TREATMENT(
treatmentID	int	primary key	not null auto_increment,
treatmentName	varchar(100)	not null,
recommendedAmount	decimal(20,1)	not null
);
ALTER TABLE TREATMENT auto_increment = 1;

-- Creates PatientAssignedToTreatment table
create table PatientAssignedToTreatment(
patientID	int not null,
treatmentID	int not null,
primary key(patientID,treatmentID),
constraint FK_patientID foreign key(patientID) references PATIENT(patientID) on update no action on delete no action,
constraint FK_treatmentID	foreign key(treatmentID) references TREATMENT(treatmentID) on update no action on delete no action
);

-- Creates Test table
create table TEST(
testID	int	primary key	not null auto_increment,
testName	varchar(100)	not null,
testResult	varchar(100)	null
);
ALTER TABLE TEST auto_increment = 1;

-- Creates PatientAssignedToTest table
create table PatientAssignedToTest(
patientID	int not null,
testID	int	not null,
primary key(patientID,testID),
constraint FK_patientID foreign key(patientID) references PATIENT(patientID) on update no action on delete no action,
constraint FK_testID foreign key(testID) references TEST(testID) on update no action on delete no action
);

-- Fills treatment table with data
insert into TREATMENT(treatmentName,recommendedAmount) VALUES
('treatment1', 1.5),
('treatment2', 2.5);

-- Fills PatientAssignedToTreatment table with data
insert into PatientAssignedToTreatment(patientID,treatmentID) VALUES
(1,1),
(2,2);

-- Fills Test table with data
insert into TEST(testName,testResult) VALUES
('Test1','Healthy'),
('Test2','Cancer');

-- Fills PatientAssignedToTest table with data
insert into PatientAssignedToTest(patientID,testID) VALUES
(1,1),
(2,2);