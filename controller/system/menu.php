<?php
class controller_system_menu extends model_system_menu {

    public $show;

    function __construct(){
        parent::__construct();
        $this->show = new show();
    }
    
    function c_index(){
        $this->show->assign('data_url', '?model=system_menu&action=data');
        $this->show->assign('url', '?model=system_menu&action=list');
        $this->show->display('system_menu_index');
    }
    /**
     * 列表
     */
    function c_list(){
        $this->model_list();
    }
    /**
     * 数据
     */
    function c_data(){
        $this->model_data();
    }
    /**
     * 修改
     */
    function c_edit(){
        $id=$_GET['id'];
        $pid=$_GET['pid'];
        $info=$this->model_info($id, $pid);
        $this->show->assign('url', '?model=system_menu&action=editsub');
        $this->show->assign('name', $info['name']);
        $this->show->assign('sort', $info['sort']);
        $this->show->assign('file', $info['file']);
        
        $this->show->assign('adminNames', $info['adminNames']);
        $this->show->assign('deptId', $info['deptId']);
        $this->show->assign('adminIds', $info['adminIds']);
        $appArr=array('1'=>"免审核",'2'=>"管理员审核",'3'=>"部门审核",'4'=>"多部门审核",'5'=>"部门+管理员审核");
        $modelArr=array('1'=>"系统",'2'=>"基础设置",'3'=>"数据报表",'4'=>"公共",'5'=>"信息",'6'=>"服务",'7'=>"财务",'8'=>"研发",'9'=>"工程",'10'=>"业务");
        $pvLeaveArr=array('1'=>"一级(管理员)",'2'=>"二级(公司领导)",'3'=>"三级(部门领导)",'4'=>"三级(跨部门领导)",'5'=>"四级(指定人员)",'6'=>"五级(相同职位)",'7'=>"六级(相同工作)",'8'=>"七级(本部门)",'9'=>"八级(跨部门)",'10'=>"九级(所有人)");
        foreach($appArr as $key =>$val){
        	if($key==$info['appTypeId']){
        		$appStr.="<option value='$key' selected>$val</option>";
        	}else{
        		$appStr.="<option value='$key'>$val</option>";
        	}
        }
        foreach($modelArr as $key =>$val){
        	if($key==$info['modelType']){
        		$modelStr.="<option value='$key' selected>$val</option>";
        	}else{
        		$modelStr.="<option value='$key'>$val</option>";
        	}
        }
        foreach($pvLeaveArr as $key =>$val){
        	if($key==$info['pvLeave']){
        		$pvLeaveStr.="<option value='$key' selected>$val</option>";
        	}else{
        		$pvLeaveStr.="<option value='$key'>$val</option>";
        	}
        }
        $this->show->assign('appOption', $appStr);
        $this->show->assign('pvLeaveOption', $pvLeaveStr);
        $this->show->assign('modelOption', $modelStr);
        		/********************* 外包修改部分 *************************/
        if($info['isSystem']==1){
        	 $this->show->assign('isSystem', 'checked');
        }else{
        	$this->show->assign('isSystem', '');
        }
		/********************* 外包修改部分 *************************/
        $this->show->assign('id', $id);
        $this->show->assign('type', is_numeric($pid)?'func':'menu');
        $this->show->display('system_menu_edit');
    }
    /**
     * 修改
     */
    function c_add(){
        $id=$_GET['id'];
        $pid=$_GET['pid'];
        $info=$this->model_info($id, $pid);
        $this->show->assign('url', '?model=system_menu&action=editsub');
        $this->show->assign('name', $info['name']);
        $this->show->assign('sort', $info['sort']);
        $this->show->assign('file', $info['file']);
        $this->show->assign('pid', $pid);
        $this->show->assign('type', 'add');
        $this->show->display('system_menu_edit');
    }
    /**
     * 修改提交
     */
    function c_editsub(){
        $res=$this->model_editsub();
        if(!$res){
            echo '失败！';
        }else{
            echo '成功！';
        }
    }
    /**
     * 显示开通对象
     */
    function c_showobj(){
        $type=$_GET['type'];
        $data=$this->showobj();
        if($type=='menu'){
            $res='<tr>
                    <th width=100>部门</th>
                    <th width=180>职位</th>
                    <th>人员</th>
                </tr>';
            if(!empty ($data)){
                foreach($data as $dkey=>$dval){
                    $i=0;
                    $dcount=count($dval);
                    foreach($dval as $key=>$val){
                        if($val['ftype']=='d'){
                            $sd='style="color:blue;"';
                            $sj='';
                            $su='';
                        }elseif($val['ftype']=='j'){
                            $sj='style="color:blue;"';
                            $sd='';
                            $su='';
                        }elseif($val['ftype']=='u'){
                            $su='style="color:blue;"';
                            $sj='';
                            $sd='';
                        }
                        $res.='<tr>
                                    '.($i==0? '<td rowspan="'.$dcount.'" '.$sd.'>'.$val['dept_name'].'</td>':'').'
                                    <td '.$sj.'>'.$val['job_name'].'</td>
                                    <td '.$su.'>'.$val['user_name'].'</td>
                              </tr>';
                        $i++;
                    }
                }
            }
        }elseif($type=='prv'){
            $pvurl=new model_pvurl();
            
            $res='<tr>
                    <th width=100>权限名/类</th>
                    <th width=100>控制名</th>
                    <th width=100>开通对象</th>
                    <th>开通内容</th>
                </tr>';
            if(!empty ($data)){
                foreach($data as $dkey=>$dval){
                    $i=0;//models
                    foreach($dval as $mkey=>$mval){
                        $y=0;
                        $mam=count($mval);
                        foreach($mval as $key=>$val){
                            $content=$pvurl->show_info($val['ttype'],$val['act'], $val['content']);
                            $res.='<tr>
                                        '.($i==0? '<td rowspan="{$dcount}" >'.$dkey.'</td>':'').'
                                        '.($y==0? '<td rowspan="'.$mam.'" >'.$mkey.'</td>':'').'
                                        <td >'.$val['obj'].'</td>
                                        <td >'.$content.'</td>
                                  </tr>';
                            $i++;
                            $y++;
                        }
                    }
                    $res=str_replace('{$dcount}',$i,$res);
                }
            }
        }
        
      $this->show->assign('data', $res);
        $this->show->display('system_menu_show');
    }
    
     function c_deptData(){
        $this->model_deptData();
    } 
    
    
    
}
?>