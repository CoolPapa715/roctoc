ALTER TABLE `custom_fields` ADD `filter_display` VARCHAR(50) NULL DEFAULT 'radio' AFTER `field_type`;

--
-- Table structure for table `plan_types`
--

CREATE TABLE IF NOT EXISTS `plan_types` (
  `id` int(11) NOT NULL,
  `plan_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'free',
  `plan_priority` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_types`
--

INSERT INTO `plan_types` (`id`, `plan_type`, `plan_priority`) VALUES
(1, 'free', 1),
(2, 'free_feat', 1),
(3, 'one_time', 10),
(4, 'one_time_feat', 20),
(5, 'monthly', 10),
(6, 'monthly_feat', 20),
(7, 'annual', 10),
(8, 'annual_feat', 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `plan_types`
--
ALTER TABLE `plan_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `plan_types`
--
ALTER TABLE `plan_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;

--
-- Table structure for table `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
  `video_id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT '0',
  `video_url` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `place_id` (`place_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE ON UPDATE CASCADE;
