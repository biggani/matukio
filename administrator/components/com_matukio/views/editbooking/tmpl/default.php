<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 2.0.0 Stable $
 **/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.mootools');
JHTML::_('behavior.tooltip');

?>
<div id="matukio" class="matukio">
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form" enctype="multipart/form-data">
<fieldset class="adminform">
    <legend><?php echo JText::_('COM_MATUKIO_EDIT_BOOKING'); ?></legend>
    <table>
        <tr>
            <td width="150" align="left" class="key">
                <?php echo JText::_('COM_MATUKIO_BOOKING_ID'); ?>:
            </td>
            <td>
                <?php echo MatukioHelperUtilsBooking::getBookingId($this->booking->id) . " (" . $this->booking->id . ")"; ?>
            </td>
        </tr>
        <tr>
            <td align="left" class="key">
                <?php echo JText::_('COM_MATUKIO_BOOKING_DATE'); ?>:
            </td>
            <td>
                <?php echo $this->booking->bookingdate; ?>
            </td>
        </tr>
        <?php
        if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {
            // TODO
            ?>
            <tr>
                <td width="200" align="left" class="key">
                    <?php echo JText::_('COM_MATUKIO_BOOKING_NAME'); ?>:
                </td>
                <td>
                    <?php echo $this->booking->name; ?>
                </td>
            </tr>
            <tr>
                <td width="200" align="left" class="key">
                    <?php echo JText::_('COM_MATUKIO_BOOKING_EMAIL'); ?>:
                </td>
                <td>
                    <?php echo $this->booking->email; ?>
                </td>
            </tr>
            <?php
        } else {
            // New booking form..

            $fields = MatukioHelperUtilsBooking::getBookingFields();
            $fieldvals = explode(";", $this->booking->newfields);
            $event = JTable::getInstance('matukio', 'Table');
            $event->load($this->booking->semid);
            // 1::mr;14::;2::;3::Yves;4::Hoppe;5::;6::Libellenstraße;7::80939;8::München;9::;10::hoppe@asklepiad.de;11::;12::;13::;

            $value = array();
            foreach ($fieldvals as $val) {
                $tmp = explode("::", $val);
                if (count($tmp) > 1) {
                    $value[$tmp[0]] = $tmp[1];
                }
            }

            foreach ($fields as $field) {
                MatukioHelperUtilsBooking::printFieldElement($field, false, $value[$field->id]);
            }

            if ($event->fees > 0) {
                echo '<tr>';
                echo '<td class="key" width="150px">';
                echo JText::_("COM_MATUKIO_FIELD_PAYMENT_METHOD");
                echo " <span class=\"mat_req\">*</span>";
                echo '</td>';
                echo '<td>';
                echo MatukioHelperPayment::getPaymentSelect($this->payment, $this->booking->payment_method);
                echo '</td>';
                echo '</tr>';

                // Payment Coupon codes
                if (MatukioHelperSettings::getSettings("payment_coupon", 1) == 1) {
                    ?>
                    <tr>
                        <td class="key" width="150px">
                            <?php echo JText::_("COM_MATUKIO_FIELD_COUPON"); ?>
                        </td>
                        <td>
                            <input class="text_area" type="text" name="coupon_code"
                                   id="coupon_code" value="" size="50"
                                   maxlength="255" style="width: 150px"
                                   title="<?php echo JText::_('COM_MATUKIO_FIELD_COUPON_DESC') ?>"
                                   value="<?php echo $this->booking->coupon_code; ?>"
                                />
                        </td>
                    </tr>
                    <?php
                }
            }
        }
        ?>
    </table>
    <?php
    // Old event only fields.. should be removed some time...
    // Zusatzfelder ausgeben
    $buchopt = MatukioHelperUtilsEvents::getEventBookableArray(0, $event, $this->booking->userid);
    $html = "";
    $tempdis = "";
    $hidden = "";
    $reqfield = " <span class=\"sem_reqfield\">*</span>";
    $reqnow = "\n<tr>" . MatukioHelperUtilsEvents::getTableCell("&nbsp;" . $reqfield . " "
        . JTEXT::_('COM_MATUKIO_REQUIRED_FIELD'), 'd', 'r', '', 'sem_nav', 2) . "</tr>";

    $zusreq = 0;
    $zusfeld = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($event);
    $zustemp = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
    if (count($buchopt[2]) > 0) {
        $zustemp = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($buchopt[2][0]);
        $zustemp = $zustemp[0];
    }

    for ($i = 0; $i < count($zusfeld[0]); $i++) {
        if ($zusfeld[0][$i] != "" AND ($buchopt[0] > 1 OR $this->art == 3)) {
            $zusart = explode("|", $zusfeld[0][$i]);
            if (count($buchopt[2]) == 0) {
                $zustemp[$i] = $zusart[2];
            }

            $name =  'zusatz' . ($i + 1);
            $val = $this->booking->$name;

            //var_dump($val);

            $zustemp[$i] = $val;

            $htxt = $zusart[0] . MatukioHelperUtilsBasic::createToolTip($zusfeld[1][$i]);
            $temp = "";
            $html .= "\n<tr>" . MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '150px', 'sem_rowd');
            if ($tempdis == "") {
                if ($zusart[1] == 1) {
                    $temp = $reqfield;
                    $reqtext = $reqnow;
                }
            }
            if (count($zusart) > 1) {
                $optionen = array();
                switch ($zusart[3]) {
                    case "select":
                        $optionen[] = JHTML::_('select.option', '', '- ' . JTEXT::_('COM_MATUKIO_PLEASE_SELECT') . ' -');
                        for ($z = 4; $z < count($zusart); $z++) {
                            $optionen[] = JHTML::_('select.option', $zusart[$z], $zusart[$z]);
                        }
                        $htxt = JHTML::_('select.genericlist', $optionen, 'zusatz' . ($i + 1), 'class="sem_inputbox" size="1"' . $tempdis, 'value', 'text', $zustemp[$i]) . $temp;
                        break;
                    case "radio":
                        for ($z = 4; $z < count($zusart); $z++) {
                            $optionen[] = JHTML::_('select.option', $zusart[$z], $zusart[$z]);
                        }
                        $auswahl = $zustemp[$i];
                        if ($zusfeld[2][$i] == 1 AND $auswahl == "") {
                            $auswahl = $zusart[2];
                        }
                        $htxt = JHTML::_('select.radiolist', $optionen, 'zusatz' . ($i + 1), 'class="sem_inputbox"' . $tempdis, 'value', 'text', $auswahl) . $temp;
                        break;
                    case "textarea":
                        if (count($zusart) > 4) {
                            if (!is_numeric($zusart[4])) {
                                $zusart[4] = 30;
                            }
                            if (!is_numeric($zusart[5])) {
                                $zusart[5] = 3;
                            }
                        } else {
                            $zusart[4] = 30;
                            $zusart[5] = 3;
                        }
                        $htxt = "<textarea class=\"sem_inputbox\" id=\"zusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" cols=\""
                            . $zusart[4] . "\" rows=\"" . $zusart[5] . "\"" . $tempdis . ">" . $zustemp[$i] . "</textarea>" . $temp;
                        break;
                    case "email":
                        $htxt = "<input type=\"text\" class=\"sem_inputbox\" id=\"emailzusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" value=\""
                            . $zustemp[$i] . "\" size=\"50\"" . $tempdis . ">" . $temp;
                        break;
                    default:
                        $htxt = "<input type=\"text\" class=\"sem_inputbox\" id=\"zusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" value=\""
                            . $zustemp[$i] . "\" size=\"50\"" . $tempdis . ">" . $temp;
                        break;
                }
            } else {
                $htxt = "<input class=\"sem_inputbox\" type=\"text\" id=\"zusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" value=\"" . $zustemp[$i]
                    . "\" size=\"50\"" . $tempdis . ">" . $temp;
            }
            $html .= MatukioHelperUtilsEvents::getTableCell($htxt, 'd', 'l', '', 'sem_rowd') . "</tr>";
            $zwang = 0;
            if ($zusart[1] == 1) {
                $zwang = 1;
            }
            $hidden .= "<input type=\"hidden\" id=\"opt" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "opt\" value=\"" . $zwang . "\">";
        } else {
            $hidden .= "<input type=\"hidden\" id=\"zusatz" . ($i + 1) . "\" name=\"zusatz" . ($i + 1) . "\" value=\"\"><input type=\"hidden\" name=\"zusatz" . ($i + 1) . "opt\" value=\"0\">";
        }
    }

    echo "<table class=\"mat_table\">\n";
    echo $html;
    echo "</table>";
    ?>

</fieldset>
<?php
echo $hidden;
?>
<input type="hidden" name="id" value="<?php echo $this->booking->id; ?>"/>
<input type="hidden" name="oldform"
       value="<?php echo MatukioHelperSettings::getSettings('oldbookingform', 0); ?>"/>
<input type="hidden" name="event_id" value="<?php echo $this->booking->semid; ?>"/>
<input type="hidden" name="userid" value="<?php echo $this->booking->userid; ?>"/>
<input type="hidden" name="uid" value="<?php echo $this->booking->userid; ?>"/>
<input type="hidden" name="uuid" value="<?php echo $this->booking->uuid; ?>"/>
<input type="hidden" name="option" value="com_matukio"/>
<input type="hidden" name="view" value="bookings"/>
<input type="hidden" name="controller" value="bookings"/>
<input type="hidden" name="task" value="editBooking"/>
</form>
</div>