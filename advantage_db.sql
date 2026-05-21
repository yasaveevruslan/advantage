-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 21 2026 г., 06:59
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `advantage_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `addresses`
--

CREATE TABLE `addresses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `city` varchar(100) NOT NULL DEFAULT 'Казань',
  `street` varchar(255) NOT NULL,
  `house` varchar(50) NOT NULL,
  `apartment` varchar(20) DEFAULT NULL,
  `floor` varchar(20) DEFAULT NULL,
  `entrance` varchar(20) DEFAULT NULL,
  `comment` text,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `city`, `street`, `house`, `apartment`, `floor`, `entrance`, `comment`, `is_default`, `created_at`) VALUES
(4, 4, 'Казань', 'fewfw', 'fwe', 'fwe', 'f', 'we', 'few', 1, '2026-05-21 06:00:44');

-- --------------------------------------------------------

--
-- Структура таблицы `cart`
--

CREATE TABLE `cart` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `item_type` enum('dish','set') NOT NULL,
  `item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `session_id`, `item_type`, `item_id`, `quantity`, `added_at`) VALUES
(14, 4, NULL, 'dish', 5, 1, '2026-05-21 03:53:03'),
(15, 4, NULL, 'set', 1, 1, '2026-05-21 03:53:03'),
(16, 4, NULL, 'set', 1, 1, '2026-05-21 03:53:03'),
(17, 4, NULL, 'dish', 5, 1, '2026-05-21 03:53:10'),
(18, 4, NULL, 'set', 1, 1, '2026-05-21 03:53:10'),
(19, 4, NULL, 'set', 1, 1, '2026-05-21 03:53:10'),
(20, 4, NULL, 'dish', 5, 1, '2026-05-21 03:56:35'),
(21, 4, NULL, 'set', 1, 1, '2026-05-21 03:56:35'),
(22, 4, NULL, 'set', 1, 1, '2026-05-21 03:56:35'),
(23, 4, NULL, 'dish', 5, 1, '2026-05-21 03:56:57'),
(24, 4, NULL, 'set', 1, 1, '2026-05-21 03:56:57'),
(25, 4, NULL, 'set', 1, 1, '2026-05-21 03:56:57'),
(26, 4, NULL, 'dish', 5, 1, '2026-05-21 03:57:45'),
(27, 4, NULL, 'set', 1, 1, '2026-05-21 03:57:45'),
(28, 4, NULL, 'set', 1, 1, '2026-05-21 03:57:45'),
(29, 4, NULL, 'dish', 5, 1, '2026-05-21 03:58:22'),
(30, 4, NULL, 'set', 1, 1, '2026-05-21 03:58:22'),
(31, 4, NULL, 'set', 1, 1, '2026-05-21 03:58:22');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'тест'),
(2, 'Категория тест обновления'),
(3, 'fewfw'),
(5, 'пукпу');

-- --------------------------------------------------------

--
-- Структура таблицы `dishes`
--

CREATE TABLE `dishes` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `ingredients` text,
  `price` decimal(10,2) NOT NULL,
  `kcal` int DEFAULT '0',
  `protein` int DEFAULT '0',
  `fat` int DEFAULT '0',
  `carbs` int DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `dishes`
--

INSERT INTO `dishes` (`id`, `name`, `description`, `ingredients`, `price`, `kcal`, `protein`, `fat`, `carbs`, `image`, `category_id`, `is_available`) VALUES
(3, 'тестпукпцк', 'пууцуацпцупц', 'упцупцупцупцупцуп', '23123.00', 23213, 3123, 3123, 412, 'dish_1779237486_77aacdcc.png', 1, 1),
(4, 'тестпукпцк', 'пууцуацпцупц', 'упцупцупцупцупцуп', '23.00', 23213, 3123, 312, 412, 'dish_1779237496_8e762319.png', 1, 1),
(5, 'gerge', 'gergerge', 'gergerg', '24124.00', 423, 23, 23, 23, 'dish_1779321600_a2989599.jpg', 2, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `item_type` enum('dish','set') NOT NULL,
  `item_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('new','confirmed','preparing','delivering','completed','cancelled') DEFAULT 'new',
  `delivery_address` text NOT NULL,
  `delivery_time` varchar(20) DEFAULT NULL,
  `payment_method` enum('card','cash','sbp') DEFAULT NULL,
  `user_comment` text,
  `total_amount` decimal(10,2) NOT NULL,
  `promocode_id` int DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `final_amount` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `status`, `delivery_address`, `delivery_time`, `payment_method`, `user_comment`, `total_amount`, `promocode_id`, `discount_amount`, `final_amount`, `created_at`) VALUES
