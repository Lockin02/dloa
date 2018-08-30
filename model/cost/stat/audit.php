<?php

class model_cost_stat_audit extends model_base {

    public $db;
    public $xls;
    public $bcom;

    //*******************************���캯��***********************************

    function __construct() {
        parent::__construct();
        $this->db = new mysql();
        $this->xls = new includes_class_excelout('gb2312', false, 'My Test Sheet');
        if($_REQUEST['seacom']){
            if($_REQUEST['seacom']!='-'){
                $this->bcom=" and l.belongtocom='".$_REQUEST['seacom']."' ";
            }
        }else{
            $this->bcom=" and l.belongtocom='".$_SESSION['USER_COM']."' ";
        }
        
    }

    //********************************��ʾ����**********************************

    function model_all_dept($flag=false) {
        $seay=empty($_GET['seay'])?date('Y'):$_GET['seay'];
        if(empty($_REQUEST['seacom'])){
            $this->bcom="";
        }
        $dataInfo = array();
        $sql = "select
                t.parentcosttypeid as ct , month(l.paydt) as mon , sum(d.costmoney * d.days ) as sm , t.costtypename as cn
            from
                cost_detail d left join cost_type t on ( d.costtypeid=t.costtypeid)
                left join  cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                left join cost_summary_list l on ( d.billno=l.billno )
            where
                l.status='���'
                and  year(l.paydt)='".$seay."'
                ".$this->bcom."
            group by left(l.paydt,7) , t.parentcosttypeid
            order by t.parentcosttypeid ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $dataInfo[$row['ct']][$row['mon']] = $row['sm'];
            $dataInfo[$row['ct']]['am'] = isset($dataInfo[$row['ct']]['am']) ? $dataInfo[$row['ct']]['am'] + $row['sm'] : $row['sm'];
        }
        if($dataInfo){
            $sql = "select costtypeid as ct , costtypename as cn
                from cost_type
                where 
                    costtypeid in (" . implode(',', array_keys($dataInfo)) . ")
                ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $dataInfo[$row['ct']]['cn'] = $row['cn'];
            }
        }
        $i = 0;
        if ($flag) {
            $responce = array();
            foreach ($dataInfo as $key => $val) {
                $responce[$i] = array(
                    $val['cn'], $val['1'],
                    $val['2'], $val['3'],
                    $val['4'], $val['5'],
                    $val['6'], $val['7'],
                    $val['8'], $val['9'],
                    $val['10'], $val['11'],
                    $val['12'], $val['am']
                );
                $i++;
            }
        } else {
            $responce->page = 1;
            foreach ($dataInfo as $key => $val) {
                $responce->rows[$i]['id'] = $key;
                $responce->rows[$i]['cell'] = un_iconv(
                                array(
                                    $val['cn'], $val['1'],
                                    $val['2'], $val['3'],
                                    $val['4'], $val['5'],
                                    $val['6'], $val['7'],
                                    $val['8'], $val['9'],
                                    $val['10'], $val['11'],
                                    $val['12'], $val['am']
                                )
                );
                $i++;
            }
        }
        return $responce;
    }

    function model_all_dept_excel() {
        $data = array(1 => array('��Ŀ', 'һ��', '����', '����', '����', '����', '����', '����', '����', '����'
                , 'ʮ��', 'ʮһ��', 'ʮ����', '�ϼ�'));
        $this->xls->setStyle(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13));
        $data = array_merge($data, $this->model_all_dept(true));
        $this->xls->addArray($data);
        $this->xls->generateXML('my-excel');
    }

    /**
     * ��ȡ��������
     */
    function model_get_stat_dept() {
        $sql = "select costbelongtodeptids as cbd from cost_summary_list where costbelongtodeptids<>'' group by costbelongtodeptids ";
        $query = $this->db->query($sql);
        $res = "�������ţ�<select onchange='deptSelect(this)' >";
        while ($row = $this->db->fetch_array($query)) {
            if ($_GET['seadept'] == $row['cbd']) {
                $res.="<option value='" . $row['cbd'] . "' selected >" . $row['cbd'] . "</option>";
            } else {
                $res.="<option value='" . $row['cbd'] . "' >" . $row['cbd'] . "</option>";
            }
        }
        $res.='</select>';
        return $res;
    }

    /**
     * ��ȡ��������Ϣ
     */
    function model_dept_data($flag='none') {
        $res = '';
        $data = array();
        $data_dept = array();
        $total = array();
        $total_all = array();
        $total_mon = array();
        $cost_type = array();
        $un_bill = array();
        $seay=empty($_GET['seay'])?date('Y'):$_GET['seay'];
        $nowm = $seay<date('Y')?12:date('n');
        if(empty($_REQUEST['seacom'])){
            $this->bcom="";
        }
        //���ű���
        $sql = "select
                t.parentcosttypeid as ct , month(l.paydt) as mon , sum( d.costmoney * d.days ) as sm , t.costtypename as cn
                , l.costbelongtodeptids as cbd
            from
                cost_detail d left join cost_type t on ( d.costtypeid=t.costtypeid)
                left join  cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                left join cost_summary_list l on ( d.billno=l.billno )
                left join user u on (l.costman=u.user_id)
                left join department dp on (u.dept_id=dp.dept_id)
            where
                l.status='���'  and dp.dept_name=l.costbelongtodeptids
                and  year(l.paydt)='".$seay."'
                ".$this->bcom."
            group by  l.costbelongtodeptids , left(l.paydt,7) , t.parentcosttypeid
            order by t.parentcosttypeid ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['cbd']][$row['ct']][$row['mon']] =isset($data[$row['cbd']][$row['ct']][$row['mon']])?$data[$row['cbd']][$row['ct']][$row['mon']]+$row['sm']:$row['sm'];
            $cost_type[$row['ct']] = 1;
            $total[$row['cbd']][$row['mon']] = isset($total[$row['cbd']][$row['mon']]) ? $total[$row['cbd']][$row['mon']] + $row['sm'] : $row['sm'];
            $total_all[$row['mon']] = isset($total_all[$row['mon']]) ? $total_all[$row['mon']] + $row['sm'] : $row['sm'];
            $total_mon[$row['cbd']][$row['ct']] = isset($total_mon[$row['cbd']][$row['ct']]) ? $total_mon[$row['cbd']][$row['ct']] + $row['sm'] : $row['sm'];
        }
        //�ǲ��ű���
        $sql = "select
                 month(l.paydt) as mon , sum( d.costmoney * d.days ) as sm
                , l.costbelongtodeptids as cbd
            from
                cost_detail d
                left join  cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                left join cost_summary_list l on ( d.billno=l.billno )
                left join user u on (l.costman=u.user_id)
                left join department dp on (u.dept_id=dp.dept_id)
            where
                l.status='���'  and dp.dept_name<>l.costbelongtodeptids
                and  year(l.paydt)='".$seay."'
                ".$this->bcom."
            group by  l.costbelongtodeptids ,   left(l.paydt,7) ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['cbd']]['other'][$row['mon']] =isset($data[$row['cbd']]['other'][$row['mon']])?$data[$row['cbd']]['other'][$row['mon']]+$row['sm']:$row['sm'];
            $total[$row['cbd']][$row['mon']] = isset($total[$row['cbd']][$row['mon']]) ? $total[$row['cbd']][$row['mon']] + $row['sm'] : $row['sm'];
            $total_all[$row['mon']] = isset($total_all[$row['mon']]) ? $total_all[$row['mon']] + $row['sm'] : $row['sm'];
            $total_mon[$row['cbd']]['other'] = isset($total_mon[$row['cbd']]['other']) ? $total_mon[$row['cbd']]['other'] + $row['sm'] : $row['sm'];
        }
        //����δ����
        $sql = "select
                t.parentcosttypeid as ct , sum( d.costmoney * d.days ) as sm
                , l.costbelongtodeptids as cbd
            from
                cost_detail d
                left join cost_type t on (d.costtypeid=t.costtypeid)
                left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                left join cost_summary_list l on ( d.billno=l.billno )
                left join user u on (l.costman=u.user_id)
                left join department dp on (u.dept_id=dp.dept_id)
            where
                l.status not in('���','�༭')
                and dp.dept_name=l.costbelongtodeptids
                ".$this->bcom."
            group by  l.costbelongtodeptids , t.parentcosttypeid ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if(in_array($row['cbd'], array_keys($data))){
                $cost_type[$row['ct']] = 1;
                $un_bill[$row['cbd']][$row['ct']][$nowm] = $row['sm'];
                $total_mon[$row['cbd']][$row['ct']] = $total_mon[$row['cbd']][$row['ct']] + $row['sm'];
                $total[$row['cbd']]['other']=$total[$row['cbd']]['other']+$row['sm'];
                $total_all['other']=$total_all['other']+$row['sm'];
            }
        }
        //�ǲ���δ����
        $sql = "select
                sum( d.costmoney * d.days ) as sm
                , l.costbelongtodeptids as cbd
            from
                cost_detail d
                left join cost_type t on (d.costtypeid=t.costtypeid)
                left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                left join cost_summary_list l on ( d.billno=l.billno )
                left join user u on (l.costman=u.user_id)
                left join department dp on (u.dept_id=dp.dept_id)
            where
                l.status not in('���','�༭')
                and dp.dept_name<>l.costbelongtodeptids
                ".$this->bcom."
            group by  l.costbelongtodeptids ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if(in_array($row['cbd'], array_keys($data))){
                $un_bill[$row['cbd']]['other'][$nowm] = $row['sm'];
                $total_mon[$row['cbd']]['other'] = $total_mon[$row['cbd']]['other'] + $row['sm'];
                $total[$row['cbd']]['other']=$total[$row['cbd']]['other']+$row['sm'];
                $total_all['other']=$total_all['other']+$row['sm'];
            }
        }
        /**
         * ��ʱ
         */
        //����δ����
        $sql = "select
                t.parentcosttypeid as ct , sum( d.costmoney * d.days ) as sm
                , l.costbelongtodeptids as cbd
            from
                cost_detail d
                left join cost_type t on (d.costtypeid=t.costtypeid)
                left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                left join cost_summary_list l on ( d.billno=l.billno )
                left join user u on (l.costman=u.user_id)
                left join department dp on (u.dept_id=dp.dept_id)
                left join wf_task w on (w.name=l.billno)
            where
                to_days(l.paydt)>to_days('2010-12-31')
                and to_days(w.finish)<=to_days('2011-01-05')
                and dp.dept_name=l.costbelongtodeptids
                ".$this->bcom."
            group by  l.costbelongtodeptids , t.parentcosttypeid ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if(in_array($row['cbd'], array_keys($data))){
                $cost_type[$row['ct']] = 1;
                $tmp_bill[$row['cbd']][$row['ct']] = $row['sm'];
                $total_mon[$row['cbd']][$row['ct']] = $total_mon[$row['cbd']][$row['ct']] + $row['sm'];
                $total[$row['cbd']]['tmp']=$total[$row['cbd']]['tmp']+$row['sm'];
                $total_all['tmp']=$total_all['tmp']+$row['sm'];
            }
        }
        //�ǲ���δ����
        $sql = "select
                sum( d.costmoney * d.days ) as sm
                , l.costbelongtodeptids as cbd
            from
                cost_detail d
                left join cost_type t on (d.costtypeid=t.costtypeid)
                left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                left join cost_summary_list l on ( d.billno=l.billno )
                left join user u on (l.costman=u.user_id)
                left join department dp on (u.dept_id=dp.dept_id)
                left join wf_task w on (w.name=l.billno)
            where
                to_days(l.paydt)>to_days('2010-12-31')
                and to_days(w.finish)<=to_days('2011-01-05')
                and dp.dept_name<>l.costbelongtodeptids
                ".$this->bcom."
            group by  l.costbelongtodeptids ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if(in_array($row['cbd'], array_keys($data))){
                $tmp_bill[$row['cbd']]['other'] = $row['sm'];
                $total_mon[$row['cbd']]['other'] = $total_mon[$row['cbd']]['other'] + $row['sm'];
                $total[$row['cbd']]['tmp']=$total[$row['cbd']]['tmp']+$row['sm'];
                $total_all['tmp']=$total_all['tmp']+$row['sm'];
            }
        }
        if(!empty($cost_type)){
            $sql = "select costtypeid as ct , costtypename as cn
                from cost_type
                where
                    costtypeid in (" . implode(',', array_keys($cost_type)) . ")
                ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                $cost_type[$row['ct']] = $row['cn'];
            }
        }
        $res.='<tr style="background: #D3E5FA;text-align: center;">
                    <td style="height: 23px;">����</td>
                    <td>��Ŀ</td>';
        for ($i = 1; $i <= 12; $i++) {
            if ($i == $nowm) {
                $res.='<td><a href="#" onclick="newParentTab(' . $i . ')">' . $i . '��</a></td>';
                $res.='<td>δ����</td>';
                $res.='<td>��ʱ</td>';
            } else {
                $res.='<td><a href="#" onclick="newParentTab(' . $i . ')">' . $i . '��</a></td>';
            }
        }
        $res.='     <td>�ϼ�</td>
                </tr>';
        $cki = 0;
        foreach ($data as $key => $val) {
            $cki++;
            $i = 0;
            foreach ($cost_type as $vkey => $vval) {
                if ($i % 2 == 1) {
                    $res.='<tr class="dis_' . $cki . ' ui-widget-content jqgrow ui-row-ltr" style="background: #F3F3F3;display:'.$flag.';">';
                } else {
                    $res.='<tr class="dis_' . $cki . ' ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;display:'.$flag.';">';
                }
                if ($i == 0) {
                    $res.='<td id="head_' . $cki . '" rowspan="' . (count($cost_type) + 1) . '" style="text-align: center;">' . $key . '</td>';
                }
                $res.='<td height="20" style="text-align: center;">' . $vval . '</td>';
                for ($i = 1; $i <= 12; $i++) {
                    if ($i == $nowm) {
                        $res.='<td>' . num_to_maney_format($val[$vkey][$i]) . '</td>';
                        $res.='<td>' . num_to_maney_format($un_bill[$key][$vkey][$i]) . '</td>';
                        $res.='<td>' . num_to_maney_format($tmp_bill[$key][$vkey]) . '</td>';
                    } else {
                        $res.='<td>' . num_to_maney_format($val[$vkey][$i]) . '</td>';
                    }
                }
                $res.='<td>' . num_to_maney_format($total_mon[$key][$vkey]) . '</td>';
                $i++;
                $res.='</tr>';
            }
            $res.='<tr class="dis_' . $cki . ' ui-widget-content jqgrow ui-row-ltr" style="background:goldenrod;display:'.$flag.';">
                <td height="20" style="text-align: center;">�������Ų�������</td>';
            for ($i = 1; $i <= 12; $i++) {
                if ($i == $nowm) {
                    $res.='<td>' . num_to_maney_format($data[$key]['other'][$i]) . '</td>';
                    $res.='<td>' . num_to_maney_format($un_bill[$key]['other'][$i]) . '</td>';
                    $res.='<td>' . num_to_maney_format($tmp_bill[$key]['other']) . '</td>';
                } else {
                    $res.='<td>' . num_to_maney_format($data[$key]['other'][$i]) . '</td>';
                }
            }
            $res.='<td>' . num_to_maney_format($total_mon[$key]['other']) . '</td>
             </tr>
             <tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #2E982E;color:#FFFFFF;">
                <td id="total_' . $cki . '" style="text-align: center;">' . $key . '</td>
                <td height="20" style="text-align: center;">
                <a href="#" onClick="disInfo(\'' . $cki . '\')"><img id="img_' . $cki . '" src="images/work/plus.png" border="0" /></a> С��</td>';
            for ($i = 1; $i <= 12; $i++) {
                if ($i == $nowm) {
                    $res.='<td>' . num_to_maney_format($total[$key][$i]) . '</td>';
                    $res.='<td>' . num_to_maney_format($total[$key]['other']) . '</td>';
                    $res.='<td>' . num_to_maney_format($total[$key]['tmp']) . '</td>';
                } else {
                    $res.='<td>' . num_to_maney_format($total[$key][$i]) . '</td>';
                }
            }
            $res.='
                <td>' . num_to_maney_format(array_sum($total[$key])) . '</td>
              </tr>
                ';
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #CCCCFF;">
                <td height="28" style="text-align: center;" colspan="2">�ϼ�</td>';
        for ($i = 1; $i <= 12; $i++) {
                if ($i == $nowm) {
                    $res.='<td>' . num_to_maney_format($total_all[$i]) . '</td>';
                    $res.='<td>' . num_to_maney_format($total_all['other']) . '</td>';
                    $res.='<td>' . num_to_maney_format($total_all['tmp']) . '</td>';
                } else {
                    $res.='<td>' . num_to_maney_format($total_all[$i]) . '</td>';
                }
            }
        $res.='
                <td>' . num_to_maney_format(array_sum($total_all)) . '</td>
              </tr>';
        return $res;
    }

    function model_dept_excel() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >              
                ' . $this->model_dept_data('') . '
            </table>';
    }
    function model_dept_data_other(){
        $res = '';
        $data = array();
        $data_dept = array();
        $total = array();
        $total_all = array();
        $total_mon = array();
        $cost_type = array();
        $un_bill = array();
        $nowm = date('n');
        $seay=empty($_GET['seay'])?date('Y'):$_GET['seay'];
        //���ű���
        $sql = "select
                t.parentcosttypeid as ct , month(l.paydt) as mon , sum( d.costmoney * d.days ) as sm , t.costtypename as cn
                , l.costbelongtodeptids as cbd
            from
                cost_detail d left join cost_type t on ( d.costtypeid=t.costtypeid)
                left join  cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                left join cost_summary_list l on ( d.billno=l.billno )
                left join user u on (l.costman=u.user_id)
                left join department dp on (u.dept_id=dp.dept_id)
            where
                l.status='���'  and dp.dept_name<>l.costbelongtodeptids
                and  year(l.paydt)='".$seay."'
                ".$this->bcom."
            group by  l.costbelongtodeptids , left(l.paydt,7) , t.parentcosttypeid
            order by t.parentcosttypeid ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['cbd']][$row['ct']][$row['mon']] =isset($data[$row['cbd']][$row['ct']][$row['mon']])?$data[$row['cbd']][$row['ct']][$row['mon']]+$row['sm']:$row['sm'];
            $cost_type[$row['ct']] = 1;
            $total[$row['cbd']][$row['mon']] = isset($total[$row['cbd']][$row['mon']]) ? $total[$row['cbd']][$row['mon']] + $row['sm'] : $row['sm'];
            $total_all[$row['mon']] = isset($total_all[$row['mon']]) ? $total_all[$row['mon']] + $row['sm'] : $row['sm'];
            $total_mon[$row['cbd']][$row['ct']] = isset($total_mon[$row['cbd']][$row['ct']]) ? $total_mon[$row['cbd']][$row['ct']] + $row['sm'] : $row['sm'];
        }
        //�ǲ���δ����
        $sql = "select
                sum( d.costmoney * d.days ) as sm
                , l.costbelongtodeptids as cbd
            from
                cost_detail d
                left join cost_type t on (d.costtypeid=t.costtypeid)
                left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
                left join cost_summary_list l on ( d.billno=l.billno )
                left join user u on (l.costman=u.user_id)
                left join department dp on (u.dept_id=dp.dept_id)
            where
                l.status not in('���','�༭')
                and dp.dept_name<>l.costbelongtodeptids
                ".$this->bcom."
            group by  l.costbelongtodeptids ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            if(in_array($row['cbd'], array_keys($data))){
                $un_bill[$row['cbd']]['other'][$nowm] = $row['sm'];
                $total_mon[$row['cbd']]['other'] = $total_mon[$row['cbd']]['other'] + $row['sm'];
                $total[$row['cbd']]['other']=$total[$row['cbd']]['other']+$row['sm'];
                $total_all['other']=$total_all['other']+$row['sm'];
            }
        }
        $sql = "select costtypeid as ct , costtypename as cn
            from cost_type
            where
                costtypeid in (" . implode(',', array_keys($cost_type)) . ")
            ";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $cost_type[$row['ct']] = $row['cn'];
        }
        $res.='<tr style="background: #D3E5FA;text-align: center;">
                    <td style="height: 23px;">����</td>
                    <td>��Ŀ</td>';
        for ($i = 1; $i <= 12; $i++) {
            if ($i == $nowm) {
                $res.='<td><a href="#" onclick="newParentTab(' . $i . ')">' . $i . '��</a></td>';
                $res.='<td>δ����</td>';
            } else {
                $res.='<td><a href="#" onclick="newParentTab(' . $i . ')">' . $i . '��</a></td>';
            }
        }
        $res.='     <td>�ϼ�</td>
                </tr>';
        $cki = 0;
        foreach ($data as $key => $val) {
            $cki++;
            $i = 0;
            foreach ($cost_type as $vkey => $vval) {
                if ($i % 2 == 1) {
                    $res.='<tr class="dis_' . $cki . ' ui-widget-content jqgrow ui-row-ltr" style="background: #F3F3F3;display:'.$flag.';">';
                } else {
                    $res.='<tr class="dis_' . $cki . ' ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;display:'.$flag.';">';
                }
                if ($i == 0) {
                    $res.='<td id="head_' . $cki . '" rowspan="' . (count($cost_type) + 1) . '" style="text-align: center;">' . $key . '</td>';
                }
                $res.='<td height="20" style="text-align: center;">' . $vval . '</td>';
                for ($i = 1; $i <= 12; $i++) {
                    if ($i == $nowm) {
                        $res.='<td>' . num_to_maney_format($val[$vkey][$i]) . '</td>';
                        $res.='<td>' . num_to_maney_format($un_bill[$key][$vkey][$i]) . '</td>';
                    } else {
                        $res.='<td>' . num_to_maney_format($val[$vkey][$i]) . '</td>';
                    }
                }
                $res.='<td>' . num_to_maney_format($total_mon[$key][$vkey]) . '</td>';
                $i++;
                $res.='</tr>';
            }
            $res.='
             </tr>
             <tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #2E982E;color:#FFFFFF;">
                <td height="20" style="text-align: center;">С��</td>';
            for ($i = 1; $i <= 12; $i++) {
                if ($i == $nowm) {
                    $res.='<td>' . num_to_maney_format($total[$key][$i]) . '</td>';
                    $res.='<td>' . num_to_maney_format($total[$key]['other']) . '</td>';
                } else {
                    $res.='<td>' . num_to_maney_format($total[$key][$i]) . '</td>';
                }
            }
            $res.='
                <td>' . num_to_maney_format(array_sum($total[$key])) . '</td>
              </tr>
                ';
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #CCCCFF;">
                <td height="28" style="text-align: center;" colspan="2">�ϼ�</td>';
        for ($i = 1; $i <= 12; $i++) {
                if ($i == $nowm) {
                    $res.='<td>' . num_to_maney_format($total_all[$i]) . '</td>';
                    $res.='<td>' . num_to_maney_format($total_all['other']) . '</td>';
                } else {
                    $res.='<td>' . num_to_maney_format($total_all[$i]) . '</td>';
                }
            }
        $res.='
                <td>' . num_to_maney_format(array_sum($total_all)) . '</td>
              </tr>';
        return $res;
    }
    function model_dept_excel_other(){
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: inline; filename=\"".time().".xls\"");
        $res= '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' .$this->model_dept_data_other() . '
            </table>';
        echo un_iconv($res);
    }
    /**
     * �г�������Աͳ��
     */
    function model_spe_user() {
        $sqlStr = '';
        $res = '';
        $staDept = array(37, 41);
        $data = array();
        $dataHeader = array();
        $total = array();
        $dataSort = array();
        $checky = isset($_POST['seay']) ? $_POST['seay'] : date('Y');
        $checkmb = isset($_POST['seamb']) ? $_POST['seamb'] : date('n');
        $checkme = isset($_POST['seame']) ? $_POST['seame'] : date('n');
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'total_desc';
        $sort = explode('_', $sort);
        $sort_k = $sort[0];
        $sort_v = $sort[1];
        $sqlStr = " and year(l.paydt)='$checky' and month(l.paydt)>='$checkmb' and month(l.paydt)<='$checkme' ";
        $sql = "select
	u.user_name as un , sum( d.costmoney * d.days ) as sm , t.costtypename as cn , t.costtypeid as ci
from
	cost_detail d
	left join cost_type t on ( d.costtypeid=t.costtypeid)
	left join  cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
        left join cost_summary_list l on ( d.billno=l.billno )
        left join user u on (l.costman=u.user_id)
where
	l.status='���' and u.dept_id in ( " . implode(',', $staDept) . " )
        ".$this->bcom."
    $sqlStr
group by
	u.user_id , d.costtypeid
";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['un']][$row['ci']] = $row['sm'];
            $dataHeader[$row['ci']] = $row['cn'];
            $total[$row['ci']] = isset($total[$row['ci']]) ? $total[$row['ci']] + $row['sm'] : $row['sm'];
            $dataSort[$row['un']] = isset($dataSort[$row['un']]) ? $dataSort[$row['un']] + $row['sm'] : $row['sm'];
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                    <td style="height: 23px;">������</td>
                    <td >С��' . sort_img('total', $sort_v, 'post') . '</td>';
        foreach ($dataHeader as $key => $val) {
            $res.='<td>' . $val . '</td>';
        }
        $res.='</tr>';
        $i = 0;
        if ($sort_v == "desc") {
            arsort($dataSort);
        } else {
            asort($dataSort);
        }
        foreach ($dataSort as $key => $val) {
            if ($i % 2 == 1) {
                $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #F3F3F3;">';
            } else {
                $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #FFFFFF;">';
            }
            $res.='<td class="ui-widget-content jqgrow ui-row-ltr"  style="height: 23px;text-align: center;" nowrap>' . $key . '</td>';
            $res.='<td  nowrap>' . num_to_maney_format($val) . '</td>';
            foreach ($dataHeader as $vkey => $vval) {
                $res.='<td nowrap>' . num_to_maney_format($data[$key][$vkey]) . '</td>';
            }
            $res.='</tr>';
            $i++;
        }
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;">
                    <td style="height: 23px;text-align: center;">�ϼ�</td>
                    <td  nowrap>' . num_to_maney_format(array_sum($total)) . '</td>';
        foreach ($dataHeader as $key => $val) {
            $res.='<td>' . num_to_maney_format($total[$key]) . '</td>';
        }
        $res.='</tr>';
        return $res;
    }

    function model_marche_excel() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_spe_user()) . '
            </table>';
    }

    function model_spe_seach() {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_POST['seay']) ? $_POST['seay'] : $nowy;
        $checkmb = isset($_POST['seamb']) ? $_POST['seamb'] : $nowm;
        $checkme = isset($_POST['seame']) ? $_POST['seame'] : $nowm;
        $res = '<select name="seay" >';
        for ($i = 2008; $i <= $nowy; $i++) {
            if ($checky == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ';
        //��ʼ��
        $res.='<select name="seamb" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkmb == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> ���� ';
        //������
        $res.='<select name="seame" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkme == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ';
        return $res;
    }

    /**
     * �ͻ�����
     * @return string
     */
    function model_customer_type($flag='', $type='') {
        $data = array();
        $res = '';
        if (!$flag) {
            $flag = 'l.costclienttype';
            $type = '�ͻ�����';
        }
        $checky = isset($_POST['seay']) ? $_POST['seay'] : date('Y');
        $checkmb = isset($_POST['seamb']) ? $_POST['seamb'] : date('n');
        $checkme = isset($_POST['seame']) ? $_POST['seame'] : date('n');
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'sm_desc';
        $sort = explode('_', $sort);
        $sort_k = $sort[0];
        $sort_v = $sort[1];
        $sqlStr = " and year(l.paydt)='$checky' and month(l.paydt)>='$checkmb' and month(l.paydt)<='$checkme' ";
        $sql = "select
	sum( d.costmoney * d.days ) as sm ,  $flag as cct
from 
	cost_detail d
	left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
	left join cost_summary_list l on(d.billno=l.billno)
	left join department dp on (dp.dept_name=l.costbelongtodeptids)
where 
	( dp.dept_id in(37,41) or l.costbelongtodeptids='����' )
	and l.status='���' 
	and l.costclienttype in ('ϵͳ��','��Ӫ��--��ͨ','��Ӫ��--�ƶ�','��Ӫ��--����','������','�г���','�ճ�֧��')
        ".$this->bcom."
	$sqlStr
group by $flag
order by $sort_k".' '."$sort_v ";
        $query = $this->db->query($sql);
        $i = 0;
        while ($row = $this->db->fetch_array($query)) {
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='
                <td style="text-align: center;height:23px;">' . $row['cct'] . '</td>
                <td>' . num_to_maney_format($row['sm']) . '</td>
                </tr>';
            $i++;
        }
        $res = '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                    <td style="height: 23px;">' . $type . sort_img('cct', $sort_k=='cct'?$sort_v:'', 'post') . '</td>
                    <td>���' . sort_img('sm', $sort_k=='sm'?$sort_v:'', 'post') . '</td>
               </tr>' . $res;
        return $res;
    }

    function model_type_excel() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . ($this->model_customer_type()) . '
            </table>';
    }

    function model_customer_pro() {
        return $this->model_customer_type('l.costclientarea', 'ʡ��');
    }

    function model_pro_excel() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_customer_type('l.costclientarea', 'ʡ��')) . '
            </table>';
    }

    function model_customer() {
        return $this->model_customer_type('l.costclientname', '�ͻ�����');
    }

    function model_customer_excel() {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_customer_type('l.costclientname', '�ͻ�����')) . '
            </table>';
    }

    /**
     * ��Ŀͳ��
     */
    function model_pro_seach() {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_POST['seay']) ? $_POST['seay'] : $nowy;
        $checkmb = isset($_POST['seamb']) ? $_POST['seamb'] : $nowm;
        $checkme = isset($_POST['seame']) ? $_POST['seame'] : $nowm;
        $checkmn = isset($_POST['seamn']) ? $_POST['seamn'] : $nowm;
        $checkcom = isset($_POST['seacom']) ? $_POST['seacom'] : '';
        $gl=new includes_class_global();
        $binfo=$gl->getBranchInfo();
        $res = '<select name="seacom" id="seacom" ><option value="-">ȫ����</option>';
        foreach($binfo as $key=>$val){
            if ($checkcom == $val['NamePT']) {
                $res.='<option value="' . $val['NamePT'] . '" selected >' . $val['NameCN'] . '</option>';
            } else {
                $res.='<option value="' . $val['NamePT'] . '" >' . $val['NameCN'] . '</option>';
            }
        }
        $res .= '</select> <select name="seay" >';
        for ($i = 2008; $i <= $nowy; $i++) {
            if ($checky == $i) {;
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ';
        //��ʼ��
        $res.='<select name="seamb" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkmb == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ';
        //������
        $res.='<select name="seame" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkme == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ��ǰ���� ';
        //��ǰ��
        $res.='<select name="seamn" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkmn == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ';
        return $res;
    }

    function model_project($flag=true) {
        $sqlStr = '';
        $data = array();
        $checky = isset($_POST['seay']) ? $_POST['seay'] : date('Y');
        $checkmb = isset($_POST['seamb']) ? $_POST['seamb'] : date('n');
        $checkme = isset($_POST['seame']) ? $_POST['seame'] : date('n');
        $checkmn = isset($_POST['seamn']) ? $_POST['seamn'] : date('n');
        $checkdtlast=date('Y-m',strtotime($checky.'-'.$checkme.'-01')).'-'.date('t',strtotime($checky.'-'.$checkme.'-01'));
        if(empty($_REQUEST['seacom'])){
            $this->bcom="";
        }
        //δ
        $sql = "select
	sum(amount) as am , projectno as pro
from
	cost_summary_list l
where
	l.isproject='1'
    and l.status<>'���'
    and year(l.inputdate)='$checky'
    and month(l.inputdate)>='$checkmb'
    and month(l.inputdate)<='$checkme'
    and ( l.paydt is null or to_days(l.paydt)>to_days('".$checkdtlast."') )
    ".$this->bcom."
group by
	l.projectno";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['pro']]['un'] = $row['am'];
        }
        //��
        $sql = "select
	sum(amount) as am , projectno as pro
from
	cost_summary_list l
where
	l.isproject='1'
	and l.status='���'
    and year(l.paydt)='$checky'
    and month(l.paydt)>='$checkmb'
    and month(l.paydt)<='$checkme'
    ".$this->bcom."
group by
	l.projectno";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['pro']]['ed'] = $row['am'];
        }
        //��ǰ
        $sql = "select
	sum(amount) as am , projectno as pro
