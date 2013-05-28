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

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');
JHTML::_('behavior.tooltip');
JHTML::_('stylesheet', 'media/com_matukio/css/matukio.css');
JHTML::_('stylesheet', 'media/com_matukio/css/booking.css');

$usermail = $this->user->email;

?>
<script type="text/javascript">
window.addEvent('domready', function () {
    var steps = <?php echo $this->steps; ?>;
    var intro = document.id('mat_intro');
    var current_step = 1;

    var btn_next = document.id('btn_next');
    var btn_back = document.id('btn_back');
    var btn_submit = document.id('btn_submit');

    var page_one = document.id('mat_pageone');
    var page_two = document.id('mat_pagetwo');
    var page_three = document.id('mat_pagethree');
    var payment = document.id('payment');

    var usermail = '<?php echo $usermail; ?>';

    var email = document.id('email');
    var agb = document.id('agb');

    var nrbooked = document.id('nrbooked');

    var coupon_code = document.id('coupon_code');

    if(email) {
        email.set('value', usermail );

        if(usermail != "")     {
            // email.set('disabled', true); // TODO add a setting for this
        }
    }

    <?php
    if (MatukioHelperSettings::getSettings("payment_coupon", 1) == 1 && !empty($this->event->fees)) {
        echo "var coupon = true;\n";
    } else {
        echo "var coupon = false;\n";
    }
    ?>

    <?php
    for ($i = 0; $i < count($this->fields_p1); $i++) {
        $field = $this->fields_p1[$i];

        if ($field->type != 'spacer')
            echo "var " . $field->field_name . " = document.id('" . $field->field_name . "');\n";
        // Confirmation fields
        if ($field->type != 'spacer')
            echo "var conf_" . $field->field_name . " = document.id('conf_" . $field->field_name . "');\n";
    }
    ?>

    var mh1, mh2, mh3 = null;

    if (steps == 2) {
        mh1 = document.id('mat_h1');
        mh3 = document.id('mat_h2');
    } else {
        mh1 = document.id('mat_hp1');
        mh2 = document.id('mat_hp2');
        mh3 = document.id('mat_hp3');
    }

    function validateAGB(){
        if(agb) { // No AGB, so they are always true..
            if(agb.checked == false){
                return false;
            }
        }

        return true;
    }

    function nextPage(event) {
        event.stop();

        if (current_step == steps) {
            return;
        }

        current_step++;

        if(current_step == 3 && !validatePayment()){
            alert("<?php echo JTEXT::_("COM_MATUKIO_NO_PAYMENT_SELECTED"); ?>");
            current_step--;
            return;
        }

        // validate input
        if (!validateForm.validate()) {
            //alert("<?php echo JTEXT::_("COM_MATUKIO_PLEASE_FILL_OUT_ALL_REQUIRED_FIELDS"); ?>");
            current_step--;
            return;
        }

        <?php if (MatukioHelperSettings::getSettings("payment_coupon", 1) == 1 && $this->event->fees > 0) : ?>
            if (current_step == 3 && !validateCoupon()) {
                alert("<?php echo JTEXT::_("COM_MATUKIO_INVALID_COUPON_CODE"); ?>");
                current_step--;
                return;
            }
        <?php endif; ?>

        btn_back.setStyle('display', 'inline-block');

        page_one.setStyle('display', 'none');
        mh1.setStyle('display', 'none');


        if (steps == 3 && current_step == 2) {
            page_two.setStyle('display', 'block');

            if (steps != 2) {
            mh2.setStyle('display', 'block');
            }
        }

        if (current_step == steps) {
            page_two.setStyle('display', 'none');
            page_three.setStyle('display', 'block');

            if (steps != 2) {
            mh2.setStyle('display', 'none');
            }
            mh3.setStyle('display', 'block');

            btn_next.setStyle('display', 'none');
            btn_submit.setStyle('display', 'inline-block');

            fillConf();

            if(steps == 3) {
                fillPayment();
            }

        }

    }

    function prevPage(event) {
        event.stop();

        if (current_step == 1) {
            return;
        }

        current_step--;

        if (steps != 2) {
            mh2.setStyle('display', 'none');
        }
        mh3.setStyle('display', 'none');
        page_three.setStyle('display', 'none');
        btn_submit.setStyle('display', 'none');
        btn_next.setStyle('display', 'inline-block');

        if (steps == 3 && current_step == 2) {
            page_two.setStyle('display', 'block');
            if (steps != 2) {
                mh2.setStyle('display', 'block');
            }
        }

        if (current_step == 1) {
            mh1.setStyle('display', 'block');
            btn_back.setStyle('display', 'none');
            page_two.setStyle('display', 'none');
            page_one.setStyle('display', 'block');
        }
    }

    function sendPage(event) {
        event.stop();

        if(!validateAGB()) {
            alert("<?php echo JText::_("COM_MATUKIO_AGB_NOT_ACCEPTED"); ?>");
            return;
        }

        document.id('FrontForm').submit();
    }

    function fillConf() {
        <?php
            // Generate js code for the confirmation fields
            for ($i = 0; $i < count($this->fields_p1); $i++) {
                $field = $this->fields_p1[$i];

                if ($field->type != 'spacer') {
                    if($field->type != 'radio') {
                        echo "conf_" . $field->field_name . ".set('text', " . $field->field_name . ".get('value'));\n";

                        //echo "alert('Value (" . $field->field_name . "): ' + " .$field->field_name . ".get('value'));";
                    } else {
                        echo "conf_" . $field->field_name . ".set('text', document.id(FrontForm).getElement('input[name="
                                     . $field->field_name . "]:checked').value);\n";
                    }
                }
            }
        ?>
    }
    <?php
    /* conf_payment_type, conf_nrbooked, conf_coupon_code, conf_payment_total */
    ?>
    function fillPayment() {
        var conf_payment_type = document.id("conf_payment_type");
        var conf_nrbooked = document.id("conf_nrbooked");
        var conf_coupon_code = document.id("conf_coupon_code");
        var conf_payment_total = document.id("conf_payment_total");

        if(conf_payment_type) {
            conf_payment_type.set('text', document.id("payment").get('value'));
        }

        if(conf_nrbooked) {
            conf_nrbooked.set('text', document.id("nrbooked").get('value'));
        }

        if(conf_coupon_code) {
            conf_coupon_code.set('text', document.id("coupon_code").get('value'));
        }

        // The tricky part
        if(conf_payment_total) {
            var code = "";

            if(coupon_code) {
                code = coupon_code.get('value');
            }

            var places = 1;

            if(nrbooked) {
                places = nrbooked.get('value');
            }

            var erg = new Request({
                url:'index.php?option=com_matukio&view=requests&format=raw&task=get_total&code=' + code + '&fee=<?php echo $this->event->fees ?>&nrbooked=' + places,
                method:'get',
                async:false,

                onSuccess:function (responseText) {
                    resp = responseText;
                    conf_payment_total.set('text', resp);
                }
            });

            erg.send();
        }

    }

    Form.Validator.add('required', {
        errorMsg:'<?php echo JText::_('COM_MATUKIO_FIELD_REQUIRED');?>',
        test:function (element) {
            if (element.value.length == 0) return false;
            else return true;
        }
    });

    var validateForm = new Form.Validator.Inline(document.id('FrontForm'), {
        //useTitles: true
        errorPrefix:'<?php echo JText::_('COM_MATUKIO_ERROR');?>: '
    });

    function validatePayment(){
        if(payment.get('value') == ''){
            return false;
        }
        return true;
    }

<?php
if (MatukioHelperSettings::getSettings("payment_coupon", 1) == 1 && $this->event->fees > 0) {
    ?>

    function validateCoupon() {
        var response = false;
        var code = coupon_code.get('value');

        if (code == '') {
            return true;
        }

        var erg = new Request({
            url:'index.php?option=com_matukio&view=requests&format=raw&task=validate_coupon&code=' + code,
            method:'get',
            async:false,

            onSuccess:function (responseText) {
                response = responseText;
                //alert(response);
            }
        });

        erg.send();
        //alert('Resp: ' + response + ' for code ' + code);
        return (response === 'true');
    }
    <?php
}
?>

    btn_next.addEvent('click', function (event) {
        nextPage(event)
    });
    btn_back.addEvent('click', function (event) {
        prevPage(event)
    });
    btn_submit.addEvent('click', function (event) {
        sendPage(event)
    });

<?php
//if (!empty($this->event->fees) && !empty($this->payment)) {
//    ?>
<!--    payment.addEvent('change', function (event) {-->
<!--        var val = payment.get('value');-->
<!---->
<!--        --><?php
//        if (MatukioHelperSettings::getSettings("payment_paypal", 1) == 1) {
//            echo "var div_pay_paypal = document.id('mat_paypal');\n";
//        }
//        if (MatukioHelperSettings::getSettings("payment_banktransfer", 1) == 1) {
//            echo "var div_pay_banktransfer = document.id('mat_banktransfer');\n";
//        }
//
//        if (MatukioHelperSettings::getSettings("payment_banktransfer", 1) == 1) {
//            echo "div_pay_banktransfer.setStyle('display', 'none');\n";
//        }
//        if (MatukioHelperSettings::getSettings("payment_paypal", 1) == 1) {
//            echo "div_pay_paypal.setStyle('display', 'none');\n";
//        }
//        ?>
<!---->
<!--        if (val == 'payment_cash') {-->
<!---->
<!--        } else if (val == 'payment_banktransfer') {-->
<!--            div_pay_banktransfer.setStyle('display', 'block');-->
<!--        } else if (val == 'payment_paypal') {-->
<!--            div_pay_paypal.setStyle('display', 'block');-->
<!--        }-->
<!---->
<!--    });-->
<!--    --><?php
//}
?>
});
</script>
<form action="index.php" method="post" name="FrontForm" id="FrontForm">

