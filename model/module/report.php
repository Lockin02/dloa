<?php

class model_module_report extends model_base {
    
    private $objExcel;
    function __construct() {
        parent :: __construct();
    }

    //报表展示
    function model_list($kid) {
        $sql = "select 
                name , sea , xls , rep , page 
            from 
                model_rep
            where
                rand_key='$kid' ";
        $rs = $this->_db->get_one($sql);
        $config['nav'] = array(
            'sea' => $rs['sea']//搜索栏
            , 'xls' => $rs['xls']//excel导出
            , 'rep' => $rs['rep']//图表
        );
        $config['grid'] = array(
            'fcs' => array(
                0 => array(
                    0 => array(
                        'field' => 'ckbox'
                        , 'checkbox' => 'true'
                    )
                    , 1 => array(
                        'field' => 'code'
                        , 'title' => '代码'
                        , 'width' => '380'
                        , 'sortable' => true
                        , 'rowspan' => '2'
                        , 'colspan' => ''
                        , 'formatter' => ''
                    )
                )//第一行
            )
            , 'cs' => array(
                0 => array(
                    1 => array(
                        'field' => ''
                        , 'title' => '基础信息'
                        , 'width' => ''
                        , 'sortable' => ''
                        , 'rowspan' => ''
                        , 'colspan' => '3'
                        , 'formatter' => ''
                    )
                    , 2 => array(
                        'field' => 'opt'
                        , 'title' => '操作'
                        , 'width' => '100'
                        , 'align' => 'center'
                        , 'sortable' => ''
                        , 'rowspan' => '2'
                        , 'colspan' => ''
                        , 'formatter' => ''
                    )
                )//第一行
                , 1 => array(
                    0 => array(
                        'field' => 'name'
                        , 'title' => '名称'
                        , 'width' => '120'
                        , 'sortable' => true
                        , 'rowspan' => ''
                        , 'colspan' => ''
                        , 'formatter' => ''
                    )
                    , 1 => array(
                        'field' => 'addr'
                        , 'title' => ' 地址'
                        , 'width' => '120'
                        , 'sortable' => ''
                        , 'rowspan' => ''
                        , 'colspan' => ''
                        , 'formatter' => ''
                    )
                    , 2 => array(
                        'field' => 'area'
                        , 'title' => 'col4'
                        , 'width' => '250'
                        , 'sortable' => ''
                        , 'rowspan' => ''
                        , 'colspan' => ''
                        , 'formatter' => ''
                    )
                )
            )
        );
        return $config;
    }

    /**
     * 报表数据
     */
    function model_data() {
        $kid = $_REQUEST['kid'];
        $page = $_POST['page'];
        $limit = $_POST['rows'];
        $sort = $_POST['sort'];
        $order = $_POST['order'];
        $start = $limit * $page - $limit;
        //搜索信息
        $seakey = $_POST['seakey'];
        $sqls = '';
        if (!empty($seakey)) {
            $sqls.=' and 1=1 ';
        }
        //获取sql语句
        $sql = "select sqlstr from model_rep where rand_key='$kid'";
        $rs = $this->_db->get_one($sql);
        $sqlm = $rs['sqlstr'];
        /**
         * 统计总数
         */
        $sqlc = 'select count(*) ' . strstr($sqlm, 'from');
        $sql = $sqlc . $sqls;
        $rs = $this->_db->get_one($sql);
        $count = $rs['count(*)'];
        $rep->total = $count;
        $sql = "$sqlm
                $sqls
              order by $sort $order
              limit $start , $limit ";
        $query = $this->_db->query($sql);
        while ($row = $this->_db->fetch_array($query)) {
            $rep->rows[] = un_iconv($row);
        }
        return $rep;
    }

