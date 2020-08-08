<?php
/**
* 2007-2020 PrestaShop SA and Contributors
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
class Storelocations extends Module
{
    public function __construct()
    {
        $this->name = 'storelocations';
        $this->tab = 'seo';
        $this->version = '1.0.0';
        $this->author = 'MSLT';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = '34fc6665ca394ee1e50b7f7eabcda499';
        parent::__construct();
        $this->displayName = 'Store locations';
        $this->description = $this->l('Generate a Google map for all your stores in one page.');
        $this->ps_versions_compliancy = array(
            'min' => '1.7.1.0',
            'max' => _PS_VERSION_,
        );
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
    }

    public function getContent()
    {
        $output = null;
    
        if (Tools::isSubmit('submit'.$this->name)) {
            $api_key = (string)Tools::getValue('SL_API_KEY');
            $lat = (string)Tools::getValue('SL_LAT');
            $long = (string)Tools::getValue('SL_LONG');
    
            if (!$api_key ||
                empty($api_key) ||
                !Validate::isGenericName($api_key) ||
                !$lat ||
                empty($lat) ||
                !Validate::isGenericName($lat) ||
                !$long ||
                empty($long) ||
                !Validate::isGenericName($long)
            ) {
                $output .= $this->displayError($this->l('Please fill out all fields'));
            } else {
                Configuration::updateValue('SL_API_KEY', $api_key);
                Configuration::updateValue('SL_LAT', $lat);
                Configuration::updateValue('SL_LONG', $long);
                $output .= $this->displayConfirmation($this->l('Configuration updated'));
            }
        }
        
        return $output.$this->displayForm();
    }

    public function displayForm()
    {
        $fieldsForm = null;
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');
        // Init Fields form array
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Map configuration'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Google API key'),
                    'name' => 'SL_API_KEY',
                    'size' => 20,
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Default latitude coordinates'),
                    'name' => 'SL_LAT',
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Default longitude coordinates'),
                    'name' => 'SL_LONG',
                    'required' => true
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];
        
        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        // Load current value
        $helper->fields_value['SL_API_KEY'] = Tools::getValue('SL_API_KEY', Configuration::get('SL_API_KEY'));
        $helper->fields_value['SL_LAT'] = Tools::getValue('SL_LAT', Configuration::get('SL_LAT'));
        $helper->fields_value['SL_LONG'] = Tools::getValue('SL_LONG', Configuration::get('SL_LONG'));
        return $helper->generateForm($fieldsForm);
    }
}
