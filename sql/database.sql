/*
    Insurance: Item Tracker
    Copyright (C) 2020 Michael Cabot
*/

/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `contents` (
  `item` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `description` varchar(200) NOT NULL,
  `quantity` tinyint(4) NOT NULL DEFAULT '1',
  `unit_price` decimal(7,2) NOT NULL DEFAULT '0.00',
  `collect_amount` decimal(7,2) NOT NULL DEFAULT '0.00',
  `spend_amount` decimal(7,2) NOT NULL DEFAULT '0.00',
  `acv_paid` decimal(7,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `pages` (
  `page` varchar(32) NOT NULL,
  `title` varchar(64) NOT NULL,
  `pushStateAddr` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `actions` (
  `timestamp` int(11) NOT NULL,
  `actionID` tinyint(4) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `timestamp_2` (`timestamp`),
  KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `pages` VALUES ('all','All Items','index.php?view=all'),('finalized','Finalized','index.php?view=finalized'),('notreplaced','Not Replaced','index.php?view=notreplaced'),('partial','Partial','index.php?view=partial'),('replaced','Replaced','index.php?view=replaced'),('search','Search','index.php?view=search'),('stats','Stats','index.php'),('submissions','Submissions','index.php?view=submissions'),('submitted','Submitted','index.php?view=submitted');


ALTER TABLE `contents`
  ADD PRIMARY KEY (`item`),
  ADD UNIQUE KEY `item` (`item`);

ALTER TABLE `pages`
  ADD PRIMARY KEY (`page`),
  ADD UNIQUE KEY `page` (`page`);
COMMIT;