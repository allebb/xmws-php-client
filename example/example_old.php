<?php

require "vendor/autoload.php";
use Zpanelx\XmwsClient as xmwsclient;

// We create a new instance of the class.
$xmws = new xmwsclient();


// URL to the ZPanel server (with or without the trailing slash)
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
} else {
    echo "Something appeared to go wrong! The webservice reported response code: <strong>".$ws_handle['response']."</strong>, The human readable version of this error is: '<strong>" .$ws_handle['data']. "</strong>'";
}

// This can be used to debug, this shows the values of the response.
/*$xmws->ShowXMLAsArrayData($xmws->Request($auto_prepared_xml));*/


// Another example of how to quickly grab everything is like so:-
/*$another_xmws_instance = new xmwsclient();
$another_xmws_instance->InitRequest('http://localhost/zpanelx/', 'test', 'TestMe', 'ee8795c8c53bfdb3b2cc595186b68912');
$another_xmws_instance->SetRequestData('some_example_variable_data_here');
$response_data = $another_xmws_instance->ResponseToArray($another_xmws_instance->Request($another_xmws_instance->BuildRequest()));

echo "<strong>Response code:</strong> " .$response_data['response']. " <strong>the data response is:</strong> " .$response_data['data']. "";
 */
?>
