<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$year=$_GET['thisYear'];  //年

//库存仓库
$balanceStock = $_GET['balanceStock'];

//旧设备仓库
$oldStock = $_GET['oldStock'];

//上年12月
$prevYear = $year - 1;
$prevYearMonth = $prevYear . "12";
$prevDate = $prevYear . '-12-01';
$thisYearMonth = $year."12";
$thisDate = $year.'-12-01';


$sql = <<<QuerySQL
select
	c.id as stockId,c.stockName,sum(if(s.amount is null,0,s.amount)) as allAmount,
	sum(if(s.thisMonth = 1,s.amount,0)) as month1,
	sum(if(s.thisMonth = 2,s.amount,0)) as month2,
	sum(if(s.thisMonth = 3,s.amount,0)) as month3,
	sum(if(s.thisMonth = 4,s.amount,0)) as month4,
	sum(if(s.thisMonth = 5,s.amount,0)) as month5,
	sum(if(s.thisMonth = 6,s.amount,0)) as month6,
	sum(if(s.thisMonth = 7,s.amount,0)) as month7,
	sum(if(s.thisMonth = 8,s.amount,0)) as month8,
	sum(if(s.thisMonth = 9,s.amount,0)) as month9,
	sum(if(s.thisMonth = 10,s.amount,0)) as month10,
	sum(if(s.thisMonth = 11,s.amount,0)) as month11,
	sum(if(s.thisMonth = 12,s.amount,0)) as month12
from
	oa_stock_baseinfo c
	left join
	(
		select
			stockId,stockName,year(thisDate) as thisYear,month(thisDate) as thisMonth,sum(balanceAmount) as amount
		from
			oa_finance_stockbalance
		where year(thisDate) = $year
		group by
			stockId,year(thisDate),month(thisDate)
	) s on c.id = s.stockId
where c.id <> -1 and c.id in ($balanceStock)
group by c.id

union all

select
	'0' as stockId,'库存商品金额汇总' as stockName,sum(if(s.amount is null,0,s.amount)) as allAmount,
	sum(if(s.thisMonth = 1,s.amount,0)) as month1,
	sum(if(s.thisMonth = 2,s.amount,0)) as month2,
	sum(if(s.thisMonth = 3,s.amount,0)) as month3,
	sum(if(s.thisMonth = 4,s.amount,0)) as month4,
	sum(if(s.thisMonth = 5,s.amount,0)) as month5,
	sum(if(s.thisMonth = 6,s.amount,0)) as month6,
	sum(if(s.thisMonth = 7,s.amount,0)) as month7,
	sum(if(s.thisMonth = 8,s.amount,0)) as month8,
	sum(if(s.thisMonth = 9,s.amount,0)) as month9,
	sum(if(s.thisMonth = 10,s.amount,0)) as month10,
	sum(if(s.thisMonth = 11,s.amount,0)) as month11,
	sum(if(s.thisMonth = 12,s.amount,0)) as month12
from
	oa_stock_baseinfo c
	left join
	(
		select
			stockId,stockName,year(thisDate) as thisYear,month(thisDate) as thisMonth,sum(balanceAmount) as amount
		from
			oa_finance_stockbalance
		where year(thisDate) = $year
		group by
			stockId,year(thisDate),month(thisDate)
	) s on c.id = s.stockId
where c.id <> -1 and c.id in ($balanceStock)

union all

