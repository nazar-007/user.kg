-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Сен 09 2018 г., 20:16
-- Версия сервера: 5.7.20-0ubuntu0.16.04.1
-- Версия PHP: 7.1.20-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `user.kg`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `nickname`, `surname`, `gender`, `birthdate`, `role`) VALUES
(1, 'nazar', '202cb962ac59075b964b07152d234b70', 'Назар', 'Бакытов', 'man', '1998-03-07', 'admin'),
(2, 'starscraper225', '202cb962ac59075b964b07152d234b70', 'Star', 'War', 'woman', '2018-09-08', 'user'),
(3, 'Zuck', '202cb962ac59075b964b07152d234b70', 'Марк', 'Цукерберг', 'man', '1984-05-14', 'user'),
(4, 'Steve', '202cb962ac59075b964b07152d234b70', 'Steve', 'Jobs', 'man', '1955-02-24', 'admin'),
(5, 'Gates', '202cb962ac59075b964b07152d234b70', 'Билл', 'Гейтс', 'man', '1955-10-28', 'admin'),
(6, 'Durov', '202cb962ac59075b964b07152d234b70', 'Павел', 'Дуров', 'man', '1984-10-10', 'admin'),
(7, 'Elon', '202cb962ac59075b964b07152d234b70', 'Илон', 'Маск', 'man', '1971-06-28', 'admin');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