from
	cost_summary_list l
where
	l.isproject='1'
    and
	(
        ( l.status<>'���' and  year(l.inputdate)='$checky'
            and month(l.inputdate)='$checkmn' )
        or
        ( l.status='���'
            and year(l.paydt)='$checky'
            and month(l.paydt)='$checkmn'
        )
     ) ".$this->bcom."
group by
	l.projectno";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['pro']]['now'] = $row['am'];
        }
        $sql = "select
                sum(amount) as am , projectno as pro
            from
                cost_summary_list l
                left join wf_task w on (w.name=l.billno)
            where
                l.isproject='1'
                and to_days(l.paydt)>to_days('2010-12-31')
                and to_days(w.finish)<=to_days('2011-01-05')
                ".$this->bcom."
            group by
                l.projectno";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['pro']]['tmp'] = $row['am'];
        }
        $i = 0;
        $ttdata=array();
        if($flag){
            $sql = "select name , applydate , money , projectno , id
                from xm
                where projectno in ('" . implode("','", array_keys($data)) . "')
                order by projectno ";
            $query = $this->db->query($sql);
            while ($row = $this->db->fetch_array($query)) {
                if ($i % 2 == 0) {
                    $res.='<tr style="background: #F3F3F3;">';
                } else {
                    $res.='<tr style="background: #FFFFFF;">';
                }
                $res.='
                    <td style="text-align: center;height:23px;">' . $row['name'] . '</td>
                    <td style="text-align: left;padding-left:3px;"><a href="#" onclick="newParentTab(' . $checky . ',' . $checkmb . ',' . $checkme . ',' . $checkmn .','.$row['id'].',\''.$row['projectno'].'\')">' . $row['projectno'] . '</a></td>
                    <td style="text-align: center;">' . $row['applydate'] . '</td>
                    <td>&nbsp;</td>
                    <td>' . num_to_maney_format($row['money']) . '</td>
                    <td>' . num_to_maney_format(round($data[$row['projectno']]['un'] + $data[$row['projectno']]['ed'],2)) . '</td>
                    <td>' . num_to_maney_format($data[$row['projectno']]['ed']) . '</td>
                    <td>' . num_to_maney_format($data[$row['projectno']]['un']) . '</td>
                    <td>' . num_to_maney_format($data[$row['projectno']]['now']) . '</td>
                    <td>' . num_to_maney_format($data[$row['projectno']]['tmp']) . '</td>
                    </tr>';
                $i++;
                $ttdata['money']+=$row['money'];
                $ttdata['uned']+=($data[$row['projectno']]['un'] + $data[$row['projectno']]['ed']);
                $ttdata['ed']+=$data[$row['projectno']]['ed'];
                $ttdata['un']+=$data[$row['projectno']]['un'];
                $ttdata['now']+=$data[$row['projectno']]['now'];
                $ttdata['tmp']+=$data[$row['projectno']]['tmp'];
            }
            $res = '<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;">��Ŀ����</td>
                        <td>��Ŀ���</td>
                        <td>�걨����</td>
                        <td>��Ŀ����</td>
                        <td>��Ŀ�ʽ�</td>
                        <td>��������</td>
                        <td>�ѱ�����</td>
                        <td>δ������</td>
                        <td>���·���</td>
                        <td>��ʱ����</td>
                   </tr>' . $res 
                    .'
                    <tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;">�ϼ�</td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td>'.num_to_maney_format($ttdata['money']).'</td>
                        <td>'.num_to_maney_format($ttdata['uned']).'</td>
                        <td>'.num_to_maney_format($ttdata['ed']).'</td>
                        <td>'.num_to_maney_format($ttdata['un']).'</td>
                        <td>'.num_to_maney_format($ttdata['now']).'</td>
                        <td>'.num_to_maney_format($ttdata['tmp']).'</td>
                   </tr>';
        }else{
            $sql = "select name , applydate , money , projectno , id
                from xm
                where projectno in ('" . implode("','", array_keys($data)) . "')
                order by projectno ";
            $query = $this->db->query($sql);
            while($row = $this->db->fetch_array($query)){
                $data[$row['projectno']]['name']=$row['name'];
                $data[$row['projectno']]['apdt']=$row['applydate'];
                $data[$row['projectno']]['money']=$row['money'];
            }
            $data_dat=array();
            $sql="select
        l.billno , sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , t.costtypename as cn
        , u.user_name as un , l.projectno
    from
        cost_detail_project d
        left join cost_type t on (d.costtypeid=t.costtypeid)
        left join cost_summary_list l on(d.billno=l.billno)
        left join user u on (l.costman=u.user_id)
    where
        l.status='���' and l.isproject='1'
        and year(l.paydt)='$checky' and month(l.paydt)>='$checkmb' and month(l.paydt)<='$checkme'
        ".$this->bcom."
    group by
        l.billno , d.costtypeid ";
            $query=$this->db->query($sql);
            while($row=$this->db->fetch_array($query)){
                $data_dat[$row['projectno']][$row['billno']][$row['ct']]=$row['sm'];
                $data_dat[$row['projectno']][$row['billno']]['un']=$row['un'];
                $cost_type[$row['ct']]=$row['cn'];
            }
            foreach ($data as $key=>$val) {
                if(empty($val['name'])){
                    continue;
                }
                if ($i % 2 == 0) {
                    $res.='<tr><td><table><tr style="background: #F6A450;">';
                } else {
                    $res.='<tr><td><table><tr style="background: #F6A450;">';
                }
                $res.='
                    <td style="text-align: center;height:23px;">' . $val['name'] . '</td>
                    <td style="text-align: left;padding-left:3px;">' . $key . '</td>
                    <td style="text-align: center;">' . $val['apdt'] . '</td>
                    <td>&nbsp;</td>
                    <td>' . num_to_maney_format($val['money']) . '</td>
                    <td>' . num_to_maney_format(round($val['un'] + $val['ed'],2)) . '</td>
                    <td>' . num_to_maney_format($val['ed']) . '</td>
                    <td>' . num_to_maney_format($val['un']) . '</td>
                    <td>' . num_to_maney_format($val['now']) . '</td>
                    </tr></table></td></tr>';
                if(!empty($data_dat[$key])){

                    $res.='<tr><td ><table width="100%">
                        <tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                        <td>��Ŀ����</td>
                        <td>��Ŀ���</td>
                        <td height="23">������</td>
                        <td>������</td>';
                    foreach ($cost_type as $keyc => $valc) {
                        $res.='<td>' . $valc . '</td>';
                    }
                    $res.='</tr>';
                    foreach($data_dat[$key] as $keys=>$vals){
                        if ($i % 2 == 0) {
                            $res.='<tr style="background: #F3F3F3;">';
                        } else {
                            $res.='<tr style="background: #FFFFFF;">';
                        }
                        $res.='<td nowrap style="text-align:center"> ' . $val['name'] . '</td>';
                        $res.='<td nowrap style="text-align:center"> ' . $key . '</td>';
                        $res.='<td nowrap style="text-align:center;height:20px;">&nbsp;' . $keys . '&nbsp;</td>';
                        $res.='<td nowrap style="text-align:center"> ' . $vals['un'] . '</td>';
                        foreach ($cost_type as $vkey => $vval) {
                            $res.='<td>' . num_to_maney_format($vals[$vkey]) . '</td>';
                        }
                        $res.='</tr>';
                        $i++;
                    }
                    $res.='</table></td></tr>';
                }
                $i++;
            }
            $res = '<table><tr><td><table><tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
                        <td style="height: 23px;">��Ŀ����</td>
                        <td>��Ŀ���</td>
                        <td>�걨����</td>
                        <td>��Ŀ����</td>
                        <td>��Ŀ�ʽ�</td>
                        <td>��������</td>
                        <td>�ѱ�����</td>
                        <td>δ������</td>
                        <td>���·���</td>
                   </tr></table></td></tr>' . $res .'</td></tr></table>';
        }
        return $res;
    }

    function model_project_excel($flag=true) {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: inline; filename=\"excel.xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_project($flag)) . '
            </table>';
    }

    function model_project_detail_sea(){
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_REQUEST['seay']) ? $_REQUEST['seay'] : $nowy;
        $checkmb = isset($_REQUEST['seamb']) ? $_REQUEST['seamb'] : $nowm;
        $checkme = isset($_REQUEST['seame']) ? $_REQUEST['seame'] : $nowm;
        $checkpn = isset($_REQUEST['seapn']) ? $_REQUEST['seapn'] : '';
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $checkcom = isset($_REQUEST['seacom']) ? $_REQUEST['seacom'] : '';
        $gl=new includes_class_global();
        $binfo=$gl->getBranchInfo();
        $res = '<select name="seacom" id="seacom" ><option value="-">ȫ����</option>';
        foreach($binfo as $key=>$val){
            if ($checkcom == $val['NamePT']) {
                $res.='<option value="' . $val['NamePT'] . '" selected >' . $val['NameCN'] . '</option>';
            } else {
                $res.='<option value="' . $val['NamePT'] . '" >' . $val['NameCN'] . '</option>';
            }
        }
        $res .= '</select> <select name="seay" >';
        for ($i = 2008; $i <= $nowy; $i++) {
            if ($checky == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ';
        //��ʼ��
        $res.='<select name="seamb" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkmb == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ��';
        //������
        $res.='<select name="seame" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkme == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ';
        $res.=' ������ <input type="seabill" name="seabill" size="18" value="' . $checkbill . '" />';
        $res.=' ������ <input type="seabill" name="seauser" size="18" value="' . $checkuser . '" />';
        $res.='<input type="hidden" name="seapn" value="'.$checkpn.'" />';
        return $res;
    }
    function model_project_detail(){
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_REQUEST['seay']) ? $_REQUEST['seay'] : $nowy;
        $checkmb = isset($_REQUEST['seamb']) ? $_REQUEST['seamb'] : $nowm;
        $checkme = isset($_REQUEST['seame']) ? $_REQUEST['seame'] : $nowm;
        $checkpn = isset($_REQUEST['seapn']) ? $_REQUEST['seapn'] : '';
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $data=array();
        $cost_type=array();
        if($checkbill){
            $sqlStr.=" and l.billno like '%$checkbill%' ";
        }
        if($checkuser){
            $sqlStr.=" and u.user_name like '%$checkuser%' ";
        }
        $sql="select
	l.billno , sum( d.costmoney * d.days ) as sm , d.costtypeid as ct , t.costtypename as cn
    , u.user_name as un , l.projectno , l.amount
from
	cost_detail_project d
	left join cost_type t on (d.costtypeid=t.costtypeid)
	left join cost_summary_list l on(d.billno=l.billno)
    left join user u on (l.costman=u.user_id)
where
	l.status='���' and l.isproject='1'
	and year(l.paydt)='$checky' and month(l.paydt)>='$checkmb' and month(l.paydt)<='$checkme'
        ".$this->bcom."
    and l.projectno='$checkpn'
    $sqlStr
group by
	l.billno , d.costtypeid ";
        $query=$this->db->query($sql);
        while($row=$this->db->fetch_array($query)){
            $data[$row['billno']][$row['ct']]=$row['sm'];
            $data[$row['billno']]['un']=$row['un'];
            $data[$row['billno']]['amount']=$row['amount'];
            $data[$row['billno']]['pro']=$row['projectno'];
            $cost_type[$row['ct']]=$row['cn'];
        }
        $res.='';
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">������</td>
            <td>������</td>
            <td>�ܽ��</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</td>';
        }
        $res.='</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['amount'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res.='<td>' . num_to_maney_format($val[$vkey]) . '</td>';
            }
            $res.='</tr>';
            $i++;
        }
        return $res;
    }

    function model_dept_detail($flag='dept') {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_POST['seay']) ? $_POST['seay'] : $nowy;
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        if(empty($_REQUEST['seacom'])){
            $this->bcom="";
        }
        $data = array();
        $cost_type = array();
        $sqlStr = '';
        if ($checkbill) {
            $sqlStr.=" and l.billno like '%$checkbill%' ";
        }
        if ($checkuser) {
            $sqlStr.=" and u.user_name like '%$checkuser%' ";
        }
        if($flag=='other'){
            $sqlStr.=" and dp.dept_name<>l.costbelongtodeptids";
        }
        $sql = "select
	sum( d.costmoney * d.days ) as sm , t.costtypename as cn , d.costtypeid as ct , l.billno as bn
    , u.user_name as un
