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



    echo MatukioHelperUtilsBasic::getCopyright();
?>
</div>
<!-- End Matukio by compojoom.com -->