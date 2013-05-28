<?php
/**
 * Tiles
 * @package Joomla!
 * @Copyright (C) 2012 - Yves Hoppe - compojoom.com
 * @All rights reserved
 * @Joomla! is Free Software
 * @Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
 * @version $Revision: 0.9.0 beta $
 **/
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of Matukio component
 */
class com_matukioInstallerScript
{
    /*
	 * The release value to be displayed and checked against throughout this file.
	 */
    private $release = '1.0';
    private $minimum_joomla_release = '2.5.6';

    private $installationQueue = array(
        // modules => { (folder) => { (module) => { (position), (published) } }* }*
        'modules' => array(
            'site' => array(
                'mod_matukio' => array('left', 0),
                'mod_matukio_booking' => array('left', 0),
                'mod_matukio_calendar' => array('left', 0),
                'mod_matukio_upcoming' => array('left', 0),
            ),
            'admin' => array(
                "mod_ccc_matukio_icons" => array('ccc_matukio_left', 1),
                "mod_ccc_matukio_newsfeed" => array('ccc_matukio_slider', 1),
                "mod_ccc_matukio_update" => array('ccc_matukio_slider', 1),
                "mod_ccc_matukio_overview" => array('ccc_matukio_slider', 1),
                "mod_ccc_matukio_promotion" => array('ccc_matukio_promotion', 1),
            ),

        ),

        'plugins' => array(
                'plg_search_matukio' => 1,
                'plg_system_compojoom' => 1,
                'plg_payment_alphauserpoints' => 1,
                'plg_payment_amazon' => 1,
                'plg_payment_authorizenet' => 1,
                'plg_payment_bycheck' => 1,
                'plg_payment_byorder' => 1,
                'plg_payment_ccavenue' => 1,
                'plg_payment_jomsocialpoints' => 1,
                'plg_payment_linkpoint' => 1,
                'plg_payment_paypal' => 1,
                'plg_payment_paypalpro' => 1,
                'plg_payment_payu' => 1
        )
    );

    /**
     * method to install the component
     *
     * @param $parent
     * @return void
     */
    public function install($parent)
    {
        $this->parent = $parent;

    }

    /**
     * The joomla framework doesn't tell us if the component has tables filled with data
     */
    private function newInstall()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(*) as count')->from('#__matukio');
        $db->setQuery($query);

