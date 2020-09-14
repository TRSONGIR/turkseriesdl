<?php

##################################################
# Created by Abdul Ibrahim
# Aug 25, 2016 1:50:04 PM
# website http://www.abdulibrahim.com/
# migrate to version 5
# i will to the following :
#   change option_value	varchar(150) instead of tinint(1)
#   add value to options table
#                        (recaptcha_status  1)
#                        (recaptcha_Site_key )
#                        (recaptcha_Secret_key) 
##################################################
$contents[] = "========================";
$contents[] = "upgrade script " . SCRIPT_SCHEMA_VERSION . " at " . date('r');
$contents[] = 'convert options=>option_value to varchar(150) instead of tinint(1)  begins';
$sql_CONVERT =            "ALTER TABLE `options` MODIFY `option_value` varchar(150)";
$db->rawQuery($sql_CONVERT);

if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
#######################
$contents[] = '======================';
$contents[] = 'Add 3 values to the option table`';
$sql_INSERT = "INSERT INTO options (option_name,option_value) VALUES
        ('recaptcha_status', '1'),
        ('recaptcha_Site_key','enter your recaptcha Site key read documentation'),
        ('recaptcha_Secret_key','enter your recaptcha_Secret_key read documentation')";
$db->rawQuery($sql_INSERT);

if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
// update schema version with the current
$contents[] = 'update schema version with the current version';
$data = Array ('schema_version' => SCRIPT_SCHEMA_VERSION);
$db->update ('config', $data) ;
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
// check if the query didn't run successfully

if ( sizeof($errors) > 0 ) {
    echo 'we got error in upgrading the script Please look into upgrade_log.txt file for details';
    $contents[] = 'error reported to the user';
} else {
    echo '            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">    
                                    <div class="panel panel-success">
                                        <div class="panel-heading">installation succeeded
                                        </div>
                                        <div class="panel-body">
                                            <p>upgrade the script to ' . SCRIPT_VERSION . ' version was successful</p>
                                            <p>You will be redirected now to the admin panel</p>
                                            <p>Please wait...</p>    
                                        </div>
                                    </div>  
                            </div> 
                    </div> 
            </div>
            <script>
                window.setTimeout(function () {
                location.href = "../admin";
                }, 5000)  
            </script>
    
';
    $contents[] = 'successful upgrade was successfully reported to the user';
 
}


