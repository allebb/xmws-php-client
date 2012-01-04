<?php

/**
 * The official PHP XMWS API Client
 * @author ballen (ballen@zpanelcp.com)
 * @see https://github.com/bobsta63/XMWS-PHP-API-Client/wiki
 * @version 1.0.0
 */
class xmwsclient {

    public $module = null;
    public $method = null;
    public $username = null;
    public $password = null;
    public $serverkey = null;
    public $wsurl = null;
    public $data = null;

    /**
     * This is a quick way to configure the class variables instead of specifying per line.
     * @return void
     */
    function InitRequest($wsurl, $mod, $met, $key, $user="", $pass="") {
        $this->module = $mod;
        $this->method = $met;
        $this->username = $user;
        $this->password = $pass;
        $this->serverkey = $key;
        $this->wsurl = $wsurl;
        return;
    }

    function SetRequestData($string) {
        $this->data = $string;
    }

    /**
     * Automatically prepares and formats the XMWS XML request message based on your preset variables.
     * @return string The formatted XML message ready to post.
     */
    function BuildRequest() {
        $request_template = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n" .
                "<xmws>" .
                "\t<apikey>" . $this->serverkey . "</apikey>\n" .
                "\t<request>" . $this->method . "</request>\n" .
                "\t<authuser>" . $this->username . "</authuser>\n" .
                "\t<authpass>" . $this->password . "</authpass>\n" .
                "\t<content>" . $this->data . "</content>" .
                "</xmws>";
        return $request_template;
    }

    /**
     * The main Request class that initiates the connection and request to the web service.
     * @param type $post_xml
     * @return type 
     */
    function Request($post_xml) {
        $full_wsurl = $this->wsurl . "/api/" . $this->module;
        return $this->PostRequest($full_wsurl, $post_xml);
    }

    /**
     * This takes a RAW XMWS XML repsonse and converts it to a usable PHP array.
     * @param type $xml
     * @return type 
     */
    function ResponseToArray($xml) {
        return array('response' => $this->GetXMLTagValue($xml, 'response'), 'data' => $this->GetXMLTagValue($xml, 'content'));
    }

    /**
     * Returns the value between a given XML tag.
     * @param string $xml
     * @param type $tag
     * @return type 
     */
    function GetXMLTagValue($xml, $tag) {
        $xml = " " . $xml;
        $ini = strpos($xml, '<' . $tag . '>');
        if ($ini == 0)
            return "";
        $ini += strlen('<' . $tag . '>');
        $len = strpos($xml, '</' . $tag . '>', $ini) - $ini;
        return substr($xml, $ini, $len);
    }

    /**
     * A simple POST class that attempts to POST data simply.
     * @param string $url URL to the XMWS web service controller.
     * @param string $data The data to post.
     * @param string $optional_headers Optional if you need to send additonal headers.
     * @return string The XML repsonse. 
     */
    function PostRequest($url, $data, $optional_headers = null) {
        $params = array('http' => array(
                'method' => 'POST',
                'content' => $data
                ));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            die("Problem reading data from " . $url . "");
        }
        $response = @stream_get_contents($fp);
        if ($response == false) {
            die("Problem reading data from " . $url . "");
        }
        return $response;
    }

    /**
     * Simply outputs the contents of the response as a PHP array (using print_r())
     * @param string $xml 
     */
    function ShowXMLAsArrayData($xml) {
        echo "<pre>";
        print_r($this->ResponseToArray($xml));
        echo "</pre>";
    }
    
    /**
    * A simple way to build an XML section for the <content> tag, perfect for multiple data lines etc.
    * @param string $name The name of the section <tag>.
    * @param array $tags An associated array of the tag names and values to be added.
    * @return string A formatted XML section block which can then be used in the <content> tag if required.
    */
    function NewXMLContentSection($name, $tags){
    $xml = "\t<" . $name . ">\n";
    foreach ($tags as $tagname => $tagval) {
        $xml .="\t\t<" . $tagname . ">" . $tagval . "</" . $tagname . ">\n";
    }
    $xml .= "\t</" . $name . ">\n";
    return $xml;
    }

}

?>
