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
JHTML::_('stylesheet', 'matukio.css', 'media/com_matukio/css/');
JHTML::_('stylesheet', 'upcoming.css', 'media/com_matukio/css/');

//$usermail = $this->user->email;
?>
<!-- Start Matukio by compojoom.com -->
<script type="text/javascript">
window.addEvent('domready', function () {

});
</script>
<div class="componentheading">
    <h2><?php echo JText::_($this->title); ?></h2>
</div>


<div id="mat_holder">
<?php
    // Starting event output
    for($i = 0; $i < count($this->events); $i++)
    {
        $event = $this->events[$i];

        $buchopt = MatukioHelperUtilsEvents::getEventBookableArray(0, $event, $this->user->id);

        // Link
        $eventid_l = $event->id.':'.JFilterOutput::stringURLSafe($event->title);
        $catid_l = $event->catid.':'.JFilterOutput::stringURLSafe(MatukioHelperCategories::getCategoryAlias($event->catid));

        $link = JRoute::_(MatukioHelperRoute::getEventRoute($eventid_l, $catid_l), false);

        // Event image   -- TODO Add / Check for category image

        $linksbild = MatukioHelperUtilsBasic::getComponentImagePath() . "2601.png";

        if($event->image!="" AND  MatukioHelperSettings::getSettings('event_image', 1)==1) {
            $linksbild = MatukioHelperUtilsBasic::getEventImagePath(1).$event->image;
        }

        // Starting
?>
        <div class="mat_single_event">
            <div class="mat_event_header">
                <div class="mat_event_header_inner">
                    <div class="mat_event_header_line">
                        <div class="mat_event_image">
                            <img src="<?php echo $linksbild ?>" alt="<?php echo $event->title; ?>" align="absmiddle" />
                        </div>
                        <div class="mat_event_title">
                            <h2><a href="<?php echo $link; ?>" title="<?php echo $event->title; ?>"><?php echo $event->title; ?></a></h2>
                        </div>
                    </div>
                    <div class="mat_event_location">
                        <?php
                            $begin = JHTML::_('date', $event->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
                            if($event->webinar == 1) {
                                $locimg = MatukioHelperUtilsBasic::getComponentImagePath() . "webinar.png";
                                echo '<h3><img src="' . $locimg . '" title="'. JText::_("COM_MATUKIO_WEBINAR") .'" style="width: 22px; vertical-align:middle" /> '
                                    . $event->place . " " . JText::_("COM_MATUKIO_AT") . " " . $begin . '</h3>';
                            } else {
                                // TODO add map link
                               $locimg = MatukioHelperUtilsBasic::getComponentImagePath() . "home.png";
                               echo '<h3><img src="' . $locimg . '" title="'. JText::_("COM_MATUKIO_FIELDS_CITY") .'" style="width: 22px; vertical-align:middle" /> '
                                   . $event->place  . " " . JText::_("COM_MATUKIO_AT") . " " . $begin . '</h3>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="mat_event_description">
                <?php echo $event->shortdesc; ?>
            </div>
            <div class="mat_event_footer">
                <div class="mat_event_footer_inner">
                    <div class="mat_event_infoline">
                         <?php
                             //echo JTEXT::_('COM_MATUKIO_CATEGORY') . ': '. JTEXT::_($event->category);
                             $catlink = JRoute::_("index.php?option=com_matukio&view=eventlist&art=0&catid=" . $event->catid . "");   // TODO Fix
                             echo '<a href="' . $catlink . '">' . JTEXT::_($event->category) . '</a>';

                            // Infoline
                            $gebucht = MatukioHelperUtilsEvents::calculateBookedPlaces($event);
                            if (MatukioHelperSettings::getSettings('event_showinfoline', 1) == 1) {
                                echo " | ";

                                // Veranstaltungsnummer anzeigen
                                if ($event->semnum != "") {
                                    echo JTEXT::_('COM_MATUKIO_NUMBER') . ": " . $event->semnum . " | ";;
                                }

                                // JTEXT::_('COM_MATUKIO_BOOKED_PLACES') . ": " . $gebucht->booked . " | " . // Booked places, too many
                                echo  JTEXT::_('COM_MATUKIO_BOOKABLE') . ": " . $buchopt[4];
                                // . " | " . JTEXT::_('COM_MATUKIO_HITS') . ": " . $event->hits
                            }

                            // Seminarleiter anzeigen
                            if ($event->teacher != "") {
                                echo " | " . $this->event->teacher;
                            }

                            // Fees
                            if($event->fees > 0) {
                                echo " | ";

                                $gebuehr = MatukioHelperUtilsEvents::getFormatedCurrency($event->fees);
                                $currency = MatukioHelperSettings::getSettings('currency_symbol', '$');

                                if($currency == '€'){
                                    echo JTEXT::_('COM_MATUKIO_FEES') . ': '. $gebuehr . " " . $currency;
                                } else {
                                    echo JTEXT::_('COM_MATUKIO_FEES') . ': '.$currency . " " . $gebuehr;
                                }
                            }
                         ?>
                    </div>
                    <div class="mat_event_footer_buttons" align="right">
                    <?php
                        // Detail Link
                        echo " <a title=\"" . $event->title . "\" href=\"" . $link . "\">"
                        . "<span class=\"mat_button\"><img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath()
                        . "0012.png\" border=\"0\" align=\"absmiddle\">&nbsp;" . JTEXT::_('COM_MATUKIO_EVENT_DETAILS') . "</span></a> ";

                        // Booking Link
                        if(($this->user->id != 0 || (MatukioHelperSettings::getSettings('booking_unregistered', 1) == 1))
                            && MatukioHelperSettings::getSettings('oldbookingform', 0) != 1) {

                            $bookinglink = JRoute::_("index.php?option=com_matukio&view=bookevent&cid=" . $event->id . ":"
                                . JFilterOutput::stringURLSafe($event->title));

                            echo " <a title=\"" . JTEXT::_('COM_MATUKIO_BOOK') . "\" href=\"" . $bookinglink
                                . "\"><span class=\"mat_button mat_book\" type=\"button\"><img src=\""
                                . MatukioHelperUtilsBasic::getComponentImagePath()
                                . "1116.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
                                . JTEXT::_('COM_MATUKIO_BOOK') . "</span></a>";
                        }

                    ?>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
?>

<?php
echo MatukioHelperUtilsBasic::getCopyright();
?>
</div>
<!-- End Matukio by compojoom.com -->