<?php
/**
 * Created on 2013-12-23
 * Created by Show
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 把所有的业务注册到这个类,然后别的业务调用的时候可以直接引入
 * formBelong 数据归属 必填
 * businessBelong 业务归属 可以为空
 */
$belongArr = array(
    'cost_summary_list' => array( # 费用报销
        'formBelong' => 'CostManComId',
        'businessBelong' => 'CostBelongComId'
    )
);

$defaultBelongArr = array(
    'formBelong' => 'formBelong',
    'businessBelong' => 'businessBelong'
);
?>