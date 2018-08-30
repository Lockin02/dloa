var show_page = function(page) {
	$("#verifyDetailGrid").yxgrid("reload");
};
$(function() {
	$("#verifyDetailGrid").yxgrid({
	model : 'outsourcing_workverify_verifyDetail',
				isEditAction:false,
				isDelAction:false,
				isAddAction:false,
				isViewAction:false,
				showcheckbox:false,
				bodyAlign:'center',
				param:{'parentStateArr':'1,2,3,4,5'},
               	title : '������ȷ�ϵ���ϸ',
						//����Ϣ
						colModel : [{
         								display : 'id',
         								name : 'id',
         								sortable : true,
         								hide : true
							  },{
                    					name : 'parentId',
                  					display : '����id',
                  					width : 30,
                  					sortable : true,
         							hide : true
                              },{
                    					name : 'parentState',
                  					display : '����״̬',
                  					width : 30,
                  					sortable : true,
         							hide : true
                              },{
                    					name : 'parentCode',
                  					display : '���ݱ��',
                  					width : 150,
                  					sortable : true,
									process : function(v,row){
											return "<a href='#' onclick='showOpenWin(\"?model=outsourcing_workverify_workVerify&action=toView&id=" + row.parentId +"\")'>" + v + "</a>";
									}
                              },{
                    					name : 'officeName',
                  					display : '����',
                  					width : 30,
                  					sortable : true
                              },{
                    					name : 'province',
                  					display : 'ʡ��',
                  					width : 35,
                  					sortable : true
                              },{
                    					name : 'outsourcingName',
                  					display : '����',
                  					width : 75,
                  					sortable : true
                              },{
                    					name : 'projectName',
                  					display : '��Ŀ����',
                  					sortable : true
                              },{
                    					name : 'projectCode',
                  					display : '��Ŀ���',
                  					width : 120,
                  					sortable : true
                              },{
                    					name : 'outsourceContractCode',
                  					display : '�����ͬ���',
                  					sortable : true
                              },{
                    					name : 'outsourceSupp',
                  					display : '�����˾',
                  					sortable : true
                              },{
                    					name : 'principal',
                  					display : '������',
                  					sortable : true
                              },{
                    					name : 'scheduleTotal',
                  					display : '�ܽ���',
                  					width : 40,
                  					sortable : true
                              },{
                    					name : 'presentSchedule',
                  					display : '���ڽ���',
                  					width : 50,
                  					sortable : true
                              },{
                    					name : 'userName',
                  					display : '������Ա',
                  					width : 80,
                  					sortable : true
                              },{
                    					name : 'beginDate',
                  					display : '��ʼ����',
                  					width : 70,
                  					sortable : true
                              },{
                    					name : 'endDate',
                  					display : '��������',
                  					width : 70,
                  					sortable : true
                              },{
                    					name : 'feeDay',
                  					display : '�Ƽ�����',
                  					width : 50,
                  					sortable : true
                              },{
                    					name : 'beginDatePM',
                  					display : 'PM�˶Ա��ڿ�ʼ',
                  					sortable : true
                              },{
                    					name : 'endDatePM',
                  					display : 'PM�˶Ա��ڽ���',
                  					sortable : true
                              },{
                    					name : 'feeDayPM',
                  					display : 'PM�˶ԼƼ�����',
                  					sortable : true
                              },{
                    					name : 'managerAuditState',
                  					display : '��Ŀ��������״̬',
                  					sortable : true,
									process : function(v) {
										if (v == "1") {
											return "<span style='color:blue'>��</span>";
										} else {
											return "<span style='color:red'>-</span>";
										}
									}
                              },{
                    					name : 'serverAuditState',
                  					display : '����������״̬',
                  					sortable : true,
									process : function(v) {
										if (v == "1") {
											return "<span style='color:blue'>��</span>";
										} else {
											return "<span style='color:red'>-</span>";
										}
									}
                              },{
                    					name : 'areaAuditState',
                  					display : '�����ܼ�����״̬',
                  					sortable : true,
									process : function(v) {
										if (v == "1") {
											return "<span style='color:blue'>��</span>";
										} else {
											return "<span style='color:red'>-</span>";
										}
									}
                              }],
		// ���ӱ������
		subGridOptions : {
			url : '?model=outsourcing_workverify_NULL&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
						name : 'XXX',
						display : '�ӱ��ֶ�'
					}]
		},

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
					display : "����",
					name : 'officeName'
				},{
    					name : 'province',
  					display : 'ʡ��'
              },{
    					name : 'outsourcingName',
  					display : '����'
              },{
    					name : 'projecttName',
  					display : '��Ŀ����'
              },{
    					name : 'projectCode',
  					display : '��Ŀ���'
              },{
    					name : 'outsourceContractCode',
  					display : '�����ͬ���'
              },{
    					name : 'outsourceSupp',
  					display : '�����˾'
              },{
    					name : 'principal',
  					display : '������'
              }]
 		});
 });