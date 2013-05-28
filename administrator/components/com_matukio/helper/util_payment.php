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

class MatukioHelperPayment
{
    private static $instance;

    public static function getPaymentSelect($payment_array, $selected = null){

        $html = "<select name=\"payment\" id=\"payment\">";
        if(count($payment_array) > 1) {
            $html .= "<option name=\"choose\" value=\"\">" . JTEXT::_("COM_MATUKIO_FIELD_CHOOSE") ."</option>";
        }

        for($i = 0; $i < count($payment_array); $i++){
            $pay = $payment_array[$i];

            $select = "";
            if($pay['name'] == $selected){
                $select = ' selected="selected" ';
            }

            $html .= "<option name=\"". $pay['name'] . "\" value=\"" . $pay['name']
                . "\" " . $select . ">" . JText::_($pay['title']) ."</option>";
        }
        $html .= "</select>";

        return $html;
    }

    public static function getBanktransferInfo($account, $blz, $bank, $accountholder, $iban, $bic){

        $html = '<div id="mat_banktransfer">';
        $html .= JText::_('COM_MATUKIO_PAYMENT_BANKTRANSFER_INTRO');
        $html .= "<br />";
        $html .= "<br />";
        if(!empty($account)){
            $html .= JText::_('COM_MATUKIO_PAYMENT_BANKTRANSFER_ACCOUNT');
            $html .= ": " . $account;
            $html .= "<br />";
        }

        if(!empty($blz)){
            $html .= JText::_('COM_MATUKIO_PAYMENT_BANKTRANSFER_BANKCODE');
            $html .= ": " . $blz;
            $html .= "<br />";
        }

        if(!empty($iban)){
            $html .= JText::_('COM_MATUKIO_PAYMENT_BANKTRANSFER_IBAN');
            $html .= ": " . $iban;
            $html .= "<br />";
        }

        if(!empty($bic)){
            $html .= JText::_('COM_MATUKIO_PAYMENT_BANKTRANSFER_BIC');
            $html .= ": " . $bic;
            $html .= "<br />";
        }

        if(!empty($bank)){
            $html .= JText::_('COM_MATUKIO_PAYMENT_BANKTRANSFER_BANK');
            $html .= ": " . $bank;
            $html .= "<br />";
        }
        $html .= JText::_('COM_MATUKIO_PAYMENT_BANKTRANSFER_ACCOUNTHOLDER');
        $html .= ": " . $accountholder;
        $html .= "<br />";

        $html .= "</div>\n";

        return $html;
    }

    public static function getPayPalForm($payment_adress, $eventname, $fee, $currency, $returnurl){
//        $html = '<div id="mat_paypal">';
//        $html .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">';
//        $html .= '<input type="hidden" name="cmd" value="_xclick">';
//        $html .= '<input type="hidden" name="business" value="' . $this->merchant . '">';
//        $html .= '<input type="hidden" name="item_name" value="' . $eventname . '">';
//        $html .= '<input type="hidden" name="item_number" value="1">';
//        $html .= '<input type="hidden" name="amount" value="' . $fee . '">';
//        $html .= '<input type="hidden" name="no_shipping" value="1">';
//        $html .= '<input type="hidden" name="no_note" value="1">';
//
//        			//'postback'		=> JURI::base().'index.php?option=com_akeebasubs&view=callback&paymentmethod=paypal',
//
//        $html .= '<input type="hidden" name="currency_code" value="' . $currency . '">';
//        $html .= '<input type="hidden" name="return" value="' . JRoute::_($returnurl) . '">';
//        $html .= '<input type="hidden" name="rm" value="2">';
//        $html .= '<input type="hidden" name="bn" value="IC_Beispiel">';
//        $html .= '<input type="image" src="https://www.paypal.com/en_GB/i/btn/x-click-but01.gif"
//                         name="submit" alt="Pay with paypal">';
//        $html .= '<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">';
//        $html .= '</form>';
//        $html .= '</div>';

        $html = '<div id="mat_paypal">';
        $html .= JText::_('COM_MATUKIO_PAYMENT_PAYPAL_INTRO');
        $html .= '</div>';
        return $html;
    }


