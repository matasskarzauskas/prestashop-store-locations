<?php
/*

*/

if (!defined('_PS_VERSION_')) {
    exit;
}


class Storelocations extends Module{

    public function __construct()
    {
        $this->name = 'storelocations';
        $this->tab = 'seo';
        $this->version = '1.0.0';
        $this->author = 'Mattprest';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = 'Store locations';
        $this->description = $this->l('Generate a Google map store locator inside your contact page.');
        $this->ps_versions_compliancy = array(
            'min' => '1.7.1.0',
            'max' => _PS_VERSION_,
        );
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');

        DB::getInstance()->execute("INSERT INTO '"._DB_PREFIX_."hook'
          SET 'name'= 'mapHook',
              'title'= 'Store locator hook',
              'description'= 'Displays the store locator map.'
        ");

        
    }

    public function getContent()
    {
        $output = null;
    
        if (Tools::isSubmit('submit'.$this->name)) {
            $storelocator = strval(Tools::getValue('storelocator'));
    
            if (
                !$storelocator ||
                empty($storelocator) ||
                !Validate::isGenericName($storelocator)
            ) {
                $output .= $this->displayError($this->l('Invalid Configuration value'));
            } else {
                Configuration::updateValue('storelocator', $storelocator);
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        
        return $output.$this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Configuration value'),
                    'name' => 'storelocator',
                    'size' => 20,
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
        $helper->fields_value['storelocator'] = Tools::getValue('storelocator', Configuration::get('storelocator'));

        return $helper->generateForm($fieldsForm);
    }

    public function hookMapHook($params)
    {
        echo "Hello World!";
    }

}


?>