$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
	}

	$("#processInfo").yxeditgrid({
		url : '?model=produce_plan_planprocess&action=listJson',
		param : {
			planId : $("#id").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : '工  序',
			name : 'process',
			width : '8%'
		},{
			display : '项目名称',
			name : 'processName',
			width : '15%',
			align : 'left'
		},{
			display : '工序时间（秒）',
			name : 'processTime',
			width : '8%'
		},{
			display : '接收人',
			name : 'recipient',
			width : '10%',
			align : 'left'
		},{
			display : '接收人ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '接收时间',
			name : 'recipientTime',
			width : '8%'
		},{
			display : '完成时间',
			name : 'finishTime',
			width : '8%'
		},{
			display : '合格数量',
			name : 'qualifiedNum',
			width : '5%'
		},{
			display : '不合格数量',
			name : 'unqualifiedNum',
			width : '6%'
		},{
			display : '物料批次号',
			name : 'productBatch',
			width : '10%'
		},{
			display : '备注',
			name : 'remark',
			width : '15%',
			align : 'left'
		}]
	});

	//从表配置
	var configProductObj = $("#configProduct");
	configProductObj.yxeditgrid({
		url : '?model=produce_task_configproduct&action=listJson',
		param : {
			taskId : $("#taskId").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : '物料ID',
			name : 'productId',
			type : 'hidden'
		},{
			display : '配置名称',
			name : 'productCode'
		},{
			display : '物料名称',
			name : 'productName',
			type : 'hidden'
		},{
			display : '规格型号',
			name : 'pattern',
			type : 'hidden'
		},{
			display : '单位名称',
			name : 'unitName',
			type : 'hidden'
		}],
		event : {
			reloadData : function (p ,g ,data) {
				$.each(data ,function (i ,v) {
					var tableHtml = '<div style="overflow-x:scroll;"><fieldset>'
								+ '<legend class="legend" onclick="showAndHideDiv(\'productConfigInfoImg' + i + '\',\'productConfigInfo' + i + '\')">' + v['productCode'] + '配置 × ' + v['num'] + '&nbsp;'
								+ '<img src="images/icon/info_up.gif" id="productConfigInfoImg' + i + '"/></legend>'
								+ '<div id="productConfigInfo' + i + '"></div></fieldset></div>';

					$("#productInfo").append(tableHtml);
					var configInfoObj = $("#productConfigInfo" + i);
					var tableHead = getTableHead(v['productCode']);
					configInfoObj.yxeditgrid({
						url : '?model=produce_task_taskconfigitem&action=tableJson',
						param : {
							taskId : $("#taskId").val(),
							configCode : v['productCode'],
							dir : 'ASC'
						},
						type : 'view',
						colModel : tableHead
					});
				});
			}
		}
	});

	$('#templateData').yxeditgrid({
		url:'?model=produce_plan_produceplan&action=classify',
		param : {
			id : $("#taskId").val(),
			productCode : $("#productCode").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '物料类型',
			name : 'proType'
		},{
			display : '物料编码',
			name : 'productCode',
		},{
			display : '物料名称',
			name : 'productName'
		},{
			display : '规格型号',
			name : 'pattern'
		},{
			display : '单位名称',
			name : 'unitName'
		},{
			display : '数量',
			name : 'num'
		}]
	});
	
	//初始化打印提醒
	initPrintTips();
});

function getTableHead(configCode) {
	var tableHead = [];
	var data = $.ajax({
				type : 'POST',
				url : '?model=produce_task_taskconfig&action=listJson',
				data : {
					taskId : $("#taskId").val(),
					configCode : configCode,
					dir : 'ASC'
				},
				async : false
			}).responseText;
	data = eval("(" + data + ")");

	$.each(data ,function(k ,v) {
		tableHead.push({
			name : v.colCode,
			display : v.colName
		});
	});

	return tableHead;
}

//打印事件
function changePrintCount(id){
	if($("#printCount").val() > 0 && !confirm("该计划单已有打印记录，确定重新打印？")){
		return false;
	}else{
		//更新单据打印次数
		if(printBatch('table')){
			$.ajax({
			    type: "POST",
			    url: "?model=produce_plan_produceplan&action=changePrintCountId",
			    data: {"id" : id},
			    async: false,
			    success: function(data){
			    	if(data > 0){
			    		$("#printCount").val($("#printCount").val() + data);
			    		$("#isReprint").html('注意：重复打印单据 ');
			    	}

			   		if(window.opener != undefined){
				    	window.opener.show_page();
				    }
				}
			});
		}
	}
}

//初始化重复打印信息
function initPrintTips(){
	//重复打印提醒
	if($("#printCount").val() > 0){
		$("#isReprint").html('注意：重复打印单据 ');
	}
}