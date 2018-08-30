var show_page = function(page) {
	$("#conprojectListGrid").yxgrid("reload");
};
$(function() {
	buttonsArr = [{
		text : "��ͬ��Ŀ����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=contract_conproject_conproject&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	}],
	LBDC = {
		name : 'export',
		text : "�б����ݵ���",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";
			for (var t in $("#conprojectListGrid").data('yxgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#conprojectListGrid")
							.data('yxgrid').options.searchParam[t];
				}
			}
			var i = 1;
			var colId = "";
			var colName = "";
			$("#conprojectListGrid_hTable").children("thead").children("tr")
					.children("th").each(function() {
						if ($(this).css("display") != "none"
								&& $(this).attr("colId") != undefined) {
							colName += $(this).children("div").html() + ",";
							colId += $(this).attr("colId") + ",";
							i++;
						}
					})
			var searchSql = $("#conprojectListGrid").data('yxgrid').getAdvSql();
			var searchArr = [];
			searchArr[0] = searchSql;
			searchArr[1] = searchConditionKey;
			searchArr[2] = searchConditionVal;

			window
					.open("?model=contract_conproject_conproject&action=exportExcel&colId="
							+ colId
							+ "&colName="
							+ colName
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal=" + searchConditionVal)
		}
	}
	$.ajax({
		type : 'POST',
		url : '?model=contract_conproject_conproject&action=getLimits',
		data : {
			'limitName' : '�б���'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(LBDC);
			}
		}
	});

	$("#conprojectListGrid").yxgrid({
		model : 'contract_conproject_conproject',
		action : 'conprojectJson',
		customCode : 'conprojectList',
		title : '��ͬ��Ŀ��',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			display : 'esmProjectId',
			name : 'esmProjectId',
			sortable : true,
			hide : true
		}, {
			name : 'contractId',
			display : '��ͬid',
			sortable : true,
			hide : true
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 120,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>"
						+ row.contractCode
						+ "</font>" + '</a>';
			}
		}, {
			name : 'contractMoney',
			display : '��ͬ���',
			sortable : true,
			hide : true
		}, {
			name : 'projectCode',
			display : '��Ŀ��',
			sortable : true,
			width : 150,
			process : function(v, row) {
				if(row.esmProjectId != ''){
					var skey = "";
				    $.ajax({
					    type: "POST",
					    url: "?model=engineering_project_esmproject&action=md5RowAjax",
					    data: { "id" : row.esmProjectId },
					    async: false,
					    success: function(data){
					   	   skey = data;
						}
					});
				    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=engineering_project_esmproject&action=viewTab&id='
						+ row.esmProjectId
						+'&skey='
						+ skey
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>"
						+ v
						+ "</font>" + '</a>';
				}else{
				    return v;

				}
			}
		}, {
			name : 'proLineCode',
			display : '��Ʒ�߱���',
			sortable : true,
			hide : true
		}, {
			name : 'proLineName',
			display : '��Ʒ������',
			sortable : true
		}, {
			name : 'proportion',
			display : 'ռ��',
			sortable : true,
			width : 50,
			process : function(v) {
				return v + "%";
			}
		}, {
			name : 'proMoney',
			display : '��ռ��ͬ��',
			sortable : true,
			width : 80,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'status',
			display : '��Ŀ״̬',
			sortable : true,
			width : 50,
			datacode : 'GCXMZT'
		}, {
			name : 'schedule',
			display : '��Ŀ����',
			sortable : true,
			process : function(v) {
				var v = formatProgress(v);
				return v;
			}
		}, {
			name : 'estimates',
			display : '����',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'exgross',
			display : 'Ԥ��ë����',
			sortable : true,
			process : function(v) {
				if(v)
				 return v + "%";
				else
				 return "-";
			}
		}, {
			name : 'budget',
			display : 'Ԥ��',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'cost',
			display : '����',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'earnings',
			display : '����',
			sortable : true,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'earningsTypeName',
			display : '����ȷ�Ϸ�ʽ',
			width : 80,
			sortable : true,
			hide : true
		}, {
			name : 'earningsTypeCode',
			display : '����ȷ�Ϸ�ʽ����',
			sortable : true,
			hide : true
		}, {
			name : 'planBeginDate',
			display : 'Ԥ�ƿ�ʼ����',
			sortable : true,
			hide : true
		}, {
			name : 'planEndDate',
			display : 'Ԥ�ƽ�������',
			sortable : true,
			hide : true
		}, {
			name : 'actBeginDate',
			display : 'ʵ�ʿ�ʼ����',
			sortable : true,
			hide : true
		}, {
			name : 'actEndDate',
			display : 'ʵ�ʽ�������',
			sortable : true,
			hide : true
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "��ͬ���",
			name : 'contractCode'
		}, {
			display : "��Ŀ���",
			name : 'projectCode'
		}, {
			display : "��Ʒ������",
			name : 'proLineName'
		}],
		menusEx : [{
			text : 'ȷ��������㷽ʽ',
			icon : 'add',
			action : function(row) {
				showThickboxWin('?model=contract_conproject_conproject&action=incomeAcc&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=600');
			}
		}],
		comboEx : [{
			text : '��Ʒ��',
			key : 'proLineCode',
			datacode : 'GCSCX'
		},{
			text : '��Ŀ״̬',
			key : 'status',
			datacode : 'GCXMZT'
		}],
		buttonsEx : buttonsArr
	});
});

//�����б������ʾ
function formatProgress(value) {
	if (value) {
		var s = '<div style="width:auto;height:auto;border:1px solid #ccc;padding: 0px;">'
				+ '<div style="width:'
				+ value
				+ '%;background:#66FF66;white-space:nowrap;padding: 0px;">'
				+ value + '%' + '</div>'
		'</div>';
		return s;
	} else {
		return '';
	}
}