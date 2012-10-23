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
$task = JRequest::GetVar('task', null);

// checking if task is set
if (!$task) {
    echo "No task specified";
    return;
}

if ($task == 'validate_coupon') {
    $coupon = JRequest::getVar('code', '');

    if(empty($coupon)){
        echo "false";
        return;
    }

    $cdate = new DateTime();

    $db = JFactory::getDBO();
    $query= $db->getQuery(true);
    $query->select('*')->from('#__matukio_booking_coupons')
        ->where('code = ' . $db->quote($coupon) . ' AND published = 1 AND published_up < '
        . $db->quote($cdate->format('Y-m-d H:i:s')) . " AND published_down > " . $db->quote($cdate->format('Y-m-d H:i:s')));

    //echo $query;
    $db->setQuery( $query );
    $coupon = $db->loadObject();

    //var_dump($coupon);

    if(empty($coupon)){
        echo "false";
        return;
    }

    echo "true";
} else if ($task == 'route_link') {
   $link = JRequest::getVar('link', '');

   if(empty($link)){
       return;
   }

//   $needles = "";
//   if ($item = MatukioHelperRoute::_findItem($needles)) {
//       $link .= '&Itemid=' . $item->id;
//   }

    $db   =& JFactory::getDBO();
    //$lang =& JFactory::getLanguage()->getTag();
    $uri  = 'index.php?option=com_matukio&view=eventlist';

    //echo $lang;

    $db->setQuery('SELECT id FROM #__menu WHERE link LIKE '. $db->Quote( $uri .'%' ) .' AND published = 1 LIMIT 1' );

    $itemId = ($db->getErrorNum())? 0 : intval($db->loadResult());

   $link = $link . "&Itemid=" . $itemId;
   // Routing of a link
   $link = JRoute::_($link);

   // Get the document object.
   $document = JFactory::getDocument();

   // Set the MIME type for JSON output.
   $document->setMimeEncoding('application/json');

   // Change the suggested filename.
   JResponse::setHeader('Content-Disposition', 'attachment;filename=element.json"');
   $url =  array("link" => $link);

   // Output the JSON data.
   echo json_encode($url);
} else if ($task == 'getcalendar') {
    $start = JRequest::getVar('startDate','');
    $end = JRequest::getVar('endDate','');

    $db   =& JFactory::getDBO();

    $db->setQuery('SELECT * FROM #__matukio WHERE begin > '. $db->Quote( $start ) .' AND begin < '
                . $db->Quote( $end ) .' AND published = 1 ORDER BY begin asc' );

   // echo $db->getQuery();
//    $db->setQuery('SELECT * FROM #__matukio WHERE published = 1 ORDER BY begin asc' );

    $rows = $db->loadObjectList();

    $events = array();

    foreach($rows as $row) {
        $begin = JHTML::_('date', $row->begin, 'Y-m-d\TH:i:s') ."-00:00"; //. JHTML::_('date', $row->begin, 'H:i');
        $end = JHTML::_('date', $row->end, 'Y-m-d\TH:i:s') . "-00:00"; //. JHTML::_('date', $row->begin, 'H:i');

        $link = MatukioHelperUtilsEvents::getRoutedLink("index.php?option=com_matukio&view=event&event_id=" . $row->id);
        $title = '<a href="' . $link . '">'.$row->title.'</a>';
        //$title = $row->title;
        $events[] = array('title' => $title, 'start' => $begin, 'end' => $end, 'location' => $row->place);
    }

//    var_dump($events);
//    die("asdf");

    //[{"title":"Breakfast","start":"2012-07-30T06:00:00-05:00","end":"2012-07-30T07:00:00-05:00","location":""}]
    // $event3 = array('title'=>'Breakfast','start'=>'2012-07-30T06:00:00-05:00','end'=>'2012-07-30T07:00:00-05:00','location'=>'');
    //$begin = JHTML::_('date', $event->begin, MatukioHelperSettings::getSettings('date_format', 'd-m-Y, H:i'));

    JResponse::setHeader('Content-Disposition', 'attachment;filename=element.json"');
    echo json_encode($events);
}

jexit();