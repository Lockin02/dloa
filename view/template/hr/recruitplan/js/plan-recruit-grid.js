var show_page = function(page) {
	$("#planGrid").yxgrid("reload");
};

$(function() {
	buttonsArr = [];

	// ��ͷ��ť����
	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recruitplan_plan&action=toImport"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};
	buttonsArr.push(excelOutArr);
	/*
	$.ajax({
		type : 'POST',
		url : '?model=hr_personnel_attendance&action=getLimits',
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
	*/

	$("#planGrid").yxgrid({
		isEditAction : false,
		isDelAction : false,
		isAddAction:false,
		model : 'hr_recruitplan_plan',
		title : '��Ƹ�ƻ�',
		buttonsEx:buttonsArr,
		param:{
			'ExaStatus':'���'
		},

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			width : 130,
			process : function(v ,row) {
				//if(row.viewType==1){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitplan_plan&action=toView&id=" + row.id +"\")'>" + v + "</a>";
				/*}else{
					return "";
				}*/
			}
		},{
			name : 'stateC',
			display : '����״̬',
			width : 60
		},{
			name : 'ExaStatus',
			display : '����״̬',
			width : 60
		},{
			name : 'formManName',
			display : '�����',
			width : 70,
			sortable : true
		},{
			name : 'deptName',
			display : '������',
			sortable : true
		},{
			name : 'postTypeName',
			display : 'ְλ����',
			width : 80,
			sortable : true
		},{
			name : 'positionName',
			display : '����ְλ',
			sortable : true
		},{
			name : 'isEmergency',
			display : '�Ƿ����',
			sortable : true,
			width : 60,
			process : function(v ,row) {
				if(v == "1") {
					return "��"
				}else if(v == "0") {
					return "��";
				}else{
					return "";
				}
			}
		},{
			name : 'hopeDate',
			display : 'ϣ������ʱ��',
			sortable : true
		},{
			name : 'addType',
			display : '��Ա����',
			sortable : true
		},{
			name : 'needNum',
			display : '��������',
			width : 60,
			sortable : true
		},{
			name : 'entryNum',
			display : '����ְ����',
			width : 60,
			sortable : true
		},{
			name : 'beEntryNum',
			display : '����ְ����',
			width : 60,
			sortable : true
		},{
			name : 'recruitManName',
			display : '��Ƹ������',
			width : 70,
			sortable : true
		},{
			name : 'assistManName',
			display : '��ƸЭ����',
			sortable : true,
			width : 200
		},{
			name : 'applyRemark',
			display : '���ȱ�ע',
			sortable : true,
			width : 300
		}],

		comboEx:[{
			text:'����״̬',
			key:'state',
			data:[{
				text:'δ�´�',
				value:'1'
			},{
				text:'��Ƹ��',
				value:'2'
			},{
				text:'��ͣ',
				value:'3'
			},{
				text:'���',
				value:'4'
			},{
				text:'�ر�',
				value:'5'
			},{
				text:'����',
				value:'6'
			},{
				text:'ȡ��',
				value:'7'
			}]
		},{
			text:'�Ƿ����',
			key:'isEmergency',
			data:[{
				text:'��',
				value:'1'
			},{
				text:'��',
				value:'0'
			}]
		}],


		menusEx : [{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���'
					|| row.ExaStatus == '���'
					|| row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitplan_plan&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text : '��ͣ',
			icon : 'delete',
			showMenuFn: function(row) {
				if (row.state ==2)
					return true;
				else
					return false;
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitplan_plan&action=tochangeState&id="
						+ row.id+"&state="+3
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '����',
			icon : 'delete',
			showMenuFn: function(row) {
				if (row.state ==2)
					return true;
				else
					return false;
			},
			action : function(row) {
				if(row) {
					showThickboxWin("?model=hr_recruitplan_plan&action=tochangeState&id="
						+ row.id+"&state="+6
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : 'ȡ��',
			icon : 'delete',
			showMenuFn: function(row) {
				if (row.state == 2)
					return true;
				else
					return false;
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitplan_plan&action=tochangeState&id="
						+ row.id+"&state="+7
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '����',
			icon : 'add',
			showMenuFn: function(row) {
				if (row.state == 3 || row.state == 6 || row.state == 9) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row) {
					if(window.confirm("ȷ��Ҫ������?")) {
						$.ajax({
							type:"POST",
							url:"?model=hr_recruitplan_plan&action=changeState",
							data:{
								id:row.id,
								state:2
							},
							success:function(msg) {
								if(msg == 1) {
									alert('���óɹ�!');
									show_page();
								}else{
									alert('����ʧ��!');
									show_page();
								}
							}
						});
					}
				}
			}
		}],

		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=hr_recruitplan_plan&action=toView&id=" + get[p.keyField]);
				}
			}
		},

		searchitems : [{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : '�����',
			name : 'formManName'
		},{
			display : "������",
			name : 'deptName'
		},{
			display : "ְλ����",
			name : 'postTypeName'
		},{
			display : "����ְλ",
			name : 'positionName'
		},{
			display : "ϣ������ʱ��",
			name : 'hopeDate'
		},{
			display : "��Ա����",
			name : 'addType'
		},{
			display : "��������",
			name : 'needNum'
		},{
			display : '����ְ����',
			name : 'entryNum'
		},{
			display : '����ְ����',
			name : 'beEntryNum'
		},{
			display : '��Ƹ������',
			name : 'recruitManName'
		},{
			display : '��ƸЭ����',
			name : 'assistManName'
		},{
			display : '���ȱ�ע',
			name : 'applyRemark'
		}]
	});
});