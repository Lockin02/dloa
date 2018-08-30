// ��������/�޸ĺ�ص�ˢ�±��

var show_page = function(page) {
	$("#rentGrid").yxgrid('reload');
};

$(function() {

	$("#rentGrid").yxgrid({

		model : 'asset_daily_rent',
		title : '�̶��ʲ�������Ϣ',
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '�����',
			name : 'billNo',
			sortable : true,
			width : 120
		}, {
			display : '������ⲿ��id',
			name : 'deptId',
			sortable : true,
			hide : true

		}, {
			display : '������ⲿ��',
			name : 'deptName',
			sortable : true
		}, {
			display : '���ⵥλid',
			name : 'lesseeid',
			sortable : true,
			hide : true
		}, {
			display : '���ⵥλ',
			name : 'lesseeName',
			sortable : true

		}, {
			display : '���⸺����',
			name : 'lessee',
			sortable : true
		}, {
			display : '���޺�ͬ���',
			name : 'contractNo',
			sortable : true,
			hide : true
		}, {
			display : '��������',
			name : 'rentNum',
			sortable : true,
			hide : true
		},

		{
			display : '�����ܶ�',
			name : 'rentAmount',
			sortable : true
		}, {
			display : '����ԭ��',
			name : 'reason',
			sortable : true
		}, {
			display : '������id',
			name : 'applicatId',
			sortable : true,
			hide : true
		}, {
			display : '������',
			name : 'applicat',
			sortable : true
		}, {
			display : '��������',
			name : 'applicatDate',
			sortable : true
		}, {
			display : '��������',
			name : 'beginDate',
			sortable : true
		}, {
			display : '��������',
			name : 'endDate',
			sortable : true
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true,
			hide : true
		}, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true
		}, {
			name : 'ExaDT',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '������id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '��������',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '¼����',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '�޸���id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '�޸�����',
			sortable : true,
			hide : true
		}],
		//						toDelConfig : {
		//								showMenuFn : function(row) {
		//										if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
		//											return true;
		//												}
		//									return false;
		//									}
		//							},
		isDelAction : false,
		isViewAction : false,
		isEditAction : false,
		toAddConfig : {
			formWidth : 1000,
			/**
			 * ������Ĭ�ϸ߶�
			 */
			formHeight : 450
		},

		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (window.confirm(("ȷ��ɾ����"))) {

					$.ajax({
						type : "GET",
						url : "?model=asset_daily_rent&action=deletes&id="
								+ row.id,
						success : function(msg) {
							$("#rentGrid").yxgrid("reload");
						}
					});
				}
			}
		}, {
			name : 'view',
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_rent&action=init&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=1000");
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			name : 'edit',
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_daily_rent&action=init&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&&width=1000");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'sumbit',
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {

					showThickboxWin("controller/asset/daily/ewf_index_rent.php?actTo=ewfSelect&billId="
							+ row.id
							+ "&examCode=oa_asset_rent&formName=�ʲ���������"
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=780");

				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���'
						|| row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_asset_rent&pid="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=900");
				}
			}
		}

		//					            ,{
		//									text : 'ǩ��',
		//									icon : 'edit',
		//									showMenuFn : function(row) {
		//										if (row.ExaStatus == "���" && row.isSign=="δǩ��" ) {
		//											return true;
		//										}else {
		//										    return false;
		//										}
		//
		//									},
		//									action: function(row){
		//										if (window.confirm(("ȷ��ǩ����"))) {
		//
		//							                 	$.ajax({
		//													type : "POST",
		//													url : "?model=asset_daily_allocation&action=sign&id="+ row.id,
		//													success : function(msg) {
		//															if( msg == 1 ){
		//																alert('ǩ�ճɹ���');
		//							                               	 $("#allocationGrid").yxgrid("reload");
		//															}else{
		//																alert('ǩ��ʧ�ܣ�');
		//															}
		//													}
		//												});
		//							                 }
		//										}
		//
		//							}
		],

		// ��������
		searchitems : [{
			display : '�����',
			name : 'billNo'
		}, {
			display : '������ⲿ��',
			name : 'deptName'
		}, {
			display : '���ⵥλ',
			name : 'lesseeName'
		}],
		// Ĭ�������ֶ���

		sortname : "id",
		// Ĭ������˳��

		sortorder : "DESC"

	});

});