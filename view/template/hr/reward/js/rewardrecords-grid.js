var show_page = function(page) {
	$("#rewardrecordsGrid").yxgrid("reload");
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
			showThickboxWin("?model=hr_reward_rewardrecords&action=toExcelIn"
		          + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};

	$.ajax({
		type : 'POST',
		url : '?model=hr_reward_rewardrecords&action=getLimits',
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

	$("#rewardrecordsGrid").yxgrid({
		model : 'hr_reward_rewardrecords',
		title : 'н����Ϣ',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true
			}, {
				name : 'userAccount',
				display : 'Ա���˺�',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : 'Ա������',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_reward_rewardrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'deptNameS',
				display : '��������',
				sortable : true
			}, {
				name : 'deptIdS',
				display : '��������Id',
				sortable : true,
				hide : true
			}, {
				name : 'deptNameT',
				display : '��������',
				sortable : true
			}, {
				name : 'deptIdT',
				display : '��������Id',
				sortable : true,
				hide : true
			}, {
				name : 'jobId',
				display : 'ְλid',
				sortable : true,
				hide : true
			}, {
				name : 'jobName',
				display : 'Ա��ְλ',
				sortable : true
			}, {
				name : 'rewardPeriod',
				display : '��н�·�',
				sortable : true
			}, {
				name : 'rewardDate',
				display : 'н������',
				sortable : true,
				hide : true
			}, {
				name : 'actRewardDate',
				display : 'ʵ������',
				sortable : true,
				hide : true
			}, {
				name : 'workDays',
				display : '���¹�����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'leaveDays',
				display : '�¼�����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'sickDays',
				display : '��������',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'basicWage',
				display : '��������',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'provident',
				display : '���˹�����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'socialSecurity',
				display : '�����籣',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'projectBonus',
				display : '��Ŀ����',
				sortable : true
			}, {
				name : 'specialBonus',
				display : '�ر���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'otherBonus',
				display : '��������',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'mealSubsidies',
				display : '�ͷѲ���',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'transportSubsidies',
				display : '��ͨ����',
				sortable : true,
				hide : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'otherSubsidies',
				display : '���ಹ��',
				sortable : true
			}, {
				name : 'sickDeduction',
				display : '���ٿۿ�',
				sortable : true,
				hide : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'leaveDeduction',
				display : '�¼ٿۿ�',
				sortable : true,
				hide : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'specialDeduction',
				display : '�ر�ۿ�',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'preTaxWage',
				display : '˰ǰ����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'afterTaxWage',
				display : '˰����',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'taxes',
				display : '�۳�˰��',
				sortable : true,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'remark',
				display : '��ע��Ϣ',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '����������',
				sortable : true,
				hide : true
			}, {
				name : 'createTime',
				display : '����ʱ��',
				sortable : true,
				hide : true
			}, {
				name : 'updateName',
				display : '�޸�������',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '�޸�ʱ��',
				sortable : true,
				hide : true
			}],
		buttonsEx : buttonsArr,
		toAddConfig : {
			formWidth : '900',
			formHeight : '500'
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			},
			action : 'toEdit',
			formWidth : '900',
			formHeight : '500'
		},
		toViewConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			},
			action : 'toView',
			formWidth : '900',
			formHeight : '500'
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if ((row.id == "noId")) {
					return false;
				}
			}
		},
		searchitems : [{
			display : "Ա�����",
			name : 'userNoM'
		},{
			display : "Ա������",
			name : 'userNameM'
		},{
			display : "����",
			name : 'deptName'
		},{
			display : "��н�·�",
			name : 'rewardPeriodSearch'
		}]
	});
});