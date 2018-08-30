<?php
/**
 * @author hhj
 * @Date 2017-11-28 13:22:20
 * @version 1.0
 * @description: Ìå¼ìÉêÇë±í Model²ã
 */
class model_extSevFuns_bodyChk_bodychk extends model_base {

    function __construct() {
        $this->tbl_name = "oa_extfuns_bodychk_record";
        $this->sql_map = "extSevFuns/bodyChk/bodychkSql.php";
        parent::__construct ();
    }
}