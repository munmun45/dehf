-- Contact information table
CREATE TABLE IF NOT EXISTS `contact_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `map_embed_url` text,
  `consultation_title` varchar(255) DEFAULT 'Free Consultation - Begin Your Healing Journey',
  `consultation_subtitle` varchar(255) DEFAULT 'Why Choose Us',
  `consultation_description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default contact information
INSERT INTO `contact_info` (`email`, `phone`, `address`, `map_embed_url`, `consultation_description`) VALUES
('themesflat@gmail.com', '1-333-345-6868', '101 E 129th St, East Chicago, IN 46312, US', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6563971.11637624!2d79.14157762376357!3d20.110719594755135!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a226aece9af3bfd%3A0x133625caa9cea81f!2sOdisha!5e1!3m2!1sen!2sin!4v1755761695025!5m2!1sen!2sin', 'Connect with a dedicated specialist today and take the first step towards a healthier, more fulfilling life.');

-- Contact form submissions table
CREATE TABLE IF NOT EXISTS `contact_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `service` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied','archived') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
