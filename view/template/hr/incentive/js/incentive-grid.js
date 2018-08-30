var show_page = function(page) {
	$("#incentiveGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [
//        {
//			name : 'view',
//			text : "�߼���ѯ",
//			icon : 'view',
//			action : function() {
//				alert('������δ�������');
//				showThickboxWin("?model=finance_payablesapply_payablesapply&action=toSearch&"
//					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
//			}
//        }
    ];

	//��ͷ��ť����
	excelInArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_incentive_incentive&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_personnel&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelInArr);
			}
		}
	});

	excelOutSelect = {
		name : 'excelOutAllArr',
		text : "�Զ��嵼����Ϣ",
		icon : 'excel',
		action : function() {
			if ($("#totalSize").val() < 1) {
				alert("û�пɵ����ļ�¼");
			} else {
				document.getElementById("form2").submit();
			}
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_education&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data = 1) {
				buttonsArr.push(excelOutSelect);
			}
		}
	});
	$("#incentiveGrid").yxgrid({
		model : 'hr_incentive_incentive',
		title : '���͹���',
		bodyAlign:'center',
		isOpButton:false,
		bodyAlign:'center',
		event : {
			'afterload' : function(data, g) {
				$("#listSql").val(g.listSql);
				$("#totalSize").val(g.totalSize);
			}
		},
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true,
				width:70
			},  {
				name : 'userName',
				display : 'Ա������',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_incentive_incentive&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				},
				width : 60
			}, {
				name : 'deptName',
				display : '��������',
				sortable : true,
				hide : true
			},  {
				name : 'incentiveTypeName',
				display : '��������',
				sortable : true,
				width : 60
			}, {
				name : 'reason',
				display : '����ԭ��',
				sortable : true,
				width : 130
			}, {
				name : 'incentiveDate',
				display : '��������',
				sortable : true,
				width : 75
			}, {
				name : 'grantUnitName',
				display : '���赥λ',
				sortable : true,
				width : 100
			}, {
				name : 'rewardPeriod',
				display : '�����·�',
				sortable : true,
				width : 70
			}, {
				name : 'incentiveMoney',
				display : '���ͽ��',
				sortable : true,
				process : function(v){
					if(v < 0){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width : 80
			}, {
				name : 'description',
				display : '����˵��',
				sortable : true
			}, {
				name : 'recordDate',
				display : '��¼����',
				sortable : true,
				width : 75
			}, {
				name : 'recorderName',
				display : '��¼��',
				sortable : true,
				width : 60
			},  {
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				width : 130
			}],
		lockCol:['userNo','userName','incentiveTypeName'],//����������
		buttonsEx : buttonsArr,
		toAddConfig : {
			formWith : 800,
			formHeight : 500
		},
		toEditConfig : {
			action : 'toEdit',
			formWith : 800,
			formHeight : 500
		},
		toViewConfig : {
			action : 'toView',
			formWith : 800,
			formHeight : 500
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			}
		},
		//��������
		comboEx : [{
			text : '��������',
			key : 'incentiveType',
			datacode : 'HRJLSS'
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : 'Ա�����',
			name : 'userNoSearch'
		},{
			display : 'Ա������',
			name : 'userNameSearch'
		}, {
			display : '���赥λ',
			name : 'grantUnitName'
		}, {
			display : '��������',
			name : 'incentiveDateSearch'
		},{
			display : '����ԭ��',
			name : 'reason'
		}, {
			display : '����˵��',
			name : 'description'
		},{
			name : 'recordDateSearch',
			display : '��¼����'
		}, {
			display : '��¼��',
			name : 'recorderName'
		}]
	});
});