<?php 

namespace Rayms\OrderEventsBroadcaster\Observer;

use Rayms\OrderEventsBroadcaster\Model\OrderBroadcast;
use Rayms\OrderEventsBroadcaster\Model\ShippingAddress;
use Rayms\OrderEventsBroadcaster\Model\BillingAddress;

class OrderPlacedObserver implements \Magento\Framework\Event\ObserverInterface 
{

  private $orderBroadCastModel;
  private $logger;

  private $_storeManager;
  private $checkoutSession;
  private $registry;
  private $resultJsonFactory;
  private $shippingAddress;
  private $billingAddress;
  
    
    public function __construct(
        OrderBroadcast $orderBroadCastModel,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Registry $registry, 
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager, 
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
      )
    {
      $this->orderBroadCastModel = $orderBroadCastModel;
      $this->logger = $logger;

      $this->_storeManager = $storeManager;
      $this->checkoutSession = $checkoutSession;
      $this->registry = $registry;
      $this->resultJsonFactory = $resultJsonFactory;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
         
      $this->logger->notice('Order observer triggered!');

      $order = $observer->getEvent()->getOrder();
      $orderId = $order->getRealOrderId();
    

      //Get Payment Info
      $payment = $order->getPayment();
      $methodTitle = $payment->getMethodInstance()->getTitle();

      $lineItems = [];
    
      foreach($order->getAllItems() as $item){

          $lineItem = [
            
            'product_id'      =>      $item->getProductId(),
            'product_sku'      =>     $item->getSku(),
            'product_name'    =>      $item->getName(),
            'product_price'    =>     $item->getPrice(),
            'ordered_qty'     =>      $item->getQtyOrdered()

          ];

          array_push($lineItems, $lineItem);
         
        }

        $this->shippingAddress =  new ShippingAddress($order->getShippingAddress());
        $this->billingAddress =  new ShippingAddress($order->getBillingAddress());

      $orderParams = [

          'order_id'                    =>    $orderId,
          'order_status'                =>    $order->getState(),
          'order_date'                  =>    $order->getCreatedAt(),
          'order_date_updated'          =>    $order->getUpdatedAt(),
          'shipping_method'             =>    $order->getShippingMethod(),
          'order_total'                 =>    $order->getGrandTotal(),
          'currency'                    =>    $order->getOrderCurrency()->getCode(),
          'customer_id'                 =>    $order->getCustomerId(),
          'customer_name'               =>    $order->getCustomerName(),
          'customer_email'              =>    $order->getCustomerEmail(),
          'line_items'                  =>    $lineItems,
          'shipping_address'            =>    $this->shippingAddress->getFullAddress(),
          'billing_address'             =>    $this->billingAddress->getFullAddress()

      ];

      $this->orderBroadCastModel->broadcastOrderEvent($orderParams);

      // echo "<pre>";
      // print_r($orderParams);
      // echo "</pre>";
      // die("done");

      // var_dump("order id : " . $orderId);
      // var_dump("order status :" .$order->getStatusLabel());
      // var_dump("order date :" . $$orderData->getCreatedAt());
      // var_dump("update date :" . $$orderData->getUpdatedAt());
      // var_dump("shipping address :" . json_encode($order->getShippingAddress()));
      // var_dump("payment method :" . $methodTitle);
      // var_dump("shipping status :" . $order->getShippingDescription());
      // var_dump("order total :" . $order->getGrandTotal());
      // var_dump("currency used :" . json_encode($order->getOrderCurrency()));
      // var_dump("customer email :" . $order->getCustomerEmail())


      // die("donena");
     
      // die("done na");
     
       
          // $event = $observer->getEvent(); 	
          // $model = $event->getPage();
          // var_dump($event);
   	      // print_r($model->getData());
          // die('test');
          // $this->orderBroadCastModel->broadCastForNewOrder();
       
    }

}