-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 05:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seatingarrangement`
--

-- --------------------------------------------------------

--
-- Table structure for table `classroom`
--

CREATE TABLE `classroom` (
  `Classroom_ID` varchar(10) NOT NULL,
  `Classroom_Name` varchar(20) NOT NULL,
  `Available_Seat` int(10) NOT NULL,
  `Assigned_At` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `classroom`
--

INSERT INTO `classroom` (`Classroom_ID`, `Classroom_Name`, `Available_Seat`, `Assigned_At`) VALUES
('DT101', 'DT 101', 40, '0000-00-00'),
('DT102', 'DT 102', 50, '0000-00-00'),
('DT103', 'DT 103', 60, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `fourthyear`
--

CREATE TABLE `fourthyear` (
  `Roll_No` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `ID` int(10) NOT NULL,
  `uname` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`ID`, `uname`, `pass`) VALUES
(1, 'admin', 'admin'),
(2, 'uday15-9779@diu.edu.bd', '172-15-9779'),
(3, 'neelima15-10150@diu.edu.bd', '172-15-10150'),
(4, 'syeda15-10000@diu.edu.bd', '172-15-10000');

-- --------------------------------------------------------

--
-- Table structure for table `secondyear`
--

CREATE TABLE `secondyear` (
  `Roll_No` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `secondyear`
--

INSERT INTO `secondyear` (`Roll_No`, `first_name`, `last_name`) VALUES
(24506263, 'Aathish', 'A'),
(24506264, 'Aravinth', 'P'),
(24506265, 'Bhuvaneshwari', 'R'),
(24506266, 'Fawaz', 'A'),
(24506267, 'Gowri', 'S'),
(24506268, 'Guna', 'N'),
(24506269, 'Harish', 'S'),
(24506270, 'Jegannath', 'V'),
(24506271, 'Joel', 'A'),
(24506272, 'Manikandan', 'C'),
(24506274, 'Mohamed Tanish', 'H'),
(24506275, 'Navven Kumar', 'T'),
(24506276, 'Naveeth Jassim', 'A'),
(24506279, 'Robert Sundar Singh', 'R'),
(24506280, 'Rocky', 'M'),
(24506281, 'Sanjay', 'S'),
(24506282, 'Tamil Selvan', 'C'),
(24506283, 'Thamas Johnson', 'U'),
(24506284, 'Thangamathi Raja', 'K'),
(24507283, 'Vishalachi', 'R');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `SubjectCode` varchar(20) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `ShortNames` varchar(10) NOT NULL,
  `program_ID` int(20) NOT NULL,
  `level_ID` int(20) NOT NULL,
  `term_ID` int(20) NOT NULL,
  `Lecturer_Id` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`SubjectCode`, `Name`, `ShortNames`, `program_ID`, `level_ID`, `term_ID`, `Lecturer_Id`) VALUES
('AI 100', 'Arttificial Intellengence', 'AI 001', 2, 1, 1, '1005'),
('CC 200', 'Cloud Computing', ' CC 002', 2, 1, 1, '1002'),
('MM 300', 'Multi-Media', 'MM 003', 2, 1, 1, '1004'),
('CHN 400', 'Computer Hardwere And Networks', 'CHN 004', 2, 1, 1, '1001'),
('IS 500', 'Innovation And Startups', 'IS 005', 2, 1, 1, '1010'),
('IOT 600', 'Internet of things and Digital twins', 'IOT 006', 2, 1, 2, '1001'),
('RDBMS 101', 'Reletional database management system', 'RDBMS 010', 2, 1, 2, '1005'),
('DLD 201', 'Digital Logic design', 'DLD 020', 2, 1, 2, '1005'),
('OS 301', 'Operating System', 'OS 030', 2, 1, 2, '1009'),
('DLD 211', 'Digital logic Design Lab', 'DLDL 120', 2, 1, 2, '1008'),
('WD 501', 'Web Designing', 'WD 050', 2, 1, 3, '1009'),
('CP 601', 'C-Programming', 'CP 060', 2, 1, 3, '1005'),
('CNS 110', 'Computer networks and Security', 'CNS 100', 2, 1, 3, '1001'),
('DSP 210', 'Data Stracture using Python', 'DSP 200', 2, 1, 3, '1001'),
('Java 310', 'Java Programming', 'JAVA 300', 2, 1, 3, '1004'),
('PY 410', 'Python Programming', 'PY 400', 2, 2, 1, '1001'),
('EPT 510', 'E-Publishing Tools', 'EPT 500', 2, 2, 1, '1007'),
('SPL 610', 'Scripting Language', 'SPL 600', 2, 2, 1, '1010'),
('HAW 700', 'Health and Welfare', 'HAW 007', 2, 2, 2, '1001'),
('CSE311L', 'Database Management System Lab', 'CSE 311L', 2, 2, 2, '1010'),
('CSE312', 'Numerical Methods', ' CSE 312', 2, 2, 2, '1005'),
('CSE331', 'Compiler Design', 'CSE 331', 2, 2, 3, '1002'),
('CSE331L', 'Compiler Design Lab', 'CSE 331L', 2, 2, 3, '1007'),
('CSE413', 'Simulation and Modeling', 'CSE 413', 2, 2, 3, '1006'),
('CSE413L', 'Simulation and Modeling Lab', ' CSE 413L', 2, 2, 3, '1003'),
('CSE323', 'Operating System', 'CSE 323', 2, 2, 3, '1010'),
('CSE323L', 'Operating System Lab', 'CSE 323L', 2, 2, 3, '1003'),
('CSE321', 'System Analysis and Design', 'CSE 321', 2, 3, 1, '1003'),
('CSE321L', 'System Analysis and Design Lab', 'CSE 321L', 2, 3, 1, '1008'),
('CSE421', 'Computer Graphics', 'CSE 421', 2, 3, 1, '1004'),
('CSE421L', 'Computer Graphics Lab', 'CSE 421L', 2, 3, 1, '1004'),
('CSE431', 'E-Commerce & Web Application', 'CSE 431', 2, 3, 1, '1005'),
('MGT414', ' Industrial Management', ' MGT 414', 2, 3, 1, '1006'),
('CSE412', 'Artificial Intelligence', 'CSE 412', 2, 3, 2, '1007'),
('CSE412L', 'Artificial Intelligence Lab', 'CSE 412L', 2, 3, 2, '1007'),
('CSE411', 'Communication Engineering', 'CSE 411', 2, 3, 2, '1008'),
('CSE332', 'Software Engineering', 'CSE 332', 2, 3, 2, '1009'),
('CSE333', 'Peripherals & Interfacing', 'CSE 333', 2, 3, 3, '1009'),
('CSE432', 'Computer and Network Security', 'CSE 432', 2, 3, 3, '1006'),
('CSE112', 'Computer Fundamentals', 'CSE 112', 1, 1, 1, '1001'),
('MAT111', 'Mathematics-I: Differential and Integral Calculus', 'MAT 111', 1, 1, 1, '1010'),
('ENG113D', 'Basic Functional English and English Spoken', 'ENG 113', 1, 1, 1, '1003'),
('PHY113', 'Physics-I: Mechanics, Heat & Thermodynamics,Waves & Oscillation, Optics', 'PHY 113', 1, 1, 1, '1004'),
('MAT121D', 'Mathematics -II: Complex Variable, Linear Algebra and Coordinate Geometry', 'MAT 121', 1, 1, 2, '1005'),
('CSE122', 'Programming and Problem Solving', 'CSE 122', 1, 1, 2, '1006'),
('CSE123', 'Problem Solving  Lab', 'CSE 123', 1, 1, 2, '1007'),
('PHY123D', 'Physics-II: Electricity, Magnetism and Modern Physics', 'PHY 123', 1, 1, 2, '1008'),
('PHY124', 'Physics-II Lab', 'PHY 124', 1, 1, 2, '1009'),
('ENG123', 'Writing and Comprehension', 'ENG 123', 1, 1, 2, '1010'),
('CSE131D', 'Discrete Mathematics', 'CSE 131', 1, 1, 3, '1010'),
('CSE132', 'Electrical Circuits', 'CSE 132', 1, 1, 3, '1001'),
('CSE133', 'Electrical Circuits Lab', 'CSE 133', 1, 1, 3, '1002'),
('CSE134', 'Data Structure', 'CSE 134', 1, 1, 3, '1003'),
('CSE135', 'Data Structure Lab', 'CSE 135', 1, 1, 3, '1002'),
('MAT131', 'Ordinary and Partial Differential Equations', 'MAT 131', 1, 1, 3, '1003'),
('MAT211D', 'Engineering Mathematics', 'MAT 211', 1, 2, 1, '1004'),
('CSE212', 'Digital Electronics', 'CSE 212', 1, 2, 1, '1005'),
('CSE213D', 'Digital Electronics Lab', 'CSE 213', 1, 2, 1, '1006'),
('CSE214', 'Object Oriented Programming', 'CSE 214', 1, 2, 1, '1007'),
('CSE215', 'Object Oriented Programming Lab', 'CSE 215', 1, 2, 1, '1008'),
('ED201', 'G Bangladesh Studies', 'ED 201', 1, 2, 1, '1009'),
('CSE221D', 'Algorithms', 'CSE 221', 1, 2, 2, '1010'),
('CSE222D', 'Algorithms Lab', 'CSE 222', 1, 2, 2, '1003'),
('STA133D', 'Statistics and Probability', 'STA 133', 1, 2, 2, '1004'),
('CSE224D', 'Electronic Devices and Circuits', 'CSE 224', 1, 2, 2, '1003'),
('CSE225', 'Electronic Devices and Circuits Lab', 'CSE 225', 1, 2, 2, '1004'),
('CSE231D', 'Microprocessor and Assembly Language', 'CSE 231', 1, 2, 3, '1005'),
('CSE232D', 'Microprocessor and Assembly Language Lab', 'CSE 232', 1, 2, 3, '1006'),
('CSE233D', 'Data Communication', 'CSE 233', 1, 2, 3, '1005'),
('CSE234D', 'Numerical Methods', 'CSE 234', 1, 2, 3, '1006'),
('CSE235', 'Introduction to Bio-Informatics', 'CSE 235', 1, 2, 3, '1006'),
('CSE311D', 'Database Management System', 'CSE 311', 1, 3, 1, '1007'),
('CSE312D', 'Database Management System Lab', 'CSE 312', 1, 3, 1, '1008'),
('CSE313D', 'Computer Networks', 'CSE 313', 1, 3, 1, '1009'),
('ECO314D', 'Economics', 'ECO 314', 1, 3, 1, '1008'),
('CSE321D', 'System Analysis and Design', 'CSE 321', 1, 3, 2, '1008'),
('CSE322D', 'Computer Architecture and Organization', 'CSE 322', 1, 3, 2, '1009'),
('CSE323D', 'Operating Systems', 'CSE 323', 1, 3, 2, '1008'),
('CSE324', 'Operating Systems Lab', 'CSE 324', 1, 3, 2, '1009'),
('GED321', 'Art of Effective Living', 'GED 321', 1, 3, 2, '1010'),
('CSE314D', 'Computer Networks Lab', 'CSE 314', 1, 3, 1, '1002'),
('CSE331D', 'Complier Design', 'CSE 331', 1, 3, 3, '1002'),
('CSE332D', 'Complier Design Lab', 'CSE 332', 1, 3, 3, '1003'),
('CSE333D', 'Software Engineering', 'CSE 333', 1, 3, 3, '1004'),
('CSE334', 'Wireless Programming', 'CSE 334', 1, 3, 3, '1003'),
('ACT301', 'Financial and Managerial Accounting 2', 'ACT 301', 1, 3, 3, '1004'),
('CSE412D', 'Artificial Intelligence', 'CSE 412', 1, 4, 1, '1005'),
('CSE413D', 'Artificial Intelligence Lab', 'CSE 413', 1, 4, 1, '1006'),
('CSE414D', 'Simulation and Modelling', 'CSE 414', 1, 4, 1, '1005'),
('CSE415D', 'Simulation and Modelling Lab', 'CSE 415', 1, 4, 1, '1006'),
('CSE417', 'Web Engineering', 'CSE 417', 1, 4, 1, '1007'),
('CSE418', 'Web Engineering Lab', 'CSE418', 1, 4, 1, '1008'),
('CSE421D', 'Computer Graphics', 'CSE 421', 1, 4, 2, '1008'),
('CSE422D', 'Computer Graphics Lab', 'CSE 422', 1, 4, 2, '1009'),
('CSE423', 'Embedded Systems', 'CSE 423', 1, 4, 2, '1009'),
('CSE498', 'Social and Professional Issues in Computing', 'CSE 498', 1, 4, 3, '1010');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `EmpId` varchar(10) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Designation` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`EmpId`, `Name`, `Designation`) VALUES
('1001', 'Prof. Dr. Syed Akhter Hossain', 'Head of the Dept'),
('1002', 'Dr. Sheak Rashed Haider Noori', 'Associate Head'),
('1003', 'Dr. Md. Mustafizur Rahman', 'Associate Professor'),
('1004', 'Dr. S. M. Aminul Haque', 'Associate Professor'),
('1005', 'Professor Dr. Md. Ismail Jabiullah', 'Professor'),
('1006', 'Dr. S.R.Subramanya', 'Visiting Professor'),
('1007', 'Dr. Neil Perez Balba', 'Visiting Professor'),
('1008', 'Dr. Bibhuti Roy', 'Visiting Professor'),
('1009', 'Mr. Anisur Rahman', 'Assistant Professor'),
('1010', 'Mr. Gazi Zahirul Islam', 'Assistant Professor');

