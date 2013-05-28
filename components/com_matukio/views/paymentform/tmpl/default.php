<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.1.0 $
 **/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');
JHTML::_('stylesheet', 'media/com_matukio/css/modern.css');
?>
<!-- Start Matukio by compojoom.com -->
<script type="text/javascript">
window.addEvent('domready', function () {

});
</script>
<div id="mat_holder">
<?php
$pg_plugin = $this->booking->payment_method;

$dispatcher = JDispatcher::getInstance();

$vars = new stdClass;
$vars->orderid = $this->booking->id;
$vars->order_id = $this->booking->id;
$vars->user_firstname = $this->booking->name;
$vars->user_id = $this->booking->userid;
$vars->user_email = $this->booking->email;

if(empty($this->booking->email) && $this->booking->userid > 0) {
    $user = JFactory::getUser($this->booking->userid);
    $vars->user_email = $user->email;
    $vars->user_firstname = $user->name;
}

$vars->item_name = $this->event->title;
$vars->return = JRoute::_(JURI::root() . "index.php?option=com_matukio&view=ppayment&pg_plugin=" . $pg_plugin . "&uuid=" . $this->uuid);
$vars->cancel_return = JRoute::_(JURI::root() . "index.php?option=com_matukio&view=ppayment&task=cancelPayment&pg_plugin=" . $pg_plugin . "&uuid=" . $this->uuid);
$vars->notify_url = JRoute::_(JURI::root() . "index.php?option=com_matukio&view=ppayment&task=status&pg_plugin=" . $pg_plugin . "&uuid=" . $this->uuid);

$vars->url = JRoute::_(JURI::root() . "index.php?option=com_matukio&view=ppayment&task=status&pg_plugin=" . $pg_plugin . "&uuid=" . $this->uuid); // Not documented..

$vars->currency_code = MatukioHelperSettings::getSettings("paypal_currency", 'EUR');
$vars->amount = $this->booking->payment_brutto;


var_dump($vars);


// Import the right plugin here!
JPluginHelper::importPlugin('payment', $pg_plugin);

if($pg_plugin=='paypal')
{
    // Set the paypal address according to the settings
    $vars->business = MatukioHelperSettings::getSettings("paypal_address", 'paypal@compjoom.com');

    $vars->cmd='_xclick';
}

//JPluginHelper::importPlugin("payment");
$html = $dispatcher->trigger('onTP_GetHTML', array($vars));

if($pg_plugin == 'paypal') {
?>
    <script type="text/javascript">
        window.addEvent('domready', function () {
            window.setTimeout(function() {document.id('paymentForm').submit()}, 10000);
        });
    </script>
    <?php
    $t1 = JText::_('COM_MATUKIO_LEVEL_REDIRECTING_HEADER');
    $t2 = JText::_('COM_MATUKIO_LEVEL_REDIRECTING_BODY');
    ?>
    <h3><?php echo JText::_('COM_MATUKIO_LEVEL_REDIRECTING_HEADER') ?></h3>
    <p><?php echo JText::_('COM_MATUKIO_LEVEL_REDIRECTING_BODY') ?></p>
<p align="center">
<?php
}

echo $html[0];

echo "<br /><br />";
echo MatukioHelperUtilsBasic::getCopyright();
?>
</div>
<!-- End Matukio by compojoom.com -->