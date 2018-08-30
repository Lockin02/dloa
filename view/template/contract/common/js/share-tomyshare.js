var show_page = function(page) {
	$("#shareGrid").yxgrid("reload");
};
$(function() {
	$("#shareGrid").yxgrid({
        param : {"toshareNameId": $("#userId").val()},
		model : 'contract_common_share',
		isViewAction : false,
		isEditAction : false,
        isDelAction : false,
        isAddAction : false,
        showcheckbox : false,
		sortorder : "DESC",
		title : '����ĺ�ͬ',
		menusEx : [
		{
			text : '�鿴',
			icon : 'view',
			action: function(row){
				  if(row.orderType == 'oa_sale_order'){
				     showOpenWin('?model=projectmanagent_order_order&action=toViewTab&id=' + row.orderId + "&skey="+row['skey_']);
				  } else if (row.orderType == 'oa_sale_service'){
				     showOpenWin("?model=engineering_serviceContract_serviceContract&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  } else if (row.orderType == 'oa_sale_lease'){
                     showOpenWin("?model=contract_rental_rentalcontract&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  } else if (row.orderType == 'oa_sale_rdproject') {
                     showOpenWin("?model=rdproject_yxrdproject_rdproject&action=toViewTab&id=" + row.orderId + "&skey="+row['skey_'])
                  }

			}
		},{
			text : '����',
			icon : 'add',
			action: function(row){
				     window.open ('?model=contract_common_allcontract&action=importCont&id='
				                      + row.orderId
				                      +'&type='
				                      +row.orderType
				                      + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
			}
		   }],
		// ��
			colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							        },{
                    					name : 'orderType',
                  					display : '�����ͬ����',
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
                              },{
                    					name : 'orderName',
                  					display : '�����ͬ����',
                  					sortable : true,
                  					width : 150
                              },{
                    					name : 'shareName',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'shareDate',
                  					display : '����ʱ��',
                  					sortable : true
                              },{
                    					name : 'toshareName',
                  					display : '��������',
                  					sortable : true
                              }],
                              comboEx : [ {
									text : '��ͬ����',
									key : 'orderType',
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
		searchitems : [{
			display : '��ͬ����',
			name : 'orderName'
		}]
	});
});