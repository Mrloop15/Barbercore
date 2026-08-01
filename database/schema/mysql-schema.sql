/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `barberias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barberias` (
  `id_barberia` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `google_maps_url` varchar(500) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_barberia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `canjes_recompensas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `canjes_recompensas` (
  `id_canje` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `id_cliente` bigint(20) unsigned NOT NULL,
  `id_recompensa` bigint(20) unsigned DEFAULT NULL,
  `puntos_usados` int(11) NOT NULL DEFAULT 0,
  `fecha_canje` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_canje`),
  KEY `idx_canjes_barberia` (`id_barberia`),
  KEY `idx_canjes_cliente` (`id_cliente`),
  KEY `idx_canjes_fecha` (`fecha_canje`),
  KEY `fk_canjes_recompensas` (`id_recompensa`),
  CONSTRAINT `fk_canjes_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE,
  CONSTRAINT `fk_canjes_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE,
  CONSTRAINT `fk_canjes_recompensas` FOREIGN KEY (`id_recompensa`) REFERENCES `recompensas` (`id_recompensa`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `citas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `citas` (
  `id_cita` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `id_cliente` bigint(20) unsigned NOT NULL,
  `id_servicio` bigint(20) unsigned NOT NULL,
  `id_barbero` bigint(20) unsigned DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` enum('pendiente','completada','cancelada') NOT NULL DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_cita`),
  KEY `idx_citas_barberia_fecha` (`id_barberia`,`fecha`),
  KEY `idx_citas_cliente` (`id_cliente`),
  KEY `idx_citas_servicio` (`id_servicio`),
  KEY `idx_citas_barbero` (`id_barbero`),
  KEY `idx_citas_estado` (`estado`),
  KEY `idx_citas_agenda` (`fecha`,`hora_inicio`,`hora_fin`),
  CONSTRAINT `fk_citas_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE,
  CONSTRAINT `fk_citas_barbero` FOREIGN KEY (`id_barbero`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_citas_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE,
  CONSTRAINT `fk_citas_servicios` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clientes` (
  `id_cliente` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cumpleanos` date DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `puntos` int(11) NOT NULL DEFAULT 0,
  `ultima_visita` date DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_cliente`),
  KEY `idx_clientes_barberia` (`id_barberia`),
  KEY `idx_clientes_nombre` (`nombre`,`apellido`),
  KEY `idx_clientes_ultima_visita` (`ultima_visita`),
  CONSTRAINT `fk_clientes_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `detalle_venta_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detalle_venta_productos` (
  `id_detalle` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_venta` bigint(20) unsigned NOT NULL,
  `id_producto` bigint(20) unsigned DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_detalle`),
  KEY `idx_detalle_venta` (`id_venta`),
  KEY `idx_detalle_producto` (`id_producto`),
  CONSTRAINT `fk_detalle_productos` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_detalle_ventas` FOREIGN KEY (`id_venta`) REFERENCES `ventas_productos` (`id_venta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `historial_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historial_servicios` (
  `id_historial` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `id_cliente` bigint(20) unsigned NOT NULL,
  `id_cita` bigint(20) unsigned DEFAULT NULL,
  `id_servicio` bigint(20) unsigned DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_servicio` date NOT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_historial`),
  KEY `idx_historial_barberia` (`id_barberia`),
  KEY `idx_historial_cliente` (`id_cliente`),
  KEY `idx_historial_fecha` (`fecha_servicio`),
  KEY `fk_historial_citas` (`id_cita`),
  KEY `fk_historial_servicios` (`id_servicio`),
  CONSTRAINT `fk_historial_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE,
  CONSTRAINT `fk_historial_citas` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_historial_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE,
  CONSTRAINT `fk_historial_servicios` FOREIGN KEY (`id_servicio`) REFERENCES `servicios` (`id_servicio`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `horarios_atencion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `horarios_atencion` (
  `id_horario` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `dia_semana` tinyint(3) unsigned NOT NULL,
  `abierto` tinyint(1) NOT NULL DEFAULT 1,
  `hora_apertura` time DEFAULT NULL,
  `hora_cierre` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_horario`),
  UNIQUE KEY `horarios_atencion_id_barberia_dia_semana_unique` (`id_barberia`,`dia_semana`),
  CONSTRAINT `horarios_atencion_id_barberia_foreign` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `movimientos_puntos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `movimientos_puntos` (
  `id_movimiento` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `id_cliente` bigint(20) unsigned NOT NULL,
  `tipo` enum('suma','resta') NOT NULL,
  `puntos` int(11) NOT NULL DEFAULT 0,
  `motivo` varchar(255) NOT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_movimiento`),
  KEY `idx_movimientos_barberia` (`id_barberia`),
  KEY `idx_movimientos_cliente` (`id_cliente`),
  KEY `idx_movimientos_tipo` (`tipo`),
  CONSTRAINT `fk_movimientos_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE,
  CONSTRAINT `fk_movimientos_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `correo` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `preguntas_frecuentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preguntas_frecuentes` (
  `id_pregunta` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `respuesta` text NOT NULL,
  `orden` smallint(5) unsigned NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_pregunta`),
  KEY `preguntas_frecuentes_id_barberia_activo_orden_index` (`id_barberia`,`activo`,`orden`),
  CONSTRAINT `preguntas_frecuentes_id_barberia_foreign` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id_producto` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL DEFAULT 0.00,
  `precio_venta` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_producto`),
  KEY `idx_productos_barberia` (`id_barberia`),
  KEY `idx_productos_stock` (`stock`,`stock_minimo`),
  KEY `idx_productos_activo` (`activo`),
  CONSTRAINT `fk_productos_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `recompensas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recompensas` (
  `id_recompensa` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `puntos_requeridos` int(11) NOT NULL DEFAULT 0,
  `tipo` enum('descuento','servicio','producto','premium') NOT NULL DEFAULT 'descuento',
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_recompensa`),
  KEY `idx_recompensas_barberia` (`id_barberia`),
  KEY `idx_recompensas_activo` (`activo`),
  CONSTRAINT `fk_recompensas_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `recordatorios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recordatorios` (
  `id_recordatorio` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `id_cliente` bigint(20) unsigned NOT NULL,
  `id_cita` bigint(20) unsigned DEFAULT NULL,
  `tipo` enum('cita','cliente_inactivo','cumpleanos','otro') NOT NULL DEFAULT 'otro',
  `mensaje` text NOT NULL,
  `estado` enum('pendiente','enviado','fallido') NOT NULL DEFAULT 'pendiente',
  `fecha_programada` datetime DEFAULT NULL,
  `fecha_enviado` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_recordatorio`),
  KEY `idx_recordatorios_barberia` (`id_barberia`),
  KEY `idx_recordatorios_cliente` (`id_cliente`),
  KEY `idx_recordatorios_estado` (`estado`),
  KEY `idx_recordatorios_fecha` (`fecha_programada`),
  KEY `fk_recordatorios_citas` (`id_cita`),
  CONSTRAINT `fk_recordatorios_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE,
  CONSTRAINT `fk_recordatorios_citas` FOREIGN KEY (`id_cita`) REFERENCES `citas` (`id_cita`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_recordatorios_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicios` (
  `id_servicio` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duracion_minutos` int(11) NOT NULL DEFAULT 30,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `mostrar_landing` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_servicio`),
  KEY `idx_servicios_barberia` (`id_barberia`),
  KEY `idx_servicios_activo` (`activo`),
  KEY `servicios_mostrar_landing_index` (`mostrar_landing`),
  CONSTRAINT `fk_servicios_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sessions_user_id` (`user_id`),
  KEY `idx_sessions_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','barbero') NOT NULL DEFAULT 'barbero',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`),
  KEY `fk_usuarios_barberias` (`id_barberia`),
  CONSTRAINT `fk_usuarios_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ventas_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ventas_productos` (
  `id_venta` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_barberia` bigint(20) unsigned NOT NULL,
  `id_cliente` bigint(20) unsigned DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fecha_venta` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_venta`),
  KEY `idx_ventas_barberia` (`id_barberia`),
  KEY `idx_ventas_cliente` (`id_cliente`),
  KEY `idx_ventas_fecha` (`fecha_venta`),
  CONSTRAINT `fk_ventas_barberias` FOREIGN KEY (`id_barberia`) REFERENCES `barberias` (`id_barberia`) ON UPDATE CASCADE,
  CONSTRAINT `fk_ventas_clientes` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2026_07_31_200000_add_landing_fields_to_servicios_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2026_07_31_210000_create_landing_configuration_tables',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2026_08_01_000000_require_barberia_for_usuarios',3);
