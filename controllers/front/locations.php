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

class StorelocationsLocationsModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $stores = null;
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');
        if (!$defaultLang || empty($defaultLang)) {
            $defaultLang = 1;
        }
        $sql = 'SELECT '._DB_PREFIX_.'store.id_store, '._DB_PREFIX_.'store_lang.name, '._DB_PREFIX_.'store_lang.id_lang, '._DB_PREFIX_.'store_lang.address1, '._DB_PREFIX_.'store.latitude, '._DB_PREFIX_.'store.longitude, '._DB_PREFIX_.'store_lang.hours FROM '._DB_PREFIX_.'store INNER JOIN '._DB_PREFIX_.'store_lang ON '._DB_PREFIX_.'store.id_store='._DB_PREFIX_.'store_lang.id_store';
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                if ($row["id_lang"] == $defaultLang) {
                    $stores[] = array(
                      'name' => $row["name"],
                      'address' => $row["address1"],
                      'latitude' => $row['latitude'],
                      'longitude' => $row['longitude']
                    );
                }
            }
        }
        $this->context->smarty->assign(
            array(
              'api_key' => Configuration::get('SL_API_KEY'), // Retrieved from GET vars
              'stores' => $stores,
              'latitude' => Configuration::get('SL_LAT'),
              'longitude' => Configuration::get('SL_LONG')
            )
        );
        $this->setTemplate('module:storelocations/views/templates/front/locations.tpl');
    }
}
