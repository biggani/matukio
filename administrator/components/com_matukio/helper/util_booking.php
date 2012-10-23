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

class MatukioHelperUtilsBooking
{
    private static $instance;

    public static function getBookingId($id)
    {
        return strtoupper(substr(sha1($id), 0, 10));
    }

    // ++++++++++++++++++++++++++++++++++++++
    // +++ Buchungs-ID-Codebild ausgeben  +++
    // ++++++++++++++++++++++++++++++++++++++

    public static function getBookingIdCodePicture($id)
    {
        $temp = MatukioHelperSettings::getSettings('frontend_userlistscode', 1); // $config->get('sem_p029',1);
        if ($temp == 1) {
            return "<img src=\"http://chart.apis.google.com/chart?cht=qr&amp;chs=100x100&amp;choe=UTF-8&amp;chld=H|4&amp;chl="
                . urlencode(MatukioHelperUtilsBooking::getBookingId($id)) . "\"><br /><code><b>"
                . MatukioHelperUtilsBooking::getBookingId($id) . "</b></code>";
        } else if ($temp == 2) {
            // Todo transofrm into a view
            return "<img src=\"" . MatukioHelperUtilsBasic::getComponentPath() . "matukio.code.php?code="
                . MatukioHelperUtilsBooking::getBookingId($id) . "\">";
        }
    }


    public static function getBookingFields($page = null, $published = 1, $orderby = 'ordering' ){
        $database = &JFactory::getDBO();
        if(empty($page)){
            $database->setQuery("SELECT * FROM #__matukio_booking_fields WHERE published = " . $published . " ORDER BY " . $orderby);
        } else {
            $database->setQuery("SELECT * FROM #__matukio_booking_fields WHERE page = " . $page
                . " AND published = " . $published . " ORDER BY " . $orderby);
        }

        $fields = $database->loadObjectList();

        return($fields);
    }

    public static function getBookingHeader($steps){
        $html = "";

        if($steps == 2) {
            $html = '<div id="mat_h1">';
            $html .= '</div>';
            $html .= '<div id="mat_h2">';
            $html .= '</div>';
        } else {
            $html = '<div id="mat_hp1">';
            $html .= '</div>';
            $html .= '<div id="mat_hp2">';
            $html .= '</div>';
            $html .= '<div id="mat_hp3">';
            $html .= '</div>';
        }

        return $html;
    }

        public static function getTextAreaField($name, $title, $value, $style = 'width:300px', $required=false, $class = 'text_area',
                                                $rows = 8, $cols = 50)
        {
            $req = "";
            if($required){
                $req = " required";
            }
            return '<textarea class="' . $class . $req . '" name="' . $name . '" id="' . $name . '" rows="'
                . $rows . '" cols="' . $cols . '" style="' . $style . '" title="' . JText::_($title)
                . '" />' . $value . '</textarea>';
        }

    /**
     * @static
     * @param $name
     * @param $title
     * @param $value
     * @param $values
     * @param string $style
     * @param bool $required
     * @param string $class
     * @param int $size
     * @param int $maxlength
     * @return string
     */
    public static function getSelectField($name, $title, $value, $values, $style = 'width:300px',
                                          $required=false, $class = 'inputbox', $size = 50,
                                          $maxlength = 255)
    {

        $req = "";
        if($required){
            $req = " required";
        }

        $valuesArray = MatukioHelperUtilsBooking::getSelectValues($values);

        $select = '<select name="'. $name . '" id="'. $name . '" class="' . $class . $req . '">' . "\n";

        foreach ($valuesArray as $valueOption) {
            if ($value == $valueOption['id']) {
                $selected = 'selected="selected"';
            } else {
                $selected = '';
            }
            $text = strtoupper(str_replace(' ', '_', $valueOption['value']));
            $text = str_replace('(', '', $text);
            $text = str_replace(')', '', $text);
            $text = str_replace(':', '', $text);
            $text = str_replace('.', '', $text);
            $text = str_replace('-', '', $text);
            $text = str_replace('__', '_', $text);
            $select .= '<option value="' . $valueOption['id'] . '" ' . $selected . '>' . JText::_($text) . '</option>' . "\n";
        }
        $select .= '</select>' . "\n";

        return $select;
    }

    /**
     * @static
     * @param $name
     * @param $title
     * @param $value
     * @param $values
     * @param string $style
     * @param bool $required
     * @param string $class
     * @return string
     */
    public static function getRadioField($name, $title, $value, $values, $style = "", $required = false, $class="inputbox"){

        $req = "";

        if($required){
            $req = " required";
        }

        $valuesArray = MatukioHelperUtilsBooking::getSelectValues($values);
        $radio = "";

        foreach ($valuesArray as $valueOption) {
            if ($value == $valueOption['id']) {
                $selected = ' checked="checked"';
            } else {
                $selected = '';
            }

            $text = strtoupper(str_replace(' ', '_', $valueOption['value']));
            $text = str_replace('(', '', $text);
            $text = str_replace(')', '', $text);
            $text = str_replace(':', '', $text);
            $text = str_replace('.', '', $text);
            $text = str_replace('-', '', $text);
            $text = str_replace('__', '_', $text);

            $radio .= '<input type="radio" name="' . $name .'" value="' . $valueOption['id'] .'" ' . $selected . ' /> ' . JText::_($text);
        }

        return $radio;
    }

