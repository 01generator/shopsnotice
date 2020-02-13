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

class AdminShopsNoticeController extends ModuleAdminController
{
/* Conctructor of the controller */
    public function __construct()
    {
        // SQL select
        $this->bootstrap = true;
        $this->className = 'shopsnotice';
        if (version_compare(_PS_VERSION_, '1.7.0.0 ', '<')) {
            $this->meta_title = $this->l('Shops Notice Settings');
        } else {
            $this->meta_title = Context::getContext()->getTranslator()->trans('Shops Notice Settings');
        }
        $this->toolbar_title[] = $this->meta_title;
        $this->list_no_link = true;
        // Call of the parent constructor method
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules') . '&configure=' . Tools::safeOutput('shopsnotice'));
    }
}
