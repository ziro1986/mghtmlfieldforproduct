<?php
/**
 * HTML field for product
 *
 * @author    Michał Grzesik <mgrzesik@refix.pl>
 * @copyright REFIX 2021 - https://refix.pl
 * @license   Commercial
 * @version   1.0.0
 *
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'mghffp` (
    `id_mghffp` int(11) NOT NULL AUTO_INCREMENT,
    `id_product` int(11) NOT NULL,
    `html` text,
    PRIMARY KEY  (`id_mghffp`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
