<?php
class model_system_menu extends model_base {
    
    function __construct() {
        parent :: __construct();
    }
    /**
     * 列表
     */
    function model_list(){
        $sql="select MENU_ID , MENU_NAME from sys_menu where menu_id order by taxis_id";
        $query=$this->_db->query($sql);
        $rep->total=$this->_db->num_rows($query);
        while($row=$this->_db->fetch_array($query)){
            $rep->rows[]=un_iconv(array(
                'id'=>$row['MENU_ID'],
                'name'=>$row['MENU_NAME'],
                "state"=>"closed"
            ));
        }
        echo json_encode($rep);
    }
    /**
     * 数据
     */
    function model_data(){
        $pid=$_GET['pid'];
        $data=array();
        $sql="select f.MENU_ID , f.FUNC_NAME , count(p.id ) as pam from sys_function f
                left join purview p on (p.menuid=f.menu_id and p.control=1 )
                 where f.MENU_ID like '".$pid."%' and f.MENU_ID !='".$pid."'  
                group by f.func_id 
                order by f.MENU_ID";
        $query=$this->_db->query($sql);
        while($row=$this->_db->fetch_array($query)){
            $pid=substr($row['MENU_ID'], 0, -2);
            if(empty($data[$pid])){
                $data[$row['MENU_ID']]=un_iconv(array(
                    'name'=>$row['FUNC_NAME'],
                    'pid'=>$pid,
                    'mid'=>$row['MENU_ID'],
                    'pam'=>$row['pam']
                ));
            }else{
                $data[$pid]['state']='closed';
            }
        }
        if(!empty($data)){
            foreach($data as $key=>$val){
                $rep->rows[]=array(
                    'id'=>$key,
                    'name'=>$val['name'],
                    '_parentId'=>$val['pid'],
                    'state'=>$val['state'],
                    'pam'=>$val['pam']
                );
            }
        }
        echo json_encode($rep);
    }
    /**
     * 
     */
    function model_info($id,$pid){
        if(is_numeric($pid)){
			/************************ 外包修改部分 ***********************/
            $sql="select FUNC_NAME as name, taxis_id  as sort , func_code as file,isSystem,appTypeId,adminIds,adminNames,pvLeave,modelType,deptId  from sys_function where menu_id='".$id."' ";
			/************************ 外包修改部分 ***********************/
        }else{
			/************************ 外包修改部分 ***********************/
            $sql="select MENU_NAME as name, taxis_id  as sort,isSystem,appTypeId,adminIds,adminNames,pvLeave,modelType,deptId  from sys_menu where menu_id='".$id."' ";
			/************************ 外包修改部分 ***********************/
        }
        $res=$this->_db->get_one($sql);
        return $res;
    }
    /**
     * 修改处理
     */
    function model_editsub(){
        extract ( $_POST );
        $deptId=implode(',',$_POST['deptId']);
        if($type=='menu'){
            /************************ 外包修改部分 ***********************/
            $sql="update sys_menu
                    set menu_name = '".$name."'
                        , taxis_id ='".$sort."',isSystem='$isSystem',appTypeId='$appTypeId'
                        ,deptId='$deptId'
                        ,adminIds='$adminIds'
                        ,adminNames='$adminNames'
                        ,pvLeave='$pvLeave'
                        ,modelType='$modelType'
                  where menu_id = '".$id."' ";
			/************************ 外包修改部分 ***********************/
            return $this->query($sql);
        }elseif($type=='func'){
            /************************ 外包修改部分 ***********************/
            $sql="update sys_function
                    set func_name = '".$name."'
                        , taxis_id ='".$sort."'
                        , func_code='".$url."'
                        ,appTypeId='$appTypeId'
                        ,deptId='$deptId'
                        ,adminIds='$adminIds'
                        ,adminNames='$adminNames'
                        ,pvLeave='$pvLeave'
                        ,modelType='$modelType'
                        
                  where menu_id = '".$id."' ";
			/************************ 外包修改部分 ***********************/
            return $this->query($sql);
        }elseif($type=='add'&&  is_numeric($pid)){
            try {
                $this->query('START TRANSACTION');
                
                $sql="select max(right(menu_id,2)) as maxm from sys_function where menu_id like '".$pid."__' ";
                $res=$this->get_one($sql);
                if(empty($res['maxm'])){
                    $pid=$pid.'01';
                }else{
                    $pid=$pid.sprintf("%02.0f",($res['maxm']+1));
                }
                
                			/************************ 外包修改部分 ***********************/
                $sql="insert into sys_function
                        (menu_id , func_name , func_code , taxis_id,isSystem,appTypeId,deptId,adminIds,adminNames,pvLeave,modelType)
                      values
                        ('".$pid."' , '".$name."' , '".$url."' , '".$sort."', '".$isSystem."','$appTypeId','$deptId','$adminIds','$adminNames','$pvLeave','$modelType')";
                $res=$this->query($sql);
			/************************ 外包修改部分 ***********************/
                //权限列表
                $sql = "insert into purview (menuid , name ) values('$pid','访问')";
                $this->query($sql);
                //赋予权限
                if ($_POST['jobs_pv']=='all_jobs')
	        {
                    $this->query("update user_jobs set func_id_str = concat(func_id_str,',_$pid') ");
	        }elseif ($_POST['jobs_pv']=='jobs' && $_POST['jobsid']){
                    $this->query("update user_jobs set func_id_str = concat(func_id_str,',_$pid') where id in(".implode(',',$_POST['jobsid']).")");
                    $this->query("update user set func_id_yes=concat(func_id_yes,',_$pid') where user_id='admin'");
	        }else{
                    $this->query("update user set func_id_yes=concat(func_id_yes,',_$pid') where user_id='admin'");
	        }
                $this->query("COMMIT");
            } catch (Exception $e) {
                $this->query("ROLLBACK");
                return false;
            }
            return true;
        }
    }
    function showobj(){
        $id=$_GET['id'];
        $type=$_GET['type'];
        $res=array();
        if($type=='menu'){
            //员工
            $sql="select d.dept_id , d.dept_name , j.id as job_id , j.name as job_name 
                    , u.user_id , u.user_name
                    ,  case when d.funcstr like '%"."_".$id."%' then 'd'  
                        when  j.func_id_str like '%"."_".$id."%' then 'j' 
                        when u.func_id_yes like '%"."_".$id."%'  then 'u' 
                        end as ftype
                from user u
                left join user_jobs j on(u.jobs_id=j.id)
                left join department d on (u.dept_id=d.dept_id) 
                where ( u.func_id_yes like '%"."_".$id."%' or j.func_id_str like '%"."_".$id."%' or d.funcstr like '%"."_".$id."%' )
                    and d.delflag=0
                    and u.del=0 and u.has_left=0
                order by d.depart_x , j.id , u.user_name ";
            $query=$this->_db->query($sql);
            while($row=$this->_db->fetch_array($query)){
                
                $res[$row['dept_id']][$row['job_id']]['job_name']=
                        ($row['ftype']=='j'?'<a href="?model=jobs&action=edit_func&id='.$row['job_id']
                        .'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false"'
                        .'title="修改 '.$row['job_name'].'权限" class="thickbox">'
                        .$row['job_name'].'</a>':$row['job_name']);
                $res[$row['dept_id']][$row['job_id']]['user_name'].=
                        ($row['ftype']=='u'?'<a href="?model=user&action=edit_func_list&userid='.$row['user_id']
                        .'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false" title="修改'.$row['user_name']
                        .'权限" class="thickbox">'.$row['user_name'].'</a>':$row['user_name']).'；';
                $res[$row['dept_id']][$row['job_id']]['ftype']=$row['ftype'];
                $res[$row['dept_id']][$row['job_id']]['dept_name']=$row['dept_name'];
            }
        }elseif($type=='prv'){
            $sql="select
                        t.name as tname , t.typeid as ttype , t.act
			, i.type as itype , i.content , i.visit , i.tid , i.typeid
                        , i.userid , i.jobsid , i.deptid , j.dept_id
			,u.user_name,j.name as job_name,d.dept_name
                        ,i.id , p.name , p.models
                from
                        purview_info as i
                        left join purview_type as t on (t.id=i.typeid)
                        left join purview as p on (i.tid=p.id)
                        left join user as u on (u.user_id=i.userid)
                        left join user_jobs as j on (j.id=i.jobsid)
                        left join department as d on (d.dept_id=i.deptid)
                where
                        p.menuid=".$_GET['id']."
                order by i.userid , i.jobsid , i.deptid";
            $query=$this->_db->query($sql);
            while($row=$this->_db->fetch_array($query)){
                //$rs['type']==1 ? '用户' :($rs['type']==2 ? '职位' : ($rs['type']==3 ? '部门' : '所有人')
                $res[$row['name']][$row['tname']][$row['id']]['models']=$row['models'];
                $res[$row['name']][$row['tname']][$row['id']]['ttype']=$row['ttype'];
                $res[$row['name']][$row['tname']][$row['id']]['act'].=$row['act'];
                $res[$row['name']][$row['tname']][$row['id']]['content']=$row['content'];
                $url = '<a href="?model=pvurl&action='
                    .(($row['ttype']==1) ? 'show_act' :($row['ttype']==2 ? 'show_field' :'index&content='.$row['content']))
                    . '&act='.$row['act'].'&visit='.$row['visit'].'&id='.$row['id']
                    .'&tid='.$row['tid'].'&type='.$row['itype'].'&typeid='.$row['typeid']
                    .'&userid='.$row['userid'].'&username='.$row['user_name'].'&jobsid='
                    .$row['jobsid'].'&deptid='.$row['deptid'].'&dept_id='.$row['dept_id']
                    .'&placeValuesBefore&TB_iframe=true&modal=false"class="thickbox" '
                    .' title="修改" >';
                if($row['itype']=='1'){
                    $obj=$url.$row['user_name'].'</a>';
                }elseif($row['itype']=='2'){
                    $obj=$url.$row['job_name'].'</a>';
                }elseif($row['itype']=='3'){
                    $obj=$url.$row['dept_name'].'</a>';
                }else{
                    $obj=$url.'所有'.'</a>';
                }
                $res[$row['name']][$row['tname']][$row['id']]['obj']=$obj;
            }
        }
        return $res;
    }
    
