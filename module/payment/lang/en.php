<?php
$note["1"] = "Result of funds";
$note["2"] = "Withdraw funds";
$note["3"] = "Pay order";
$note["4"] = "Sales";
$note["5"] = "Purchase points";
$note["6"] = "Upgrade member";
$note["7"] = "System charge back";
$note["8"] = "Manager Result";
$note["9"]="Withdraw success";
$note["11"]="Buy adv";
$note["12"]="拥金支付，技术维护费";

if(!isset($lang))
	$lang=array();
 global $_LANG_MOD_OFFER; 
 $_LANG_MOD_OFFER = array (
	'avidcash' => 'Available balance',
	'bind_a' => 'Bank Account Management',
	'bind_b' => 'Unaudited',
	'bind_c' => 'Bind pickup your bank account is not verified, please try again later. ',
	'bind_d' => 'Full Name',
	'bind_e' => 'Bank branches',
	'bind_f' => 'Bank Account',
	'bind_g' => 'You are not bound withdraw bank account, please fill out the following information after the adoption of pending review can apply to withdraw. ',
	'bind_h' => 'For example: China Merchants Bank Shanghai Branch Office Hutai branch center',
	'bind_j' => 'Open username can not be empty',
	'bind_k' => 'Bank can not be empty',
	'bind_l' => 'Bank account can not be empty',
	'pay_a' => 'Make a deposit to the present',
	'pay_b' => 'Please select the bank',
	'pay_c' => 'Please select',
	'pay_d' => 'e-banking Transfer',
	'pay_e' => 'Next',
	'pay_f' => 'Please enter the amount',
	'pay_g' => 'Cash amount not enter',
	'pickup_a' => 'Withdraw Application',
	'pickup_b' => 'The operation failed to mention is the amount of errors, to mention the amount of cash can not exceed your available amount. ',
	'pickup_c' => 'The operation failed, Password Error. ',
	'pickup_d' => 'Bank Name',
	'pickup_e' => 'Open username',
	'pickup_f' => 'Account',
	'pickup_g' => 'Available balance',
	'pickup_h' => 'Pickup amount',
	'have_account'=>'Has been bound account',
	'payment_password'=>'Payment password',
	'flow_a' => 'Cash flowing query',
	'flow_b' => 'Start and end dates',
	'flow_c' => 'Please enter the correct time format. ',
	'flow_d' => 'Result',
	'flow_e' => 'Withdraw',
	'flow_f' => 'Serial number',
	'flow_g' => 'Amount',
	'flow_h' => 'Income:',
	'flow_i' => 'Payment',
	'flow_j' => 'Time',
	'flow_k' => 'State',
	'flow_l' => 'Note',
	'flow_m' => 'Subtotal',
	'flow_n' => 'Record type',
	'flow_o' => 'Expenditure:',
	'flow_p' => 'Total:',
	'flow_q' => 'All',
); 
$lang = array_merge($lang, $_LANG_MOD_OFFER); 
?>