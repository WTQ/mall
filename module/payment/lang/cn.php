<?php
$note["1"]="资金充值";
$note["2"]="申请提现";
$note["3"]="网站购物";
$note["4"]="销售收入";
$note["5"]="购买积分";
$note["6"]="升级会员";
$note["7"]="系统扣费";
$note["8"]="管理员充值";
$note["9"]="提现成功";
$note["10"]="提现失败";
$note["11"]="广告购买";
$note["12"]="拥金支付，技术维护费,订单编号：";

if(!isset($lang))
	$lang=array();
 global $_LANG_MOD_OFFER; 
 $_LANG_MOD_OFFER = array (
	'avidcash' => '帐户可用余额',
	'bind_a' => '银行账号管理',
	'bind_b' => '未审核',
	'bind_c' => '您绑定的提现银行账户尚未通过验证，请稍候再试。',
	'bind_d' => '开户名',
	'bind_e' => '开户银行',
	'bind_f' => '帐号',
	'bind_g' => '您尚未绑定提现银行账户，请填写下列信息等待审核通过后即可申请提现。',
	'bind_h' => '比如:招商银行 上海分行营业部转 沪太支行',
	'bind_j' => '开户名不能为空',
	'bind_k' => '开户银行不能为空',
	'bind_l' => '银行帐号不能为空',
	'pay_a' => '给本账户充值',
	'pay_b' => '请选择充值方式',
	'pay_c' => '请选择',
	'pay_d' => '网上银行充值',
	'pay_e' => '下一步',
	'pay_f' => '请输入充值金额',
	'pay_g' => '金额未输入',
	'pickup_a' => '提现申请',
	'pickup_b' => '操作失败，提现金额错误，提现金额不能大于您的可用余额。',
	'pickup_c' => '操作失败，登录密码输入错误。',
	'pickup_d' => '提现银行',
	'pickup_e' => '开户名',
	'pickup_f' => '帐号',
	'pickup_g' => '账户可用余额',
	'pickup_h' => '提现金额',
	'have_account'=>'已绑定账号',
	'payment_password'=>'支付密码',
	'flow_a' => '资金流水查询',
	'flow_b' => '起止日期',
	'flow_c' => '请输入正确的时间格式,例如: 20080208。',
	'flow_d' => '充值',
	'flow_e' => '提现',
	'flow_f' => '流水号',
	'flow_g' => '金额',
	'flow_h' => '收入:',
	'flow_i' => '支付方式',
	'flow_j' => '时间',
	'flow_k' => '状态',
	'flow_l' => '备注',
	'flow_m' => '小计',
	'flow_n' => '记录类型',
	'flow_o' => '支出',
	'flow_p' => '总计',
	'flow_q' => '全部',

); 
$lang = array_merge($lang, $_LANG_MOD_OFFER); 
?>