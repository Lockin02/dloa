var show_page = function(page) {
	$("#esmworklogGrid").yxgrid("reload");
};

$(function() {
	//��ͷ��ť����
	buttonsArr = [];
	

	batchUnauditArr = {
		name : 'batchUnaudit',
		text : '����ȡ�����',
		icon : 'delete',
		action : function(row, rows, grid) {
			showOpenWin("?model=engineering_worklog_esmworklog&action=toBatchUnaudit&projectId="+$("#projectId").val(),1,300,700);
		}
	};
	if($("#unauditLimit").val() == "1"){
		buttonsArr.push(batchUnauditArr);
	}
	
	$("#esmworklogGrid").yxgrid({
		model : 'engineering_worklog_esmworklog',
		title : '������־',
		showcheckbox : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		isOpButton :false,
		param : {
			'projectId' : $("#projectId").val()
		},
		// ����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'executionDate',
				display : '����',
				sortable : true,
				width : 70,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_worklog_esmworklog&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1,750,1150)'>" + v + "</a>";
				}
			}, {
				name : 'createName',
				display : '��д��',
				sortable : true
			},{
				name : 'country',
				display : '����',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'province',
				display : 'ʡ',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'city',
				display : '��',
				sortable : true,
				width : 70,
				hide : true
			}, {
				name : 'provinceCity',
				display : '���ڵ�',
				sortable : true,
				width : 80
			}, {
				name : 'workStatus',
				display : '����״̬',
				sortable : true,
				width : 70,
				datacode : 'GXRYZT',
				hide : true
			}, {
				name : 'projectName',
				display : '��Ŀ',
				sortable : true,
				width : 140,
				hide : true
			}, {
				name : 'activityName',
				display : '����',
				sortable : true,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showActivity(" + row.activityId + ")'>" + v + "</a>";
				}
			}, {
				name : 'workloadAndUnit',
				display : '�����',
				sortable : true,
				width : 60,
				process : function(v,row){
					return v + " " + row.workloadUnitName;
				}
			}, {
				name : 'workloadDay',
				display : '�����',
				sortable : true,
				width : 60,
				hide : true
			}, {
				name : 'workProcess',
				display : '����',
				sortable : true,
				width : 70,
				process : function (v){
					if(v*1 == -1){
						return " -- ";
					}else{
						return v + " %";
					}
				}
			}, {
				name : 'description',
				display : '����������',
				sortable : true,
				width : 150
			},{
				name : 'remark',
				display : '��ע˵��',
				sortable : true,
				hide : true
			},{
				name : 'status',
				display : '�ܱ�״̬',
				sortable : true,
				width : 60,
				process : function(v) {
					if (v == "WTJ") {
						return "δ�ύ";
					} else if (v == "YTJ") {
						return "���ύ";
					} else if(v == 'YQR'){
						return "��ȷ��";
					} else {
						return "��ͨ��";
					}
				},
				hide : true
			},{
				name : 'confirmStatus',
				display : 'ȷ��״̬',
				sortable : true,
				width : 60,
				process : function(v) {
					if (v == "1") {
						return "��ȷ��";
					}else{
						return "δȷ��";
					}
				},
				hide : true
			},{
				name : 'assessResultName',
				display : '��˽��',
				sortable : true,
				width : 70
			},{
				name : 'feedBack',
				display : '��˽���',
				sortable : true
			},{
				name : 'costMoney',
				display : '¼�����',
				sortable : true,
				width : 70,
				process : function (v,row){
					if(row.confirmStatus == '0'){
						return "<span class='green' title='δȷ�ϵķ���'>" + moneyFormat2(v) + "</span>";
					}else{
						return "<span class='blue' title='��ȷ�ϵķ���'>" + moneyFormat2(v) + "</span>";
					}
				}
			},{
				name : 'confirmMoney',
				display : 'ȷ�Ϸ���',
				sortable : true,
				width : 70,
				process : function (v,row){
					if(row.confirmStatus == '1' && v != row.costMoney){
						return "<span class='blue'>" + moneyFormat2(v) + "</span>";
					}else{
						return moneyFormat2(v);
					}
				}
			},{
				name : 'backMoney',
				display : '��ط���',
				sortable : true,
				width : 70,
				process : function (v,row){
					if(v > 0){
						return "<span class='red'>" + moneyFormat2(v) + "</span>";
//						return "<a href='javascript:void(0)' style='color:red;' onclick='reeditCost(\"" + row.id + "\")' title='������±༭����'>" + moneyFormat2(v) + "</a>";
					}else{
						return moneyFormat2(v);
					}
				}
			},{
				name : 'inWorkRate',
				display : 'Ͷ�빤������',
				sortable : true,
				width : 70,
				process : function (v){
					return moneyFormat2(v)+'%';
				}				
			},{
				name : 'confirmName',
				display : 'ȷ����',
				sortable : true,
				width : 80,
				hide : true
			},{
				name : 'confirmDate',
				display : 'ȷ������',
				sortable : true,
				width : 70,
				hide : true
			},{
				name : 'thisActivityProcess',
				display : '�����������',
				sortable : true,
				width : 80,
				hide : true
			},{
				name : 'thisProjectProcess',
				display : '������Ŀ����',
				sortable : true,
				width : 80,
				hide : true
			}
		],
		buttonsEx : buttonsArr,
		menusEx : [{
			text : '�鿴��־',
			icon : 'view',
			action : function(row, rows, grid) {
				showOpenWin("?model=engineering_worklog_esmworklog&action=toView&id=" + row.id + '&skey=' + row.skey_ ,1,750,1150);
			}
		}, {
			text : '�鿴�ܱ�',
			icon : 'view',
			action : function(row, rows, grid) {
				showOpenWin("?model=engineering_worklog_esmweeklog&action=init&perm=view&id="
						+ row.weekId );
			}
		}, {
			text : 'ȡ�����',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.confirmStatus == "1" && $("#unauditLimit").val() == "1") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				if(confirm('ȷ��Ҫȡ����־�������')){
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_worklog_esmworklog&action=unauditLog",
					    data: {"id" : row.id},
					    async: false,
					    success: function(data){
					   	   if(data == '1'){
								alert('�����ɹ�');
								show_page();
							}else{
								alert(data);
							}
						}
					});
				}
			}
		}],
		//��������
		comboEx : [{
			text : '���״̬',
			key : 'confirmStatus',
			data : [{
				text : '�����',
				value : '1'
			}, {
				text : 'δ���',
				value : '0'
			}]
		}],
		searchitems : [{
			display : '�����',
			name : 'executionDateSearch'
		}, {
			display : '��������',
			name : 'activityNameSearch'
		}, {
			display : '��Ŀ����',
			name : 'projectNameSearch'
		}, {
			display : '��д��',
			name : 'createNameSearch'
		}],
		sortorder : "DESC",
		sortname : "executionDate desc,activityName"
	});
});