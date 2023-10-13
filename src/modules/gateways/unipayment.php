<?php

if (!defined("WHMCS"))
{
    die("This file cannot be accessed directly");
}

require_once(dirname(__FILE__) . '/unipayment/vendor/autoload.php');	


function unipayment_MetaData()
{
    return array('DisplayName' => 'Unipayment','APIVersion' => '1.1');
}

function unipayment_config()
{
    $env_list =  array('test'=>'SandBox', 'live' => 'Live');		
	$confirm_speeds = array('low'=>'low', 'medium'=>'medium', 'high'=>'high');					
	$processing_statuses = array('Confirmed'=>'Confirmed', 'Complete'=>'Complete');    

    $param = array(
        'FriendlyName' => array('Type' => 'System','Value' => 'Unipayment'),
        'client_id' => array('FriendlyName' => 'Client ID','Type' => 'text','Size' => '25','Description' => 'Enter your Client ID here'),
        'client_secret' => array('FriendlyName' => 'Client Secret','Type' => 'text','Size' => '25','Description' => 'Enter your Client Secret here'),
        'app_id' => array('FriendlyName' => 'App ID','Type' => 'text','Size' => '25','Description' => 'Enter your App ID here'),                
    );
    
    $param['confirm_speed'] = array('FriendlyName' => 'Confirm Speed','Type' => 'dropdown',"Options" =>$confirm_speeds, 'Description' => 'Select Confirm Speed');    
    $param['processing_status'] = array('FriendlyName' => 'Processing Status','Type' => 'dropdown',"Options" =>$processing_statuses, 'Description' => 'Select Confirm Speed');
    $param['testMode'] = array('FriendlyName' => 'Test Mode','Type' => 'yesno','Description' => 'Tick to enable test mode');
    return $param ;
}


function unipayment_link($params)
{
    #module configuartion
    $client_id = $params['client_id'];
    $client_secret = $params['client_secret'];
    $app_id = $params['app_id'];
    $confirm_speed = $params['confirm_speed'];    
    $testMode = $params['testMode'];               
	$langPayNow = $params['langpaynow'];	

    #order details
    $invoiceId = $params['invoiceid'];
    $description = $params['description'];
    $amount=$params['amount'];
    $currency_code = $params['currency'];
	$systemUrl = $params['systemurl'];	
    $returnURL = $params['returnurl'];
    $notifyURL = $systemUrl . 'modules/gateways/unipayment/notify.php?invid='.$params['invoiceid'];
	
	$createInvoiceRequest = new \UniPayment\Client\Model\CreateInvoiceRequest();
	$createInvoiceRequest->setAppId($app_id);
	$createInvoiceRequest->setPriceAmount($amount);
	$createInvoiceRequest->setPriceCurrency($currency_code);
	$createInvoiceRequest->setConfirmSpeed($confirm_speed);
	$createInvoiceRequest->setNotifyUrl($notifyURL);
	$createInvoiceRequest->setRedirectUrl($returnURL);
	$createInvoiceRequest->setOrderId($invoiceId );
	$createInvoiceRequest->setTitle($description);
	$createInvoiceRequest->setDescription($description);


	$client = new \UniPayment\Client\UniPaymentClient();
	$client->getConfig()->setClientId($client_id);
	$client->getConfig()->setClientSecret($client_secret);

    $response = $client->createInvoice($createInvoiceRequest);    
    if ($response['code'] == 'OK') {
		
        $payurl = $response->getData()->getInvoiceUrl();		  
        $htmlOutput .= '<form id="Unipayment_PaymentForm" action="'.$payurl.'" method="GET">'; 
		$htmlOutput .= '<input type="submit" value="' . $langPayNow . '" />';	
		$htmlOutput .= '</form>';			        
    } else {
        $errmsg = $response['msg']; 
		$htmlOutput .= '<p>'.$errmsg.'</p>';
        
    }
    return $htmlOutput;     

}

