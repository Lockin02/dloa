var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};

$(function () {
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : 'Ա��ת������������',
		action : 'hrJson',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : true,
		param : {
			statusArr : "1,2,3,4,5,6,7,8,9"
		},
		isOpButton:false,
		bodyAlign:'center',
		buttonsEx : [ {
			name : 'expport',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_permanent_examine&action=toExport&docType=RKPURCHASE"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=650");
			}
		},{
			name : 'excelOut',
			text : "�����б�",
			icon : 'excel',
			action : function(row) {
				window.open("?model=hr_permanent_examine&action=excelOut"
					+ "&status=" + $("#status").val()
					+ "&ExaStatus=" + $("#ExaStatus").val()
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=40&width=60");
			}
		},{
			text:'�ʼ�����',
			icon:'add',
			action:function(row,rows,grid){
				rowsId = [];
				useNameArr = [];
				userAccountArr = [];
				if(row){
					for(var i = 0 ;i < rows.length ;i++) {
						rowsId.push(rows[i].id);
						useNameArr.push(rows[i].useName);
						userAccountArr.push(rows[i].userAccount);
					}
					showThickboxWin("?model=hr_permanent_examine&action=toMailMsg&id="
						+ rowsId
						+ "&userName=" + useNameArr
						+ "&userAccount=" + userAccountArr
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width : 70
			// process : function(v,row){
			// 	return "<a href='#' onclick='showOpenWin(\"?model=hr_personnel_personnel&action=toCodeView&userNo="+v+"\")'>"+v+"</a>"
			// }
		},{
			name : 'useName',
			display : '����',
			sortable : true,
			width : 60
		},{
			name : 'permanentMonth',
			display : 'ʵ��ת���·�',
			sortable : true,
			width : 70
		},{
			name : 'statusC',
			display : '״̬',
			width : 60
		},{
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_examine&action=toView&id="+row.id+"\")'>"+v+"</a>"
			},
			width : 130
		},{
			name : 'formDate',
			display : '��������',
			sortable : true,
			width : 80
		},{
			name : 'ExaStatus',
			display : '���״̬',
			sortable : true,
			width : 60
		},{
			name : 'isAgree',
			display : 'Ա��ͬ�����',
			sortable : true,
			width : 70,
			process : function(v ,row) {
				if(v == 0)
					return "δ��д";
				else if(v == 1) {
					return "ͬ��";
				}else if(v == 2) {
					return "��ͬ��" ;
				}
			}
		},{
			name : 'permanentType',
			display : 'ת������',
			sortable : true,
			width : 70
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width:30
		},{
			name : 'deptName',
			display : '����',
			sortable : true
		},{
			name : 'positionName',
			display : 'ְλ',
			sortable : true,
			width : 80
		},{
			name : 'begintime',
			display : '��ְʱ��',
			sortable : true,
			width : 80
		},{
			name : 'finishtime',
			display : '�ƻ�ת��ʱ��',
			sortable : true,
			width : 80
		},{
			name : 'permanentDate',
			display : 'ʵ��ת������',
			width : 80
		},{
			name : 'selfScore',
			display : '��������',
			sortable : true,
			width : 70
		},{
			name : 'totalScore',
			display : '��ʦ����',
			sortable : true,
			width : 70
		},{
			name : 'leaderScore',
			display : '�쵼����',
			sortable : true,
			width : 70
		},{
			name : 'interviewSalary',
			display : '����ȷ��ת������'
		},{
			name : 'suggestSalary',
			display : 'ת�����ʽ���н��'
		},{
			name : 'suggestJobName',
			display : '����ְλ',
			width : 80
		},{
			name : 'schemeName',
			display : '���˷���',
			width : 80,
			hide : true
		}],

		lockCol:['userNo','useName'],//����������

		toViewConfig : {
			showMenuFn : function(row) {
				if (row.formCode != "") {
					return true;
				} else {
					return false;
				}
			},
			toViewFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					if(rowData.formCode != ""){
						showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField]);
					}
				}
			}
		},

		menusEx : [{
			text : '��дhr����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 5 && row.ExaStatus == "δ���" ) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_permanent_examine&action=toEditSalary&id="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
			}
		},{
			text : '�ύ���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 5 && row.ExaStatus== "δ���" || row.ExaStatus == '���') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				location = "controller/hr/permanent/ewf_hr_index.php?actTo=ewfSelect&billId=" + row.id + "&examCode=oa_hr_permanent_examine&billDept="+row.deptId;
			}
		},{
			text : '�޸�н��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isAgree != 1 && row.ExaStatus== "���") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showOpenWin("?model=hr_permanent_examine&action=toDirectorSet&id=" + row.id);
			}
		},{
			text : '�ʼ�����',
			icon : 'add',
			action : function(row, rows, grid) {
				showThickboxWin("?model=hr_permanent_examine&action=toMailMsg&id="
					+ row.id
					+ "&userName=" + row.useName
					+ "&userAccount=" + row.userAccount
					+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
			}
		}],

		comboEx : [{
			text : '״̬',
			key : 'status',
			data : [{
				text : '��ʦ���',
				value : '2'
			},{
				text : '�쵼���',
				value : '3'
			},{
				text : '�������',
				value : '5'
			},{
				text : 'Ա��ȷ��',
				value : '6'
			},{
				text : '���',
				value : '7'
			},{
				text : '�ر�',
				value : '8'
			},{
				text : 'δ��д',
				value : '9'
			}]
		},{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : '��������',
				value : '��������'
			},{
				text : 'δ���',
				value : 'δ���'
			},{
				text : '���',
				value : '���'
			},{
				text : '���',
				value : '���'
			}]
		}],

		searchitems : [{
			display : "Ա�����",
			name : 'userNo'
		},{
			display : "����",
			name : 'useName'
		},{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : "��������",
			name : 'formCode'
		},{
			display : "Ա���Ƿ�ͬ��",
			name : 'isAgree'
		},{
			display : "ת������",
			name : 'permanentType'
		},{
			display : "�������",
			name : 'reformDT'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "����",
			name : 'deptName'
		},{
			display : "ְλ",
			name : 'positionName'
		},{
			display : "ת�����",
			name : 'finishYear'
		},{
			display : "ת���·�",
			name : 'finishMonth'
		},{
			display : "ʵ��ת���·�",
			name : 'permanentMonth'
		},{
			display : "����ְλ",
			name : 'suggestJobName'
		},{
			display : "���˷���",
			name : 'schemeName'
		}],

		// Ĭ�������ֶ���
		sortname : "statuss",

		// Ĭ������˳��
		sortorder : "ASC"
	});
});