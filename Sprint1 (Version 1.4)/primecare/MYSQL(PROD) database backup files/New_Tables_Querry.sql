-- Creates treatment table
CREATE table treatment(
treatmentID	int	primary key	not null auto_increment,
treatmentName	varchar(100)	not null,
recommendedAmount	decimal(20,1)	not null
);
ALTER TABLE treatment auto_increment = 1;

-- Creates PatientAssignedToTreatment table
create table patientassignedtotreatment(
patientID	int not null,
treatmentID	int not null,
primary key(patientID,treatmentID),
constraint FK_patientID foreign key(patientID) references patient(patientID) on update no action on delete no action,
constraint FK_treatmentID	foreign key(treatmentID) references treatment(treatmentID) on update no action on delete no action
);

-- Creates Test table
create table test(
testID	int	primary key	not null auto_increment,
testName	varchar(100)	not null,
testResult	varchar(100)	null
);
ALTER TABLE test auto_increment = 1;

-- Creates PatientAssignedToTest table
create table patientassignedtotest(
patientID	int not null,
testID	int	not null,
primary key(patientID,testID),
constraint FK_patientID foreign key(patientID) references patient(patientID) on update no action on delete no action,
constraint FK_testID foreign key(testID) references test(testID) on update no action on delete no action
);

-- Fills treatment table with data
insert into treatment(treatmentName,recommendedAmount) VALUES
('treatment1', 1.5),
('treatment2', 2.5);

-- Fills PatientAssignedToTreatment table with data
insert into patientassignedtotreatment(patientID,treatmentID) VALUES
(1,1),
(2,2);

-- Fills Test table with data
insert into test(testName,testResult) VALUES
('Test1','Healthy'),
('Test2','Cancer');

-- Fills PatientAssignedToTest table with data
insert into patientassignedtotest(patientID,testID) VALUES
(1,1),
(2,2);
