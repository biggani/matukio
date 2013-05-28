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

defined('_JEXEC') or die ('Restricted access');


class MatukioHelperTemplates
{

    private static $instance;

    /**
     * @param $event
     * @param $booking
     * @return mixed
     */
    public static function getReplaces($event, $booking = null, $nr = null)
    {

        if ($nr != null) {
            $replaces["MAT_NR"] = $nr;
        }

        $replaces["MAT_DATE"] = JHTML::_('date', '', MatukioHelperSettings::getSettings('date_format_without_time', 'd-m-Y'));  // Current date

        // Event data
        $replaces["MAT_EVENT_SEMNUM"] = $event->semnum;
        $replaces["MAT_EVENT_NUMBER"] = $event->semnum; // Alias
        $replaces["MAT_EVENT_CATID"] = $event->catid;
        $replaces["MAT_EVENT_TITLE"] = $event->title;
        $replaces["MAT_EVENT_TARGET"] = $event->target;

        $replaces["MAT_EVENT_SHORTDESC"] = $event->shortdesc;
        $replaces["MAT_EVENT_DESCRIPTION"] = JHTML::_('content.prepare', $event->description); // TODO change image path

        $replaces["MAT_EVENT_PLACE"] = $event->place;
        $replaces["MAT_EVENT_LOCATION"] = $event->place;

        $replaces["MAT_EVENT_TEACHER"] = $event->teacher;
        $replaces["MAT_EVENT_TUTOR"] = $event->teacher; // Alias

        if ($event->fees > 0)
            $replaces["MAT_EVENT_FEES"] = MatukioHelperUtilsEvents::getFormatedCurrency($event->fees);
        else
            $replaces["MAT_EVENT_FEES"] = JText::_("COM_MATUKIO_FREE");

        $replaces["MAT_EVENT_MAXPUPIL"] = $event->maxpupil;
        $replaces["MAT_EVENT_BOOKEDPUPIL"] = $event->bookedpupil;

        $replaces["MAT_EVENT_BEGIN"] = JHTML::_('date', $event->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
        $replaces["MAT_EVENT_END"] = JHTML::_('date', $event->end, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
        $replaces["MAT_EVENT_BOOKED"] = JHTML::_('date', $event->booked, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));

        $replaces["MAT_EVENT_GMAPLOC"] = $event->gmaploc;
        $replaces["MAT_EVENT_NRBOOKED"] = $event->nrbooked;

        $replaces["MAT_EVENT_ZUSATZ1"] = $event->zusatz1;
        $replaces["MAT_EVENT_ZUSATZ2"] = $event->zusatz2;
        $replaces["MAT_EVENT_ZUSATZ3"] = $event->zusatz3;
        $replaces["MAT_EVENT_ZUSATZ4"] = $event->zusatz4;
        $replaces["MAT_EVENT_ZUSATZ5"] = $event->zusatz5;
        $replaces["MAT_EVENT_ZUSATZ6"] = $event->zusatz6;
        $replaces["MAT_EVENT_ZUSATZ7"] = $event->zusatz7;
        $replaces["MAT_EVENT_ZUSATZ8"] = $event->zusatz8;
        $replaces["MAT_EVENT_ZUSATZ9"] = $event->zusatz9;
        $replaces["MAT_EVENT_ZUSATZ10"] = $event->zusatz10;
        $replaces["MAT_EVENT_ZUSATZ11"] = $event->zusatz11;
        $replaces["MAT_EVENT_ZUSATZ12"] = $event->zusatz12;
        $replaces["MAT_EVENT_ZUSATZ13"] = $event->zusatz13;
        $replaces["MAT_EVENT_ZUSATZ14"] = $event->zusatz14;
        $replaces["MAT_EVENT_ZUSATZ15"] = $event->zusatz15;
        $replaces["MAT_EVENT_ZUSATZ16"] = $event->zusatz16;
        $replaces["MAT_EVENT_ZUSATZ17"] = $event->zusatz17;
        $replaces["MAT_EVENT_ZUSATZ18"] = $event->zusatz18;
        $replaces["MAT_EVENT_ZUSATZ19"] = $event->zusatz19;
        $replaces["MAT_EVENT_ZUSATZ20"] = $event->zusatz20;

        /* Alias */

        $replaces["MAT_EVENT_CUSTOM1"] = $event->zusatz1;
        $replaces["MAT_EVENT_CUSTOM2"] = $event->zusatz2;
        $replaces["MAT_EVENT_CUSTOM3"] = $event->zusatz3;
        $replaces["MAT_EVENT_CUSTOM4"] = $event->zusatz4;
        $replaces["MAT_EVENT_CUSTOM5"] = $event->zusatz5;
        $replaces["MAT_EVENT_CUSTOM6"] = $event->zusatz6;
        $replaces["MAT_EVENT_CUSTOM7"] = $event->zusatz7;
        $replaces["MAT_EVENT_CUSTOM8"] = $event->zusatz8;
        $replaces["MAT_EVENT_CUSTOM9"] = $event->zusatz9;
        $replaces["MAT_EVENT_CUSTOM10"] = $event->zusatz10;
        $replaces["MAT_EVENT_CUSTOM11"] = $event->zusatz11;
        $replaces["MAT_EVENT_CUSTOM12"] = $event->zusatz12;
        $replaces["MAT_EVENT_CUSTOM13"] = $event->zusatz13;
        $replaces["MAT_EVENT_CUSTOM14"] = $event->zusatz14;
        $replaces["MAT_EVENT_CUSTOM15"] = $event->zusatz15;
        $replaces["MAT_EVENT_CUSTOM16"] = $event->zusatz16;
        $replaces["MAT_EVENT_CUSTOM17"] = $event->zusatz17;
        $replaces["MAT_EVENT_CUSTOM18"] = $event->zusatz18;
        $replaces["MAT_EVENT_CUSTOM19"] = $event->zusatz19;
        $replaces["MAT_EVENT_CUSTOM20"] = $event->zusatz20;

        $replaces["MAT_EVENT_ZUSATZ1HINT"] = $event->zusatz1hint;
        $replaces["MAT_EVENT_ZUSATZ2HINT"] = $event->zusatz2hint;
        $replaces["MAT_EVENT_ZUSATZ3HINT"] = $event->zusatz3hint;
        $replaces["MAT_EVENT_ZUSATZ4HINT"] = $event->zusatz4hint;
        $replaces["MAT_EVENT_ZUSATZ5HINT"] = $event->zusatz5hint;
        $replaces["MAT_EVENT_ZUSATZ6HINT"] = $event->zusatz6hint;
        $replaces["MAT_EVENT_ZUSATZ7HINT"] = $event->zusatz7hint;
        $replaces["MAT_EVENT_ZUSATZ8HINT"] = $event->zusatz8hint;
        $replaces["MAT_EVENT_ZUSATZ9HINT"] = $event->zusatz9hint;
        $replaces["MAT_EVENT_ZUSATZ10HINT"] = $event->zusatz10hint;
        $replaces["MAT_EVENT_ZUSATZ11HINT"] = $event->zusatz11hint;
        $replaces["MAT_EVENT_ZUSATZ12HINT"] = $event->zusatz12hint;
        $replaces["MAT_EVENT_ZUSATZ13HINT"] = $event->zusatz13hint;
        $replaces["MAT_EVENT_ZUSATZ14HINT"] = $event->zusatz14hint;
        $replaces["MAT_EVENT_ZUSATZ15HINT"] = $event->zusatz15hint;
        $replaces["MAT_EVENT_ZUSATZ16HINT"] = $event->zusatz16hint;
        $replaces["MAT_EVENT_ZUSATZ17HINT"] = $event->zusatz17hint;
        $replaces["MAT_EVENT_ZUSATZ18HINT"] = $event->zusatz18hint;
        $replaces["MAT_EVENT_ZUSATZ19HINT"] = $event->zusatz19hint;
        $replaces["MAT_EVENT_ZUSATZ20HINT"] = $event->zusatz20hint;

        /* ALIAS */

        $replaces["MAT_EVENT_CUSTOM1HINT"] = $event->zusatz1hint;
        $replaces["MAT_EVENT_CUSTOM2HINT"] = $event->zusatz2hint;
        $replaces["MAT_EVENT_CUSTOM3HINT"] = $event->zusatz3hint;
        $replaces["MAT_EVENT_CUSTOM4HINT"] = $event->zusatz4hint;
        $replaces["MAT_EVENT_CUSTOM5HINT"] = $event->zusatz5hint;
        $replaces["MAT_EVENT_CUSTOM6HINT"] = $event->zusatz6hint;
        $replaces["MAT_EVENT_CUSTOM7HINT"] = $event->zusatz7hint;
        $replaces["MAT_EVENT_CUSTOM8HINT"] = $event->zusatz8hint;
        $replaces["MAT_EVENT_CUSTOM9HINT"] = $event->zusatz9hint;
        $replaces["MAT_EVENT_CUSTOM10HINT"] = $event->zusatz10hint;
        $replaces["MAT_EVENT_CUSTOM11HINT"] = $event->zusatz11hint;
        $replaces["MAT_EVENT_CUSTOM12HINT"] = $event->zusatz12hint;
        $replaces["MAT_EVENT_CUSTOM13HINT"] = $event->zusatz13hint;
        $replaces["MAT_EVENT_CUSTOM14HINT"] = $event->zusatz14hint;
        $replaces["MAT_EVENT_CUSTOM15HINT"] = $event->zusatz15hint;
        $replaces["MAT_EVENT_CUSTOM16HINT"] = $event->zusatz16hint;
        $replaces["MAT_EVENT_CUSTOM17HINT"] = $event->zusatz17hint;
        $replaces["MAT_EVENT_CUSTOM18HINT"] = $event->zusatz18hint;
        $replaces["MAT_EVENT_CUSTOM19HINT"] = $event->zusatz19hint;
        $replaces["MAT_EVENT_CUSTOM20HINT"] = $event->zusatz20hint;

        $replaces["MAT_EVENT_CREATED_BY"] = $event->created_by;
        $replaces["MAT_EVENT_MODIFIED_BY"] = $event->modified_by;

        $replaces["MAT_EVENT_CREATED"] = JHTML::_('date', $event->created, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')); //JFactory::getDate($event->begin);

        $replaces["MAT_EVENT_WEBINAR"] = $event->webinar;

        if ($booking != null) {
            // Booking data

            $replaces["MAT_BOOKING_ID"] = $booking->id;
            $replaces["MAT_BOOKING_NUMBER"] = MatukioHelperUtilsBooking::getBookingId($booking->id);
            $replaces["MAT_SIGN"] = "<span> </span>";


            // Old form
            $replaces["MAT_BOOKING_NAME"] = $booking->name;
            $replaces["MAT_BOOKING_EMAIL"] = $booking->email;


            $replaces["MAT_BOOKING_SID"] = $booking->sid;
            $replaces["MAT_BOOKING_SEMID"] = $booking->semid;
            $replaces["MAT_BOOKING_USERID"] = $booking->userid;
            $replaces["MAT_BOOKING_CERTIFICATED"] = $booking->certificated;

            $replaces["MAT_EVENT_BOOKINGDATE"] = JHTML::_('date', $booking->bookingdate, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));
            $replaces["MAT_EVENT_UPDATED"] = JHTML::_('date', $booking->updated, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));

            $replaces["MAT_BOOKING_COMMENT"] = $booking->comment;
            $replaces["MAT_BOOKING_PAID"] = $booking->paid;

            $replaces["MAT_BOOKING_NRBOOKED"] = $booking->nrbooked;
            $replaces["MAT_BOOKING_BOOKEDNR"] = $booking->nrbooked; // Alias

            $replaces["MAT_BOOKING_ZUSATZ1"] = $booking->zusatz1;
            $replaces["MAT_BOOKING_ZUSATZ2"] = $booking->zusatz2;
            $replaces["MAT_BOOKING_ZUSATZ3"] = $booking->zusatz3;
            $replaces["MAT_BOOKING_ZUSATZ4"] = $booking->zusatz4;
            $replaces["MAT_BOOKING_ZUSATZ5"] = $booking->zusatz5;
            $replaces["MAT_BOOKING_ZUSATZ6"] = $booking->zusatz6;
            $replaces["MAT_BOOKING_ZUSATZ7"] = $booking->zusatz7;
            $replaces["MAT_BOOKING_ZUSATZ8"] = $booking->zusatz8;
            $replaces["MAT_BOOKING_ZUSATZ9"] = $booking->zusatz9;
            $replaces["MAT_BOOKING_ZUSATZ10"] = $booking->zusatz10;
            $replaces["MAT_BOOKING_ZUSATZ11"] = $booking->zusatz11;
            $replaces["MAT_BOOKING_ZUSATZ12"] = $booking->zusatz12;
            $replaces["MAT_BOOKING_ZUSATZ13"] = $booking->zusatz13;
            $replaces["MAT_BOOKING_ZUSATZ14"] = $booking->zusatz14;
            $replaces["MAT_BOOKING_ZUSATZ15"] = $booking->zusatz15;
            $replaces["MAT_BOOKING_ZUSATZ16"] = $booking->zusatz16;
            $replaces["MAT_BOOKING_ZUSATZ17"] = $booking->zusatz17;
            $replaces["MAT_BOOKING_ZUSATZ18"] = $booking->zusatz18;
            $replaces["MAT_BOOKING_ZUSATZ19"] = $booking->zusatz19;
            $replaces["MAT_BOOKING_ZUSATZ20"] = $booking->zusatz20;

            /* Alias */

            $replaces["MAT_BOOKING_CUSTOM1"] = $booking->zusatz1;
            $replaces["MAT_BOOKING_CUSTOM2"] = $booking->zusatz2;
            $replaces["MAT_BOOKING_CUSTOM3"] = $booking->zusatz3;
            $replaces["MAT_BOOKING_CUSTOM4"] = $booking->zusatz4;
            $replaces["MAT_BOOKING_CUSTOM5"] = $booking->zusatz5;
            $replaces["MAT_BOOKING_CUSTOM6"] = $booking->zusatz6;
            $replaces["MAT_BOOKING_CUSTOM7"] = $booking->zusatz7;
            $replaces["MAT_BOOKING_CUSTOM8"] = $booking->zusatz8;
            $replaces["MAT_BOOKING_CUSTOM9"] = $booking->zusatz9;
            $replaces["MAT_BOOKING_CUSTOM10"] = $booking->zusatz10;
            $replaces["MAT_BOOKING_CUSTOM11"] = $booking->zusatz11;
            $replaces["MAT_BOOKING_CUSTOM12"] = $booking->zusatz12;
            $replaces["MAT_BOOKING_CUSTOM13"] = $booking->zusatz13;
            $replaces["MAT_BOOKING_CUSTOM14"] = $booking->zusatz14;
            $replaces["MAT_BOOKING_CUSTOM15"] = $booking->zusatz15;
            $replaces["MAT_BOOKING_CUSTOM16"] = $booking->zusatz16;
            $replaces["MAT_BOOKING_CUSTOM17"] = $booking->zusatz17;
            $replaces["MAT_BOOKING_CUSTOM18"] = $booking->zusatz18;
            $replaces["MAT_BOOKING_CUSTOM19"] = $booking->zusatz19;
            $replaces["MAT_BOOKING_CUSTOM20"] = $booking->zusatz20;

            $replaces["MAT_BOOKING_UUID"] = $booking->uuid;
            $replaces["MAT_BOOKING_PAYMENT_METHOD"] = $booking->payment_method; // TODO Translate
            $replaces["MAT_BOOKING_PAYMENT_NUMBER"] = $booking->payment_number;
            $replaces["MAT_BOOKING_PAYMENT_NETTO"] = MatukioHelperUtilsEvents::getFormatedCurrency($booking->payment_netto);
            $replaces["MAT_BOOKING_PAYMENT_TAX"] = MatukioHelperUtilsEvents::getFormatedCurrency($booking->payment_tax);
            $replaces["MAT_BOOKING_PAYMENT_BRUTTO"] = MatukioHelperUtilsEvents::getFormatedCurrency($booking->payment_brutto);
            $replaces["MAT_BOOKING_COUPON_CODE"] = $booking->coupon_code;

            $replaces["MAT_BOOKING_STATUS"] = MatukioHelperUtilsBooking::getBookingStatusName($booking->status);

            // Booking complete
            $replaces["MAT_BOOKING_ALL_DETAILS_HTML"] = MatukioHelperTemplates::getEmailBookingInfoHTML($event, $booking);
            $replaces["MAT_BOOKING_ALL_DETAILS_TEXT"] = MatukioHelperTemplates::getEmailBookingInfoTEXT($event, $booking);

            /* QR Codes */
            $replaces["MAT_BOOKING_QRCODE_ID"] = MatukioHelperUtilsBooking::getBookingIdCodePicture($booking->sid);


            if ($event->fees > 0) {
                $replaces["MAT_BOOKING_FEES_STATUS"] = MatukioHelperSettings::getSettings('currency_symbol', '$') . " "
                    . MatukioHelperUtilsEvents::getFormatedCurrency($booking->payment_brutto)
                    . " (" . MatukioHelperUtilsBooking::getBookingPaidName($booking->paid) . ")";
            } else {
                $replaces["MAT_BOOKING_FEES_STATUS"] = JText::_("COM_MATUKIO_FREE_EVENT");
            }

        }


        // Event info complete
        $replaces["MAT_EVENT_ALL_DETAILS_HTML"] = MatukioHelperTemplates::getEmailEventInfoHTML($event, $booking);
        $replaces["MAT_EVENT_ALL_DETAILS_TEXT"] = MatukioHelperTemplates::getEmailEventInfoTEXT($event, $booking);


        $replaces["MAT_SIGNATURE"] = MatukioHelperSettings::getSettings('email_signature', 'Please do not answer this E-Mail.');


        /* Other things */
        $replaces["MAT_BANK_TRANSFER_INFORMATIONS"] = MatukioHelperPayment::getBanktransferInfo(MatukioHelperSettings::getSettings("banktransfer_account", ''),
            MatukioHelperSettings::getSettings("banktransfer_blz", ''),
            MatukioHelperSettings::getSettings("banktransfer_bank", ''),
            MatukioHelperSettings::getSettings("banktransfer_accountholder", ''),
            MatukioHelperSettings::getSettings("banktransfer_iban", ''),
            MatukioHelperSettings::getSettings("banktransfer_bic", '')
        );

        $replaces["MAT_PAYPAL_ADDRESS"] = MatukioHelperSettings::getSettings("paypal_address", '');

        // CSV

        if ($booking != null) {
            $replaces["MAT_CSV_BOOKING_DETAILS"] = MatukioHelperTemplates::getExportCSVBookingDetails($booking, $event,
                MatukioHelperSettings::getSettings('export_csv_separator', ';'));

            if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {
                // Old booking form

            } else {
                // New booking form fields
                $fields = MatukioHelperUtilsBooking::getBookingFields();
                $fieldvals = explode(";", $booking->newfields);

                $value = array();
                foreach ($fieldvals as $val) {
                    $tmp = explode("::", $val);
                    if (count($tmp) > 1) {
                        $value[$tmp[0]] = $tmp[1];
                    } else {
                        $value[$tmp[0]] = "";
                    }
                }

                foreach ($fields as $field) {
                    if (!empty($value[$field->id])) {
                        $replaces["MAT_BOOKING_" . strtoupper($field->field_name)] = $value[$field->id];
                    } else {
                        $replaces["MAT_BOOKING_" . strtoupper($field->field_name)] = "";
                    }
                }
            }
        }

        return $replaces;
    }


