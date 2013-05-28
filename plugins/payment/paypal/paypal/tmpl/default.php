<?php
/**
 * @package Social Ads
 * @copyright Copyright (C) 2009 -2010 Techjoomla, Tekdi Web Solutions . All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */
defined('_JEXEC') or die('Restricted access');
JLoader::register('MatukioHelperSettings', JPATH_COMPONENT_ADMINISTRATOR . '/helper/settings.php');

?>

<div class="akeeba-bootstrap">
	<form action="<?php echo $vars->action_url ?>" class="form-horizontal" method="post" id="paymentForm">
		<input type="hidden" name="business" value="<?php echo $vars->business; ?>" />
		<input type="hidden" name="custom" value="<?php echo $vars->order_id; ?>" />
		<input type="hidden" name="item_name" value="<?php echo $vars->item_name; ?>" />
		<input type="hidden" name="return" value="<?php echo $vars->return; ?>" />
		<input type="hidden" name="cancel_return" value="<?php echo $vars->cancel_return; ?>" />
		<input type="hidden" name="notify_url" value="<?php echo $vars->notify_url; ?>" />
		<input type="hidden" name="currency_code" value="<?php echo $vars->currency_code; ?>" />
		<input type="hidden" name="no_note" value="1" />
		<input type="hidden" name="rm" value="2" />
		<input type="hidden" name="amount" value="<?php echo $vars->amount; ?>" />
		<input type="hidden" name="cmd" value="<?php echo $vars->cmd; ?>" />
        <input type="hidden" name="no_shipping" value="1" />

        <?php if($cbt = MatukioHelperSettings::getSettings('cbt','')): // Text for return to merchant button?>
        <input type="hidden" name="cbt" value="<?php echo $cbt ?>" />
        <?php endif; ?>
        <?php if($cpp_header_image = MatukioHelperSettings::getSettings('cpp_header_image','')): ?>
        <input type="hidden" name="cpp_header_image" value="<?php echo $cpp_header_image?>" />
        <?php endif; ?>
        <?php if($cpp_headerback_color = MatukioHelperSettings::getSettings('cpp_headerback_color','')): ?>
        <input type="hidden" name="cpp_headerback_color" value="<?php echo $cpp_headerback_color?>" />
        <?php endif; ?>
        <?php if($cpp_headerborder_color = MatukioHelperSettings::getSettings('cpp_headerborder_color','')): ?>
        <input type="hidden" name="cpp_headerborder_color" value="<?php echo $cpp_headerborder_color?>" />
        <?php endif; ?>

		<div class="form-actions">
			<input type="image" class="mat_button" src="https://www.paypal.com/en_US/i/btn/x-click-but02.gif" border="0"
                   value="<?php echo JText::_('SUBMIT'); ?>" alt="Make payments with PayPal - it's fast, free and secure!" id="paypalsubmit" />
            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
		</div>
	</form>
</div>