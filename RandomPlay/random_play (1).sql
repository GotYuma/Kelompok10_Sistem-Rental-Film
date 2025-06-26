-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2025 at 03:01 AM
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
-- Database: `random_play`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_cust` int(3) NOT NULL,
  `nama` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `telpon` varchar(55) NOT NULL,
  `alamat` varchar(55) NOT NULL,
  `tanggaldaftar` date NOT NULL,
  `statusmember` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_cust`, `nama`, `email`, `telpon`, `alamat`, `tanggaldaftar`, `statusmember`) VALUES
(1, 'antoa', 'anto@gmail.com', '1111111', 'singingi', '2025-06-19', 'Active'),
(2, 'Sosuke Aizen', 'aizen@gmail.com', '08123948429', 'Rumbai', '2025-06-26', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `kasetfilm`
--

CREATE TABLE `kasetfilm` (
  `id_film` int(3) NOT NULL,
  `judul` varchar(55) NOT NULL,
  `genre` varchar(55) NOT NULL,
  `tahunrilis` int(4) NOT NULL,
  `rating` int(3) NOT NULL,
  `stok` int(3) NOT NULL,
  `hargasewa` int(5) NOT NULL,
  `tersedia` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kasetfilm`
--

INSERT INTO `kasetfilm` (`id_film`, `judul`, `genre`, `tahunrilis`, `rating`, `stok`, `hargasewa`, `tersedia`) VALUES
(1, 'Coffee Mate', 'Comedy', 2025, 8, 9, 70000, 10),
(2, 'Don\'t Touch', 'Fantasy', 2023, 9, 12, 90000, 12),
(3, 'The Heartbeat', 'Family', 2024, 8, 7, 65000, 7),
(4, 'Invasion: Next Gen', 'Action', 2024, 8, 11, 85000, 12),
(5, 'Starlight Knight', 'Adventure', 2022, 10, 17, 92000, 17),
(6, '7710 and Its Cat', 'Adventure', 2022, 9, 10, 60000, 10),
(7, 'Family', 'Action', 2020, 9, 10, 75000, 10),
(8, 'Oh~ Sweetie', 'Romance', 2025, 9, 12, 88000, 12),
(9, 'Small Body Big Crisis', 'Comedy', 2020, 8, 10, 85000, 10),
(10, 'Final Punch', 'Action', 2023, 9, 10, 77000, 10),
(11, 'Last Flight', 'Romance', 2023, 8, 10, 75000, 10),
(12, 'More Than One Truth', 'Comedy', 2019, 9, 15, 90000, 15);

-- --------------------------------------------------------

--
-- Table structure for table `rent`
--

CREATE TABLE `rent` (
  `id_rent` int(3) NOT NULL,
  `id_cust` int(3) NOT NULL,
  `id_film` int(3) NOT NULL,
  `tanggalsewa` date NOT NULL,
  `status` varchar(55) NOT NULL,
  `biaya` double(15,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rent`
--

INSERT INTO `rent` (`id_rent`, `id_cust`, `id_film`, `tanggalsewa`, `status`, `biaya`) VALUES
(1, 1, 1, '2025-06-26', 'Active', 70000),
(2, 1, 4, '2025-06-26', 'Active', 85000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_cust`);

--
-- Indexes for table `kasetfilm`
--
ALTER TABLE `kasetfilm`
  ADD PRIMARY KEY (`id_film`);

--
-- Indexes for table `rent`
--
ALTER TABLE `rent`
  ADD PRIMARY KEY (`id_rent`),
  ADD KEY `id_cust_rent_FK` (`id_cust`),
  ADD KEY `id_film_kasetfilm_FK` (`id_film`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id_cust` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kasetfilm`
--
ALTER TABLE `kasetfilm`
  MODIFY `id_film` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `rent`
--
ALTER TABLE `rent`
  MODIFY `id_rent` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rent`
--
ALTER TABLE `rent`
  ADD CONSTRAINT `id_cust_rent_FK` FOREIGN KEY (`id_cust`) REFERENCES `customer` (`id_cust`),
  ADD CONSTRAINT `id_film_kasetfilm_FK` FOREIGN KEY (`id_film`) REFERENCES `kasetfilm` (`id_film`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
