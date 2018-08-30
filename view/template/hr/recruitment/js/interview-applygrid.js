var show_page = function(page) {
	$("#interviewGrid").yxgrid("reload");
};
$(function() {


	$("#interviewGrid").yxgrid({
		model : 'hr_recruitment_interview',
		title : '���Լ�¼',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : false,
		isOpButton:false,
		param : {
			parentId : $("#applyid").val(),
			interviewType : $("#interviewtype").val()
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
			}, {
				name : 'ExaStatus',
				display : '���״̬',
				sortable : true
			}, {
				name : 'deptName',
				display : '���˲���',
				sortable : true
			}, {
				name : 'hrRequire',
				display : '��Ƹ����',
				sortable : true
			}, {
				name : 'useInterviewResult',
				display : '���Խ��',
				sortable : true,
				process : function(v){
					if(v==0)
						return "�����˲�";
					else
						return "����¼��";
				}
			}, {
				name : 'hrSourceType1Name',
				display : '������Դ����',
				sortable : true
			}, {
				name : 'hrSourceType2Name',
				display : '������ԴС��',
				sortable : true
			}],
		lockCol:['formCode','formDate','userName'],//����������
		toEditConfig : {
			toEditFn : function(p, g) {
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
					showOpenWin("?model=hr_recruitment_interview&action=lastedit&id=" + + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
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
					showOpenWin("?model=hr_recruitment_interview&action=toView&id=" + rowData[p.keyField]
							+ keyUrl);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},
//		menusEx : [{
//			text : '������ְ֪ͨ',
//			icon : 'edit',
//			action : function(row) {
//					showOpenWin("?model=hr_recruitment_entryNotice&action=toadd&id="+row.id);
//			}
//
//		}],
		searchitems : [{
			display : '����',
			name : 'userNameSearch'
		}, {
			display : '���˲���',
			name : 'deptNamSearche'
		}]
	});
});