    /**
     * Generates a Universally Unique IDentifier, version 4.
     *
     * This function generates a truly random UUID.
     *
     * @paream boolean	If TRUE return the uuid in hex format, otherwise as a string
     * @see http://tools.ietf.org/html/rfc4122#section-4.4
     * @see http://en.wikipedia.org/wiki/UUID
     * @return string A UUID, made up of 36 characters or 16 hex digits.
     */
    public static function getUuid($hex = false)
    {
        $pr_bits = false;

        if (!$pr_bits)
        {
            $fp = @fopen ( '/dev/urandom', 'rb' );
            if ($fp !== false)
            {
                $pr_bits .= @fread ( $fp, 16 );
                @fclose ( $fp );
            }
            else
            {
                // If /dev/urandom isn't available (eg: in non-unix systems), use mt_rand().
                $pr_bits = "";
                for($cnt = 0; $cnt < 16; $cnt ++) {
                    $pr_bits .= chr ( mt_rand ( 0, 255 ) );
                }
            }
        }

        $time_low = bin2hex ( substr ( $pr_bits, 0, 4 ) );
        $time_mid = bin2hex ( substr ( $pr_bits, 4, 2 ) );
        $time_hi_and_version = bin2hex ( substr ( $pr_bits, 6, 2 ) );
        $clock_seq_hi_and_reserved = bin2hex ( substr ( $pr_bits, 8, 2 ) );
        $node = bin2hex ( substr ( $pr_bits, 10, 6 ) );

        /**
         * Set the four most significant bits (bits 12 through 15) of the
         * time_hi_and_version field to the 4-bit version number from
         * Section 4.1.3.
         * @see http://tools.ietf.org/html/rfc4122#section-4.1.3
         */
        $time_hi_and_version = hexdec ( $time_hi_and_version );
        $time_hi_and_version = $time_hi_and_version >> 4;
        $time_hi_and_version = $time_hi_and_version | 0x4000;

        /**
         * Set the two most significant bits (bits 6 and 7) of the
         * clock_seq_hi_and_reserved to zero and one, respectively.
         */
        $clock_seq_hi_and_reserved = hexdec ( $clock_seq_hi_and_reserved );
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved >> 2;
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved | 0x8000;

        //Either return as hex or as string
        $format = $hex ? '%08s%04s%04x%04x%012s' : '%08s-%04s-%04x-%04x-%012s';

        return sprintf ( $format, $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $node );
    }

    public static function validEmail($email)
    {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        } else {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                // local part length exceeded
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length exceeded
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
                // local part starts or ends with '.'
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)) {
                // local part has two consecutive dots
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                // character not valid in domain part
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)) {
                // domain part has two consecutive dots
                $isValid = false;
            } else if
            (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                    str_replace("\\\\","",$local))) {
                // character not valid in local part unless
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/',
                    str_replace("\\\\","",$local))) {
                    $isValid = false;
                }
            }

            // Check the domain name
            if($isValid && !MatukioHelperPayment::is_valid_domain_name($domain)) {
                return false;
            }

            // Uncomment below to have PHP run a proper DNS check (risky on shared hosts!)
            /**
            if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
            // domain not found in DNS
            $isValid = false;
            }
            /**/
        }
        return $isValid;
    }

    public static function is_valid_domain_name($domain_name)
    {
        $pieces = explode(".",$domain_name);
        foreach($pieces as $piece) {
            if (!preg_match('/^[a-z\d][a-z\d-]{0,62}$/i', $piece)
                || preg_match('/-$/', $piece) ) {
                return false;
            }
        }
        return true;
    }

    public static function _toPPDuration($days)
    {
        $ret = (object)array(
            'unit'		=> 'D',
            'value'		=> $days
        );

        // 0-90 => return days
        if($days < 90) return $ret;

        // Translate to weeks, months and years
        $weeks = (int)($days / 7);
        $months = (int)($days / 30);
        $years = (int)($days / 365);

        // Find which one is the closest match
        $deltaW = abs($days - $weeks*7);
        $deltaM = abs($days - $months*30);
        $deltaY = abs($days - $years*365);
        $minDelta = min($deltaW, $deltaM, $deltaY);

        // Counting weeks gives a better approximation
        if($minDelta == $deltaW) {
            $ret->unit = 'W';
            $ret->value = $weeks;

            // Make sure we have 1-52 weeks, otherwise go for a months or years
            if(($ret->value > 0) && ($ret->value <= 52)) {
                return $ret;
            } else {
                $minDelta = min($deltaM, $deltaY);
            }
        }

        // Counting months gives a better approximation
        if($minDelta == $deltaM) {
            $ret->unit = 'M';
            $ret->value = $months;

            // Make sure we have 1-24 month, otherwise go for years
            if(($ret->value > 0) && ($ret->value <= 24)) {
                return $ret;
            } else {
                $minDelta = min($deltaM, $deltaY);
            }
        }

        // If we're here, we're better off translating to years
        $ret->unit = 'Y';
        $ret->value = $years;

        if($ret->value < 0) {
            // Too short? Make it 1 (should never happen)
            $ret->value = 1;
        } else if($ret->value > 5) {
            // One major pitfall. You can't have renewal periods over 5 years.
            $ret->value = 5;
        }

        return $ret;
    }


    /**
     * Gets the IPN callback URL
     */
    private static function getCallbackURL($sandbox = 0, $ssl = 1)
    {
        $scheme = $ssl ? 'ssl://' : '';
        if($sandbox) {
            return $scheme.'www.sandbox.paypal.com';
        } else {
            return $scheme.'www.paypal.com';
        }
    }

    /**
     * Validates the incoming data against PayPal's IPN to make sure this is not a
     * fraudelent request.
     */
    private function isValidIPN($data, $ssl = 1)
    {
        $url = $this->getCallbackURL();

        $req = 'cmd=_notify-validate';
        foreach($data as $key => $value) {
            $value = urlencode($value);
            $req .= "&$key=$value";
        }
        $header = '';
        $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

        $port = $ssl ? 443 : 80;

        $fp = fsockopen ($url, $port, $errno, $errstr, 30);

        if (!$fp) {
            // HTTP ERROR
            return false;
        } else {
            fputs ($fp, $header . $req);
            while (!feof($fp)) {
                $res = fgets ($fp, 1024);
                if (strcmp ($res, "VERIFIED") == 0) {
                    return true;
                } else if (strcmp ($res, "INVALID") == 0) {
                    return false;
                }
            }
            fclose ($fp);
        }
    }


}