-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Авг 12 2016 г., 14:07
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `wallet`
--

-- --------------------------------------------------------

--
-- Структура таблицы `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wallet_id` int(11) NOT NULL,
  `guid` varchar(50) NOT NULL,
  `u_id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL COMMENT '1 - пополнение счета пользователем',
  `system` varchar(100) NOT NULL DEFAULT 'console',
  `system_id` varchar(100) NOT NULL,
  `descr` text NOT NULL,
  `money` float NOT NULL,
  `date_creat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payer_fio` varchar(255) NOT NULL,
  `payer_phone` varchar(20) NOT NULL,
  `payer_email` varchar(30) NOT NULL,
  `payer_address` text NOT NULL,
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `status` int(2) NOT NULL,
  `highlight` tinyint(1) NOT NULL DEFAULT '0',
  `admin_comment` varchar(255) NOT NULL,
  `return_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=77424 ;

--
-- Дамп данных таблицы `payment`
--

INSERT INTO `payment` (`id`, `wallet_id`, `guid`, `u_id`, `type`, `system`, `system_id`, `descr`, `money`, `date_creat`, `payer_fio`, `payer_phone`, `payer_email`, `payer_address`, `show`, `status`, `highlight`, `admin_comment`, `return_url`) VALUES
(77423, 1, '0578740c636a52ea460f0d873621f1c8', 0, '', 'console', '', 'ADD', 100, '2016-08-10 15:47:48', '', '', '', '', 1, 1, 0, '', ''),
(77422, 2, '418af3f8c8f21bd5507722108e24c2c0', 0, '', 'console', '', 'ADD', 100, '2016-08-10 15:47:42', '', '', '', '', 1, 1, 0, '', ''),
(77420, 3, 'c68980e1edff2ef33aae0dc424d5a0f7', 0, '', 'console', '', 'TAKE_CONVERT', -66, '2016-08-10 15:30:33', '', '', '', '', 1, 1, 0, '', ''),
(77421, 2, '011ee8164994bbe2fbac9f0680d013bc', 0, '', 'console', '', 'ADD_CONVERT', 0.960352, '2016-08-10 15:30:33', '', '', '', '', 1, 1, 0, '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `wallet`
--

CREATE TABLE IF NOT EXISTS `wallet` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `u_id` int(20) NOT NULL,
  `money` float NOT NULL,
  `TYPE` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `wallet`
--

INSERT INTO `wallet` (`id`, `u_id`, `money`, `TYPE`) VALUES
(1, 0, 100, 'RUR'),
(2, 0, 100.96, 'USD'),
(3, 0, 0, 'KGS'),
(4, 0, 0, 'EUR');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
