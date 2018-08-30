$(document).ready(function() {
     budgetList();
  })
//// 删除产品
//function delectPro(obj) {
//	if (confirm('确定要删除该行？')) {
//		var rowNo = obj.parentNode.rowIndex;
//		$(obj).parent().remove();
//		listNum();
//		countSum();
//		// $(obj).parent().hide();
//	}
//}
//// 处理序号
//function listNum() {
//	var goodsTable = document.getElementById("infoList");
//	var rows = goodsTable.rows.length;
//	for (var i = 0; i < rows; i++) {
//		$("#infoList tr:eq(" + i + ") td:eq(0)").text(i + 2);
//	}
//}
function countMoney(){
	var n = $("input[id^='price']").length;
    var allmoneyArr = new Array();//总金额数组
  $("#infoListNum").children().each(function() {
       	 var price = $(this).find("input[id^='price']").val();
         var num   = $(this).find("input[id^='num']").val();
         money = price * num;
        if(money != '0'){
           //计算单行总金额
           $(this).find("input[id^='money']").val(money);
           $(this).find("input[id^='money']").blur();
        }else{
           $(this).find("input[id^='money']").val("0");
        }
          if(isNaN(money)){
             money = '0'
          }
          allmoneyArr.push(money);
       });
     //计算金额汇总
           var allmoney = accAddMore(allmoneyArr);
           $("#allMoney").val(allmoney);
           $("#allMoney_v").val(allmoney);
           $("#allMoney").blur();
           $("#allMoney_v").blur();
         formateMoney();
}
//选择设备配置
 function chooseBudget(){
     url = "?model=equipment_budget_budgetbaseinfo&action=toChooseBudget&idStr="+$("#idStr").val();;
	 var returnValue = showModalDialog(url, '',"dialogWidth:1000px;dialogHeight:600px;");

	 if(returnValue){
//	 	 $("#idStr").val(returnValueStr);"
//	 	 $("#infoList").empty();
	 	 returnValueStr = returnValue.toString();
	 	 $.post("?model=equipment_budget_budget&action=baseinfoList", {
			 ids : returnValueStr,
			 type : 'add'
		}, function(data) {
			   var th = "<tr>"+
			               "<td ><b>操作</b></td>"+
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
			             "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>"+
					     "<td><b>金额汇总</b></td>"+
			             "<td><input type='text' class='rimless_textA formatMoney' readonly id='allMoney' name='budget[allMoney]' /></td>"+
                         "<td style='width:200px'></td>"+
					   "</tr>";

//                $("#infoList").append(th);
				$("#infoList").after(data);
				listNum();
//				$("#infoList").append(total);
		});
		};
	 };
//数据渲染
function budgetList(){
//	 	 $("#infoList").empty();
	 	 var moneyAll = $("#moneyTemp").val();
	 	 $.post("?model=equipment_budget_budget&action=baseinfoList", {
			 ids : $("#budgetId").val(),
			 type : 'edit'
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
			             "<td><input type='text' class='rimless_textA formatMoney' readonly id='allMoney' name='budget[allMoney]' value='"+moneyAll+"'  /></td>"+
                         "<td style='width:200px'></td>"+
					   "</tr>";

//                $("#infoList").append(th);
				$("#infoList").after(data);
				countMoney();
//				$("#infoList").append(total);
		});
		}

//删除
function mydel(obj) {
	if (confirm('确定要删除该行？')) {
		var rowNo = obj.parentNode.rowIndex;
		var mytable = document.getElementById("infoListNum");
		mytable.deleteRow(rowNo);
		listNum();
		countMoney();
	}
}
//重置序号
function listNum() {
	var i = 1;
	$("#infoListNum").children("tr").each(function() {
		var oldNum = $(this).children("td").eq(1).text()
       if(oldNum != '序号' && oldNum != ''){
         $(this).children("td").eq(1).text(i);
			i++;
       }
	})
}