{*
*   The MIT License (MIT)
*
*   Copyright (c) 2020 Sarafoudis Nikolaos for 01generator.com
*
*   Permission is hereby granted, free of charge, to any person obtaining a copy
*   of this software and associated documentation files (the "Software"), to deal
*   in the Software without restriction, including without limitation the rights
*   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
*   copies of the Software, and to permit persons to whom the Software is
*   furnished to do so, subject to the following conditions:
*
*   The above copyright notice and this permission notice shall be included in
*   all copies or substantial portions of the Software.
*
*   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
*   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
*   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
*   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
*   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
*   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
*   THE SOFTWARE.
*}
{if $shopsnotice_design eq 1}
<style type="text/css">
    {literal}
    .shopsnotice{
        background-color: {/literal}{$shopsnotice_bg_color|escape:'html'}{literal};
        color: {/literal}{$shopsnotice_font_color|escape:'html'}{literal};
        {/literal}{if $shopsnotice_font_weight eq 1}{literal}
        font-weight: 400;
        {/literal}{elseif $shopsnotice_font_weight eq 2}{literal}
        font-weight: 600;
        {/literal}{/if}{literal}
        text-align: {/literal}{if $shopsnotice_text_align eq 1}left{elseif $shopsnotice_text_align eq 2}center{elseif $shopsnotice_text_align eq 4}right{/if}{literal};
        font-size: {/literal}{$shopsnotice_font_size|escape:'html'}{literal}px;
        padding: {/literal}{$shopsnotice_div_padding|escape:'html'}{literal}px;
    }
    {/literal}
</style>
{/if}
<div class="container-fluid shopsnotice">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            {$shopsnotice_notice|escape:'html'}
        </div>
    </div>
</div>