    /**
     * @return mixed
     */

    public static function getReplacesHeader()
    {


        $replaces["MAT_NR"] = JText::_("COM_MATUKIO_NR");

        $replaces["MAT_SIGN"] = JText::_("COM_MATUKIO_SIGN");

        // Event data
        $replaces["MAT_EVENT_SEMNUM"] = JText::_("COM_MATUKIO_SEMNUM");
        $replaces["MAT_EVENT_CATID"] = JText::_("COM_MATUKIO_CATID");
        $replaces["MAT_EVENT_TITLE"] = JText::_("COM_MATUKIO_FIELDS_TITLE");
        $replaces["MAT_EVENT_TARGET"] = JText::_("COM_MATUKIO_TARGET_GROUP");

        $replaces["MAT_EVENT_SHORTDESC"] = JText::_("COM_MATUKIO_BRIEF_DESCRIPTION");
        $replaces["MAT_EVENT_DESCRIPTION"] = JText::_("COM_MATUKIO_DESCRIPTION");
        $replaces["MAT_EVENT_PLACE"] = JText::_("COM_MATUKIO_FIELDS_CITY");
        $replaces["MAT_EVENT_TEACHER"] = JText::_("COM_MATUKIO_TUTOR");
        $replaces["MAT_EVENT_FEES"] = JText::_("COM_MATUKIO_FEES");
        $replaces["MAT_EVENT_MAXPUPIL"] = JText::_("COM_MATUKIO_MAX_PARTICIPANT");
        $replaces["MAT_EVENT_BOOKEDPUPIL"] = JText::_("COM_MATUKIO_BOOKED_PLACES");

        $replaces["MAT_EVENT_BEGIN"] = JText::_("COM_MATUKIO_BEGIN");
        $replaces["MAT_EVENT_END"] = JText::_("COM_MATUKIO_END");
        $replaces["MAT_EVENT_BOOKED"] = JText::_("COM_MATUKIO_END_BOOKING");

        $replaces["MAT_EVENT_GMAPLOC"] = JText::_("COM_MATUKIO_GMAPS_LOCATION");
        $replaces["MAT_EVENT_NRBOOKED"] = JText::_("COM_MATUKIO_BOOKED_PLACES");

        $replaces["MAT_EVENT_ZUSATZ1"] = JText::_("COM_MATUKIO_CUSTOM");
        $replaces["MAT_EVENT_ZUSATZ2"] = JText::_("COM_MATUKIO_CUSTOM2");
        $replaces["MAT_EVENT_ZUSATZ3"] = JText::_("COM_MATUKIO_CUSTOM3");
        $replaces["MAT_EVENT_ZUSATZ4"] = JText::_("COM_MATUKIO_CUSTOM4");
        $replaces["MAT_EVENT_ZUSATZ5"] = JText::_("COM_MATUKIO_CUSTOM5");
        $replaces["MAT_EVENT_ZUSATZ6"] = JText::_("COM_MATUKIO_CUSTOM6");
        $replaces["MAT_EVENT_ZUSATZ7"] = JText::_("COM_MATUKIO_CUSTOM7");
        $replaces["MAT_EVENT_ZUSATZ8"] = JText::_("COM_MATUKIO_CUSTOM8");
        $replaces["MAT_EVENT_ZUSATZ9"] = JText::_("COM_MATUKIO_CUSTOM9");
        $replaces["MAT_EVENT_ZUSATZ10"] = JText::_("COM_MATUKIO_CUSTOM10");
        $replaces["MAT_EVENT_ZUSATZ11"] = JText::_("COM_MATUKIO_CUSTOM11");
        $replaces["MAT_EVENT_ZUSATZ12"] = JText::_("COM_MATUKIO_CUSTOM12");
        $replaces["MAT_EVENT_ZUSATZ13"] = JText::_("COM_MATUKIO_CUSTOM13");
        $replaces["MAT_EVENT_ZUSATZ14"] = JText::_("COM_MATUKIO_CUSTOM14");
        $replaces["MAT_EVENT_ZUSATZ15"] = JText::_("COM_MATUKIO_CUSTOM15");
        $replaces["MAT_EVENT_ZUSATZ16"] = JText::_("COM_MATUKIO_CUSTOM16");
        $replaces["MAT_EVENT_ZUSATZ17"] = JText::_("COM_MATUKIO_CUSTOM17");
        $replaces["MAT_EVENT_ZUSATZ18"] = JText::_("COM_MATUKIO_CUSTOM18");
        $replaces["MAT_EVENT_ZUSATZ19"] = JText::_("COM_MATUKIO_CUSTOM19");
        $replaces["MAT_EVENT_ZUSATZ20"] = JText::_("COM_MATUKIO_CUSTOM20");

        /* Alias */

        $replaces["MAT_EVENT_CUSTOM1"] = JText::_("COM_MATUKIO_CUSTOM1");
        $replaces["MAT_EVENT_CUSTOM2"] = JText::_("COM_MATUKIO_CUSTOM2");
        $replaces["MAT_EVENT_CUSTOM3"] = JText::_("COM_MATUKIO_CUSTOM3");
        $replaces["MAT_EVENT_CUSTOM4"] = JText::_("COM_MATUKIO_CUSTOM4");
        $replaces["MAT_EVENT_CUSTOM5"] = JText::_("COM_MATUKIO_CUSTOM5");
        $replaces["MAT_EVENT_CUSTOM6"] = JText::_("COM_MATUKIO_CUSTOM6");
        $replaces["MAT_EVENT_CUSTOM7"] = JText::_("COM_MATUKIO_CUSTOM7");
        $replaces["MAT_EVENT_CUSTOM8"] = JText::_("COM_MATUKIO_CUSTOM8");
        $replaces["MAT_EVENT_CUSTOM9"] = JText::_("COM_MATUKIO_CUSTOM9");
        $replaces["MAT_EVENT_CUSTOM10"] = JText::_("COM_MATUKIO_CUSTOM10");
        $replaces["MAT_EVENT_CUSTOM11"] = JText::_("COM_MATUKIO_CUSTOM11");
        $replaces["MAT_EVENT_CUSTOM12"] = JText::_("COM_MATUKIO_CUSTOM12");
        $replaces["MAT_EVENT_CUSTOM13"] = JText::_("COM_MATUKIO_CUSTOM13");
        $replaces["MAT_EVENT_CUSTOM14"] = JText::_("COM_MATUKIO_CUSTOM14");
        $replaces["MAT_EVENT_CUSTOM15"] = JText::_("COM_MATUKIO_CUSTOM15");
        $replaces["MAT_EVENT_CUSTOM16"] = JText::_("COM_MATUKIO_CUSTOM16");
        $replaces["MAT_EVENT_CUSTOM17"] = JText::_("COM_MATUKIO_CUSTOM17");
        $replaces["MAT_EVENT_CUSTOM18"] = JText::_("COM_MATUKIO_CUSTOM18");
        $replaces["MAT_EVENT_CUSTOM19"] = JText::_("COM_MATUKIO_CUSTOM19");
        $replaces["MAT_EVENT_CUSTOM20"] = JText::_("COM_MATUKIO_CUSTOM20");

        $replaces["MAT_EVENT_ZUSATZ1HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT1");
        $replaces["MAT_EVENT_ZUSATZ2HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT2");
        $replaces["MAT_EVENT_ZUSATZ3HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT3");
        $replaces["MAT_EVENT_ZUSATZ4HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT4");
        $replaces["MAT_EVENT_ZUSATZ5HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT5");
        $replaces["MAT_EVENT_ZUSATZ6HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT6");
        $replaces["MAT_EVENT_ZUSATZ7HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT7");
        $replaces["MAT_EVENT_ZUSATZ8HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT8");
        $replaces["MAT_EVENT_ZUSATZ9HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT9");
        $replaces["MAT_EVENT_ZUSATZ10HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT10");
        $replaces["MAT_EVENT_ZUSATZ11HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT11");
        $replaces["MAT_EVENT_ZUSATZ12HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT12");
        $replaces["MAT_EVENT_ZUSATZ13HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT13");
        $replaces["MAT_EVENT_ZUSATZ14HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT14");
        $replaces["MAT_EVENT_ZUSATZ15HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT15");
        $replaces["MAT_EVENT_ZUSATZ16HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT16");
        $replaces["MAT_EVENT_ZUSATZ17HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT17");
        $replaces["MAT_EVENT_ZUSATZ18HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT18");
        $replaces["MAT_EVENT_ZUSATZ19HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT19");
        $replaces["MAT_EVENT_ZUSATZ20HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT20");

        /* ALIAS */

        $replaces["MAT_EVENT_CUSTOM1HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT1");
        $replaces["MAT_EVENT_CUSTOM2HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT2");
        $replaces["MAT_EVENT_CUSTOM3HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT3");
        $replaces["MAT_EVENT_CUSTOM4HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT4");
        $replaces["MAT_EVENT_CUSTOM5HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT5");
        $replaces["MAT_EVENT_CUSTOM6HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT6");
        $replaces["MAT_EVENT_CUSTOM7HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT7");
        $replaces["MAT_EVENT_CUSTOM8HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT8");
        $replaces["MAT_EVENT_CUSTOM9HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT9");
        $replaces["MAT_EVENT_CUSTOM10HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT10");
        $replaces["MAT_EVENT_CUSTOM11HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT11");
        $replaces["MAT_EVENT_CUSTOM12HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT12");
        $replaces["MAT_EVENT_CUSTOM13HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT13");
        $replaces["MAT_EVENT_CUSTOM14HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT14");
        $replaces["MAT_EVENT_CUSTOM15HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT15");
        $replaces["MAT_EVENT_CUSTOM16HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT16");
        $replaces["MAT_EVENT_CUSTOM17HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT17");
        $replaces["MAT_EVENT_CUSTOM18HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT18");
        $replaces["MAT_EVENT_CUSTOM19HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT19");
        $replaces["MAT_EVENT_CUSTOM20HINT"] = JText::_("COM_MATUKIO_CUSTOMHINT20");

        $replaces["MAT_EVENT_CREATED_BY"] = JText::_("COM_MATUKIO_CREATED_BY");
        $replaces["MAT_EVENT_MODIFIED_BY"] = JText::_("COM_MATUKIO_MODIFIED_BY");

        $replaces["MAT_EVENT_CREATED"] = JText::_("COM_MATUKIO_CREATED_ON");

        $replaces["MAT_EVENT_WEBINAR"] = JText::_("COM_MATUKIO_WEBINAR");

        // Booking data

        $replaces["MAT_BOOKING_ID"] = JText::_("COM_MATUKIO_BOOKING_ID");
        $replaces["MAT_BOOKING_NUMBER"] = JText::_("COM_MATUKIO_BOOKING_NUMBER");

        // Old form
        $replaces["MAT_BOOKING_NAME"] = JText::_("COM_MATUKIO_NAME");
        $replaces["MAT_BOOKING_EMAIL"] = JText::_("COM_MATUKIO_EMAIL");

//
//        $replaces["MAT_BOOKING_SID"] = JText::_("$booking->sid");
//        $replaces["MAT_BOOKING_SEMID"] = JText::_("$booking->semid");
        $replaces["MAT_BOOKING_USERID"] = JText::_("COM_MATUKIO_USERID");
        $replaces["MAT_BOOKING_CERTIFICATED"] = JText::_("COM_MATUKIO_CERTIFICATED");

        $replaces["MAT_EVENT_BOOKINGDATE"] = JText::_("COM_MATUKIO_BOOKING_DATE");
        $replaces["MAT_EVENT_UPDATED"] = JText::_("COM_MATUKIO_BOOKING_UPDATED");

        $replaces["MAT_BOOKING_COMMENT"] = JText::_("COM_MATUKIO_COMMENT");
        $replaces["MAT_BOOKING_PAID"] = JText::_("COM_MATUKIO_BOOK_PAID");

        $replaces["MAT_BOOKING_NRBOOKED"] = JText::_("COM_MATUKIO_BOOKED_PLACES");

        $replaces["MAT_BOOKING_ZUSATZ1"] = JText::_("COM_MATUKIO_CUSTOM1");
        $replaces["MAT_BOOKING_ZUSATZ2"] = JText::_("COM_MATUKIO_CUSTOM2");
        $replaces["MAT_BOOKING_ZUSATZ3"] = JText::_("COM_MATUKIO_CUSTOM3");
        $replaces["MAT_BOOKING_ZUSATZ4"] = JText::_("COM_MATUKIO_CUSTOM4");
        $replaces["MAT_BOOKING_ZUSATZ5"] = JText::_("COM_MATUKIO_CUSTOM5");
        $replaces["MAT_BOOKING_ZUSATZ6"] = JText::_("COM_MATUKIO_CUSTOM6");
        $replaces["MAT_BOOKING_ZUSATZ7"] = JText::_("COM_MATUKIO_CUSTOM7");
        $replaces["MAT_BOOKING_ZUSATZ8"] = JText::_("COM_MATUKIO_CUSTOM8");
        $replaces["MAT_BOOKING_ZUSATZ9"] = JText::_("COM_MATUKIO_CUSTOM9");
        $replaces["MAT_BOOKING_ZUSATZ10"] = JText::_("COM_MATUKIO_CUSTOM10");
        $replaces["MAT_BOOKING_ZUSATZ11"] = JText::_("COM_MATUKIO_CUSTOM11");
        $replaces["MAT_BOOKING_ZUSATZ12"] = JText::_("COM_MATUKIO_CUSTOM12");
        $replaces["MAT_BOOKING_ZUSATZ13"] = JText::_("COM_MATUKIO_CUSTOM13");
        $replaces["MAT_BOOKING_ZUSATZ14"] = JText::_("COM_MATUKIO_CUSTOM14");
        $replaces["MAT_BOOKING_ZUSATZ15"] = JText::_("COM_MATUKIO_CUSTOM15");
        $replaces["MAT_BOOKING_ZUSATZ16"] = JText::_("COM_MATUKIO_CUSTOM16");
        $replaces["MAT_BOOKING_ZUSATZ17"] = JText::_("COM_MATUKIO_CUSTOM17");
        $replaces["MAT_BOOKING_ZUSATZ18"] = JText::_("COM_MATUKIO_CUSTOM18");
        $replaces["MAT_BOOKING_ZUSATZ19"] = JText::_("COM_MATUKIO_CUSTOM19");
        $replaces["MAT_BOOKING_ZUSATZ20"] = JText::_("COM_MATUKIO_CUSTOM20");

        /* Alias */

        $replaces["MAT_BOOKING_CUSTOM1"] = JText::_("COM_MATUKIO_CUSTOM1");
        $replaces["MAT_BOOKING_CUSTOM2"] = JText::_("COM_MATUKIO_CUSTOM2");
        $replaces["MAT_BOOKING_CUSTOM3"] = JText::_("COM_MATUKIO_CUSTOM3");
        $replaces["MAT_BOOKING_CUSTOM4"] = JText::_("COM_MATUKIO_CUSTOM4");
        $replaces["MAT_BOOKING_CUSTOM5"] = JText::_("COM_MATUKIO_CUSTOM5");
        $replaces["MAT_BOOKING_CUSTOM6"] = JText::_("COM_MATUKIO_CUSTOM6");
        $replaces["MAT_BOOKING_CUSTOM7"] = JText::_("COM_MATUKIO_CUSTOM7");
        $replaces["MAT_BOOKING_CUSTOM8"] = JText::_("COM_MATUKIO_CUSTOM8");
        $replaces["MAT_BOOKING_CUSTOM9"] = JText::_("COM_MATUKIO_CUSTOM9");
        $replaces["MAT_BOOKING_CUSTOM10"] = JText::_("COM_MATUKIO_CUSTOM10");
        $replaces["MAT_BOOKING_CUSTOM11"] = JText::_("COM_MATUKIO_CUSTOM11");
        $replaces["MAT_BOOKING_CUSTOM12"] = JText::_("COM_MATUKIO_CUSTOM12");
        $replaces["MAT_BOOKING_CUSTOM13"] = JText::_("COM_MATUKIO_CUSTOM13");
        $replaces["MAT_BOOKING_CUSTOM14"] = JText::_("COM_MATUKIO_CUSTOM14");
        $replaces["MAT_BOOKING_CUSTOM15"] = JText::_("COM_MATUKIO_CUSTOM15");
        $replaces["MAT_BOOKING_CUSTOM16"] = JText::_("COM_MATUKIO_CUSTOM16");
        $replaces["MAT_BOOKING_CUSTOM17"] = JText::_("COM_MATUKIO_CUSTOM17");
        $replaces["MAT_BOOKING_CUSTOM18"] = JText::_("COM_MATUKIO_CUSTOM18");
        $replaces["MAT_BOOKING_CUSTOM19"] = JText::_("COM_MATUKIO_CUSTOM19");
        $replaces["MAT_BOOKING_CUSTOM20"] = JText::_("COM_MATUKIO_CUSTOM20");

        $replaces["MAT_BOOKING_UUID"] = JText::_("COM_MATUKIO_UUID");
        $replaces["MAT_BOOKING_PAYMENT_METHOD"] = JText::_("COM_MATUKIO_FIELD_PAYMENT_METHOD");
        $replaces["MAT_BOOKING_PAYMENT_NUMBER"] = JText::_("COM_MATUKIO_FIELD_PAYMENT_NUMBER");
        $replaces["MAT_BOOKING_PAYMENT_NETTO"] = JText::_("COM_MATUKIO_FIELD_PAYMENT_NETTO");
        $replaces["MAT_BOOKING_PAYMENT_TAX"] = JText::_("COM_MATUKIO_FIELD_PAYMENT_TAX");
        $replaces["MAT_BOOKING_PAYMENT_BRUTTO"] = JText::_("COM_MATUKIO_FIELD_PAYMENT_BRUTTO");
        $replaces["MAT_BOOKING_COUPON_CODE"] = JText::_("COM_MATUKIO_FIELD_COUPON");

        $replaces["MAT_CSV_BOOKING_DETAILS"] = MatukioHelperTemplates::getExportCSVBookingDetailsHeader(MatukioHelperSettings::getSettings('export_csv_separator', ';'));
//        // Booking complete
//        $replaces["MAT_BOOKING_ALL_DETAILS_HTML"] = JText::_("MatukioHelperTemplates::getEmailBookingInfoHTML($event, $booking)");
//        $replaces["MAT_BOOKING_ALL_DETAILS_TEXT"] = MatukioHelperTemplates::getEmailBookingInfoTEXT($event, $booking)");
//
//
//        // Event info complete
//        $replaces["MAT_EVENT_ALL_DETAILS_HTML"] = MatukioHelperTemplates::getEmailEventInfoHTML($event, $booking)");
//        $replaces["MAT_EVENT_ALL_DETAILS_TEXT"] = MatukioHelperTemplates::getEmailEventInfoTEXT($event, $booking)");

        if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {
            // Old booking form

        } else {
            // New booking form fields
            $fields = MatukioHelperUtilsBooking::getBookingFields();

            foreach ($fields as $field) {
                if ($field->type != "spacer")
                    $replaces["MAT_BOOKING_" . strtoupper($field->field_name)] = JText::_($field->label);
            }
        }

        return $replaces;
    }

