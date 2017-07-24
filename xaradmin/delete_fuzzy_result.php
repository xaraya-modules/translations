<?php
/**
 * Translations Module
 *
 * @package modules
 * @subpackage translations module
 * @category Third Party Xaraya Module
 * @version 2.0.0
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @link http://xaraya.com/index.php/release/77.html
 * @author Marco Canini
 * @author Marcel van der Boom <marcel@xaraya.com>
 */

function translations_admin_delete_fuzzy_result()
{
    // Security Check
    if(!xarSecurityCheck('AdminTranslations')) return;

    if (!xarVarFetch('dnType','int',$dnType)) return;
    if (!xarVarFetch('dnName','str:1:',$dnName)) return;
    if (!xarVarFetch('extid','int',$extid)) return;

    $locale = translations_working_locale(); 
    $backend = xarMod::apiFunc('translations', 'admin', 'create_backend_instance',
                             array('interface' => 'ReferencesBackend', 'locale' => $locale)); 
    if (!isset($backend)) return;

    $ctxType = xarMLSContext::getContextTypePrefix($dnType);
    $ctxName = 'fuzzy';

    if ($backend->bindDomain($dnType,$dnName)) {
        $fileName = $backend->findContext($ctxType, $ctxName);
        unlink($fileName);
    }

    xarController::redirect(xarModURL('translations', 'admin', 'translate', 
        array('dnType' => $dnType,
              'dnName' => $dnName,
              'extid' => $extid)));

    return true;
}

?>