from 
	cost_detail d 
	left join cost_type t on (d.costtypeid=t.costtypeid)
	left join cost_detail_assistant a on ( d.headid=a.headid and d.rno=a.rno )
	left join cost_summary_list  l on (l.billno=d.billno)
	left join user u on (l.costman=u.user_id)
    left join department dp on (u.dept_id=dp.dept_id)
where 
	l.status='���'
    $sqlStr
    and year(l.paydt)='$checky' and month(l.paydt)='$checkm'
    ".$this->bcom."
group by 
	l.billno , d.costtypeid";
        $query = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            $data[$row['bn']][$row['ct']] = $row['sm'];
            $data[$row['bn']]['un'] = $row['un'];
            $cost_type[$row['ct']] = $row['cn'];
        }
        $res = '';
        $res.='<tr class="ui-widget-content jqgrow ui-row-ltr" style="background: #D3E5FA;text-align: center;">
            <td height="23">������</td>
            <td>������</td>';
        foreach ($cost_type as $key => $val) {
            $res.='<td>' . $val . '</td>';
        }
        $res.='</tr>';
        $i = 0;
        foreach ($data as $key => $val) {
            if ($i % 2 == 0) {
                $res.='<tr style="background: #F3F3F3;">';
            } else {
                $res.='<tr style="background: #FFFFFF;">';
            }
            $res.='<td nowrap style="text-align:center;height:20px;">&nbsp;' . $key . '&nbsp;</td>';
            $res.='<td nowrap style="text-align:center"> ' . $val['un'] . '</td>';
            foreach ($cost_type as $vkey => $vval) {
                $res.='<td>' . num_to_maney_format($val[$vkey]) . '</td>';
            }
            $res.='</tr>';
            $i++;
        }
        return $res;
    }

    function model_dept_detail_sea() {
        $nowy = date('Y');
        $nowm = date('n');
        $checky = isset($_POST['seay']) ? $_POST['seay'] : $nowy;
        $checkm = isset($_REQUEST['seam']) ? $_REQUEST['seam'] : $nowm;
        $checkbill = isset($_POST['seabill']) ? $_POST['seabill'] : '';
        $checkuser = isset($_POST['seauser']) ? $_POST['seauser'] : '';
        $res = '<select name="seay" >';
        for ($i = 2008; $i <= $nowy; $i++) {
            if ($checky == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ';
        //��ʼ��
        $res.='<select name="seam" >';
        for ($i = 1; $i <= 12; $i++) {
            if ($checkm == $i) {
                $res.='<option value=' . $i . ' selected >' . $i . '</option>';
            } else {
                $res.='<option value=' . $i . ' >' . $i . '</option>';
            }
        }
        $res.='</select> �� ';
        $res.=' ������ <input type="seabill" name="seabill" size="18" value="' . $checkbill . '" />';
        $res.=' ������ <input type="seabill" name="seauser" size="18" value="' . $checkuser . '" />';
        return $res;
    }

    function model_dept_detail_excel($flag='dept') {
        header("Cache-Control: must-revalidate,post-check=0,pre-check=0");
        header("Cache-Control: public");
        header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition: inline; filename=\"excel-".time().".xls\"");
        echo '<table class="ui-jqgrid-btable" align="center" style="text-align: right;font-size: 12px; background: #000000" width="115%" cellpadding="0" cellspacing="0" border="1" >
                ' . un_iconv($this->model_dept_detail($flag)) . '
            </table>';
    }

    //*********************************��������************************************

    function __destruct() {

    }

}
?>