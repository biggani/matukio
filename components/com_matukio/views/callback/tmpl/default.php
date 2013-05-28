<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 **/
defined('_JEXEC') or die();
?>
<script type="text/javascript">
    window.addEvent('domready', function () {
        window.setTimeout(function() {window.location = "<?php echo JURI::root(); ?>"}, 10000);
    });
</script>
<?php
$t1 = JText::_('COM_MATUKIO_THANK_YOU');
$t2 = JText::_('COM_MATUKIO_LEVEL_REDIRECTING_BODY');
?>
<h3><?php echo JText::_('COM_MATUKIO_THANK_YOU') ?></h3>
<p><?php echo JText::_('COM_MATUKIO_THANK_YOU_TEXT') ?></p>
<p>
</p>