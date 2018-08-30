var show_page = function(page) {
	$("#workflowGrid").yxgrid("reload");
};


/**
 * 进入审批页面
 */
function toAuditInfo(task,id,code,Pid,name,code,isTemp){
	showThickboxWin('controller/common/readview.php?itemtype='
		+ code
		+ '&pid='
        + Pid
		+ '&taskId='
		+ task
        + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
}

/**
 * 进入审批页面
 */
function toView(task,id,code,Pid,name,code,isTemp){
	showModalWin('?model=common_workflow_workflow&action=toViweObjInfo'
		+ "&taskId=" + task
		+ "&spid=" + id
		+ "&examCode=" + code
		+ "&billId=" + Pid
		+ "&formName=" + name
		+ "&code=" + code
		+ "&isTemp=" + isTemp
		);
}
//获取工作流类型数组
formTypeArr = [];

$(function() {
	$.ajax({
	    type: "POST",
	    url: "?model=common_workflow_workflow&action=getFormType",
	    data: "",
	    async: false,
	    success: function(data){
	   		formTypeArr = eval( "(" + data + ")" );
		}
	});

	$("#workflowGrid").yxgrid({
		model: 'common_workflow_workflow',
		action : 'auditedPageJson',
		title: '工作流',
		showcheckbox : true,
		isAddAction : false,
		isEditAction :false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'auditedGrid',
		//列信息
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide : true
		},{
			name: 'task',
			display: '审批单号',
			sortable: true,
			width : 70
		},
		{
			name: 'name',
			display: '审批类型',
			sortable: true,
			width : 120,
			process : function(v){
				switch(v){
					case '销售订单审批' : return '销售合同审批';break;
					case '借用出库申请' : return '借试用申请审批';break;
					default : return v;
				}
			}
		},
		{
			name: 'creatorName',
			display: '提交人',
			sortable: true,
			width : 110
		},
		{
			name: 'start',
			display: '提交时间',
			sortable: true,
			width : 130
		},
		{
			name: 'Result',
			display: '审批结果',
			sortable: true,
			process : function(v){
				if(v == 'ok'){
					return '通过';
				}else{
					return '不通过';
				}
			},
			width : 70
		},{
			name: 'Endtime',
			display: '审批时间',
			sortable: true,
			width : 130
		},{
			name: 'content',
			display: '审批意见',
			width : 180,
			hide : true
		}, {
			name: 'infomation',
			display: '单据摘要',
			sortable: true,
			width : 150
		},{
			display: '收单状态',
			name: 'receiveStatus',
			align : 'center',
			process : function(v,row){
				if(row.isReceive == "1"){
					if(v == "1"){
						return '已收单';
					}else{
						return '未收单';
					}
				}
			},
			width : 50,
			hide : true
		},{
			name: 'objCode',
			display: '业务编号',
			sortable: true,
			hide : true,
			width : 120
		},{
			name: 'costSourceType',
			display: '来源',
			sortable: true,
			width : 80
				// process : function(v,row){
				// 	var str = "";
				// 	if(v == '是'){
				// 		str = "补贴";
				// 	}else{
				// 		str = row.costSourceType;
				// 	}
				// 	return str;
				// }
		},{
			name: 'projectCode',
			display: '项目编号',
			sortable: true,
			width : 120
		},{
			name: 'objName',
			display: '业务名称',
			sortable: true,
			hide : true,
			width : 120
		},{
			name: 'objCustomer',
			display: '业务客户',
			sortable: true,
			hide : true,
			width : 120
		},{
			name: 'objAmount',
			display: '业务金额',
			sortable: true,
			hide : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		},{
			name: 'objUserName',
			display: '业务人员',
			sortable: true,
			hide : true,
			width : 80
		},{
			display: '操 作',
			align : 'center',
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='toView(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>查看单据</a> |"
				+ " <a href='javascript:void(0)' onclick='toAuditInfo(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>审批情况</a>";
			},
			width : 120
		}, {
			name: 'auditHistory',
			display: '审批历史',
			width : 300,
			process : function(v,row){
				return v;
			},
			hide : true
		}],
        buttonsEx : [
	        {
				name : 'appendView',
				text : "查看单据",
				icon : 'view',
				action: function(row,rows,idArr ) {
					if(row){
						if(idArr.length > 1){
							alert('一次只能选择一条单据进行查看');
						}else{
							showModalWin('?model=common_workflow_workflow&action=toViweObjInfo'
								+ '&taskId='
								+ row.task
								+ "&spid="
								+ row.id
								+ "&examCode="
								+ row.code
								+ "&billId="
								+ row.Pid
								+ "&formName="
								+ row.name
								+ "&code="
								+ row.code
								+ "&isTemp="
								+ row.isTemp
							);
						}
					}else{
						alert('请先选择记录');
					}
				}
	        }
        ],
		menusEx : [
			{
				text : '查看单据',
				icon : 'view',
				action : function(row,rows,grid){
					showModalWin('?model=common_workflow_workflow&action=toViweObjInfo'
						+ '&taskId='
						+ row.task
						+ "&spid="
						+ row.id
						+ "&examCode="
						+ row.code
						+ "&billId="
						+ row.Pid
						+ "&formName="
						+ row.name
						+ "&code="
						+ row.code
						+ "&isTemp="
						+ row.isTemp
						+ "&audited=1"
					);
				}
			},{
				text : '审批情况',
				icon : 'view',
				action : function(row) {
					showThickboxWin('controller/common/readview.php?itemtype='
						+ row.code
						+ '&pid='
			            + row.Pid
						+ '&taskId='
						+ row.task
			            + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			}
		],
		comboEx : [{
				text : '类型',
				key : 'formName',
				value : $("#selectedCode").val(),
				data : formTypeArr
			}
		],
		searchitems : [{
			display : '审批单号',
			name : 'taskSearch'
		},{
			display : '提交人',
			name : 'creatorNameSearch'
		},{
			display : '审批时间',
			name : 'endTimeSearch'
		},{
			display : '单据摘要',
			name : 'infomationsearch'
		},{
			display : '项目编号',
			name : 'projectCodeSearch'
		},{
			display : '来源',
			name : 'costSourceTypeSrch'
		}
		// ,{
		// 	display : '导入补贴',
		// 	name : 'isImptSubsidySrch'
		// }
		],
		sortname : 'Endtime'
	});

	// $("#formName").bind("change",function(i,n){
	// 	//设置选择值
	// 	$("#selectedCode").val($(this).val());
	// 	$.ajax({
	// 	    type: "POST",
	// 	    url: "?model=common_workflow_selectedsetting&action=changeSelectedCode",
	// 	    data: {"selectedCode" : $(this).val() , "gridId" : 'audited'}
	// 	});
	// });
});