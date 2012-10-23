<?php
/**
 * Hotspots - Adminstrator
 * @package Joomla!
 * @Copyright (C) 2009 Yves Hoppe - lunajoom.de
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.3 beta $
 **/
defined( '_JEXEC' ) or die ( 'Restricted access' );

// Include library dependencies
jimport('joomla.filter.input');

class TableSettings extends JTable
{
    var $id 				= null;
    var $title 				= null;
    var $value				= null;

    function __construct(&$db)
    {
        parent::__construct( '#__matukio_settings', 'id', $db );
    }
}
?>