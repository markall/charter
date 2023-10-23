<?php
/*
*************************************************************************
        MODx Content Management System and PHP Application Framework
        Managed and maintained by Raymond Irving, Ryan Thrash and the
        MODx community
*************************************************************************
        MODx is an opensource PHP/MySQL content management system and content
        management framework that is flexible, adaptable, supports XHTML/CSS
        layouts, and works with most web browsers, including Safari.

        MODx is distributed under the GNU General Public License
*************************************************************************

        MODx CMS and Application Framework ("MODx")
        Copyright 2005 and forever thereafter by Raymond Irving & Ryan Thrash.
        All rights reserved.

        This file and all related or dependant files distributed with this filie
        are considered as a whole to make up MODx.

        MODx is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.

        MODx is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with MODx (located in "/assets/docs/"); if not, write to the Free Software
        Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

        For more information on MODx please visit http://modxcms.com/

**************************************************************************
    Originally based on Etomite by Alex Butter
**************************************************************************
*/

/**
 * Initialize Document Parsing
 * -----------------------------
 */

// is this file included?
if(count(get_included_files())>1) $noparser = true;

// get start time
$mtime = microtime(); $mtime = explode(" ",$mtime); $mtime = $mtime[1] + $mtime[0]; $tstart = $mtime;

// harden it
require_once(dirname(__FILE__).'/manager/includes/protect.inc.php');

// set some settings, and address some IE issues
@ini_set('url_rewriter.tags', '');
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_only_cookies',1);
session_cache_limiter('');
header('P3P: CP="NOI NID ADMa OUR IND UNI COM NAV"'); // header for weird cookie stuff. Blame IE.
header('Cache-Control: private, must-revalidate');
ob_start();
error_reporting(E_ALL & ~E_NOTICE);

/**
 *      Filename: index.php
 *      Function: This file loads and executes the parser. *
 */

define("IN_ETOMITE_PARSER", "true"); // provides compatibility with etomite 0.6 and maybe later versions
define("IN_PARSER_MODE", "true");
define("IN_MANAGER_MODE", "false");

// initialize the variables prior to grabbing the config file
$database_type = '';
$database_server = '';
$database_user = '';
$database_password = '';
$dbase = '';
$table_prefix = '';
$base_url = '';
$base_path = '';