    //报表设置列表
    function model_set_data() {
        $page = $_POST['page'];
        $limit = $_POST['rows'];
        $sort = $_POST['sort'];
        $order = $_POST['order'];
        $start = $limit * $page - $limit;
        //搜索信息
        $seakey = $_POST['seakey'];
        $sqls = '';
        if (!empty($seakey)) {
            $sqls.=' and 1=1 ';
        }
        /**
         * 统计总数
         */
        $sqlc = 'select count(*) from model_rep where 1 ' . $sqls;
        $rs = $this->_db->get_one($sql);
        $count = $rs['count(*)'];
        $rep->total = $count;
        $sql = "select 
                $sqls
              order by $sort $order
              limit $start , $limit ";
        $query = $this->_db->query($sql);
        while ($row = $this->_db->fetch_array($query)) {
            $rep->rows[] = un_iconv($row);
        }
        return $rep;
    }

    //
    function model_list_head() {
        
    }

//  获取表头数据
    function model_get_head() {
//定义report，读取数据；reportkey：报表key
        $key = $_REQUEST['reportkey'];
        return $this->get_rep($key);
    }
    
    function get_rep($key,$type='key'){
        if($type=='key'){
            //获取sql语句
            $sql = "select  name , sqlstr , tabstr , rows , cols , coltotals , rand_key as kid , id , updis , wrdis , r.* from model_rep r where rand_key='$key'";
            $rs = $this->_db->get_one($sql);
        }elseif($type=='id'){
            //获取sql语句
            $sql = "select  name , sqlstr , tabstr , rows , cols , coltotals , rand_key as kid , id , updis , wrdis , r.* from model_rep r where id='$key'";
            $rs = $this->_db->get_one($sql);
        }
        //模板
        $sql = "SELECT f.* FROM model_rep_fm f where f.rep_id='".$rs['id']."'";
        $rs['fm'] = $this->_db->getArray($sql);
        if(!empty($rs['fm'])){
            $tabArr=array();
            foreach($rs['fm'] as $val){
                $tabArr[$val['index_row']][$val['index_col']]['name']=$val['name'];
                $tabArr[$val['index_row']][$val['index_col']]['to_rows']=$val['to_rows'];
                $tabArr[$val['index_row']][$val['index_col']]['to_cols']=$val['to_cols'];
            }
            $rs['fm']=$tabArr;
        }
        if(!empty($rs['dim_ids'])){
            $sql = "SELECT f.* FROM model_rep_dim f where  find_in_set( f.id , '".$rs['dim_ids']."' ) ";
            $rs['dim'] = $this->_db->getArray($sql);
        }
        return $rs;
    }
    
    function getRepData($repkey,$dim,$show='all') {
        $repData=array();
        $repData=$this->get_rep($repkey);
        $repData['list_id']=$this->getRepList($repData['id'], $dim);
        
        $sql="select name , rep_id , list_id , index_col , index_row , to_cols , to_rows , index_type 
            from model_rep_data where list_id = '".$repData['list_id']."' order by index_row , index_col";
        $repData['rep_data'] = $this->_db->getArray($sql);
        if(!empty($repData['rep_data'])){
            $tabArr=array();
            foreach($repData['rep_data'] as $val){
                $tabArr[$val['index_row']][$val['index_col']]['name']=$val['name'];
                $tabArr[$val['index_row']][$val['index_col']]['to_rows']=$val['to_rows'];
                $tabArr[$val['index_row']][$val['index_col']]['to_cols']=$val['to_cols'];
                $tabArr[$val['index_row']][$val['index_col']]['header']=$val['index_type'];
            }
            $repData['rep_data']=$tabArr;
        }
        if($show=='all'){
            $repData['rep_table'] = $this->showRep($repData['rep_data'],'table');
            return $repData;
        }elseif($show=='data'){
            return $repData['rep_data'];
        }elseif($show=='table_edit'){
            $repData['rep_table'] = $this->showRep($repData['rep_data'],'table_edit');
             return $repData;
        }elseif($show=='excel'){
            $tabArr=  un_iconv($repData['rep_data']);
            $this->objExcel = new includes_classes_excelphp();
            $this->objExcel->dataToExcel($tabArr);
            $this->objExcel->OutPut();
        }
    }
    
