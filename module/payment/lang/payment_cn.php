<?php 
 if(!isset($lang))
	$lang=array();
 global $_LANG_PAYMENT; 
 $_LANG_PAYMENT = array (
  'alipay' => '支付宝',
  'alipay_desc' => '支付宝网站(www.alipay.com) 是国内先进的网上支付平台。需与支付宝公司签约方可使用。',
  'alipay_interface' => '选择接口类型',
  'alipay_interface_options' => 
  array (
    0 => '使用即时到帐交易接口',
  ),
  'chinabank' => '网银在线',
  'chinabank_account' => '商户编号',
  'chinabank_desc' => '网银在线与中国工商银行、招商银行、中国建设银行、农业银行、民生银行等数十家金融机构达成协议。全面支持全国19家银行的信用卡及借记卡实现网上支付。网址:http://www.chinabank.com.cn',
  'chinabank_key' => 'MD5 密钥',
  'key' => '交易安全校验码',
  'partner' => '合作者身份ID',
  'paypal' => 'PayPal',
  'paypal_account' => '商户帐号',
  'paypal_category' => '支付货币种类',
  'paypal_category_options' => 
  array (
    'USD' => '美元',
    'HKD' => '港币',
    'EUR' => '欧元',
    'JPY' => '日元',
  ),
  'paypal_desc' => 'PayPal 是在线付款解决方案的全球领导者，在全世界有超过七千一百六十万个帐户用户。PayPal 可在 56 个市场以 7 种货币（加元、欧元、英镑、美元、日元、澳元、港元）使用。（网址:http://www.paypal.com）',
  'paypalcn' => '贝宝',
  'paypalcn_account' => '商户帐号',
  'paypalcn_desc' => '贝宝是由上海网付易信息技术有限公司与世界领先的网络支付公司—— PayPal 公司通力合作为中国市场度身定做的网络支付服务。（网址:http://www.paypal.com/cn）',
  'seller_email' => '支付宝帐户',
  'tenpay' => '财付通',
  'tenpay_account' => '财付通商户号',
  'tenpay_desc' => 0,
  'tenpay_key' => '财付通密钥',
  'tenpay_magic_key' => '自定义签名',
); 
  $lang = array_merge($lang, $_LANG_PAYMENT); 
?>