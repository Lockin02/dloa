var show_page = function (page) {
	$("#applyGrid").yxgrid("reload");
};

$(function () {
	$("#applyGrid").yxgrid({
		model : 'hr_recruitment_apply',
		action : "teamPageJsonList",
		title : '��Ա����',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		param : {
			stateArr : '2,3,4,7'
		},
		isOpButton : false,
		bodyAlign : 'center',
		customCode : 'hr_recruitment_apply_dept_grid',
		event : {
			afterload : function(data,g){
				$("#deptLeadFlag").val(g.deptLeadFlag);
			}
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
			width : 140,
			process : function(v ,row){
				if(row.id>0) {
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_apply&action=toView&id=" + row.id +"\",1)'>" + v + "</a>";
				} else {
					return "";
				}
			}
		},{
			name : 'stateC',
			display : '����״̬',
			width : 60
		},{
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width:40
		},{
			name : 'formManName',
			display : '�����',
			width : 70,
			sortable : true
		},{
			name : 'resumeToName',
			display : '�ӿ���',
			width : 70,
			sortable : true
		},{
			name : 'deptNameO',
			display : 'ֱ������',
			width : 70,
			sortable : true
		},{
			name : 'deptNameS',
			display : '��������',
			width : 70,
			sortable : true
		},{
			name : 'deptNameT',
			display : '��������',
			width : 70,
			sortable : true
		},{
			name : 'deptNameF',
			display : '�ļ�����',
			width : 70,
			sortable : true
		},{
			name : 'workPlace',
			display : '�����ص�',
			width : 70,
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
			name : 'positionNote',
			display : 'ְλ��ע',
			sortable : true,
			width : 180,
			process : function(v,row){
				var tmp = '';
				if (row.developPositionName) {
					tmp += row.developPositionName + '��';
				}
				if (row.network) {
					tmp += row.network + '��';
				}
				if (row.device) {
					tmp += row.device;
				}
				return tmp;
			}
		},{
			name : 'positionLevel',
			display : '����',
			width : 70,
			sortable : true
		},{
			name : 'projectGroup',
			display : '������Ŀ��',
			width : 100,
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
			name : 'formDate',
			display : '�������',
			width : 80,
			sortable : true
		},{
			name: 'ExaDT',
			display : '����ͨ��ʱ��',
			width : 120,
			sortable : true,
			process : function (v ,row) {
				if (row.state >= 1 && row.state <= 7) {
					return v;
				} else {
					return '';
				}
			}
		},{
			name : 'assignedDate',
			display : '�´�����',
			width : 80,
			sortable : true
		},{
			name : 'addType',
			display : '��Ա����',
			sortable : true
		},{
			name : 'leaveManName',
			display : '��ְ/����������',
			sortable : true
		},{
			name : 'needNum',
			display : '��������',
			width : 60,
			sortable : true,
			process : function(v ,row) {
				if(v == "") {
					return 0;
				} else {
					return v;
				}
			}
		},{
			name : 'entryNum',
			display : '����ְ����',
			width : 60,
			sortable : true,
			process : function(v ,row) {
				if(v == "") {
					return 0;
				} else {
					return v;
				}
			}
		},{
			name : 'beEntryNum',
			display : '����ְ����',
			width : 60,
			sortable : true,
			process : function(v ,row) {
				if(v == "") {
					return 0;
				} else {
					return v;
				}
			}
		},{
			name : 'ingtryNum',
			display : '����Ƹ����',
			width : 60,
			sortable : true,
			process : function(v ,row) {
				return row.needNum - row.entryNum - row.beEntryNum;
			}
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
			name : 'userName',
			display : '¼������',
			sortable : true,
			width : 200,
			process : function (v ,row) {
				if (v == '') {
					return row.employName;
				} else if (row.employName == '') {
					return v;
				} else {
					return v + ',' + row.employName;
				}
			}
		},{
			name : 'applyReason',
			display : '����ԭ��',
			width : 200,
			sortable : true
		},{
			name : 'workDuty',
			display : '����ְ��',
			width : 200,
			sortable : true
		},{
			name : 'jobRequire',
			display : '��ְҪ��',
			width : 200,
			sortable : true
		}],

		comboEx:[{
			text:'����״̬',
			key:'state',
			data:[{
				text:'��Ƹ��',
				value:'2'
			},{
				text:'��ͣ',
				value:'3'
			},{
				text:'���',
				value:'4'
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

		toViewConfig : {
			toViewFn : function(p,g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showOpenWin("?model=hr_recruitment_apply&action=toView&id=" + get[p.keyField]+"&ExaStatus="+get['ExaStatus'],'1');
				}
			}
		},

		menusEx : [{
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row){
				var deptLeadFlag = $("#deptLeadFlag").val();
				if(row.ExaStatus == "���" && deptLeadFlag == 1) {
					return true;
				}
				return false;
			},
			action : function(row,rows,grid) {
				if(row){
					if(row.deptId == '130' || row.postType == 'YPZW-WY') {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&isAudit=no" + "&skey=" + row['skey_'] ,1);
					} else {
						showModalWin("?model=hr_recruitment_apply&action=toAuditEdit&id=" + row.id + "&skey=" + row['skey_'] ,1);
					}
				}
			}
		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitment_apply&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text : '��ͣ',
			icon : 'delete',
			showMenuFn: function(row) {
				var deptLeadFlag=$("#deptLeadFlag").val();
				if (row.state == 2&&deptLeadFlag==1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id="
						+ row.id + "&state=3"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : 'ȡ��',
			icon : 'delete',
			showMenuFn: function(row) {
				var deptLeadFlag=$("#deptLeadFlag").val();
				if (row.state == 2&&deptLeadFlag==1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id="
						+ row.id + "&state=7"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '����',
			icon : 'add',
			showMenuFn: function(row) {
				var deptLeadFlag=$("#deptLeadFlag").val();
				if (row.state == 3&&deptLeadFlag==1) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=tochangeState&id="
						+ row.id + "&state=2"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=650");
				}
			}
		},{
			text : '������ͣ��¼',
			icon : 'view',
			showMenuFn: function(row) {
				if (row.stopStart != '') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=toViewStartstop&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
				}
			}
		},{
			text : 'ȡ����Ƹԭ��',
			icon : 'view',
			showMenuFn: function(row) {
				if (row.state == 7) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if(row){
					showThickboxWin("?model=hr_recruitment_apply&action=toViewCancel&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=900");
				}
			}
		}],

		searchitems : [{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : '�����',
			name : 'formManName'
		},{
			display : '�ӿ���',
			name : 'resumeToNameSearch'
		},{
			display : "ֱ������",
			name : 'deptNameO'
		},{
			display : "��������",
			name : 'deptNameS'
		},{
			display : "��������",
			name : 'deptNameT'
		},{
			display : "�ļ�����",
			name : 'deptNameF'
		},{
			display : "ְλ����",
			name : 'postTypeName'
		},{
			display : "����ְλ",
			name : 'positionName'
		},{
			display : "�����ص�",
			name : 'workPlaceSearch'
		},{
			display : "����",
			name : 'positionLevelSearch'
		},{
			display : "������Ŀ��",
			name : 'projectGroupSearch'
		},{
			display : '���ʱ��',
			name : 'formDate'
		},{
			display : '����ͨ��ʱ��',
			name : 'ExaDTSea'
		},{
			display : '��Ա����',
			name : 'addType'
		},{
			display : '��ְ/����������',
			name : 'leaveManName'
		},{
			display : '��Ƹ������',
			name : 'recruitManName'
		},{
			display : '��ƸЭ����',
			name : 'assistManNameSearch'
		},{
			display : '¼������',
			name : 'userName'
		},{
			display : '����ԭ��',
			name : 'applyReasonSearch'
		},{
			display : '����ְ��',
			name : 'workDutySearch'
		},{
			display : '��ְҪ��',
			name : 'jobRequireSearch'
		}]
	});
});