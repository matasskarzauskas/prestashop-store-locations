<?php
/**
 * <ModuleName> => cheque
 * <FileName> => validation.php
 * Format expected: <ModuleName><FileName>ModuleFrontController
 */
class StorelocatorLocationsModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
      parent::initContent();
      $this->context->smarty->assign(
        array(
          'api' => Configuration::get('storelocator'), // Retrieved from GET vars
        ));

        $this->setTemplate('module:storelocations/views/templates/front/locations.tpl');
    }

    
}