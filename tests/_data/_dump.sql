-- Adminer 4.7.1 MySQL dump
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`
(
    `td_id`                   int(11)      NOT NULL COMMENT 'Идентификатор в TD',
    `sku`                     varchar(100) NOT NULL COMMENT 'Номер заказа',
    `serv`                    varchar(100) NOT NULL DEFAULT 'выдача' COMMENT 'Услуга',
    `gp_status`               varchar(100)          DEFAULT NULL COMMENT 'Статус в Главпункт',
    `td_status_id`            int(11)      NOT NULL COMMENT 'Идентификатор статус ТД',
    `td_status_name`          varchar(255) NOT NULL COMMENT 'Название статуса ТД',
    `shipment_id`             int(11)      NOT NULL COMMENT 'Номер отправки',
    `return_shipment_id`      int(11)               DEFAULT NULL COMMENT 'Номер возвратной отправки',
    `barcode`                 varchar(100) NOT NULL COMMENT 'Штрих-код заказа',
    `price`                   float        NOT NULL COMMENT 'Цена к оплате клиентом',
    `td_status`               int(11)               DEFAULT NULL COMMENT '!deprecated Статус в ГП',
    `payment_type`            varchar(100)          DEFAULT NULL COMMENT 'Тип оплаты',
    `create_date`             datetime              DEFAULT NULL COMMENT 'Дата создания',
    `modified_date`           datetime              DEFAULT NULL ON UPDATE current_timestamp() COMMENT 'Дата обновления',
    `pkg_partial`             varchar(100)          DEFAULT NULL COMMENT 'sku заказа, при частичной выдаче',
    `client_delivery_price`   float        NOT NULL DEFAULT 0 COMMENT 'Цена доставки для клиента',
    `weight`                  float        NOT NULL DEFAULT 0 COMMENT 'Вес (в кг)',
    `buyer_fio`               varchar(255) NOT NULL COMMENT 'Имя получателя',
    `buyer_phone`             varchar(255) NOT NULL COMMENT 'Телефон получателя',
    `buyer_address`           varchar(255) NOT NULL COMMENT 'Адрес получателя',
    `delivery_date`           date         NOT NULL COMMENT 'Дата доставки',
    `comment`                 varchar(255) NOT NULL COMMENT 'Комментарий',
    `dst_punkt_id`            varchar(255) NOT NULL COMMENT 'Пункт выдачи',
    `items_count`             varchar(255) NOT NULL COMMENT 'Кол-во мест',
    `partial_giveout_enabled` tinyint(1)   NOT NULL COMMENT 'Доступна ли частичная выдача',
    `can_open_box`            tinyint(1)   NOT NULL COMMENT 'Можно ли открывать до оплаты',
    UNIQUE KEY `td_id` (`td_id`),
    KEY `shipment_id` (`shipment_id`),
    KEY `return_shipment_id` (`return_shipment_id`),
    CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT ='Заказы';

DROP TABLE IF EXISTS `orders_log`;
CREATE TABLE `orders_log`
(
    `order_id` int(11)      NOT NULL COMMENT 'Идентификатор заказа в TD',
    `status`   varchar(100) NOT NULL COMMENT 'Статус заказа',
    `date`     datetime     NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp() COMMENT 'Дата установления заказа',
    KEY `order_id` (`order_id`),
    CONSTRAINT `orders_log_ibfk_4` FOREIGN KEY (`order_id`) REFERENCES `orders` (`td_id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT ='Изменение статусов заказа';

DROP TABLE IF EXISTS `order_part`;
CREATE TABLE `order_part`
(
    `order_id`       int(11)      NOT NULL COMMENT 'Идентификатор заказа',
    `id`             int(11)      NOT NULL COMMENT 'Идентификатор в ТД',
    `td_status_id`   int(11)      NOT NULL COMMENT 'ИД статуса в ТД',
    `td_status_name` varchar(255) NOT NULL COMMENT 'Название статуса в ТД',
    `name`           varchar(255) NOT NULL COMMENT 'Имя товара',
    `price`          float        NOT NULL COMMENT 'Цена товара',
    `declared_price` float        NOT NULL COMMENT 'Объявленная стоимость',
    `barcode`        varchar(255) NOT NULL COMMENT 'Штрих-код товара',
    `num`            int(11)      NOT NULL COMMENT 'Кол-во',
    `returning`      tinyint(1)   NOT NULL DEFAULT 0 COMMENT 'Идёт ли на возврат после частичной выдачи',
    `article`        varchar(255) NOT NULL COMMENT 'Артикул товара',
    `weight`         int(11)      NOT NULL COMMENT 'Вес товара',
    KEY `order_id` (`order_id`),
    CONSTRAINT `order_part_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`td_id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT ='Номенклатура заказа';

DROP TABLE IF EXISTS `punkts`;
CREATE TABLE `punkts`
(
    `tdId` int(11)                           NOT NULL COMMENT 'Идентификатор в TD',
    `gpId` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Идентификатор в ГП',
    `city` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT 'Город нахождения',
    `enabled` varchar(100) NOT NULL DEFAULT '1' COMMENT 'Включён пункт или нет',
    UNIQUE KEY `id_key` (`tdId`, `gpId`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT ='Пункты выдачи';

DROP TABLE IF EXISTS `shipments`;
CREATE TABLE `shipments`
(
    `id`           int(11)      NOT NULL COMMENT 'Идентификатор в TD',
    `punkt_id`     varchar(100) NOT NULL COMMENT 'Пункт получения',
    `move_id`      int(11)      DEFAULT NULL COMMENT 'Накладная в ГП',
    `created_date` datetime     DEFAULT NULL COMMENT 'Дата создания',
    `status`       enum ('none','created','accepted','partly-accepted','pre-accepted') NOT NULL DEFAULT 'none' COMMENT 'Статус накладной',
    UNIQUE KEY `id` (`id`),
    UNIQUE KEY `move_id` (`move_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT ='Отправки';

DROP TABLE IF EXISTS `shipments_log`;
CREATE TABLE `shipments_log`
(
    `shipment_id` int(11)      NOT NULL COMMENT 'Идентификатор поставки',
    `status`      varchar(255) NOT NULL COMMENT 'Статус',
    `date`        datetime     NOT NULL COMMENT 'Дата установки статуса',
    KEY `shipment_id` (`shipment_id`),
    CONSTRAINT `shipments_log_ibfk_1` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8 COMMENT ='Изменение статусов поставок';