-- --------------------------------------------------------

--
-- Table structure for table `thirdyear`
--

CREATE TABLE `thirdyear` (
  `Roll_No` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `thirdyear`
--

INSERT INTO `thirdyear` (`Roll_No`, `first_name`, `last_name`) VALUES
(1, 'Michelle', 'James'),
(2, 'Kimberly', 'Richardson'),
(3, 'Raymond', 'Coleman'),
(4, 'Arthur', 'Lynch'),
(5, 'Juan', 'Hicks'),
(6, 'Jennifer', 'Bishop'),
(7, 'Kelly', 'Burton'),
(8, 'Richard', 'Davis'),
(9, 'Frank', 'Weaver'),
(10, 'Martha', 'Schmidt'),
(11, 'Henry', 'Arnold'),
(12, 'Jacqueline', 'Morales'),
(13, 'Alice', 'Kim'),
(14, 'Cynthia', 'Kennedy'),
(15, 'Ryan', 'Miller'),
(16, 'Alan', 'Smith'),
(17, 'Jeremy', 'Jenkins'),
(18, 'Adam', 'Stephens'),
(19, 'Phyllis', 'Castillo'),
(20, 'Jeffrey', 'Williams'),
(21, 'Jimmy', 'Alvarez'),
(22, 'Christopher', 'Shaw'),
(23, 'Ann', 'Hanson'),
(24, 'Anna', 'Burns'),
(25, 'George', 'Gutierrez'),
(26, 'Bonnie', 'Nichols'),
(27, 'Annie', 'Castillo'),
(28, 'Chris', 'Austin'),
(29, 'Anna', 'Moore'),
(30, 'James', 'Cruz'),
(31, 'Fred', 'Smith'),
(32, 'Linda', 'Rice'),
(33, 'Thomas', 'Barnes'),
(34, 'Albert', 'Nichols'),
(35, 'Gregory', 'Rogers'),
(36, 'Lisa', 'Hughes'),
(37, 'Christine', 'Simmons'),
(38, 'Scott', 'Barnes'),
(39, 'James', 'Lynch'),
(40, 'Cheryl', 'Webb'),
(41, 'Annie', 'Alexander'),
(42, 'Matthew', 'Sanders'),
(43, 'Scott', 'Moreno'),
(44, 'Paula', 'Rose'),
(45, 'Betty', 'Lawson'),
(46, 'Walter', 'Sanders'),
(47, 'Jack', 'Porter'),
(48, 'Jean', 'Hernandez'),
(49, 'Anne', 'Sims'),
(50, 'Louis', 'Hart'),
(51, 'Joseph', 'Hernandez'),
(52, 'Larry', 'Murphy'),
(53, 'Stephen', 'Gonzales'),
(54, 'Tammy', 'Rogers'),
(55, 'Lori', 'Dunn'),
(56, 'Andrea', 'Willis'),
(57, 'Cheryl', 'Harrison'),
(58, 'Stephen', 'Miller'),
(59, 'Sharon', 'Ferguson'),
(60, 'Joseph', 'Roberts'),
(61, 'Ruby', 'Jones'),
(62, 'Brandon', 'Payne'),
(63, 'Joshua', 'Sims'),
(64, 'Lois', 'Cunningham'),
(65, 'Margaret', 'Lane'),
(66, 'Eugene', 'Frazier'),
(67, 'Marilyn', 'Torres'),
(68, 'John', 'Kennedy'),
(69, 'Diana', 'Turner'),
(70, 'Joyce', 'Hanson'),
(71, 'Jose', 'Anderson'),
(72, 'Lillian', 'Reyes'),
(73, 'Carolyn', 'Jacobs'),
(74, 'Ryan', 'Medina'),
(75, 'Marie', 'Murray'),
(76, 'Matthew', 'Hughes'),
(77, 'Anthony', 'Edwards'),
(78, 'Aaron', 'Bishop'),
(79, 'Linda', 'Mendoza'),
(80, 'Craig', 'Alvarez');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classroom`
--
ALTER TABLE `classroom`
  ADD PRIMARY KEY (`Classroom_ID`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`SubjectCode`),
  ADD KEY `Lecturer_Id` (`Lecturer_Id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`EmpId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
