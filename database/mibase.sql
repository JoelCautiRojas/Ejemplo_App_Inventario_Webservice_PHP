CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `codigo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `categoria` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `precio` decimal(6,2) NOT NULL,
  `stock` int(4) NOT NULL,
  `imagen` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;


INSERT INTO `productos` (`id`, `nombre`, `codigo`, `categoria`, `descripcion`, `precio`, `stock`, `imagen`) VALUES
(1, 'Taladro Bosch PSB 530 RE', 'TA002BO235', 'Taladro', 'Taladro percutor de 530 Watts de potencia con reversa, chuck y llave de presion.\r\nGarantia 1 año Bosch.', '200.00', 20, 'imagen1.jpg'),
(2, 'Taladro Truper 450 RE', 'TAL006TRU3', 'Taladro', 'Taladro percutor de 450 Watts de potencia con reversa chuck y llave de presion. Grantia 6 meses Truper.', '150.00', 50, 'imagen2.jpg');
