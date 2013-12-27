<?php 
 if(!isset($lang))
	$lang=array();
 global $_LANG_PAYMENT; 
 $_LANG_PAYMENT = array (
  'alipay' => 'Alipay',
  'alipay_account' => 'Alipay account',
  'alipay_desc' => 'Alipay website(www.alipay.com) is a leading online platform for payment. You need to sign with Alipay company before you can use it.',
  'alipay_interface' => 'Select connection type',
  'alipay_interface_options' => 
  array (
    0 => 'Use instant payment collection connection',
  ),
  'alipay_key' => 'Transaction security verification code',
  'alipay_partner_id' => 'Cooperator ID',
  'chinabank' => 'Chinabank',
  'chinabank_account' => 'Trade company S/N',
  'chinabank_desc' => 'Chinabank has reached an agreement with Industrial and Commercial Bank of China, China Merchants Bank
, China Construction Bank, Agricultural Bank of China, China Minsheng Banking Corporation Limited. and so on. It supports online payment with credit cards and debit cards from 19 banks in China. Website: http://www.chinabank.com.cn',
  'chinabank_key' => 'MD5 private key',
  'paypal' => 'PayPal',
  'paypal_account' => 'User Account',
  'paypal_category' => 'Payment Currency',
  'paypal_category_options' => 
  array (
    'USD' => 'USD',
    'HKD' => 'HKD',
    'EUR' => 'EUR',
    'JPY' => 'JPY',
  ),
  'paypal_desc' => 'PayPal a global leader in online payment solutions with more than 71.6 million accounts worldwide.PayPal can be used in 56 markets and 7 currencies(CAD, EUR, GBP, USD, JPY, AUD and HKD). (Site: http://www.paypal.com)',
  'paypalcn' => 'PayPal China',
  'paypalcn_account' => 'User Account',
  'paypalcn_desc' => 'PayPal China provides online payment service initiated by Shanghai Wang Fu Yi Information Technology Co. Ltd. and worldwide leading online payment company PayPal.（Web:http://www.paypal.com/cn）',
  'tenpay' => 'Tenpay',
  'tenpay_account' => 'Tenpay User Account',
  'tenpay_desc' => 'Tenpay（www.tenpay.com） - online payment platform charged by Tencent. It has been verified by National Authority Security. It supports online payment with banks in China. No commission charge included',
  'tenpay_key' => 'Tenpay key',
  'tenpay_magic_key' => 'Customized signiture',
); 
  $lang = array_merge($lang, $_LANG_PAYMENT); 
?>