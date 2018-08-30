/** �����¼��Ϣ�б�
 *  @linzx
 * */
var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
};
$(function() {
	$("#datadictList").yxgrid({
		model : 'asset_assetcard_clean',
		title : '�����¼',
		showcheckbox : false,
		isToolBar : true,
		isViewAction : false,
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,

		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'ԭ�����',
			name : 'businessNo',
			sortable : true,
			width : 120,
			process : function(v, row) {
				if (row.businessId) {
					switch (row.businessType) {
						case 'scrap' :
							return '<a href="#" onclick="javascript:window.open(\'?model=asset_disposal_scrap&action=init&perm=view&id='
									+ row.businessId
									+ '\')">'
									+ row.businessNo
									+ '</a>';
							break;
						case 'sell' :
							return '<a href="#" onclick="javascript:window.open(\'?model=asset_disposal_sell&action=init&perm=view&id='
									+ row.businessId
									+ '\')">'
									+ row.businessNo
									+ '</a>';
							break;
						default :
							break;
					}
				}
			}
		}, {
			display : '�ʲ����',
			name : 'assetType',
			sortable : true
		}, {

			display : '�ʲ�id',
			name : 'assetId',
			sortable : true,
			hide : true
		}, {
			display : '��Ƭ���',
			name : 'assetCode',
			sortable : true,
			width : 260,
			process : function(v, row) {
				return '<a href="#" onclick="javascript:window.open(\'?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.assetId + '\')">' + row.assetCode + '</a>';
			}
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			sortable : true
		}, {
			display : 'ҵ������',
			name : 'businessType',
			sortable : true,
			process : function(val) {
				if (val == "sell") {
					return "���۵��ʲ�";
				}
				if (val == "scrap") {
					return "���ϵ��ʲ�";
				}
			}
		}, {
			display : 'ҵ��id',
			name : 'businessId',
			sortable : true,
			hide : true
		}, {
			display : '��������',
			name : 'cleanDate',
			sortable : true
		}, {
			display : '�������',
			name : 'cleanFee',
			sortable : true,
			//�б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '��ֵ����',
			name : 'salvageFee',
			sortable : true,
			//�б��ʽ��ǧ��λ
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '����״̬',
			name : 'ExaStatus',
			sortable : true
		}, {
			display : '����ʱ��',
			name : 'ExaDT',
			sortable : true
		}, {
			display : '�ڼ�',
			name : 'period',
			sortable : true,
			hide : true
		}, {
			display : '���',
			name : 'years',
			sortable : true,
			hide : true
		}, {
			display : 'ժҪ',
			name : 'explanation',
			sortable : true
		}],

		// ��չ�Ҽ��˵�
		menusEx : [{
			name : 'aduit',
			text : '�鿴������Ϣ',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin('?model=asset_assetcard_clean&action=init&perm=view&id='
									+ row.id
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=900');
				}
			}
		},{
			name : 'aduit',
			text : '�鿴�ʲ���Ƭ',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					window
							.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
									+ row.assetId
									+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				}
			}
		},{
			text : '�ύ����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���ύ" || row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
						showThickboxWin('controller/asset/assetcard/ewf_index_clean.php?actTo=ewfSelect&billId='
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'cancel',
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_asset_clean'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('�����Ѿ�����������Ϣ�����ܳ���������');
						    	show_page();
								return false;
							}else{
								if(confirm('ȷ��Ҫ����������')){
									$.ajax({
									    type: "GET",
									    url: "controller/asset/assetcard/ewf_index_clean.php?actTo=delWork&billId=",
									    data: {"billId" : row.id },
									    async: false,
									    success: function(data){
									    	alert(data)
									    	show_page();
										}
									});
								}
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			    showMenuFn:function(row){
			    	if((row.ExaStatus=="���"||row.ExaStatus=="���"|| row.ExaStatus == "��������")){
			    	   return true;
			    	}
			    	return false;
			    },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_clean&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "���" || row.ExaStatus == "���ύ"|| row.ExaStatus == "���")) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {
					$.ajax({
						type : "POST",
						url : "?model=asset_assetcard_clean&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#datadictList").yxgrid("reload");
						}
					});
				}
			}
		}],

		searchitems : [{
			display : 'ԭ�����',
			name : 'businessNo'
		}, {
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}],
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder : "DESC"

	});
});
