var show_page = function(page) {
	// $("#proTypeTree").yxtree("reload");
	$("#assetGrid").yxgrid("reload");
};

$(function() {
	var showcheckbox = $("#showcheckbox").val();
	var showButton = $("#showButton").val();

	var textArr = [];
	var valArr = [];
	var indexArr = [];
	var combogrid = window.dialogArguments[0];
	var opener = window.dialogArguments[1];
	var p = combogrid.options;
	var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
	var titleVal = "<b>��Ƭ��ѡ  : ��˫����Ҫѡ��Ŀ�Ƭ&nbsp;&nbsp;&nbsp;</b>";

	if (!showcheckbox) { // ����ǵ�ѡ���������ı���
		if (eventStr.row_dblclick) {
			var dbclickFunLast = eventStr.row_dblclick;
			eventStr.row_dblclick = function(e, row, data) {
				var isReturn = dbclickFunLast(e, row, data);
				if (isReturn !== false) {
					window.returnValue = row.data('data');
					window.close();
				}
			};
		} else {
			eventStr.row_dblclick = function(e, row, data) {
				window.close();
			};
		}

	} else {// ��ѡ
		titleVal = "<b>��Ƭ��ѡ  : �빴ѡ��Ҫѡ��Ŀ�Ƭ&nbsp;&nbsp;&nbsp;</b>";
		var rowCheckFunLast = function() {
		};
		if (eventStr.row_check) {
			rowCheckFunLast = eventStr.row_check;
		}
		eventStr.row_check = function(e, checkbox, row, rowData) {
			var el = combogrid.el;
			if (p.hiddenId) {
				if (checkbox.attr('checked')) {
					if (p.idArr.indexOf(rowData[p.valueCol]) == -1) {
						p.idArr.push(rowData[p.valueCol]);
						p.nameArr.push(rowData[p.nameCol]);
					}
				} else {
					// ���ֵ���ڣ�ɾ��������
					var index = p.idArr.indexOf(rowData[p.valueCol]);
					if (index != -1) {
						p.idArr.splice(index, 1);
						p.nameArr.splice(index, 1);
					}
				}
				p.nameStr = p.nameArr.toString();
				p.idStr = p.idArr.toString();
				if (p.isShowName == true) {
					opener.$("#" + el.attr('id')).val(p.nameStr);
				}
				opener.$("#" + p.hiddenId).val(p.idStr);
				// ѡ���ٷ��¼�
				row.trigger('after_row_check', [checkbox, row, rowData]);
			}
			rowCheckFunLast(e, checkbox, row, rowData);
		}
		var checkIds = $("#checkIds").val();
		eventStr.row_success = function(e, rows, g) {
			for (var i = 0, l = rows.size(); i < l; i++) {
				var rowData = $(rows[i]).data('data');
				var v = rowData[combogrid.options.valueCol];
				if (v) {
					if (("," + checkIds + ",").indexOf("," + v + ",") != -1) {
						var checkbox = g.getCheckboxByRow(rows[i]);
						checkbox.trigger('click', [true]);
					}
				}
			}
		}
	}

	var gridOptions = combogrid.options.gridOptions;
	$("#assetGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		action : gridOptions.action,
		title : titleVal,
		isToolBar : true,
		isViewAction : false,
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : showcheckbox,
		param : gridOptions.param,
		pageSize : 40,
		imSearch : true,// ��ʱ����
		colModel : [{
					name : 'property',
					display : '�ʲ�����',
					width : '70',
					process : function(v){
						if(v==0){
							return '�̶��ʲ�'
						}else if(v==1){
							return '��ֵ����Ʒ'
						}
					},
					sortable : true
				}, {
					name : 'assetTypeName',
					display : '�ʲ����',
					width : '70',
					sortable : true
				}, {
					name : 'id',
					display : '�ʲ�Id',
					hide : true,
					sortable : true
				}, {
					name : 'assetCode',
					display : '��Ƭ���',
					width : '160',
					sortable : true
				}, {
					name : 'machineCode',
					display : '������',
					width : '100',
					sortable : true
				}, {
					name : 'assetName',
					display : '�ʲ�����',
					width : '120',
					sortable : true
				}, {
					name : 'useStatusCode',
					display : 'ʹ��״̬����',
					hide : true,
					sortable : true
				}, {
					name : 'useStatusName',
					display : 'ʹ��״̬',
					width : '70',
					sortable : true
				}, {
					name : 'spec',
					display : '����ͺ�',
					sortable : true
				}, {
					name : 'unit',
					display : '������λ',
					hide : true,
					sortable : true
				}, {
					name : 'account',
					display : '����ԭֵ',
					hide : true,
					sortable : true
				}, {
					name : 'buyDate',
					display : '��������',
					sortable : true
				}, {
					name : 'orgId',
					display : '��������id',
					hide : true,
					sortable : true
				}, {
					name : 'orgName',
					display : '��������',
					sortable : true
				},{
					name : 'deploy',
					display : '����',
					sortable : true
				}],
		menusEx : [{
			name : 'view',
			text : "�鿴",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=init&perm=view&id="
						+ row.id
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],
//		buttonsEx : [{
//			text : 'ȷ�����',
//			icon : 'add',
//			action : function(row, rows, idArr) {
//				if(row){
//					if(window.opener){
//						if(showCheckBox==true){
//							confirmAudit();
//							window.opener.setDates(rows);
//						}else{
//							confirmAudit();
//							window.opener.setDates(row);
//						}
//					}
//					window.close();
//				}else{
//					alert('��ѡ��һ�����ݣ�');
//				}
//			}
//		}],

		searchitems : [{
					display : '�ʲ�����',
					name : 'assetName'
				}, {
					display : '��Ƭ���',
					name : 'assetCode'
				}],
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// ���¼����ƹ���
		event : eventStr

	});
});