(1, 4, '2026-05-21 03:24:26', 'completed', 'Казань, ул. fewfw, д. fwe, кв. fwe', '10:00', 'card', 'fwefewf', '72418.00', 3, '14483.60', '57934.40', '2026-05-21 06:24:26');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `item_type` enum('dish','set') NOT NULL,
  `item_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_at_time` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_type`, `item_id`, `quantity`, `price_at_time`) VALUES
(1, 1, 'dish', 5, 1, '24124.00'),
(2, 1, 'set', 1, 1, '24147.00'),
(3, 1, 'set', 1, 1, '24147.00');

-- --------------------------------------------------------

--
-- Структура таблицы `promocodes`
--

CREATE TABLE `promocodes` (
  `id` int NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL,
  `min_order` decimal(10,2) DEFAULT '0.00',
  `valid_until` date DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `promocodes`
--

INSERT INTO `promocodes` (`id`, `code`, `discount_type`, `discount_value`, `min_order`, `valid_until`, `usage_limit`, `used_count`, `is_active`) VALUES
(2, 'FERGWGWERGW', 'fixed', '23.00', '0.00', NULL, 0, 0, 0),
(3, 'ауцац', 'percentage', '20.00', '1200.00', NULL, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_moderated` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `rating`, `comment`, `created_at`, `is_moderated`) VALUES
(4, 4, 2, 'efwefewfewfewfewfe', '2026-05-19 02:32:09', 1),
(5, 4, 5, 'fwefwefwefwefwef', '2026-05-19 02:33:55', 1),
(6, 4, 4, '31231241241241', '2026-05-19 23:55:14', 0),
(7, 4, 2, 'fwefewfwefwefw', '2026-05-19 23:58:34', 0),
(8, 4, 1, 'цкукцуккцацуацуа', '2026-05-21 01:25:38', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `sets`
--

CREATE TABLE `sets` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `sets`
--

INSERT INTO `sets` (`id`, `name`) VALUES
(1, 'КАТТЕГОРИЯ 1'),
(2, 'КАТЕГОРИЯ 2');

-- --------------------------------------------------------

--
-- Структура таблицы `set_composition`
--

CREATE TABLE `set_composition` (
  `id` int NOT NULL,
  `set_dish_id` int NOT NULL,
  `dish_id` int NOT NULL,
  `quantity` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `set_composition`
--

INSERT INTO `set_composition` (`id`, `set_dish_id`, `dish_id`, `quantity`) VALUES
(1, 1, 5, 1),
(2, 1, 4, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `set_dishes`
--

CREATE TABLE `set_dishes` (
  `id` int NOT NULL,
  `set_id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `kcal` int DEFAULT '0',
  `protein` int DEFAULT '0',
  `fat` int DEFAULT '0',
  `carbs` int DEFAULT '0',
  `is_available` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `set_dishes`
--

INSERT INTO `set_dishes` (`id`, `set_id`, `name`, `image`, `description`, `price`, `kcal`, `protein`, `fat`, `carbs`, `is_available`, `created_at`) VALUES
(1, 1, 'Кето-старт', 'set_1779324093_e381bd17.jpeg', '', '24147.00', 23636, 3146, 335, 435, 1, '2026-05-21 03:41:33');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `full_name`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'adelamingaraeva220606@mail.ru', '$2y$10$2G0QNJXRAT23E8fcEBVl/.uwfFZ3lQA13Efbb4LBHtsmYUZ/TUp2a', 'Бариев Ислам Искандерович', '89228805707', NULL, 'user', '2026-05-18 19:06:17'),
(2, 'adelamingarkjdfjba220606@mail.ru', '$2y$10$wql7MwNzDYQ8rnLtdMZaqe0wcXOGzZdoyImIyp8SmHyPcOh3wqnOC', 'Аделя', '89228805789', NULL, 'user', '2026-05-18 19:07:31'),
(3, 'adelamingar606@mail.ru', '$2y$10$XAzBq1wOlHjYyQTay6nKwejSP7hHb766pEg854BNf3IemvsjLEaTO', 'Руслан', '89228705789', NULL, 'user', '2026-05-18 19:14:59'),
(4, 'twet22@mail.ru', '$2y$10$Je3UjxBNJSWDEkiUPCbsGu49XsSdJsNB.llcoNRzbxq5uGCHhiMCO', 'TWETWE', '79999999999', NULL, 'user', '2026-05-19 00:11:27');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`);

--
-- Индексы таблицы `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_item` (`user_id`,`item_type`,`item_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `promocode_id` (`promocode_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Индексы таблицы `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Индексы таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `sets`
--
ALTER TABLE `sets`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `set_composition`
--
ALTER TABLE `set_composition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `set_dish_id` (`set_dish_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Индексы таблицы `set_dishes`
--
ALTER TABLE `set_dishes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_set` (`set_id`),
  ADD KEY `idx_available` (`is_available`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `dishes`
--
ALTER TABLE `dishes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `sets`
--
ALTER TABLE `sets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `set_composition`
--
ALTER TABLE `set_composition`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `set_dishes`
--
ALTER TABLE `set_dishes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `dishes`
--
ALTER TABLE `dishes`
  ADD CONSTRAINT `dishes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`promocode_id`) REFERENCES `promocodes` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `set_composition`
--
ALTER TABLE `set_composition`
  ADD CONSTRAINT `set_composition_ibfk_1` FOREIGN KEY (`set_dish_id`) REFERENCES `set_dishes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `set_composition_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `dishes` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `set_dishes`
--
ALTER TABLE `set_dishes`
  ADD CONSTRAINT `set_dishes_ibfk_1` FOREIGN KEY (`set_id`) REFERENCES `sets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
