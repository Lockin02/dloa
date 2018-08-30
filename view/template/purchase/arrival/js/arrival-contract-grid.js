function getQueryStringPay(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null)
		return unescape(r[2]);
	return null;
}
var show_page = function(page) {
	$("#arrivalContractGrid").yxgrid("reload");
};
$(function() {
			var gdbtable = getQueryStringPay('gdbtable');
			$("#arrivalContractGrid").yxgrid({
				model : 'purchase_arrival_arrival',
				action:'contractPageJson',
               	title : '����֪ͨ�� -- �ɹ����� :' + $("#objCode").val(),
               	isToolBar:false,
               	showcheckbox:false,
        		param : {"purchaseId" : $("#objId").val(),"gdbtable" : gdbtable},
				isOpButton:false,
				bodyAlign:'center',
						//����Ϣ
					colModel : [ {
										name : 'sequence',
										display : '���ϱ��',
										width:80
									}, {
										name : 'productName',
										width : 200,
										display : '��������'
									},{
										name : 'batchNum',
										display : "���κ�"
									},{
										name : 'arrivalDate',
										display : "��������",
										width:70
									},{
										name : 'arrivalNum',
										display : "��������",
										width:60
									},{
										name : 'storageNum',
										display : "���������",
										width:60
									},
									{
     								display : 'id',
     								name : 'id',
     								sortable : true,
     								hide : true
						        },{
                					name : 'arrivalCode',
                  					display : '���ϵ���',
                  					sortable : true,
                  					width:80
                              },{
                    					name : 'state',
                  					display : '���ϵ�״̬',
                  					sortable : true,
									width:80,
									process : function(v, row) {
										if (row.state == '0') {
											return "δִ��";
										} else {
											return "��ִ��";
										}
									}
                              },{
                    					name : 'supplierName',
                  					display : '��Ӧ������',
                  					sortable : true,
      								width:150

                              },{
                    					name : 'purchManName',
                  					display : '�ɹ�Ա',
                  					sortable : true,
									width:60
                              },{
                    					name : 'deliveryPlace',
                  					display : '�����ص�',
                  					sortable : true,
										width:80
                              },{
                    					name : 'stockName',
                  					display : '���ϲֿ�����',
                  					sortable : true
                              }],
				        buttonsEx : [
				        	{
								text : '����������ʷ',
								icon : 'view',
								action : function(row) {
									location="?model=finance_payablesapply_payablesapply&action=toHistory"
										+ "&obj[objId]=" + $("#objId").val()
									    + "&obj[objCode]=" + $("#objCode").val()
									    + "&obj[objType]=" + $("#objType").val()
									    + "&obj[supplierId]=" + $("#supplierId").val()
									    + "&obj[supplierName]=" + $("#supplierName").val()
									    + "&gdbtable=" + gdbtable
									    + "&skey=" + $("#skey").val()
								}
							},{
								text : '�����¼��ʷ',
								icon : 'view',
								action : function(row) {
									location="?model=finance_payables_payables&action=toHistory"
										+ "&obj[objId]=" + $("#objId").val()
									    + "&obj[objCode]=" + $("#objCode").val()
									    + "&obj[objType]=" + $("#objType").val()
									    + "&obj[supplierId]=" + $("#supplierId").val()
									    + "&obj[supplierName]=" + $("#supplierName").val()
									    + "&gdbtable=" + gdbtable
									    + "&skey=" + $("#skey").val()
								}
							}
							,{
								text : '�ɹ���Ʊ��¼',
								icon : 'view',
								action : function(row) {
									location="?model=finance_invpurchase_invpurchase&action=toHistory"
										+ "&obj[objId]=" + $("#objId").val()
									    + "&obj[objCode]=" + $("#objCode").val()
									    + "&obj[objType]=" + $("#objType").val()
									    + "&obj[supplierId]=" + $("#supplierId").val()
									    + "&obj[supplierName]=" + $("#supplierName").val()
									    + "&gdbtable=" + gdbtable
									    + "&skey=" + $("#skey").val()
								}
							}
							,{
								text : '���ϼ�¼',
								icon : 'edit',
								action : function(row) {
									location="?model=purchase_arrival_arrival&action=toListByOrder"
										+ "&obj[objId]=" + $("#objId").val()
									    + "&obj[objCode]=" + $("#objCode").val()
									    + "&obj[objType]=" + $("#objType").val()
									    + "&obj[supplierId]=" + $("#supplierId").val()
									    + "&obj[supplierName]=" + $("#supplierName").val()
									    + "&gdbtable=" + gdbtable
									    + "&skey=" + $("#skey").val()
								}
							}
				        ],
						//��չ�Ҽ��˵�
						menusEx : [
						{
							text : '�鿴',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=purchase_arrival_arrival&action=init&perm=view&id="+row.id+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800"+"&gdbtable=" + gdbtable);
								}else{
									alert("��ѡ��һ������");
								}
							}

						}
						],
							// Ĭ������˳��
							sortorder : "DESC",
							sortname:"updateTime"
 		});
 });