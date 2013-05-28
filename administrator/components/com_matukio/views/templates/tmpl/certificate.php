<?php
/**
* Matukio
* @package Joomla!
* @Copyright (C) 2013 - Yves Hoppe - compojoom.com
* @All rights reserved
* @Joomla! is Free Software
* @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 2.2 $
**/

defined('_JEXEC') or die('Restricted access');

JImport( 'joomla.html.editor' );

?>
<div class="col60">
    <div id="mat_certificate">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_MATUKIO_TEMPLATE_CERTIFICATE'); ?></legend>
            <table class="admintable">
                <tr>
                    <td class="key" colspan="2">
                        <label for="value[7]" width="100" title="<?php echo JText::_('COM_MATUKIO_TEMPLATE_CERTIFICATE_CODE'); ?>">
                            <span class="editlinktip hasTip matTip" title="<?php echo JText::_("COM_MATUKIO_TOOLTIP_TEMPLATE_CERTIFICATE_CODE");
                            ?>"><img src="../media/com_matukio/images/info.png" align="right" style="float: right !important; margin: 0 !important;"/></span>
                            <?php echo JText::_('COM_MATUKIO_TEMPLATE_CERTIFICATE_CODE'); ?>:
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <?php
                        $editor = JFactory::getEditor() ;
                        echo $editor->display("value[7]", $this->templates[6]->value, 800, 400, 40, 20, 1);
                        ?>

                        <input type="hidden" name="subject[7]" value="E" />
                        <input type="hidden" name="value_text[7]" value="" />
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</div>
<div class="clr"></div>