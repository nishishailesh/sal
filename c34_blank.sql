-- MySQL dump 10.18  Distrib 10.3.27-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: c34
-- ------------------------------------------------------
-- Server version	10.3.27-MariaDB-0+deb10u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `advance`
--

DROP TABLE IF EXISTS `advance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advance` (
  `staff_id` bigint(20) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `amount` float NOT NULL,
  `installment` int(11) NOT NULL,
  `pre_amount` float NOT NULL,
  `pre_installment` int(11) NOT NULL,
  PRIMARY KEY (`staff_id`,`type`),
  CONSTRAINT `advance_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_group`
--

DROP TABLE IF EXISTS `bill_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_group` (
  `bill_group` bigint(11) NOT NULL,
  `date_of_preparation` date NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `head` varchar(100) DEFAULT NULL,
  `bill_type` varchar(100) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `locked` int(11) DEFAULT NULL,
  PRIMARY KEY (`bill_group`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_type`
--

DROP TABLE IF EXISTS `bill_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_type` (
  `bill_type` varchar(100) NOT NULL,
  PRIMARY KEY (`bill_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `department` varchar(200) NOT NULL,
  PRIMARY KEY (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `map`
--

DROP TABLE IF EXISTS `map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `map` (
  `id` int(2) NOT NULL DEFAULT 0,
  `type` varchar(2) NOT NULL DEFAULT '',
  `type_name` varchar(32) DEFAULT NULL,
  `field` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nonsalary`
--

DROP TABLE IF EXISTS `nonsalary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nonsalary` (
  `staff_id` bigint(20) NOT NULL,
  `bill_group` bigint(11) NOT NULL,
  `nonsalary_type_id` int(11) NOT NULL,
  `data` varchar(100) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`staff_id`,`bill_group`,`nonsalary_type_id`),
  KEY `bill_group` (`bill_group`),
  KEY `salary_type_id` (`nonsalary_type_id`),
  CONSTRAINT `nonsalary_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON UPDATE CASCADE,
  CONSTRAINT `nonsalary_ibfk_3` FOREIGN KEY (`nonsalary_type_id`) REFERENCES `nonsalary_type` (`nonsalary_type_id`) ON UPDATE CASCADE,
  CONSTRAINT `nonsalary_ibfk_5` FOREIGN KEY (`bill_group`) REFERENCES `bill_group` (`bill_group`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nonsalary_type`
--

DROP TABLE IF EXISTS `nonsalary_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nonsalary_type` (
  `nonsalary_type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `shortform` varchar(6) NOT NULL,
  PRIMARY KEY (`nonsalary_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `nonsalary_type_old`
--

DROP TABLE IF EXISTS `nonsalary_type_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nonsalary_type_old` (
  `nonsalary_type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`nonsalary_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `shortform` varchar(100) NOT NULL,
  `post` varchar(100) NOT NULL,
  `senctioned` int(11) NOT NULL,
  `class` int(11) NOT NULL,
  PRIMARY KEY (`post`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `postold`
--

DROP TABLE IF EXISTS `postold`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `postold` (
  `shortform` varchar(100) NOT NULL,
  `post` varchar(100) NOT NULL,
  PRIMARY KEY (`post`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `salary`
--

DROP TABLE IF EXISTS `salary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary` (
  `staff_id` bigint(20) NOT NULL,
  `bill_group` bigint(11) NOT NULL,
  `salary_type_id` int(11) NOT NULL,
  `amount` decimal(10,0) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`staff_id`,`bill_group`,`salary_type_id`),
  KEY `bill_group` (`bill_group`),
  KEY `salary_type_id` (`salary_type_id`),
  CONSTRAINT `salary_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON UPDATE CASCADE,
  CONSTRAINT `salary_ibfk_4` FOREIGN KEY (`salary_type_id`) REFERENCES `salary_type` (`salary_type_id`) ON UPDATE CASCADE,
  CONSTRAINT `salary_ibfk_6` FOREIGN KEY (`bill_group`) REFERENCES `bill_group` (`bill_group`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `salary.B4.DEL`
--

DROP TABLE IF EXISTS `salary.B4.DEL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary.B4.DEL` (
  `staff_id` bigint(20) NOT NULL,
  `bill_group` bigint(11) NOT NULL,
  `salary_type_id` int(11) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `remark` varchar(100) NOT NULL,
  PRIMARY KEY (`staff_id`,`bill_group`,`salary_type_id`),
  KEY `bill_group` (`bill_group`),
  KEY `salary_type_id` (`salary_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `salary_type`
--

DROP TABLE IF EXISTS `salary_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_type` (
  `salary_type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code1` varchar(5) NOT NULL,
  `code2` varchar(5) NOT NULL,
  `type` varchar(1) NOT NULL,
  `shortform` varchar(6) NOT NULL,
  PRIMARY KEY (`salary_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `salary_type_old`
--

DROP TABLE IF EXISTS `salary_type_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_type_old` (
  `salary_type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code1` varchar(5) NOT NULL,
  `code2` varchar(5) NOT NULL,
  `type` varchar(1) NOT NULL,
  PRIMARY KEY (`salary_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `salaryyy`
--

DROP TABLE IF EXISTS `salaryyy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salaryyy` (
  `staff_id` bigint(20) NOT NULL,
  `bill_group` int(11) NOT NULL,
  `bill_number` int(11) NOT NULL,
  `fullname` varchar(300) NOT NULL,
  `department` varchar(200) NOT NULL,
  `post` varchar(100) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `bill_type` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `Pay_of_Officer_0101(+)` float NOT NULL,
  `Grade_Pay_of_Officer_0101(+)` float NOT NULL,
  `Pay_of_Establishment_0102(+)` float NOT NULL,
  `Grade_Pay_of_Establishment_0102(+)` float NOT NULL,
  `NPA_0128(+)` float NOT NULL,
  `Dearness_Allowance_0103(+)` float NOT NULL,
  `Compansatory_Local_Allowance_0111(+)` float NOT NULL,
  `House_Rent_Allowance_0110(+)` float NOT NULL,
  `Medical_Allowance_0107(+)` float NOT NULL,
  `BA_0104(+)` float NOT NULL,
  `Transport_Allowance_0113(+)` float NOT NULL,
  `Interim_Relief_0112(+)` float NOT NULL,
  `Washing_Allowance_0132(+)` float NOT NULL,
  `Uniform_Allowance_0131(+)` float NOT NULL,
  `Nursing_Allownace_0129(+)` float NOT NULL,
  `Special_Post_Allow_0104(+)` float NOT NULL,
  `Family_Welfare_Allow_0104(+)` float NOT NULL,
  `Ceiling_Extra_0104(+)` int(11) NOT NULL,
  `Income_Tax_9510(-)` float NOT NULL,
  `Rent_of_Building_9560(-)` float NOT NULL,
  `Professional_Tax_9570(-)` float NOT NULL,
  `SIS_I_9581(-)` float NOT NULL,
  `SIS_S_9582(-)` float NOT NULL,
  `GPF_non_IV_9670(-)` float NOT NULL,
  `GPF_IV_9531(-)` float NOT NULL,
  `CPF_9690(-)` float NOT NULL,
  `Pay_of_Officer_0101(-)` float NOT NULL,
  `Pay_of_Establishment_0102(-)` float NOT NULL,
  `bank_acc_number` varchar(50) NOT NULL,
  `bank` varchar(100) NOT NULL,
  `gpf_acc` varchar(50) NOT NULL,
  `cpf_acc` varchar(100) NOT NULL,
  `pan` varchar(20) NOT NULL,
  `quarter` varchar(20) NOT NULL,
  `budget_head` varchar(100) NOT NULL,
  `pay_scale` varchar(100) NOT NULL,
  `old_pay_scale` varchar(100) NOT NULL,
  PRIMARY KEY (`staff_id`,`bill_group`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `staff_id` bigint(20) NOT NULL,
  `fullname` varchar(300) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `uid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(300) DEFAULT NULL,
  `epassword` varchar(500) DEFAULT NULL,
  `mobile` varchar(15) DEFAULT NULL,
  `expirydate` date DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `unit` varchar(10) DEFAULT NULL,
  `right` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-06-10 22:48:19
