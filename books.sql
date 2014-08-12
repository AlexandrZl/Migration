-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Авг 12 2014 г., 19:11
-- Версия сервера: 5.5.38-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `books`
--

-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE IF NOT EXISTS `author` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `First Name` varchar(255) NOT NULL,
  `Last Name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `author`
--

INSERT INTO `author` (`id`, `First Name`, `Last Name`) VALUES
(1, 'Jack', 'London'),
(2, 'EM', 'Remarque'),
(3, 'Arkady', 'Strugatsky'),
(4, 'Boris', 'Strugatsky'),
(5, 'Ilya', 'Ilf'),
(6, 'Yevgeny', 'Petrov'),
(7, 'Ernest', 'Hemingway');

-- --------------------------------------------------------

--
-- Структура таблицы `book author`
--

CREATE TABLE IF NOT EXISTS `book author` (
  `id author` int(11) NOT NULL,
  `id book` int(11) NOT NULL,
  `delta` int(11) NOT NULL AUTO_INCREMENT,
  UNIQUE KEY `delta` (`delta`),
  UNIQUE KEY `id author_2` (`id author`,`id book`,`delta`),
  KEY `id author` (`id author`,`id book`),
  KEY `id book` (`id book`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `book author`
--

INSERT INTO `book author` (`id author`, `id book`, `delta`) VALUES
(1, 1, 1),
(2, 2, 2),
(2, 3, 3),
(3, 4, 4),
(3, 5, 5),
(4, 5, 6),
(5, 6, 7),
(6, 6, 8),
(7, 7, 9);

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Дамп данных таблицы `books`
--

INSERT INTO `books` (`id`, `name`) VALUES
(1, 'white Fang'),
(2, 'Three Comrades'),
(3, 'On the Western Front'),
(4, 'The expedition to the underworld'),
(5, 'Roadside Picnic'),
(6, 'The Golden Calf'),
(7, 'For Whom the Bell Tolls');

-- --------------------------------------------------------

--
-- Структура таблицы `book tags`
--

CREATE TABLE IF NOT EXISTS `book tags` (
  `id book` int(11) NOT NULL,
  `id tag` int(11) NOT NULL,
  UNIQUE KEY `id book_2` (`id book`,`id tag`),
  KEY `id book` (`id book`,`id tag`),
  KEY `id tag` (`id tag`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `book tags`
--

INSERT INTO `book tags` (`id book`, `id tag`) VALUES
(1, 1),
(2, 2),
(3, 2),
(6, 2),
(7, 2),
(4, 3),
(5, 4);

-- --------------------------------------------------------

--
-- Структура таблицы `cost format`
--

CREATE TABLE IF NOT EXISTS `cost format` (
  `id release` int(11) NOT NULL,
  `id format` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  UNIQUE KEY `id release_2` (`id release`,`id format`),
  KEY `id release` (`id release`,`id format`),
  KEY `id format` (`id format`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `cost format`
--

INSERT INTO `cost format` (`id release`, `id format`, `cost`) VALUES
(1, 1, 150),
(2, 1, 300),
(3, 1, 240),
(4, 1, 140),
(5, 1, 100),
(6, 1, 200),
(7, 1, 100);

-- --------------------------------------------------------

--
-- Структура таблицы `edition`
--

CREATE TABLE IF NOT EXISTS `edition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `edition` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `edition`
--

INSERT INTO `edition` (`id`, `edition`) VALUES
(1, 'Macmillan and Company,New York'),
(2, 'Little, Brown and Company'),
(3, 'Haus Ullstein'),
(4, 'Moscow Worker'),
(5, 'young Guard'),
(6, 'Magazine "30 days", 1931, ? 1-7, 9-12.'),
(7, 'Charles Scribner’s Sons');

-- --------------------------------------------------------

--
-- Структура таблицы `editor`
--

CREATE TABLE IF NOT EXISTS `editor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `editor` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `editor` (`editor`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `format`
--

CREATE TABLE IF NOT EXISTS `format` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `format` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `format`
--

INSERT INTO `format` (`id`, `format`) VALUES
(1, 'hardcover');

-- --------------------------------------------------------

--
-- Структура таблицы `release`
--

CREATE TABLE IF NOT EXISTS `release` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id editor` int(11) NOT NULL,
  `year` date NOT NULL,
  `id edition` int(11) NOT NULL,
  `id cost format` int(11) NOT NULL,
  `id book` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id editor` (`id editor`,`id edition`,`id cost format`,`id book`),
  KEY `id book` (`id book`),
  KEY `id edition` (`id edition`),
  KEY `id cost format` (`id cost format`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Дамп данных таблицы `release`
--

INSERT INTO `release` (`id`, `id editor`, `year`, `id edition`, `id cost format`, `id book`) VALUES
(1, 1, '1906-05-01', 1, 1, 1),
(2, 2, '1936-12-01', 2, 2, 2),
(3, 3, '1929-12-01', 3, 3, 3),
(4, 4, '1988-01-01', 4, 4, 4),
(5, 5, '1972-01-01', 5, 5, 5),
(6, 6, '1931-08-12', 6, 6, 6),
(7, 7, '1940-08-06', 7, 7, 7);

-- --------------------------------------------------------

--
-- Структура таблицы `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Дамп данных таблицы `tag`
--

INSERT INTO `tag` (`id`, `tag`) VALUES
(1, 'Tale'),
(2, 'novel'),
(3, 'fairy tale'),
(4, 'fiction novel');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `author`
--
ALTER TABLE `author`
  ADD CONSTRAINT `author_ibfk_1` FOREIGN KEY (`id`) REFERENCES `book author` (`id author`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `book author`
--
ALTER TABLE `book author`
  ADD CONSTRAINT `book author_ibfk_1` FOREIGN KEY (`id book`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `book tags`
--
ALTER TABLE `book tags`
  ADD CONSTRAINT `book tags_ibfk_1` FOREIGN KEY (`id book`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cost format`
--
ALTER TABLE `cost format`
  ADD CONSTRAINT `cost format_ibfk_1` FOREIGN KEY (`id release`) REFERENCES `release` (`id cost format`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `edition`
--
ALTER TABLE `edition`
  ADD CONSTRAINT `edition_ibfk_1` FOREIGN KEY (`id`) REFERENCES `release` (`id edition`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `editor`
--
ALTER TABLE `editor`
  ADD CONSTRAINT `editor_ibfk_1` FOREIGN KEY (`id`) REFERENCES `release` (`id editor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `format`
--
ALTER TABLE `format`
  ADD CONSTRAINT `format_ibfk_1` FOREIGN KEY (`id`) REFERENCES `cost format` (`id format`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `release`
--
ALTER TABLE `release`
  ADD CONSTRAINT `release_ibfk_1` FOREIGN KEY (`id book`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `tag_ibfk_1` FOREIGN KEY (`id`) REFERENCES `book tags` (`id tag`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
