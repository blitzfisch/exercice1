<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Gt2i extends Module {

    public function __construct() {
        $this->name = 'gt2i';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Eric Streignart';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.0', 'max' => '2.0');

        parent::__construct();

        $this->displayName = $this->l('Module Gt2i');
        $this->description = $this->l('Module de contrôle du stock.');

        $this->confirmUninstall = $this->l('Voulez-vous vraiment désinstaller ce module ?');

        if (!Configuration::get('GT2I')) {
            $this->warning = $this->l('Aucun nom fourni');
        }
    }

    public function install() {

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        return parent::install() &&
                $this->registerHook('actionUpdateQuantity') &&
                Configuration::updateValue('GT2I', 'hello world');
    }

    public function uninstall() {
        if (!parent::uninstall() ||
                !Configuration::deleteByName('GT2I')) {
            return false;
        }
        return true;
    }

    public function hookActionUpdateQuantity($params) {

        $data = array(
            '{id_product}' => $params['id_product'],
            '{id_product_attribute}' => $params['id_product_attribute'],
            '{stock}' => $params['quantity']
        );
        
        $emailPath = dirname(__FILE__) . '/mails/';

        Mail::Send(intval($cookie->id_lang), 'stock', 'Etat des stocks', $data, 'gestion.stock@example.com', NULL, NULL, NULL, NULL, NULL, $emailPath);
    }

}