    function delRepData($repkey,$dim){
        $repData=array();
        $repData=$this->get_rep($repkey);
        $repData['list_id']=$this->getRepList($repData['id'], $dim);
        $sql="delete
            from model_rep_data where list_id = '".$repData['list_id']."' ";
        return $this->_db->query($sql);
    }

    /*
     * 读取数据-20110717
     */

    function model_get_data($head, $flag = 'html') {
//定义rep，读取数据；
        $rep = $_REQUEST['rep'];
        $gl = new includes_class_global();
        $tabdata = $gl->getHtmlTables($head['tabstr'], 'rep');
        $tabsc = count($tabdata['tab']);
        $res['tabrows'] = $tabsc;
        $tabs = $tabdata['code'];
        $res['tabcols'] = count($tabs);

        $tabtotal = array();
        $trstr = '';
        $sql = $head['sqlstr'];
//        获取搜索信息，生成sql
        $repsql = '';
        if (!empty($rep)) {
            foreach ($rep as $key => $val) {
                if (in_array('$' . $key, $tabs)) {
                    $repsql.=$key . " like '%" . $val . "%' and  ";
                } elseif ($key == 'seakey' && !empty($val)) {
                    $tsql = " ( ";
                    foreach ($tabs as $tval) {
                        $tsql.=trim($tval, '$') . " like '%" . $val . "%' or ";
                    }
                    $tsql = trim($tsql, 'or ');
                    $repsql = $tsql . " ) and ";
                }
            }
        }
        if (!empty($repsql)) {
            $sql = " select * from ( $sql ) rep where $repsql 1 ";
        }
        $query = $this->_db->query($sql);
        $res['sqlrows'] = mysql_affected_rows();
        $res['trows'] = $res['sqlrows'] + $res['tabrows'];
        if ($flag == 'html') {
            $head_tab = substr($head['tabstr'], 0, stripos($head['tabstr'], '<tr>') + 4);
            $head['tabstr'] = str_replace($head_tab, $head_tab . '<th rowspan="' . $tabsc . '">序号</th>', $head['tabstr']);
            $i = 0;
            while ($row = $this->_db->fetch_array($query)) {
                $i++;
                $trstr.='
                <tr>
                    <td>' . $i . '</td>
                ';
                foreach ($tabs as $key => $val) {
                    $tabtotal[$key]+=$row[trim($val, '$')];
                    $trstr.='<td>' . $row[trim($val, '$')] . '</td>';
                }
                $trstr.='
                </tr>
                ';
            }
            if (!empty($head['coltotals'])) {
                $cts = explode(',', $head['coltotals']);
                $trstr.='
                <tr>
                    <td>合计</td>
                ';
                foreach ($tabtotal as $key => $val) {
                    if (in_array($key + 1, $cts)) {
                        $trstr.='<td>' . num_to_maney_format($val) . '</td>';
                    } else {
                        $trstr.='<td></td>';
                    }
                }
                $trstr.='
                </tr>
                ';
            }
            if (empty($res['sqlrows'])) {
                $trstr = '<tr>
                    <td style="color:red;">无数据</td>
                    ';
                foreach ($tabs as $val) {
                    $trstr.='<td></td>';
                }
                $trstr.='
                </tr>';
                $res['trows']+=1;
            }
            $trstr = str_replace('<td></td>', '<td>&nbsp;</td>', $trstr);
            $res['list'] = substr($head['tabstr'], 0, strripos($head['tabstr'], '<tr>')) . $trstr . '</table>';
            return $res;
        } elseif ($flag == 'xls') {
            $arr = array();
            while ($row = $this->_db->fetch_array($query)) {
                $arrtmp = array();
                foreach ($tabs as $key => $val) {
                    $arrtmp[] = $row[trim($val, '$')];
                }
                $arr[] = $arrtmp;
            }
            $xls = new includes_class_excel();
            $xls->tabSetExcel($tabdata['tab']);
            $xls->arrSetExcel($arr, $tabsc + 1);
            $xls->OutPut();
        }
    }