    /**
     * @static
     * @param $name
     * @param $title
     * @param $value
     * @param $values
     * @param string $style
     * @param bool $required
     * @param string $class
     * @return string
     */
    public static function getCheckboxField($name, $title, $value, $values, $style = "", $required = false, $class="inputbox"){

        $req = "";

        if($required){
            $req = " required";
        }

        $valuesArray = MatukioHelperUtilsBooking::getSelectValues($values);
        $check = "";

        foreach ($valuesArray as $valueOption) {
            if ($value == $valueOption['id']) {
                $selected = ' checked="checked"';
            } else {
                $selected = '';
            }

            $text = strtoupper(str_replace(' ', '_', $valueOption['value']));
            $text = str_replace('(', '', $text);
            $text = str_replace(')', '', $text);
            $text = str_replace(':', '', $text);
            $text = str_replace('.', '', $text);
            $text = str_replace('-', '', $text);
            $text = str_replace('__', '_', $text);

            $check .= '<input type="checkbox" name="' . $name .'" value="' . $valueOption['id'] .'" ' . $selected . ' /> ' . JText::_($text);
        }

        return $check;
    }


    /**
     *
     * @param <type> $params
     * @return <type>
     */
    function getSelectValues($params)
    {

        $regex_one = '/({\s*)(.*?)(})/si';
        $regex_all = '/{\s*.*?}/si';
        $matches = array();
        $count_matches = preg_match_all($regex_all, $params, $matches, PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

        $values = array();

        for ($i = 0; $i < $count_matches; $i++) {

            $matukio = $matches[0][$i][0];
            preg_match($regex_one, $matukio, $matukioParts);
            $values_replace = array("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
            $values = explode("=", $matukioParts[2], 2);

            foreach ($values_replace as $key2 => $values2) {
                $values = preg_replace($values2, '', $values);
            }

            $returnValues[$i]['id'] = $values[0];
            $returnValues[$i]['value'] = $values[1];
        }

        return $returnValues;
    }

    public static function getTextField($name, $title, $value, $style = 'width: 250px', $required = false, $class = 'text_area',
                                        $size = 50, $maxlength = 255)
    {
        $req = "";
        if($required){
            $req = " required";
        }
        return '<input class="' . $class . $req . '" type="text" name="' . $name . '"
            id="' . $name . '" value="' . $value . '" size="' . $size . '"
            maxlength="' . $maxlength . '" style="' . $style . '" title="' .
            JText::_(strtoupper($title) . '_DESC') . '" />';
    }


    public static function getSpacerField($class = "mat_spacer", $style = ""){
        return '<hr class="' . $class . '" style="' . $style . '" >';
    }

    /**
     * @static
     * @param $name
     * @param $title
     * @param $link
     * @param bool $checked
     * @param string $style
     * @param string $class
     * @return string
     */
    public static function getCheckbox($name, $title, $link, $checked = false, $style = "width: 20px;", $class = 'checkbox'){

        return '<input type="checkbox" name="' . $name . '" id="' . $name .'" value="' . $name
            .'" style="' . $style . '" class="' . $class . '" /> ' . JText::_($title);
    }

    public static function getConfirmationfields($name){
        return "<div id=\"conf_" . $name .  "\"></div>";
    }

    /**
     * print the field
     * @static
     * @param $field
     * @param bool $pageone
     * @param $value
     */
    public static function printFieldElement($field, $pageone = false, $value = -1){
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

            echo '<td colspan="2">';

            $style = "width: 250px";
            if (!empty($field->style)) {
                $style = $field->style;
            }

            // Checking required only on page one, should be changed sometime
            if(!$pageone) {
               $field->required = false;
            }

            if($value != -1){
                $field->default = $value;
            }

            switch ($field->type) {
                case 'textarea':
                    echo MatukioHelperUtilsBooking::getTextAreaField($field->field_name,
                        $field->label, $field->default, $style, $field->required);
                    break;

                case 'select':
                    echo MatukioHelperUtilsBooking::getSelectField($field->field_name, $field->label,
                        $field->default, $field->values, $style, $field->required);
                    break;

                case 'radio':
                    echo MatukioHelperUtilsBooking::getRadioField($field->field_name, $field->label, $field->default,
                        $field->values, $style, $field->required);
                    break;

                case 'checkbox':
                    echo MatukioHelperUtilsBooking::getCheckboxField($field->field_name, $field->label, $field->default,
                        $field->values, $style, $field->required);
                    break;

                case 'text':
                default:
                    echo MatukioHelperUtilsBooking::getTextField($field->field_name,
                        $field->label, $field->default, $style, $field->required);
                    break;

            }
            echo '</td>';
            echo '</tr>';
        }
    }

    public static function getBooking($booking_id) {
        $database = JFactory::getDBO();
        $database->setQuery("SELECT * FROM #__matukio_bookings WHERE id='" . $booking_id . "'");
        $booking = $database->loadObject();

        return $booking;
    }


}