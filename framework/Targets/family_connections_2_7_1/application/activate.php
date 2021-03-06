<?php
/**
 * Activate
 *  
 * PHP versions 4 and 5
 *  
 * @category  FCMS
 * @package   FamilyConnections
 * @author    Ryan Haudenschilt <r.haudenschilt@gmail.com> 
 * @copyright 2008 Haudenschilt LLC
 * @php       4.4
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @link      http://www.familycms.com/wiki/
 * @since     1.7
 */
header("Cache-control: private");

require 'fcms.php';

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.T_('lang').'" lang="'.T_('lang').'">
<head>
<title>'.getSiteName().' - '.T_('powered by').' '.getCurrentVersion().'</title>
<link rel="stylesheet" type="text/css" href="themes/fcms-core.css" />
</head>
<body>';

if (isset($_GET['uid']))
{
    // Check for valid user id
    if (is_numeric($_GET['uid']))
    {
        echo '
    <div id="login_box">
        <h1 id="reset_header">'.T_('Account Activation').'</h1>';

        $sql = "SELECT `activate_code` 
                FROM `fcms_users` 
                WHERE `id` = '".cleanInput($_GET['uid'], 'int')."'";

        $result = mysql_query($sql);
        if (!$result)
        {
            displaySQLError('Check Code Error', __FILE__.' ['.__LINE__.']', $sql, mysql_error());
            echo '</body></html>';
            exit();
        }

        $row = mysql_fetch_array($result);

        // User supplied an activation code
        if (isset($_GET['code']))
        {
            // Code is valid
            $code = cleanInput($_GET['code']);
            if ($row['activate_code'] == $code)
            {
                $sql = "UPDATE `fcms_users` 
                        SET `activated` = 1, `joindate` = NOW() 
                        WHERE `id` = '".cleanInput($_GET['uid'], 'int')."'";
                if (!mysql_query($sql))
                {
                    displaySQLError('Activation Error', __FILE__.' ['.__LINE__.']', $sql, mysql_error());
                    echo '</body></html>';
                    exit();
                }

                echo '
        <p><b>'.T_('Alright!').'</b></p>
        <p>'.T_('Your account is now active.').'</p>
        <p><a href="index.php">'.T_('You can now login and begin using the site.').'</a></p>
        <meta http-equiv=\'refresh\' content=\'5;URL=index.php\'>';

            }
            // Code is invalid
            else
            {
                echo '
        <p><b>'.T_('Invalid Activation Code!').'</b></p>
        <p>'.T_('Your account could NOT be activated').'</p>';
            }

        }
        // No code
        else
        {
            echo '
        <p><b>'.T_('Invalid Activation Code!').'</b></p>
        <p>'.T_('Your account could NOT be activated').'</p>';

        }

        echo  '
        <br/>
    </div>';

    }
    // Invalid user id
    else
    {
        echo T_('Access Denied');
    }

}
// No uid supplied
else
{
    echo T_('Access Denied');
}

echo '
</body>
</html>';
