var show_page = function(page) {
	$("#esmpkListGrid").yxeditgrid("reload");
};
$(function() {
	//������Ŀid
	var projectId = $("#projectId").val();
	//��񲿷�
	$("#esmpkListGrid").yxeditgrid({
		url: "?model=engineering_project_esmproject&action=PKInfoJson",
		param : {
			"projectId" : projectId
		},
		isAddAction : false,
		isDelAction : false,
		noCheckIdValue : 'noId',
		isOpButton : false,
		type : 'view',
		isAddOneRow : false,
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				type : 'hidden'
			}, {
				name : 'projectCode',
				display : 'PK��Ŀ��',
				sortable : true,
				width : 140,
				process : function(v,row){
					if(row.id != 'noId'){
						return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ +"\",1," + row.id + ")'>" + v + "</a>";
					}else{
						return v;
					}
				}
			}, {
				name : 'projectName',
				display : '��Ŀ����',
				width : 140
			}, {
				name : 'productLine',
				display : '��Ʒ�߱��',
				type : 'hidden'
			}, {
				name : 'productLineName',
				display : 'ִ������',
				width : 80
			}, {
				name : 'status',
				display : '״̬���',
				type : 'hidden'
			}, {
				name : 'statusName',
				display : '״̬',
				width : 60
			}, {
				name : 'managerId',
				display : '��Ŀ����id',
				type : 'hidden'
			}, {
				name : 'managerName',
				display : '��Ŀ����',
				width : 80
			}, {
				name : 'planBeginDate',
				display : 'Ԥ�ƿ�ʼ����',
				width : 80
			}, {
				name : 'planEndDate',
				display : 'Ԥ�ƽ�������',
				width : 80
			}, {
				name : 'expectedDuration',
				display : 'Ԥ�ƹ���',
				width : 60
			}, {
				name : 'actBeginDate',
				display : 'ʵ�ʿ�ʼ����',
				width : 80
			}, {
				name : 'actEndDate',
				display : 'ʵ�ʽ�������',
				width : 80
			}, {
				name : 'actDuration',
				display : 'ʵ�ʹ���',
				width : 60
			}, {
				name : 'budgetAll',
				display : 'Ԥ��',
				process : function(v,row) {
					return moneyFormat2(v);
				},
				width : 80
			}, {
				name : 'feeAll',
				display : '����',
				process : function(v,row) {
					return moneyFormat2(v);
				},
				width : 80
			}
		],
		event : {
			'reloadData' : function(e, g, data){
				if(data && data.length != 0){
                    var budgetAll = 0;
                    var feeAll = 0;
                    for(var i = 0; i < data.length; i++){
                        budgetAll = accAdd(budgetAll,data[i].budgetAll,2);
                        feeAll = accAdd(feeAll,data[i].feeAll,2);
                    }
                    $("#esmpkListGrid tbody").append("<tr class='tr_count'><td></td><td>�� ��</td><td colspan='10'></td><td>"+moneyFormat2(budgetAll)+"</td><td>"+moneyFormat2(feeAll)+"</td></tr>");
				}else{
                    $("#esmpkListGrid tbody").append("<tr><td colspan='14'>-- ������ؼ�¼ --</td></tr>");
                }
			}
		}
	});
});