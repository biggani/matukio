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

class TableNumber extends JTable
{

    public function __construct(&$db)
    {
        parent::__construct( '#__matukio_number', 'id', $db );
    }

}
