var show_page = function(page) {
	$("#esmmemberGrid").yxgrid("reload");
};

$(function() {

	var projectId = $("#projectId").val();
	$("#esmmemberGrid").yxgrid({
		model : 'engineering_member_esmmember',
		param : {
			"projectId" : projectId
		},
		title : '��Ŀ��Ա',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isViewAction : false,
		showcheckbox : false,
		noCheckIdValue : 'noId',
		isOpButton : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '��Ŀ���',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				sortable : true,
				hide : true
			}, {
				name : 'memberName',
				display : '����',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(row.id == 'noId' || row.memberId == 'SYSTEM') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_member_esmmember&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'memberId',
				display : '��Աid',
				sortable : true,
				hide : true
			}, {
				name : 'personLevel',
				display : '����',
				sortable : true,
				width : 50,
				process : function(v,row){
					if(row.memberId != 'SYSTEM') return v;
				}
			}, {
				name : 'beginDate',
				display : '������Ŀ',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'endDate',
				display : '�뿪��Ŀ',
				sortable : true,
				width : 80,
				hide : true
			}, {
				name : 'roleName',
				display : '��ɫ',
				sortable : true,
				width : 70
			}, {
				name : 'activityName',
				display : '��������',
				width : 150,
				sortable : true,
				hide : true
			}, {
				name : 'feeDay',
				display : '��������',
				sortable : true,
				process : function(v,row){
					if(row.beginDate == ''){
						return '0.00';
					}
					if(row.endDate == ''){
						return "<span class='green' title='��ʱ��Ϣ'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width : 70
			}, {
				name : 'status',
				display : '״̬',
				process : function(v,row){
					if(row.id != 'noId'){
						if(v == 1){
							return '�뿪��Ŀ';
						}else {
							return '����';
						}
					}
				},
				width : 70
			}, {
				name : 'feePeople',
				display : '�����ɱ�(��)',
				sortable : true,
				process : function(v,row){
					if(row.beginDate == ''){
						return '0.00';
					}
					if(row.endDate == ''){
						return "<span class='green' title='��ʱ��Ϣ'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width : 70,
				hide : true
			}, {
				name : 'feePerson',
				display : '�����ɱ�(ȷ��)',
				sortable : true,
				process : function(v,row){
					if(row.memberId == 'SYSTEM'){
						return moneyFormat2(v);
					}else{
						if(row.beginDate == ''){
							return '0.00';
						}
						if(row.endDate == ''){
							return "<span class='green' title='��ʱ��Ϣ'>" + moneyFormat2(v) + "</span>";
						}else{
							return moneyFormat2(v);
						}
					}
				},
				width : 80
			}, {
				name : 'feePersonCount',
				display : '�����ɱ�(ʵʱ)',
				sortable : true,
				process : function(v,row){
					if(row.beginDate == ''){
						return '0.00';
					}
					if(row.endDate == ''){
						return "<span class='green' title='��ʱ��Ϣ'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				},
				width : 80,
				hide : true
			}, {
				name : 'costMoney',
				display : '¼�����',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'unconfirmMoney',
				display : 'δȷ�Ϸ���',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'confirmMoney',
				display : '��ȷ�Ϸ���',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'backMoney',
				display : '��ط���',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'unexpenseMoney',
				display : 'δ��������',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'expensingMoney',
				display : '�ڱ�������',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'expenseMoney',
				display : '�ѱ�������',
				sortable : true,
				width : 70,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				hide : true
			}
		],
//		menusEx : [{
//			text : 'ɾ��',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if(row.isManager == "1"){
//					return false;
//				}
//				if (row.status == "0") {
//					return true;
//				}
//				return false;
//			},
//			action : function(rowData, rows, rowIds, g) {
//				if(rowData.memberType == 0){
//					$.ajax({
//						type : "POST",
//						url : "?model=engineering_worklog_esmworklog&action=checkExistLogPro",
//						data : {
//							"projectId" : projectId,
//							"userId" : rowData.memberId
//						},
//				    	async: false,
//						success : function(msg) {
//							if (msg == '1') {
//								alert('�ó�Ա�Ѷ���Ŀ����ҵ�����������ɾ����');
//							} else {
//								if(confirm('ȷ��Ҫɾ����Ŀ��Ա��')){
//									$.ajax({
//										type : "POST",
//										url : "?model=engineering_member_esmmember&action=ajaxdeletes",
//										data : {
//											id : rowData.id
//										},
//					    				async: false,
//										success : function(msg) {
//											if (msg == 1) {
//												alert('ɾ���ɹ���');
//												show_page(1);
//											}else{
//												alert("ɾ��ʧ��! ");
//											}
//										}
//									});
//								}
//							}
//						}
//					});
//				}else{
//					g.options.toDelConfig.toDelFn(g.options, g);
//				}
//			}
//		}],
		toAddConfig : {
			formWidth : 950,
			formHeight : 500,
			plusUrl : "&id="
					+ projectId
		},
		toEditConfig : {
			formWidth : 950,
			formHeight : 500,
			action : 'toEdit',
			showMenuFn : function(row){
				if(row.memberId != 'SYSTEM' && row.status != 1){
					return true;
				}
				return false;
			}
		},
		toViewConfig : {
			formWidth : 900,
			formHeight : 400,
			action : 'toView',
			showMenuFn : function(row){
				if(row.memberId != 'SYSTEM'){
					return true;
				}
				return false;
			}
		},
		searchitems : [{
			display : "��Ա����",
			name : 'memberNameSearch'
		},{
			display : "��Ա�ȼ�",
			name : 'personLevelSearch'
		}],
		comboEx :[{
			text : "״̬",
			key : 'status',
			value : '0',
 			data : [{
                text : '����',
                value : 0
            }, {
                text : '�뿪��Ŀ',
                value : 1
            }]
		}],
		sortorder : 'ASC',
		sortname : 'c.isManager desc,c.personLevel'
	});
});