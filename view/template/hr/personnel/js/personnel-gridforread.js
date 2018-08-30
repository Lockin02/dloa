var show_page = function(page) {
	$("#personnelGrid").yxgrid("reload");
};
// �鿴Ա������
function viewPersonnel(id, userNo, userAccount) {
	var skey = "";
	$.ajax({
		type : "POST",
		url : "?model=hr_personnel_personnel&action=md5RowAjax",
		data : {
			"id" : id
		},
		async : false,
		success : function(data) {
			skey = data;
		}
	});
	showModalWin("?model=hr_personnel_personnel&action=toTabView&id=" + id
			+ "&userNo=" + userNo + "&userAccount=" + userAccount + "&skey="
			+ skey, 'newwindow1', 'resizable=yes,scrollbars=yes');
}
$(function() {
	// ��ͷ��ť����
	buttonsArr = [{
		name : 'view',
		text : "�߼���ѯ",
		icon : 'view',
		action : function() {
			showThickboxWin("?model=hr_personnel_personnel&action=toSearch&"
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800');
		}
	},
        {
			name : 'excelOutAllArr',
			text : "����������Ϣ",
			icon : 'excel',
			action : function() {
				if($("#totalSize").val()<1){
					alert("û�пɵ����ļ�¼");
				}else{
					document.getElementById("form2").submit();
				}
			}
        },{
			name : 'excelOutOtherSelect',
			text : "����������Ϣ",
			icon : 'excel',
			action : function() {
				if($("#totalSize").val()<1){
					alert("û�пɵ����ļ�¼");
				}else{
					document.getElementById("form3").submit();
				}
			}
        }];
	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		title : '��Ա������Ϣ',
		action : 'pageJsonForRead',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton:false,
		bodyAlign:'center',
	    event:{'afterload':function(data,g){
	         $("#listSql").val(g.listSql);
          	 $("#totalSize").val(g.totalSize);
          	$("#otherListSql").val(g.listSql);
          	$("#otherTotalSize").val(g.totalSize);
	    }},
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
  			width:60,
			process : function(v, row) {
				return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
						+ row.id + "\",\"" + row.userNo + "\",\""
						+ row.userAccount + "\")' >" + v + "</a>";
			}
		}, {
			name : 'staffName',
			display : '����',
  			width:60,
			sortable : true,
			process : function(v, row) {
				return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
						+ row.id + "\",\"" + row.userNo + "\",\""
						+ row.userAccount + "\")' >" + v + "</a>";
			}
		}, {
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width : 60
		}, {
			name : 'companyType',
			display : '��˾����',
  			width:60,
			sortable : true
		}, {
			name : 'companyName',
			display : '��˾',
  			width:60,
			sortable : true
		},{
			name : 'belongDeptName',
  			display : '��������',
  			width:80,
  			hide : true,
  			sortable : true
        },
      	{
			name : 'deptName',
  			display : 'ֱ������',
  			width:80,
  			sortable : true
        },
        {
    		name : 'deptNameS',
  			display : '��������',
  			width:80,
  			sortable : true
        },{
    		name : 'deptNameT',
  			display : '��������',
  			width:80,
  			sortable : true
        },{
            name : 'deptNameF',
            display : '�ļ�����',
            width:80,
            sortable : true
        },{
			name : 'jobName',
			display : 'ְλ',
  			width:80,
			sortable : true
		},{
			name : 'isNeedTutor',
			display : '��ʦ״̬',
			sortable : true,
			width :90,
			process : function(v, row) {
				if(v==1){
					return "����Ҫָ����ʦ";
				}else{
					if(row.isTut==1){
						return "��ָ����ʦ";
					}else{
						return "δָ����ʦ";
					}
				}
			}
		}, {
			name : 'employeesStateName',
			display : 'Ա��״̬',
			sortable : true,
			width : 60
		}, {
			name : 'personnelTypeName',
			display : 'Ա������',
  			width:60,
			sortable : true
		}, {
			name : 'positionName',
			display : '��λ����',
  			width:60,
			sortable : true
		}, {
			name : 'personnelClassName',
			display : '��Ա����',
  			width:60,
			sortable : true
		}, {
			name : 'wageLevelName',
			display : '���ʼ���',
  			width:70,
			sortable : true
		}, {
			name : 'jobLevel',
			display : 'ְ��',
  			width:60,
			sortable : true
		}],
		lockCol:['userNo','staffName'],//����������
		// �߼�����
		// advSearchOptions : {
		// modelName : 'personnel',
		// searchConfig : [{
		// name : 'Ա�����',
		// value : 'c.userNo'
		// }, {
		// name : '����',
		// value : 'c.userName'
		// },{
		// name : "��˾",
		// value : 'companyName'
		// },{
		// name : "����",
		// value : 'deptSearch'
		// },{
		// name : "ְλ",
		// value : 'jobName'
		// },{
		// name : "����",
		// value : 'regionName'
		// },{
		// name : "Ա��״̬",
		// value : 'employeesStateName'
		// },{
		// name : "Ա������",
		// value : 'personnelTypeName'
		// },{
		// name : "��λ����",
		// value : 'positionName'
		// },{
		// name : "��Ա����",
		// value : 'personnelClassName'
		// },{
		// name : "ְ��",
		// value : 'jobLevel'
		// }]
		// },

		buttonsEx : buttonsArr,

		buttonsEx : buttonsArr,

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toTabView'
		},

		comboEx:[{
			text:'��ʦ״̬',
			key:'personnelTutorState',
			data:[{
			   text:'����Ҫָ����ʦ',
			   value:'1'
			},{
			   text:'δָ����ʦ',
			   value:'2'
			},{
			   text:'��ָ����ʦ',
			   value:'3'
			}]
		}],

		// ��չ�Ҽ�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showModalWin(
							"?model=hr_personnel_personnel&action=toTabView&id="
									+ row.id + "&skey=" + row['skey_']
									+ "&userNo=" + row.userNo + "&userAccount="
									+ row.userAccount, 'newwindow1',
							'resizable=yes,scrollbars=yes');
				}
			}
		}, {
			text : 'ָ����ʦ',
			icon : 'add',
			 showMenuFn : function(row) {
			 if (row.isNeedTutor != 1 && row.isTut == '0') {
			   return true;
			 }
			   return false;
			 },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toSetTutor&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&userNo="
							+ row.userNo
							+ "&userAccount="
							+ row.userAccount
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		},{
			text : '��ָ����ʦ',
			icon : 'add',
			 showMenuFn : function(row) {
			 if (row.isNeedTutor != 1 && row.isTut == '0') {
			   return true;
			 }
			   return false;
			 },
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_tutor_tutorrecords&action=toUnsetTutor&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&userNo="
							+ row.userNo
							+ "&userAccount="
							+ row.userAccount
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],
		searchitems : [{
			display : "Ա�����",
			name : 'userNoSearch'
		}, {
			display : "����",
			name : 'staffNameSearch'
		}, {
			display : "�Ա�",
			name : 'sex'
		},{
			display : "��˾",
			name : 'companyNameSearch'
		},{
			display : "ֱ������",
			name : 'deptNameSearch'
		},{
			display : "��������",
			name : 'deptNameSSearch'
		},{
			display : "��������",
			name : 'deptNameTSearch'
		},{
            display : "�ļ�����",
            name : 'deptNameFSearch'
        },  {
			display : "ְλ",
			name : 'jobNameSearch'
		}, {
			display : "����",
			name : 'regionNameSearch'
		}, {
			display : "Ա��״̬",
			name : 'employeesStateNameSearch'
		}, {
			display : "Ա������",
			name : 'personnelTypeNameSearch'
		}, {
			display : "��λ����",
			name : 'positionNameSearch'
		}, {
			display : "��Ա����",
			name : 'personnelClassNameSearch'
		}, {
			display : "ְ��",
			name : 'jobLevelSearch'
		}]
	});
});