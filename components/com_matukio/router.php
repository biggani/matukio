<?php
/**
 * Matukio
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 1.0.0 $
 *
 *
 * Help god.. help :)
 * This is a mess and it will get worse
 **/

defined( '_JEXEC' ) or die ( 'Restricted access' );

function MatukioBuildRoute(&$query)
{
    static $items;
    $segments	= array();
    $itemid		= null;

    $segments = array();

    if(isset($query['view']))
    {
        $segments[] = $query['view'];
        unset($query['view']);
    };

    if(isset($query['task']))
    {
        $segments[] = $query['task'];
        unset($query['task']);
    };

    if(isset($query['catid']))
    {
        $segments[] = $query['catid'];
        unset($query['catid']);
    };

    if(isset($query['id']))
    {
        $segments[] = $query['id'];
        unset($query['id']);
    };

    if(isset($query['cid']))
    {
        $segments[] = $query['cid'];
        unset($query['cid']);
    };

    if(isset($query['art']))
    {
        $segments[] = $query['art'];
        unset($query['art']);
    };

    if(isset($query['tmpl']))
    {
        $segments[] = $query['tmpl'];
        unset($query['tmpl']);
    };

    if(isset($query['format']))
    {
        $segments[] = $query['format'];
        unset($query['format']);
    };

    if(isset($query['event_id']))
    {
        $segments[] = $query['event_id'];
        unset($query['event_id']);
    };

    if(isset($query['uid']))
    {
        $segments[] = $query['uid'];
        unset($query['uid']);
    };

    if(isset($query['booking_id']))
    {
        $segments[] = $query['booking_id'];
        unset($query['booking_id']);
    };

    if(isset($query['return']))
    {
        $segments[] = $query['return'];
        unset($query['return']);
    };

    if(isset($query['search']))
    {
        $segments[] = $query['search'];
        unset($query['search']);
    };

    if(isset($query['limit']))
    {
        $segments[] = $query['limit'];
        unset($query['limit']);
    };

    return $segments;
}

/**
 * Method to parse Route
 * @param array $segments
 */
