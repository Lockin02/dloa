var show_page = function(page) {
	$("#workflowGrid").yxgrid("reload");
};

/**
 * 进入审批页面
 */
function toAudit(task,id,code,Pid,name,code,isTemp,receiveStatus,isReceive){
	if(isReceive == '1' && receiveStatus == '0'){
		alert('请收单后,再做审批处理');
		return false;
	}
	location.href = "controller/common/workflow/ewf_index.php?actTo=ewfExam"
		+ "&taskId=" + task
		+ "&spid=" + id
		+ "&examCode=" + code
		+ "&billId=" + Pid
		+ "&formName=" + name
		+ "&code=" + code
		+ "&isTemp=" + isTemp
		;
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

/**
 * 收单
 */
function toReceive(task,id,code,Pid,name,code,isTemp){
	//if(confirm('确认收单吗？')){
		$.ajax({
		    type: "POST",
		    url: "?model=common_workflow_workflow&action=receiveForm",
		    data: {"spid" : id },
		    async: false,
		    success: function(data){
				if(data == "1"){
					//alert('收单成功');
					show_page();
				}else if(data == "-1"){
					alert('单据收单功能配置错误，请联系管理员');
				}
			}
		});
	//}
}

/**
 * 退单
 */
function toBack(task,id,code,Pid,name,code,isTemp){
	//if(confirm('确认退单吗？')){
		$.ajax({
		    type: "POST",
		    url: "?model=common_workflow_workflow&action=backForm",
		    data: {"spid" : id },
		    async: false,
		    success: function(data){
				if(data == "1"){
					//alert('退单成功');
					show_page();
				}else if(data == "-1"){
					alert('单据收单功能配置错误，请联系管理员');
				}
			}
		});
	//}
}

//获取工作流类型数组
var formTypeArr = [];

//获取工作流可批量审批类型
var batchAuditArr = []; //用于下拉过滤
var batchAuditArr2 = []; //用于判断

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

	//下拉类型数组
	var comboArr = [{
			text : '审批类型',
			key : 'formName',
			value : $("#selectedCode").val(),
			data : formTypeArr
		}
	];

	//批量操作数组
	var buttonsArr = [
	    {
			name : 'appendView',
			text : "查看",
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
	    },
	    {
			name : 'exam',
			text : "详细审批",
			icon : 'edit',
			action: function(row,rows,idArr ) {
				if(row){
					if(idArr.length > 1){
						alert('一个只能对一条审批单进行审批');
					}else{
						if(row.isReceive == '1' && row.receiveStatus == '0'){
							alert('请收单后,再做审批处理');
							return false;
						}
						location.href = "controller/common/workflow/ewf_index.php?actTo=ewfExam&taskId="
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
					}
				}else{
					alert('请先选择记录');
				}
			}
	    }
	];

	//批量数组转换
	function initBatchArr(){
		$.ajax({
		    type: "POST",
		    url: "?model=common_workflow_workflow&action=getBatchAudit",
		    data: "",
		    async: false,
		    success: function(data){
		   		batchAuditArr = eval( "(" + data + ")" );
			}
		});

		for(var i = 1; i < batchAuditArr.length ; i++){
			batchAuditArr2.push(batchAuditArr[i].value);
		}

		//下拉
		comboArr.push({
			text : '批审类型',
			key : 'formNames',
			data : batchAuditArr
		});

		//按钮
	    buttonsArr.shift();
	    buttonsArr.push({
			name : 'exam',
			text : "批量审批",
			icon : 'edit',
			action: function(row,rows,idArr ) {
				if(row){
					//taskId数组
					var taskIdArr = [];
					//spid数组
					var spidArr = [];
					//循环取数
					for(var i = 0;i < rows.length ; i++){
						//收单判断
						if(rows[i].isReceive == '1' && rows[i].receiveStatus == '0'){
							alert('审批单号为：'+ rows[i].task +' ,业务编号为：' + rows[i].objCode + ' 的单据还未收单，请收单后,再做审批处理');
							return false;
						}
						//判断业务是否在批量审批业务中
						if(batchAuditArr2.indexOf(rows[i].name) == -1){
							alert('审批单号为：'+ rows[i].task +' ,业务类型为：' + rows[i].name + ' 的单据暂不支持批量审批操作，可在右上角查询可批量审批的类型');
							return false;
						}

						taskIdArr.push( rows[i].task );
						spidArr.push( rows[i].id );
					}
					showThickboxWin('?model=common_workflow_workflow&action=toBatchAudit'
						+ "&taskIds=" + taskIdArr.toString()
						+ "&spids=" + spidArr.toString()
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
				}else{
					alert('请先选择记录');
				}
			}
	    });
	}

	if($("#batchAuditLimit").val() == "1"){
		//数组转换
		initBatchArr();
	}

	// 导出按钮
	var DCBtn = {
		name: 'export',
		text: "导出",
		icon: 'excel',
		action: function (row) {
			var colId = "";
			var colName = "";
			$("#workflowGrid_hTable").children("thead").children("tr")
				.children("th").each(function () {
				if ($(this).css("display") != "none"
					&& $(this).attr("colId") != undefined) {
					if($(this).attr("colId") != 'thisAction'){
						colName += $(this).children("div").html() + ",";
						colId += $(this).attr("colId") + ",";
					}
				}
			});

			var msg = $.ajax({
				url: '?model=common_workflow_workflow&action=setColInfoToSession',
				data: 'ColId=' + colId + '&ColName=' + colName + '&sType=workflowAuditingData',
				dataType: 'html',
				type: 'post',
				async: false
			}).responseText;

			if (msg == 1) {
				window.open("?model=common_workflow_workflow&action=exportData&sType=workflowAuditingData")
			}
		}
	};

	if($("#canExportLimit").val() == 1){
		buttonsArr.push(DCBtn);
	}

	//渲染列表
	$("#workflowGrid").yxgrid({
		model: 'common_workflow_workflow',
		action : 'auditingPageJson',
		title: '审批',
//		showcheckbox : false,
		isAddAction : false,
		isEditAction :false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'auditingGrid',
		isOpButton : false,
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
			process : function(v,row){
				if(v == "借用出库申请"){
					v = '借试用申请审批'
				}
				return "<a href='javascript:void(0)'onclick='toView(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>" + v  +"</a>" ;
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
			name: 'Pid',
			display: '源单id',
			sortable: true,
			hide : true
		},{
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
			name : 'thisAction',
			process : function(v,row){
				var url = "<a href='javascript:void(0)' onclick='toView(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>查看单据</a> |"
				+ " <a href='javascript:void(0)' onclick='toAudit(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\",\""+ row.receiveStatus + "\",\""+ row.isReceive + "\")'>审批</a>";

				if(row.isReceive == "1"){
					if(row.receiveStatus == "1"){
						url += " | <a href='javascript:void(0)' style='color:red;' onclick='toBack(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>退单</a>";
					}else{
						url += " | <a href='javascript:void(0)' onclick='toReceive(\""+ row.task + "\",\""+ row.id + "\",\""+ row.code + "\",\""+ row.Pid + "\",\""+ row.name + "\",\""+ row.code + "\",\""+ row.isTemp + "\")'>收单</a>";
					}
				}
				return url;
			},
			width : 110
		},{
			name: 'auditHistory',
			display: '审批历史',
			width : 300,
			process : function(v,row){
				return v;
			},
			hide : true
		}],
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
					);
				}
			},{
				text : '审批',
				icon : 'edit',
				action : function(row) {
					if(row.isReceive == '1' && row.receiveStatus == '0'){
						alert('请收单后,再做审批处理');
						return false;
					}
					location.href = "controller/common/workflow/ewf_index.php?actTo=ewfExam&taskId="
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
				}
			}
		],
        buttonsEx : buttonsArr,
		comboEx : comboArr,
		searchitems : [{
			display : '审批单号',
			name : 'taskSearch'
		},{
			display : '提交人',
			name : 'creatorNameSearch'
		},{
			display : '提交时间',
			name : 'startSearch'
		},{
			display : '单据摘要',
			name : 'infomationSearch'
		},{
			display : '业务编号',
			name : 'objCodeSearch'
		},{
			display : '项目编号',
			name : 'projectCodeSearch'
		},{
			display : '业务名称',
			name : 'objNameSearch'
		},{
			display : '业务客户',
			name : 'objCustomerNameSearch'
		},{
			display : '业务金额',
			name : 'objAmountSearch'
		},{
			display : '来源',
			name : 'costSourceTypeSrch'
		}
			// {
			// 	display : '导入补贴',
			// 	name : 'isImptSubsidySrch'
			// }
		]
	});

	// $("#formName").bind("change",function(i,n){
	// 	//设置选择值
	// 	$("#selectedCode").val($(this).val());
	// 	$.ajax({
	// 	    type: "POST",
	// 	    url: "?model=common_workflow_selectedsetting&action=changeSelectedCode",
	// 	    data: {"selectedCode" : $(this).val() , "gridId" : 'auditing'}
	// 	});
	// });
});