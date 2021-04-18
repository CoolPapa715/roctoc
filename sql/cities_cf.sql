-- phpMyAdmin SQL Dump
-- version 4.4.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 18, 2019 at 08:59 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.12

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `globalcities`
--

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `country_name`, `country_abbr`, `slug`) VALUES
(40, 'Central African Republic', 'CF', 'central-african-republic');

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`state_id`, `state_name`, `state_abbr`, `slug`, `country_abbr`, `country_id`) VALUES
(575, 'Ouham', 'AC', 'ouham', 'CF', 40),
(576, 'Bamingui-Bangoran', 'BB', 'bamingui-bangoran', 'CF', 40),
(577, 'Bangui', 'BGF', 'bangui', 'CF', 40),
(578, 'Basse-Kotto', 'BK', 'basse-kotto', 'CF', 40),
(579, 'Haute-Kotto', 'HK', 'haute-kotto', 'CF', 40),
(580, 'Haut-Mbomou', 'HM', 'haut-mbomou', 'CF', 40),
(581, 'Mambéré-Kadéï', 'HS', 'mambere-kadei', 'CF', 40),
(582, 'Gribingui', 'KB', 'gribingui', 'CF', 40),
(583, 'Kémo-Gribingui', 'KG', 'kemo-gribingui', 'CF', 40),
(584, 'Lobaye', 'LB', 'lobaye', 'CF', 40),
(585, 'Mbomou', 'MB', 'mbomou', 'CF', 40),
(586, 'Ombella-Mpoko', 'MP', 'ombella-mpoko', 'CF', 40),
(587, 'Nana-Mambéré', 'NM', 'nana-mambere', 'CF', 40),
(588, 'Ouham-Pendé', 'OP', 'ouham-pende', 'CF', 40),
(589, 'Sangha', 'SE', 'sangha', 'CF', 40),
(590, 'Ouaka', 'UK', 'ouaka', 'CF', 40),
(591, 'Vakaga', 'VK', 'vakaga', 'CF', 40),
(4741, 'Central African Republic Region', 'CF', 'central-african-republic-region', 'CF', 40);

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`city_id`, `city_name`, `state`, `state_id`, `slug`, `lat`, `lng`) VALUES
(13191, 'Alindao', 'CF', 4741, 'alindao-4741', '5.03333300', '21.21666700'),
(13192, 'Baboua', 'CF', 4741, 'baboua-4741', '5.78333300', '14.81666700'),
(13193, 'Bakouma', 'CF', 4741, 'bakouma-4741', '5.69861100', '22.78333300'),
(13194, 'Bambari', 'CF', 4741, 'bambari-4741', '5.76527800', '20.67416700'),
(13195, 'Bangassou', 'CF', 4741, 'bangassou-4741', '4.73742500', '22.81946200'),
(13196, 'Bangui', 'CF', 4741, 'bangui-4741', '4.36666700', '18.58333300'),
(13197, 'Baoro', 'CF', 4741, 'baoro-4741', '5.68333300', '15.95000000'),
(13198, 'Batangafo', 'CF', 4741, 'batangafo-4741', '7.30000000', '18.30000000'),
(13199, 'Berbérati', 'CF', 4741, 'berberati-4741', '4.26666667', '15.78944400'),
(13200, 'Birao', 'CF', 4741, 'birao-4741', '10.29400000', '22.78200000'),
(13201, 'Bocaranga', 'CF', 4741, 'bocaranga-4741', '6.96666667', '15.65000000'),
(13202, 'Boda', 'CF', 4741, 'boda-4741', '4.31666700', '17.46666700'),
(13203, 'Bogoto', 'CF', 4741, 'bogoto-4741', '4.95000000', '2.16666667'),
(13204, 'Bossangoa', 'CF', 4741, 'bossangoa-4741', '6.48333300', '17.45000000'),
(13205, 'Bossembélé', 'CF', 4741, 'bossembele-4741', '5.26666700', '17.65000000'),
(13206, 'Bouar', 'CF', 4741, 'bouar-4741', '5.95000000', '15.60000000'),
(13207, 'Bouca', 'CF', 4741, 'bouca-4741', '6.50000000', '18.28333300'),
(13208, 'Bozoum', 'CF', 4741, 'bozoum-4741', '6.31722200', '16.37833300'),
(13209, 'Bria', 'CF', 4741, 'bria-4741', '6.53694400', '21.99194400'),
(13210, 'Carnot', 'CF', 4741, 'carnot-4741', '4.94000000', '15.87000000'),
(13211, 'Dekoa', 'CF', 4741, 'dekoa-4741', '6.31666700', '19.06666700'),
(13212, 'Gordil', 'CF', 4741, 'gordil-4741', '9.73333300', '21.58333300'),
(13213, 'Gounda', 'CF', 4741, 'gounda-4741', '9.05000000', '21.20000000'),
(13214, 'Grimari', 'CF', 4741, 'grimari-4741', '5.71666700', '20.05000000'),
(13215, 'Kaga Bandoro', 'CF', 4741, 'kaga-bandoro-4741', '7.00000000', '19.18333300'),
(13217, 'Kembé', 'CF', 4741, 'kembe-4741', '4.60000000', '21.76666700'),
(13218, 'Koumala', 'CF', 4741, 'koumala-4741', '8.50000000', '21.25000000'),
(13219, 'Mboki', 'HM', 580, 'mboki-580', '5.33277800', '25.93194400'),
(13220, 'Mobaye', 'CF', 4741, 'mobaye-4741', '4.31666700', '21.18333300'),
(13221, 'Mongoumba', 'CF', 4741, 'mongoumba-4741', '3.63333300', '18.60000000'),
(13222, 'Ndélé', 'CF', 4741, 'ndele-4741', '8.40916700', '20.65305600'),
(13223, 'Nola', 'CF', 4741, 'nola-4741', '3.53333300', '16.06666700'),
(13224, 'Obo', 'CF', 4741, 'obo-4741', '5.40000000', '26.50000000'),
(13225, 'Ouadda', 'CF', 4741, 'ouadda-4741', '8.06666700', '22.40000000'),
(13226, 'Ouanda Djallé', 'CF', 4741, 'ouanda-djalle-4741', '8.90000000', '22.80000000'),
(13227, 'Paoua', 'CF', 4741, 'paoua-4741', '7.25000000', '16.43333300'),
(13228, 'Rafai', 'CF', 4741, 'rafai-4741', '4.97305600', '23.93194400'),
(13229, 'Sibut', 'CF', 4741, 'sibut-4741', '5.73777800', '19.08666700'),
(13231, 'Yalinga', 'CF', 4741, 'yalinga-4741', '6.51666700', '23.25000000'),
(13232, 'Zemio', 'CF', 4741, 'zemio-4741', '5.03333300', '25.13333300');

SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