<div id="mat_booking">
<div id="mat_heading">
    <?php
    echo "<div align=\"center\">";
    echo MatukioHelperUtilsBooking::getBookingHeader($this->steps);
    echo "</div>";
    echo "<div id=\"mat_intro\">";
    echo "<h3>" . JText::_($this->event->title) . "</h3>";

    echo JTEXT::_("COM_MATUKIO_BOOKING_INTRO");

    if(MatukioHelperSettings::getSettings("booking_confirmation", 1)){
        echo " " . JTEXT::_("COM_MATUKIO_BOOKING_EMAIL_CONFIRMATION"); // you get an email text
    }

    echo "</div>";
    ?>
    <noscript>
        <h2><?php echo JText::_("COM_MATUKIO_JAVASCRIPT_REQUIRED"); ?></h2>
    </noscript>

</div>
<div id="mat_pageone">
    <table class="mat_table" border="0" cellpadding="8" cellspacing="8">
        <?php
        foreach ($this->fields_p1 as $field) {
            // Prints the field in the table <tr><td>label</td><td>field</td>
            MatukioHelperUtilsBooking::printFieldElement($field, true);
        }
        ?>
    </table>
    <?php
    // Old event only fields.. should be removed some time...
    // Zusatzfelder ausgeben
    $buchopt = MatukioHelperUtilsEvents::getEventBookableArray(0, $this->event, $this->user->id);
    $html = "";
    $tempdis = "";
    $hidden = "";
    $reqfield = " <span class=\"sem_reqfield\">*</span>";
    $reqnow = "\n<tr>" . MatukioHelperUtilsEvents::getTableCell("&nbsp;" . $reqfield . " "
        . JTEXT::_('COM_MATUKIO_REQUIRED_FIELD'), 'd', 'r', '', 'sem_nav', 2) . "</tr>";

    $zusreq = 0;
    $zusfeld = MatukioHelperUtilsEvents::getAdditionalFieldsFrontend($this->event);
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

    if ($this->event->nrbooked > 1 AND MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0) {
        echo "<table class=\"mat_table\">\n";

        $this->limits = array();

        for ($i = 1; $i <= $this->event->nrbooked; $i++) {
            $this->limits[] = JHTML::_('select.option', $i);
        }
        $platzauswahl = JHTML::_('select.genericlist', $this->limits, 'nrbooked', 'class="sem_inputbox" size="1"' . $tempdis,
            'value', 'text', 1);


        if ($buchopt[0] == 3) {
            $htx1 = JTEXT::_('COM_MATUKIO_PLACES_TO_BOOK');
        } else {
            $htx1 = JTEXT::_('COM_MATUKIO_BOOKED_PLACES');
        }
        if ($tempdis == "") {
            $htx2 = $platzauswahl;
        } else {
            $htx2 = "<input class=\"sem_inputbox\" type=\"text\" value=\"" . $buchopt[2][0]->nrbooked
                . "\"size=\"1\" style=\"text-align:right;\"" . $tempdis . " />";
        }

        echo '<tr>';
        echo '<td class="key" width="150px">';
        echo $htx1;
        echo " <span class=\"mat_req\">*</span>";
        echo '</td>';
        echo '<td>';
        echo $htx2;
        echo '</td>';
        echo '</tr>';
        echo "</table>";

    } else {
       echo "<input type=\"hidden\" name=\"nrbooked\" id=\"nrbooked\"value=\"1\">";
    }
    ?>
