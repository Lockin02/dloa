$(function() {
	disDT();
})
function disDTreload() {
	disDT();
	var chanceGrid = $("#chanceListGrid").data('yxsubgrid');
	chanceGrid.options.extParam['timingDate'] = $("#hideDT").val();
	chanceGrid.reload();
}
var show_page = function(page) {
	$("#chanceListGrid").yxsubgrid("reload");
};
//��������
function disDT() {
	//���������ڣ��������С�� 15�ţ������ڶ�Ϊ�ϸ���30�ţ�������ڴ���15�ţ������ڶ�Ϊ����15��
	var timingDT = $("#timingDT").val();
	var dates = timingDT.split("-");
	var fTime = new Date(dates[0], dates[1], dates[2]);
	//	var fTime = new Date(timingDT);
	year = fTime.getFullYear();
	month = (fTime.getMonth());
	day = (fTime.getDate());
	if (day < 15) {
		if (month == '1') {
			var newDate = (year - 1) + "-12-30";
		} else {
			if ((month - 1) == "2") {
				D = new Date(year, (month - 1), 0);
				var listDay = D.getDate();
				var newDate = year + "-" + (month - 1) + "-" + listDay;
			} else {
				var newDate = year + "-" + (month - 1) + "-" + "30";
			}
		}
	} else {
		var newDate = year + "-" + month + "-15";
	}
	$("#hideDT").val(newDate);
}

