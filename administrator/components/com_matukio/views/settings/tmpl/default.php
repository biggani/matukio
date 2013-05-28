<?php

/**
 * CAdvancedSlideshow
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
jimport( 'joomla.html.pane' );

$doc = JFactory::getDocument();
$doc->addStyleSheet( '../media/com_matukio/backend/css/settings.css' );
$doc->addScript( '../media/com_matukio/backend/js/Form.Check.js' );
$doc->addScript( '../media/com_matukio/backend/js/Form.CheckGroup.js' );
$doc->addScript( '../media/com_matukio/backend/js/Form.Dropdown.js' );
$doc->addScript( '../media/com_matukio/backend/js/Form.Radio.js' );
$doc->addScript( '../media/com_matukio/backend/js/Form.RadioGroup.js' );
$doc->addScript( '../media/com_matukio/backend/js/Form.SelectOption.js' );

?>

<form action="<?php JRoute::_("index.php?option=com_matukio&view=settings") ?>" method="post" name="adminForm" id="adminForm">
<div class="mat_settings_holder mat_settings">
<?php
$params['useCookie'] = true;
$params['startOffset'] = 0;

$group = 'tabs';

echo JHtml::_('tabs.start', $group, $params);
echo JHtml::_('tabs.panel', JText::_( 'COM_MATUKIO_BASIC' ), 'basic' );
?>

<div class="col60">
    <div id="matsettings">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_BASIC'); ?></legend>
            <table class="admintable">
                <tr>
                    <td colspan="2"><?php echo JText::_("COM_MATUKIO_SETTINGS_BASIC_INTRO"); ?><br /><br /></td>
                </tr>
                <?php
                foreach ($this->items_basic as $value) {

                    echo '<tr>';
                    echo '<td class="key" width="300px">';
                    echo '<label for="' . $value->title . '" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                    echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                    echo '</label>';
                    echo '</td>';

                    echo '<td>';

                    echo MatukioHelperSettings::getSettingField($value);

                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </fieldset>
    </div>
</div>
<div class="clr"></div>
<?php
echo JHtml::_('tabs.panel', JText::_('COM_MATUKIO_LAYOUT'), 'layout');
?>
<div class="col60">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_MATUKIO_LAYOUT'); ?></legend>
        <table class="admintable">
            <tr>
                <td colspan="2"><?php echo JText::_("COM_MATUKIO_SETTINGS_LAYOUT_INTRO"); ?><br /><br /></td>
            </tr>
            <?php
            foreach ($this->items_layout as $value) {

                echo '<tr>';
                echo '<td class="key" width="300px">';
                echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                echo '</label>';
                echo '</td>';

                echo '<td>';

                echo MatukioHelperSettings::getSettingField($value);

                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </fieldset>
</div>
<div class="clr"></div>
<?php
echo JHtml::_('tabs.panel', JText::_('COM_MATUKIO_MODERN_TEMPLATE'), 'modernlayout');
?>
<div class="col60">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_MATUKIO_MODERN_TEMPLATE'); ?></legend>

        <table class="admintable">
            <tr>
                <td colspan="2"><?php echo JText::_("COM_MATUKIO_SETTINGS_MODERN_TEMPLATE_INTRO"); ?><br /><br /></td>
            </tr>
            <?php
            foreach ($this->items_modernlayout as $value) {

                echo '<tr>';
                echo '<td class="key" width="300px">';
                echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                echo '</label>';
                echo '</td>';

                echo '<td>';

                echo MatukioHelperSettings::getSettingField($value);

                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </fieldset>
</div>
<div class="clr"></div>
<?php
echo JHtml::_('tabs.panel', JText::_( 'COM_MATUKIO_PAYMENT' ), 'payment' );
?>
<div class="col60">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_MATUKIO_PAYMENT'); ?></legend>

        <table class="admintable">
            <tr>
                <td colspan="2"><?php echo JText::_("COM_MATUKIO_SETTINGS_PAYMENT_INTRO"); ?><br /><br /></td>
            </tr>
            <?php
            foreach ($this->items_payment as $value) {

                echo '<tr>';
                echo '<td class="key" width="300px">';
                echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                echo '</label>';
                echo '</td>';

                echo '<td>';

                echo MatukioHelperSettings::getSettingField($value);

                echo '</td>';
                echo '</tr>';

            }
            ?>
        </table>
    </fieldset>
</div>
<div class="clr"></div>
<?php

    echo JHtml::_('tabs.panel', JText::_( 'COM_MATUKIO_ADVANCED' ), 'advanced' );

?>
<div class="col60">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_MATUKIO_ADVANCED'); ?></legend>

        <table class="admintable">
            <tr>
                <td colspan="2"><?php echo JText::_("COM_MATUKIO_SETTINGS_ADVANCED_INTRO"); ?><br /><br /></td>
            </tr>
            <?php
            foreach ($this->items_advanced as $value) {

                echo '<tr>';
                echo '<td class="key" width="300px">';
                echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                echo '</label>';
                echo '</td>';

                echo '<td>';

                echo MatukioHelperSettings::getSettingField($value);

                echo '</td>';
                echo '</tr>';

            }
            ?>
        </table>
    </fieldset>
</div>
<div class="clr"></div>
<?php

echo JHtml::_('tabs.panel', JText::_( 'COM_MATUKIO_SECURITY' ), 'security' );

?>
<div class="col60">
    <fieldset class="adminform">
        <legend><?php echo JText::_('COM_MATUKIO_SECURITY'); ?></legend>

        <table class="admintable">
            <tr>
                <td colspan="2"><?php echo JText::_("COM_MATUKIO_SETTINGS_SECURITY_INTRO"); ?><br /><br /></td>
            </tr>
            <?php
            foreach ($this->items_security as $value) {

                echo '<tr>';
                echo '<td class="key" width="300px">';
                echo '<label for="' . $value->title . '" width="100" title="' . JText::_('COM_MATUKIO_' . strtoupper($value->title) . '_DESC') . '">';
                echo JText::_('COM_MATUKIO_' . strtoupper($value->title));
                echo '</label>';
                echo '</td>';

                echo '<td>';

                echo MatukioHelperSettings::getSettingField($value);

                echo '</td>';
                echo '</tr>';
            }
            ?>
        </table>
    </fieldset>
</div>
<div class="clr"></div>
<?php

echo JHtml::_('tabs.end');
?>

<input type="hidden" name="option" value="com_matukio" />
<input type="hidden" name="view" value="settings" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="settings" />
    
<?php echo JHTML::_( 'form.token' ); ?>
</div>
</form>

