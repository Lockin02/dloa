var show_page = function(page) {
	$("#aidhandleGrid").yxgrid("reload");
};
$(function() {
	var handleType = $("#handleType").val();// ��������
	var param = {
		'dir' : 'ASC',
		'handleType' : handleType
	}
	$("#aidhandleGrid").yxgrid({
		model : 'contract_contract_contract',
		// action : 'conPageJson',
		showcheckbox : false,
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isViewAction : false,
		autoload : false,
		isOpButton : false,
		isEquSearch : false,

		param : param,
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'contractCode',
					display : '��ͬ���',
					sortable : true,
					width : 100
				}, {
					name : 'contractName',
					display : '��ͬ����',
					sortable : true,
					width : 100
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 100
				}, {
					name : 'prinvipalName',
					display : '��ͬ������',
					sortable : true,
					width : 80
				}, {
					name : 'areaPrincipal',
					display : '��������',
					sortable : true,
					width : 80
				}],
		// buttonsEx : [{
		// name : 'Add',
		// text : "ȷ��",
		// icon : 'add',
		// action : function(rowData, rows, rowIds, g) {
		// if (rows) {
		// showThickboxWin('?model=projectmanagent_borrow_borrow&action=tochooseCon&ids='
		// +rowIds
		// +
		// "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400");
		// // parent.window.returnValue = rows;
		// // $.showDump(outJson);
		// // parent.window.close();
		// } else {
		// alert('����ѡ���¼');
		// }
		// }
		// }],
		event : {
			'row_dblclick' : function(e, row, rowData) {
				if (rowData) {
					if (handleType == "FJSC") {
						showThickboxWin('?model=contract_contract_contract&action=handleDispose&id='
								+ rowData.id
								+ '&type=oa_contract_contract'
								+ '&handleType='
								+ handleType
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
					} else if (handleType == "GZSQ") {
						if (rowData.isNeedStamp == '1') {
							alert('�˺�ͬ���������,�����ظ�����');
							return false;
						}
						showThickboxWin('?model=contract_contract_contract&action=handleDispose&id='
								+ rowData.id
								+ '&handleType='
								+ handleType
								+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
					}else if (handleType == "YSWJ") {
						switch(rowData.type){
							case "oa_contract_contract":
								showThickboxWin('?model=contract_contract_contract&action=handleDispose&id=' + rowData.id
									+ '&type=oa_contract_contract'
									+ '&handleType=' + handleType
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
								break;
							case "oa_borrow_borrow":
								showThickboxWin('?model=projectmanagent_borrow_borrow&action=handleDispose&id=' + rowData.id
									+ '&type=oa_borrow_borrow'
									+ '&handleType=' + handleType
									+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
								break;
						}
					}
				} else {
					alert('����ѡ���¼');
				}
			}
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "��ͬ���",
					name : 'contractCodeAll'
				}, {
					display : "��ͬ����",
					name : 'contractNameAll'
				}]
	});
	// ��д����¼�
	var g = $("#aidhandleGrid").data("yxgrid");
	g.$clearBn.unbind();
	g.$clearBn.bind("click", function() {
				g.$inputText.val("");
				$(g.el).empty();
			});
	g.$searchBn.unbind();
	g.$searchBn.click(function() {
				if (g.$inputText.val() == "") {
					$(g.el).empty();
				} else {
					g.doSearch();
				}
			});

});
