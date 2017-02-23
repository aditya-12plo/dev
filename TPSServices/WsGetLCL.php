 <?php
$options = array('location' => 'http://10.1.6.112/cfs-center/TPSServices/server_lcl.php', 
                  'uri' => 'http://10.1.6.112/');
$api = new SoapClient(NULL, $options);
echo $api->GetUbahStatus(test1,test);
 ?>