    /*
     * 添加提交
     */

    function model_addsub() {
        $kid = $_POST['reportkey'];
        $name = $_POST['name'];
        $rows = $_POST['rows'];
        $cols = $_POST['cols'];
        $coltotals = $_POST['coltotals'];
//        $tabstr=trim($_POST['tabstr']);
//        <table id="spsTable" width="100%">
        $tabstr = preg_replace("'<table[^>]*?>'si", '<table id="spsTable" class="ui-table" cellpadding="0" cellspacing="0" width="100%">', trim($_POST['tabstr']));
        //$tabstr = str_replace('<tr', '<tr', $tabstr);
        $tabstr = str_replace('<td', "<th", $tabstr);
        $tabstr = str_replace('</td>', "</th>", $tabstr);
        $sqlstr = preg_replace("'&[^>]*?;'si", ' ', trim($_POST['sqlstr']));
        
        if (empty($kid)) {
            $kid = md5(rand());
            $sql = "insert into model_rep ( name , rows , cols , tabstr , sqlstr , coltotals , rand_key ) 
                values ('$name' ,'$rows' ,'$cols' ,'$tabstr' ,'$sqlstr','$coltotals','$kid')";
            $this->_db->query($sql);
            
        } else {
            $sql = "update model_rep set name='$name' , rows='$rows' , cols='$cols' 
                    , tabstr='$tabstr' , sqlstr='$sqlstr' , coltotals='$coltotals'
                 where rand_key='$kid'";
            $this->_db->query($sql);
            
        }
        if($_REQUEST['tabstr']){
            $rep=$this->get_rep($kid);
            $rep_tab=$this->readTableData($_REQUEST['tabstr']);
            if(!empty($rep_tab)){
                $sql="delete from model_rep_fm where rep_id='".$rep['id']."' ";
                $this->_db->query($sql);
                
                foreach($rep_tab as $key=>$val){
                    foreach($val as $vkey=>$vval){
                        if(!empty($vval['name'])){
                            $sql = "insert into model_rep_fm ( name , rep_id , index_col , index_row , to_cols , to_rows , index_type ) 
                                values ('".$vval['name']."','".$rep['id']."' ,'".$vval['index_col']."' ,'".$vval['index_row']."'
                                        ,'".$vval['to_cols']."' ,'".$vval['to_rows']."','0')";
                            $this->_db->query($sql);
                        }
                    }
                }
            }
        }
        return $kid;
    }

    function model_upExcel() {
        
        $res = array();
        $repkey=$_GET['repkey'];
        //填充参数
        $res['repkey'] = $_GET['repkey'];
        $res['listid'] = $_GET['listid'];
        if($repkey){
            $repInfo=$this->get_rep($repkey);
            $res['repname'] = $repInfo['name'];
            $res['updis']=$repInfo['updis'];
            $res['wrdis']=$repInfo['wrdis'];
            $res['dim_list']=$this->showRepDim($repInfo['dim']);
        }
        if($res['updis']=='block'){
            $excelPhp = new includes_classes_excelphp();
            $updata=$excelPhp->readUpExcelData('file_obj');
            $res['wr_list']='';
            //处理数据
            if (!empty($updata['data'])) {
                //融入模板样式
                $repInfo['rep_data'] = $this->readTableData($updata['data'],'excel',$repInfo['fm']);
                $repInfo = $this->checkRepData($repInfo);
                $res['up_list'] = $this->showRep($repInfo['rep_data'],'table');
                $res['uploadedFile'] = $updata['uploadedFile'];
                //print_r($repInfo['rep_data']);
            } else {
                $res['error_list'] = '<tr><td colspan="256">' . $updata['error'] . '</td></tr>';
            }
        }
        if($res['wrdis']=='block'){
            $repInfo['tabstr'] = str_replace('&nbsp;</th>', '<input type="text" class="wr_class"></input></th>' , $repInfo['tabstr']);
            $res['wr_list']=$repInfo['tabstr'];
        }
        return $res;
    }
    
