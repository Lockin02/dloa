//��ʼ������
$(function(){
	//��ӡ��Ϣ��ֵ
	$("#headNum").html($("#allNum").html());
	var idStr = $("#ids").val();
	var idArr = idStr.split(",");
	for(var i=0;i<idArr.length;i++){
		var id = idArr[i];
		var detailObj = $("#detail"+id);
		detailObj.yxeditgrid({
			url : "?model=finance_compensate_compensatedetail&action=listJson",
			param : {"mainId":id},
			tableClass : 'form_in_table',
			type : 'view',
			async : false,
			event : {
				'reloadData': function(e,g,data) {
					if(data.length > 0){
						detailObj.find('tbody').after("<tr class='tr_count'>" +
	                        "<td></td><td>�ϼ�</td><td colspan='4'></td>" +
	                        "<td style='text-align:right;'>" +
	                            moneyFormat2($("#formMoney"+id).val()) +
	                        "</td>" +
	                        "<td></td>" +
	                        "<td style='text-align:right;'>" +
	                            moneyFormat2($("#compensateMoney"+id).val()) +
	                        "</td><td colspan='2'></td>" +
	                        "</tr>");
					}
				}
			},
			colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden',
				isSubmit : true
			}, {
	            display : '����Id',
	            name : 'productId',
	            type : 'hidden'
	        }, {
				display : '���ϱ��',
				name : 'productNo',
				width : 80
			}, {
				display : '��������',
				name : 'productName'
			}, {
				display : '����ͺ�',
				name : 'productModel'
			}, {
				display : '��λ',
				name : 'unitName',
				width : 50
			}, {
				display : '����',
				name : 'number',
				width : 70
			}, {
				display : '���',
				name : 'money',
				width : 70,
				align : 'right',
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
	            display : '��ֵ',
	            name : 'price',
	            width : 70,
	            align : 'right',
	            process : function(v){
	                return moneyFormat2(v);
	            }
	        }, {
				display : '�⳥���',
				name : 'compensateMoney',
				width : 70,
				align : 'right',
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '��ע',
				name : 'remark',
	            width : 70,
				align : 'left'
			}, {
				display : '���к�',
				name : 'serialNos',
	            width : 70,
				align : 'left'
			}]
		});;
	}
});