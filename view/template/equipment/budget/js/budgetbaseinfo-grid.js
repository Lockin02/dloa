var show_page = function(page) {
	$("#budgetbaseinfoGrid").yxgrid("reload");
};
$(function() {
	var flag = $("#flag").val();
	if (flag == "all") {
		var flagTF = true;
	} else {
		var flagTF = false;
	}
	$("#budgetTypeTree").yxtree({
		url : '?model=equipment_budget_budgetType&action=getTreeData',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var goodsbaseinfoGrid = $("#budgetbaseinfoGrid").data('yxgrid');
				goodsbaseinfoGrid.options.param['budgetTypeId'] = treeNode.id;
				$("#parentName").val(treeNode.name);
				$("#parentId").val(treeNode.id);
				goodsbaseinfoGrid.reload();
			}
		}
	});
	buttonsArr = [{
		name : 'add',
		text : "�豸����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=equipment_budget_budgetbaseinfo&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	},{
		name : 'add',
		text : "����",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";

			var budgetTypeId = $("#budgetbaseinfoGrid").data('yxgrid').options.param.budgetTypeId;

			for (var t in $("#budgetbaseinfoGrid").data('yxgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#budgetbaseinfoGrid").data('yxgrid').options.searchParam[t];
				}
			}
			var i = 1;
			var colId = "";
			var colName = "";
			$("#budgetbaseinfoGrid_hTable").children("thead").children("tr")
					.children("th").each(function() {
						if ($(this).css("display") != "none"
								&& $(this).attr("colId") != undefined) {
							colName += $(this).children("div").html() + ",";
							colId += $(this).attr("colId") + ",";
							i++;
						}
					})
			var searchSql = $("#budgetbaseinfoGrid").data('yxgrid').getAdvSql()
			var searchArr = [];
			searchArr[0] = searchSql;
			searchArr[1] = searchConditionKey;
			searchArr[2] = searchConditionVal;
			window.open("?model=equipment_budget_budgetbaseinfo&action=exportExcel&colId="
							+ colId
							+ "&colName="
							+ colName
							+ "&budgetTypeId="
							+ budgetTypeId
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal="
							+ searchConditionVal)
		}
	},{
		name : 'delete',
		text : "�������",
		icon : 'delete',
		action : function(row) {
				if (window.confirm("ȷ��Ҫ���������?")) {
					if (window.confirm("��������ݽ��޷��ָ����ٴ�ȷ��Ҫ�����")){
						  $.ajax({
							type : "POST",
							url : "?model=equipment_budget_budgetbaseinfo&action=ajaxEmptyData",
							data : {},
							success : function(msg) {
								if (msg == 1) {
									show_page();
									alert('�����ɹ���');
								} else {
									alert('����ʧ��!');
								}
							}
						});
					}
				}
			}
	}],
	$("#budgetbaseinfoGrid").yxgrid({
		model : 'equipment_budget_budgetbaseinfo',
		param : {
			goodsTypeId : -1
		},
		showcheckbox : false,
		isDelAction : false,
		isEditAction : flagTF,
		isAddAction : flagTF,
		title : '�豸����',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'budgetTypeName',
			display : '��������',
			sortable : true,
			width : 100
		}, {
			name : 'equCode',
			display : '���ϱ��',
			sortable : true,
			width : 100
		}, {
			name : 'equName',
			display : '��������',
			sortable : true,
			width : 100
		}, {
			name : 'pattern',
			display : '����ͺ�',
			sortable : true,
			width : 100
		}, {
			name : 'brand',
			display : 'Ʒ��',
			sortable : true,
			width : 60
		}, {
			name : 'quotedPrice',
			display : '����',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'useEndDate',
			display : '������Ч��',
			sortable : true
		}, {
			name : 'unitName',
			display : '��λ',
			sortable : true
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 200
		}, {
			name : 'latestPrice',
			display : '���²ɹ��۸�',
			sortable : true,
			width : 80,
			process : function(v){
			   return v;
			}
		}, {
			name : 'useStatus',
			display : '�Ƿ�����',
			sortable : true,
			process:function(v){
			   if(v == '0' || v == ''){
			      return "�ر�";
			   }else if(v == '1'){
			      return "����";
			   }
			}
		}],
		menusEx : [
//		{
//			text : '���ñ༭',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if (flag == "all") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				showModalWin("?model=equipment_budget_deploy&action=toEditConfig&equId="
//						+ row.id
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=550&width=1150");
//			}
//		}, {
//			text : '����Ԥ��',
//			icon : 'view',
//			action : function(row, rows, grid) {
//
//				showModalWin("?model=equipment_budget_deploy&action=toViewConfig&equId="
//						+ row.id
//						// + "&skey="
//						// + row['skey_']
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
//			}
//		}, {
//			text : '��дԤ���',
//			icon : 'add',
//			action : function(row, rows, grid) {
//				showModalWin("?model=equipment_budget_budget&action=toAdd&equId="
//						+ row.id
//						// + "&skey="
//						// + row['skey_']
//						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
//			}
//		},
		{
			text : '����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.useStatus == "0" || row.useStatus == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("ȷ������?")) {
					$.ajax({
						type : "POST",
						url : "?model=equipment_budget_budgetbaseinfo&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '1'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('���óɹ���');
							} else {
								alert('����ʧ��!');
							}
						}
					});
				}
			}
		}, {
			text : 'ͣ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.useStatus == "0" || row.useStatus == "") {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (window.confirm("ȷ��ͣ��?")) {
					$.ajax({
						type : "POST",
						url : "?model=equipment_budget_budgetbaseinfo&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '0'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('�����ɹ���');
							} else {
								alert('����ʧ��!');
							}
						}
					});
				}
			}
		}, {
			name : 'view',
			text : "������־",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_equ_budget_baseinfo"
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}
//		, {
//			text : 'ɾ��',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (flag == "all") {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (window.confirm("ȷ��Ҫɾ��?")) {
//					$.ajax({
//						type : "POST",
//						url : "?model=equipment_budget_budgetbaseinfo&action=ajaxdeletes",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								show_page();
//								alert('ɾ���ɹ���');
//							} else {
//								alert('ɾ��ʧ��!');
//							}
//						}
//					});
//				}
//			}
//		}
		],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=equipment_budget_budgetbaseinfo&action=toAdd&parentName="
						+ $("#parentName").val()
						+ "&parentId="
						+ $("#parentId").val()

						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=900");
			}
		},
		buttonsEx : buttonsArr,
		toEditConfig : {
			action : 'toEdit',
			formHeight : 400,
			formWidth : 750
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 400,
			formWidth : 750
		},
		searchitems : [{
			display : "��������",
			name : 'budgetTypeName'
		}, {
			display : "��������",
			name : 'equName'
		}, {
			display : "���ϱ��",
			name : 'equCode'
		}]
	});
});