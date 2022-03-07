<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsonxmlController extends Controller
{
    public function xmlToArray(Request $request, $xmlstring='')
	{	
	  $xmlstring =  $request->xmlstring;
	  $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
	  $json = json_encode($xml);
	  $array = json_decode($json,TRUE);
	  return $array;
	}
	
	
	function removeNamespaceFromXML( $xml )
	{
		// Because I know all of the the namespaces that will possibly appear in 
		// in the XML string I can just hard code them and check for 
		// them to remove them
		$toRemove = ['rap', 'turss', 'crim', 'cred', 'j', 'rap-code', 'evic'];
		// This is part of a regex I will use to remove the namespace declaration from string
		$nameSpaceDefRegEx = '(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?';

		// Cycle through each namespace and remove it from the XML string
	   foreach( $toRemove as $remove ) {
			// First remove the namespace from the opening of the tag
			$xml = str_replace('<' . $remove . ':', '<', $xml);
			// Now remove the namespace from the closing of the tag
			$xml = str_replace('</' . $remove . ':', '</', $xml);
			// This XML uses the name space with CommentText, so remove that too
			$xml = str_replace($remove . ':commentText', 'commentText', $xml);
			// Complete the pattern for RegEx to remove this namespace declaration
			$pattern = "/xmlns:{$remove}{$nameSpaceDefRegEx}/";
			// Remove the actual namespace declaration using the Pattern
			$xml = preg_replace($pattern, '', $xml, 1);
		}

		// Return sanitized and cleaned up XML with no namespaces
		return $xml;
	}

	function namespacedXMLToArray(Request $request)
	{
		
		$xml =  $request->xmlstring;
		
		return json_decode(json_encode(simplexml_load_string($this->removeNamespaceFromXML($xml))), true);
	}
	
	function Parse2(Request $request, $xmlnode = '') 
	{
		
		
		$xmlnode =  $request->xmlstring;


		$xmldata = simplexml_load_string($xmlnode);
		  
		// Encode this xml data into json 
		// using json_encoe function
		$jsondata = json_encode($xmldata);
		   print_r($jsondata);
		//return $jsondata;
			
			
			/*
		$root = (func_num_args() > 1 ? false : true);
		$jsnode = array();

		if (!$root) {
			if (count($xmlnode->attributes()) > 0){
				$jsnode["$"] = array();
				foreach($xmlnode->attributes() as $key => $value)
					$jsnode["$"][$key] = (string)$value;
			}

			$textcontent = trim((string)$xmlnode);
			if (count($textcontent) > 0)
				$jsnode["_"] = $textcontent;

			foreach ($xmlnode->children() as $childxmlnode) {
				$childname = $childxmlnode->getName();
				if (!array_key_exists($childname, $jsnode))
					$jsnode[$childname] = array();
				array_push($jsnode[$childname], xml2js($childxmlnode, true));
			}
			return $jsnode;
		} else {
			$nodename = $xmlnode->getName();
			$jsnode[$nodename] = array();
			array_push($jsnode[$nodename], xml2js($xmlnode, true));
			return json_encode($jsnode);
		}
		
		*/
	}
	
	
	
	
		
	public function Parse (Request $request) {
		
		
		$url =  $request->url;
        $fileContents= file_get_contents($url);
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        $json = json_encode($simpleXml);
		//$json = $simpleXml;
		
	
		$json2 = json_decode($json, true);
		$rep1 = '{"@attributes":{"version":"2.0"}';
		$json3 = $json2['channel'];
		$data = array();
		$data['data'][0] = $json3;
		//array_push($data['data'] , $json3);
		//print_r($data);
		
       return $data;
    }
	
	
	
}