// get the required includes
if($database_user=="") {
        $rt = @include_once(dirname(__FILE__).'/manager/includes/config.inc.php');
        // Be sure config.inc.php is there and that it contains some important values
        if(!$rt || !$database_type || !$database_server || !$database_user || !$dbase) {
        echo "
<style type=\"text/css\">
*{margin:0;padding:0}
body{margin:50px;background:#eee;}
.install{padding:10px;border:5px solid #f22;background:#f99;margin:0 auto;font:120%/1em serif;text-align:center;}
p{ margin:20px 0; }
a{font-size:200%;color:#f22;text-decoration:underline;margin-top: 30px;padding: 5px;}
</style>
<div class=\"install\">
<p>MODx is not currently installed or the configuration file cannot be found.</p>
<p>Do you want to <a href=\"install/index.php\">install now</a>?</p>
</div>";
                exit;
        }
}

// start session
startCMSSession();

// initiate a new document parser
include_once(MODX_MANAGER_PATH.'/includes/document.parser.class.inc.php');
$modx = new DocumentParser;

include_once ($modx->config['base_path'] . "manager/includes/crypt.class.inc.php");

$etomite = &$modx; // for backward compatibility

// set some parser options
$modx->minParserPasses = 1; // min number of parser recursive loops or passes
$modx->maxParserPasses = 10; // max number of parser recursive loops or passes
$modx->dumpSQL = false;
$modx->dumpSnippets = false; // feed the parser the execution start time
$modx->tstart = $tstart;

// Debugging mode:
$modx->stopOnNotice = false;

// Don't show PHP errors to the public
if(!isset($_SESSION['mgrValidated']) || !$_SESSION['mgrValidated']) @ini_set("display_errors","0");

// execute the parser if index.php was not included
//if(!$noparser) $modx->executeParser();


function insertwebuser($username,$password,$email,$fullname,$postcode,$county,$country) {
   global $modx;
    // create the user account
    $sql = "INSERT INTO ".$modx->getFullTableName("web_users")." (username, password)
            VALUES('".$username."', md5('".$password."'));";
    $rs = $modx->db->query($sql);
    if(!$rs){
        $output = webLoginAlert("An error occured while attempting to save the user.").$tpl;
        return;
    }
    // now get the id
    $key=$modx->db->getInsertId();

    // save user attributes
    $sql = "INSERT INTO ".$modx->getFullTableName("web_user_attributes")." (internalKey, fullname, email, zip, state, country)
            VALUES($key, '$fullname', '$email', '$postcode', '$county', '$country');";
    $rs = $modx->db->query($sql);
    if(!$rs){
        $output = webLoginAlert("An error occured while attempting to save the user's attributes.").$tpl;
        return;
    }

    // add user to web groups
    if(count($groups)>0) {
        $ds = $modx->dbQuery("SELECT id FROM ".$modx->getFullTableName("webgroup_names")." WHERE name IN ('".implode("','",$groups)."')");
        if(!$ds) return $modx->webAlert('An error occured while attempting to update user\'s web groups');
        else {
            while ($row = $modx->fetchRow($ds)) {
                $wg = $row["id"];
                $modx->dbQuery("REPLACE INTO ".$modx->getFullTableName("web_groups")." (webgroup,webuser) VALUES('$wg','$key')");
            }
        }
    }

    // invoke OnWebSaveUser event
    $modx->invokeEvent("OnWebSaveUser",
                        array(
                            "mode"         => "new",
                            "userid"       => $key,
                            "username"     => $username,
                            "userpassword" => $password,
                            "useremail"    => $email,
                            "userfullname" => $fullname
                        ));

}

function modxlogin($loginhomeid,$logouthomeid,$pwdreqid,$pwdactid,$logintext,$logouttext,$tpl) {
    # WebLogin 1.0
    # Created By Raymond Irving 2004
    #::::::::::::::::::::::::::::::::::::::::
    # Usage:
    #       Allows a web user to login to the website
    #
    # Params:
    #       &loginhomeid    - (Optional)
    #               redirects the user to first authorized page in the list.
    #               If no id was specified then the login home page id or
    #               the current document id will be used
    #
    #       &logouthomeid   - (Optional)
    #               document id to load when user logs out
    #
    #       &pwdreqid       - (Optional)
    #               document id to load after the user has submited
    #               a request for a new password
    #
    #       &pwdactid       - (Optional)
    #               document id to load when the after the user has activated
    #               their new password
    #
    #       &logintext              - (Optional)
    #               Text to be displayed inside login button (for built-in form)
    #
    #       &logouttext     - (Optional)
    #               Text to be displayed inside logout link (for built-in form)
    #
    #       &tpl                    - (Optional)
    #               Chunk name or document id to as a template
    #
    #       Note: Templats design:
    #                       section 1: login template
    #                       section 2: logout template
    #                       section 3: password reminder template
    #
    #                       See weblogin.tpl for more information
    #
    # Examples:
    #
    #       [[WebLogin? &loginhomeid=`8` &logouthomeid=`1`]]
    #
    #       [[WebLogin? &loginhomeid=`8,18,7,5` &tpl=`Login`]]

    # Set Snippet Paths
    $snipPath = $modx->config['base_path'] . "assets/snippets/";

    # check if inside manager
    if ($m = $modx->insideManager()) {
            return ''; # don't go any further when inside manager
    }

    # deprecated params - only for backward compatibility
    if(isset($loginid)) $loginhomeid=$loginid;
    if(isset($logoutid)) $logouthomeid = $logoutid;
    if(isset($template)) $tpl = $template;

    # Snippet customize settings
    $liHomeId       = isset($loginhomeid)? explode(",",$loginhomeid):array($modx->config['login_home'],$modx->documentIdentifier);
    $loHomeId       = isset($logouthomeid)? $logouthomeid:$modx->documentIdentifier;
    $pwdReqId       = isset($pwdreqid)? $pwdreqid:0;
    $pwdActId       = isset($pwdactid)? $pwdactid:0;
    $loginText      = isset($logintext)? $logintext:'Login';
    $logoutText     = isset($logouttext)? $logouttext:'Logout';
    $tpl            = isset($tpl)? $tpl:"";

    # System settings
    $webLoginMode = isset($_REQUEST['webloginmode'])? $_REQUEST['webloginmode']: '';
    $isLogOut               = $webLoginMode=='lo' ? 1:0;
    $isPWDActivate  = $webLoginMode=='actp' ? 1:0;
    $isPostBack             = count($_POST) && (isset($_POST['cmdweblogin']) || isset($_POST['cmdweblogin_x']));
    $txtPwdRem              = isset($_REQUEST['txtpwdrem'])? $_REQUEST['txtpwdrem']: 0;
    $isPWDReminder  = $isPostBack && $txtPwdRem=='1' ? 1:0;

    $site_id = isset($site_id)? $site_id: '';
    $cookieKey = substr(md5($site_id."Web-User"),0,15);

    # Start processing
    include_once $snipPath."weblogin/weblogin.common.inc.php";
    include_once ($modx->config['base_path'] . "manager/includes/crypt.class.inc.php");

    if ($isPWDActivate || $isPWDReminder || $isLogOut || $isPostBack) {
            # include the logger class
            include_once $modx->config['base_path'] . "manager/includes/log.class.inc.php";
            include_once $snipPath."weblogin/weblogin.processor.inc.php";
    }

    include_once $snipPath."weblogin/weblogin.inc.php";

    # Return
    return $output;
}

?>