</div>
<div id="mat_pagetwo">
    <?php
    if ($this->event->fees > 0) {
        ?>
        <table class="mat_table" border="0" cellpadding="8" cellspacing="8">
            <?php
            echo '<tr>';
            echo '<td class="key" width="150px">';
            echo JText::_("COM_MATUKIO_FIELD_PAYMENT_METHOD");
            echo " <span class=\"mat_req\">*</span>";
            echo '</td>';
            echo '<td>';
            echo MatukioHelperPayment::getPaymentSelect($this->payment);
            echo '</td>';
            echo '</tr>';
            ?>
        </table>
        <?php
        // TODO Sometime
//        foreach($this->payment as $paym){
//            echo '<div id="' . $paym['name'] . '">';
//
//            echo '</div>';
//        }

        if (MatukioHelperSettings::getSettings("payment_banktransfer", 1) == 1) {
            echo MatukioHelperPayment::getBanktransferInfo(MatukioHelperSettings::getSettings("banktransfer_account", ''),
                MatukioHelperSettings::getSettings("banktransfer_blz", ''),
                MatukioHelperSettings::getSettings("banktransfer_bank", ''),
                MatukioHelperSettings::getSettings("banktransfer_accountholder", ''),
                MatukioHelperSettings::getSettings("banktransfer_iban", ''),
                MatukioHelperSettings::getSettings("banktransfer_bic", '')
            );
        }

        if (MatukioHelperSettings::getSettings("payment_paypal", 1) == 1) {
            echo MatukioHelperPayment::getPayPalForm(MatukioHelperSettings::getSettings("paypal_address", ''),
                $this->event->title,
                $this->event->fees,
                MatukioHelperSettings::getSettings("paypal_currency", 'EUR'),
                "index.php?option=com_matukio&view=bookevent&task=paypal"
            );
        }

        // Payment Coupon codes
        if (MatukioHelperSettings::getSettings("payment_coupon", 1) == 1) {
            ?>
            <table class="mat_table" border="0" cellpadding="8" cellspacing="8">
                <tr>
                    <td class="key" width="150px">
                        <?php echo JText::_("COM_MATUKIO_FIELD_COUPON"); ?>
                    </td>
                    <td>
                        <input class="text_area" type="text" name="coupon_code"
                               id="coupon_code" value="" size="50"
                               maxlength="255" style="width: 150px"
                               title="<?php echo JText::_('COM_MATUKIO_FIELD_COUPON_DESC') ?>"/>
                    </td>
                </tr>
            </table>
            <?php
        }

        // Fields on Page 2
        if (!empty($this->fields_p2)) {
            ?>
            <table class="mat_table" border="0" cellpadding="8" cellspacing="8">
                <?php
                foreach ($this->fields_p2 as $field) {
                    // Prints the field in the table <tr><td>label</td><td>field</td>
                    MatukioHelperUtilsBooking::printFieldElement($field);
                }
                ?>
            </table>
            <?php
        }


    } else {
        echo "Page 2";
    }
    ?>
