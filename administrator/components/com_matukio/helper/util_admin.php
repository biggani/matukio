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

class MatukioHelperUtilsAdmin
{
    private static $instance;


// ++++++++++++++++++++++++++++++++++++++
// +++ Druckfenster im Backend ausgeben       sem_f038    TODO fix
// ++++++++++++++++++++++++++++++++++++++

    public static function getBackendPrintWindow($art, $cid)
    {
        $katid = trim(JRequest::getVar('katid', 0));
        $ordid = trim(JRequest::getVar('ordid', 0));
        $ricid = trim(JRequest::getVar('ricid', 0));
        $einid = trim(JRequest::getVar('einid', 0));
        $search = trim(strtolower(JRequest::getVar('search', '')));
        $limit = trim(JRequest::getVar('limit', 5));
        $limitstart = trim(JRequest::getVar('limitstart', 0));
        $uid = trim(JRequest::getVar('uid', 0));

//        $href = JURI::ROOT() . "index.php?tmpl=component&s=" . MatukioHelperUtilsBasic::getRandomChar() . "&option=" . JRequest::getCmd('option')
//            . "&view=printeventlist&dateid=" . $dateid . "&catid=" . $catid . "&search=" . $search . "&amp;limit=" . $limit . "&limitstart="
//            . $limitstart . "&cid=" . $cid . "&uid=" . $uid . "&todo=";

        //$zufall = MatukioHelperUtilsBasic::getRandomChar();
//        $href = "index.php?tmpl=component&s=" . $zufall . "&option=com_matukio&katid=" . $katid . "&ordid=" . $ordid
//            . "&ricid=" . $ricid . "&einid=" . $einid . "&search=" . $search . "&limit=" . $limit . "&limitstart="
//            . $limitstart . "&uid=" . $uid . "&task=";

        $href = JURI::ROOT() . "index.php?tmpl=component&s=" . 0 . "&option=" . JRequest::getCmd('option')
            . "&view=printeventlist&search=" . $search . "&amp;limit=" . $limit . "&limitstart="
            . $limitstart . "&cid=" . $cid . "&uid=" . $uid . "&todo=";

        $x = 550;
        $y = 300;
        $image = "1932";
        $title = JTEXT::_('COM_MATUKIO_PRINT');
        switch ($art) {
            case 1:
                $href .= "print_eventlist"; // 36
                break;
            case 2:
                $href .= "print_teilnehmerliste&art=1&cid=" . $cid;  // Teilnehmerliste  // 34
                $image = "1932";
                break;
            case 3:
                $href .= "certificate&cid=" . $cid;   // 35
                $image = "2900";
                $title = JTEXT::_('COM_MATUKIO_PRINT_CERTIFICATE');
                break;
            case 4:
                $href .= "print_teilnehmerliste&cid=" . $cid;         // Unterschriftliste
                $image = "2032";
                break;
            case 5:
                $href = JURI::ROOT() ."index.php?option=com_matukio&view=printeventlist&format=raw&todo=csvlist&cid=" . $cid;
                $image = "1632";
                $title = JTEXT::_('COM_MATUKIO_DOWNLOAD_CSV_FILE');
                break;
        }

        //echo $href;
        //die("asdf");
        if ($art != 5) {
            $html = "<a title=\"" . $title . "\" class=\"modal\" href=\"" . $href . "\" rel=\"{handler: 'iframe', size: {x: " . $x . ", y: " . $y . "}}\">";
        } else {
            $html = "<a title=\"" . $title . "\" href=\"" . $href . "\">";
        }
        $html .= "<img src=\"" . MatukioHelperUtilsBasic::getComponentImagePath() . $image . ".png\" border=\"0\" valign=\"absmiddle\" alt=\"" . $title . "\"></a>";
        return $html;
    }

    // +++++++++++++++++++++++++++++++++++++++
// +++ Ausgabe einer Tabellenzeile     +++      sem_f024
// +++++++++++++++++++++++++++++++++++++++

    public static function getTableLine($art, $var1, $var2, $werte, $klasse)
    {
        $zurueck = "<tr";
        if ($klasse <> "") {
            $zurueck .= " class=\"" . $klasse . "\"";
        }
        $zurueck .= ">";

        $n = count($werte);
        for ($l = 0, $n; $l < $n; $l++) {
            $format1 = "";
            if (is_array($var1)) {
                switch ($var1[$l]) {
                    case "c2":
                        $format1 .= " colspan=\"2\"";
                        break;
                    case "nw":
                        $format1 .= " nowrap=\"nowrap\"";
                        break;
                    case "l":
                        $format1 .= " style=\"text-align:left;\"";
                        break;
                    case "r":
                        $format1 .= " style=\"text-align:right;\"";
                        break;
                    case "c":
                        $format1 .= " style=\"text-align:center;\"";
                        break;
                }
            }
            $format2 = "";
            if (is_array($var2)) {
                switch ($var2[$l]) {
                    case "c2":
                        $format1 .= " colspan=\"2\"";
                        break;
                    case "nw":
                        $format1 .= " nowrap=\"nowrap\"";
                        break;
                    case "l":
                        $format1 .= " style=\"text-align:left;\"";
                        break;
                    case "r":
                        $format1 .= " style=\"text-align:right;\"";
                        break;
                    case "c":
                        $format1 .= " style=\"text-align:center;\"";
                        break;
                }
            }
            $zurueck .= "<" . $art . $format1 . $format2 . ">" . $werte[$l] . "</" . $art . ">";
        }

        $zurueck .= "</tr>";
        return $zurueck;
    }
}