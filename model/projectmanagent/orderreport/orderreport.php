<?php
class model_projectmanagent_orderreport_orderreport extends model_base {

	function __construct() {
		parent::__construct ();
	}

    /**
     * 获取统计主表的数据
     * @param $searchType
     * @param string $countType
     * @return mixed
     */
	function getMainTableData($searchType,$countType = '',$limit){
        switch ($searchType){
            case 'sales':// 统计类型为借用人
                $groupType = "if(c.limits='客户',c.salesName,c.createName)";
                $condition = '';
                break;
            case 'product':// 统计类型为产品
                $groupType = 'e.productName';
                $condition = '';
                break;
            case 'saleman':// 统计类型为销售员
                $groupType = 'c.salesName';
                $condition = 'AND c.salesName IS NOT NULL AND c.salesName <> ""';
                break;
            default:
                $groupType = 'c.customerName';
                $condition = 'AND c.customerName IS NOT NULL AND c.customerName <> ""';
        }

        if($countType == 'num'){ // 数量
            $countStr = "SUM(e.executedNum - e.backNum)";
        }else{ // 统计类型为客户
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
     * 获取明细表的数据
     * @param $searchType
     * @param string $searchKey
     * @return mixed
     */
    function getDetailTableData($searchType,$searchKey = ''){
        switch ($searchType){
            case 'sales':// 统计类型为借用人
                $condition = 'AND ((c.limits="客户" and c.salesName="'.$searchKey.'") or (c.limits!="客户" and c.createName = "'.$searchKey.'"))' ;
                break;
            case 'product':// 统计类型为产品
                $condition = 'AND e.productName = "'.$searchKey.'"' ;
                break;
            case 'saleman':// 统计类型为销售员
                $condition = 'AND c.salesName IS NOT NULL AND c.salesName <> "" AND c.salesName = "'.$searchKey.'"' ;
                break;
            default:// 统计类型为客户
                $condition = 'AND (c.customerName IS NOT NULL AND c.customerName <> "" AND c.customerName = "'.$searchKey.'")' ;
        }

        $QuerySQL = <<<QuerySQL
        SELECT
            c.id,c.Code,c.customerName,c.salesName,
            case c.limits
            when '客户'  then c.salesName
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