$(document).ready(function() {
     chooseBudget();
  })


//ѡ���豸����
 function chooseBudget(){
//	 	 $("#infoList").empty();
	 	 $.post("?model=equipment_budget_budget&action=baseinfoList", {
			 ids : $("#budgetId").val(),
			 type : 'view'
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
