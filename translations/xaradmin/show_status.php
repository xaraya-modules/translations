<?php
/**
 * File: $Id$
 *
 * Status report for the current translation
 *
 * @package modules
 * @subpackage translations
 * @copyright (C) 2004 Marcel van der Boom
 * @link http://www.xaraya.com
 * 
 * @author Marcel van der Boom <marcel@xaraya.com>
*/

function &count_entries(&$entries)
{
    $counts['numEntries']         = 0;
    $counts['numEmptyEntries']    = 0;
    $counts['numKeyEntries']      = 0;
    $counts['numEmptyKeyEntries'] = 0;
    foreach($entries as $entry) {
        $counts['numEntries']         += $entry['numEntries'];
        $counts['numEmptyEntries']    += $entry['numEmptyEntries'];
        $counts['numKeyEntries']      += $entry['numKeyEntries'];
        $counts['numEmptyKeyEntries'] += $entry['numEmptyKeyEntries']; 
    }
    return $counts;
}

function translations_admin_show_status()
{
    // Security Check
    if(!xarSecurityCheck('AdminTranslations')) return;
   
    $data = array();

    // core
    xarSessionSetVar('translations_dnName','core');
    xarSessionSetVar('translations_dnType',XARMLS_DNTYPE_CORE);
    $tmp =& translations_create_trabar('core','core');
    $coreentries =& count_entries(&$tmp['entrydata']);
    unset($tmp);

    // modules
    if(!($mods = xarModAPIFunc('modules','admin','GetList')))  return;
    $modentries = array();
    xarSessionSetVar('translations_dnType',XARMLS_DNTYPE_MODULE);
    $mod_totalentries = 0; $mod_untranslated = 0; $mod_keytotalentries = 0; $mod_keyuntranslated =0;
    foreach($mods as $mod) {
        $modname = $mod['name'];
        xarSessionSetVar('translations_dnName',$modname);
        xarSessionSetVar('translations_modid',$mod['id']);
        
        $tmp =&  translations_create_trabar('modules',$modname);
        $modentries[$modname] =& count_entries(&$tmp['entrydata']);
        unset($tmp);
        $mod_totalentries += $modentries[$modname]['numEntries'];
        $mod_untranslated += $modentries[$modname]['numEmptyEntries'];
        $mod_keytotalentries += $modentries[$modname]['numKeyEntries'];
        $mod_keyuntranslated += $modentries[$modname]['numEmptyKeyEntries'];
    }

     // themes
     if(!($themes = xarModAPIFunc('themes','admin','GetList')))  return;
     $themeentries = array();
     xarSessionSetVar('translations_dnType',XARMLS_DNTYPE_THEME);
     $theme_totalentries = 0; $theme_untranslated =0; $theme_keytotalentries = 0; $theme_keyuntranslated = 0;
     foreach($themes as $theme) {
         $themename = $theme['directory'];
         xarSessionSetVar('translations_dnName',$themename);
         xarSessionSetVar('translations_themeid',$theme['id']);

         $tmp =&  translations_create_trabar('themes',$themename);
         $themeentries[$themename] =& count_entries(&$tmp['entrydata']);
         unset($tmp);
         $theme_totalentries += $themeentries[$themename]['numEntries'];
         $theme_untranslated += $themeentries[$themename]['numEmptyEntries'];
         $theme_keytotalentries += $themeentries[$themename]['numKeyEntries'];
         $theme_keyuntranslated += $themeentries[$themename]['numEmptyKeyEntries'];
     }

     $data['coreentries'] = $coreentries;
     $data['modentries']   = $modentries;
     $data['mod_totalentries'] = $mod_totalentries;
     $data['mod_untranslated'] = $mod_untranslated;
     $data['mod_keytotalentries'] = $mod_keytotalentries;
     $data['mod_keyuntranslated'] = $mod_keyuntranslated;
     $data['theme_totalentries'] = $theme_totalentries;
     $data['theme_untranslated'] = $theme_untranslated;
     $data['theme_keytotalentries'] = $theme_keytotalentries;
     $data['theme_keyuntranslated'] = $theme_keyuntranslated;
     $data['themeentries'] = $themeentries;
     return $data;
}


?>