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

if (!defined('_PS_VERSION_')) {
    exit;
}

class MgHtmlFieldForProduct extends Module {
    public function __construct()
    {
        $this->name = 'mghtmlfieldforproduct';
        $this->tab = 'front-office-features';
        $this->version = '1.0.0';
        $this->author = 'Michał Grzesik';
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Additional HTML on product page.');
        $this->description = $this->l('Display additional HTML on product page.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        $this->ps_versions_compliancy = array('min' => '1.7.6.8', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('actionProductUpdate') &&
            $this->registerHook('displayMghfpp') &&
            $this->registerHook('displayAdminProductsExtra');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall()&&
            $this->unregisterHook('actionProductUpdate') &&
            $this->unregisterHook('displayMghfpp') &&
            $this->unregisterHook('displayAdminProductsExtra');
    }

    public function getHtml($id_product)
    {
        $sql = new DbQuery();
        $sql->select('`html`');
        $sql->from('mghffp');
        $sql->where('id_product = ' . $id_product . '');

        $result = Db::getInstance()->executeS($sql);

        (empty($result)) ? $html = false : $html = $result[0]['html'];

        return $html;
    }

    public function addOrUpdateHtml($id_product, $mghffp_html)
    {
        $html = $this->getHtml($id_product);

        if ($html != false) {
            if ($html !== $mghffp_html) {
                Db::getInstance()->update('mghffp', array(
                    'html' => $mghffp_html
                ), 'id_product ='.(int) $id_product);
            }
        } else {
            Db::getInstance()->insert('mghffp', array(
                'id_product' => (int) $id_product,
                'html' => $mghffp_html
            ));
        }
    }

    public function removeHtml($id_product)
    {
        Db::getInstance()->delete(
            'mghffp',
            '`id_product` = ' . (int) $id_product
        );
    }

    public function hookDisplayMghfpp($params)
    {
        $html = $this->getHtml($params['id_product']);

        if ($html == false) {
            return false;
        } else {
            $this->context->smarty->assign('html', $html);
            return $this->display(__FILE__, 'mghfpp_dm.tpl');
        }
    }

    public function hookActionProductUpdate($params)
    {
        $id_product = $params['id_product'];
        $mghffp_html = Tools::getValue('mghffp_html');
        
        if (empty($mghffp_html)) {
            if (!empty($this->getHtml($id_product))) {
                $this->removeHtml($id_product);
            }
        } else {
            $this->addOrUpdateHtml($id_product, $mghffp_html);
        }
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $this->context->smarty->assign('html', $this->getHtml($params['id_product']));
        return $this->context->smarty->fetch($this->local_path.'views/templates/admin/mghffp_dape.tpl');
    }
}
