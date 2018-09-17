<?php 

namespace Rayms\OrderEventsBroadcaster\Model;

class OrderBroadcast extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface 
{

    const BETA_BROADCAST_ENDPOINT    = 'https://betaproaudio.herokuapp.com/webhooks/magento';
    const PROD_BROADCAST_ENDPOINT    = 'https://proaudio.com/webhooks/magento';
    const SECRET_KEY = 'mysecret';
    const CACHE_TAG = 'inbound_av_cache';
    const CONFIG_SECRET_KEY = 'ordereventsbroadcaster/general/secret_key';
    const CONFIG_STATUS_MODE = 'ordereventsbroadcaster/general/status_mode';
    
    private $logger;
    private $scopeConfig;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ){

        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }
    
    public function getIdentities() : string
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

    public function broadcastOrderEvent($orderData = [])
    {

        $orderJsonData = json_encode(['order' => $orderData]);
        $this->logger->notice('order data: ' . $orderJsonData);
        $this->sendRequestViaCurl($orderJsonData);
    }

    public function sendRequestViaCurl($data)
    {

        $this->logger->notice('Sending curl request...');
        $secretKey = $this->scopeConfig->getValue(self::CONFIG_SECRET_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $hash = base64_encode(hash_hmac('sha256', $data, $secretKey, true));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->getRequestUrl());
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HEADER  , true);  

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(

            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($data),
            'X-Magento-Hmac-SHA256: ' . $hash,
            'X-Magento-Domain: mydomain.com'
        ));
                                
        $result = curl_exec($curl);
        $response = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        if ($err) {
            
            $this->logger->notice('Curl error : '. $err);
        }
        curl_close($curl);

    }
    
    public function getRequestUrl() : string 
    {

        $statusMode = $this->scopeConfig
                    ->getValue(self::CONFIG_STATUS_MODE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        $this->logger->notice('status mode : '. $statusMode);

        if ($statusMode == 'beta') {

            return self::BETA_BROADCAST_ENDPOINT;
        }
        
        return self::PROD_BROADCAST_ENDPOINT;
    }
    

}

