SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `prwb_2526_f09`;
CREATE DATABASE IF NOT EXISTS `prwb_2526_f09` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `prwb_2526_f09`;

DROP TABLE IF EXISTS `bids`;
CREATE TABLE IF NOT EXISTS `bids` (
  `owner` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `amount` decimal(10, 2) NOT NULL,
  PRIMARY KEY (`owner`,`item`,`created_at`),
  KEY `item` (`item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `owner` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `buy_now_price` decimal(10, 2) DEFAULT NULL,
  `duration_days` int(11) NOT NULL,
  `starting_bid` decimal(10, 2) DEFAULT 0.0,
  UNIQUE(`title`, `owner`),
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

DROP TABLE IF EXISTS `item_pictures`;
CREATE TABLE IF NOT EXISTS `item_pictures` (
  `item` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `picture_path` varchar(255) NOT NULL,
  PRIMARY KEY (`item`,`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `picture_path` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `full_name` (`full_name`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`item`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

ALTER TABLE `item_pictures`
  ADD CONSTRAINT `item_pictures_ibfk_1` FOREIGN KEY (`item`) REFERENCES `items` (`id`);
COMMIT;

-- Note: no NOW() here; time-based values must be computed by the app using :now from AppTime
DROP VIEW IF EXISTS v_items_status;
CREATE VIEW v_items_status AS
SELECT
  i.*,
  DATE_ADD(i.created_at, INTERVAL i.duration_days DAY) AS end_at,
  (SELECT COUNT(*) FROM bids b WHERE b.item = i.id) AS bid_count,
  (SELECT MAX(b2.amount) FROM bids b2 WHERE b2.item = i.id) AS max_bid,
  (i.starting_bid IS NULL OR i.starting_bid = 0) AS is_direct_sale,
  (i.starting_bid > 0) AS is_auction,
  (i.buy_now_price IS NOT NULL) AS has_buy_now,
  ((SELECT COUNT(*) FROM bids b WHERE b.item = i.id) > 0) AS has_bids,
  -- buy-now reached is time-agnostic (depends only on max_bid vs price)
  (i.buy_now_price IS NOT NULL AND (SELECT MAX(b3.amount) FROM bids b3 WHERE b3.item = i.id) IS NOT NULL
     AND (SELECT MAX(b3.amount) FROM bids b3 WHERE b3.item = i.id) >= i.buy_now_price) AS buy_now_reached,
  -- direct sale not yet purchased
  ((i.starting_bid IS NULL OR i.starting_bid = 0) AND (SELECT COUNT(*) FROM bids b WHERE b.item = i.id) = 0) AS not_purchased_direct_sale
FROM items i;
