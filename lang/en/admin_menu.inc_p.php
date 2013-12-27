<?php
$menu=array(
	'main'=>array(
			'name'=>'Home',
			'action'=>'main',
			'sub'=>array(
				array(
						'name'=>'My order',
						'action'=>"?m=product&s=admin_buyorder",
					 ),	
				array(
						'name'=>'My favorites',
						'action'=>array(
							'?m=sns&s=admin_share_product'=>'Product List',
							'?m=sns&s=admin_share_shop'=>'Shop List',
						)
					),
				array(
						'name'=>'Evaluation',
						'action'=>"?m=company&s=admin_credit",
					),
			),
		),
	
	'friend'=>array(
			'name'=>'My Friends',
			'sub'=>array(
						array(
						'name'=>'好友',
						'action'=>array(
							'?m=sns&s=admin_friends'=>"好友",
						),
					)
			)
	),
	'inquire'=>array(
			'name'=>'Message',
			'sub'=>array(
						array(
							'name'=>$lang['mes'],
							'type'=>array(1,2),
							'action'=>array(
								'?m=message&s=admin_message_list_inbox'=>$lang['inbox'],
								'?m=message&s=admin_message_list_savebox'=>$lang['savebox'],
								'?m=message&s=admin_message_list_delbox'=>$lang['delbox'],
								'?m=message&s=admin_message_det'=>'',
								'?m=message&s=admin_message_sed'=>'',
							)
					)
			)
	),
	'user'=>array(
			'name'=>'Setting',
			'sub'=>array(
					array(
						'name'=>'Profiles',
						'action'=>array(
							'admin_personal'=>'Edit Pfrofiles',
							'?m=product&s=admin_orderadder'=>'Delivery Address',
							//'?m=product&s=admin_invoice'=>'发票信息',
						),
					),
					array(
						'name'=>'Payment',
						'action'=>array(
							'?m=payment&s=admin_accounts_base'=>'账户信息',
							'?m=payment&s=admin_accounts_cashflow'=>'资金流水',
							'?m=payment&s=admin_accounts_pay'=>'账户充值',
							'?m=payment&s=admin_accounts_bind'=>'提现银行',
							'?m=payment&s=admin_accounts_pickup'=>'资金提现',
							'?m=payment&s=admin_info'=>'支付账户',
							'?m=payment&s=admin_pay'=>'',
						),
					),
			),	
		),
	'personal'=>array(
			'name'=>'My Space',
			'action'=>$config['weburl'].'/home.php?uid='.$buid,
		),

);

//----------------------
foreach($menu as $key=>$v)
{
	if(isset($menu[$key]['sub']))
	{
		
		foreach($menu[$key]['sub'] as $sv)
		{
			if(is_array($sv['action']))
			{
				foreach($sv['action'] as $sskey=>$ssv)
				{
					if($sskey==$_GET['action']||$sskey=='?m='.$_GET['m'].'&s='.$_GET['s'])
						$cmenu=$key;
				}
			}
		}
		ksort($menu[$key]['sub']);
	}
	if(isset($admin))
	{	
		if($key!='main'&&is_array($menu[$key]['sub']))
		{
			$act=each($menu[$key]['sub']);$subkey=$act['key'];//取出第一个下标
			$act=@each($menu[$key]['sub'][$subkey]['action']);
			$menu[$key]['action']=$act['key'];
		}
	}
}
//----------------------------------------
if(isset($tpl))
{
	$cmenu=!empty($cmenu)?$cmenu:'main';
	$smenu=!empty($cmenu)?($cmenu=='friend'||$cmenu=='inquire'?'main':$cmenu):'main';
	$tpl->assign("submenu",$menu[$smenu]);
	$tpl->assign("menu",$menu);
	$tpl->assign("cmenu",$cmenu);
}
?>