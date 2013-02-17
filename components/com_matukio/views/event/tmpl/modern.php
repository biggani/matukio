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
defined( '_JEXEC' ) or die ( 'Restricted access' );

global $mainframe;
$document = JFactory::getDocument();
$database = JFactory::getDBO();
$my = JFactory::getuser();

JHTML::_('behavior.modal');
JHTML::_('stylesheet', 'media/com_matukio/css/modern.css');


// Backward compatibilty
$buchopt = MatukioHelperUtilsEvents::getEventBookableArray($this->art, $this->event, $my->id);

$nametemp = "";
$htxt = 2;

$bezahlt = 0;

if($this->art == 1) {
    $bezahlt = $this->booking->paid;
}

if ($this->art > 2) {
    if ($usrid == 0) {
        $nametemp = MatukioHelperUtilsBasic::getBookedUserList($this->event);
    } else if ($usrid > 0) {
        $nametemp = JFactory::getuser($usrid);
        $nametemp = $nametemp->name;
    }
    if ($nametemp == "") {
        $htxt = 2.2;
    }
}

// Status für Parser festlegen
$parse = "sem_unregistered";
if ($my->id > 0) {
    $parse = "sem_registered";
}
if ($buchopt[0] == 2) {
    $parse = "sem_booked";
    if ($buchopt[2][0]->paid > 0) {
        $parse = "sem_paid";
    }
    if ($buchopt[2][0]->certificated > 0) {
        $parse = "sem_certifcated";
    }
}

?>
<!-- Start Matukio by compojoom.com -->
<?php if (MatukioHelperSettings::getSettings('show_event_title', 1)) : ?>
<div class="componentheading">
    <h2><?php echo $this->event->title; ?></h2>
</div>
<?php endif; ?>

