<?php
namespace wallet
{
  class cash
  {
    function __construct()
    {
		// id пользователя
$this->u_id=(int)$_SESSION['user_id'];
// сбор массива по принципу 'значение валюты'
$this->TYPE='TYPE';
$this->where="`u_id`= '{$this->u_id}'";
$this->show_balance();
    }
	function show_balance() {
		// запрос на mysql
		$sql="SELECT * FROM  `wallet` where {$this->where} LIMIT 0 , 30";
		$query=mysql_query($sql);
		while ($array = mysql_fetch_assoc($query)) {
			$return[$array[$this->TYPE]]=$array;
		}
		$this->balance=$return;
		return $return;
	}
	function update_balance($wallet) {
			 $up="UPDATE `wallet` SET `money` = (SELECT SUM(`money`) FROM `payment` WHERE {$this->where} AND `u_id` = '{$this->u_id}' AND `wallet_id` = '".$this->balance[$wallet]['id']."' AND `status`=1) where `id` = '".$this->balance[$wallet]['id']."'";
			 mysql_query($up) or die($up);
			 $this->show_balance();
	}
	
	function show_from_money($from,$to,$from_money) {
		$course= new course;
		// для расширения и правильной конвертаци....
		//$my_money= new convert\money($from,$to,$money,$course);
		// По умолчанию:	
				$new_course=$course->todo($to);
return	    		$to_money=(float)$from_money*(float)$new_course[$from];
	}
	function convert($from,$to,$from_money) {
		if (!$this->check_wallet($from))return array('error' =>WALLET_NOT_FOUND,'ok'=>0);
		if (!$this->check_wallet($to))return array('error' =>WALLET_NOT_FOUND,'ok'=>0);
		$to_money=$this->show_from_money($from,$to,$from_money);
		$take_money=$this->insert_payment('TAKE_CONVERT',$from,$from_money*-1);
		if ($take_money[ok]) { 
			$add_money=$this->insert_payment('ADD_CONVERT',$to,$to_money);
			if ($add_money[ok]) {
			return array('ok'=>1,'from'=>$take_money,'to'=>$add_money);
			}
		}
		else return $take_money;
	}
	function show($wallet) {
		$return['balance']=$this->balance[$wallet];
		
		if (!($return['balance'] >= 0) or ($wallet == '')) {
			return array('error'=>WALLET_NOT_FOUND);
		}
		$sql="SELECT * FROM  `payment` WHERE {$this->where} $addon";
		$query=mysql_query($sql);
			while ($array = mysql_fetch_assoc($query)) {
			$return[operation][$array[id]]=$array;
		}
		return $return;
	}
	function show_all($to) {

	if (!$this->check_wallet($to))return array('error' =>WALLET_NOT_FOUND,'ok'=>0);		
	foreach ($this->balance as $from => $value) {
	$sum+=$this->show_from_money($from,$to,(float)$value['money']);
	}
	return array('FULL_BALANCE' => $sum,'TYPE'=>$to);
	}
	function check_wallet($wallet) {
	if (!isset($this->balance[$wallet])) {
		return false;
		}
		return true;
	}
	function insert_payment($to_do,$wallet,$money) {
		if (!$this->check_wallet($wallet))return $this->check_wallet($wallet);
$balance=$this->balance[$wallet]['money']+$money;
$descr=mysql_escape_string($descr);
 if ($balance >= 0)  {
	 $sql="INSERT INTO `payment` (`wallet_id`, `money`, `descr`,`u_id`,`status`,`guid`) VALUES ('".$this->balance[$wallet]['id']."', '$money', '{$to_do}','{$this->u_id}','1','".md5(time().$this->u_id.$money)."');";
	 if (mysql_query($sql) or die($sql)) {
	 $this->update_balance($wallet);		 
	 return array($to_do => $money, 'wallet'=>$this->balance[$wallet],'ok'=>1);
	 }
 } else {
 return array('error'=>NOT_ENOUGHT_MONEY,'ok'=>0);
 }
	}
    function todo($balance)
    {
		$balance=explode(' ',$balance);
		if ($balance[0] == '') {
		return $this->balance;
		} else {
			switch ($balance[0]) {
				case 'SHOW': {
					return $this->show($balance[1]);
					break;
				}
				case 'SHOWALL': {
					return $this->show_all($balance[1]);
					break;
				}
				case 'ADD': {
					return $this->insert_payment($balance[0],$balance[1],(float)$balance[2]);
					break;
				}
				case 'TAKE': {
					return $this->insert_payment($balance[0],$balance[1],(float)$balance[2]*(-1));
					break;
				}
				case 'CONVERT': {
					return $this->convert($balance[1],$balance[2],(float)$balance[3]);
					break;					
				}
			}
		}
    }
  }
}
