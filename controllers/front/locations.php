<?php
/**
 * <ModuleName> => cheque
 * <FileName> => validation.php
 * Format expected: <ModuleName><FileName>ModuleFrontController
 */
class StorelocationsLocationsModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
      parent::initContent();

      $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT'); //get default shop language
      if(!$defaultLang || empty($defaultLang)) $defaultLang = 1;
      $sql = 'SELECT '._DB_PREFIX_.'store.id_store, '._DB_PREFIX_.'store_lang.name, '._DB_PREFIX_.'store_lang.id_lang, '._DB_PREFIX_.'store_lang.address1, '._DB_PREFIX_.'store.latitude, '._DB_PREFIX_.'store.longitude, '._DB_PREFIX_.'store_lang.hours FROM '._DB_PREFIX_.'store INNER JOIN '._DB_PREFIX_.'store_lang ON '._DB_PREFIX_.'store.id_store='._DB_PREFIX_.'store_lang.id_store';
      
      if ($results = Db::getInstance()->ExecuteS($sql)){
        foreach ($results as $row){
          if($row["id_lang"] == $defaultLang){
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
        ));
      
      $this->setTemplate('module:storelocations/views/templates/front/locations.tpl');
    }
}