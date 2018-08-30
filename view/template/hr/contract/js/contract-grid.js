var show_page = function(page) {
	$("#contractGrid").yxgrid("reload");
};
$(function() {
	//��ͷ��ť����
	buttonsArr = [];

	//��ͷ��ť����
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_contract_contract&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	excelUpdateArr = {
		name : 'exportIn',
		text : "�������",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_contract_contract&action=toExcelUpdate"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	excelOutSelect = {
		name : 'excelOutAllArr',
		text : "�Զ��嵼����Ϣ",
		icon : 'excel',
		action : function() {
			if($("#totalSize").val() < 1) {
				alert("û�пɵ����ļ�¼");
			}else{
				document.getElementById("form2").submit();
			}
		}
	}

	excelOutArr2 = {
		name : 'exportOut',
		text : "�߼���ѯ������",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_contract_contract&action=toExcelOut"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_contract_contract&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (1) {
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelUpdateArr);
				buttonsArr.push(excelOutArr2);
				buttonsArr.push(excelOutSelect);
			}
		}
	});

	$("#contractGrid").yxgrid({
		model : 'hr_contract_contract',
		title : '��ͬ��Ϣ',
		isAddAction : true,
		isEditAction : true,
		isOpButton : false,
		bodyAlign : 'center',
		event : {
			'afterload' : function(data,g) {
				$("#listSql").val(g.listSql);
				$("#totalSize").val(g.totalSize);
			}
		},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width:90
		},{
			name : 'userName',
			display : 'Ա������',
			sortable : true,
			width:65
		},{
			name : 'conNo',
			display : '��ͬ���',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showThickboxWin(\"?model=hr_contract_contract&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
			}
		},{
			name : 'conName',
			display : '��ͬ����',
			sortable : true
		},{
			name : 'conTypeName',
			display : '��ͬ����',
			sortable : true
		},{
			name : 'conStateName',
			display : '��ͬ״̬',
			sortable : true,
			width:65
		},{
			name : 'beginDate',
			display : '��ͬ��ʼʱ��',
			sortable : true
		},{
			name : 'closeDate',
			display : '��ͬ����ʱ��',
			sortable : true
		},{
			name : 'conNumName',
			display : '��ͬ����',
			sortable : true
		},{
			name : 'conContent',
			display : '��ͬ����',
			sortable : true
		},{
			name : 'fileExist',
			display : '�Ƿ��и���',
			process : function(row ,v) {
				if(v['files'] == 0) {
					return v = "��";
				} else {
					return v = "��";
				}
			},
			width:65
		}],

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toAddConfig : {
			action : 'toAdd',
			formWidth : '800',
			formHeight : '500'
		},
		toViewConfig : {
			action : 'toView'
		},

		// Ĭ�������ֶ���
		sortname : "userNo",
		// Ĭ������˳��
		sortorder : "asc",

		/**
		 * ��������
		 */
		searchitems : [{
			display : 'Ա������',
			name : 'userName'
		},{
			display : 'Ա�����',
			name : 'userNo'
		},{
			display : '��ͬ���',
			name : 'conNo'
		},{
			display : '��ͬ����',
			name : 'conName'
		},{
			display : '��ͬ����',
			name : 'conTypeName'
		},{
			display : '��ͬ״̬',
			name : 'conStateName'
		}]
	});
});