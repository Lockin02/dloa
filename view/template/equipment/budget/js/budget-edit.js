$(document).ready(function() {
     budgetList();
  })
//// ɾ����Ʒ
//function delectPro(obj) {
//	if (confirm('ȷ��Ҫɾ�����У�')) {
//		var rowNo = obj.parentNode.rowIndex;
//		$(obj).parent().remove();
//		listNum();
//		countSum();
//		// $(obj).parent().hide();
//	}
//}
//// �������
//function listNum() {
//	var goodsTable = document.getElementById("infoList");
//	var rows = goodsTable.rows.length;
//	for (var i = 0; i < rows; i++) {
//		$("#infoList tr:eq(" + i + ") td:eq(0)").text(i + 2);
//	}
//}
function countMoney(){
	var n = $("input[id^='price']").length;
    var allmoneyArr = new Array();//�ܽ������
  $("#infoListNum").children().each(function() {
       	 var price = $(this).find("input[id^='price']").val();
         var num   = $(this).find("input[id^='num']").val();
         money = price * num;
        if(money != '0'){
           //���㵥���ܽ��
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
     //���������
           var allmoney = accAddMore(allmoneyArr);
           $("#allMoney").val(allmoney);
           $("#allMoney_v").val(allmoney);
           $("#allMoney").blur();
           $("#allMoney_v").blur();
         formateMoney();
}
//ѡ���豸����
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
			               "<td ><b>����</b></td>"+
				           "<td ><b>���</b></td>"+
				           "<td ><b>��������</b></td>"+
				           "<td ><b>���ϱ���</b></td>"+
				           "<td ><b>��������</b></td>"+
				           "<td ><b>����ͺ�</b></td>"+
				           "<td ><b>Ʒ��</b></td>"+
				           "<td ><b>��λ</b></td>"+
				           "<td ><b>����</b></td>"+
				           "<td><b>��������</b></td>"+
				           "<td><b>�ܽ��</b></td>"+
				           "<td ><b>��ע</b></td>"+
				       "</tr>";
			 var total = "<tr>"+
			             "<td><b>�ϼ�</b></td>"+
			             "<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>"+
					     "<td><b>������</b></td>"+
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
//������Ⱦ
function budgetList(){
//	 	 $("#infoList").empty();
	 	 var moneyAll = $("#moneyTemp").val();
	 	 $.post("?model=equipment_budget_budget&action=baseinfoList", {
			 ids : $("#budgetId").val(),
			 type : 'edit'
		}, function(data) {
			   var th = "<tr>"+
				           "<td ><b>���</b></td>"+
				           "<td ><b>��������</b></td>"+
				           "<td ><b>���ϱ���</b></td>"+
				           "<td ><b>��������</b></td>"+
				           "<td ><b>����ͺ�</b></td>"+
				           "<td ><b>Ʒ��</b></td>"+
				           "<td ><b>��λ</b></td>"+
				           "<td ><b>����</b></td>"+
				           "<td><b>��������</b></td>"+
				           "<td><b>�ܽ��</b></td>"+
				           "<td ><b>��ע</b></td>"+
				       "</tr>";
			 var total = "<tr>"+
			             "<td><b>�ϼ�</b></td>"+
			             "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>"+
					     "<td><b>������</b></td>"+
			             "<td><input type='text' class='rimless_textA formatMoney' readonly id='allMoney' name='budget[allMoney]' value='"+moneyAll+"'  /></td>"+
                         "<td style='width:200px'></td>"+
					   "</tr>";

//                $("#infoList").append(th);
				$("#infoList").after(data);
				countMoney();
//				$("#infoList").append(total);
		});
		}

//ɾ��
function mydel(obj) {
	if (confirm('ȷ��Ҫɾ�����У�')) {
		var rowNo = obj.parentNode.rowIndex;
		var mytable = document.getElementById("infoListNum");
		mytable.deleteRow(rowNo);
		listNum();
		countMoney();
	}
}
//�������
function listNum() {
	var i = 1;
	$("#infoListNum").children("tr").each(function() {
		var oldNum = $(this).children("td").eq(1).text()
       if(oldNum != '���' && oldNum != ''){
         $(this).children("td").eq(1).text(i);
			i++;
       }
	})
}