function MatukioParseRoute($segments) {
    $vars = array();
    $menu =& JSite::getMenu();
    $item =& $menu->getActive();

    // Count route segments
    $count = count($segments);

    //Standard routing
    if(!isset($item))  {
        if($count == 4 ) {
            $vars['view']  = $segments[$count - 3];

            // UNDER TESTING
        } else if($count == 3) {
            $vars['view']    = $segments[$count - 2];
            $segments[$count - 2] = $segments[$count - 3];
            // UNDER TESTING
        } else {
            $vars['view'] = 'eventlist';
        }
        $vars['catid']	= $segments[$count - 2];//-4
        $vars['id']    	= $segments[$count - 1];


    } else {
        // TODO Comment this
       // var_dump($segments);

        $view = $segments[0];

        switch($view) {

            default:
                if($count == 1 && is_numeric($view)) {
                    $vars['art']	= $segments[0];
                } else if(empty($view)) {
                    $vars['view'] = "eventlist";
                } else {
                    $vars['view'] = $segments[0];
                }

                if($count == 2) {
                    $vars['art']	= $segments[0];
                    $catid = explode(':', $segments[1]);
                    $vars['catid']	= $catid[0];
                }
                break;


            case 'eventlist' :

                if($count == 1) {
                    $vars['view'] = 'eventlist';
                }

                if($count == 2) {
                    $vars['view'] = 'eventlist';
                    $vars['art']	= $segments[1];
                }

                if($count == 3) {
                    $vars['view'] = 'eventlist';
                    $vars['art']	= $segments[2];
                    $catid = explode(':', $segments[1]);
                    $vars['catid']	= $catid[0];
                }

                //index.php/matukio-test/eventlist/78/0?search=&amp;limit=10
                //index.php?option=com_matukio&view=eventlist&art=" + art + "&catid=" + catid + "&search=" +search + "&limit=" + lim

                if($count == 4) {
                    $vars['view'] = 'eventlist';
                    $vars['catid']	= $segments[1];
                    $vars['art']	= $segments[2];
                    //$vars['search']	= $segments[3];
                    $vars['limit']	= $segments[3];
                }

                if($count == 5) {
                    $vars['view'] = 'eventlist';
                    $vars['catid']	= $segments[1];
                    $vars['art']	= $segments[2];
                    $vars['search']	= $segments[3];
                    $vars['limit']	= $segments[4];
                }

                break;

            case 'map' :

                // array(3) { [0]=> string(3) "map" [1]=> string(9) "component" [2]=> string(1) "2" }
                $vars['view'] = 'map';
                $vars['tmpl'] = $segments[1];
                $vars['event_id'] = $segments[2];
                break;

            case 'matukio' :
                $vars['view'] = 'matukio';
                $vars['task'] = $segments[1];
                break;

            case 'ics' :
                // array(3) { [0]=> string(3) "ics" [1]=> string(1) "1" [2]=> string(3) "raw" } asdf
                $vars['view'] = 'ics';
                $vars['cid'] = $segments[1];
                $vars['format'] = $segments[2];
                break;

            case 'event'   :
                // array(2) { [0]=> string(5) "event" [1]=> string(1) "1" }

                // $link = 'index.php?option=com_matukio&view=event&catid=' . $catid . '&id=' . $id . "&art=" . $art;
                // $link = JRoute::_("index.php?option=com_matukio&view=event&id=" . $eventid . "&art=2");
                // $detaillink = JRoute::_("index.php?option=com_matukio&view=event&id="
                // . $this->kurs->id. "&art=3&uid=" . $row->sid);


                if($count == 1) {
                    $vars['view'] 	= 'event';
                }

                if($count == 2) {
                    $vars['view'] 	= 'event';
                    $id = explode(':', $segments[1]);
                    $vars['id'] 	= $id[0];
                }

                //event/cancelBooking/62

                if($count == 3) {
                    $vars['view'] 	= 'event';
                    $id = explode(':', $segments[1]);
                    $vars['id'] 	= $id[0];
                    $vars['art']	= $segments[2];
                }

                if($count == 4) {
                    $vars['view'] 	= 'event';
                    $id = explode(':', $segments[2]);
                    $vars['id'] 	= $id[0];
                    $catid = explode(':', $segments[1]);
                    $vars['catid']	= $catid[0];
                    $vars['art']	= $segments[3];
                }

                // event/78-testycat/4-myfucking-event-2/3/21
                // $link = JRoute::_('index.php?option=com_matukio&view=event&catid=' .$catid . '&id=' . $row->id. "&art=1&uid=" . $neu->id); // TODO Expand
                // event/0/1/1/32
                if($count == 5) {
                    $vars['view'] 	= 'event';
                    $id = explode(':', $segments[2]);
                    $vars['id'] 	= $id[0];
                    $catid = explode(':', $segments[1]);
                    $vars['catid']	= $catid[0];
                    $vars['art']	= $segments[3];
                    $vars['uid']    = $segments[4];
                }

                break;

            case 'participants'   :
                // $viewteilnehmerlink = JRoute::_("index.php?option=com_matukio&view=participants&cid=" . $row->id . "&art=" . $this->art);

                // $bookingcancellink = JRoute::_("index.php?option=com_matukio&view=participants&task=cancelBooking&uid=". $row->sid . "&cid=" . $this->kurs->id);

                // participants/toogleStatusPayed?booking_id=21
                // svar_dump($segments);
                if($count == 1) {
                    $vars['view'] 	= 'participants';
                }

                if($count == 2) {
                    $vars['view'] 	= 'participants';
                    $vars['cid'] 	= $segments[$count-1];
                }

                if($count == 3) {
                    $vars['art']	= $segments[$count-1];
                    $vars['cid']	= $segments[$count-2];
                    $vars['view']	= 'participants';
                }

                if($count == 4) {
                    $vars['view']	= 'participants';
                    $vars['task']   = $segments[1];
                    $vars['cid']   = $segments[2];
                    $vars['uid']   = $segments[3];
                }
                break;

            case 'createevent'   :
                if($count == 1) {
                    $vars['view'] 	= 'createevent';
                }

                //http://web822.webbox239.server-home.org/bmver03/events/createevent/duplicateEvent/5

                if($count == 2) {
                    $vars['view'] 	= 'createevent';
                    $vars['cid'] 	= $segments[$count-1];
                }

                if($count == 3) {
                    $vars['view'] 	= 'createevent';
                    $vars['cid']    = $segments[2];
                    $vars['task']	= $segments[1];
                }
                break;

            case 'bookevent'   :
                if($count == 1) {
                    $vars['view'] 	= 'bookevent';
                }

                if($count == 2) {
                    $vars['view'] 	= 'bookevent';
                    $cid = explode(':', $segments[1]);
                    $vars['cid']	= $cid[0];
                }

//                if($count >= 2) {
//                    $vars['view'] 	= 'bookevent';
//                    $cid = explode(':', $segments[1]);
//                    $vars['cid']	= $cid[0];
//                }

                if($count == 3) {
                    $vars['view'] 	= 'bookevent';
                    $vars['task']	=  $segments[1];
                    $vars['cid']	=  $segments[2];
                }

                if($count == 4) {
                    $vars['view'] 	= 'bookevent';
                    $vars['task']	=  $segments[1];
                    $vars['booking_id']	=  $segments[2];
                    $vars['return']	=  $segments[3];
                }


                // bookevent/cancelBooking/62

                break;

            case 'paypalpayment' :

                if($count == 1) {
                    $vars['view'] 	= 'paypalpayment';
                }

                if($count == 2) {
                    $vars['view'] 	= 'paypalpayment';
                    $vars['booking_id']	= $segments[1];
                }

                if($count > 2) {
                    $vars['view'] 	= 'paypalpayment';
                    $vars['booking_id']	= $segments[1];
                }

                break;

            case 'callback' :

                if($count == 1) {
                    $vars['view'] 	= 'callback';
                }

                if($count == 2) {
                    $vars['view'] 	= 'callback';
                    $vars['booking_id']	= $segments[1];
                }

                if($count > 2) {
                    $vars['view'] 	= 'callback';
                    $vars['task'] 	= $segments[1];
                    $vars['booking_id']	= $segments[2];
                }

                break;

            case 'editbooking' :

                if($count == 1) {
                    $vars['view'] 	= 'editbooking';
                }

                if($count == 2) {
                    $vars['view'] 	= 'editbooking';
                    $vars['booking_id']	= $segments[1];
                }

                if($count == 3) {
                    $vars['view'] 	= 'editbooking';
                    $vars['task']	= $segments[1];
                    $vars['cid']	= $segments[2];
                }

                break;

            case 'rss'   :
                if($count == 1) {
                    $vars['view'] 	= 'rss';
                }

                if($count == 2) {
                    $vars['view'] 	= 'rss';
                    $vars['catid'] 	= $segments[$count-1];
                }

                break;

            case 'upcomingevents':
                if($count == 1) {
                    $vars['view'] 	= 'upcomingevents';
                }

                if($count == 2) {
                    $vars['view'] 	= 'upcomingevents';
                    $vars['catid'] 	= $segments[$count-1];
                }

                break;

            case 'calendar':
                if($count == 1) {
                    $vars['view'] 	= 'calendar';
                }

                if($count == 2) {
                    $vars['view'] 	= 'calendar';
                    $vars['catid'] 	= $segments[$count-1];
                }

                break;

            }

    }

    return $vars;
}
?>