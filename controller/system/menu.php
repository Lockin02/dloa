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
     * �б�
     */
    function c_list(){
        $this->model_list();
    }
    /**
     * ����
     */
    function c_data(){
        $this->model_data();
    }
    /**
     * �޸�
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
        $appArr=array('1'=>"�����",'2'=>"����Ա���",'3'=>"�������",'4'=>"�ಿ�����",'5'=>"����+����Ա���");
        $modelArr=array('1'=>"ϵͳ",'2'=>"��������",'3'=>"���ݱ���",'4'=>"����",'5'=>"��Ϣ",'6'=>"����",'7'=>"����",'8'=>"�з�",'9'=>"����",'10'=>"ҵ��");
        $pvLeaveArr=array('1'=>"һ��(����Ա)",'2'=>"����(��˾�쵼)",'3'=>"����(�����쵼)",'4'=>"����(�粿���쵼)",'5'=>"�ļ�(ָ����Ա)",'6'=>"�弶(��ְͬλ)",'7'=>"����(��ͬ����)",'8'=>"�߼�(������)",'9'=>"�˼�(�粿��)",'10'=>"�ż�(������)");
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
        		/********************* ����޸Ĳ��� *************************/
        if($info['isSystem']==1){
        	 $this->show->assign('isSystem', 'checked');
        }else{
        	$this->show->assign('isSystem', '');
        }
		/********************* ����޸Ĳ��� *************************/
        $this->show->assign('id', $id);
        $this->show->assign('type', is_numeric($pid)?'func':'menu');
        $this->show->display('system_menu_edit');
    }
    /**
     * �޸�
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
     * �޸��ύ
     */
    function c_editsub(){
        $res=$this->model_editsub();
        if(!$res){
            echo 'ʧ�ܣ�';
        }else{
            echo '�ɹ���';
        }
    }
    /**
     * ��ʾ��ͨ����
     */
    function c_showobj(){
        $type=$_GET['type'];
        $data=$this->showobj();
        if($type=='menu'){
            $res='<tr>
                    <th width=100>����</th>
                    <th width=180>ְλ</th>
                    <th>��Ա</th>
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
                    <th width=100>Ȩ����/��</th>
                    <th width=100>������</th>
                    <th width=100>��ͨ����</th>
                    <th>��ͨ����</th>
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