<div id="mat_holder">
    <div id="mat_topmenu">

    </div>
    <div id="mat_infobox">
        <table class="mat_infotable" border="0" width="100%">
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_NUMBER'); ?>
                </td>
                <td>
                    <?php echo $this->event->semnum; ?>
                </td>
            </tr>
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_STATUS'); ?>
                </td>
                <td>
                    <?php
                    // Status anzeigen
                    $htxt = $buchopt[1];
                    if ($this->event->nrbooked < 1) {
                        $htxt = JTEXT::_('COM_MATUKIO_CANNOT_BOOK_ONLINE');
                    }

                    echo $htxt;
                    ?>
                </td>
            </tr>
            <?php if (count($buchopt[2]) > 0) : ?>
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_BOOKING_ID'); ?>
                </td>
                <td>
                    <?php echo MatukioHelperUtilsBooking::getBookingId($buchopt[2][0]->id); ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php
            $htx1 = "";
            $htx2 = "";
            if ($this->event->cancelled == 1) {
                $htx1 = "\n<span class=\"sem_cancelled\">" . JTEXT::_('COM_MATUKIO_CANCELLED') . " </span>(<del>";
                $htx2 = "</del>)";
            }
            ?>
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_BEGIN'); ?>
                </td>
                <td>
                    <?php echo $htx1 . JHTML::_('date', $this->event->begin
                    , MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')) . $htx2; ?>
                </td>
            </tr>
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_END'); ?>
                </td>
                <td>
                    <?php echo $htx1 . JHTML::_('date', $this->event->end
                    , MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')) . $htx2; ?>
                </td>
            </tr>
            <?php if ($this->event->showbooked > 0) { ?>
            <?php if ($this->art == 0 OR ($this->art == 3 AND $usrid == 0)): ?>
                <tr>
                    <td class="key" width="80px">
                        <?php echo JTEXT::_('COM_MATUKIO_CLOSING_DATE'); ?>
                    </td>
                    <td>
                        <?php echo $htx1 . JHTML::_('date', $this->event->booked
                        , MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')) . $htx2; ?>
                    </td>
                </tr>
                <?php else: ?>
                <tr>
                    <td class="key" width="80px">
                        <?php echo JTEXT::_('COM_MATUKIO_DATE_OF_BOOKING'); ?>
                    </td>
                    <td>
                        <?php echo JHTML::_('date', $buchopt[2][0]->bookingdate,
                        MatukioHelperSettings::getSettings('date_format_small', 'd-m-Y, H:i')); ?>
                    </td>
                </tr>
                <?php endif; ?>
            <?php } ?>
            <?php if ($this->event->teacher != "") : ?>
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_TUTOR'); ?>
                </td>
                <td>
                    <?php echo $this->event->teacher; ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($this->event->target != "") : ?>
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_TARGET_GROUP'); ?>
                </td>
                <td>
                    <?php echo $this->event->target; ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($this->event->webinar != 1) : ?>
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_CITY'); ?>
                </td>
                <td>
                    <?php echo $this->event->place; ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($this->event->nrbooked > 0 AND MatukioHelperSettings::getSettings('event_showinfoline', 1) == 1) : ?>
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_BOOKABLE'); ?>
                </td>
                <td>
                    <?php echo $buchopt[4]; ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($this->event->fees > 0) : ?>
            <tr>
                <td class="key" width="80px">
                    <?php echo JTEXT::_('COM_MATUKIO_FEES'); ?>
                </td>
                <td>
                    <?php
                    $tmp = MatukioHelperSettings::getSettings('currency_symbol', '$') . " "
                        . MatukioHelperUtilsEvents::getFormatedCurrency($this->event->fees);

                    if (MatukioHelperSettings::getSettings('currency_symbol', '$') == "€") {
                        // Stupid hack.. should be changed sometime to a setting
                        $tmp = MatukioHelperUtilsEvents::getFormatedCurrency($this->event->fees) . " "
                            . MatukioHelperSettings::getSettings('currency_symbol', '$');
                    }

                    if ($buchopt[0] == 2) {
                        if ($buchopt[2][0]->paid == 1) {
                            $tmp .= " - " . JTEXT::_('COM_MATUKIO_PAID');
                        }
                    }

                    echo $tmp . " " . JTEXT::_('COM_MATUKIO_PRO_PERSON');;

                    ?>
                </td>
            </tr>
            <?php endif; ?>
            <?php
            // Files:

            $datfeld = MatukioHelperUtilsEvents::getEventFileArray($this->event);
            $htxt = array();

                for ($i = 0; $i < count($datfeld[0]); $i++) {
                    if ($datfeld[0][$i] != "" AND ($datfeld[2][$i] == 0 OR ($my->id > 0 AND $datfeld[2][$i] == 1) OR ($buchopt[0] == 2
                        AND $datfeld[2][$i] == 2) OR ($buchopt[2][0]->paid == 1 AND $datfeld[2][$i] == 3)))
                    {

                        // Still a joke
                        $filelink = JRoute::_("index.php?option=com_matukio&view=matukio&task=downloadfile&a6d5dgdee4cu7eho8e7fc6ed4e76z="
                            . sha1(md5($datfeld[0][$i])) . $this->event->id);

                        $htxt[] = "<tr>
                                        <td style=\"white-space:nowrap;vertical-align:top;\">
                                            <span style=\"background-image:url(" . MatukioHelperUtilsBasic::getComponentImagePath()
                                                                                 . "0002.png);background-repeat:no-repeat;
                                                    background-position:2px;padding-left:18px;vertical-align:middle;\">
                                            <a href=\"" . $filelink . "\" target=\"_blank\">" . $datfeld[0][$i]
                                            . "</a>
                                            </span>
                                        <br />"
                                        . $datfeld[1][$i]
                                        . "</td>
                                   </tr>";

                    }
                }

            if(count($htxt) > 0) {

                echo "<tr>";
                echo "<td colspan='2'>";
                echo JTEXT::_('COM_MATUKIO_FILES');
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<td colspan='2'>";
                echo '<table width="100%" border="0">';
                echo implode($htxt);
                echo "</table>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <?php if($this->event->webinar != 1 && $this->event->gmaploc != "") : ?>
            <div id="mat_map">
                <?php
                    Jhtml::_('behavior.framework');
                    $api = 'http://maps.googleapis.com/maps/api/js?sensor=false';
                    $document = JFactory::getDocument();
                    $document->addScript($api);
                    $script = "window.addEvent('domready', function() {

                    geocoder = new google.maps.Geocoder();
                    var myOptions = {
                        zoom:8,
                        mapTypeId:google.maps.MapTypeId.ROADMAP
                    };
                    var map = new google.maps.Map(document.getElementById('map_canvas'),
                              myOptions);
                    var address = '" . preg_replace("#\n|\r#", ' ', str_replace('<br />', ',', $this->event->gmaploc)) . "';
                    geocoder.geocode( { 'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });

                    var infowindow = new google.maps.InfoWindow({
                        content: address
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.open(map,marker);
                    });

                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }
                    });

                    });";

                    $document->addScriptDeclaration($script);
                ?>
                <a title="<?php JTEXT::_('COM_MATUKIO_MAP'); ?>" class="modal" href="<?php echo
                 JRoute::_('index.php?option=com_matukio&view=map&tmpl=component&event_id=' . $this->event->id);
                ?>" rel="{handler: 'iframe', size: {x: 500, y: 350}}">
                <div id="map_canvas" style="width: 100%;height: 200px; border-radius: 0 0 0 15px" ></div></a>
            </div>
        <?php endif; ?>
    </div>
    <div id="mat_description">
        <div id="mat_description_inner">
            <?php if (MatukioHelperSettings::getSettings('social_media', 1)) : ?>
            <div id="mat_social">

                <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
                </script>

                <div class="twitter-btn">
                    <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en">Tweet</a>
                    <script>
                        !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);
                            js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}
                            (document,"script","twitter-wjs");
                    </script>
                </div>

                <div class="fb-like" data-href="<?php echo JURI::current(); ?>" data-send="false" data-layout="button_count"
                     data-width="100" data-show-faces="false" data-action="recommend"></div>

                <script type="text/javascript" src="http://apis.google.com/js/plusone.js">
                    {lang: 'de', parsetags: 'explicit'}</script><g:plusone href="<?php echo JURI::current(); ?>" size="medium">
            </g:plusone><script type="text/javascript">gapi.plusone.go();
            </script>
            </div>
            <?php endif; ?>

            <?php
            // Show description
            if ($this->event->description != "") {
                echo MatukioHelperUtilsBasic::parseOutput(JHtml::_('content.prepare', $this->event->description), $parse);
            }
            ?>
        </div>
    </div>
    <div id="mat_bottom">
        <?php
        // Kontaktformular
        if(MatukioHelperSettings::getSettings("sendmail_contact", 1)){
            echo MatukioHelperUtilsEvents::getEmailWindow(MatukioHelperUtilsBasic::getComponentImagePath(), $this->event->id, 2, "modern");
        }

        // Kalender
        if (MatukioHelperSettings::getSettings('frontend_usericsdownload', 1) > 0) {


//            $icslink = JRoute::_("index.php?option=com_matukio&view=ics&format=raw&cid=" . $this->event->id);
            $config = JFactory::getConfig();


            $_suffix = $config->get( 'config.sef_suffix' );

            //$_suffix = JFactory::getApplication()->getCfg( 'config.sef_suffix' );

            if ( $_suffix == 0) { // no .html suffix
                $icslink = JRoute::_("index.php?option=com_matukio&tmpl=component&view=ics&format=raw&cid=" . $this->event->id);
            } else {
                $icslink = JRoute::_("index.php?option=com_matukio&tmpl=component&view=ics&cid=" . $this->event->id) . "?format=raw";
            }


            echo " <a title=\"" . JTEXT::_('COM_MATUKIO_DOWNLOAD_CALENDER_FILE') . "\" href=\"" . $icslink . "\" target=\"_BLANK\">"
                . "<span class=\"mat_button\"><img src=\""
                . MatukioHelperUtilsBasic::getComponentImagePath() . "3316.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
                . JTEXT::_('COM_MATUKIO_DOWNLOAD_CALENDER_FILE') . "</span></a> ";
        }

        // Print
        echo MatukioHelperUtilsEvents::getPrintWindow(2, $this->event->id, '', 'b', "modern");

        // Book
        if ((($buchopt[0] > 2 AND $this->art == 0) OR ($this->art == 3 AND $usrid == 0 AND ($nametemp != "" OR
            MatukioHelperSettings::getSettings('booking_unregistered', 1) == 1)))
            AND $this->event->cancelled == 0 AND $this->event->nrbooked > 0) {

            $bookinglink = JRoute::_("index.php?option=com_matukio&view=bookevent&cid=" . $this->event->id . ":"
                                        . JFilterOutput::stringURLSafe($this->event->title));

            echo " <a title=\"" . JTEXT::_('COM_MATUKIO_BOOK') . "\" href=\"" . $bookinglink
                . "\"><span class=\"mat_button mat_book\" type=\"button\"><img src=\""
                . MatukioHelperUtilsBasic::getComponentImagePath()
                . "1116.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
                . JTEXT::_('COM_MATUKIO_BOOK') . "</span></a>";
        }

    // Aenderungen speichern Veranstalter  , not really implemented here
    if ($this->art == 3 And $usrid != 0 AND ($this->event->nrbooked > 1 OR $zfleer == 0)) {
        echo ' <input type="submit" class="button" value="' . JTEXT::_('COM_MATUKIO_SAVE_CHANGES') . '">';
    }

    // Aenderungen speichern Benutzer falls noch nicht gezahlt
    if ($this->art == 1 AND strtotime($this->event->booked) - time() >= (MatukioHelperSettings::getSettings('booking_stornotage', 1)
        * 24 * 60 * 60) AND $bezahlt == 0) {
        if($this->user->id > 0) {
            $unbookinglink = JRoute::_("index.php?option=com_matukio&view=bookevent&task=cancelBooking&cid=" . $this->event->id);

            if (MatukioHelperSettings::getSettings('booking_stornotage', 1) > -1) {
               echo " <a border=\"0\" href=\"" . $unbookinglink
                    . "\" ><span class=\"mat_button mat_book\" type=\"button\"><img src=\""
                    . MatukioHelperUtilsBasic::getComponentImagePath() . "1532.png\" border=\"0\" align=\"absmiddle\">&nbsp;"
                    . JTEXT::_('COM_MATUKIO_BOOKING_CANCELLED') . "</span></a>";
            }
        }
    }
    ?>
    </div>
</div>

<?php
echo MatukioHelperUtilsBasic::getCopyright();
?>
<!-- End Matukio by compojoom.com -->