select
    '-2' as stockId,'环比变化情况' as stockName,
    sum(if(s.thisMonth = 1,s.amount,if(s.thisYearMonth = '$prevYearMonth',-s.amount,0))) +
    sum(if(s.thisMonth = 2,s.amount,if(s.thisMonth = 1,-s.amount,0))) +
    sum(if(s.thisMonth = 3,s.amount,if(s.thisMonth = 2,-s.amount,0))) +
    sum(if(s.thisMonth = 4,s.amount,if(s.thisMonth = 3,-s.amount,0))) +
    sum(if(s.thisMonth = 5,s.amount,if(s.thisMonth = 4,-s.amount,0))) +
    sum(if(s.thisMonth = 6,s.amount,if(s.thisMonth = 5,-s.amount,0))) +
    sum(if(s.thisMonth = 7,s.amount,if(s.thisMonth = 6,-s.amount,0))) +
    sum(if(s.thisMonth = 8,s.amount,if(s.thisMonth = 7,-s.amount,0))) +
    sum(if(s.thisMonth = 9,s.amount,if(s.thisMonth = 8,-s.amount,0))) +
    sum(if(s.thisMonth = 10,s.amount,if(s.thisMonth = 9,-s.amount,0))) +
    sum(if(s.thisMonth = 11,s.amount,if(s.thisMonth = 10,-s.amount,0))) +
    sum(if(s.thisYearMonth = '$thisYearMonth',s.amount,if(s.thisMonth = 11,-s.amount,0))) as allAmount,
    sum(if(s.thisMonth = 1,s.amount,if(s.thisYearMonth = '$prevYearMonth',-s.amount,0))) as month1,
    sum(if(s.thisMonth = 2,s.amount,if(s.thisMonth = 1,-s.amount,0))) as month2,
    sum(if(s.thisMonth = 3,s.amount,if(s.thisMonth = 2,-s.amount,0))) as month3,
    sum(if(s.thisMonth = 4,s.amount,if(s.thisMonth = 3,-s.amount,0))) as month4,
    sum(if(s.thisMonth = 5,s.amount,if(s.thisMonth = 4,-s.amount,0))) as month5,
    sum(if(s.thisMonth = 6,s.amount,if(s.thisMonth = 5,-s.amount,0))) as month6,
    sum(if(s.thisMonth = 7,s.amount,if(s.thisMonth = 6,-s.amount,0))) as month7,
    sum(if(s.thisMonth = 8,s.amount,if(s.thisMonth = 7,-s.amount,0))) as month8,
    sum(if(s.thisMonth = 9,s.amount,if(s.thisMonth = 8,-s.amount,0))) as month9,
    sum(if(s.thisMonth = 10,s.amount,if(s.thisMonth = 9,-s.amount,0))) as month10,
    sum(if(s.thisMonth = 11,s.amount,if(s.thisMonth = 10,-s.amount,0))) as month11,
    sum(if(s.thisYearMonth = '$thisYearMonth',s.amount,if(s.thisMonth = 11,-s.amount,0))) as month12
from
    oa_stock_baseinfo c
    left join
    (
        select
            stockId,stockName,year(thisDate) as thisYear,month(thisDate) as thisMonth,date_format(thisDate,'%Y%m') as thisYearMonth,sum(balanceAmount) as amount
        from
            oa_finance_stockbalance
        where date_format(thisDate,'%Y%m') <= date_format('$thisDate','%Y%m') and date_format(thisDate,'%Y%m') >= date_format('$prevDate','%Y%m')
        group by
            stockId,year(thisDate),month(thisDate)
    ) s on c.id = s.stockId
where c.id <> -1 and c.id in ($balanceStock)

union all
select
	c.id as stockId,c.stockName,sum(if(s.amount is null,0,s.amount)) as allAmount,
	sum(if(s.thisMonth = 1,s.amount,0)) as month1,
	sum(if(s.thisMonth = 2,s.amount,0)) as month2,
	sum(if(s.thisMonth = 3,s.amount,0)) as month3,
	sum(if(s.thisMonth = 4,s.amount,0)) as month4,
	sum(if(s.thisMonth = 5,s.amount,0)) as month5,
	sum(if(s.thisMonth = 6,s.amount,0)) as month6,
	sum(if(s.thisMonth = 7,s.amount,0)) as month7,
	sum(if(s.thisMonth = 8,s.amount,0)) as month8,
	sum(if(s.thisMonth = 9,s.amount,0)) as month9,
	sum(if(s.thisMonth = 10,s.amount,0)) as month10,
	sum(if(s.thisMonth = 11,s.amount,0)) as month11,
	sum(if(s.thisMonth = 12,s.amount,0)) as month12
from
	oa_stock_baseinfo c
	left join
	(
		select
			stockId,stockName,year(thisDate) as thisYear,month(thisDate) as thisMonth,sum(balanceAmount) as amount
		from
			oa_finance_stockbalance
		where year(thisDate) = $year
		group by
			stockId,year(thisDate),month(thisDate)
	) s on c.id = s.stockId
where c.id <> -1 and c.id in ($oldStock)
group by c.id

union all

select
	'-3' as stockId,'旧设备金额汇总' as stockName,sum(if(s.amount is null,0,s.amount)) as allAmount,
	sum(if(s.thisMonth = 1,s.amount,0)) as month1,
	sum(if(s.thisMonth = 2,s.amount,0)) as month2,
	sum(if(s.thisMonth = 3,s.amount,0)) as month3,
	sum(if(s.thisMonth = 4,s.amount,0)) as month4,
	sum(if(s.thisMonth = 5,s.amount,0)) as month5,
	sum(if(s.thisMonth = 6,s.amount,0)) as month6,
	sum(if(s.thisMonth = 7,s.amount,0)) as month7,
	sum(if(s.thisMonth = 8,s.amount,0)) as month8,
	sum(if(s.thisMonth = 9,s.amount,0)) as month9,
	sum(if(s.thisMonth = 10,s.amount,0)) as month10,
	sum(if(s.thisMonth = 11,s.amount,0)) as month11,
	sum(if(s.thisMonth = 12,s.amount,0)) as month12
