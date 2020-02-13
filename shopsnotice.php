<?php
/**
 * 2020 Sarafoudis Nikolaos for 01generator.com
 *
 * This is a module for Prestashop.
 *
 *  @author    Sarafoudis Nikolaos for 01generator.com
 *  @copyright Copyright (c) 2020 Sarafoudis Nikolaos for 01generator.com
 *  @license   MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
/* Main Class */
class Shopsnotice extends Module
{

    /* Main Constructor */
    public function __construct()
    {

        if (!defined('_PS_VERSION_')) {
            exit;
        }

        $this->name = 'shopsnotice';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = '01generator';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Shops Notice');
        $this->description = $this->l('Leave a message at the top of your PrestaShop for your customers');

        $this->confirmUninstall = $this->l('Are you sure you want to unistall?');
    }

    /* Installation tasks */
    public function install()
    {

        if (!parent::install()
            || !$this->registerHook('displayBanner')
            || !$this->registerHook('displayAfterBodyOpeningTag')
            || !$this->installTab('AdminTools', 'AdminShopsNotice', $this->l('Shops Notice'))
        ) {
            return false;
        }

        // --------------------------------- FIELD OPTIONS
        Configuration::updateValue('SHOPSNOTICE_NOTICE_ENABLE', 0);
        Configuration::updateValue('SHOPSNOTICE_AFTERBODY', 1);
        Configuration::updateValue('SHOPSNOTICE_DESIGN', 1);
        Configuration::updateValue('SHOPSNOTICE_BG_COLOR', '#000000');
        Configuration::updateValue('SHOPSNOTICE_FONT_COLOR', '#ffffff');
        Configuration::updateValue('SHOPSNOTICE_FONT_WEIGHT', 2);
        Configuration::updateValue('SHOPSNOTICE_ALIGN', 2);
        Configuration::updateValue('SHOPSNOTICE_FONT_SIZE', 20);
        Configuration::updateValue('SHOPSNOTICE_DIV_PADDING', 10);

        // All went well :)
        return true;
    }

    /* Unistallation tasks */
    public function uninstall()
    {
        //Unistall the admin tab
        if (!parent::uninstall()
            || !$this->uninstallTab('AdminShopsNotice')
        ) {
            return false;
        }
        //All went well :)
        return true;
    }

    /* Install the required tabs by the module */
    public function installTab($parent, $class_name, $tab_name)
    {
        // Create new admin tab
        if (version_compare(_PS_VERSION_, '1.7.0.0 ', '<')) {
            // Create new admin tab 1.6
            $tab = new Tab();
            $tab->id_parent = (int) Tab::getIdFromClassName($parent);
            $tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = $tab_name;
            }

            $tab->class_name = $class_name;
            $tab->module = $this->name;
            $tab->active = 1;
            return $tab->add();
        } else {
            // Create new admin tab 1.7
            if ($parent == 'AdminTools') {
                $parent = 'ShopParameters';
            }
            $tab = new Tab();
            $tab->id_parent = (int) Tab::getIdFromClassName($parent);
            // $this->position = (int) Tab::getNewLastPosition($tab->id_parent);
            $tab->name = array();
            foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = $tab_name;
            }

