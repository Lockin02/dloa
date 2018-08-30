<?php
/**
 * ������
 * @author Administrator
 */
define("ERROR_CONTANT_ADMIN", "����ʧ������ϵ����Ա");
class model_stock_material_management_tools extends model_base {

    function __construct() {
        //		define("PARAMETER_TYPE_STRING", 'string');
        //		define("PARAMETER_TYPE_INT", 'int');
        //		define("PARAMETER_TYPE_JSON", 'json');
        parent::__construct();

    }

    /**
     * ����������ȡ���ݼ�����
     * @param String $tableName
     * @param String $conditions
     * @return Int
     */
    public function loadTotal($tableName, $conditions) {
        if (trim($conditions) != "" && strlen($conditions) > 0) {
            $conditions = "WHERE {$conditions}";
        }
        $SQL = "SELECT COUNT(*) AS totalDetail FROM `{$tableName}` {$conditions} ";
        $query = $this->query($SQL);
        $data = $this->_db->fetch_array($query);
        $total = 0;
        if (is_array($data) && count($data) > 0) {
            $total = $data['totalDetail'];
        }
        return $total;
    }

    /**
     * ��ҳ����
     * @param Int $total
     * @param Int $rows
     * @param Int $pages
     * @return String
     */
    public function setLimit($rows, $pages) {
        return $rows * ($pages - 1) . ',' . $rows;
    }

    /**
     * ��Ϣ����
     * @param Array/String $val
     */
    public function processMsg($val) {
        $str = "";
        $type = is_array($val) ? 'array' : (is_string($val) ? 'string' : false);
        if ($type == 'array') {
            foreach ($val as $msg) {
                $str .= $msg . "\r\n";
            }
        } else if ($type == 'string') {
            $str = $val . "\r\n";
        } else {
            $str = "ϵͳ��������ϵ����Ա��";
        }
        return $str;
    }

    /**
     * ��Ϣ�������
     * @param unknown_type $msg
     */
    public function alertResult($methodName, $val, $isJson = true) {
        if (!$isJson && $val != "") {
            $val = "'{$val}'";
        }
        return "<script>parent.{$methodName}({$val});</script>";
    }

    /**
     * ��ʼ��SESSION
     */
    public function initSession() {
        unset($_SESSION[$_SESSION['USER_ID']]['EXCEL_IMPORT']);
    }

    /**
     * ���������ѯ
     * @param String $tableName
     * @param String $conditions default '*'
     * @param String $fields default empty
     * @param String $orderBy default empty
     * @param String $sort default empty
     * @param String $limit defaul empty
     */
    public function getTableFiledByConditions($tableName, $fields = '', $conditions = '', $orderBy = '', $sort = '', $limit = '', $groupBy = '', $join = '', $isDebug = false) {
        if (trim($fields) == '') {
            $fields = "*";
        }

        if (trim($conditions) != "") {
            $conditions = " WHERE " . $conditions;
        } else {
            $conditions = "WHERE 1=1";
        }

        if (trim($limit) != "") {
            $limit = "LIMIT {$limit}";
        }

        if (trim($orderBy) != "" && trim($sort) != "") {
            $orderByStr = "ORDER BY {$orderBy} {$sort}";
        }

        if (trim($groupBy) != '') {
            $group = "GROUP BY {$groupBy}";
        }

        if (trim($join) != '') {
            $join = "LEFT JOIN {$join}";
        }

        $SQL = "SELECT {$fields} FROM {$tableName} {$join} {$conditions} {$group} {$orderByStr} {$limit}";

        if ($isDebug) {
            echo $SQL;
            exit;
        }

        $query = $this->query($SQL);

        return $query;
    }

    /**
     * ���ı��
     */
    public function updateDetailMark($id, $mark) {
        $SQL = "update oa_stock_product_bom_detail set `stock_mark` = {$mark} where id = {$id}";
        $query = $this->query($SQL);
        return $query;
    }

