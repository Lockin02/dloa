var show_page = function(page) {
	$("#transferGrid").yxgrid("reload");
};

//ɾ���ظ������IE�ļ��������⣩
function uniqueArray(a){
	temp = new Array();
	for(var i = 0 ;i < a.length ;i ++){
		if(!contains(temp ,a[i])) {
			temp.length += 1;
			temp[temp.length - 1] = a[i];
		}
	}
	return temp;
}

function contains(a, e){
	for(j = 0 ;j < a.length ;j++) {
		if(a[j] == e) {
			return true;
		}
	}
	return false;
}

$(function() {
	//��ͷ��ť����
	buttonsArr = [{
		name : 'return',
		text : '����Ա������',
		icon : 'edit',
		action : function(row, rows, grid) {
			if(rows) {
				var checkedRowsIds=$("#transferGrid").yxgrid("getCheckedRowIds");  //��ȡѡ�е�id
				var states=[];   //�ɹ�ѯ�۵�״̬����
				$.each(rows,function(i,n){
					var o = eval( n );
					states.push(o.status);
				});
				states.sort();
				var uniqueState=uniqueArray(states);
				var stateLength=uniqueState.length;
				if(stateLength==1&&uniqueState[0]==4){  //�жϵ��ݵ�״̬�Ƿ�Ϊ�����������¡�����ֻ��һ��״̬
					if(window.confirm("ȷ�ϸ���?")){
						$.ajax({
							type:"POST",
							url:"?model=hr_transfer_transfer&action=updatePersonInfo",
							data:{
								transferIds:checkedRowsIds
							},
							success:function(msg){
								if(msg==1){
									alert('���³ɹ�!');
									show_page();
								}else{
									alert('����ʧ��!');
									show_page();
								}
							}
						});
					}
				}else{
					alert("��ѡ��״̬Ϊ'����������'�ĵ���");
				}
			}else{
				alert("��ѡ�񵥾ݡ�");
			}
		}
	}];

	excelOutArr = {
		name : 'exportIn',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_transfer_transfer&action=toExcelIn"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
		}
	};

	excelOutArr2 = {
		name : 'exportOut',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_transfer_transfer&action=toExport"
				+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=800");
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
				buttonsArr.push(excelOutArr);
				buttonsArr.push(excelOutArr2);
			}
		}
	});

	$("#transferGrid").yxgrid({
		model : 'hr_transfer_transfer',
		title : '������¼',
		isAddAction:true,
		isEditAction:false,
		isViewAction:false,
		isDelAction:false,
		isOpButton:false,
		bodyAlign:'center',
		param : {
			'status' : '1,2,3,4,5,6,7'
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
			width:120,
			process : function(v ,row) {
				if(row.status == 4) {
					return "<img src='images/icon/icon139.gif'/><a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +"\"'>" + v + "</a>";
				} else {
					return "<a href='#' onclick='location=\"?model=hr_transfer_transfer&action=toViewJobTran&id=" + row.id +"\"'>" + v + "</a>";
				}
			}
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width : 80
		},{
			name : 'userName',
			display : 'Ա������',
			sortable : true,
			width : 70
		},{
			name : 'stateC',
			display : '����״̬',
			width : 70
		},{
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 70
		},{
			name : 'entryDate',
			display : '��ְ����',
			sortable : true,
			width : 80
		},{
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 80
		},{
			name : 'isCompanyChangeC',
			display : '�Ƿ�˾�䶯',
			sortable : false,
			width : 70
		},{
			name : 'isDeptChangeC',
			display : '�Ƿ��ű䶯',
			sortable : false,
			width : 70
		},{
			name : 'isJobChangeC',
			display : '�Ƿ�ְλ�䶯',
			sortable : false,
			width : 70
		},{
			name : 'isAreaChangeC',
			display : '�Ƿ�����䶯',
			sortable : false,
			width : 70
		},{
			name : 'isClassChangeC',
			display : '�Ƿ���Ա����䶯',
			sortable : false,
			width : 100
		},{
			name : 'preUnitName',
			display : '����ǰ��˾',
			sortable : true,
			width : 80
		},{
			name : 'preBelongDeptName',
			display : '����ǰ��������',
			sortable : true,
			width : 80
		},{
			name : 'preDeptNameS',
			display : '����ǰ��������',
			sortable : true,
			width : 80
		},{
			name : 'preDeptNameT',
			display : '����ǰ��������',
			sortable : true,
			width : 80
		},{
			name : 'preDeptNameF',
			display : '����ǰ�ļ�����',
			sortable : true,
			width : 80
		},{
			name : 'afterUnitName',
			display : '������˾',
			sortable : true,
			width : 80
		},{
			name : 'afterBelongDeptName',
			display : '��������������',
			sortable : true,
			width : 80
		},{
			name : 'afterDeptNameS',
			display : '�������������',
			sortable : true,
			width : 80
		},{
			name : 'afterDeptNameT',
			display : '��������������',
			sortable : true,
			width : 80
		},{
			name : 'afterDeptNameF',
			display : '�������ļ�����',
			sortable : true,
			width : 80
		},{
			name : 'preJobName',
			display : '����ǰְλ',
			sortable : true,
			width : 80
		},{
			name : 'afterJobName',
			display : '������ְλ',
			sortable : true,
			width : 80
		},{
			name : 'preUseAreaName',
			display : '����ǰ��������',
			sortable : true,
			width : 80
		},{
			name : 'afterUseAreaName',
			display : '�������������',
			sortable : true,
			width : 80
		},{
			name : 'prePersonClass',
			display : '����ǰ��Ա����',
			sortable : true
		},{
			name : 'afterPersonClass',
			display : '��������Ա����',
			sortable : true
		},{
			name : 'managerName',
			display : '������',
			sortable : true,
			width : 60
		},{
			name : 'reason',
			display : '����ԭ��',
			sortable : true,
			width : 130,
			align : 'left'
		}],

		lockCol:['formCode','userNo','userName'],//����������

		buttonsEx: buttonsArr,

		toAddConfig : {
			formHeight : 550,
			formWidth : 900
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 500,
			formWidth : 900
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},

		//��չ�Ҽ��˵�
		menusEx:[{
			text:'�鿴',
			icon:'view',
			action:function(row) {
				if(row){
					showThickboxWin("?model=hr_transfer_transfer&action=toViewJobTran&id="
						+ row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800");
				}
			}
		},{
			text:'�ύ����',
			icon:'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == "δ�ύ" || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action:function(row){
				if(row){
					location = "?model=hr_transfer_transfer&action=toConfirm&id="+ row.id;
				}
			}
		},{
			text:'����Ա������',
			icon:'edit',
			showMenuFn:function(row){
				if(row.ExaStatus == "���" && row.status == 4) {
					return true;
				}
				return false;
			},
			action:function(row,rows,grid){
				if(row){
					if(window.confirm("ȷ�ϸ���?")){
						$.ajax({
							type:"POST",
							url:"?model=hr_transfer_transfer&action=updatePersonInfo",
							data:{
								transferIds:row.id
							},
							success:function(msg){
								if(msg==1){
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
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_personnel_transfer&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		},{
			text:'��д��������',
			icon:'edit',
			showMenuFn : function(row) {
				if (row.employeeOpinion == 1 && row.status == 3) {
					return true;
				}
				return false;
			},
			action:function(row){
				if(row){
					location = "?model=hr_transfer_transfer&action=toLeaderView&type=hrmanager&id="+ row.id;
				}
			}
		}],

		comboEx:[{
			text:'����״̬',
			key:'ExaStatus',
			data:[{
				text:'δ�ύ',
				value:'δ�ύ'
			},{
				text:'��������',
				value:'��������'
			},{
				text:'���',
				value:'���'
			}]
		},{
			text:'����״̬',
			key:'status',
			data:[{
				text:'δ���',
				value:'1'
			},{
				text:'�����',
				value:'7'
			},{
				text:'Ա����ȷ��',
				value:'2'
			},{
				text:'Ա����ȷ��',
				value:'3'
			},{
				text:'����������',
				value:'4'
			},{
				text:'���',
				value:'6'
			}]
		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '���ݱ��',
			name : 'formCode'
		},{
			display : 'Ա�����',
			name : 'userNoSearch'
		},{
			display : 'Ա������',
			name : 'userNameSearch'
		},{
			display : '��ְ����',
			name : 'entryDate'
		},{
			display : '��������',
			name : 'applyDate'
		},{
			display : '����ǰ��˾',
			name : 'preUnitName'
		},{
			display : '����ǰ��������',
			name : 'preBelongDeptName'
		},{
			display : '����ǰ��������',
			name : 'preDeptNameS'
		},{
			display : '����ǰ��������',
			name : 'preDeptNameT'
		},{
			display : '����ǰ�ļ�����',
			name : 'preDeptNameF'
		},{
			display : '������˾',
			name : 'afterUnitName'
		},{
			display : '��������������',
			name : 'afterBelongDeptName'
		},{
			display : '�������������',
			name : 'afterDeptNameS'
		},{
			display : '��������������',
			name : 'afterDeptNameT'
		},{
			display : '�������ļ�����',
			name : 'afterDeptNameF'
		},{
			display : '����ǰְλ',
			name : 'preJobName'
		},{
			display : '������ְλ',
			name : 'afterJobName'
		},{
			display : '����ǰ��������',
			name : 'preUseAreaName'
		},{
			display : '�������������',
			name : 'afterUseAreaName'
		},{
			display : '����ǰ��Ա����',
			name : 'prePersonClass'
		},{
			display : '��������Ա����',
			name : 'afterPersonClass'
		},{
			display : '������',
			name : 'managerName'
		},{
			display : '����ԭ��',
			name : 'reason'
		}]
	});
});