<?php

/**
 * SoapClient
 * https://ec.europa.eu/taxation_customs/vies/?locale=pl 
 * @category   Lib
 * @package    Mass-Symfonia
 * @author     Rafał Żygadło <rafal@maxkod.pl>
 *
 * @copyright  2020 maxkod.pl
 * @version    1.0
 */


namespace Lib\Soap;
use Lib\FileLog;
use SoapClient;

class MySoapClient
{

   function __construct()
   {
      $url = "https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";
      $this->SoapClient = new SoapClient($url,array('features' =>SOAP_SINGLE_ELEMENT_ARRAYS, 'trace'=>1));
   
   }            
   
   public function GetSoapResponse($method,$params)
   { 
      try 
      {    
           $request = $this->SoapClient->__soapCall($method, $params);
           return $request;
      }
      catch(\SoapFault $e)
      {
         FileLog::Write("SoapFault:".$e->getMessage());
      }
   }
   
   public function IsValidVat($countryCode, $vatNumber)
   {
        $struct["countryCode"] = $countryCode;
        $struct["vatNumber"] = $vatNumber;
        $item = $this->GetSoapResponse("checkVat",array($struct));
        return $item->valid;
   }         
    
}