    public function delete($tableName, $conditions) {
        if (trim($conditions) != "" && strlen($conditions) > 0) {
            $conditions = "WHERE {$conditions}";
        }
        $SQL = "DELETE FROM `{$tableName}` {$conditions} ";

        return $this->_db->query($SQL);
    }

    /**
     * ����Review���������
     * @param int $id
     * @param String $name
     * @param String $clash
     * @param String $description
     * @param String $type
     * @return Array
     */
    public function setMainReviewTableProperties($id, $name, $clash, $description, $type) {
        return array(
            'id' => $id,
            'name' => $name,
            'clash' => $clash,
            'description' => $description,
            'type' => $type
        );
    }

    /**
     * ���Դ�ӡ
     * @param everything $data
     * @param Boolean $isBreak default true
     */
    public function debug($data, $isBreak = true) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if ($isBreak) {
            exit;
        }
    }

    /**
     * ���õ�-����б�
     * @param unknown_type $id
     * @param unknown_type $configDetail
     */
    public function markDetailTable($tag, $detail) {
        $str = '';
        //		if($detail['stock_code']){
        //			$str .= "<tr id='tr_{$tag}_{$detail['id']}'>";
        //		}else{
        //			$str .= "<tr style='background:#ccc;'>";
        //		}

        if ($detail['mark']) {
            $str .= "<tr id='tr_{$tag}_{$detail['id']}' style='background:#ccc;'>";
        } else {
            $str .= "<tr id='tr_{$tag}_{$detail['id']}'>";
        }

        $str .= "<td align='center'>{$detail['serial_number']}</td>";

        if ($detail['stock_code']) {
            $str .= "<td align='center'>{$detail['stock_code']}</td>";
        } else {
            $str .= "<td align='center'><input type='text' value='{$detail['stock_code']}' style='border:1; text-align:center; width:60px;'/></td>";
        }

        $str .= "<td align='center'>{$detail['stock_name']}</td>
				<td align='left'>{$detail['stock_model']}</td>
				<td align='center'>{$detail['stock_packaging']}</td>
				<td align='center'>{$detail['stock_total']}</td>
				<td align='left'>{$detail['stock_serial_number']}</td>
				<td align='left'>{$detail['stock_factory']}</td>
				<td align='left'>{$detail['stock_mark']}</td>
				<td align='center'><input type='button' onclick='notNeedMark({$tag}, {$detail['id']}, \"{$detail['type']}\");' value='���'></td>";
        $str .= "</tr>";

        return $str;
    }

    /**
     * ���õ����ε�����չ -����б�
     * @param unknown_type $tag
     * @param unknown_type $detail
     */
    public function markDetailTableExt($tag, $detail) {
        $str = "<tr id='tr_{$tag}}'>";
        $str .= "<td align='center'>{$detail['serial_number']}</td>
				<td align='center'>{$detail['stock_type']}</td>
				<td align='center'>{$detail['stock_code']}</td>
				<td align='center'>{$detail['stock_name']}</td>
				<td align='left'>{$detail['stock_model']}</td>
				<td align='center'>{$detail['stock_packaging']}</td>
				<td align='center'>{$detail['stock_total']}</td>
				<td align='left'>{$detail['stock_serial_number']}</td>
				<td align='left'>{$detail['stock_factory']}</td>
				<td align='left'>{$detail['stock_mark']}</td>
				<td align='center'><input type='button' onclick='notNeedMark({$tag}, {$detail['id']}, \"{$detail['type']}\");' value='���'></td>
				";
        $str .= "</tr>";
        return $str;

    }

    public function normalDetailTableHeader($colspan) {
        return "
				<tr style='background-color:lightblue;'><td colspan='{$colspan}'>�����嵥</td></tr>
				<tr align='center' >
					<th style='width:30px;'>���</th>
					<th style='width:60px;'>���ϱ���</th>
					<th style='width:100px;'>��   ��</th>
					<th style='width:120px;'>��    ��</th>
					<th style='width:80px;'>��    װ</th>
					<th style='width:30px;'>����</th>
					<th style='width:150px;'>Ԫ�����</th>
					<th style='width:120px;'>��    ��</th>
					<th style='width:150px;'>��ע</th>
					<th style='width:30px;' >����</th>
				</tr>
				";
    }

    /**
     * ���õ�-���ñ��
     * @param unknown_type $id
     * @param unknown_type $configDetail
     */
    public function markConfigTable($id, $configDetail) {
        $str = "<tr id='tr_{$id}_{$configDetail['id']}'>";
        $str .= "<td align='center'>{$configDetail['serial_number']}</td>
				<td align='center'>{$configDetail['stock_type']}</td>
				<td align='center'>{$configDetail['stock_code']}</td>
				<td align='center'>{$configDetail['stock_name']}</td>
				<td align='left'>{$configDetail['stock_model']}</td>
				<td align='center'>{$configDetail['stock_packaging']}</td>
				<td align='center'>{$configDetail['stock_total']}</td>
				<td align='left'>{$configDetail['stock_serial_number']}</td>
				<td align='left'>{$configDetail['stock_factory']}</td>
				<td align='left'>{$configDetail['stock_mark']}</td>
				<td align='center'><input type='button' onclick='notNeedMark(\"{$id}\", \"{$configDetail['id']}\", \"{$configDetail['type']}\");' value='���' ></td>
				";
        $str .= "</tr>";
        return $str;
    }

    /**
     * ���õ����ε�����չ-���ñ��
     * @param unknown_type $id
     * @param unknown_type $configDetail
     */
    public function markConfigTableExt($id, $configDetail) {
        $str = "<tr id='tr_{$id}_{$configDetail['id']}'>";
        $str .= "<td align='center'>{$configDetail['serial_number']}</td>
				<td align='center'>{$configDetail['stock_type']}</td>
				<td align='center'>{$configDetail['stock_code']}</td>
				<td align='center'>{$configDetail['stock_name']}</td>
				<td align='left'>{$configDetail['stock_model']}</td>
				<td align='center'>{$configDetail['stock_packaging']}</td>
				<td align='center'>{$configDetail['stock_total']}</td>
				<td align='left'>{$configDetail['stock_serial_number']}</td>
				<td align='left'>{$configDetail['stock_factory']}</td>
				<td align='left'>{$configDetail['stock_mark']}</td>
				<td align='center'><input type='button' onclick='notNeedMark(\"{$id}\", \"{$configDetail['id']}\", \"{$configDetail['type']}\");' value='���'  ></td>
				";
        $str .= "</tr>";
        return $str;
    }

    public function normalConfigTableHeader($configTag, $configColspan) {
        return "
				<tr style='background-color:lightblue;' >
					<td align='center' style='width:30px;'>����</td>
					<td style='width:60px;'>{$configTag['name']}</td>
					<td colspan='2'>{$configTag['clash']}</td>
					<td colspan='{$configColspan}}'>{$configTag['description']}</td>
				</tr>
				<tr align='center' >
					<th style='width:30px;'>���</th>
					<th style='width:60px;'>��������</th>
					<th style='width:60px;'>���ϱ���</th>
					<th style='width:100px;'>��   ��</th>
					<th style='width:120px;'>��    ��</th>
					<th style='width:80px;'>��    װ</th>
					<th style='width:30px;'>����</th>
					<th style='width:150px;'>Ԫ�����</th>
					<th style='width:120px;'>��    ��</th>
					<th style='width:150px;'>��ע</th>
					<th style='width:30px;' >����</th>
				</tr>
			";
    }

    function fetch_array($query, $result_type = MYSQL_ASSOC) {
        return mysql_fetch_array($query, $result_type);
    }

}