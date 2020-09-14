<?php
#########################
# upgrade from 1 to 2
# 16/12/2014
# this upgrade script from 1.3 to 1.4
# this will rename config.toplinks to config.schema_version
# add option table
########################

define('SCRIPT_TO_VERSION', '1.4');
$contents[] = "========================";
$contents[] = "upgrade script " . SCRIPT_SCHEMA_VERSION . " at " . date('r');
$contents[] = 'alter table config';
$sql_config = 'ALTER TABLE config CHANGE COLUMN toplinks schema_version VARCHAR(10);';
$db->rawQuery($sql_config);

if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] =  'done';
// update schema version with the current
$contents[] = 'update schema version with the current version';
$data = Array ('schema_version' => '2');
$db->update ('config', $data) ;
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}
$contents[] = 'done';
$contents[] = 'create table option';
$sql_option = 'CREATE TABLE IF NOT EXISTS `options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(100) NOT NULL,
  `option_value` longtext NOT NULL,
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;';
$db->rawQuery($sql_option);
if (strlen($db->getLastError()) > 2 ) {
    $errors[] = $db->getLastError();
}$contents[] = 'done';
// insert data to option table
$link_window = Array ("option_name" => "link_window",
               "option_value" => "1",
);
$id = $db->insert('options', $link_window);
$second_page = Array ("option_name" => "second_page",
               "option_value" => "1",
);
$id = $db->insert('options', $second_page);
if($id)
   $contents[] = 'inserted options values to option table with id='.$id;
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
                                            <p>upgrade the script to ' . SCRIPT_TO_VERSION . ' version was successful</p>
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