// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#myApplyGrid").yxsubgrid("reload");
};
$(function() {
	$("#myApplyGrid").yxsubgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_plan_basic',
		action : 'myListPageJson',
		title : '�ɹ������б�',
		isToolBar : false,
		showcheckbox : false,
		param : {
			'state' : '0'
		},

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ɹ�����',
					name : 'purchTypeCName',
					sortable : false
				}, {
					display : '�ɹ�������',
					name : 'planNumb',
					sortable : true,
					width : 120
				}, {
					display : '����״̬',
					name : 'ExaStatus',
					sortable : true
				}, {
					display : '����Դ���ݺ�',
					name : 'sourceNumb',
					sortable : true,
					width : 120
				}, {
					display : '���κ�',
					name : 'batchNumb',
					sortable : true,
					width : 120
				}, {
					display : '������',
					name : 'createName',
					sortable : true
				}, {
					display : '����ʱ�� ',
					name : 'sendTime',
					sortable : true,
					width : 80
				}, {
					display : 'ϣ�����ʱ�� ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}, {
					display : '����ԭ�� ',
					name : 'applyReason',
					sortable : true,
					width : 200
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJson',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productCategoryName',
						display : '�������',
						width : 50
					}, {
						name : 'productNumb',
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 200,
						display : '��������',
						process : function(v, data) {
							if (v == "") {
								return data.inputProductName;
							}
							return v;
						}
					},{
						name : 'amountAll',
						display : "��������",
						width : 70
					}, {
						name : 'amountAllOld',
						display : "ԭ��������",
						width : 70
					},{
						name : 'dateIssued',
						display : "��������"
					}, {
						name : 'dateHope',
						display : "ϣ���������"
					}, {
						name : 'isBack',
						display : "�Ƿ���",
						process : function(v, data) {
							return v == 1 ? "��" : "��";
						}
					}]
		},
		// ��չ��ť
		buttonsEx : [{
			name : 'export',
			text : '����',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=purchase_plan_basic&action=toExport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}, {
			name : 'export',
			text : '����ʱЧ��',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=purchase_plan_basic&action=toExportAging"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}, {
			name : 'export',
			text : '������������',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=purchase_plan_basic&action=toExportProduceEqu"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600");
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType + "&skey="
							+ row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "δ�ύ" || row.ExaStatus == "���"
						|| row.ExaStatus == "����ȷ�ϴ��") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=purchase_plan_basic&action=toEdit&id="
							+ row.id + "&purchType=" + row.purchType + "&skey="
							+ row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "δ�ύ" || row.ExaStatus == "���"
						|| row.ExaStatus == "����ȷ�ϴ��") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					// location =
					// 'controller/purchase/plan/ewf_index.php?actTo=ewfSelect&billId='
					// + row.id+'&purchType='+row.purchType
					// + '&examCode=oa_purch_plan_basic&formName=�ɹ���������';
					switch (row.purchType) {
						case 'assets' :
							location = 'controller/purchase/plan/ewf_index.php?actTo=ewfSelect&billId='
									+ row.id
									+ '&purchType='
									+ row.purchType
									+ '&examCode=oa_purch_plan_basic&formName=�ʲ��ɹ���������&billDept='+ row.departId;
							break;
						case 'rdproject' :
							location = 'controller/purchase/plan/ewf_rdproject_index.php?actTo=ewfSelect&billId='
									+ row.id
									+ '&examCode=oa_purch_plan_basic&formName=�з��ɹ���������';
							break;
						case 'produce' :
							location = 'controller/purchase/plan/ewf_produce_index.php?actTo=ewfSelect&billId='
									+ row.id
									+ '&examCode=oa_purch_plan_basic&formName=�����ɹ���������';
							break;
					}
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '��������',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "��������" ) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(confirm('ȷ��Ҫ����������')){
						switch (row.purchType) {
							case 'assets' :
								location = 'controller/purchase/plan/ewf_index.php?actTo=delWork&billId='
										+ row.id
										+ '&purchType='
										+ row.purchType
										+ '&examCode=oa_purch_plan_basic&formName=�ʲ��ɹ���������';
								break;
							case 'rdproject' :
								location = 'controller/purchase/plan/ewf_rdproject_index.php?actTo=delWork&billId='
										+ row.id
										+ '&examCode=oa_purch_plan_basic&formName=�з��ɹ���������';
								break;
							case 'produce' :
								location = 'controller/purchase/plan/ewf_produce_index.php?actTo=delWork&billId='
										+ row.id
										+ '&examCode=oa_purch_plan_basic&formName=�����ɹ���������';
								break;
						}
					}
				} else {
					alert("��ѡ��һ������");
				}
			}

		},{
			text : '���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=purchase_plan_basic&action=toChange&id="
							+ row.id + "&skey=" + row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == "δ�ύ"||row.ExaStatus == "���") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type : "POST",
							url : "?model=purchase_plan_basic&action=deletesInfo",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('ɾ���ɹ�!');
									show_page();
								}
							}
						});
					}
				}
			}
		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "���" || row.ExaStatus == "���")
						&& (row.purchType == "assets"
								|| row.purchType == "rdproject" || row.purchType == "produce")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_plan_basic&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}],
		// ��������
		searchitems : [{
					display : '�ɹ�������',
					name : 'seachPlanNumb'
				}, {
					display : '���ϱ��',
					name : 'productNumb'
				}, {
					display : '��������',
					name : 'productName'
				}, {
					display : '����Դ���ݺ�',
					name : 'sourceNumb'
				}, {
					display : '���κ�',
					name : 'batchNumb'
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "updateTime",
		// Ĭ������˳��
		sortorder : "DESC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});