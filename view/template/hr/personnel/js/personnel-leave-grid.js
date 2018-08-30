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
					+ "&userNo=" + userNo + "&userAccount=" + userAccount
					+ "&skey=" + skey, 'newwindow1',
			'resizable=yes,scrollbars=yes');
}
$(function() {
	var buttonsArr = [{
		name : 'view',
		text : "��ѯ",
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
        }];

	$("#personnelGrid").yxgrid({
		model : 'hr_personnel_personnel',
		title : '��ְԱ��������Ϣ',
		param : {
			"employeesState":"YGZTLZ"
	}	,
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton : false,
		bodyAlign:'center',
	    event:{'afterload':function(data,g){
	         $("#listSql").val(g.listSql);
          	 $("#totalSize").val(g.totalSize);
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
  					width:60,
					sortable : true,
					process : function(v, row) {
						return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
								+ row.id
								+ "\",\""
								+ row.userNo
								+ "\",\""
								+ row.userAccount + "\")' >" + v + "</a>";
					}
				}, {
					name : 'staffName',
					display : '����',
  					width:60,
					sortable : true,
					process : function(v, row) {
						return "<a href='#' title='����鿴Ա����Ϣ' onclick='viewPersonnel(\""
								+ row.id
								+ "\",\""
								+ row.userNo
								+ "\",\""
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
				}, {
                    name : 'belongDeptName',
                  	display : '��������',
  					width:80,
                  	hide : true
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
                },
					{
					name : 'jobName',
					display : 'ְλ',
  					width:80,
					sortable : true
				}, {
					name : 'quitDate',
					display : '��ְʱ��',
					sortable : true
				}, {
					name : 'quitTypeCode',
					display : '��ְ����',
  					width:60,
					sortable : true
				}],
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

		searchitems : [{
					display : "Ա�����",
					name : 'userNoSearch'
				}, {
					display : "����",
					name : 'staffNameSearch'
				},{
						display : "�Ա�",
						name : 'sex'
					}, {
					display : "��˾",
					name : 'companyNameSearch'
				}, {
						display : "ֱ������",
						name : 'deptNameSearch'
					},{
						display : "��������",
						name : 'deptNameSSearch'
					},{
						display : "��������",
						name : 'deptNameTSearch'
					}, {
                        display : "�ļ�����",
                        name : 'deptNameFSearch'
                    },{
					display : "ְλ",
					name : 'jobNameSearch'
				}]
	});
});