</div>
<div id="mat_pagethree">
    <table class="mat_table" border="0" cellpadding="8" cellspacing="8">
        <?php
        // Confirmation

//        echo "<tr>";
//        echo '<td colspan="2">';
//        echo JText::_($this->event->title);
//        echo '</td>';
//        echo '</tr>';

        // Fields
        foreach ($this->fields_p1 as $field) {

            if ($field->type == 'spacer') {
                echo "</table>";
                echo MatukioHelperUtilsBooking::getSpacerField();
                echo "<table class=\"mat_table\">\n";
            } else {
                echo '<tr>';
                echo '<td class="key" width="150px">';
                echo '<label for="' . $field->field_name . '" width="100" title="' . JText::_($field->label) . '">';
                echo JText::_($field->label);
                if ($field->required == 1) {
                    echo " <span class=\"mat_req\">*</span>";
                }

                echo '</label>';
                echo '</td>';

                echo '<td >';

                echo MatukioHelperUtilsBooking::getConfirmationfields($field->field_name);

                echo '</td>';
                echo '</tr>';
            }
        }

        // Fields on Page 3
        if (!empty($this->fields_p3)) {
            ?>
            <table class="mat_table" border="0" cellpadding="8" cellspacing="8">
                <?php
                foreach ($this->fields_p3 as $field) {
                    // Prints the field in the table <tr><td>label</td><td>field</td>
                    MatukioHelperUtilsBooking::printFieldElement($field);
                }
                ?>
            </table>
            <?php
        }
        ?>
    </table>
    <?php
    echo "<br />";
    // Captcha
    if (MatukioHelperSettings::getSettings("captcha", 0)) {
        echo '<table class="mat_table" border="0" cellpadding="8" cellspacing="8">';
        echo "<tr>";
        echo '<td class="key" width="150px">';
        echo JTEXT::_("COM_MATUKIO_CAPTCHA");
        echo "</td>";
        echo "<td>";
        function randomString($len)
        {
            function make_seed()
            {
                list($usec, $sec) = explode(' ', microtime());
                return (float)$sec + ((float)$usec * 100000);
            }

            srand(make_seed());
            $possible = "ABCDEFGHJKLMNPRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789";
            $str = "";
            while (strlen($str) < $len) {
                $str .= substr($possible, (rand() % (strlen($possible))), 1);
            }
            return ($str);
        }

        //header('Content-type: image/png');
        $imagepath = (JPATH_BASE . '/components/com_matukio/captcha/');
        $captchatext = randomString(5);
        $img = ImageCreateFromPNG(JPATH_BASE . '/components/com_matukio/captcha/captcha.PNG');
        $color = ImageColorAllocate($img, 0, 0, 0); //Farbe
        $ttf = (JPATH_BASE . '/components/com_matukio/captcha/XFILES.TTF');
        $ttfsize = 25;
        $angle = rand(0, 5);
        $t_x = rand(5, 30);
        $t_y = 35;
        imagettftext($img, $ttfsize, $angle, $t_x, $t_y, $color, $ttf, $captchatext);
        if (!file_exists($imagepath . md5($captchatext) . '.png')) {
            imagepng($img, $imagepath . md5($captchatext) . '.png');
        }
        ?>
        <input type="text" name="captcha" id="captcha" size="10"> <img src="<?php echo
            'components/com_matukio/captcha/' . md5($captchatext) . '.png' ?>"
                                                                       border="0" title="Captchacode"
                                                                       style="vertical-align:middle;"/>
        <?php
        echo "</td>";
        echo "</table>";
    }

    // Payment

    if($this->event->fees > 0) {

        echo '<table class="mat_table" border="0" cellpadding="8" cellspacing="8">';

        // Payment type
        echo '<tr>';
        echo '<td class="key" width="150px">';
        echo '<label for="conf_payment_type" width="100" title="' . JText::_("COM_MATUKIO_FIELD_PAYMENT_METHOD") . '">';
        echo JText::_("COM_MATUKIO_FIELD_PAYMENT_METHOD");

        echo " <span class=\"mat_req\">*</span>";

        echo '</label>';
        echo '</td>';

        echo '<td >';

        echo "<div id=\"conf_payment_type\"></div>";

        echo '</td>';
        echo '</tr>';

        // Nr Booked
        if ($this->event->nrbooked > 1 AND MatukioHelperSettings::getSettings('frontend_usermehrereplaetze', 1) > 0) {
            echo '<tr>';
            echo '<td class="key" width="150px">';
            echo '<label for="conf_nrbooked" width="100" title="' . JText::_("COM_MATUKIO_BOOKED_PLACES") . '">';
            echo JText::_("COM_MATUKIO_BOOKED_PLACES");

            echo '</label>';
            echo '</td>';

            echo '<td >';

            echo "<div id=\"conf_nrbooked\"></div>";

            echo '</td>';
            echo '</tr>';
        } else {
            echo "<input type=\"hidden\" id=\"conf_nrbooked\" value=\"1\">";
        }

        if (MatukioHelperSettings::getSettings("payment_coupon", 1) == 1) {
            echo '<tr>';
            echo '<td class="key" width="150px">';
            echo '<label for="conf_coupon_code" width="100" title="' . JText::_("COM_MATUKIO_FIELD_COUPON") . '">';
            echo JText::_("COM_MATUKIO_FIELD_COUPON");

            echo '</label>';
            echo '</td>';

            echo '<td >';

            echo "<div id=\"conf_coupon_code\"></div>";

            echo '</td>';
            echo '</tr>';
        } else {
            echo "<input type=\"hidden\" id=\"conf_coupon_code\" value=\"1\">";
        }

        echo '<tr>';
        echo '<td class="key" width="150px">';
        echo '<label for="conf_payment_type" width="100" title="' . JText::_("COM_MATUKIO_TOTAL_AMOUNT") . '">';
        echo JText::_("COM_MATUKIO_TOTAL_AMOUNT");

        echo '</label>';
        echo '</td>';

        echo '<td >';

        echo "<div id=\"conf_payment_total\"></div>";

        echo '</td>';
        echo '</tr>';


        echo '</table>';
    }


    // AGB
    echo "<br />";
    $agb = MatukioHelperSettings::getSettings("agb_text", "");
    if (!empty($agb)) {
        $link = JURI::ROOT() . "index.php?tmpl=component&s=" . MatukioHelperUtilsBasic::getRandomChar() . "&option=" . JFactory::getApplication()->input->get('option') . "&view=agb";
        echo MatukioHelperUtilsBooking::getCheckbox("agb", " ", false);
        echo "<a href=\"" . $link . "\" class=\"modal\" rel=\"{handler: 'iframe', size: {x:700, y:500}}\">";
        echo JTEXT::_('COM_MATUKIO_TERMS_AND_CONDITIONS');
        echo "</a>";
    }

    ?>
