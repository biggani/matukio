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
$task = JFactory::getApplication()->input->get('task', null);

// checking if task is set
if (!$task) {
    echo "No task specified";
    return;
}

if ($task == 'validate_coupon') {
    $coupon = JFactory::getApplication()->input->get('code', '', 'string');

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
} else if ($task == 'get_total') {
    $total = 0.00;

    $nrbooked = JFactory::getApplication()->input->getInt('nrbooked', 1);
    $single_fee = JFactory::getApplication()->input->get('fee', 0);
    $coupon_code = JFactory::getApplication()->input->get('code', '', 'string');

    $total = $nrbooked * $single_fee;

    if(!empty($coupon_code)){
        // Get coupon value
        $cdate = new DateTime();

        $db = JFactory::getDBO();
        $query= $db->getQuery(true);
        $query->select('*')->from('#__matukio_booking_coupons')
            ->where('code = ' . $db->quote($coupon_code) . ' AND published = 1 AND published_up < '
            . $db->quote($cdate->format('Y-m-d H:i:s')) . " AND published_down > " . $db->quote($cdate->format('Y-m-d H:i:s')));

        //echo $query;
        $db->setQuery( $query );
        $coupon = $db->loadObject();

        //var_dump($coupon);

        if(!empty($coupon)){
            if($coupon->procent == 1){
                // Get a procent value
                $total = round($total * ((100 - $coupon->value) / 100), 2);
            } else {
                $total = $total - $coupon->value;
            }
        }
    }

    echo MatukioHelperSettings::getSettings('currency_symbol', '$') . " " .MatukioHelperUtilsEvents::getFormatedCurrency($total);
} else if ($task == 'route_link') {
   $link = JFactory::getApplication()->input->get('link', '', 'string');

   if(empty($link)){
       return;
   }

//   $needles = "";
//   if ($item = MatukioHelperRoute::_findItem($needles)) {
//       $link .= '&Itemid=' . $item->id;
//   }

    $db   = JFactory::getDBO();
    //$lang = JFactory::getLanguage()->getTag();
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
    $start =JFactory::getApplication()->input->get('startDate','');
    $end = JFactory::getApplication()->input->get('endDate','');

    $db = JFactory::getDBO();

    // Check category ACL rights
    $groups	= implode(',', JFactory::getUser()->getAuthorisedViewLevels());
    $query = $db->getQuery(true);
    $query->select("id, access")->from("#__categories")->where(array ("extension = " . $db->quote(JFactory::getApplication()->input->get('option')),
        "published = 1", "access in (" . $groups . ")"));

    $db->setQuery($query);
    $cats = $db->loadObjectList();

    $allowedcat = array();

    foreach ((array)$cats AS $cat) {
        $allowedcat[] = $cat->id;
    }

    $where[] = "a.catid IN (" . implode(',', $allowedcat) . ")";

    $db->setQuery('SELECT * FROM #__matukio WHERE begin > '. $db->Quote( $start ) .' AND begin < '
                . $db->Quote( $end ) .' AND published = 1 AND catid IN (' . implode(',', $allowedcat)
                . ') ORDER BY begin asc' );

    $rows = $db->loadObjectList();

    $events = array();

    foreach($rows as $row) {
        $begin = JHTML::_('date', $row->begin, 'Y-m-d\TH:i:s') ."-00:00"; //. JHTML::_('date', $row->begin, 'H:i');
        $end = JHTML::_('date', $row->end, 'Y-m-d\TH:i:s') . "-00:00"; //. JHTML::_('date', $row->begin, 'H:i');

        // Link
        $eventid_l = $row->id.':'.JFilterOutput::stringURLSafe($row->title);
        $catid_l = $row->catid.':'.JFilterOutput::stringURLSafe(MatukioHelperCategories::getCategoryAlias($row->catid));

        $link = JRoute::_(MatukioHelperRoute::getEventRoute($eventid_l, $catid_l), false);

        //$link = MatukioHelperUtilsEvents::getRoutedLink("index.php?option=com_matukio&view=event&event_id=" . $row->id);
        $title = '<a href="' . $link . '">'.$row->title.'</a>';
        $events[] = array('title' => $title, 'start' => $begin, 'end' => $end, 'location' => $row->place);
    }


    JResponse::setHeader('Content-Disposition', 'attachment;filename=element.json"');
    echo json_encode($events);
}

jexit();