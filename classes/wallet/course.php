<?php
namespace wallet
{
  class course
  {
	  var $money;
    function __construct()
    {
		$this->wallets=explode(' ',"RUB USD EUR KGS");	
		$this->money[CURRENT_MONEY_TYPE]=RUR;
		$this->money[RUR]=1;
		// config load course
		$xml=file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp');
		$this->_parce($xml);
		//
    }
	function _parce($xml) {
				$currency=simplexml_load_string($xml);
//				$this->Date=$currency->Date;
				foreach($currency->Valute as $item) {
				$item=(array)$item;
				$value=(float)str_replace(',','.',$item[Value]);
				if (in_array($item[CharCode],$this->wallets)) {
				$this->money[$item[CharCode]]=$value/$item[Nominal];
				}
				}
	}
	function update_course($todo) {
		if ($this->money[$todo] == '') return array('error'=>MONEY_NOT_FOUND);
$coef=$this->money[$this->money[CURRENT_MONEY_TYPE]]/$this->money[$todo];
	foreach ($this->money as $money=>$value) {
		$new_money[$money]=$coef*$value;
	}
	$new_money[CURRENT_MONEY_TYPE]=$todo;
	return $new_money;
	}
    function todo($todo)
    {
		if ($todo == '') return $this->money;
		return $this->update_course(strtoupper($todo));
    }
  }
}