        if ($db->loadObject()->count) {
            return false;
        }
        return true;
    }

    /**
     * method to uninstall the component
     *
     * @param $parent
     * @return void
     */
    function uninstall($parent)
    {
        $this->parent = $parent;
        $this->uninstallModules();
        $this->status->plugins = $this->uninstallPlugins($this->installationQueue['plugins']);

        echo $this->displayInfoUninstallation();
    }

    /**
     * method to update the component
     *
     * @param $parent
     * @return void
     */
    public function update($parent)
    {
        $this->parent = $parent;

        if (!$this->newInstall()) {

            $db = JFactory::getDbo();

            // Check which db version
            $query = 'SELECT * FROM ' . $db->nameQuote('#__matukio_settings') . ' WHERE title = ' . $db->Quote('db_version');

            $db->setQuery($query);
            $update = $db->loadObject();

            // Version < 2.0.0
            if (empty($update)) {

                $query = "ALTER TABLE  `#__matukio` ADD  `created_by` INT( 10 ) NOT NULL DEFAULT  '0',
                ADD  `created_by_alias` VARCHAR( 255 ) NOT NULL DEFAULT  '',
                ADD  `created` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00',
                ADD  `modified` DATETIME NOT NULL DEFAULT  '0000-00-00 00:00:00',
                ADD  `modified_by` INT( 10 ) NOT NULL DEFAULT  '0',
                ADD  `group_id` INT( 11 ) NOT NULL DEFAULT  '0',
                ADD  `webinar` TINYINT( 1 ) DEFAULT '0';
                ";

                $db->setQuery($query);
                $db->execute();

                // DB 2.0.0
                $query = "ALTER TABLE  #__matukio_bookings ADD  `newfields` TEXT NULL,
                          ADD  `uuid` VARCHAR(255) NULL DEFAULT  '',
                          ADD  `payment_method` VARCHAR(255) NULL DEFAULT  '',
                          ADD  `payment_number` VARCHAR(255) NULL DEFAULT  '',
                          ADD  `payment_netto` FLOAT( 11, 2 ) NULL DEFAULT  '0.00',
                          ADD  `payment_tax` FLOAT( 11, 2 ) NULL DEFAULT  '0.00',
                          ADD  `payment_brutto` FLOAT( 11, 2 ) NULL DEFAULT  '0.00',
                          ADD  `coupon_code` VARCHAR(255) NULL DEFAULT '',
                          ADD  `checked_in` TINYINT(1) DEFAULT '0';
                ";

                $db->setQuery($query);
                $db->execute();

                $query = "CREATE TABLE IF NOT EXISTS `#__matukio_booking_coupons` (
                              `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                              `code` VARCHAR( 255 ) NOT NULL ,
                              `value` FLOAT( 11.2 ) NOT NULL DEFAULT  '0.00',
                              `procent` TINYINT( 1 ) NOT NULL DEFAULT  '1',
                              `published_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                              `published_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                              `published` TINYINT( 1 ) NOT NULL DEFAULT  '0'
                            ) COMMENT='Coupons';
                ";

                $db->setQuery($query);
                $db->execute();

                $query = "CREATE TABLE IF NOT EXISTS `#__matukio_booking_fields` (
                              `id` int(11) NOT NULL AUTO_INCREMENT,
                              `field_name` varchar(255) NOT NULL,
                              `label` varchar(255) NOT NULL,
                              `default` text,
                              `values` text,
                              `page` tinyint(3) NOT NULL DEFAULT '1',
                              `type` varchar(255) NOT NULL DEFAULT 'text',
                              `required` tinyint(1) NOT NULL DEFAULT '0',
                              `ordering` int(11) NOT NULL DEFAULT '0',
                              `style` text,
                              `published` tinyint(1) NOT NULL DEFAULT '0',
                              PRIMARY KEY (`id`)
                            ) COMMENT='Fields';
                ";

                $db->setQuery($query);
                $db->execute();

                // Settings Reset for version 2.0 and 2.0.1
                $query = "TRUNCATE #__matukio_settings";
                $db->setQuery($query);
                $db->execute();

                // Settings Reset for version 2.0
                $this->dummyContent();
                $this->update22();
            } else if ($update->value == "2.0.0") {
                // Settings Reset for version 2.0 and 2.0.1
                $query = "TRUNCATE #__matukio_settings";
                $db->setQuery($query);
                $db->execute();

                $this->settingsContent();
                $this->update22();
            } else if ($update->value == "2.0.1") {
                $query = "INSERT INTO " . $db->nameQuote('#__matukio_settings') . " (`id`, `title`, `value`, `values`, `type`, `catdisp`) VALUES
                          (66, 'banktransfer_iban', '','', 'text', 'payment'),
                          (67, 'banktransfer_bic', '','', 'text', 'payment');";

                $db->setQuery($query);
                $db->execute();

                $query = "INSERT INTO " . $db->nameQuote('#__matukio_settings') . " (`title`, `value`, `values`, `type`, `catdisp`) VALUES
                          ('oldbooking_redirect_after', 'bookingpage', '{bookingpage=BOOKINGPAGE}{eventpage=EVENTPAGE}{eventlist=EVENTLIST}', 'select', 'advanced');";

                $db->setQuery($query);
                $db->execute();

                $this->updateDBVersion();
                $this->update22();
            } else if ($update->value == "2.0.2") {
                $query = "ALTER TABLE  " . $db->nameQuote('#__matukio') . " ADD `language` VARCHAR( 255 ) NOT NULL DEFAULT '*'";

                $db->setQuery($query);
                $db->execute();

                $query = "INSERT INTO " . $db->nameQuote('#__matukio_settings') . " (`title`, `value`, `values`, `type`, `catdisp`) VALUES
                          ('oldbooking_redirect_after', 'bookingpage', '{bookingpage=BOOKINGPAGE}{eventpage=EVENTPAGE}{eventlist=EVENTLIST}', 'select', 'advanced');";

                $db->setQuery($query);
                $db->execute();

                $this->updateDBVersion();
                $this->update22();
            } else if ($update->value == "2.1.0") {
                $this->updateDBVersion();

                $query = "INSERT INTO " . $db->nameQuote('#__matukio_settings') . " (`title`, `value`, `values`, `type`, `catdisp`) VALUES
                          ('oldbooking_redirect_after', 'bookingpage', '{bookingpage=BOOKINGPAGE}{eventpage=EVENTPAGE}{eventlist=EVENTLIST}', 'select', 'advanced');";

                $db->setQuery($query);
                $db->execute();
                $this->update22();
            } else if ($update->value == "2.1.1") {
                $this->updateDBVersion();

                $query = "INSERT INTO " . $db->nameQuote('#__matukio_settings') . " (`title`, `value`, `values`, `type`, `catdisp`) VALUES
                          ('oldbooking_redirect_after', 'bookingpage', '{bookingpage=BOOKINGPAGE}{eventpage=EVENTPAGE}{eventlist=EVENTLIST}', 'select', 'advanced');";

                $db->setQuery($query);
                $db->execute();
                $this->update22();
            } else if ($update->value == "2.1.2") {
                $this->updateDBVersion();

                $query = "INSERT INTO " . $db->nameQuote('#__matukio_settings') . " (`title`, `value`, `values`, `type`, `catdisp`) VALUES
                          ('oldbooking_redirect_after', 'bookingpage', '{bookingpage=BOOKINGPAGE}{eventpage=EVENTPAGE}{eventlist=EVENTLIST}', 'select', 'advanced');";

                $db->setQuery($query);
                $db->execute();
                $this->update22();
            } else if ($update->value == "2.1.3") {
                $this->updateDBVersion();
                $this->update22();
            } else if ($update->value == "2.1.4") {
                $this->updateDBVersion();
                $this->update22();
            }  else if ($update->value == "2.1.5") {
                $this->updateDBVersion();
                $this->update22();
            } else if ($update->value == "2.1.6") {
                $this->updateDBVersion();
                $this->update22();
            } else if ($update->value == "2.1.7") {
                $this->updateDBVersion();
                $this->update22();
            } else if ($update->value == "2.1.8") {
                $this->updateDBVersion();
                $this->update22();
            } else if ($update->value == "2.1.9") {
                $this->updateDBVersion();
                $this->update22();
            } else if ($update->value == "2.1.10") {
                $this->updateDBVersion();
                $this->update22();
            } else if ($update->value == "2.2.0") {
                // Current release

            }  else {
                // Reinsert settings
                $this->settingsContent();
                $this->templatesContent();
            }
        }
    }

    public function updateDBVersion() {
        $db = JFactory::getDbo();
        $query = 'UPDATE ' . $db->nameQuote('#__matukio_settings') . ' SET value = "2.2.0" WHERE title = ' . $db->Quote('db_version');

        $db->setQuery($query);
        $db->execute();
    }

    public function update22()
    {
        $db = JFactory::getDbo();

        $query = 'UPDATE ' . $db->quoteName('#__matukio_settings') . ' SET value = "2.2.0" WHERE title = ' . $db->Quote('db_version');

        $db->setQuery($query);
        $db->execute();

        $query = "INSERT INTO " . $db->quoteName('#__matukio_settings') . " (`title`, `value`, `values`, `type`, `catdisp`) VALUES
                          ('mat_signature', '<strong>Please do not answer this E-Mail</strong>', '', 'text', 'layout'),
                          ('email_html', '1', '', 'bool', 'layout'),
                          ('export_csv_separator',  ';',  '',  'text',  'advanced'),
                          ('location_image', '1', '', 'bool', 'modernlayout'),
                          ('bookingfield_desc', '0', '', 'bool', 'advanced'),
                          ('navi_eventlist_number', '1', '', 'bool', 'modernlayout'),
                          ('navi_eventlist_search', '1', '', 'bool', 'modernlayout'),
                          ('navi_eventlist_categories', '1', '', 'bool', 'modernlayout'),
                          ('navi_eventlist_types', '1', '', 'bool', 'modernlayout'),
                          ('navi_eventlist_reset', '1', '', 'bool', 'modernlayout');";

        // Todo alter {3=NEVER} Settings
        // UPDATE  `j25d`.`jos_matukio_settings` SET  `catdisp` =  'modernlayout' WHERE  `jos_matukio_settings`.`id` =69;

        $db->setQuery($query);
        $db->execute();

        $query = "ALTER TABLE " . $db->quoteName('#__matukio_bookings')
                    . " ADD `payment_status` VARCHAR( 255 ) NOT NULL DEFAULT 'P' ,
                        ADD `status` TINYINT NOT NULL DEFAULT '0'";
        $db->setQuery($query);
        $db->execute();

        $settings_del = array("certificate_htmlcode", "payment_cash", "payment_banktransfer", "payment_paypal", "payment_invoice");

        $query = "DELETE FROM " . $db->quoteName('#__matukio_settings') . " WHERE title IN (\"" . implode('","', $settings_del) . "\")";  // Delete certificate setting
        $db->setQuery($query);
        $db->execute();

        $query = "ALTER TABLE " . $db->quoteName('#__matukio') . " ADD  `booking_mail` TEXT NOT NULL ,
                                             ADD  `certificate_code` TEXT NOT NULL ,
                                             ADD  `top_event` TINYINT( 1 ) NOT NULL DEFAULT  '0' ,
                                             ADD  `hot_event` TINYINT( 1 ) NOT NULL DEFAULT  '0' ,
                                             ADD  `language` VARCHAR( 255 ) NOT NULL DEFAULT  '*' ,
                                             ADD  `asset_id` INT( 10 ) NOT NULL DEFAULT  '0' ,
                                             ADD  `status` TINYINT NOT NULL DEFAULT  '0'";

        $db->setQuery($query);
        $db->execute();

        $this->templatesContent();
    }

    /**
     * method to run before an install/update/discover method
     *
     * @param $type
     * @param $parent
     * @return void
     */
    public function preflight($type, $parent)
    {
        $jversion = new JVersion();

        // Extract the version number from the manifest file
        $this->release = $parent->get("manifest")->version;

        // Find mimimum required joomla version from the manifest file
        $this->minimum_joomla_release = $parent->get("manifest")->attributes()->version;

        if (version_compare($jversion->getShortVersion(), $this->minimum_joomla_release, 'lt')) {
            Jerror::raiseWarning(null, 'Cannot install com_matukio in a Joomla release prior to '
                . $this->minimum_joomla_release);
            return false;
        }

        // abort if the component being installed is not newer than the currently installed version
        if ($type == 'update') {
            $oldRelease = $this->getParam('version');
            $rel = $oldRelease . ' to ' . $this->release;
            if (version_compare($this->release, $oldRelease, 'lt')) {
                Jerror::raiseWarning(null, 'Incorrect version sequence. Cannot upgrade ' . $rel);
                return false;
            }
        }
    }

    /**
     * method to run after an install/update/discover method
     *
     * @param $type
     * @param $parent
     * @return void
     */
    public function postflight($type, $parent)
    {

        $jlang = JFactory::getLanguage();
        $path = $parent->getParent()->getPath('source') . '/administrator';
        $jlang->load('com_matukio.sys', $path, 'en-GB', true);
        $jlang->load('com_matukio.sys', $path, $jlang->getDefault(), true);
        $jlang->load('com_matukio.sys', $path, null, true);

        if ($type == 'install') {
            if ($this->newInstall()) {
                $this->dummyContent();
            }
        }

        // let us install the modules
        $this->installModules();
        $this->installPlugins($this->installationQueue['plugins']);

        echo $this->displayInfoInstallation();

    }

    private function uninstallModules()
    {
        if (count($this->installationQueue['modules'])) {
            $db = JFactory::getDbo();
            foreach ($this->installationQueue['modules'] as $folder => $modules) {
                if (count($modules)) {

                    foreach ($modules as $module => $modulePreferences) {
                        // Find the module ID
                        $db->setQuery('SELECT `extension_id` FROM #__extensions WHERE `element` = '
                            . $db->Quote($module) . ' AND `type` = "module"');

                        $id = $db->loadResult();
                        // Uninstall the module
                        $installer = new JInstaller;
                        $result = $installer->uninstall('module', $id, 1);
                        $this->status->modules[] = array('name' => $module, 'client' => $folder, 'result' => $result);
                    }
                }
            }
        }
    }

    public function uninstallPlugins($plugins)
    {
        $db = JFactory::getDbo();
        $status = array();

        foreach ($plugins as $plugin => $published) {
            $parts = explode('_', $plugin);
            $pluginType = $parts[1];
            $pluginName = $parts[2];
            $db->setQuery('SELECT `extension_id` FROM #__extensions WHERE `type` = "plugin" AND `element` = ' . $db->Quote($pluginName) . ' AND `folder` = ' . $db->Quote($pluginType));

            $id = $db->loadResult();

            if ($id) {
                $installer = new JInstaller;
                $result = $installer->uninstall('plugin', $id, 1);
                $status[] = array('name' => $plugin, 'group' => $pluginType, 'result' => $result);
                $this->status->plugins[] = array('name' => $plugin, 'group' => $pluginType, 'result' => $result);
            }
        }

        return $status;
    }

    private function installModules()
    {
        $src = $this->parent->getParent()->getPath('source');
        // Modules installation
        if (count($this->installationQueue['modules'])) {
            foreach ($this->installationQueue['modules'] as $folder => $modules) {
                if (count($modules)) {
                    foreach ($modules as $module => $modulePreferences) {
                        // Install the module
                        if (empty($folder)) {
                            $folder = 'site';
                        }
                        $path = "$src/modules/$module";
                        if ($folder == 'admin') {
                            $path = "$src/administrator/modules/$module";
                        }
                        if (!is_dir($path)) {
                            continue;
                        }
                        $db = JFactory::getDbo();
                        // Was the module alrady installed?
                        $sql = 'SELECT COUNT(*) FROM #__modules WHERE `module`=' . $db->Quote($module);
                        $db->setQuery($sql);
                        $count = $db->loadResult();
                        $installer = new JInstaller;
                        $result = $installer->install($path);
                        $this->status->modules[] = array('name' => $module, 'client' => $folder, 'result' => $result);
                        // Modify where it's published and its published state
                        if (!$count) {
                            list($modulePosition, $modulePublished) = $modulePreferences;
                            $sql = "UPDATE #__modules SET position=" . $db->Quote($modulePosition);
                            if ($modulePublished) $sql .= ', published=1';
                            $sql .= ', params = ' . $db->quote($installer->getParams());
                            $sql .= ' WHERE `module`=' . $db->Quote($module);
                            $db->setQuery($sql);
                            $db->execute();

//	                        get module id
                            $db->setQuery('SELECT id FROM #__modules WHERE module = ' . $db->quote($module));
                            $moduleId = $db->loadObject()->id;

                            // insert the module on all pages, otherwise we can't use it
                            $query = 'INSERT INTO #__modules_menu(moduleid, menuid) VALUES (' . $db->quote($moduleId) . ' ,0 );';
                            $db->setQuery($query);

                            $db->execute();
                        }
                    }
                }
            }
        }
    }

    public function installPlugins($plugins)
    {
        $src = $this->parent->getParent()->getPath('source');

        $db = JFactory::getDbo();
        $status = array();

        foreach ($plugins as $plugin => $published) {
            $parts = explode('_', $plugin);
            $pluginType = $parts[1];
            $pluginName = $parts[2];

            $path = $src . "/plugins/$pluginType/$pluginName";

            $query = "SELECT COUNT(*) FROM  #__extensions WHERE element=" . $db->Quote($pluginName) . " AND folder=" . $db->Quote($pluginType);

            $db->setQuery($query);
            $count = $db->loadResult();

            $installer = new JInstaller;
            $result = $installer->install($path);
            $status[] = array('name' => $plugin, 'group' => $pluginType, 'result' => $result);

            if ($published && !$count) {
                $query = "UPDATE #__extensions SET enabled=1 WHERE element=" . $db->Quote($pluginName) . " AND folder=" . $db->Quote($pluginType);
                $db->setQuery($query);
                $db->query();
            }
        }

        return $status;
    }

    /**
     * Insert the templates Data into DB
     */
    private function templatesContent() {
        $db = JFactory::getDbo();

        $query = "INSERT INTO `#__matukio_templates` (`id`, `tmpl_name`, `category`, `subject`, `value`, `value_text`, `default`, `modified_by`, `published`) VALUES
            (1, 'mail_booking', 0, '##COM_MATUKIO_EVENT## MAT_EVENT_SEMNUM: MAT_EVENT_TITLE', '<p>##COM_MATUKIO_THANK_YOU_FOR_YOUR_BOOKING##<br /><br /> ##COM_MATUKIO_BOOKING_DETAILS##:<br />MAT_BOOKING_ALL_DETAILS_HTML<br /><br /> ##COM_MATUKIO_EVENT_DETAILS##:<br />MAT_EVENT_ALL_DETAILS_HTML<br /> <br /> MAT_SIGNATURE</p>', '##COM_MATUKIO_THANK_YOU_FOR_YOUR_BOOKING##\r\n\r\n##COM_MATUKIO_BOOKING_DETAILS##:\r\n\r\nMAT_BOOKING_ALL_DETAILS_TEXT\r\n\r\n##COM_MATUKIO_EVENT_DETAILS##:\r\n\r\nMAT_EVENT_ALL_DETAILS_TEXT\r\n\r\nMAT_SIGNATURE\r\n ', '', 0, 1),
            (2, 'mail_booking_canceled_admin', 0, '##COM_MATUKIO_BOOKING_CANCELED## MAT_EVENT_SEMNUM: MAT_EVENT_TITLE (MAT_BOOKING_NUMBER)', '<p>##COM_MATUKIO_THE_ADMIN_CANCELED_THE_BOOKING_OF_FOLLOWING##<br /> <br /> MAT_BOOKING_ALL_DETAILS_HTML <br /> MAT_SIGNATURE</p>', '##COM_MATUKIO_THE_ADMIN_CANCELED_THE_BOOKING_OF_FOLLOWING##\r\n\r\nMAT_BOOKING_ALL_DETAILS_TEXT\r\n\r\nMAT_SIGNATURE', '', 0, 1),
            (3, 'mail_booking_canceled', 0, '##COM_MATUKIO_BOOKING_CANCELED## MAT_EVENT_SEMNUM: MAT_EVENT_TITLE (MAT_BOOKING_NUMBER)', '<p>##COM_MATUKIO_YOU_HAVE_CANCELLED## ##COM_MATUKIO_BOOKING_FOR_EVENT_CANCELLED##<br /> <br /> MAT_BOOKING_ALL_DETAILS_HTML <br /><br /> MAT_SIGNATURE</p>', '##COM_MATUKIO_YOU_HAVE_CANCELLED## ##COM_MATUKIO_BOOKING_FOR_EVENT_CANCELLED##\r\n\r\nMAT_BOOKING_ALL_DETAILS_HTML\r\n\r\nMAT_SIGNATURE', '', 0, 1),
            (4, 'export_csv', 1, 'ID', '''MAT_BOOKING_NUMBER'';''MAT_EVENT_TITLE'';MAT_CSV_BOOKING_DETAILS', '', '', 0, 1),
            (5, 'export_signaturelist', 1, '##COM_MATUKIO_SIGNATURE_LIST##', '<p>MAT_NR MAT_BOOKING_NUMBER MAT_BOOKING_FIRSTNAME MAT_BOOKING_LASTNAME MAT_SIGN</p>', '<table class=\"mat_table\" style=\"width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td class=\"key\" width=\"150px\"><strong>##COM_MATUKIO_NR##:</strong></td>\r\n<td>MAT_EVENT_NUMBER</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_FIELDS_TITLE##:</strong></td>\r\n<td>MAT_EVENT_TITLE</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_BEGIN##:</strong></td>\r\n<td>MAT_EVENT_BEGIN</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_END##:</strong></td>\r\n<td>MAT_EVENT_END</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_FEES##:</strong></td>\r\n<td>MAT_EVENT_FEES</td>\r\n</tr>\r\n</tbody>\r\n</table>', '', 0, 1),
            (6, 'export_participantslist', 1, '##COM_MATUKIO_PARTICIPANTS_LIST##', '<table class=\"mat_table\" style=\"width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_NAME##:</strong></td>\r\n<td>MAT_BOOKING_NAME</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_EMAIL##:</strong></td>\r\n<td>MAT_BOOKING_EMAIL </td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_BOOKING_NUMBER##:</strong></td>\r\n<td>MAT_BOOKING_NUMBER </td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_STATUS##:</strong></td>\r\n<td>MAT_BOOKING_STATUS </td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_BOOKEDNR##:</strong></td>\r\n<td>MAT_BOOKING_BOOKEDNR</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_PAYMENT_FEES##:</strong></td>\r\n<td>MAT_BOOKING_FEES_STATUS</td>\r\n</tr>\r\n<tr>\r\n<td style=\"text-align: center;\" colspan=\"2\">MAT_BOOKING_QRCODE_ID<em><br /></em></td>\r\n</tr>\r\n</tbody>\r\n</table>', '<table class=\"mat_table\" style=\"width: 100%;\" border=\"0\">\r\n<tbody>\r\n<tr>\r\n<td class=\"key\" width=\"150px\"><strong>##COM_MATUKIO_NR##:</strong></td>\r\n<td>MAT_EVENT_NUMBER</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_FIELDS_TITLE##:</strong></td>\r\n<td>MAT_EVENT_TITLE</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_BEGIN##:</strong></td>\r\n<td>MAT_EVENT_BEGIN</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_END##:</strong></td>\r\n<td>MAT_EVENT_END</td>\r\n</tr>\r\n<tr>\r\n<td width=\"150px\"><strong>##COM_MATUKIO_FEES##:</strong></td>\r\n<td>MAT_EVENT_FEES</td>\r\n</tr>\r\n</tbody>\r\n</table>', '', 0, 1),
            (7, 'export_certificate', 2, 'E', '<div style=\"position: absolute; top: 0; left: 0; z-index: 0;\"><img src=\"MAT_IMAGEDIRcertificate.png\" border=\"0\" /></div>\r\n<div style=\"position: absolute; top: 0; left: 0; z-index: 1;\">\r\n<table style=\"width: 734pt;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n<tbody>\r\n<tr>\r\n<td rowspan=\"8\" width=\"180pt\" height=\"1080pt\"> </td>\r\n<th width=\"554pt\" height=\"150pt\"><span style=\"color: #330099; font-size: 48pt; font-family: Verdana;\">##COM_MATUKIO_CERTIFICATE##</span></th></tr>\r\n<tr><th width=\"554pt\" height=\"150pt\"><span style=\"color: #000000; font-size: 28pt; font-family: Verdana;\">MAT_BOOKING_NAME</span></th></tr>\r\n<tr>\r\n<td width=\"554pt\" height=\"100pt\"><span style=\"color: #000000; font-size: 24pt; font-family: Verdana;\">##COM_MATUKIO_CERTIFICATE_ATTENDED##</span></td>\r\n</tr>\r\n<tr><th width=\"554pt\" height=\"250pt\"><span style=\"color: #000000; font-size: 28pt; font-family: Verdana;\">MAT_EVENT_TITLE</span></th></tr>\r\n<tr>\r\n<td width=\"554pt\" height=\"230pt\"><span style=\"color: #000000; font-size: 18pt; font-family: Verdana;\">##COM_MATUKIO_BEGIN##: MAT_EVENT_BEGIN</span>\r\n<p style=\"margin-top: 20pt; margin-bottom: 8pt;\"><span style=\"color: #000000; font-size: 18pt; font-family: Verdana;\"><span>##COM_MATUKIO_END##</span>: MAT_EVENT_END</span></p>\r\n<p style=\"margin-top: 20pt; margin-bottom: 8pt;\"><span style=\"color: #000000; font-size: 18pt; font-family: Verdana;\"><span>##COM_MATUKIO_CITY##</span>: MAT_EVENT_LOCATION</span></p>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td width=\"554pt\" height=\"100pt\"><span style=\"color: #000000; font-size: 18pt; font-family: Verdana;\">##COM_MATUKIO_TUTOR##: MAT_EVENT_TEACHER</span></td>\r\n</tr>\r\n<tr>\r\n<td width=\"554pt\" height=\"100pt\"><span style=\"color: #000000; font-size: 18pt; font-family: Verdana;\">##COM_MATUKIO_DATE##: MAT_DATE</span></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>', '', '', 0, 1);
            ";

        $db->setQuery($query);
        $this->status->sql['#__matukio_templates'] = $db->execute();
    }

    private function settingsContent()
    {
        $db = JFactory::getDbo();
        $query = "INSERT INTO `#__matukio_settings` (`id`, `title`, `value`, `values`, `type`, `catdisp`) VALUES
        (1, 'booking_unregistered', '1', '', 'bool', 'basic'),
        (2, 'booking_ownevents', '1', '', 'bool', 'advanced'),
        (3, 'booking_confirmation', '0', '', 'bool', 'basic'),
        (4, 'booking_stornoconfirmation', '1', '', 'bool', 'basic'),
        (5, 'frontend_userprintlists', '1', '', 'bool', 'basic'),
        (6, 'event_template', 'default', '{default=DEFAULT}{modern=MODERN}', 'select', 'layout'),
        (7, 'frontend_userlistscode', '1', '{0=NONE}{1=QR CODES}{2=BARCODE}', 'select', 'layout'),
        (8, 'frontend_usericsdownload', '1', '', 'bool', 'basic'),
        (9, 'frontend_userviewteilnehmer', '0', '{0=NONE}{1=UNREGISTERED}{2=REGISTERED}', 'select', 'basic'),
        (10, 'frontend_teilnehmerviewteilnehmer', '0', '', 'bool', 'basic'),
        (11, 'frontend_teilnehmernametyp', '1', '{0=USERNAME}{1=REALNAME}', 'select', 'basic'),
        (12, 'frontend_ownereditevent', '1', '', 'bool', 'advanced'),
        (13, 'frontend_ratingsystem', '0', '', 'bool', 'basic'),
        (14, 'frontend_certificatesystem', '0', '', 'bool', 'basic'),
        (15, 'frontend_userprintcertificate', '0', '', 'bool', 'advanced'),
        (17, 'sendmail_teilnehmer', '1', '', 'bool', 'basic'),
        (18, 'sendmail_owner', '1', '', 'bool', 'basic'),
        (19, 'sendmail_contact', '1', '', 'bool', 'basic'),
        (21, 'googlemap_booble', '1', '', 'bool', 'layout'),
        (22, 'event_image', '0', '', 'bool', 'layout'),
        (23, 'image_path', 'matukio', '', 'text', 'advanced'),
        (24, 'event_showstatuspictures', '1', '', 'bool', 'layout'),
        (25, 'file_maxsize', '500', '', 'text', 'security'),
        (26, 'file_endings', 'txt zip pdf jpg png gif', '', 'text', 'security'),
        (27, 'event_showinfoline', '1', '', 'bool', 'layout'),
        (28, 'event_statusgraphic', '1', '{0=NONE}{1=AMPEL}{2=SAEULE}', 'select', 'layout'),
        (29, 'event_buttonposition', '1', '{0=TOP}{1=BOTTOM}{2=BOTH}', 'select', 'layout'),
        (30, 'currency_symbol', '$', '', 'text', 'layout'),
        (31, 'dezimal_stellen', '2', '{0=NONE}{1=ONE}{2=TWO}', 'select', 'layout'),
        (32, 'dezimal_trennzeichen', '.', '', 'text', 'layout'),
        (33, 'frontend_usermehrereplaetze', '1', '', 'bool', 'basic'),
        (34, 'booking_edit', '1', '', 'bool', 'basic'),
        (35, 'booking_stornotage', '1', '', 'text', 'advanced'),
        (36, 'event_stopshowing', '0', '{0=START}{1=END}{2=ANMELDESCHLUSS}{3=NEVER}', 'select', 'advanced'),
        (37, 'event_showanzahl', '20', '', 'text', 'layout'),
        (38, 'agb_text', '', '', 'textarea', 'basic'),
        (39, 'frontend_showfooter', '1', '', 'bool', 'layout'),
        (40, 'rss_feed', '1', '', 'bool', 'advanced'),
        (41, 'frontend_unregisteredshowlogin', '0', '', 'bool', 'layout'),
        (42, 'csv_export_charset', 'ISO-8859-15', '', 'text', 'advanced'),
        (43, 'frontend_showownerdetails', '1', '', 'bool', 'basic'),
        (44, 'date_format_small', 'd-m-Y, H:i', '', 'text', 'layout'),
        (45, 'date_format_without_time', 'd-m-Y', '', 'text', 'layout'),
        (46, 'time_format', 'H:i', '', 'text', 'layout'),
        (47, 'date_format', 'l, d. F Y - h:i a', '', 'text', 'layout'),
        (48, 'db_version', '2.2.0', '', 'text', 'hidden'),
        (49, 'oldbookingform', '0', '', 'bool', 'basic'),
        (50, 'paypal_address', 'paypal@compojoom.com', '', 'text', 'payment'),
        (51, 'paypal_currency', 'USD', '', 'text', 'payment'),
        (56, 'payment_coupon', '1', '', 'bool', 'payment'),
        (57, 'banktransfer_account', '', '', 'text', 'payment'),
        (58, 'banktransfer_blz', '', '', 'text', 'payment'),
        (59, 'banktransfer_bank', '', '', 'text', 'payment'),
        (60, 'banktransfer_accountholder', '', '', 'text', 'payment'),
        (61, 'cbt', '', '', 'text', 'payment'),
        (62, 'cpp_header_image', '', '', 'text', 'payment'),
        (63, 'cpp_headerback_color', '', '', 'text', 'payment'),
        (64, 'cpp_headerborder_color', '', '', 'text', 'payment'),
        (65, 'captcha', '0', '', 'bool', 'security'),
        (66, 'banktransfer_iban', '', '', 'text', 'payment'),
        (67, 'banktransfer_bic', '', '', 'text', 'payment'),
        (68, 'frontend_unregisteredshowlogin', '1', '', 'bool', 'layout'),
        (69, 'social_media', '1', '', 'bool', 'modernlayout'),
        (70, 'oldbooking_redirect_after', 'bookingpage', '{bookingpage=BOOKINGPAGE}{eventpage=EVENTPAGE}{eventlist=EVENTLIST}', 'select', 'advanced'),
        (71, 'frontend_topnavshowmodules', 'SEM_NUMBER SEM_SEARCH SEM_CATEGORIES SEM_RESET', '', 'text', 'advanced'),
        (72, 'frontend_topnavbookingmodules', 'SEM_NUMBER SEM_SEARCH SEM_TYPES SEM_RESET', '', 'text', 'advanced'),
        (73, 'frontend_topnavoffermodules', 'SEM_NUMBER SEM_SEARCH SEM_TYPES SEM_RESET', '', 'text', 'advanced'),
        (74, 'mat_signature', '<strong>Please do not answer this E-Mail</strong>', '', 'text', 'layout'),
        (75, 'email_html', '1', '', 'bool', 'layout'),
        (76, 'export_csv_separator',  ';',  '',  'text',  'advanced'),
        (77, 'location_image', '1', '', 'bool', 'modernlayout'),
        (78, 'bookingfield_desc', '0', '', 'bool', 'advanced'),
        (79, 'navi_eventlist_number', '1', '', 'bool', 'modernlayout'),
        (80, 'navi_eventlist_search', '1', '', 'bool', 'modernlayout'),
        (81, 'navi_eventlist_categories', '1', '', 'bool', 'modernlayout'),
        (82, 'navi_eventlist_types', '1', '', 'bool', 'modernlayout'),
        (83, 'navi_eventlist_reset', '1', '', 'bool', 'modernlayout');";

        $db->setQuery($query);
        $this->status->sql['#__matukio_settings'] = $db->execute();
    }

    private function dummyContent()
    {
        $db = JFactory::getDbo();
        $this->settingsContent();

        $query = "INSERT INTO `#__matukio_booking_fields` (`id`, `field_name`, `label`, `default`, `values`, `page`, `type`, `required`, `ordering`, `style`, `published`) VALUES
        (1, 'title', 'COM_MATUKIO_FIELDS_TITLE', 'choose', '{=COM_MATUKIO_FIELD_CHOOSE}{Mr=COM_MATUKIO_FIELD_MR}{Ms=COM_MATUKIO_FIELD_MS}', 1, 'select', 1, 0, NULL, 1),
        (2, 'company', 'COM_MATUKIO_FIELDS_COMPANY', NULL, NULL, 1, 'text', 0, 1, NULL, 1),
        (3, 'firstname', 'COM_MATUKIO_FIELDS_FIRST_NAME', NULL, NULL, 1, 'text', 1, 2, NULL, 1),
        (4, 'lastname', 'COM_MATUKIO_FIELDS_SURNAME', NULL, NULL, 1, 'text', 1, 3, NULL, 1),
        (5, 'spacer', '', NULL, NULL, 1, 'spacer', 0, 4, NULL, 1),
        (6, 'street', 'COM_MATUKIO_FIELDS_STREET', NULL, NULL, 1, 'text', 1, 5, NULL, 1),
        (7, 'zip', 'COM_MATUKIO_FIELDS_ZIP', NULL, NULL, 1, 'text', 1, 6, 'width: 80px;', 1),
        (8, 'city', 'COM_MATUKIO_FIELDS_CITY', NULL, NULL, 1, 'text', 1, 7, NULL, 1),
        (9, 'country', 'COM_MATUKIO_FIELDS_COUNTRY', NULL, NULL, 1, 'text', 1, 7, NULL, 1),
        (10, 'spacer', '', NULL, NULL, 1, 'spacer', 0, 8, NULL, 1),
        (11, 'email', 'COM_MATUKIO_FIELDS_EMAIL', NULL, NULL, 1, 'text', 1, 9, NULL, 1),
        (12, 'phone', 'COM_MATUKIO_FIELDS_PHONE', NULL, NULL, 1, 'text', 0, 10, NULL, 1),
        (13, 'mobile', 'COM_MATUKIO_FIELDS_MOBILE', NULL, NULL, 1, 'text', 0, 11, NULL, 1),
        (14, 'fax', 'COM_MATUKIO_FIELDS_FAX', NULL, NULL, 1, 'text', 0, 13, NULL, 1),
        (15, 'comments', 'COM_MATUKIO_FIELDS_COMMENTS', NULL, NULL, 2, 'textarea', 0, 0, NULL, 1);";

        $db->setQuery($query);
        $this->status->sql['#__matukio_booking_fields'] = $db->execute();
    }

    private function displayInfoUninstallation()
    {

        $html[] = JText::_('COM_MATUKIO_COMPLETE_UNINSTALL');
        $rows = 0;
        $html[] = '<table>';
        if (count($this->status->modules)) {
            $html[] = '<tr>';
            $html[] = '<th>' . JText::_('COM_MATUKIO_MODULE') . '</th>';
            $html[] = '<th>' . JText::_('COM_MATUKIO_CLIENT') . '</th>';
            $html[] = '<th>' . JText::_('COM_MATUKIO_STATUS') . '</th>';
            $html[] = '</tr>';
            foreach ($this->status->modules as $module) {
                $html[] = '<tr class="row' . (++$rows % 2) . '">';
                $html[] = '<td class="key">' . $module['name'] . '</td>';
                $html[] = '<td class="key">' . ucfirst($module['client']) . '</td>';
                $html[] = '<td>';
                $html[] = '<span style="color:' . (($module['result']) ? 'green' : 'red') . '; font-weight: bold;">';
                $html[] = ($module['result']) ? JText::_('COM_MATUKIO_MODULE_UNINSTALLED') : JText::_('COM_MATUKIO_MODULE_COULD_NOT_UNINSTALL');
                $html[] = '</span>';
                $html[] = '</td>';
                $html[] = '</tr>';
            }
        }
        $html[] = '</table>';

        if ($this->status->plugins) {
            $html[] = $this->renderPluginInfoInstall($this->status->plugins);
        }

        return implode('', $html);
    }
    private function displayInfoInstallation()
    {
        if (file_exists(JPATH_ADMINISTRATOR . "/components/com_joomfish/config.joomfish.php")) {
            rename(JPATH_ADMINISTRATOR . "/components/com_matukio/joomfish/jf_matukio.xml", JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/matukio.xml");
        }
        $update = "2.2.0";

        $imagedir = "../media/com_matukio/images/";
        $lang = JFactory::getLanguage();
        $sprache = strtolower(substr($lang->getName(), 0, 2));
        $html[] = "<div style=\"float: right;\"><img src=\"" . $imagedir . "logo.png\" valign=\"middle\"></div>";
        $html[] = "<divs style=\"float: left;\"><table border=\"0\" width=\"100%\"><tbody>";
        $html[] = "<tr><td width=\"18%\"><b>Autor:</b></td><td width=\"80%\">Matukio " . $update . "</td></tr>";
        $html[] = "<tr><td width=\"18%\"><b>Autor:</b></td><td width=\"80%\">Compojoom.com - Yves Hoppe</td></tr>";
        $html[] = "<tr><td width=\"18%\"><b>Internet:</b></td><td width=\"80%\"><a target=\"_blank\" href=\"http://compojoom.com\">http://compojoom.com</a></td></tr>";
        $html[] = "<tr><td width=\"18%\"><b>Version:</b></td><td width=\"80%\">" . $update . "</td></tr>";
        switch ($sprache) {
            case "de":
                $html[] = "<tr><td colspan=\"2\">";
                $html[] = "Mit Matukio haben Sie sich f&uuml;r ein leistungsstarkes Buchungssystem f&uuml;r Ihre joomla!-Seite entschieden. ";
                $html[] = "Egal, ob Sie Fortbildungen anbieten, Ihr Verein Ausfl&uuml;ge veranstaltet oder Sie zu einer Party einladen m&ouml;chten: Mit Matukio ist die Verwaltung der Veranstaltungen kein Problem. <p>";
                $html[] = "Matukio wurde unter der <a href=\"http://www.gnu.org/licenses/gpl.html\" target=\"_new\">GNU General Public License</a> ver&ouml;ffentlicht.<p>";
                $html[] = "<ul>";
                $html[] = "<li>Die grundlegenden Datumsformate werden durch die Sprachdateien festgelegt. Darüberhinaus können Sie aber durch Angaben in den Einstellungen überschrieben werden.";
                $html[] = "<li>Joomfish wird direkt unterstützt.";
                $html[] = "<li>In der Beschreibung können nun Tags steuern, wer bestimmte Textteile angezeigt bekommt. So wird bei der Angabe von [sem_registered] TEXT [/sem_registered] TEXT nur den registrierten Benutzern angezeigt.";
                $html[] = "<li>Die Eingabelder können vorbelegt werden. Dazu musste aber das Steuerformat geändert werden. Es hat nun das Format Bezeichner|Pflichtfeld|Vorgabewert|Feldtyp|Parameter|Parameter|... Alte Veranstaltungen müssen leider angepasst werden.";
                $html[] = "<li>In den Einstellungen kann festgelegt werden, ab wann die aktuellen Kurse nicht mehr angezeigt werden sollen (Beginn, Ende oder Anmeldeschluss der Veranstaltung). Diese Einstellung wird auch im Modul berücksichtigt.";
                $html[] = "<li>Die Sommerzeit wird automatisch berücksichtigt (optional). Damit muss die Zeitzone während der Sommerzeit nicht extra auf +2 gestellt werden. Auch das Modul greift auf diese Einstellung zurück.";
                $html[] = "<li>Die im Textfeld 'Beschreibung' verwendeten Markierungen für die Plugins vom Typ 'Inhalt' werden in HTML-Code umgesetzt.";
                $html[] = "<li>Die Begrenzung der Zusatzfelder auf 120 Zeichen wurde aufgehoben.";
                $html[] = "<li>Das Zahlenformat für die Währung kann festgelegt werden (Dezimalstellen, Tausender-Trennzeichen, Dezimal-Trennzeichen).";
                $html[] = "<li>Bei kostenpflichtigen Veranstaltungen wird der Preis stärker hervorgehoben dargestellt als bisher.";
                $html[] = "<li>Wird die Infozeile in der Übersicht ausgeblendet, so werden auch die freien Plätze in der Detailansicht nicht mehr angezeigt.";
                $html[] = "<li>Beim nachträglichen Ändern einer Veranstaltung wurden die Zugriffe auf 0 zurückgesetzt. Der Fehler ist behoben.";
                $html[] = "<li>Veranstaltungsbuchungen können von den Benutzern nur so lange geändert werden, bis die Buchung als bezahlt markiert wurde. danach sind Änderungen nur noch durch den Veranstalter möglich.";
                $html[] = "<li>Werden bei einer Veranstaltung die maximal buchbaren Plätze auf 0 gesetzt, ist diese nicht mehr online buchbar und dient als Veranstaltungsankündigung.";
                $html[] = "<li>Die Einstellungen im Backend sind nun direkt aufrufbar und nicht mehr über ein Fenster.";
                $html[] = "<li>Für die Teilnehmerübersichten der Benutzer kann zwischen Realnamen und Benutzernamen gewählt werden.";
                $html[] = "<li>Der Eingabebereich der Veranstaltungen wurde aufgeteilt (Grundangaben, Zusatzangaben, Eingabefelder, Dateien), um die inzwischen sehr umfangreichen Eingabemöglichkeiten strukturierter darzustellen.";
                $html[] = "<li>An jede Veranstaltung können bis zu 5 Dateien angehängt werden. Dabei ist einzeln einstellbar, wer diese Dateien herunterladen darf (jeder, registrierte Benutzer, Benutzer die die Veranstaltung gebucht haben, Benutzer die die Veranstaltung bezahlt haben). Über die Parameter kann die max. Größe und die erlaubten Dateitypen festgelegt werden.";
                $html[] = "<li>Die Veranstaltungsleitung kann nun auch HTML-Code enthalten, um z.B. einen Link auf ein Benutzerprofil zu ermöglichen.";
                $html[] = "<li>Für jeden Bereich (Veranstaltungen, Meine Buchungen, Meine Angebote) können in den Einstellungen die Module der oberen Auswahlzeile (Anzahl, Suche, Kategorien, ...) festgelegt werden. Auch das Ausblenden der Auswahlzeile ist möglich.";
                $html[] = "<li>In der Detailansicht kann eine Kalender-Datei im ICAL-Format heruntergeladen werden. Damit kann der Benutzer die Veranstaltungen in seinen Kalender (z.B. Outlook) eintragen lassen (Einstellung in den Parametern).";
                $html[] = "<li>Das Anmelden und Abmelden an die joomla!-Webseite kann nun direkt in Matukio erfolgen (Einstellung in den Parametern).";
                $html[] = "<li>Es ist möglich, Vorlagen für Veranstaltungen anzulegen und zu verwalten.";
                $html[] = "<li>In den Einstellungen kann festgelegt werden, ab welchem Level ein Benutzer im Frontend Veranstaltungen eingeben darf.";
                $html[] = "<li>Der CSV-Download klappte nicht richtig, wenn im Datensatz eine Eurozeichen (€) angezeigt wurde. Das lag an der Umsetzung von UTF-8 in ISO-8559-1. Daher wird nun als Standard-Codierung für die CSV-Datei ISO-8559-15 verwendet, falls in den Einstellungen keine andere Kodierung angegeben wurde.";
                $html[] = "<li>Beim ersten Aufruf des Ausdrucks der Veranstaltungsübersicht wurden immer fünf statt der in den Einstellungen vorgegebenen Anzahl der Veranstaltungen ausgedruckt.";
                $html[] = "<li>Beim Zurücksetzen der Übersicht wurde die Anzahl der angezeigten Veranstaltungen immer auf fünf gesetzt. Nun wird die in den Einstellungen angegebene Anzahl verwendet.";
                $html[] = "<li>Beim Beginn, beim Ende und beim Anmeldeschluss einer Veranstaltung kann angegeben werden, ob die eingegebene Zeit angezeigt werden soll. So lassen sich Missverständnisse z.B. bei Veranstaltungen mit offenem Ende vermeiden.";
                $html[] = "<li>In der Benachrichtigungs-E-Mails wird die Buchungs-ID angezeigt.";
                $html[] = "<li>Die Anzahl der der eingebbaren Zeichen des Veranstaltungstitels wurde auf 255 erhöht.";
                $html[] = "<li>Bei jedem Eingabefeld kann angegeben werden, ob es in den Teilnehmerübersichten angezeigt werden soll.";
                $html[] = "<li>Einige zwingende Angaben wurden zu optionalen Angaben geändert (Leitung, Zielgruppe).";
                $html[] = "<li>Für jedes Eingabefeld kann ein Erläuterungstext angegeben werden.";
                $html[] = "<li>Die Zahl der optionalen Eingabefelder wurde auf 20 erhöht.";
                $html[] = "<li>Die Veranstaltungen können auch in einem RSS-Feed veröffentlicht werden.";
                $html[] = "<li>Die Veranstaltungsnummer kann frei vergegeben werden.";
                $html[] = "<li>Auf der Veranstaltungsübersicht werden alle Veranstaltungen angezeigt, die noch nicht beendet wurden, falls der Anmeldeschluss nach dem Veranstaltungsbeginn liegt. Dadurch ist es möglich, auch noch Plätze bei bereits laufenden Veranstaltungen zu buchen.";
                $html[] = "<li>Das Grundlayout wurde überarbeitet. Es werden die grundlegenden Elemente des Templates übernommen (Schriftart, Verweisfarben, etc.). Natürlich ist es nach wie vor über die CSS-Datei auf eigene Bedürfnisse anpassbar.";
                $html[] = "<li>Für Webseiten mit dunklem Template wurde ein dunkles Layout ergänzt, das in den Backendparametern statt des hellen Layouts gewählt werden kann.";
                $html[] = "</ul>";
                $html[] = "</td>";
                break;
            default:
                $html[] = "<tr><td colspan=\"2\">";
                $html[] = "<strong>Thank you for installing Matukio!</strong><br /><br />Please fill in the Matukio parameters first and create an event category. Matukio needs a Joomla menu link to the eventlist overview (can be hidden) in order to work properply with search engine friendly urls.";
                $html[] = "</td>";
                break;
        }
        $html[] = "</tr></tbody></table></div><div class=\"clr clear\"></div>";


        if (isset($this->status->sql) && count($this->status->sql)) {

            $tables = array();
            foreach ($this->status->sql as $key => $value) {
                if ($value == true) {
                    $tables[] = $key;
                }
            }
            if (count($tables)) {
                $html[] = JText::sprintf('COM_MATUKIO_DEFAULT_SETTINGS_FOR_TABLES', implode(',', $this->status->sql));
            }
        }

        $rows = 0;
        $html[] = '<table>';
        if (count($this->status->modules)) {
            $html[] = '<tr>';
            $html[] = '<th>' . JText::_('COM_MATUKIO_MODULE') . '</th>';
            $html[] = '<th>' . JText::_('COM_MATUKIO_CLIENT') . '</th>';
            $html[] = '<th>' . JText::_('COM_MATUKIO_STATUS') . '</th>';
            $html[] = '</tr>';
            foreach ($this->status->modules as $module) {
                $html[] = '<tr class="row' . (++$rows % 2) . '">';
                $html[] = '<td class="key">' . $module['name'] . '</td>';
                $html[] = '<td class="key">' . ucfirst($module['client']) . '</td>';
                $html[] = '<td>';
                $html[] = '<span style="color:' . (($module['result']) ? 'green' : 'red') . '; font-weight: bold;">';
                $html[] = ($module['result']) ? JText::_('COM_MATUKIO_MODULE_INSTALLED') : JText::_('COM_MATUKIO_MODULE_NOT_INSTALLED');
                $html[] = '</span>';
                $html[] = '</td>';
                $html[] = '</tr>';
            }
        }
        $html[] = '</table>';

        $html[] = $this->renderPluginInfoInstall($this->status->plugins);

        return implode('', $html);
    }

    public function renderModuleInfoInstall($modules) {
        $rows = 0;

        $html = array();
        if (count($modules)) {
            $html[] = '<table class="table">';
            $html[] = '<tr>';
            $html[] = '<th>' . JText::_(strtoupper($this->extension) .'_MODULE') . '</th>';
            $html[] = '<th>' . JText::_(strtoupper($this->extension) .'_CLIENT') . '</th>';
            $html[] = '<th>' . JText::_(strtoupper($this->extension) .'_STATUS') . '</th>';
            $html[] = '</tr>';
            foreach ($modules as $module) {
                $html[] = '<tr class="row' . (++$rows % 2) . '">';
                $html[] = '<td class="key">' . $module['name'] . '</td>';
                $html[] = '<td class="key">' . ucfirst($module['client']) . '</td>';
                $html[] = '<td>';
                $html[] = '<span style="color:' . (($module['result']) ? 'green' : 'red') . '; font-weight: bold;">';
                $html[] = ($module['result']) ? JText::_(strtoupper($this->extension) .'_MODULE_INSTALLED') : JText::_(strtoupper($this->extension) .'_MODULE_NOT_INSTALLED');
                $html[] = '</span>';
                $html[] = '</td>';
                $html[] = '</tr>';
            }
            $html[] = '</table>';
        }


        return implode('', $html);
    }

    public function renderModuleInfoUninstall($modules)
    {
        $rows = 0;
        $html = array();
        if (count($modules)) {
            $html[] = '<table class="table">';
            $html[] = '<tr>';
            $html[] = '<th>' . JText::_(strtoupper($this->extension) . '_MODULE') . '</th>';
            $html[] = '<th>' . JText::_(strtoupper($this->extension) . '_CLIENT') . '</th>';
            $html[] = '<th>' . JText::_(strtoupper($this->extension) . '_STATUS') . '</th>';
            $html[] = '</tr>';
            foreach ($modules as $module) {
                $html[] = '<tr class="row' . (++$rows % 2) . '">';
                $html[] = '<td class="key">' . $module['name'] . '</td>';
                $html[] = '<td class="key">' . ucfirst($module['client']) . '</td>';
                $html[] = '<td>';
                $html[] = '<span style="color:' . (($module['result']) ? 'green' : 'red') . '; font-weight: bold;">';
                $html[] = ($module['result']) ? JText::_(strtoupper($this->extension) . '_MODULE_UNINSTALLED') : JText::_(strtoupper($this->extension) . '_MODULE_COULD_NOT_UNINSTALL');
                $html[] = '</span>';
                $html[] = '</td>';
                $html[] = '</tr>';
            }
            $html[] = '</table>';
        }

        return implode('', $html);
    }

    public function renderPluginInfoInstall($plugins)
    {
        $rows = 0;
        $html[] = '<table class="table">';
        if (count($plugins)) {
            $html[] = '<tr>';
            $html[] = '<th>' . JText::_(strtoupper($this->extension) . '_PLUGIN') . '</th>';
            $html[] = '<th>' . JText::_(strtoupper($this->extension) . '_GROUP') . '</th>';
            $html[] = '<th>' . JText::_(strtoupper($this->extension) . '_STATUS') . '</th>';
            $html[] = '</tr>';
            foreach ($plugins as $plugin) {
                $html[] = '<tr class="row' . (++$rows % 2) . '">';
                $html[] = '<td class="key">' . $plugin['name'] . '</td>';
                $html[] = '<td class="key">' . ucfirst($plugin['group']) . '</td>';
                $html[] = '<td>';
                $html[] = '<span style="color: ' . (($plugin['result']) ? 'green' : 'red') . '; font-weight: bold;">';
                $html[] = ($plugin['result']) ? JText::_(strtoupper($this->extension) . '_PLUGIN_INSTALLED') : JText::_(strtoupper($this->extension) . 'PLUGIN_NOT_INSTALLED');
                $html[] = '</span>';
                $html[] = '</td>';
                $html[] = '</tr>';
            }
        }
        $html[] = '</table>';

        return implode('', $html);
    }

    public function renderPluginInfoUninstall($plugins)
    {
        $rows = 0;
        $html = array();
        if (count($plugins)) {
            $html[] = '<table class="table">';
            $html[] = '<tbody>';
            $html[] = '<tr>';
            $html[] = '<th>Plugin</th>';
            $html[] = '<th>Group</th>';
            $html[] = '<th></th>';
            $html[] = '</tr>';
            foreach ($plugins as $plugin) {
                $html[] = '<tr class="row' . (++$rows % 2) . '">';
                $html[] = '<td class="key">' . $plugin['name'] . '</td>';
                $html[] = '<td class="key">' . ucfirst($plugin['group']) . '</td>';
                $html[] = '<td>';
                $html[] = '	<span style="color:' . (($plugin['result']) ? 'green' : 'red') . '; font-weight: bold;">';
                $html[] = ($plugin['result']) ? JText::_(strtoupper($this->extension) . '_PLUGIN_UNINSTALLED') : JText::_(strtoupper($this->extension) . '_PLUGIN_NOT_UNINSTALLED');
                $html[] = '</span>';
                $html[] = '</td>';
                $html[] = ' </tr> ';
            }
            $html[] = '</tbody > ';
            $html[] = '</table > ';
        }

        return implode('', $html);
    }


    /*
     * get a variable from the manifest file (actually, from the manifest cache).
     */
    private function getParam($name)
    {
        $db = JFactory::getDbo();
        $db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = ' . $db->quote('com_matukio'));
        $manifest = json_decode($db->loadResult(), true);
        return $manifest[$name];
    }
}