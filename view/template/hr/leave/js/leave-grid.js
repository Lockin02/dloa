var show_page = function(page) {
	$("#leaveGrid").yxgrid("reload");
};

$(function() {
	$("#leaveGrid").yxgrid({
		model : 'hr_leave_leave',
		action : 'pageJsonLeave',
		param:{
			state:'1,2,3,4'
		},
		title : '��ְ����',
		showcheckbox : true,
		isDelAction : false,
		isEditAction : false,
		isAddAction : true,
		isOpButton : false,
		isAdvanceSearch : false,
		bodyAlign : 'center',

		buttonsEx : [{
			name : 'printLeave',
			text : "��ӡ��ְ֤��",
			icon : 'print',
			action :function(row ,rows ,idArr) {
				if(row){
					for(var i = 0 ;i < rows.length ;i++) {
						if(rows[i].ExaStatus != '���' || rows[i].comfirmQuitDate == '' || rows[i].state == '4') {
							alert('����Ч���ݣ�������ѡ��');
							return false;
						}
					}
					idStr = idArr.toString();
				} else {
					idStr = '';
				}
				showThickboxWin("?model=hr_leave_leave&action=toConfirmation&idStr=" + idStr
					+"&type=prove"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=650");
			}
		},{
			name : 'printOrder',
			text : "��ӡ�����嵥",
			icon : 'print',
			action :function(row ,rows ,idArr){
				if(row){
					for(var i = 0 ;i < rows.length ;i++) {
						if(rows[i].isHandover != '1') {
							alert('����δ����Ľ����嵥��������ѡ��');
							return false;
						}
						if(rows[i].state == '4') {
							alert('�����ѹرյ���ְ���룬������ѡ��');
							return false;
						}
					}
					idStr = idArr.toString();
				} else {
					idStr = '';
				}
				showThickboxWin("?model=hr_leave_leave&action=toConfirmation&idStr=" + idStr
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=650");
			}
		},{
			name : 'expport',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=hr_leave_leave&action=toExport&docType=RKPURCHASE"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=950");
			}
		},{
			name : 'expportunconfirm',
			text : 'δȷ����Ϣ����',
			icon : 'excel',
			action : function(row) {
				window.open("?model=hr_leave_leave&action=toExpportunconfirm"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=650&width=950");
			}
		},{
			name : 'updateArchives',
			text : '����Ա������',
			icon : 'edit',
			action : function(row ,rows ,grid) {
				if(rows){
					var checkedRowsIds = $("#leaveGrid").yxgrid("getCheckedRowIds");  //��ȡѡ�е�id
					var num = 0;
					var states = [] ,UpdateStates = [];
					$.each(rows,function(i,n) {
						var o = eval( n );
						states.push(o.ExaStatus);
						UpdateStates.push(o.state);
						if(o.nowDate<=o.comfirmQuitDate){
							num++;
						}
					});
					var uniqueState = $.unique(states);
					var stateLength = uniqueState.length;
					var uniqueUpdateStates = $.unique(UpdateStates);
					var UpdateStatesLength = uniqueUpdateStates.length;
					if(stateLength == 1 && UpdateStatesLength == 1 && uniqueState[0] == '���'
						&& uniqueUpdateStates[0] != '3' && num == 0) {
						if(window.confirm("ȷ�ϸ���?")){
							$.ajax({
								type:"POST",
								url:"?model=hr_leave_leave&action=updatePersonInfo",
								data:{
									id : checkedRowsIds
								},
								success:function(msg){
									if(msg == 1){
										alert('���³ɹ�!');
										show_page();
									}else{
										alert('����ʧ��!');
										show_page();
									}
								}
							});
						}
					} else {
						alert("��ѡ��״̬Ϊ'����������'�ĵ���");
					}
				} else {
					alert("��ѡ�񵥾ݣ�");
				}
			}
		},{
			name : 'editLeaveInfoExcel',
			text : '�޸���ְ��Ϣ',
			icon : 'edit',
			action : function() {
				showThickboxWin("?model=hr_leave_leave&action=toEditLeaveInfoExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=650");
			}
		}],

		event : {
			row_dblclick : function(e, row, data) {
				showThickboxWin("?model=hr_leave_leave&action=toView&id="
					+ data.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : 'ȷ����ְ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toEditType&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
			}
		},{
			name : 'sumbit',
			text : '�ύ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if ((row.ExaStatus != '���' && row.ExaStatus!= '��������') && row.state == 2) {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if (row) {
					var auditType = '';
					switch(row.wageLevelCode){
						case "GZJBFGL" : auditType = '5';break;//�ǹ����
						case "GZJBJL" : auditType = '15';break;//����
						case "GZJBZG" : auditType = '25';break;//����
						case "GZJBZJ" : auditType = '35';break;//�ܼ�
						case "GZJBFZ" : auditType = '45';break;//����
						case "GZJBZJL" : auditType = '75';break;//�ܾ���
					}

					showThickboxWin("controller/hr/leave/ewf_index1.php?actTo=ewfSelect&billId=" + row.id
						+ "&billDept=" + row.deptId
						+ "&flowMoney=" + auditType
						+ "&proSid=" + row.projectManagerId
						+ "&eUserId=" + row.userAccount
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=600"');
				} else {
					alert("��ѡ��һ������");
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
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_leave&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			name : 'realReason',
			text : '��ʵ��ְԭ��',
			icon : 'edit',
			action : function(row ,rows ,grid) {
				if (row) {
					showThickboxWin('?model=hr_leave_leave&action=toEditReal&id='
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text : '��̸��¼',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_interview&action=InterviewNotice&leaveId=' + row.id);
			}
		},{
			text : '��ְ�����嵥',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=handoverlist&leaveId=' + row.id);
			}
		},{
			text : '�޸Ľ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				//����ж��Ƿ��Ѿ�ȷ���������ҵ���δ�ر�
				if (row.isHandover != '0' && row.isAffirmAll != '0' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=toAlterHand&leaveId=' + row.id);
			}
		},{
			text : '������ְ�����嵥',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isHandover != '0' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=toRestart&leaveId='+ row.id);
			}
		},{
			text : 'ȷ�Ͻ����嵥',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.isHandover == '1' && row.userSelfCstatus != 'YQR' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=hr_leave_handover&action=handoverProlist&leaveId=' + row.id );
			}
		},{
			text : '�����ʼ�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toSendEmail&leaveId='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '������ְָ��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '4') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toSendEmailguide&leaveId='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '��ӡ��ְ֤��',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.comfirmQuitDate != '' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toLeaveProof&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800");
			}
		},{
			text : '��ע����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == '4') {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toEditRemark&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '�޸�Ա��״̬��Ϣ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=user&action=edit&userid='
					+ row.userAccount
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '�޸���ְ��Ϣ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toEditLeaveInfo&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");
			}
		},{
			text : '�������',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toBack&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=800");
			}
		},{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_leave&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#leaveGrid").yxgrid("reload");
							}
						}
					});
				}
			}
		},{
			text:"����Ա������",
			icon :'edit',
			showMenuFn : function(row){
				if (row.ExaStatus == '���' && row.nowDate > row.comfirmQuitDate
						&& (row.state != "3" && row.state != '4')){
					return true;
				}
				else return false;
			},
			action : function(row ,rows ,grid) {
				if(window.confirm("ȷ�ϸ���?")) {
					$.ajax({
						type:"POST",
						url:"?model=hr_leave_leave&action=updatePersonInfo",
						data:{
							id : row.id
						},
						success:function(msg){
							if(msg == 1) {
								alert('���³ɹ�!');
								show_page();
							}else{
								alert('����ʧ��!');
								show_page();
							}
						}
					});
				}
			}
		},{
			name : 'cancel',
			text : '��������',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((row.ExaStatus == 'δ�ύ' && row.state == '2') || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action : function(row ,rows ,grid) {
				if(row.ExaStatus == "δ�ύ"){
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_leave&action=backSubmit",
						data : {
							id : row.id,
							state:1
						},
						success : function(msg) {
							if (msg == 1) {
								alert('���سɹ���');
								$("#leaveGrid").yxgrid("reload");
							}
						}
					});
				} else {
					var ewfurl = 'controller/hr/leave/ewf_index1.php?actTo=delWork&billId=';
					$.ajax({
						type : "POST",
						url : "?model=common_workflow_workflow&action=isAudited",
						data : {
							billId : row.id,
							examCode : 'oa_hr_leave'
						},
						success : function(msg) {
							if (msg == '1') {
								alert('�����Ѿ�����������Ϣ�����ܳ���������');
								$("#leaveGrid").yxgrid("reload");
								return false;
							} else {
								if(confirm('ȷ��Ҫ����������')){
									$.ajax({
										type: "GET",
										url: ewfurl,
										data: {"billId" : row.id },
										async: false,
										success: function(data){
											$.ajax({
												type : "POST",
												url : "?model=hr_leave_leave&action=backSubmit",
												data : {
													id : row.id,
													state : 1
												},
												success : function(msg) {
													if (msg == 1) {
														alert('���سɹ���');
														$("#leaveGrid").yxgrid("reload");
													}
												}
											});
										}
									});
								}
							}
						}
					});
				}
			}
		},{
			name : 'close',
			text : '�ر�ԭ��',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.state == '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toCloseReason&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
			}
		},{
			name : 'close',
			text : '�ر�',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.state != '4') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin('?model=hr_leave_leave&action=toClose&id='
					+ row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=800");
			}
		}],

		// ����Ϣ
		colModel : [{
			display: '��ӡ',
			name: 'id',
			width : 30,
			align : 'center',
			sortable: true,
			process : function(v,row){
				if(row.ExaStatus == '���' && row.comfirmQuitDate !='' && row.state != '4') {
					return '<img src="images/icon/print.gif" />';
				}
			}
		},{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'leaveCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v, row) {
				var date = new Date();
				var leaveDate = new Date(row.comfirmQuitDate);
				var ms = (leaveDate.valueOf() - date.valueOf()) / 1000;
				// �뵱ǰʱ�䲻�����죬��ͨ������������δ���𽻽��嵥�ģ����ݱ����ʾΪ��ɫ
				if (ms < 172800 && ms > 0 && row.ExaStatus == '���' && row.handoverId == '') {
					if (row.ExaStatus=='���' && row.nowDate > row.comfirmQuitDate && row.state != "3") {
						return '<img src="images/icon/icon139.gif"/><a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_leave_leave&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#CE0000'>"
							+ v + "</font>" + '</a>';
					} else {
						return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_leave_leave&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#CE0000'>"
							+ v + "</font>" + '</a>';
					}
				} else {
					if (row.ExaStatus=='���' && row.nowDate > row.comfirmQuitDate && row.state != "3") {
						return '<img src="images/icon/icon139.gif"/><a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_leave_leave&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v + "</font>" + '</a>';
					} else {
						return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showModalWin(\'?model=hr_leave_leave&action=toViewTab&id='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v + "</font>" + '</a>';
					}
				}
			}
		},{
			name : 'userNo',
			display : 'Ա�����',
			width : 80,
			sortable : true,
			process : function(v,row){
				if (row.emailSate == 1) {
					return "<img src='images/icon/icon144.gif'/>"+ v;
				} else {
					return  v ;
				}
			}
		},{
			name : 'userName',
			display : 'Ա������',
			width : 60,
			sortable : true
		},{
			name : 'state',
			display : '����״̬',
			sortable : true,
			width : 70,
			process:function(v ,row) {
				if (v == "1") {
					return "δȷ������ ";
				}else if (v == "2") {
					if(row.ExaStatus == '���' && row.nowDate > row.comfirmQuitDate) {
						return "����������";
					} else {
						return "��ȷ������";
					}
				} else if (v == '4'){
					return "�ѹر�";
				} else {
					return "�Ѹ��µ���";
				}
			}
		},{
			name : 'ExaStatus',
			display : '����״̬',
			width : 60,
			sortable : true
		},{
			name : 'userSelfCstatus',
			display : 'Ա��ȷ��״̬',
			sortable : true,
			width : 70,
			process:function(v) {
				if(v == "WQR") {
					return "δȷ�� ";
				} else {
					return "��ȷ��";
				}
			}
		},{
			name : 'handoverCstatus',
			display : '�����嵥״̬',
			width : 70,
			sortable : true,
			process:function(v ,row) {
				if (row.isHandover == 0) {
					return "δ���� ";
				} else {
					if (v == "WQR") {
						return "δȷ�� ";
					} else {
						return "��ȷ��";
					}
				}
			}
		},{
			name : 'companyName',
			display : '��˾',
			width : 60,
			sortable : true
		},{
			name : 'personnelTypeName',
			display : 'Ա������',
			width : 60,
			sortable : true
		},{
			name : 'deptName',
			display : '����',
			width : 80,
			sortable : true
		},{
			name : 'deptNameS',
			display : '��������',
			width : 80,
			sortable : true
		},{
			name : 'deptNameT',
			display : '��������',
			width : 80,
			sortable : true
		},{
            name : 'deptNameF',
            display : '�ļ�����',
            width:80,
            sortable : true
        },{
			name : 'jobName',
			display : 'ְλ',
			width : 80,
			sortable : true
		},{
			name : 'workProvince',
			display : 'ʡ��',
			width : 80,
			sortable : true,
			hide : true
		},{
			name : 'entryDate',
			display : '��ְ����',
			width : 70,
			sortable : true
		},{
			name : 'quitTypeName',
			display : '��ְ����',
			width : 100,
			sortable : true
		},{
			name : 'leaveApplyDate',
			display : '��ְ��������',
			width : 80,
			sortable : true,
			process : function (v) {
				return v.substr(0 ,10);
			}
		},{
			name : 'requireDate',
			display : '������ְ����',
			width : 80,
			sortable : true
		},{
			name : 'comfirmQuitDate',
			display : '��ְ����',
			width : 80,
			sortable : true
		},{
			name : 'salaryEndDate',
			display : '���ʽ����ֹ����',
			width : 90,
			sortable : true
		},{
			name : 'salaryPayDate',
			display : '����֧������',
			width : 80,
			sortable : true
		},{
			name : 'pensionReduction',
			display : '�籣��Ա',
			width : 90,
			sortable : true
		},{
			name : 'fundReduction',
			display : '�������Ա',
			width : 90,
			sortable : true
		},{
			name : 'employmentEnd',
			display : '�ù���ֹ',
			width : 50,
			sortable : true
		},{
			name : 'softSate',
			display : '�칫���״̬',
			width : 80,
			sortable : true,
			process:function(v){
				if(v == "1"){
					return "�ѹر� ";
				} else {
					return "δ�ر�";
				}
			}
		},{
			name : 'createName',
			display : '������',
			width : 60,
			sortable : true
		},{
			name : 'remark',
			display : '���ȱ�ע',
			width : 200,
			sortable : true,
			align : 'left'
		},{
			name : 'mobile',
			display : '��ϵ�绰',
			width : 100,
			sortable : true
		},{
			name : 'personEmail',
			display : '˽������',
			width : 120,
			sortable : true
		},{
			name : 'postAddress',
			display : '�ʼĵ�ַ',
			width : 150,
			sortable : true,
			align : 'left'
		},{
			name : 'quitReson',
			display : '��ְԭ��',
			width : 350,
			sortable : true,
			align : 'left',
			process :��function(v){
				//��ȡ��ְԭ���滻�����ַ�
				var str = v.substring(-5);
				if (str == "^nbsp") { //û�а�������ԭ��
					v = v.replace(/\^nbsp/g,"��");
				} else {
					var num =  v.split("^nbsp").length - 1;
					for (var i = 0; i < num - 1; i++) {
						v = v.replace(/\^nbsp/,"��");
					}
					v = v.replace(/\^nbsp/,":"); //���һ��Ϊ����
				}

				return v;
			}
		},{
			name : 'isBack',
			display : '�Ƿ������',
			width : 70,
			sortable : true,
			process : function (v) {
				if (v == 1) {
					return  '��';
				} else {
					return '��';
				}
			}
		},{
			name : 'realReason',
			display : '��ʵ��ְԭ��',
			width : 350,
			sortable : true,
			align : 'left'
		}],

		lockCol:['leaveCode','userNo','userName'],//����������

		toEditConfig : {
			action : 'toEdit'
		},
		toAddConfig : {
			formHeight : 700
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin('?model=hr_leave_leave&action=toViewTab&id=' + rowData['id']);
			}
		},

		//��������
		comboEx : [{
			text : '����״̬',
			key : 'stateS',
			data : [{
				text : 'δȷ������',
				value : '1'
			},{
				text : '��ȷ������',
				value : '2_1'
			},{
				text : '����������',
				value : '2_2'
			},{
				text : '�Ѹ��µ���',
				value : '3'
			},{
				text : '�ѹر�',
				value : '4'
			}]
		},{
			text : '��ְ����',
			key : 'quitTypeCode',
			datacode : 'YGZTLZ'
		},{
			text : '�����嵥״̬',
			key : 'handoverCstatusS',
			data : [{
				text : 'δ����',
				value : '1'
			},{
				text : 'δȷ��',
				value : 'WQR'
			},{
				text : '��ȷ��',
				value : 'YQR'
			}]
		},{
			text : '����״̬',
			key : 'spzt',
			data : [{
				text : '���',
				value : '���'
			},{
				text : '��������',
				value : '��������'
			},{
				text : 'δ�ύ',
				value : 'δ�ύ'
			},{
				text : '���',
				value : '���'
			}]
		},{
			text : '������',
			key : 'isBack',
			data : [{
				text : '��',
				value : '1'
			},{
				text : '��',
				value : '0'
			}]
		}],

		searchitems : [{
			display : "���ݱ��",
			name : 'leaveCode'
		},{
			display : "Ա�����",
			name : 'userNo'
		},{
			display : "Ա������",
			name : 'userName'
		},{
			display : "����",
			name : 'deptName'
		},{
			display : "ְλ",
			name : 'jobName'
		},{
			display : "��ְ����",
			name : 'entryDate'
		},{
			display : "������ְ����",
			name : 'requireDate'
		},{
			display : "��ְ����",
			name : 'comfirmQuitDate'
		},{
			display : "���ʽ����ֹ����",
			name : 'salaryEndDate'
		},{
			display : "����֧������",
			name : 'salaryPayDate'
		},{
			display : "�籣��Ա",
			name : 'pensionReduction'
		},{
			display : "�������Ա",
			name : 'fundReduction'
		},{
			display : "���ȱ�ע",
			name : 'remark'
		},{
			display : "��ְԭ��",
			name : 'lzyy'
		},{
			display : "��ʵ��ְԭ��",
			name : 'realReason'
		}]
	});
});