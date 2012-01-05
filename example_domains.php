<?php

require 'xmwsclient.class.php';


/**
 * If we recieve a 'Delete' request, we'll request that it is deleted from the server!
 */
if(isset($_GET['delete'])){
    // A domain has been requested to be deleted, lets delete it then!
    $xmws = new xmwsclient();
    $xmws->InitRequest('http://localhost/zpanelx/', 'domains', 'DeleteDomain', 'ee8795c8c53bfdb3b2cc595186b68912');
    $xmws->SetRequestData('<domainid>' .$_GET['delete']. '</domainid>');

    $response_array = $xmws->XMLDataToArray($xmws->Request($xmws->BuildRequest()));
    if($response_array['xmws']['response']=='1101'){
    echo "<strong>The domain (Domain id: ".$response_array['xmws']['content']['domainid'].") has been deleted as requested!</strong>";
    } else {
        echo "An error occured requesting the domain deleteion, the web service reported: " .$response_array['xmws']['content']. "";
    }
}





$xmws = new xmwsclient();
$xmws->InitRequest('http://localhost/zpanelx/', 'domains', 'GetAllDomains', 'ee8795c8c53bfdb3b2cc595186b68912');
$xmws->SetRequestData('');

$response_array = $xmws->XMLDataToArray($xmws->Request($xmws->BuildRequest()), 0);



if ($response_array['xmws']['response'] <> 1101) {
    echo "Something appeared to go wrong! The webservice reported response code: <strong>" . $alldomains_xml['response'] . "</strong>, The human readable version of this error is: '<strong>" . $alldomains_xml['data'] . "</strong>'";
} else {
    echo "<h1>All domains on your server!</h1>";
    echo "This is a simple example on how to grab and display all domains on a ZPanelX server.";
    echo "<table border=\"1\">";
    echo "<tr><th>Domain</th><th>Data directory</th><th>Is active?</th><th>Date created</th><tr>";
    foreach ($response_array['xmws']['content']['domain'] as $rows) {
        if($rows['active']==0){
            $isactive = 'No';
        } else {
             $isactive = 'Yes!';
        }
        echo "<tr><td>" . $rows['domain'] . "</td><td>" . $rows['homedirectory'] . "</td><td>" . $isactive. "</td><td>" . date('c',$rows['datecreated']) . "</td><td><a href=\"?delete=" .$rows['id']. "\">Delete</a></td></tr>";
    }
    echo "</table>";
    echo "Thats all folks! If your interested in seeing what the RAW data looks like, <a href=\"?raw\">click here</a>!";

    if(isset($_GET['raw'])){
        echo "<h1>The mechanics of the communication..</h1>";
    echo "<h2>This is what the RAW XML response looks like, this comes from the ZPanelX server based on your request...</h2><textarea cols=\"100\" rows=\"30\"> " .$xmws->Request($xmws->BuildRequest()). "</textarea>";
    echo "<h2>This is what the PHP array looks like, we take the RAW XML response and then convert it to a PHP array :)</h2><pre>";
    print_r($response_array);
    echo "</pre>";
    
    
    }
}

?>
