<?php

require_once 'xmwsclient.class.php';

// We create a new instance of the class.
$xmws = new xmwsclient();


// With of without the trailing slash!
$xmws->wsurl = 'http://localhost/zpanelx/';

// The server won't help you unless you can authenticate with the correct server API key (the key is a Zpanel setting, use ctrl_options::GetOption('apikey') to find out what yours is)
$xmws->serverkey = 'ee8795c8c53bfdb3b2cc595186b68912';

// Specify the 'module' where the web service class exists in.
$xmws->module = 'test';

// Specify what method you want to run and get the response back from.
$xmws->method = 'TestMe';

// If the web service class requires authentication then you need to specify the username and password! - This is a ZPanel user account!
$xmws->username = '';
$xmws->password = '';

// Finally we now send over any variables that can be used by the Zpanel module to help with the request (eg. a string, a comma seperated list, binary data etc.) if not required then it can be left blank.
$xmws->SetRequestData('Bobby Allen, how come you ask? and what is yours?');
// Alternatively you can set it using the normal class variable set like: $xmws->data = '';
// Now we just prepare the XML, this can be built dynamically (like in this example) or you can specify raw XML if you wish.
$auto_prepared_xml = $xmws->BuildRequest();

// So here we are sending the request and converting the response into a PHP array so we can access the data in an easy to handle format.
// By just requesting $repsonse = $xmws->Request($auto_prepared_xml); you would recieve the raw request. (XML data)
$ws_handle = $xmws->ResponseToArray($xmws->Request($auto_prepared_xml));


if ($ws_handle['response'] == 1101) {
    echo "<table><tr><th>Server response data</th></tr><tr><td>" . $ws_handle['data'] . "</td></tr></table>";
}

// This can be used to debug, this shows the values of the response.
$xmws->ShowXMLAsArrayData($xmws->Request($auto_prepared_xml));
?>
