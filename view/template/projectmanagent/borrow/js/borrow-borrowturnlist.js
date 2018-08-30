var show_page = function(page) {
	$("#borrowequGrid").yxgrid("reload");
};
$(function (){
	 var customerId = $("#customerId").val();
	 var salesNameId = $("#salesNameId").val();
	 var borrowId = $("#borrowId").val();
	 if(borrowId == ''){
	    var paramArr = { 'salesNameId' : salesNameId }
	 }else{
	    var paramArr = { 'customerId' : customerId , 'borrowId' : borrowId }
	 }

   $("#borrowequGrid").yxgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'borrowequJson',
		param : paramArr,
		height : 500,
		isAllPageCheckedId : true,//�������з�ҳ��ѡ������
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isViewAction : false,
		isOpButton:false,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '��������',
					sortable : true,
					width : 200
				}, {
					name : 'productId',
					display : '����ID',
					sortable : true,
					hide : true
				}, {
					name : 'productNo',
					display : '���ϱ���',
					sortable : true
				}, {
					name : 'productModel',
					display : '���/�ͺ�',
					sortable : true,
					width : 80
				}, {
					name : 'warrantyPeriod',
					display : '������',
					sortable : true,
					width : 50
				}, {
					name : 'number',
					display : '����',
					sortable : true,
					width : 50
				}, {
					name : 'executedNum',
					display : '��ִ������',
					sortable : true,
					width : 50
				}, {
					name : 'backNum',
					display : '�黹����',
					sortable : true,
					width : 50
				}
//				, {
//					name : 'toContractNum',
//					display : 'ת��������',
//					sortable : true,
//					width : 50,
//					process : function(v){
//					   if(v == ''){
//					      return 0;
//					   }else{
//					      return v;
//					   }
//					}
//				}
				, {
					name : 'price',
					display : '����',
					sortable : true
				}, {
					name : 'money',
					display : '���',
					sortable : true
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true
				}, {
					name : 'customerId',
					display : '�ͻ�ID',
					sortable : true,
					hide : true
				}, {
					name : 'salesName',
					display : '���۸�����',
					sortable : true
				}, {
					name : 'salesNameId',
					display : '���۸�����ID',
					sortable : true,
					hide : true
				}, {
					name : 'borrowId',
					display : '������Դ��ID',
					sortable : true,
					hide : true
				}],
		buttonsEx : [{
			name : 'Add',
			text : "ȷ��",
			icon : 'add',
			action : function(rowData, rows, rowIds, g) {
					if (rows) {
						showThickboxWin('?model=projectmanagent_borrow_borrow&action=tochooseCon&ids='
						    +rowIds
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400");
//						parent.window.returnValue = rows;
						// $.showDump(outJson);
//						parent.window.close();
					} else {
						alert('����ѡ���¼');
					}
			}
		},{
			name : 'Add',
			text : "ȫѡ",
			icon : 'add',
			action : function(rowData, rows, rowIds, g) {
				var ids = $("#borrowequIds").val();
				if(ids != ''){
					var idArr = ids.split(",");
					g.options.checkedIdArr = idArr;
					g.reload();
				}else{
					alert("û�����ݱ�ѡ�У���ˢ��ҳ������");
				}
			}
		}],
//		event : {
//			'row_dblclick' : function(e, row, rowData) {
//				if (rowData) {
//					// ���json
//					outJson = {
//						"id" : rowData.id,
//						"prodcutId" : rowData.productId,
//						"productCode" : rowData.prodcutNo,
//						"productName" : rowData.productName,
//						"productModel" : rowData.productModel,
//						"number" : rowData.number,
//						"price" : rowData.price,
//						"money" : rowData.money,
//						"warrantyPeriod" : rowData.warrantyPeriod,
//						"isBorrowToorder" : 1,
//						"toBorrowId" : rowData.borrowId,
//						"toBorrowequId" : rowData.id
//					};
//					parent.window.returnValue = outJson;
//
//					// $.showDump(outJson);
//					parent.window.close();
//				} else {
//					alert('����ѡ���¼');
//				}
//			}
//		},
		toViewConfig : {
			action : 'toView'
		}
	});
   //��Ⱦ������ת��������id��
   getBorrowequIds(paramArr);
});

//������ת���ۻ�ȡ����id��
function getBorrowequIds(paramArr){
	$.ajax({
        type: 'POST',
        url: "?model=projectmanagent_borrow_borrow&action=borrowequIds",
        data: paramArr,
        async: false,
        success: function (data) {
        	$("#borrowequIds").val(data);
        }
    });
}
