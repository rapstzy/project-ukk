-- Peminjaman Buku - Import Database
-- Import this file into phpMyAdmin or MySQL client.
-- After import, point APP_URL to your domain and keep DB credentials in .env.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `loan_items`;
DROP TABLE IF EXISTS `loans`;
DROP TABLE IF EXISTS `books`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`, `notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `books` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `year` year NOT NULL,
  `category` varchar(255) NOT NULL,
  `stock` int NOT NULL DEFAULT 0,
  `cover` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `books_isbn_unique` (`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `loans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `loan_date` date NOT NULL,
  `due_date` date NOT NULL,
  `returned_at` date DEFAULT NULL,
  `status` enum('borrowed','verified','returned','late','completed','cancelled') NOT NULL DEFAULT 'borrowed',
  `fine_amount` int NOT NULL DEFAULT 0,
  `is_extended` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loans_user_id_foreign` (`user_id`),
  KEY `loans_status_index` (`status`),
  KEY `loans_due_date_index` (`due_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `loan_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `loan_id` bigint unsigned NOT NULL,
  `book_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loan_items_loan_id_foreign` (`loan_id`),
  KEY `loan_items_book_id_foreign` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
  (1, '0001_01_01_000000_create_users_table', 1),
  (2, '0001_01_01_000001_create_cache_table', 1),
  (3, '0001_01_01_000002_create_jobs_table', 1),
  (4, '2026_04_02_014436_create_books_table', 2),
  (5, '2026_04_02_014436_create_loans_table', 2),
  (6, '2026_04_02_015624_create_loan_items_table', 2),
  (7, '2026_04_02_120000_update_loan_statuses', 3),
  (8, '2026_04_09_062806_add_soft_delete_to_books_table', 3);

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
  (1, 'Admin Apay', 'admin@apaysbooks.com', '2026-04-10 00:00:00', '$2y$10$8zzC6ry1JDXs7F4RxkTj1OAJgkUMt23EOPDhmrL3nuqnVfSPLkVVu', 'admin', NULL, '2026-04-10 00:00:00', '2026-04-10 00:00:00'),
  (2, 'John Doe', 'user@apaysbooks.com', '2026-04-10 00:00:00', '$2y$10$ilgChYD71azAvhKTzmz/2e9D4oU3oBwmxseA8LiXj/5DMZyuObhLC', 'user', NULL, '2026-04-10 00:00:00', '2026-04-10 00:00:00');

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `publisher`, `year`, `category`, `stock`, `cover`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (1, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', '978-0062316097', 'Harper', 2014, 'History', 5, '/images/Sapiens.webp', 'A sweeping story of how Homo sapiens came to dominate the world.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (2, 'The Midnight Library', 'Matt Haig', '978-0020195719', 'Viking', 2020, 'Fiction', 4, '/images/themidnight.jpg', 'A novel about all the choices that go into a life well lived.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (3, '1984', 'George Orwell', '978-0451524935', 'Signet Classics', 1949, 'Dystopian', 3, '/images/1984.jpg', 'A dystopian novel about a totalitarian society.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (4, 'To Kill a Mockingbird', 'Harper Lee', '978-0061120084', 'HarperCollins', 1960, 'Literature', 4, '/images/tokill.jpg', 'A gripping tale of racial injustice and childhood innocence.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (5, 'Atomic Habits', 'James Clear', '978-0735211292', 'Avery', 2018, 'Self-Help', 6, '/images/atomic.jpg', 'Transform your life through tiny changes.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (6, 'The Lean Startup', 'Eric Ries', '978-0307887894', 'Crown Business', 2011, 'Business', 3, '/images/thelean.jpg', 'How today''s entrepreneurs use continuous innovation.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (7, 'Dune', 'Frank Herbert', '978-0441172719', 'Ace', 1965, 'Science Fiction', 4, '/images/dune.webp', 'An epic saga of politics, religion, and ecology.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (8, 'The Great Gatsby', 'F. Scott Fitzgerald', '978-0743273565', 'Scribner', 1925, 'Classic', 5, '/images/thegreat.jpg', 'A tale of obsessive love and the American Dream.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (9, 'Python for Everybody', 'Charles Severance', '978-1511885935', 'CreateSpace', 2014, 'Programming', 7, '/images/python.jpg', 'Exploring data with Python for beginners.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (10, 'Clean Code', 'Robert C. Martin', '978-0132350884', 'Prentice Hall', 2008, 'Programming', 4, '/images/cleancode.webp', 'A handbook of agile software craftsmanship.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (11, 'The Hobbit', 'J.R.R. Tolkien', '978-0547928227', 'Mariner Books', 1937, 'Fantasy', 5, '/images/thehobbit.jpg', 'A fantasy adventure of a hobbit''s unexpected journey.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (12, 'Thinking, Fast and Slow', 'Daniel Kahneman', '978-0374533557', 'Farrar, Straus and Giroux', 2011, 'Psychology', 3, '/images/thinking.jpg', 'Insights into the two systems that drive the way we think.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (13, 'The Catcher in the Rye', 'J.D. Salinger', '978-0316769174', 'Little, Brown', 1951, 'Literature', 2, '/images/thecatcher.jpg', 'A story of teenage alienation and angst.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (14, 'Educated', 'Tara Westover', '978-0399590504', 'Penguin Press', 2018, 'Biography', 4, '/images/educated.jpg', 'A memoir of extraordinary journey to education and self-invention.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (15, 'The Design of Everyday Things', 'Don Norman', '978-0465050659', 'Basic Books', 2013, 'Design', 3, '/images/thedesign.jpg', 'Principles of good design and poor design.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (16, 'Mindset', 'Carol S. Dweck', '978-0345472328', 'Ballantine Books', 2006, 'Self-Help', 5, '/images/mindset.jpg', 'The new psychology of success.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (17, 'Foundation', 'Isaac Asimov', '978-0553293357', 'Bantam Classics', 1951, 'Science Fiction', 3, '/images/foundation.jpg', 'A story of galactic empire and psychohistory.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (18, 'The Silent Patient', 'Alex Michaelides', '978-1250295522', 'Hachette Book Group', 2019, 'Thriller', 4, '/images/thesilent.jpg', 'A woman shoots her husband and never speaks again.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (19, 'Deep Work', 'Cal Newport', '978-0349411903', 'Grand Central Publishing', 2016, 'Business', 4, '/images/deep.jpg', 'Rules for focused success in a distracted world.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (20, 'The Book Thief', 'Markus Zusak', '978-0375831003', 'Knopf', 2005, 'Historical Fiction', 4, '/images/thebook.jpg', 'A story set during Nazi Germany told by Death itself.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (21, 'The Alchemist', 'Paulo Coelho', '978-0061122415', 'HarperOne', 1988, 'Fiction', 5, '/images/thealchemist.jpg', 'A fable about following your dreams and listening to your heart.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (22, 'The Library Book', 'Susan Orlean', '978-1476757004', 'Simon & Schuster', 2018, 'Nonfiction', 4, '/images/thelibrarybook.jpg', 'A story about libraries, memory, fire, and the people who keep books alive.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (23, 'How to Read a Book', 'Mortimer J. Adler', '978-0671212094', 'Touchstone', 1940, 'Education', 3, '/images/howtoreadbook.jpg', 'A classic guide to reading with focus, insight, and comprehension.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL),
  (24, 'The Little Prince', 'Antoine de Saint-Exupery', '978-0156012195', 'Harcourt', 1943, 'Classic', 6, '/images/thelittleprince.jpg', 'A poetic tale about wonder, friendship, and seeing with the heart.', '2026-04-10 00:00:00', '2026-04-10 00:00:00', NULL);

ALTER TABLE `users` AUTO_INCREMENT = 3;
ALTER TABLE `books` AUTO_INCREMENT = 25;

ALTER TABLE `loans`
  ADD CONSTRAINT `fk_loans_user_id`
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `loan_items`
  ADD CONSTRAINT `fk_loan_items_loan_id`
  FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_loan_items_book_id`
  FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

ALTER TABLE `sessions`
  ADD CONSTRAINT `fk_sessions_user_id`
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

SET FOREIGN_KEY_CHECKS = 1;
