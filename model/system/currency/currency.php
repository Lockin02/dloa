<?php

/**
 * @author Administrator
 * @Date 2011年10月26日 15:47:12
 * @version 1.0
 * @description:货币换算 Model层
 */
class model_system_currency_currency extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_system_currency";
        $this->sql_map = "system/currency/currencySql.php";
        parent::__construct();
    }

    /**
     * @param $currency
     * @return bool|mixed
     */
    function getCurrencyInfo_d($currency)
    {
        $rs = $this->find(array('currency' => $currency), null, 'Currency AS currency,rate ,currencyCode');
        return empty($rs) ? array(
            'currency' => '人民币', 'rate' => '1', 'currencyCode' => 'CNY'
        ) : $rs;
    }
}