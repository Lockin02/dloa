<?php
class model_projectmanagent_orderreport_orderreport extends model_base {

	function __construct() {
		parent::__construct ();
	}

    /**
     * ��ȡͳ�����������
     * @param $searchType
     * @param string $countType
     * @return mixed
     */
	function getMainTableData($searchType,$countType = '',$limit){
        switch ($searchType){
            case 'sales':// ͳ������Ϊ������
                $groupType = "if(c.limits='�ͻ�',c.salesName,c.createName)";
                $condition = '';
                break;
            case 'product':// ͳ������Ϊ��Ʒ
                $groupType = 'e.productName';
                $condition = '';
                break;
            case 'saleman':// ͳ������Ϊ����Ա
                $groupType = 'c.salesName';
                $condition = 'AND c.salesName IS NOT NULL AND c.salesName <> ""';
                break;
            default:
                $groupType = 'c.customerName';
                $condition = 'AND c.customerName IS NOT NULL AND c.customerName <> ""';
        }

        if($countType == 'num'){ // ����
            $countStr = "SUM(e.executedNum - e.backNum)";
        }else{ // ͳ������Ϊ�ͻ�
            $countStr = "SUM(IF(i.priCost IS NULL , 0 ,(e.executedNum - e.backNum)*i.priCost))";
        }

        $MainQuerySQL = <<<QuerySQL
            SELECT
                $groupType  AS searchType,
                $countStr AS countType
            FROM
                oa_borrow_equ e LEFT JOIN oa_borrow_borrow c ON e.borrowId = c.id
                LEFT JOIN
                oa_stock_product_info i ON e.productId = i.id
            WHERE c.isTemp = 0 AND c.backStatus <> 1 AND e.executedNum > 0 AND e.executedNum > backNum
            $condition
            GROUP BY $groupType
            ORDER BY $countStr DESC,$groupType $limit
QuerySQL;
        return $this->_db->getArray($MainQuerySQL);
    }

    /**
     * ��ȡ��ϸ�������
     * @param $searchType
     * @param string $searchKey
     * @return mixed
     */
    function getDetailTableData($searchType,$searchKey = ''){
        switch ($searchType){
            case 'sales':// ͳ������Ϊ������
                $condition = 'AND ((c.limits="�ͻ�" and c.salesName="'.$searchKey.'") or (c.limits!="�ͻ�" and c.createName = "'.$searchKey.'"))' ;
                break;
            case 'product':// ͳ������Ϊ��Ʒ
                $condition = 'AND e.productName = "'.$searchKey.'"' ;
                break;
            case 'saleman':// ͳ������Ϊ����Ա
                $condition = 'AND c.salesName IS NOT NULL AND c.salesName <> "" AND c.salesName = "'.$searchKey.'"' ;
                break;
            default:// ͳ������Ϊ�ͻ�
                $condition = 'AND (c.customerName IS NOT NULL AND c.customerName <> "" AND c.customerName = "'.$searchKey.'")' ;
        }

        $QuerySQL = <<<QuerySQL
        SELECT
            c.id,c.Code,c.customerName,c.salesName,
            case c.limits
            when '�ͻ�'  then c.salesName
            else c.createName  end
            as createName,
            c.beginTime,c.closeTime,
            e.productNo,e.productName,e.productModel,e.executedNum,e.executedNum - e.backNum AS waitNum,e.backNum,
            IF(i.priCost IS NULL , 0 ,(e.executedNum - e.backNum)*i.priCost) AS equMoney,
            IF(i.priCost IS NULL , 0 , i.priCost) AS priCost,se.sequence
        FROM
            oa_borrow_equ e LEFT JOIN oa_borrow_borrow c ON e.borrowId = c.id
            LEFT JOIN
            oa_stock_product_info i ON e.productId = i.id
                LEFT JOIN
            (SELECT GROUP_CONCAT(s.sequence) as sequence,s.relDocItemId
            FROM
            oa_stock_product_serialno s
            LEFT JOIN oa_borrow_equ be ON be.id=s.relDocItemId where s.relDocType='oa_borrow_borrow' and stockId=3  GROUP BY s.relDocItemId
            )se ON se.relDocItemId=e.id
        WHERE c.isTemp = 0 AND e.isTemp = 0 AND c.backStatus <> 1 AND e.executedNum > 0 AND e.executedNum > backNum $condition
        ORDER BY c.Code
QuerySQL;
        return $this->_db->getArray($QuerySQL);
    }
}