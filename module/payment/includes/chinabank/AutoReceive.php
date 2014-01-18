<?

include_once(dirname(__FILE__) . "/../../../../includes/global.php");

$key 		= 'tuoyu100100';

$v_oid     = isset($_POST['v_oid']) ? trim($_POST['v_oid']) : '';
$v_pmode   = isset($_POST['v_pmode']) ? trim($_POST['v_pmode']) : '';
$v_pstatus = isset($_POST['v_pstatus']) ? trim($_POST['v_pstatus']) : '';
$v_pstring = isset($_POST['v_pstring']) ? trim($_POST['v_pstring']) : '';
$v_amount  = isset($_POST['v_amount']) ? trim($_POST['v_amount']) : '';
$v_moneytype  = isset($_POST['v_moneytype']) ? trim($_POST['v_moneytype']) : '';
$remark1   = isset($_POST['remark1']) ? trim($_POST['remark1' ]) : '';
$remark2   = isset($_POST['remark2']) ? trim($_POST['remark2' ]) : '';
$v_md5str  = isset($_POST['v_md5str']) ? trim($_POST['v_md5str' ]) : '';

/**
 * 重新计算md5的值
 */                        
$md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key)); //拼凑加密串
if ($v_md5str==$md5string)
{
   if($v_pstatus=="20")
	{
	   //支付成功
		//商户系统的逻辑处理（例如判断金额，判断支付状态(20成功,30失败),更新订单状态等等）......
		$sql="select flow_id,pay_uid,statu from ".CASHFLOW." where id='$v_oid'";//验证签名
		$db->query($sql);
		
		$re=$db->fetchRow();
		$userid=$re['pay_uid'];
		$is_succeed=$re['statu'];
		if($is_succeed==1)//如果验证成功,并且流水表中的记录为新提交
		{
			$sql="update ".CASHFLOW." set price='$total_fee',flow_id='$payflowid',statu='4' where id='$v_oid'";
			$db->query($sql);
	
			$sql="update ".PUSER." set cash=cash+$v_amount where pay_uid='$userid'";
			$db->query($sql);
			return TRUE;
		}
	}
  echo "ok";
	
}else{
	echo "error";
}

