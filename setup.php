<?php
function plugin_version_tag() {
   return array('name'       => __('Tag Management', 'tag'),
            'version'        => '1.0',
            'author'         => 'Emmanuel Haguet - <a href="http://www.teclib.com">Teclib\'</a>',
            'homepage'       => 'http://www.teclib.com',
            'license'        => '',
            'minGlpiVersion' => "0.84");
}

/**
 * Check plugin's prerequisites before installation
 */
function plugin_tag_check_prerequisites() {
   if (version_compare(GLPI_VERSION,'0.84','lt') || version_compare(GLPI_VERSION,'0.85','ge')) {
      echo __('This plugin requires GLPI >= 0.84 and GLPI < 0.85', 'tag');
   } else {
      return true;
   }
   return false;
}

/**
 * Check plugin's config before activation
 */
function plugin_tag_check_config($verbose=false) {
   return true;
}

function getItemtypes() {
   return array('Computer', 'Monitor', 'Software', 'Peripheral', 'Printer', 'SLA', 'Link', 
               'Cartridgeitem', 'Consumableitem', 'Phone', 'Ticket', 'Problem', 'TicketRecurrent', 
               'Budget', 'Supplier', 'Contact', 'Contract', 'Document', 'Reminder', 'RSSFeed', 'User',
               'Group', 'Profile', 'Location', 'ITILCategory', 'NetworkEquipment', ); //, 'KnowbaseItem'
}

function plugin_init_tag() {
   global $PLUGIN_HOOKS;
   
   $PLUGIN_HOOKS['csrf_compliant']['tag'] = true;

   // charge chosen component when needed
   $PLUGIN_HOOKS['add_css']['tag'][] = "lib/chosen/chosen.css";
   if (strpos($_SERVER['REQUEST_URI'], ".form.php?id=") !== false) {
      $PLUGIN_HOOKS['add_javascript']['tag'] = array('lib/chosen/chosen.native.js', 'js/show_tags.js');
   }
   
   Plugin::registerClass('PluginTagTagItem',
            array('addtabon' => array('PluginTagTag')));
   
   foreach (getItemtypes() as $itemtype) {
      $PLUGIN_HOOKS['pre_item_update']['tag'][$itemtype] = 'plugin_pre_item_update_tag';
   }

}
