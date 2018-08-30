$(document).ready(function() {
     chooseBudget();
  })


//选择设备配置
 function chooseBudget(){
//	 	 $("#infoList").empty();
	 	 $.post("?model=equipment_budget_budget&action=baseinfoList", {
			 ids : $("#budgetId").val(),
			 type : 'view'
		}, function(data) {
			   var th = "<tr>"+
				           "<td ><b>序号</b></td>"+
				           "<td ><b>所属分类</b></td>"+
				           "<td ><b>物料编码</b></td>"+
				           "<td ><b>物料名称</b></td>"+
				           "<td ><b>规格型号</b></td>"+
				           "<td ><b>品牌</b></td>"+
				           "<td ><b>单位</b></td>"+
				           "<td ><b>报价</b></td>"+
				           "<td><b>需求数量</b></td>"+
				           "<td><b>总金额</b></td>"+
				           "<td ><b>备注</b></td>"+
				       "</tr>";
			 var total = "<tr>"+
			             "<td><b>合计</b></td>"+
			             "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>"+
					     "<td><b>金额汇总</b></td>"+
			             "<td><input type='text' class='rimless_textA formatMoney' readonly id='allMoney' name='budget[allMoney]'  /></td>"+
                         "<td style='width:200px'></td>"+
					   "</tr>";

//                $("#infoList").append(th);
				$("#infoList").after(data);
//				$("#infoList").append(total);
				 var moneyAll = $("#moneyTemp").val();
			     $("#allMoney").val(moneyAll);

			     formateMoney();
		});
		};
