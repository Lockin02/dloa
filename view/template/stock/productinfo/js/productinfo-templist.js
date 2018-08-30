var show_page = function(page) {
	$("#templist").yxgrid("reload");
};
$(function() {
	$("#templist").yxgrid({
		model: 'projectmanagent_order_order',
		action : 'customizelistJson',
		param : {'ExaStatusV' : "���"},
		title: '��ʱ������Ϣ',
			isViewAction : false,
			isEditAction : false,
			isDelAction : false,
			isAddAction : false,
			showcheckbox : false,
		// ��չ�Ҽ��˵�

		menusEx : [{
			text : '��ͬ��Ϣ',
			icon : 'view',
			action: function(row){
				  if(row.tablename == 'oa_sale_order'){
				     showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id=' + row.orderId + "&skey="+row['skey_']);
				  } else if (row.tablename == 'oa_sale_service'){
				     showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  } else if (row.tablename == 'oa_sale_lease'){
                     showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  } else if (row.tablename == 'oa_sale_rdproject') {
                     showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  }

			}
		},{
			text : '����',
			icon : 'edit',
			action : function(row) {
				 showThickboxWin('?model=stock_productinfo_productinfo&action=handle&id='
				    + row.orgId
				    +'&type='
				    +row.tablename
				    +'&placeValuesBefore&TB_iframe=true&modal=false&height=100&width=300');
//				if(confirm("��ȷ���Ƿ�����������������Ϣ��")){
//				    showThickboxWin('?model=stock_productinfo_productinfo&action=tempadd&id='
//				    + row.id
//				    +'&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
//				}else{
//				    alert("������");
//				}
			}
		}],

		//����Ϣ
		colModel : [ {
							display : 'id',
							name : 'id',
							sortable : true,
							hide : true
						},{
			        name : 'tablename',
			        display : '��ͬ����',
			        sortable : true,
			        process : function(v){
  						if( v == 'oa_sale_order'){
  							return "���ۺ�ͬ";
  						}else if(v == 'oa_sale_service'){
  							return "�����ͬ";
  						}else if(v == 'oa_sale_lease'){
  							return "���޺�ͬ";
  						}else if(v == 'oa_sale_rdproject'){
  							return "�з���ͬ";
  						}
  					}
			          }, {
							display : '������ͬID',
							name : 'orderId',
							sortable : true,
							hide : true
						}, {
							display : '������ͬ��',
							name : 'orderCode',
							sortable : true,
							width : 210
						},{
							display : '��ʱ��ͬ��',
							name : 'orderTempCode',
							sortable : true,
							width : 210
						},{
							display : '��ͬ״̬',
							name : 'ExaStatus',
							sortable : true,
							hide : true,
							width : 60
						},{
							display : '������ͬ',
							name : 'orderName',
							sortable : true,
							width : 150
						},{
							display : '��������',
							name : 'productName',
							sortable : true
						}, {
							name : 'productModel',
							display : '����ͺ�',
							sortable : true
						},  {
							name : 'number',
							display : '����',
							sortable : true
						},{
							name : 'price',
							display : '����',
							sortable : true,
							process : function(v){
								return moneyFormat2(v);
				            }
						},{
							name : 'money',
							display : '���',
							sortable : true,
							process : function(v){
								return moneyFormat2(v);
				            }
						},{
							name : 'projArraDT',
							display : '�ƻ���������',
							sortable : true
						},{
							name : 'remark',
							display : '��ע',
							sortable : true,
							width : 200
						},{
							name : 'warranty',
							display : '������(��)',
							hide : true,
							sortable : true
						} ],
						 comboEx : [ {
							text : '��ͬ����',
							key : 'tablename',
							data : [ {
								text : '���ۺ�ͬ',
								value : 'oa_sale_order'
							}, {
								text : '���޺�ͬ',
								value : 'oa_sale_lease'
							},{
								text : '�����ͬ',
								value : 'oa_sale_service'
							},{
								text : '�з���ͬ',
								value : 'oa_sale_rdproject'
							}  ]
						}],

		 /**
			 * ��������
			 */
		searchitems : [
		{
			display : '��ͬ���',
			name : 'orderCodeOrTempSearchV'
		},
		{
			display : '��ͬ����',
			name : 'orderName'
		}]


	});
});