<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2013 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.2.0 $
 **/
defined('_JEXEC') or die('Restricted access');

JHTML::_('stylesheet', 'media/mod_calendar_upcoming/tmpl/default/css/tmpl.css');

$catids = $params->get("catid", 0);

JHTML::_('stylesheet', 'media/com_matukio/css/modern.css');
JHTML::_('stylesheet', 'media/com_matukio/css/calendar.css');
JHTML::_('stylesheet', 'media/com_matukio/css/mooECal.css');
//JHTML::_('stylesheet', 'media/com_matukio/css/mooECalLarge.css');
JHTML::_('stylesheet', 'media/com_matukio/css/mooECalSmall.css');
JHTML::_('script', 'media/com_matukio/js/mooECal.js');
JHTML::_('script', 'media/com_matukio/js/mecPHPPlugin.js');

?>
<script type="text/javascript">
    window.addEvent('domready', function () {
        new Calendar({
            calContainer:'mmat_calendar_holder_<?php echo $module->id; ?>',
            newDate:'<?php echo Date("m/d/Y");//8/5/2009?>',
            feedPlugin:'new mecPHPPlugin()',
            dayNames: ['<?php echo JText::_("COM_MATUKIO_SUNDAY_SHORT");?>', '<?php echo JText::_("COM_MATUKIO_MONDAY_SHORT");?>', '<?php echo JText::_("COM_MATUKIO_TUESDAY_SHORT");?>',
                '<?php echo JText::_("COM_MATUKIO_WEDNESDAY_SHORT");?>', '<?php echo JText::_("COM_MATUKIO_THURSDAY_SHORT");?>', '<?php echo JText::_("COM_MATUKIO_FRIDAY_SHORT");?>',
                '<?php echo JText::_("COM_MATUKIO_SATURDAY_SHORT");?>'],
            monthNames: ['<?php echo JText::_("COM_MATUKIO_JANUARY");?>', '<?php echo JText::_("COM_MATUKIO_FEBRUARY");?>', '<?php echo JText::_("COM_MATUKIO_MARCH");?>',
                '<?php echo JText::_("COM_MATUKIO_APRIL");?>',
                '<?php echo JText::_("COM_MATUKIO_MAY");?>', '<?php echo JText::_("COM_MATUKIO_JUNE");?>', '<?php echo JText::_("COM_MATUKIO_JULY");?>',
                '<?php echo JText::_("COM_MATUKIO_AUGUST");?>', '<?php echo JText::_("COM_MATUKIO_SEPTEMBER");?>', '<?php echo JText::_("COM_MATUKIO_OCTOBER");?>',
                '<?php echo JText::_("COM_MATUKIO_NOVEMBER");?>', '<?php echo JText::_("COM_MATUKIO_DECEMBER");?>'],
            dateFormat: '<?php echo $params->get('dateFormat', '%Y-%m-%d'); ?>',
            timeFormat: '<?php echo $params->get('timeFormat', '%I:%M'); ?>',
            dayText: '<?php echo JText::_("COM_MATUKIO_DAY");?>',
            weekText: '<?php echo JText::_("COM_MATUKIO_WEEK");?>',
            monthText: '<?php echo JText::_("COM_MATUKIO_MONTH");?>',
            timeTwelveHourFormat: '<?php echo $params->get('timeTwelveHourFormat', false); ?>'
        });
    });
</script>

<div id="mmat_calendar_<?php echo $module->id; ?>" class="mmat_calendar">
    <div id="mmat_calendar_holder_<?php echo $module->id; ?>">

    </div>
</div>