</div>
<div id="mat_control">
    <div id="mat_control_inner">
        <button id="btn_back" class="mat_button"><?php echo JTEXT::_("COM_MATUKIO_BACK") ?></button>
        <button id="btn_next" class="mat_button"><?php echo JTEXT::_("COM_MATUKIO_NEXT") ?></button>
        <?php if ($this->event->fees > 0): ?>
            <button id="btn_submit" class="mat_button"><?php echo JTEXT::_("COM_MATUKIO_BOOK_PAID") ?></button>
        <?php else: ?>
            <button id="btn_submit" class="mat_button"><?php echo JTEXT::_("COM_MATUKIO_BOOK") ?></button>
        <?php endif;?>
    </div>
</div>
</div>

<?php
echo $hidden;
?>

<input type="hidden" name="option" value="com_matukio"/>
<input type="hidden" name="view" value="bookevent"/>
<input type="hidden" name="controller" value="bookevent"/>
<input type="hidden" name="task" value="book"/>
<input type="hidden" name="uid" value="<?php echo $this->uid; ?>"/>
<input type="hidden" name="event_id" value="<?php echo $this->event->id; ?>"/>
<input type="hidden" name="catid" value="<?php echo $this->event->catid; ?>"/>
<input type="hidden" name="semid" value="<?php echo $this->event->id; ?>"/>
<input type="hidden" name="userid" value="<?php echo $this->user->id; ?>"/>
<input type="hidden" name="uuid" value="<?php echo MatukioHelperPayment::getUuid(true); ?>"/>
<input type="hidden" name="ccval" value="<?php if(!empty($captchatext)){ echo md5($captchatext); } ?>"/>
</form>

<?php
echo MatukioHelperUtilsBasic::getCopyright();
?>