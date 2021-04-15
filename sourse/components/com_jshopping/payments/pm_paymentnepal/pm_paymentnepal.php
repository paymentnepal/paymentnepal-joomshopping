<?php
defined('_JEXEC') or die();

class pm_paymentnepal extends PaymentRoot{
    /** версия платежного интерфейса */
    const VERSION = '1.0';
    /** Url для совершения оплаты */
    const MERCHANT_URL = 'https://pay.paymentnepal.com/alba/input/';

    /**
     * @param array $params
     * @param array $pmconfigs
     */
    function showPaymentForm($params, $pmconfigs){
		include(dirname(__FILE__).'/paymentForm.php');
	}

    /**
     * This method is responsible for plugin settings in administration panel
     * @param $params Plugin settings params
     */
    function showAdminFormParams($params){
        $module_params_array = array(
            'paymentnepal_secret_key',
            'paymentnepal_key',
            'transaction_end_status',
            'transaction_pending_status',
            'transaction_failed_status'
        );
        foreach($module_params_array as $module_param){
            if(!isset($params[$module_param]))
                $params[$module_param] = '';
        }
        $orders = JModelLegacy::getInstance('orders', 'JshoppingModel');
        $this->loadLanguageFile();
        include dirname(__FILE__) . '/adminparamsform.php';
	}

    /**
     * Attaching required lang file for plugin
     */
    function loadLanguageFile(){
        $lang = JFactory::getLanguage();
        // Detecting current language
        $lang_tag = $lang->getTag();
        // Lang files directory
        $lang_dir = JPATH_ROOT . '/components/com_jshopping/payments/pm_paymentnepal/lang/';
        // Full lang file path variable
        $lang_file = $lang_dir . $lang_tag . '.php';
        // Trying to mount lang file (default en-GB.php)
        if(file_exists($lang_file))
            require_once $lang_file;
        else
            require_once $lang_dir . 'en-GB.php';
    }

    /**
     * Get info about order and plugin settings, form xml fields and sign for POST request
     * Make an HTML form from recieved data and echo it.
     * @param array $pmconfigs Plugin settings array
     * @param object $order Current order object
     */
    function showEndForm($pmconfigs, $order){
        // Loading lang file to describe possible errors
        $this->loadLanguageFile();
        // If default order status isn't equal to status for unfinished transactions - echo error
        if($order->order_status != $pmconfigs['transaction_pending_status'])
            die(PAYMENTNEPAL_REDIRECT_PENDING_STATUS_ERROR);
        /* Get required fields to init payment */

        $lang = JFactory::getLanguage()->getTag();
        switch($lang){
            case 'en_EN':
                $lang = 'en';
                break;
            case 'ru_RU':
                $lang = 'ru';
                break;
            default:
                $lang = 'en';
                break;
        }
        $order_id = $order->order_id;
        $cost = $order->order_total;
        $name = 'Order №' . $order_id;
        $key = $pmconfigs['paymentnepal_key'];
        $merchant_url = self::MERCHANT_URL;
        $redirect_text = PAYMENTNEPAL_REDIRECT_TO_PAYMENT_PAGE;
        $form = <<<FORM
<form action="$merchant_url" method="POST" id="paymentform">
    $redirect_text
    <input type="hidden" name="key" value="$key">
    <input type="hidden" name="cost" value="$cost"> 
    <input type="hidden" name="name" value="$name">
    <input type="hidden" name="order_id" value="$order_id">
</form>
<script type="text/javascript">
   document.getElementById('paymentform').submit();
</script>
FORM;
        echo $form;
    }

    /**
     * Called at requests processing to success/error/result URLs and before CheckTransaction() method
     * Init an array with incoming request process result :
     * 'order_id' - unique order id
     * 'hash' - hash for request authorization
     * 'checkHash' - is hash check required
     * 'checkReturnParams' - is incoming params check required
     * @param $paymentnepal_config payment method settings array
     * @return array incoming request process result array
     */
    function getUrlParams($paymentnepal_config){
        $params = array();
        $input = JFactory::$application->input;
        $params['order_id'] = $input->getInt('order_id', null);
        $params['hash'] = "";
        $params['checkHash'] = 0;
        $params['checkReturnParams'] = 0;
        return $params;
    }

