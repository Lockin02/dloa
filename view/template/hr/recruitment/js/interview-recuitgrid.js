var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
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
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitment_interview&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_recruitment_interview&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});

	$("#interviewGrid").yxgrid({
		model : 'hr_recruitment_interview',
		title : '���Լ�¼',
		isDelAction : false,
		isAddAction : false,
		showcheckbox : false,
		param : {
			recuitid : $("#userid").val()
		},
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formCode',
				display : '���ݱ��',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_interview&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
				}
			}, {
				name : 'formDate',
				display : '��������',
				sortable : true
			}, {
				name : 'userName',
				display : '����',
				sortable : true
			}, {
				name : 'sexy',
				display : '�Ա�',
				sortable : true,
				width : 70
			}, {
				name : 'positionsName',
				display : 'ӦƸ��λ',
				sortable : true
			}, {
				name : 'deptState',
				display : '����ȷ��״̬',
				sortable : true,
				process : function(v){
					if(v==1)
						return "��ȷ��";
					else
					    return "δȷ��";
				}
			}, {
				name : 'hrState',
				display : '������Դȷ��״̬',
				sortable : true,
				process : function(v){
					if(v==1)
						return "��ȷ��";
					else
					    return "δȷ��";
				}
			}, {
				name : 'stateC',
				display : '״̬'
			}],
		toViewConfig : {
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					var keyUrl = "";
					if (rowData['skey_']) {
						keyUrl = "&skey=" + rowData['skey_'];
					}
					showOpenWin("?model=hr_recruitment_interview&action=recuitview&id=" + + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
		buttonsEx : buttonsArr,
		searchitems : [{
			display : '����',
			name : 'userNameSearch'
		}, {
			display : '���˲���',
			name : 'deptNamSearche'
		}]
	});
});