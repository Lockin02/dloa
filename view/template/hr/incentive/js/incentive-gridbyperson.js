var show_page = function(page) {
	$("#incentiveGrid").yxgrid("reload");
};
$(function() {
	$("#incentiveGrid").yxgrid({
		model : 'hr_incentive_incentive',
		param : {
//			'userAccount' : $('#userAccount').val()
			'userNo' : $('#userNo').val()
		},
		title : '���͹���',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton : false,
		bodyAlign:'center',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true,
				width:70
			},  {
				name : 'userName',
				display : 'Ա������',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showThickboxWin(\"?model=hr_incentive_incentive&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				},
				width : 60
			}, {
				name : 'deptName',
				display : '��������',
				sortable : true,
				hide : true
			},  {
				name : 'incentiveTypeName',
				display : '��������',
				sortable : true,
				width : 60
			}, {
				name : 'reason',
				display : '����ԭ��',
				sortable : true,
				width : 130
			}, {
				name : 'incentiveDate',
				display : '��������',
				sortable : true,
				width : 75
			}, {
				name : 'grantUnitName',
				display : '���赥λ',
				sortable : true,
				width : 100
			}, {
				name : 'rewardPeriod',
				display : '�����·�',
				sortable : true,
				width : 70
			}, {
				name : 'incentiveMoney',
				display : '���ͽ��',
				sortable : true,
				process : function(v){
					if(v < 0){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width : 80
			}, {
				name : 'description',
				display : '����˵��',
				sortable : true
			}, {
				name : 'recordDate',
				display : '��¼����',
				sortable : true,
				width : 75
			}, {
				name : 'recorderName',
				display : '��¼��',
				sortable : true,
				width : 60
			},  {
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				width : 130
			}],
		toViewConfig : {
			action : 'toView',
			formWith : 800,
			formHeight : 400
		},
		//��������
		comboEx : [{
			text : '��������',
			key : 'incentiveType',
			datacode : 'HRJLSS'
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '���赥λ',
			name : 'grantUnitName'
		}, {
			display : '��������',
			name : 'incentiveDateSearch'
		},{
			display : '�����·�',
			name : 'rewardPeriod'
//		}, {
//			display : '����˵��',
//			name : 'description'
//		},{
//			name : 'recordDateSearch',
//			display : '��¼����'
//		}, {
//			display : '��¼��',
//			name : 'recorderName'
		}]
	});
});