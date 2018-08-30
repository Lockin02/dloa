var show_page = function(page) {
	$("#shippedGrid").yxsubgrid("reload");
};
function hasEqu( orgid,type ){
	var equNum = 0
	 $.ajax({
		type : 'POST',
		url : '?model=contract_common_allcontract&action=getEquById',
		data : {
			id : orgid,
			type : type
		},
	    async: false,
		success : function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
function cusNum(orgid,type){
    var cusNum = 0
     $.ajax({
          type : 'POST',
          url : '?model=contract_common_allcontract&action=cusById',
          data : { id : orgid,
                   type : type
                 },
          async: false,
          success : function (data){
                cusNum = data;
                return false ;
          }
     })
     return cusNum ;
}
$(function() {
	$("#shippedGrid").yxsubgrid({
		model : 'projectmanagent_order_order',
		title : '��ͬ����',
		action : 'shipmentsPageJson',
		param : {"ExaStatusArr":"���,���������","states" : "2,4","DeliveryStatus1" : "8,11"},
		isDelAction : false,
		isToolBar : false, // �Ƿ���ʾ������
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			name : 'rate',
			display : '����',
			sortable : false,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.orgid
						+ "&docType="
						+ row.tablename
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע��'+"<font color='gray'>"+v+"</font>"+'</a>';
			}
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			width : 80,
			sortable : true
		}, {
			name : 'deliveryDate',
			display : '��������',
			width : 80,
			sortable : true
		}, {
			name : 'objCode',
			display : 'ҵ�����',
			width : 80,
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			width : 160,
			sortable : true
		}, {
			name : 'orderCode',
			display : '������ͬ��',
			width : 160,
			sortable : true
		}, {
			name : 'orderTempCode',
			display : '��ʱ��ͬ��',
			width : 160,
			sortable : true
		}, {
			name : 'orderName',
			display : '��ͬ����',
			width : 180,
			sortable : true,
			hide : true
		}, {
			name : 'tablename',
			display : '��ͬ����',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "���ۺ�ͬ";
				}else if (v == 'oa_sale_lease') {
					return "���޺�ͬ";
				}else if (v == 'oa_sale_service'){
				    return "�����ͬ";
				}else if (v == 'oa_sale_rdproject'){
				    return "�з���ͬ";
				}
			}
		}, {
			name : 'DeliveryStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == '7') {
					return "δ����";
				} else if (v == '8') {
					return "�ѷ���";
				} else if (v == '10') {
					return "���ַ���";
				} else if (v == '11') {
					return "ֹͣ����";
				}
			},
			width : 60,
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '�´�״̬',
			sortable : true,
			process : function(v) {
				if (v == 'WXD') {
					return "δ�´�";
				} else if (v == 'BFXD') {
					return "�����´�";
				} else if (v == 'YXD') {
					return "���´�";
				} else if (v == 'WXFH') {
					return "���跢��";
				}
			},
			width : 60,
			sortable : true
		}, {
			name : 'state',
			display : '��ͬ״̬',
			width : 60,
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ�ύ";
				} else if (v == '1') {
					return "������";
				} else if (v == '2') {
					return "ִ����";
				} else if (v == '3') {
					return "�ѹر�";
				} else if (v == '4') {
					return "�����";
				}
			},
			width : 60,
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
//			hide : true,
			width : 60,
			sortable : true
		}, {
			name : 'sign',
			display : '�Ƿ�ǩԼ',
			width : 50,
			sortable : true
		}, {
			name : 'orderstate',
			display : 'ֽ�ʺ�ͬ״̬',
			width : 75,
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'parentOrder',
			display : '����ͬ����',
			sortable : true,
			hide : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=contract_common_allcontract&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
						paramId : 'orderId',// ���ݸ���̨�Ĳ�������
						colId : 'orgid'// ��ȡ���������ݵ�������

					},{paramId : 'docType',colId : 'tablename'}],
			// ��ʾ����
			colModel : [{
						name : 'productName',
						width : 200,
						display : '��Ʒ����'
					}, {
					    name : 'number',
					    display : '����',
						width : 40
					},{
					    name : 'lockNum',
					    display : '��������',
						width : 50,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'exeNum',
					    display : '�������',
						width : 50,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'issuedShipNum',
					    display : '���´﷢������',
						width : 90,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'executedNum',
					    display : '�ѷ�������',
						width : 60
					},{
					    name : 'issuedPurNum',
					    display : '���´�ɹ�����',
						width : 90,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'issuedProNum',
					    display : '���´���������',
						width : 90,
					    process : function(v){
					    	if(v==''){
					    		return 0;
					    	}else
					    		return v;
					    }
					},{
					    name : 'backNum',
					    display : '�˿�����',
						width : 60
					},{
					    name : 'projArraDate',
					    display : '�ƻ���������',
						width : 80,
					    process : function(v){
					    	if( v == null ){
					    		return '��';
					    	}else{
					    		return v;
					    	}
					    }
					}]
		},
//		lockCol:['orderCode','orderTempCode','orderName'],// ����������
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
		menusEx : [{
			text : '�鿴��ϸ',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=viewByOrder&id='
					+ row.orgid
					+ "&objType="
					+ row.tablename
					+ "&skey="
					+ row['skey_']
				);
			}
        },{
//			text : '���ȱ�ע',
//			icon : 'edit',
//			action : function(row) {
//				showThickboxWin('?model=stock_outplan_contractrate&action=page&docId='
//						+ row.orgid
//						+ "&docType="
//						+ row.tablename
//						+ "&objCode="
//						+ row.objCode
//						+ "&skey="
//						+ row['skey_']
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
//			}
//		}, {
            text: "�ر�",
			icon : 'delete',
			showMenuFn : function(row) {
				if ( row.DeliveryStatus != 11 ) {
					return true;
				}
				return false;
			},
            action: function(row){
            	if(confirm('ȷ��Ҫ�رշ�����')){
					 $.ajax({
						type : 'POST',
						url : '?model=contract_common_allcontract&action=closeCont&skey=' + row['skey_'],
						data : {
							id : row.orgid,
							type : row.tablename
						},
	//				    async: false,
						success : function(data) {
							alert("�رճɹ�");
							show_page();
							return false;
						}
					});
           		}
            }
        },{
            text: "�ָ�����",
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.DeliveryStatus == 11 ) {
					return true;
				}
				return false;
			},
            action: function(row){
            	if(confirm('ȷ��Ҫ�ָ�������')){
					 $.ajax({
						type : 'POST',
						url : '?model=contract_common_allcontract&action=recoverCont&skey=' + row['skey_'],
						data : {
							id : row.orgid,
							type : row.tablename
						},
	//				    async: false,
						success : function(data) {
							alert("�ָ��ɹ�");
							show_page();
							return false;
						}
					});
          		}
            }
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ����',
			name : 'orderName'
		},{
			display : '��ͬ���',
			name : 'orderCodeOrTempSearch'
		},{
			display : '�ͻ�����',
			name : 'customer'
		},{
			display : '������',
			name : 'createName'
		}],
		sortname : 'ExaDT',
		sortorder : 'DESC'
	});
});