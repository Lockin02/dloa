//借试用转销售物料 从表信息显示
$(function(){
	var orderId = $("#orderId").val();
	var orderType = $("#orderType").val();

	$.post(
		"?model=projectmanagent_borrow_borrow&action=getBorrowToOrderequ",
		{
			orderId : orderId,
			orderType : orderType
		}, function(data) {
			if(data == 0){ //没有物料时
				$("#borrowOrderequ").append("");
			}else {
               var $html = $("<table class='form_main_table'>" +
    		               "<thead><tr align='left'><td colspan='11' class='form_header'>借试用转销售产品清单</td></tr></thead>" +
    		               "<tbody id='borrowbody'><tr class='main_tr_header'>" +
    		                                   "<th>序号</th>"+
    		                                   "<th>产品编号</th>"+
    		                                   "<th>产品名称</th>"+
    		                                   "<th>产品型号</th>"+
    		                                   "<th>数量</th>"+
    		                                   "<th>单价</th>"+
    		                                   "<th>金额</th>"+
    		                                   "<th>计划交货日期</th>"+
    		                                   "<th>保修期</th>"+
    		                                   "<th>加密配置</th>"+
    		                                   "<th>合同内</th></tr>"
    		                  );
    		    var $str = $(data);
    		    var $html2 = $("</tbody></table>");
    		    $html.append($str);
    		    $html.append($html2);
    		    $("#borrowOrderequ").append($html);
			}
		}
	);
});