    function model_upExcelSub(){
        $repInfo=array(); 
        $uploadedFile = urldecode($_POST['uploadedFile']);
        $repkey = $_POST['repkey'];
        if(!empty($uploadedFile)){
            
            $repInfo=$this->get_rep($repkey);
            $repInfo['dim_data']=$_REQUEST['dim'];
            $repInfo['listid'] = $_POST['listid'];
            $excelPhp = new includes_classes_excelphp();
            $repInfo['rep_data']=$excelPhp->readExcelData($uploadedFile);
            $repInfo['rep_data'] = $this->readTableData($repInfo['rep_data'],'excel',$repInfo['fm']);
            $repInfo = $this->checkRepData($repInfo);
            $res=$this->insertRepData($repInfo);
            return ($res);
        }
    }
    
    function getRepList($repid,$dim){
        $cksql="";
        foreach($dim as $key=>$val){
            $cksql.=" and find_in_set('".$key."=".$val."' , dim_data ) ";
        }
        $sql="select dim_data , list_id from ( 
            SELECT list_id , group_concat( concat( code , '=',  val )  )  dim_data  FROM model_rep_dim_data where rep_id ='".$repid."'  group by list_id ) 
            d where 1 ".$cksql;
        $temp=$this->_db->get_one($sql);
        return $temp['list_id'];
    }
    
    function insertRepData($repData){
        $res='';
        if($repData['kid']){
            //检查
            $repData['listid']=$this->getRepList($repData['id'],$repData['dim_data']);
            if(empty($repData['listid'])){
                $sql = "insert into model_rep_data_list ( rep_id , name  ) 
                    values ('".$repData['id']."','".$rep['name']."' )";
                $this->_db->query($sql);
                $repData['listid']=$this->_db->insert_id();
            }else{//清空原先数据
                $sql = "delete from  model_rep_dim_data where list_id ='".$repData['listid']."' ";
                $this->_db->query($sql);
                $sql = "delete from  model_rep_data where list_id ='".$repData['listid']."' ";
                $this->_db->query($sql);
            }
            foreach($repData['dim_data'] as $key=>$val){
                $sql = "insert into model_rep_dim_data ( code , val , list_id , rep_id ) 
                    values ('".$key."','".$val."','".$repData['listid']."' ,'".$repData['id']."' )";
                $this->_db->query($sql);
            }
            foreach($repData['rep_data'] as $key=>$val){
                foreach($val as $vkey=>$vval){
                    if(!empty($vval['name'])){
                        $sql = "insert into model_rep_data ( name , rep_id , list_id , index_col , index_row , to_cols , to_rows , index_type ) 
                            values ('".$vval['name']."','".$repData['id']."','".$repData['listid']."' ,'".$vval['index_col']."' ,'".$vval['index_row']."'
                                    ,'".$vval['to_cols']."' ,'".$vval['to_rows']."','".$vval['header']."')";
                        $this->_db->query($sql);
                    }
                }
            }
        }
        $res['listid']=$repData['listid'];
        return $res;
    }
    
    function updateRepData($listid,$dt){
        $res=0;
        if(!empty($dt)){
            foreach($dt as $key=>$val){
                foreach($val as $vkey=>$vval){
                    if($listid){
                        $sql="select count(*) as am from model_rep_data
                            where list_id ='".$listid."' and index_row='".$key."' and index_col='".$vkey."'  ";
                        $res=$this->_db->get_one($sql);
                        print_r($res);
                        die();
                        if($res['am']){
                            $sql="update model_rep_data set name ='".  trim($vval)."' 
                                where list_id ='".$listid."' and index_row='".$key."' and index_col='".$vkey."'  ";
                            $res=$this->_db->query($sql);
                        }else{
                            $sql="insert into model_rep_data ( name , list_id , index_col , index_row , to_cols , to_rows , index_type ) 
                                values ( '".  trim($vval)."' , '".$listid."' , '".$vkey."' , '".$key."' , 0 , 0 , '' ) ";
                            $res=$this->_db->query($sql);
                        }
                    }
                }
            }
        }
        return $res;
    }
    
    function checkRepData($rep){
        $res=array();
        $res=$rep;
        return $res;
    }
    
    function showRep($rep,$type='table'){
        if($type=='excel'){
            
        }else{//table
            $res='';
            $tmparr=array();
            $maxc=0;
            foreach($rep as $val){
                foreach($val as $vkey=>$vval){
                    if($vkey>$maxc){
                        $maxc=$vkey;
                    }
                }
            }
            $ri=0;
            foreach($rep as $key=>$val){
                $tr='<tr>';
                $ci=0;
                foreach($val as $vkey=>$vval){
                    for($i=0;$i<$vkey;$i++){
                        if(!isset($tmparr[$key][$i])){
                            $tmparr[$key][$i]=$vval['name'];
                            if($type=='table'){
                                $tr.='<td ></td>';
                            }elseif($type=='table_edit'){
                                $tr.='<td ondblclick="changeTd('.$key.','.$i.')" id="td_'.$key.'_'.$i.'"></td>';
                            }
                        }
                    }
                    $tmparr[$key][$vkey]=$vval['name'];
                    $cols='';
                    $rows='';
                    if(!empty($vval['to_cols'])){
                        $cols=' colspan="'.$vval['to_cols'].'" ';
                        for($i=1;$i<$vval['to_cols'];$i++){
                            $tmparr[$key][$vkey+$i]=$vval['name'];
                        }
                    }
                    if(!empty($vval['to_rows'])){
                        $rows=' rowspan="'.$vval['to_rows'].'" ';
                        for($i=1;$i<$vval['to_rows'];$i++){
                            $tmparr[$key+$i][$vkey]=$vval['name'];
                            for($j=1;$j<$vval['to_cols'];$j++){
                                $tmparr[$key+$i][$vkey+$j]=$vval['name'];
                            }
                        }
                    }
                    if($vval['header']=='th'){
                        $tr.='<th '.$cols.$rows.' >'.$vval['name'].'</th>';
                    }else{
                        if($type=='table'){
                            $tr.='<td '.$cols.$rows.'>'.$vval['name'].'</td>';
                        }elseif($type=='table_edit'){
                            $tr.='<td '.$cols.$rows.' ondblclick="changeTd('.$key.','.$vkey.')" id="td_'.$key.'_'.$vkey.'" class="reptd">'.$vval['name'].'</td>';
                        }
                    }
                    $ci=empty($vval['to_cols'])?$vkey:($vkey+$vval['to_cols']-1);
                    
                }
                //加载空栏
                for($i=$ci+1;$i<=$maxc;$i++){
                    if($type=='table'){
                        $tr.='<td ></td>';
                    }elseif($type=='table_edit'){
                        $tr.='<td ondblclick="changeTd('.$key.','.$i.')" id="td_'.$key.'_'.$i.'"></td>';
                    }
                }
                $res.=$tr.'</tr>';
            }
//            print_r($tmparr);
            $res = '<table id="spsTable" class="ui-table" cellpadding="0" cellspacing="0" width="100%">'.$res.'</table>';
            return $res;
        }
    }
    
    function showRepDim($dim,$type='td'){
        $res='';
        if($type=='tr'){
            foreach($dim as $val){
                if($val['code']=='dimY'){
                    $seled=date('Y');
                    $res.='<tr><td>'.$val['name'].'</td><td><select id="dim[dimY]" name="年" class="dim"  onchange="selectDim()">';
                    for($i=$val['ini_val'];$i<=date("Y");$i++){
                        $res.='<option value="'.$i.'" '.($seled==$i?'selected':'').'>'.$i.'</option>';
                    }
                    $res.='</select></td></tr>';
                } elseif($val['code']=='dimM'){
                    $seled=date('m');
                    $res.='<tr><td>'.$val['name'].'</td><td><select id="dim[dimM]" name="月" class="dim"  onchange="selectDim()">';
                    for($i=1;$i<=12;$i++){
                        $res.='<option value="'.$i.'" '.($seled==$i?'selected':'').'>'.$i.'</option>';
                    }
                    $res.='</select></td></tr>';
                }else{
                    $res.='<tr><td>'.$val['name'].'</td><td>'.$val['code'].'</td></tr>';
                }
            }
        }elseif($type=='td'){
            foreach($dim as $val){
                if($val['code']=='dimY'){
                    $seled=date('Y');
                    $res.='<td style="text-align: center;width:40px;">'.$val['name']
                            .'</td><td style="text-align: center;width:40px;"><select id="dim[dimY]" name="年" class="dim" onchange="selectDim()">';
                    for($i=$val['ini_val'];$i<=date("Y");$i++){
                        $res.='<option value="'.$i.'" '.($seled==$i?'selected':'').'>'.$i.'</option>';
                    }
                    $res.='</select></td>';
                } elseif($val['code']=='dimM'){
                    $seled=date('m');
                    $res.='<td style="text-align: center;width:30px;" >'.$val['name']
                            .'</td><td style="text-align: center;width:40px;"><select id="dim[dimM]" name="月" class="dim" onchange="selectDim()">';
                    for($i=1;$i<=12;$i++){
                        $res.='<option value="'.$i.'" '.($seled==$i?'selected':'').'>'.$i.'</option>';
                    }
                    $res.='</select></td>';
                }else{
                    $res.='<td>'.$val['name'].'</td><td>'.$val['code'].'</td>';
                }
            }
        }
        
        return $res;
    }
    
    
    
    function writeRepFm($key,$type='excel'){
        $repInfo=$this->get_rep($key);
        if($repInfo['fm']){
            if($type=='excel'){
                $tabArr=  un_iconv($repInfo['fm']);
                $this->objExcel = new includes_classes_excelphp();
                $this->objExcel->dataToExcel($tabArr);
                $this->objExcel->OutPut();
//                print_r($tabArr);
            }elseif($type=='table'){
                
            }
        }
    }
    
    /**
     *读取数据
     * @param type $key
     * @param type $type
     * @param type $show table data 
     * @return type 
     */
    function getRepFm($key,$type='key',$show='table'){
        if($type=='key'){
            //获取sql语句
            $sql = "SELECT f.* FROM model_rep_fm f
                    left join model_rep r on ( f.rep_id=r.id )
                    where r.rand_key='$key' ";
            $rs = $this->_db->getArray($sql);
        }elseif($type=='id'){
            //获取sql语句
            $sql = "SELECT f.* FROM model_rep_fm f where f.rep_id='$key'";
            $rs = $this->_db->getArray($sql);
        }
        if($show=='table'){
            $tabArr=array();
            foreach($rs as $val){
                $tabArr[$val['index_row']][$val['index_col']]['name']=$val['name'];
                $tabArr[$val['index_row']][$val['index_col']]['to_rows']=$val['to_rows'];
                $tabArr[$val['index_row']][$val['index_col']]['to_cols']=$val['to_cols'];
            }
            $rs=$tabArr;
        }
        return $rs;
    }
    
    

    function readTableData($table,$type='table',$md=array()) {
        $tabArr = array();
        if($type=='table'){
            $search = array ("'<script[^>]*?>.*?</script>'si",  // 去掉 javascript
                    "'<[\/\!]*?[^<>]*?>'si",           // 去掉 HTML 标记
                    "'([\r\n])[\s]+'",                 // 去掉空白字符
                    "'&(quot|#34);'i",                 // 替换 HTML 实体
                    "'&(amp|#38);'i",
                    "'&(lt|#60);'i",
                    "'&(gt|#62);'i",
                    "'&(nbsp|#160);'i",
                    "'&(iexcl|#161);'i",
                    "'&(cent|#162);'i",
                    "'&(pound|#163);'i",
                    "'&(copy|#169);'i",
                    "'&#(\d+);'e"); 
            $replace=array('');
            $html = eregi_replace(">[\r\n\t ]+<", "><", $table); // 去掉多余的空字符
            eregi("<table[^>]*>(.+)</table>", $html, $regs); // 提取表体
            $ar = split("</tr>", $regs[1]); // 按行分解成数组
            array_pop($ar); // 去处尾部多余的元素
            for ($i = 0; $i < count($ar); $i++) {
                $ar[$i] = split("</t[h|d]>", $ar[$i]); // 分裂各列
                array_pop($ar[$i]); // 去处尾部多余的元素
            }
    //        print_r($ar);
            //行数由1开始； 列数由0开始
            $rowN=0;//定位
            $colN=0;
            $tabVir=array();
            foreach($ar as $key=>$val){
                $tabTemp=array();
                foreach($val as $vkey=>$vval){
                    //获取正文
                    $tabTemp[$vkey]['name']=trim(preg_replace($search,$replace,strip_tags($vval)));
                    //跨行
                    $ckr=null;
                    preg_match("/rowspan=[\"|'](.*?)[\"|']/i",$vval,$ckr);
                    $tabTemp[$vkey]['to_rows']=$ckr[1];
                    //跨列
                    $ckr=null;
                    preg_match("/colspan=[\"|'](.*?)[\"|']/i",$vval,$ckr);
                    $tabTemp[$vkey]['to_cols']=$ckr[1];
                    //
                    $tabVir[$rowN][]=$tabTemp[$vkey]['name'];
                    $colN=count($tabVir[$rowN])-1;//记录当前列
                    //排列位置
                    $tabTemp[$vkey]['index_row']=$rowN;
                    $tabTemp[$vkey]['index_col']=$colN;
                    if($tabTemp[$vkey]['to_cols']){
                        for($i=1;$i<$tabTemp[$vkey]['to_cols'];$i++){
                            $tabVir[$rowN][$colN+$i]=$tabTemp[$vkey]['name'];
                        }
                    }
                    if($tabTemp[$vkey]['to_rows']){
                        for($i=1;$i<$tabTemp[$vkey]['to_rows'];$i++){
                            $tabVir[$rowN+$i][$colN]=$tabTemp[$vkey]['name'];
                            if($tabTemp[$vkey]['to_cols']){
                                for($j=1;$j<$tabTemp[$vkey]['to_cols'];$j++){
                                    $tabVir[$rowN+$i][$colN+$j]=$tabTemp[$vkey]['name'];
                                }
                            }
                        }
                    }
                }
                $rowN++;//行数自增
                $tabArr[]=$tabTemp;
            }
            //print_r($tabArr);
        }elseif($type=='excel'){
            if(!empty($table)){
                foreach($table as $row=>$val){
                    $tabTemp=array();
                    if(!empty($val)){
                        foreach($val as $col=>$vval){
                            if(!empty($vval)||$vval=='0'){
                                if($md[$row][$col]){
                                    $tabTemp[$col]['to_rows']=$md[$row][$col]['to_rows'];
                                    $tabTemp[$col]['to_cols']=$md[$row][$col]['to_cols'];
                                    $tabTemp[$col]['header']='th';
                                }
                                $tabTemp[$col]['index_row']=$row;
                                $tabTemp[$col]['index_col']=$col;
                                $tabTemp[$col]['name']=$vval;
                            }
                        }
                    }
                    $tabArr[]=$tabTemp;
                }
            }
        }
        //echo $table;
//        print_r($tabVir);
//        print_r($tabArr);
        return $tabArr;
    }
    
}

?>