<?php
/**
 * ��ʱ����������
 * ============================================================================
 * api˵����
 * init(),��ʼ������Ĭ�ϸ�һЩ����ֵ����cmdno,date�ȡ�
 * getGateURL()/setGateURL(),��ȡ/������ڵ�ַ,�������ֵ
 * getKey()/setKey(),��ȡ/������Կ
 * getParameter()/setParameter(),��ȡ/���ò���ֵ
 * getAllParameters(),��ȡ���в���
 * getRequestURL(),��ȡ����������URL
 * doSend(),�ض��򵽲Ƹ�֧ͨ��
 * getDebugInfo(),��ȡdebug��Ϣ
 * 
 * ============================================================================
 *
 */

require ("RequestHandler.class.php");
class PayRequestHandler extends RequestHandler {
	
	function __construct() {
		$this->PayRequestHandler();
	}
	
	function PayRequestHandler() {
		//Ĭ��֧����ص�ַ
		$this->setGateURL("https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi");	
	}
	
	/**
	*@Override
	*��ʼ������Ĭ�ϸ�һЩ����ֵ����cmdno,date�ȡ�
	*/
	function init() {
		//�������
		$this->setParameter("cmdno", "1");
		
		//����
		$this->setParameter("date",  date("Ymd"));
		
		//�̻���
		$this->setParameter("bargainor_id", "");
		
		//�Ƹ�ͨ���׵���
		$this->setParameter("transaction_id", "");
		
		//�̼Ҷ�����
		$this->setParameter("sp_billno", "");
		
		//��Ʒ�۸��Է�Ϊ��λ
		$this->setParameter("total_fee", "");
		
		//��������
		$this->setParameter("fee_type",  "1");
		
		//����url
		$this->setParameter("return_url",  "");
		
		//�Զ������
		$this->setParameter("attach",  "");
		
		//�û�ip
		$this->setParameter("spbill_create_ip",  "");
		
		//��Ʒ���
		$this->setParameter("desc",  "");
		
		//���б���
		$this->setParameter("bank_type",  "0");
		
		//�ַ����
		$this->setParameter("cs",  "gbk");
		
		//ժҪ
		$this->setParameter("sign",  "");
		
	}
	
	/**
	*@Override
	*����ǩ��
	*/
	function createSign() {
		$cmdno = $this->getParameter("cmdno");
		$date = $this->getParameter("date");
		$bargainor_id = $this->getParameter("bargainor_id");
		$transaction_id = $this->getParameter("transaction_id");
		$sp_billno = $this->getParameter("sp_billno");
		$total_fee = $this->getParameter("total_fee");
		$fee_type = $this->getParameter("fee_type");
		$return_url = $this->getParameter("return_url");
		$attach = $this->getParameter("attach");
		$spbill_create_ip = $this->getParameter("spbill_create_ip");
		$key = $this->getKey();
		
		$signPars = "cmdno=" . $cmdno . "&" .
				"date=" . $date . "&" .
				"bargainor_id=" . $bargainor_id . "&" .
				"transaction_id=" . $transaction_id . "&" .
				"sp_billno=" . $sp_billno . "&" .
				"total_fee=" . $total_fee . "&" .
				"fee_type=" . $fee_type . "&" .
				"return_url=" . $return_url . "&" .
				"attach=" . $attach . "&";
		
		if($spbill_create_ip != "") {
			$signPars .= "spbill_create_ip=" . $spbill_create_ip . "&";
		}
		
		$signPars .= "key=" . $key;
		
		$sign = strtolower(md5($signPars));
		
		$this->setParameter("sign", $sign);
		
		//debug��Ϣ
		$this->_setDebugInfo($signPars . " => sign:" . $sign);
		
	}

}

?>