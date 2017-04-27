-- MySQL dump 10.13  Distrib 5.5.39, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: salary
-- ------------------------------------------------------
-- Server version	5.5.39-1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
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
  `bill_group` int(11) NOT NULL,
  `advance_type` varchar(100) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `advance_type`
--

DROP TABLE IF EXISTS `advance_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `advance_type` (
  `advance_type` varchar(100) NOT NULL,
  PRIMARY KEY (`advance_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_group`
--

DROP TABLE IF EXISTS `bill_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_group` (
  `bill_number` int(11) NOT NULL,
  `date_of_preparation` date NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `head` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL,
  PRIMARY KEY (`bill_number`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bill_type`
--

DROP TABLE IF EXISTS `bill_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bill_type` (
  `bill_type` varchar(100) NOT NULL
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
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
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
  `Leave_Salary_Encash_0109(+)` float NOT NULL,
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
  `Festival_A_5701(-)` float NOT NULL,
  `Food_Grains_A_5801(-)` float NOT NULL,
  `Car_A_9741(-)` float NOT NULL,
  `HBA_9591(-)` float NOT NULL,
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
-- Table structure for table `salary_head`
--

DROP TABLE IF EXISTS `salary_head`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_head` (
  `edp` varchar(20) NOT NULL,
  `ooe` varchar(100) NOT NULL,
  `bc` varchar(20) NOT NULL,
  PRIMARY KEY (`edp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `salary_old`
--

DROP TABLE IF EXISTS `salary_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salary_old` (
  `staff_id` bigint(20) NOT NULL,
  `bill_number` int(11) NOT NULL,
  `fullname` varchar(300) NOT NULL,
  `department` varchar(200) NOT NULL,
  `_post` varchar(100) NOT NULL,
  `remark` varchar(100) NOT NULL,
  `pbasic_pay` float NOT NULL,
  `pgrade_pay` float NOT NULL,
  `pnppa` float NOT NULL,
  `pda` float NOT NULL,
  `pcla` float NOT NULL,
  `phra` float NOT NULL,
  `pma` float NOT NULL,
  `pba` float NOT NULL,
  `pta` float NOT NULL,
  `pinterim_relief` double NOT NULL,
  `pwa` double NOT NULL,
  `pua` double NOT NULL,
  `pmess_a` double NOT NULL,
  `pspecial1` float NOT NULL,
  `pspecial2` float NOT NULL,
  `pspecial3` float NOT NULL,
  `pspecial4` float NOT NULL,
  `mitax` float NOT NULL,
  `mhouse_rent` float NOT NULL,
  `mptax` float NOT NULL,
  `msis1` float NOT NULL,
  `msis2` float NOT NULL,
  `mgpf` float NOT NULL,
  `mcpf` float NOT NULL,
  `mgpf_advance` float NOT NULL,
  `madvance1` float NOT NULL,
  `madvance2` float NOT NULL,
  `madvance3` float NOT NULL,
  `madvance4` float NOT NULL,
  `mdeduction1` float NOT NULL,
  `mdeduction2` float NOT NULL,
  `mdeduction3` float NOT NULL,
  `mdeduction4` float NOT NULL,
  `bank_acc_number` varchar(50) NOT NULL,
  `bank` varchar(100) NOT NULL,
  `gpf_acc` varchar(50) NOT NULL,
  `cpf_acc` varchar(100) NOT NULL,
  `basic_catagory` varchar(20) NOT NULL,
  `_pan` varchar(20) NOT NULL,
  `quarter` varchar(20) NOT NULL,
  `budget_head` varchar(100) NOT NULL,
  PRIMARY KEY (`staff_id`,`bill_number`)
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
  `uid` bigint(20) NOT NULL,
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_position`
--

DROP TABLE IF EXISTS `staff_position`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_position` (
  `staff_position_id` int(11) NOT NULL DEFAULT '0',
  `department` varchar(100) NOT NULL,
  `_post` varchar(100) NOT NULL,
  PRIMARY KEY (`staff_position_id`)
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
  `password` varchar(300) NOT NULL,
  `department` varchar(100) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `right` int(11) NOT NULL DEFAULT '0',
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

-- Dump completed on 2017-04-10  8:07:35
