<?php

/**
 * Matukio - Installation
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 * Based on Seminar for Joomla!
 * by Dirk Vollmar
 **/

defined( '_JEXEC' ) or die ( 'Restricted access' );

function com_install()
{
    $database = &JFactory::getDBO();
    if (file_exists(JPATH_ADMINISTRATOR . "/components/com_joomfish/config.joomfish.php")) {
        rename(JPATH_ADMINISTRATOR . "/components/com_matukio/joomfish/jf_matukio.xml", JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/matukio.xml");
    }
    $update = "";

    $imagedir = "../media/com_matukio/images/";
    $lang = JFactory::getLanguage();
    $sprache = strtolower(substr($lang->getName(), 0, 2));
    $html = "<img src=\"" . $imagedir . "logo.png\" valign=\"middle\"><font size=\"+1\" color=\"#0B55C4\"> Matukio" . $update . "</font>";
    $html .= "<div align=\"center\"><table border=\"0\" width=\"90%\"><tbody>";
    $html .= "<tr><td width=\"18%\"><b>Autor:</b></td><td width=\"80%\">Comojoom.com (Daniel Dimitrov &amp; Yves Hoppe)</td></tr>";
    $html .= "<tr><td width=\"18%\"><b>Internet:</b></td><td width=\"80%\"><a target=\"_blank\" href=\"http://compojoom.com\">http://compojoom.com</a></td></tr>";
    $html .= "<tr><td width=\"18%\"><b>Version:</b></td><td width=\"80%\">2.1.1</td></tr>";
    switch ($sprache) {
        case "de":
            $html .= "<tr><td colspan=\"2\">";
            $html .= "Mit Matukio haben Sie sich f&uuml;r ein leistungsstarkes Buchungssystem f&uuml;r Ihre joomla!-Seite entschieden. ";
            $html .= "Egal, ob Sie Fortbildungen anbieten, Ihr Verein Ausfl&uuml;ge veranstaltet oder Sie zu einer Party einladen m&ouml;chten: Mit Matukio ist die Verwaltung der Veranstaltungen kein Problem. <p>";
            $html .= "Matukio wurde unter der <a href=\"http://www.gnu.org/licenses/gpl.html\" target=\"_new\">GNU General Public License</a> ver&ouml;ffentlicht.<p>";
            $html .= "<ul>";
            $html .= "<li>Die grundlegenden Datumsformate werden durch die Sprachdateien festgelegt. Darüberhinaus können Sie aber durch Angaben in den Einstellungen überschrieben werden.";
            $html .= "<li>Joomfish wird direkt unterstützt.";
            $html .= "<li>In der Beschreibung können nun Tags steuern, wer bestimmte Textteile angezeigt bekommt. So wird bei der Angabe von [sem_registered] TEXT [/sem_registered] TEXT nur den registrierten Benutzern angezeigt.";
            $html .= "<li>Die Eingabelder können vorbelegt werden. Dazu musste aber das Steuerformat geändert werden. Es hat nun das Format Bezeichner|Pflichtfeld|Vorgabewert|Feldtyp|Parameter|Parameter|... Alte Veranstaltungen müssen leider angepasst werden.";
            $html .= "<li>In den Einstellungen kann festgelegt werden, ab wann die aktuellen Kurse nicht mehr angezeigt werden sollen (Beginn, Ende oder Anmeldeschluss der Veranstaltung). Diese Einstellung wird auch im Modul berücksichtigt.";
            $html .= "<li>Die Sommerzeit wird automatisch berücksichtigt (optional). Damit muss die Zeitzone während der Sommerzeit nicht extra auf +2 gestellt werden. Auch das Modul greift auf diese Einstellung zurück.";
            $html .= "<li>Die im Textfeld 'Beschreibung' verwendeten Markierungen für die Plugins vom Typ 'Inhalt' werden in HTML-Code umgesetzt.";
            $html .= "<li>Die Begrenzung der Zusatzfelder auf 120 Zeichen wurde aufgehoben.";
            $html .= "<li>Das Zahlenformat für die Währung kann festgelegt werden (Dezimalstellen, Tausender-Trennzeichen, Dezimal-Trennzeichen).";
            $html .= "<li>Bei kostenpflichtigen Veranstaltungen wird der Preis stärker hervorgehoben dargestellt als bisher.";
            $html .= "<li>Wird die Infozeile in der Übersicht ausgeblendet, so werden auch die freien Plätze in der Detailansicht nicht mehr angezeigt.";
            $html .= "<li>Beim nachträglichen Ändern einer Veranstaltung wurden die Zugriffe auf 0 zurückgesetzt. Der Fehler ist behoben.";
            $html .= "<li>Veranstaltungsbuchungen können von den Benutzern nur so lange geändert werden, bis die Buchung als bezahlt markiert wurde. danach sind Änderungen nur noch durch den Veranstalter möglich.";
            $html .= "<li>Werden bei einer Veranstaltung die maximal buchbaren Plätze auf 0 gesetzt, ist diese nicht mehr online buchbar und dient als Veranstaltungsankündigung.";
            $html .= "<li>Die Einstellungen im Backend sind nun direkt aufrufbar und nicht mehr über ein Fenster.";
            $html .= "<li>Für die Teilnehmerübersichten der Benutzer kann zwischen Realnamen und Benutzernamen gewählt werden.";
            $html .= "<li>Der Eingabebereich der Veranstaltungen wurde aufgeteilt (Grundangaben, Zusatzangaben, Eingabefelder, Dateien), um die inzwischen sehr umfangreichen Eingabemöglichkeiten strukturierter darzustellen.";
            $html .= "<li>An jede Veranstaltung können bis zu 5 Dateien angehängt werden. Dabei ist einzeln einstellbar, wer diese Dateien herunterladen darf (jeder, registrierte Benutzer, Benutzer die die Veranstaltung gebucht haben, Benutzer die die Veranstaltung bezahlt haben). Über die Parameter kann die max. Größe und die erlaubten Dateitypen festgelegt werden.";
            $html .= "<li>Die Veranstaltungsleitung kann nun auch HTML-Code enthalten, um z.B. einen Link auf ein Benutzerprofil zu ermöglichen.";
            $html .= "<li>Für jeden Bereich (Veranstaltungen, Meine Buchungen, Meine Angebote) können in den Einstellungen die Module der oberen Auswahlzeile (Anzahl, Suche, Kategorien, ...) festgelegt werden. Auch das Ausblenden der Auswahlzeile ist möglich.";
            $html .= "<li>In der Detailansicht kann eine Kalender-Datei im ICAL-Format heruntergeladen werden. Damit kann der Benutzer die Veranstaltungen in seinen Kalender (z.B. Outlook) eintragen lassen (Einstellung in den Parametern).";
            $html .= "<li>Das Anmelden und Abmelden an die joomla!-Webseite kann nun direkt in Matukio erfolgen (Einstellung in den Parametern).";
            $html .= "<li>Es ist möglich, Vorlagen für Veranstaltungen anzulegen und zu verwalten.";
            $html .= "<li>In den Einstellungen kann festgelegt werden, ab welchem Level ein Benutzer im Frontend Veranstaltungen eingeben darf.";
            $html .= "<li>Der CSV-Download klappte nicht richtig, wenn im Datensatz eine Eurozeichen (€) angezeigt wurde. Das lag an der Umsetzung von UTF-8 in ISO-8559-1. Daher wird nun als Standard-Codierung für die CSV-Datei ISO-8559-15 verwendet, falls in den Einstellungen keine andere Kodierung angegeben wurde.";
            $html .= "<li>Beim ersten Aufruf des Ausdrucks der Veranstaltungsübersicht wurden immer fünf statt der in den Einstellungen vorgegebenen Anzahl der Veranstaltungen ausgedruckt.";
            $html .= "<li>Beim Zurücksetzen der Übersicht wurde die Anzahl der angezeigten Veranstaltungen immer auf fünf gesetzt. Nun wird die in den Einstellungen angegebene Anzahl verwendet.";
            $html .= "<li>Beim Beginn, beim Ende und beim Anmeldeschluss einer Veranstaltung kann angegeben werden, ob die eingegebene Zeit angezeigt werden soll. So lassen sich Missverständnisse z.B. bei Veranstaltungen mit offenem Ende vermeiden.";
            $html .= "<li>In der Benachrichtigungs-E-Mails wird die Buchungs-ID angezeigt.";
            $html .= "<li>Die Anzahl der der eingebbaren Zeichen des Veranstaltungstitels wurde auf 255 erhöht.";
            $html .= "<li>Bei jedem Eingabefeld kann angegeben werden, ob es in den Teilnehmerübersichten angezeigt werden soll.";
            $html .= "<li>Einige zwingende Angaben wurden zu optionalen Angaben geändert (Leitung, Zielgruppe).";
            $html .= "<li>Für jedes Eingabefeld kann ein Erläuterungstext angegeben werden.";
            $html .= "<li>Die Zahl der optionalen Eingabefelder wurde auf 20 erhöht.";
            $html .= "<li>Die Veranstaltungen können auch in einem RSS-Feed veröffentlicht werden.";
            $html .= "<li>Die Veranstaltungsnummer kann frei vergegeben werden.";
            $html .= "<li>Auf der Veranstaltungsübersicht werden alle Veranstaltungen angezeigt, die noch nicht beendet wurden, falls der Anmeldeschluss nach dem Veranstaltungsbeginn liegt. Dadurch ist es möglich, auch noch Plätze bei bereits laufenden Veranstaltungen zu buchen.";
            $html .= "<li>Das Grundlayout wurde überarbeitet. Es werden die grundlegenden Elemente des Templates übernommen (Schriftart, Verweisfarben, etc.). Natürlich ist es nach wie vor über die CSS-Datei auf eigene Bedürfnisse anpassbar.";
            $html .= "<li>Für Webseiten mit dunklem Template wurde ein dunkles Layout ergänzt, das in den Backendparametern statt des hellen Layouts gewählt werden kann.";
            $html .= "</ul>";
            $html .= "</td>";
            break;
        default:
            $html .= "<tr><td colspan=\"2\">";
            $html .= "Please fill in the parameters first.<p>";
            $html .= "Matukio has been released under the <a href=\"http://www.gnu.org/licenses/gpl.html\" target=\"_new\">GNU general public license</a>.<p>";
            $html .= "</td>";
            break;
    }
    $html .= "</tr></tbody></table></div>";
    echo $html;
}

?>
