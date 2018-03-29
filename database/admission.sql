-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2018 at 07:09 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `admission`
--

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `program_choice` varchar(30) NOT NULL,
  `ss_course` varchar(30) NOT NULL,
  `aggregate` int(20) NOT NULL,
  `certificate` varchar(30) NOT NULL,
  `secondary_School` varchar(40) NOT NULL,
  `graduation_year` date NOT NULL,
  `index_number` varchar(50) NOT NULL,
  `full name` varchar(70) NOT NULL,
  `application_status` varchar(20) NOT NULL,
  `upload_certificate` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`program_choice`, `ss_course`, `aggregate`, `certificate`, `secondary_School`, `graduation_year`, `index_number`, `full name`, `application_status`, `upload_certificate`) VALUES
('Bsc Information Technology', 'General art', 1007758, 'application/pdf', 'Tema econdary School', '2007-02-08', '213ds01000277', 'Solomon Yaw Adeklo', 'approved', 'micky.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `program _id` int(20) NOT NULL,
  `program` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`program _id`, `program`) VALUES
(1, 'Bsc Software Engineering'),
(2, 'Bsc Information Technology'),
(3, 'Bsc Computer Science'),
(4, 'Bsc Nursing'),
(5, 'BSc Artificial Intelligence'),
(6, 'Bsc Mathematics'),
(7, 'Bsc.Robotics'),
(8, 'BSc Chemical Engineering'),
(9, 'BSc Materials Engineering'),
(10, 'BSc Architecture'),
(11, 'BA African and African Diaspor'),
(12, 'BSC Astronomy'),
(13, 'BSC Entrepreneurship and Innov'),
(14, 'Bsc Geographic Information Sys'),
(15, 'Bsc.Environment Science'),
(16, 'Bsc.Electrical Engineering'),
(17, 'MBBS/BSc Medicine'),
(18, 'Bsc.Architecture'),
(19, 'Bsc.Geodetic Engineering'),
(20, 'Bsc. Business Systems Analysis');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `admmision_status_id` int(20) NOT NULL,
  `admmision_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`admmision_status_id`, `admmision_status`) VALUES
(1, 'pending'),
(2, 'approved'),
(3, 'rejected');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `StudentID` int(11) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `LastName` varchar(30) NOT NULL,
  `BithDate` datetime NOT NULL,
  `Address` varchar(60) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Country` varchar(15) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `userlevel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`StudentID`, `FirstName`, `LastName`, `BithDate`, `Address`, `Username`, `Password`, `Country`, `Email`, `photo`, `userlevel_id`) VALUES
(1, 'Solomon', 'Adeklo', '1989-05-09 00:00:00', 'P.O Box SP 518, Spintex Road, accra', 'solomon', 'solomon', 'Ghana', 'sadeklo@st.vvu.edu.gh', 'IMG_20170711_030951.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `userlevelpermissions`
--

CREATE TABLE `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlevelpermissions`
--

INSERT INTO `userlevelpermissions` (`userlevelid`, `tablename`, `permission`) VALUES
(-2, '{F31DB578-461D-4551-B52B-112914F68329}students', 0),
(-2, '{F31DB578-461D-4551-B52B-112914F68329}userlevelpermissions', 0),
(-2, '{F31DB578-461D-4551-B52B-112914F68329}userlevels', 0),
(0, '{F31DB578-461D-4551-B52B-112914F68329}application', 105),
(0, '{F31DB578-461D-4551-B52B-112914F68329}programs', 104),
(0, '{F31DB578-461D-4551-B52B-112914F68329}students', 104),
(0, '{F31DB578-461D-4551-B52B-112914F68329}userlevelpermissions', 0),
(0, '{F31DB578-461D-4551-B52B-112914F68329}userlevels', 0),
(2, '{F31DB578-461D-4551-B52B-112914F68329}application', 105),
(2, '{F31DB578-461D-4551-B52B-112914F68329}programs', 104),
(2, '{F31DB578-461D-4551-B52B-112914F68329}students', 108),
(2, '{F31DB578-461D-4551-B52B-112914F68329}userlevelpermissions', 0),
(2, '{F31DB578-461D-4551-B52B-112914F68329}userlevels', 0);

-- --------------------------------------------------------

--
-- Table structure for table `userlevels`
--

CREATE TABLE `userlevels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userlevels`
--

INSERT INTO `userlevels` (`userlevelid`, `userlevelname`) VALUES
(-2, 'Anonymous'),
(-1, 'Administrator'),
(0, 'Default'),
(2, 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD PRIMARY KEY (`index_number`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`program _id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`admmision_status_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`StudentID`);

--
-- Indexes for table `userlevelpermissions`
--
ALTER TABLE `userlevelpermissions`
  ADD PRIMARY KEY (`userlevelid`,`tablename`);

--
-- Indexes for table `userlevels`
--
ALTER TABLE `userlevels`
  ADD PRIMARY KEY (`userlevelid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `program _id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `admmision_status_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `StudentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