    /**
     * Just returns the template row with the given name
     * @param $tmpl_name
     */
    public static function getTemplate($tmpl_name)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("*")->from("#__matukio_templates")->where("tmpl_name = " . $db->quote($tmpl_name), "published = 1");

        $tmpl = $db->setQuery($query)->loadObject();

        return $tmpl;
    }

    /**
     * @param $art
     * @return string
     */
    public static function getEmailTemplateName($art)
    {
        switch ($art) {
            case 1:
            default:
                return "mail_booking";
                break;

            case 2:
                return "mail_booking_canceled";
                break;

            case 3:
                return "mail_booking_canceled_admin";
                break;
        }
    }


    public static function getEmailBody($tmpl_name, $event, $booking = null)
    {
        $tmpl = MatukioHelperTemplates::getTemplate($tmpl_name);

        if (empty($tmpl)) {
            throw new Exception('COM_MATUKIO_NO_TEMPLATE');
        }

        $tmpl = MatukioHelperTemplates::replaceLanguage($tmpl);

        $tmpl = MatukioHelperTemplates::replaceConstants($tmpl, $event, $booking);

//        echo "Template:<br />";
//        var_dump($tmpl);
//        die("test");

        return $tmpl;
    }

    public static function getParsedExportTemplateHeadding($tmpl, $event)
    {

        $tmpl = MatukioHelperTemplates::replaceLanguage($tmpl);

        $tmpl = MatukioHelperTemplates::replaceConstants($tmpl, $event);

        return $tmpl;
    }


    /**
     * @param $template
     * @return mixed
     */
    public static function replaceLanguage($template)
    {
        $template->value = preg_replace_callback("/##(.*)##/isU", create_function('$matches', 'return JText::_($matches[1]);'), $template->value);
        $template->value_text = preg_replace_callback("/##(.*)##/isU", create_function('$matches', 'return JText::_($matches[1]);'), $template->value_text);
        $template->subject = preg_replace_callback("/##(.*)##/isU", create_function('$matches', 'return JText::_($matches[1]);'), $template->subject);

        return $template;
    }

    /**
     * @param $s
     * @return mixed
     */
    public static function replaceLanguageStrings($s)
    {
        return preg_replace_callback("/##(.*)##/isU", create_function('$matches', 'return JText::_($matches[1]);'), $s);
    }

    /**
     * @param $template
     * @return mixed
     */
    public static function getCSVHeader($template)
    {
        $header_text = $template->value;

        $replaces = MatukioHelperTemplates::getReplacesHeader();

        foreach ($replaces as $key => $replace) {
            $header_text = str_replace($key, $replace, $header_text);
        }

        $header_text .= "\r\n";

        return $header_text;
    }

    /**
     * @param $signature_line
     * @return mixed
     */
    public static function getExportSignatureHeader($signature_line)
    {
        $replaces = MatukioHelperTemplates::getReplacesHeader();

        foreach ($replaces as $key => $replace) {
            if ($key != "MAT_SIGN")
                $signature_line = str_replace($key, "<td>" . $replace . "</td>", $signature_line);
            else
                $signature_line = str_replace($key, "<td width=\"35%\">" . $replace . "</td>", $signature_line);
        }

        return $signature_line;
    }


    /**
     * @param $template
     * @param $bookings
     * @param $event
     */
    public static function getCSVData($template, $bookings, $event)
    {
        $header_text = $template->value;

        $csvdata = "";

        foreach ($bookings as $booking) {
            $replaces = MatukioHelperTemplates::getReplaces($event, $booking);
            $line = $header_text;

            foreach ($replaces as $key => $replace) {
                $line = str_replace($key, $replace, $line);
            }

            $csvdata .= $line;
            $csvdata .= "\r\n";
        }

        return $csvdata;
    }


    /**
     * @param $template
     * @param $event
     * @param $booking
     * @return mixed
     */
    public static function replaceConstants($template, $event, $booking = null)
    {
        $replaces = MatukioHelperTemplates::getReplaces($event, $booking);

        // Replacing all strings here
        foreach ($replaces as $key => $replace) {
            $template->value = str_replace($key, $replace, $template->value);
            $template->value_text = str_replace($key, $replace, $template->value_text);
            $template->subject = str_replace($key, $replace, $template->subject);
        }

        return $template;
    }


    /**
     * @param $event
     * @param $booking
     * @return string
     */
    public static function getEmailBookingInfoHTML($event, $booking)
    {
        $html = '<p><table cellpadding="2" border="0" width="100%">';
        $html .= "\n<tr>
                        <td width=\"180px\"><strong>" . JTEXT::_('COM_MATUKIO_BOOKING_NUMBER') . "</strong>: </td>
                        <td>" . MatukioHelperUtilsBooking::getBookingId($booking->id) . "</td>
                    </tr>";
        if ($booking->nrbooked > 1) {
            $html .= '<tr>';
            $html .= '<td><strong>' . JText::_('COM_MATUKIO_BOOKED_PLACES') . '</strong></td>';
            $html .= '<td>' . $booking->nrbooked . '</td>';
            $html .= '</tr>';
        }


        if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {

            if ($booking->userid == 0) {
                $user = JFactory::getUser(0);
                $user->name = $booking->name;
                $user->email = $booking->email;
            } else {
                $user = JFactory::getuser($booking->userid);
            }


            $html .= "\n<tr><td><strong>" . JTEXT::_('COM_MATUKIO_NAME') . "</strong>: </td><td>" . $booking->name . " (" . $user->name . ")" . "</td></tr>";
            $html .= "\n<tr><td><strong>" . JTEXT::_('COM_MATUKIO_EMAIL') . "</strong>: </td><td>" . $user->email . "</td></tr>";

        } else {
            // New booking form fields
            $fields = MatukioHelperUtilsBooking::getBookingFields();
            $fieldvals = explode(";", $booking->newfields);

            $value = array();
            foreach ($fieldvals as $val) {
                $tmp = explode("::", $val);
                if (count($tmp) > 1) {
                    $value[$tmp[0]] = $tmp[1];
                } else {
                    $value[$tmp[0]] = "";
                }
            }

            foreach ($fields as $field) {
                if ($field->type != "spacer") {
                    if (!empty($value[$field->id])) {
                        $html .= "<tr><td>" . JTEXT::_($field->label) . ": </td><td>" . $value[$field->id] . "</td></tr>";
                    } else {
                        $html .= "<tr><td>" . JTEXT::_($field->label) . ": </td><td> </td></tr>";
                    }
                }
            }

        }

        if ($event->fees > 0 && $event->fees != 0.00) {
            $html .= '</table></p>';

            //echo "Payment Details: ";

            $html .= '<p><table cellpadding="2" border="0" width="100%">';

            $html .= "\n<tr><td width=\"180px\">" . JTEXT::_('COM_MATUKIO_FIELD_PAYMENT_METHOD') . ": </td><td>"
                . $booking->payment_method . "</td></tr>";
            $html .= "\n<tr><td width=\"180px\">" . JTEXT::_('COM_MATUKIO_FEES') . ": </td><td>"
                . MatukioHelperUtilsEvents::getFormatedCurrency($booking->payment_brutto) . "</td></tr>";
        }


        $html .= '</table></p>';

        return $html;
    }


    /**
     * @param $event
     * @param $booking
     * @return string
     */
    public static function getEmailBookingInfoTEXT($event, $booking)
    {
        $html = '\n';
        $html .= JTEXT::_('COM_MATUKIO_BOOKING_NUMBER') . ":\t\t" . MatukioHelperUtilsBooking::getBookingId($booking->id) . "\n";

        if ($booking->nrbooked > 1) {
            $html .= JTEXT::_('COM_MATUKIO_BOOKED_PLACES') . ":\t\t" . $booking->nrbooked . "\n";
        }

        if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {

            if ($booking->userid == 0) {
                $user = JFactory::getUser(0);
                $user->name = $booking->name;
                $user->email = $booking->email;
            } else {
                $user = JFactory::getuser($booking->userid);
            }

            $html .= JTEXT::_('COM_MATUKIO_NAME') . ":\t\t" . $booking->name . " (" . $user->name . ")" . "\n";
            $html .= JTEXT::_('COM_MATUKIO_EMAIL') . ":\t\t" . $booking->email . "\n";

        } else {
            // New booking form fields
            $fields = MatukioHelperUtilsBooking::getBookingFields();
            $fieldvals = explode(";", $booking->newfields);

            $value = array();
            foreach ($fieldvals as $val) {
                $tmp = explode("::", $val);
                if (count($tmp) > 1) {
                    $value[$tmp[0]] = $tmp[1];
                } else {
                    $value[$tmp[0]] = "";
                }
            }

            foreach ($fields as $field) {
                if ($field->type != "spacer") {
                    if (!empty($value[$field->id])) {
                        $html .= JTEXT::_($field->label) . ":\t\t" . $value[$field->id] . "\n";
                    } else {
                        $html .= JTEXT::_($field->label) . ":\t\t" . "\n";
                    }
                }
            }

        }

        $html .= '</table></p>';

        return $html;
    }

    /**
     * @param $event
     * @param $booking
     * @return string
     */
    public static function getEmailEventInfoHTML($event)
    {
        $html = '<p><table cellpadding="2" border="0" width="100%">';
        $html .= "\n<tr><td colspan=\"2\"><b>" . $event->title . "</b></td></tr>";

        if ($event->showbegin > 0) {
            $html .= "\n<tr><td width=\"180px\">" . JTEXT::_('COM_MATUKIO_BEGIN') . ": </td><td>" . JHTML::_('date', $event->begin,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "</td></tr>";
        }
        if ($event->showend > 0) {
            $html .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_END') . ": </td><td>" . JHTML::_('date', $event->end,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "</td></tr>";
        }
        if ($event->showbooked > 0) {
            $html .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_CLOSING_DATE') . ": </td><td>" . JHTML::_('date', $event->booked,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "</td></tr>";
        }
        if ($event->teacher != "") {
            $html .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_TUTOR') . ": </td><td>" . $event->teacher . "</td></tr>";
        }

        $html .= "\n<tr><td>" . JTEXT::_('COM_MATUKIO_CITY') . ": </td><td>" . $event->place . "</td></tr>";


        $html .= '</table></p>';
        return $html;
    }


    /**
     * @param $event
     * @param $booking
     * @return string
     */
    public static function getEmailEventInfoTEXT($event)
    {
        $html = '\n';
        $html .= $event->title . "\n";

        if ($event->showbegin > 0) {
            $html .= JTEXT::_('COM_MATUKIO_BEGIN') . ":\t\t" . JHTML::_('date', $event->begin,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "\n";
        }
        if ($event->showend > 0) {
            $html .= JTEXT::_('COM_MATUKIO_END') . ":\t\t" . JHTML::_('date', $event->end,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "\n";
        }
        if ($event->showbooked > 0) {
            $html .= JTEXT::_('COM_MATUKIO_CLOSING_DATE') . ":\t\t" . JHTML::_('date', $event->booked,
                MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i')) . "\n";
        }
        if ($event->teacher != "") {
            $html .= JTEXT::_('COM_MATUKIO_CLOSING_DATE') . ":\t\t" . $event->teacher . "\n";
        }

        $html .= JTEXT::_('COM_MATUKIO_CITY') . ":\t\t" . $event->place . "\n";

        return $html;
    }


    /**
     * @param string $separator
     * @return string
     */
    public static function getExportCSVBookingDetailsHeader($separator = ";")
    {

        $html = "";

        $html .= "'" . JTEXT::_('COM_MATUKIO_BOOKED_PLACES') . "';";

        if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {
            // Old booking form
            $html .= "'" . JText::_("COM_MATUKIO_NAME") . "';";
            $html .= "'" . JText::_("COM_MATUKIO_EMAIL") . "';";
        } else {
            // New booking form fields
            $fields = MatukioHelperUtilsBooking::getBookingFields();

            foreach ($fields as $field) {
                $html .= "'" . JText::_($field->label) . "';";
            }
        }


        return $html;
    }


    /**
     * @param string $separator
     * @return string
     */
    public static function getExportCSVBookingDetails($booking, $event, $separator = ";")
    {

        $html = "";

        $html .= "'" . $booking->nrbooked . "';";

        if (MatukioHelperSettings::getSettings('oldbookingform', 0) == 1) {
            // Old booking form
            if ($booking->userid < 1) {
                $html .= "'" . $booking->aname . "';";
                $html .= "'" . $booking->aemail . "';";
            } else {
                $user = JFactory::getUser($booking->userid);
                $html .= "'" . $user->name . "';";
                $html .= "'" . $user->email . "';";
            }
        } else {
            // New booking form fields
            $fields = MatukioHelperUtilsBooking::getBookingFields();

            $fields = MatukioHelperUtilsBooking::getBookingFields();
            $fieldvals = explode(";", $booking->newfields);

            $value = array();
            foreach ($fieldvals as $val) {
                $tmp = explode("::", $val);
                if (count($tmp) > 1) {
                    $value[$tmp[0]] = $tmp[1];
                } else {
                    $value[$tmp[0]] = "";
                }
            }

            foreach ($fields as $field) {
                if ($field->type != "spacer") {
                    if (!empty($value[$field->id])) {
                        $html .= "'" . str_replace($separator, " ", $value[$field->id]) . "'" . $separator;
                    } else {
                        $html .= "''" . $separator;
                    }
                }
            }
        }


        return $html;
    }


}