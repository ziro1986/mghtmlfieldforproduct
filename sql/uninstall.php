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

$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'mghffp`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
