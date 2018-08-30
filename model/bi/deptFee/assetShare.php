<?php

/**
 * ²¿ÃÅÕÛ¾É·ÖÌ¯
 * Class model_bi_deptFee_assetShare
 */
class model_bi_deptFee_assetShare extends model_base
{

    function __construct()
    {
        $this->tbl_name = "oa_bi_asset_share";
        $this->sql_map = "bi/deptFee/assetShareSql.php";
        parent::__construct();
    }
}