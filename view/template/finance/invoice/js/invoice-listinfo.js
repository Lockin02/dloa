$(function(){
});


//ËÑË÷·½·¨
function searchBtn(){
	window.open("?model=finance_invoice_invoice&action=listinfoSearch"
		+ "&beginYear="  + $("#beginYear").val()
		+ "&endYear="  + $("#endYear").val()
		+ "&beginMonth="  + $("#beginMonth").val()
		+ "&endMonth="  + $("#endMonth").val()
		+ "&customerId="  + $("#customerId").val()
		+ "&customerName="  + $("#customerName").val()
		+ "&objCodeSearch="  + $("#objCodeSearch").val()
		+ "&invoiceNo="  + $("#invoiceNo").val()
		+ "&customerType="  + $("#customerType").val()
		+ "&areaName="  + $("#areaName").val()
		+ "&customerProvince="  + $("#customerProvince").val()
		+ "&salesmanId="  + $("#salesmanId").val()
		+ "&salesman="  + $("#salesman").val()
		+ "&signSubjectName="  + $("#signSubjectName").val()
	,'newwindow1','height=500,width=800');
}