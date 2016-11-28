<?php
if (!empty($_POST['operation']) && !empty($_POST['login']) && !empty($_POST['pass'])){
    $requestFileName = 'res/'.$_POST['operation'].'.xml';
    $operation = "/VPartnerGw/".$_POST['operation'];
    $request = file_get_contents($requestFileName);
    $request = passAuthData($request);
    try {
      $client = new SoapClient("https://my.velcom.by/openapi?wsdl");
      $response = $client->__doRequest ($request , "https://my.velcom.by/openapi" , $operation, 1, 0);
    }
    catch (SoapFault $e) {
      echo $e;
    }

    $parser = xml_parser_create();
    xml_parse_into_struct($parser, $response, $vals);
    xml_parser_free($parser);

    require('res/'.$_POST['operation'].'.tpl');
}

function passAuthData($request){
  $request = str_replace('{ISSA_LOGIN}', $_POST['login'], $request);
  $request = str_replace('{ISSA_PASSWORD}', $_POST['pass'], $request);
  return $request;
}
