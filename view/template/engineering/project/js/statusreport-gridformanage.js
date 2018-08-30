var show_page = function() {
	$("#statusreportGrid").yxgrid("reload");
};

$(function() {
	$("#statusreportGrid").yxgrid({
		model : 'engineering_project_statusreport',
		action : 'jsonForProject',
		title : '��Ŀ�ܱ�    <span style="color:red">��ܰ��ʾ����ȷ����Ŀ���Ա�����ύ��־���������־���������ύ�ܱ���</span>',
		param : { "projectId" : $("#projectId").val() },
		isDelAction : false,
		isAddAction : false,
		usepager : false,
		sortname : 'weekNo',
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				hide : true
			}, {
				name : 'projectId',
				display : '��Ŀid',
				hide : true
			}, {
				name : '',
				display : '',
				align :'center',
				width : 40,
				process : function(v,row){
					if(row.ExaStatus == '���'){
						return "<img src='images/icon/cicle_green.png'/>";
					}else if(row.ExaStatus == '��������'){
						return "<img src='images/icon/cicle_blue.png'/>";
					}else{
						return "<img src='images/icon/cicle_grey.png'/>";
					}
				}
			}, {
				name : 'weekNo',
				display : '�ܴ�',
				sortable : true,
				width : 80
			}, {
				name : 'handupDate',
				display : '�㱨����',
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_statusreport&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\",1)'>" + v + "</a>";
				}
			}, {
				name : 'beginDate',
				display : '��ʼ����'
			}, {
				name : 'endDate',
				display : '��������'
			}, {
				name : 'projectProcess',
				display : '��Ŀ����',
				process : function(v){
                    return v != "" ? v + " %" : "";
				}
			}, {
				name : 'weekProcess',
				display : '���ܽ���',
				process : function(v){
                    return v != "" ? v + " %" : "";
				}
			}, {
				name : 'createName',
				display : '�ύ��',
				hide : true
			}, {
				name : 'status',
				display : '����״̬',
				datacode : 'XMZTBG',
				hide : true,
				width : 80
			}, {
				name : 'ExaStatus',
				display : '����״̬',
				width : 80
			}, {
				name : 'confirmName',
				display : '������'
			}, {
				name : 'ExaDT',
				display : '��������',
				hide : true
			}, {
				name : 'confirmDate',
				display : '��������'
			}, {
				name : 'createTime',
				display : '����ʱ��',
				width : 140
			}
		],
		toEditConfig : {
			showMenuFn : function(row) {
				return row.ExaStatus == "���ύ" || row.ExaStatus == '���';
			},
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showModalWin("?model=engineering_project_statusreport&action=toEdit&id=" + row[p.keyField] ,1,row.weekNo);
			}
		},
		toViewConfig : {
			showMenuFn : function(row) {
				return row.id *1 == row.id;
			},
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				if(row.id *1 == row.id){
					showModalWin("?model=engineering_project_statusreport&action=toView&id=" + row[p.keyField] ,1,row.weekNo);
				}
			}
		},
		buttonsEx : [{
			text : ' ˢ��',
			icon : 'edit',
			action : function(row,rows,idArr,g) {
				g.reload();
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
				text : ' ��д�ܱ�',
				icon : 'add',
				showMenuFn:function(row){
					return row.id *1 != row.id;
				},
				action : function(row, rows, rowIds, g) {
					showModalWin("?model=engineering_project_statusreport&action=toAdd&weekNo="
						+ row.weekNo
						+ "&projectId=" + $("#projectId").val(),1,row.weekNo);
				}
			},{
				text : ' �ύ����',
				icon : 'edit',
				showMenuFn:function(row){
					return row.ExaStatus == "���ύ" || row.ExaStatus == '���';
				},
				action : function(row, rows, rowIds, g) {
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_project_esmproject&action=getRangeId",
					    data: {'projectId' : row.projectId },
					    async: false,
					    success: function(data){
					   		if(data != ''){
								showThickboxWin('controller/engineering/project/statusreport_ewf.php?actTo=ewfSelect&billId='
									+ row.id + "&billArea=" + data
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							}else{
								showThickboxWin('controller/engineering/project/statusreport_ewf.php?actTo=ewfSelect&billId='
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							}
						}
					});
				}
			},{
				text : 'ɾ��',
				icon : 'delete',
				showMenuFn:function(row){
					return row.ExaStatus == "���ύ" || row.ExaStatus == '���';
				},
				action : function(rowData, rows, rowIds, g) {
					g.options.toDelConfig.toDelFn(g.options,g);
				}
			}
		]
	});
});