from
	oa_stock_baseinfo c
	left join
	(
		select
			stockId,stockName,year(thisDate) as thisYear,month(thisDate) as thisMonth,sum(balanceAmount) as amount
		from
			oa_finance_stockbalance
		where year(thisDate) = $year
		group by
			stockId,year(thisDate),month(thisDate)
	) s on c.id = s.stockId
where c.id <> -1 and c.id in ($oldStock)
union all
select
    '-4' as stockId,'环比变化情况' as stockName,
    sum(if(s.thisMonth = 1,s.amount,if(s.thisYearMonth = '$prevYearMonth',-s.amount,0))) +
    sum(if(s.thisMonth = 2,s.amount,if(s.thisMonth = 1,-s.amount,0))) +
    sum(if(s.thisMonth = 3,s.amount,if(s.thisMonth = 2,-s.amount,0))) +
    sum(if(s.thisMonth = 4,s.amount,if(s.thisMonth = 3,-s.amount,0))) +
    sum(if(s.thisMonth = 5,s.amount,if(s.thisMonth = 4,-s.amount,0))) +
    sum(if(s.thisMonth = 6,s.amount,if(s.thisMonth = 5,-s.amount,0))) +
    sum(if(s.thisMonth = 7,s.amount,if(s.thisMonth = 6,-s.amount,0))) +
    sum(if(s.thisMonth = 8,s.amount,if(s.thisMonth = 7,-s.amount,0))) +
    sum(if(s.thisMonth = 9,s.amount,if(s.thisMonth = 8,-s.amount,0))) +
    sum(if(s.thisMonth = 10,s.amount,if(s.thisMonth = 9,-s.amount,0))) +
    sum(if(s.thisMonth = 11,s.amount,if(s.thisMonth = 10,-s.amount,0))) +
    sum(if(s.thisYearMonth = '$thisYearMonth',s.amount,if(s.thisMonth = 11,-s.amount,0))) as allAmount,
    sum(if(s.thisMonth = 1,s.amount,if(s.thisYearMonth = '$prevYearMonth',-s.amount,0))) as month1,
    sum(if(s.thisMonth = 2,s.amount,if(s.thisMonth = 1,-s.amount,0))) as month2,
    sum(if(s.thisMonth = 3,s.amount,if(s.thisMonth = 2,-s.amount,0))) as month3,
    sum(if(s.thisMonth = 4,s.amount,if(s.thisMonth = 3,-s.amount,0))) as month4,
    sum(if(s.thisMonth = 5,s.amount,if(s.thisMonth = 4,-s.amount,0))) as month5,
    sum(if(s.thisMonth = 6,s.amount,if(s.thisMonth = 5,-s.amount,0))) as month6,
    sum(if(s.thisMonth = 7,s.amount,if(s.thisMonth = 6,-s.amount,0))) as month7,
    sum(if(s.thisMonth = 8,s.amount,if(s.thisMonth = 7,-s.amount,0))) as month8,
    sum(if(s.thisMonth = 9,s.amount,if(s.thisMonth = 8,-s.amount,0))) as month9,
    sum(if(s.thisMonth = 10,s.amount,if(s.thisMonth = 9,-s.amount,0))) as month10,
    sum(if(s.thisMonth = 11,s.amount,if(s.thisMonth = 10,-s.amount,0))) as month11,
    sum(if(s.thisYearMonth = '$thisYearMonth',s.amount,if(s.thisMonth = 11,-s.amount,0))) as month12
from
    oa_stock_baseinfo c
    left join
    (
        select
            stockId,stockName,year(thisDate) as thisYear,month(thisDate) as thisMonth,date_format(thisDate,'%Y%m') as thisYearMonth,sum(balanceAmount) as amount
        from
            oa_finance_stockbalance
        where date_format(thisDate,'%Y%m') <= date_format('$thisDate','%Y%m') and date_format(thisDate,'%Y%m') >= date_format('$prevDate','%Y%m')
        group by
            stockId,year(thisDate),month(thisDate)
    ) s on c.id = s.stockId
where c.id <> -1 and c.id in ($oldStock)

QuerySQL;
GenAttrXmlData($sql,false);