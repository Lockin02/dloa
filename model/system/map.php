<?php

class model_system_map extends model_base {

    function __construct() {
        parent :: __construct();
    }
    function getMu(){
        $sql="select j.func_id_str , j.name , u.func_id_yes , u.func_id_no
            from user u  left join user_jobs j on (u.jobs_id = j.id) 
            where user_id='".$_SESSION['USER_ID']."' ";
        $query=$this->_db->query($sql);
        while ($row=$this->_db->fetch_array($query)) {
                $funcIdY=$row["func_id_yes"];
                $funcIdN=$row["func_id_no"];
                $funcIdStr=$row["func_id_str"].",";
        }
        $funcIdY=explode(',',$funcIdY);
        $funcIdN=explode(',',$funcIdN);
        $funcIdStr=explode(',',$funcIdStr);
        $funcIdYArr=array_unique(array_merge_recursive($funcIdY,$funcIdStr));
        foreach ($funcIdN as $key=>$val) //去除禁止权限
        {
            if (in_array($val,$funcIdYArr))
            {
                unset($funcIdYArr[array_search($val,$funcIdYArr)]);
            }
        }
        $mu=array();
        foreach($funcIdYArr as $val){
            $key=substr($val,0,3);
            $mu[$key]=trim($key,'_');
        }
        //菜单
        if($_SESSION['USER_ID']=='admin'){
            $sql = "select menu_id as mi , menu_name as mn  , image as mg
                from sys_menu where 
                    closed='0'
                order by taxis_id ASC";
        }else{
            $sql = "select menu_id as mi , menu_name as mn  , image as mg
                from sys_menu where menu_id in ('" . implode("','", $mu) . "') 
                    and closed='0'
                order by taxis_id ASC";
        }
        $query=$this->_db->query($sql);
        $i=0;
        while($row=$this->_db->fetch_array($query)){
            if ($i%6==0) $res.='<tr><td>';
            $res.='<div class="divmu">
                    <ul id="tt" class="easyui-tree"  url="privdata.php?ckmu='.$row['mi'].'"  >  
                    </ul>
                </div>';
            $i++;
            if ($i%6==0) $res.='</td></tr>';
        }
        $res.='</ul></td></tr>';
        return $res;
    }
}

?>