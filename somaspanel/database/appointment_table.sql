-- Appointment bookings table
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `service` varchar(255) NOT NULL,
  `therapist` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `message` text,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Therapists table for dynamic therapist management
CREATE TABLE IF NOT EXISTS `therapists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `email` varchar(255),
  `phone` varchar(50),
  `bio` text,
  `image_path` varchar(500),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default therapists
INSERT INTO `therapists` (`name`, `specialization`, `email`, `phone`) VALUES
('Dr. Emily Stevens', 'Individual Counseling', 'emily.stevens@clinic.com', '555-0101'),
('Michael Carter', 'Family Therapy', 'michael.carter@clinic.com', '555-0102'),
('Sarah Martinez', 'Couples Therapy', 'sarah.martinez@clinic.com', '555-0103'),
('Dr. James Mcavoy', 'Group Therapy', 'james.mcavoy@clinic.com', '555-0104'),
('Dr. Lisa Thompson', 'Child & Adolescent Therapy', 'lisa.thompson@clinic.com', '555-0105'),
('Andrew Collins', 'Trauma Counseling', 'andrew.collins@clinic.com', '555-0106'),
('Jessica Rivera', 'Trauma Counseling', 'jessica.rivera@clinic.com', '555-0107'),
('Dr. Robert Evans', 'Trauma Counseling', 'robert.evans@clinic.com', '555-0108');