    /**
     * Makes transactions check (handling requests to success/error/result URLs)
     * Returns an array with check result: array($rescode, $restext), where:
     * $rescode - transaction result code (1 - success, 2 - pending, 3 - cancel, 0 - error)
     * $restext - transaction result text
     * Depending on $rescode inits order creation, order status change and email sending to administrator and customer
     * Further control is passed to nofityFinish() method
     * @param $pmconfig Plugin settings array
     * @param $order Current order object
     * @param $rescode Request type(notify, return, cancel)
     * @return array Array with transaction check result
     */
    function checkTransaction($pmconfig, $order, $rescode){
        // Load lang file to describe possible errors
        $this->loadLanguageFile();
        // Get object, containing incoming data (GET or POST), using instead of JRequest::getInt('var')
        $inputObj = JFactory::$application->input;
        //print_r($inputObj);
        $in_data = array(  'tid'    =>  $inputObj->getString('tid', null),
                   'name'           =>  $inputObj->getString('name', null), 
                   'comment'        =>  $inputObj->getString('comment', null),
                   'partner_id'     =>  $inputObj->getString('partner_id', null),
                   'service_id'     =>  $inputObj->getString('service_id', null),
                   'order_id'       =>  $inputObj->getString('order_id', null),
                   'type'           =>  $inputObj->getString('type', null),
                   'partner_income' =>  $inputObj->getString('partner_income', null),
                   'system_income'  =>  $inputObj->getString('system_income', null),
                   'test'           =>  $inputObj->getString('test', null)
                );  
    $secret_key = $pmconfig['paymentnepal_secret_key'];
    $transaction_sign = md5(implode('', array_values($in_data)) . $secret_key);
       
if ($transaction_sign !== $inputObj->getString('check', null) || empty($secret_key))
	die('bad sign');
  $type=$inputObj->getString('act', null);
      switch($type){
        case 'notify':  
          $order_amount = $order->order_total;
          $response_amount =$in_data['system_income'];
          if($response_amount < $order_amount) die('bad cost');
          if(!$order->order_created && ($order->order_status == $pmconfig['transaction_pending_status']))
            return array(1, PAYMENTNEPAL_SUCCESS_URL_REQUEST_PASSED);
        // If order is created and status is Paid, this means request at result URL has already been sent
          elseif($order->order_created && ($order->order_status == $pmconfig['transaction_end_status']))
              return array(1, PAYMENTNEPAL_SUCCESS_URL_ORDER_ALREADY_PAID);
          // else there's smth wrong with order statuses
          else
              return array(0, PAYMENTNEPAL_SUCCESS_URL_ORDER_STATUS_FAILED);
          break;
        case 'return':
          if($order->order_created && ($order->order_status == $pmconfig['transaction_end_status']))
              //return array(1, PAYMENTNEPAL_SUCCESS_URL_ORDER_ALREADY_PAID);
              echo 'Thank you';
          // else there's smth wrong with order statuses
          else
              return array(0, PAYMENTNEPAL_SUCCESS_URL_ORDER_STATUS_FAILED);
          break;
        case 'cancel':
            /* Handling request to error URL */
            // Payment was cancelled by the customer
            return array(3, PAYMENTNEPAL_RESULT_URL_PAYMENT_CANCELLED);
            break;
        default:
            // in case of error
            return array(0, PAYMENTNEPAL_UNKNOWN_ERROR);
            break;
    } 

}

    /**
     * Данный метод выводит сообщение об успешной обработке запроса на Result Url.
     * Вызывается после метода checkTransaction()
     * @param $pmconfigs
     * @param $order
     * @param $rescode
     */
    function nofityFinish($pmconfigs, $order, $rescode){
        $msg = "OK$order->order_id";
        echo $msg;
	}
}
?>
