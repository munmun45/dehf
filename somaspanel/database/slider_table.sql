CREATE TABLE IF NOT EXISTS `slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(500) NOT NULL,
  `button_text` varchar(100) DEFAULT 'Book a Consultation',
  `button_link` varchar(255) DEFAULT 'contact.php',
  `text_alignment` enum('left','center','right') DEFAULT 'left',
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample slider data
INSERT INTO `slider` (`title`, `description`, `image_path`, `button_text`, `button_link`, `text_alignment`, `status`, `sort_order`) VALUES
('Discover Your Past Life Journey', 'Our specialized past life astrology readings offer profound insights into your soul\'s journey, helping you understand karmic patterns and unlock your spiritual potential for healing and growth.', 'images/page-title/page-title-home-3.1.jpg', 'Book a Consultation', 'contact.php', 'left', 'active', 1),
('Strengthen Your Spiritual Core', 'Our astrology sessions focus on empowering you to understand your cosmic blueprint and soul purpose. Let us guide you through life\'s challenges with ancient wisdom and spiritual insights.', 'images/page-title/page-title-home-3.2.jpg', 'Book a Consultation', 'contact.php', 'center', 'active', 2),
('Renew Your Spiritual Energy', 'We focus on renewing your spiritual energy and divine connection. Our personalized astrology readings offer the guidance and healing you need to overcome karmic blocks & manifest your highest potential.', 'images/page-title/page-title-home-3.3.jpg', 'Book a Consultation', 'contact.php', 'right', 'active', 3);