$(function() {
	buttonsArr = [],
	SJDC = {
		name : 'export',
		text : "����",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";
			for (var t in $("#chanceListGrid").data('yxsubgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#chanceListGrid")
							.data('yxsubgrid').options.searchParam[t];
				}
			}
			var status = $("#status").val();
			var chanceType = $("#chanceType").val();
			var chanceLevel = $("#chanceLevel").val();
			var winRate = $("#winRate").val();
			var chanceStage = $("#chanceStage").val();
			var timingDate = $("#hideDT").val();
			var i = 1;
			var colId = "";
			var colName = "";
			$("#chanceListGrid_hTable").children("thead").children("tr")
					.children("th").each(function() {
						if ($(this).css("display") != "none"
								&& $(this).attr("colId") != undefined
								&& $(this).children("div").text() != "+") {

							colName += $(this).children("div").html() + ",";
							colId += $(this).attr("colId") + ",";
							i++;
						}
					})
			window
					.open("?model=projectmanagent_chance_chance&action=historyChanceExcel&colId="
							+ colId
							+ "&colName="
							+ colName
							+ "&status="
							+ status
							+ "&chanceType="
							+ chanceType
							+ "&chanceLevel="
							+ chanceLevel
							+ "&winRate="
							+ winRate
							+ "&chanceStage="
							+ chanceStage
							+ "&timingDate="
							+ timingDate
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal="
							+ searchConditionVal
							+ "&1width=200,height=200,top=200,left=200,resizable=yes")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_chance_chance&action=getLimits',
		data : {
			'limitName' : '�̻���Ϣ�б���'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(SJDC);
			}
		}
	});

	$("#chanceListGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceListJson',
		param : {
			'timingDate' : $("#hideDT").val()
		},
		// event : {
		// 'row_dblclick' : function(e, row, data) {
		// showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
		// + data.id + "&skey="+row['skey_']
		// +
		// "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
		// );
		// }
		// },
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'oldId',
			name : 'oldId',
			sortable : true,
			hide : true
		}, {
			name : 'timingDate',
			display : '��ʱ����ʱ��',
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'newUpdateDate',
			display : '�������ʱ��',
			sortable : true
		}, {
			name : 'chanceCode',
			display : '�̻����',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
						+ row.oldId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'chanceName',
			display : '��Ŀ����',
			sortable : true
		}, {
			name : 'chanceMoney',
			display : '��Ŀ�ܶ�',
			sortable : true,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}, {
			name : 'chanceType',
			display : '��Ŀ����',
			datacode : 'HTLX',
			sortable : true
		}, {
			name : 'winRate',
			display : '�̻�Ӯ�����ŵ(%)',
			datacode : 'SJYL',
			sortable : true
		}, {
			name : 'chanceStage',
			display : '��Ŀ��չ�׶�',
			datacode : 'SJJD',
			sortable : true
		}, {
			name : 'predictContractDate',
			display : 'Ԥ�ƺ�ͬǩ������',
			sortable : true
		}, {
			name : 'predictExeDate',
			display : 'Ԥ�ƺ�ִͬ������',
			sortable : true
		}, {
			name : 'contractPeriod',
			display : '��ִͬ�����ڣ��£�',
			sortable : true
		}, {
			name : 'contractTurnDate',
			display : 'ת��ͬ����',
			sortable : true
		}, {
			name : 'rObjCode',
			display : 'oaҵ����',
			sortable : true
		}, {
			name : 'contractCode',
			display : '��ͬ��',
			sortable : true
		}, {
			name : 'progress',
			display : '��Ŀ��չ����',
			sortable : true
		}, {
			name : 'Province',
			display : '����ʡ',
			sortable : true
		}, {
			name : 'City',
			display : '������',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '��������',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '�̻�������',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '�̻�������',
			sortable : true
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			datacode : 'KHLX',
			sortable : true
		}, {
			name : 'status',
			display : '�̻�״̬',
			process : function(v) {
				if (v == 0) {
					return "������";
				} else if (v == 3) {
					return "�ر�";
				} else if (v == 4) {
					return "�����ɺ�ͬ";
				} else if (v == 5) {
					return "������"
				} else if (v == 6) {
					return "��ͣ"
				}
			},
			sortable : true
		}],
		//		buttonsEx : [{
		//			name : 'export',
		//			text : "����",
		//			icon : 'excel',
		//			action : function(row) {
		//				var status = $("#status").val();
		//				var chanceType = $("#chanceType").val();
		//				var i = 1;
		//				var colId = "";
		//				var colName = "";
		//				$("#chanceListGrid_hTable").children("thead").children("tr")
		//						.children("th").each(function() {
		//							if ($(this).css("display") != "none"
		//									&& $(this).attr("colId") != undefined) {
		//								colName += $(this).children("div").html() + ",";
		//								colId += $(this).attr("colId") + ",";
		//								i++;
		//							}
		//						})
		//				window
		//						.open("?model=projectmanagent_chance_chance&action=exportExcel&colId="
		//								+ colId
		//								+ "&colName="
		//								+ colName
		//								+ "&status="
		//								+ status
		//								+ "&chanceType="
		//								+ chanceType
		//								+ "&1width=200,height=200,top=200,left=200,resizable=yes")
		//			}
		//		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_chance_goods&action=timingPageJson&timingDate='
					+ $("#hideDT").val(),// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'chanceId',// ���ݸ���̨�Ĳ�������
				colId : 'oldId'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
				name : 'goodsName',
				width : 200,
				display : '��Ʒ����'
			}, {
				name : 'number',
				display : '����',
				width : 80
			}, {
				name : 'money',
				display : '���',
				width : 80
			}]
		},
		buttonsEx : buttonsArr,
		comboEx : [{
			text : '�̻�����',
			key : 'chanceType',
			datacode : 'SJLX'
		}, {
			text : '�̻�״̬',
			key : 'status',
			value : '5',
			data : [{
				text : '������',
				value : '5'
			}, {
				text : '��ͣ',
				value : '6'
			}, {
				text : '�ر�',
				value : '3'
			}, {
				text : '�����ɺ�ͬ',
				value : '4'
			}]
		}, {
			text : '�̻�ӯ��',
			key : 'winRate',
			datacode : 'SJYL'

		//}, {
		//	text : '�̻��׶�',
		//	key : 'chanceStage',
		//	datacode : 'SJJD'
		}],
		//��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			}

		}],
		// ��������
		searchitems : [{
			display : '�̻����',
			name : 'chanceCode'
		}, {
			display : '�̻�����',
			name : 'chanceName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : '��Ʒ����',
			name : 'goodsName'
		}],
		// Ĭ������˳��
		sortorder : "DSC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});