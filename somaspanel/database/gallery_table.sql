CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(500) NOT NULL,
  `category` varchar(100) DEFAULT 'general',
  `status` enum('active','inactive') DEFAULT 'active',
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data
INSERT INTO `gallery` (`title`, `description`, `image_path`, `category`, `status`, `sort_order`) VALUES
('Therapy Session', 'Professional therapy session in our comfortable environment', 'images/gallery/therapy-1.jpg', 'therapy', 'active', 1),
('Consultation Room', 'Modern consultation room designed for privacy and comfort', 'images/gallery/room-1.jpg', 'facilities', 'active', 2),
('Group Therapy', 'Group therapy session promoting community healing', 'images/gallery/group-1.jpg', 'therapy', 'active', 3),
('Relaxation Area', 'Peaceful relaxation area for clients', 'images/gallery/relax-1.jpg', 'facilities', 'active', 4),
('Team Meeting', 'Our professional team in discussion', 'images/gallery/team-1.jpg', 'team', 'active', 5),
('Wellness Workshop', 'Wellness workshop for mental health awareness', 'images/gallery/workshop-1.jpg', 'events', 'active', 6);