            $tab->class_name = $class_name;
            $tab->module = $this->name;
            $tab->active = 1;
            $tab->hide_host_mode = 1;
            return $tab->add();
        }
    }

    /* Unistall the required admin tabs of the module */
    public function uninstallTab($class_name)
    {
        // Retrieve Tab ID
        $id_tab = (int) Tab::getIdFromClassName($class_name);
        // Load tab
        $tab = new Tab((int) $id_tab);
        // Delete it
        return $tab->delete();
    }

    public function getContent()
    {
        $processForm = $this->processConfiguration();
        return $this->manualContentForm($processForm);
    }

    public function manualContentForm($processForm)
    {
        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = (int) ($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT'));
        }
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'shopsnotice';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->languages = $languages;
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = true;
        $helper->submit_action = 'submitShopsNoticeSettings';
        $helper->show_toolbar = true;

        $this->fields_form[0]['form'] = array(
            'description' => $this->l('This module is created by 01generator.com For more modules visit our') . '<br>'
            . '<a href="https://addons.prestashop.com/en/2_community-developer?contributor=454322" target="_blank">' . $this->l('PrestaShop Page') . '</a>',
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('Shops Notice General Settings'),
                // 'image' => _MODULE_DIR_ . '/shopsnotice/logo.png',
            ),
            'submit' => array(
                'name' => 'submitShopsNoticeSettings',
                'title' => $this->l('Save '),
                'class' => 'btn btn-default pull-right',
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable/Disable Notice'),
                    'desc' => $this->l(
                        'Choose yes to enable the notice on your shop and no to disable it.'
                    ),
                    'name' => 'shopsnotice_enable',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'shopsnotice_enable_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'shopsnotice_enable_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Notice'),
                    'name' => 'shopsnotice_notice',
                    'required' => false,
                    'autoload_rte' => true,
                    'desc' => $this->l('Write a message for your customers'),
                    'hint' => $this->l('For e.g. "Our responsive time is slow due to sickness. Sorry for the inconvenience and thank you for your understanding."'),
                    'lang' => true,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable/Disable Position Banner'),
                    'desc' => $this->l(
                        'Choose yes to enable the notice to show on hook DisplayBanner.'
                    ),
                    'name' => 'shopsnotice_banner',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'shopsnotice_banner_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'shopsnotice_banner_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable/Disable Position After Body tag'),
                    'desc' => $this->l(
                        'Choose yes to enable the notice to show on hook AfterBodyOpeningTag.'
                    ),
                    'name' => 'shopsnotice_afterbody',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'shopsnotice_afterbody_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'shopsnotice_afterbody_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
            ),
        );
        $languages = Language::getLanguages(false);
        $stored_notices = array();
        foreach ($languages as $language) {
            $stored_notices[$language['id_lang']] = Configuration::get('SHOPSNOTICE_NOTICE_' . $language['id_lang']);
        }
        $helper->fields_value['shopsnotice_notice'] = $stored_notices;

        $helper->fields_value['shopsnotice_enable'] = Configuration::get('SHOPSNOTICE_NOTICE_ENABLE');
        $helper->fields_value['shopsnotice_banner'] = Configuration::get('SHOPSNOTICE_BANNER');
        $helper->fields_value['shopsnotice_afterbody'] = Configuration::get('SHOPSNOTICE_AFTERBODY');

        // Design Options
        $this->fields_form[1]['form'] = array(
            'description' => $this->l('This module is created by 01generator.com For more modules visit our') . '<br>'
            . '<a href="https://addons.prestashop.com/en/2_community-developer?contributor=454322" target="_blank">' . $this->l('PrestaShop Page') . '</a>',
            'tinymce' => true,
            'legend' => array(
                'title' => 'Shops Notice Design Settings',
            ),
            'submit' => array(
                'name' => 'submitShopsNoticeSettings',
                'title' => $this->l('Save '),
                'class' => 'btn btn-default pull-right',
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Enable/Disable Design Settings'),
                    'desc' => $this->l(
                        'Choose yes to enable the settings bellow, otherwise if you have CSS knowledge you can style it your own, class is shopsnotice.'
                    ),
                    'name' => 'shopsnotice_design',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'shopsnotice_design_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ),
                        array(
                            'id' => 'shopsnotice_design_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ),
                    ),
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Background Color'),
                    'name' => 'shopsnotice_bg_color',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Font Color'),
                    'name' => 'shopsnotice_font_color',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Font weight'),
                    'name' => 'shopsnotice_font_weight',
                    'options' => array(
                        'query' => array(
                            0 => array(
                                'id_weight' => 1,
                                'name' => $this->l('Normal'),
                            ),
                            1 => array(
                                'id_weight' => 2,
                                'name' => $this->l('Bold'),
                            ),
                        ),
                        'id' => 'id_weight',
                        'name' => 'name',
                    ),
                    'col' => '4',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Text align'),
                    'name' => 'shopsnotice_text_align',
                    'options' => array(
                        'query' => array(
                            0 => array(
                                'id_align' => 1,
                                'name' => $this->l('Left'),
                            ),
                            1 => array(
                                'id_align' => 2,
                                'name' => $this->l('Center'),
                            ),
                            2 => array(
                                'id_align' => 3,
                                'name' => $this->l('Right'),
                            ),
                        ),
                        'id' => 'id_align',
                        'name' => 'name',
                    ),
                    'col' => '4',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Font Size'),
                    'name' => 'shopsnotice_font_size',
                    'required' => false,
                    'desc' => $this->l('Choose font size, enter just the number, it is measured in px'),
                    'hint' => $this->l('For e.g. 20'),
                    'lang' => false,
                    'col' => '1',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('DIV padding'),
                    'name' => 'shopsnotice_div_padding',
                    'required' => false,
                    'desc' => $this->l('Choose padding, enter just the number, it is measured in px and it is for top,right,bottom and left'),
                    'hint' => $this->l('For e.g. 10'),
                    'lang' => false,
                    'col' => '1',
                ),
            ),
        );

        $helper->fields_value['shopsnotice_design'] = Configuration::get('SHOPSNOTICE_DESIGN');
        $helper->fields_value['shopsnotice_bg_color'] = Configuration::get('SHOPSNOTICE_BG_COLOR');
        $helper->fields_value['shopsnotice_font_color'] = Configuration::get('SHOPSNOTICE_FONT_COLOR');
        $helper->fields_value['shopsnotice_font_weight'] = Configuration::get('SHOPSNOTICE_FONT_WEIGHT');
        $helper->fields_value['shopsnotice_text_align'] = Configuration::get('SHOPSNOTICE_ALIGN');
        $helper->fields_value['shopsnotice_font_size'] = Configuration::get('SHOPSNOTICE_FONT_SIZE');
        $helper->fields_value['shopsnotice_div_padding'] = Configuration::get('SHOPSNOTICE_DIV_PADDING');

        return $processForm . $helper->generateForm($this->fields_form);
    }

    public function processConfiguration()
    {
        if (Tools::isSubmit('submitShopsNoticeSettings')) {
            // --------------------------------- FIELD OPTIONS
            $shopsnotice_enable = Tools::getValue('shopsnotice_enable');
            $shopsnotice_banner = Tools::getValue('shopsnotice_banner');
            $shopsnotice_afterbody = Tools::getValue('shopsnotice_afterbody');
            $shopsnotice_design = Tools::getValue('shopsnotice_design');
            $shopsnotice_bg_color = Tools::getValue('shopsnotice_bg_color');
            $shopsnotice_font_color = Tools::getValue('shopsnotice_font_color');
            $shopsnotice_font_weight = Tools::getValue('shopsnotice_font_weight');
            $shopsnotice_text_align = Tools::getValue('shopsnotice_text_align');
            $shopsnotice_font_size = Tools::getValue('shopsnotice_font_size');
            $shopsnotice_div_padding = Tools::getValue('shopsnotice_div_padding');

            // For future error checking form
            // $form_errors = array();

            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                Configuration::updateValue('SHOPSNOTICE_NOTICE_' . $language['id_lang'], Tools::getValue('shopsnotice_notice_' . $language['id_lang']));
            }

            Configuration::updateValue('SHOPSNOTICE_NOTICE_ENABLE', $shopsnotice_enable);
            Configuration::updateValue('SHOPSNOTICE_BANNER', $shopsnotice_banner);
            Configuration::updateValue('SHOPSNOTICE_AFTERBODY', $shopsnotice_afterbody);
            Configuration::updateValue('SHOPSNOTICE_DESIGN', $shopsnotice_design);
            Configuration::updateValue('SHOPSNOTICE_BG_COLOR', $shopsnotice_bg_color);
            Configuration::updateValue('SHOPSNOTICE_FONT_COLOR', $shopsnotice_font_color);
            Configuration::updateValue('SHOPSNOTICE_FONT_WEIGHT', $shopsnotice_font_weight);
            Configuration::updateValue('SHOPSNOTICE_ALIGN', $shopsnotice_text_align);
            Configuration::updateValue('SHOPSNOTICE_FONT_SIZE', $shopsnotice_font_size);
            Configuration::updateValue('SHOPSNOTICE_DIV_PADDING', $shopsnotice_div_padding);

            // For future error checking form
            // Check for errors and return appropriate message
            // if (!empty($form_errors)) {
            //     return $this->displayError($form_errors);
            // } else {
            //     return $this->displayConfirmation($this->l('Settings updated'));
            // }
            return $this->displayConfirmation($this->l('Settings updated'));
        }
    }

    public function hookDisplayBanner()
    {
        if (Configuration::get('SHOPSNOTICE_BANNER') && Configuration::get('SHOPSNOTICE_NOTICE_ENABLE')) {
            $this->context->smarty->assign(
                array(
                    'shopsnotice_notice' => Configuration::get('SHOPSNOTICE_NOTICE_' . $this->context->language->id),
                    'shopsnotice_design' => Configuration::get('SHOPSNOTICE_DESIGN'),
                    'shopsnotice_bg_color' => Configuration::get('SHOPSNOTICE_BG_COLOR'),
                    'shopsnotice_font_color' => Configuration::get('SHOPSNOTICE_FONT_COLOR'),
                    'shopsnotice_text_align' => Configuration::get('SHOPSNOTICE_ALIGN'),
                    'shopsnotice_font_size' => Configuration::get('SHOPSNOTICE_FONT_SIZE'),
                    'shopsnotice_div_padding' => Configuration::get('SHOPSNOTICE_DIV_PADDING'),
                )
            );
            return $this->display(__FILE__, 'views/templates/front/notice.tpl');
        }
    }

    public function hookDisplayAfterBodyOpeningTag()
    {
        if (Configuration::get('SHOPSNOTICE_AFTERBODY') && Configuration::get('SHOPSNOTICE_NOTICE_ENABLE')) {
            $this->context->smarty->assign(
                array(
                    'shopsnotice_notice' => Configuration::get('SHOPSNOTICE_NOTICE_' . $this->context->language->id),
                    'shopsnotice_design' => Configuration::get('SHOPSNOTICE_DESIGN'),
                    'shopsnotice_bg_color' => Configuration::get('SHOPSNOTICE_BG_COLOR'),
                    'shopsnotice_font_color' => Configuration::get('SHOPSNOTICE_FONT_COLOR'),
                    'shopsnotice_font_weight' => Configuration::get('SHOPSNOTICE_FONT_WEIGHT'),
                    'shopsnotice_text_align' => Configuration::get('SHOPSNOTICE_ALIGN'),
                    'shopsnotice_font_size' => Configuration::get('SHOPSNOTICE_FONT_SIZE'),
                    'shopsnotice_div_padding' => Configuration::get('SHOPSNOTICE_DIV_PADDING'),
                )
            );
            return $this->display(__FILE__, 'views/templates/front/notice.tpl');
        }
    }
}