    /**
     * 数据
     */
    function model_deptData(){
        $data=array();
        $sql="SELECT DEPT_ID,DEPT_NAME,PARENT_ID FROM department WHERE DelFlag=0 ORDER BY DEPT_ID";
        $query=$this->_db->query($sql);
        $sidI=explode(',',$sids=$_GET['deptId']);
        $data=array();
        while($row=$this->_db->fetch_array($query)){
        	$data[$row['DEPT_ID']]['DEPT_ID']=$row['DEPT_ID'];
        	$data[$row['DEPT_ID']]['DEPT_NAME']=$row['DEPT_NAME'];
        	$data[$row['DEPT_ID']]['PARENT_ID']=$row['PARENT_ID'];
        }
        //$pid=$_POST['id'];
        $dataI=$this->model_getSub($data,0,$sidI);
       $dataI = array_merge(array(array('id'=>'0','text'=>'所有')), $dataI); 
        //print_r($dataI);
        echo json_encode(un_iconv($dataI));
    }
    
   function model_getSub($data,$pid=0,$checkedArr){
    	if(is_array($data)&&$data){
        	foreach($data as $key =>$val){
        		if($pid==0&&$val['PARENT_ID']==0){
        			$subData=$this->model_getSub($data,$val['DEPT_ID']);
        			if($subData){
        				if(in_array($val['DEPT_ID'],$checkedArr)){
        					$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"state"=>"closed","children"=>$subData,"checked"=>true);
        				 }else{
        			 		$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"state"=>"closed","children"=>$subData);	
        			 	}	
        				
        			}else{
        				if(in_array($val['DEPT_ID'],$checkedArr)){
        					$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"checked"=>true);
        			 	}else{
        			 		$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME']);
        			 	}	
        			}
        			
        		}else if($pid==$val['PARENT_ID']){
        		     if(in_array($val['DEPT_ID'],$checkedArr)){
        				$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME'],"checked"=>true);
        			 }else{
        			 	$dataI[]=array('id'=>$val['DEPT_ID'],'text'=>$val['DEPT_NAME']);
        			 }	
        			
        		}
        		
        	}
        	return $dataI;
        }
    	
    }
    
}
?>