$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
		$("#department1").hide();
	}
	//从表配置
	var configProductObj = $("#configProduct");
	configProductObj.yxeditgrid({
		url : '?model=produce_task_configproduct&action=listJson',
		param : {
			taskId : $("#id").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '物料ID',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料编号',
			name : 'productCode'
		},{
			display : '物料名称',
			name : 'productName'
		},{
			display : '规格型号',
			name : 'pattern'
		},{
			display : '数量',
			name : 'num'
		},{
			display : '单位名称',
			name : 'unitName',
			type : 'hidden'
		},{
			display : '确认情况',
			name : 'isMeetProduction',
			process : function(v,row) {
				if(row.remark != ''){
					if(v == '2'){
						return "<span class='red'>" + row.remark + "</span>";
					}else{
						return row.remark;
					}
				}else{
					if (v == '0') {
						return "未确认";
					} else if (v == '1') {
						return "满足生产";
					} else if (v == '2') {
						return "<span class='red'>不满足生产</span>";
					}
				}
			}
		}],
		event : {
			reloadData : function (data) {
				var productObj = configProductObj.yxeditgrid("getCmpByCol" ,'productCode');
				productObj.each(function (i) {
					var tableHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
								+ '<legend class="legend" onclick="showAndHideDiv(\'productConfigInfoImg' + i + '\',\'productConfigInfo' + i + '\')">' + this.value + '配置'
								+ '<img src="images/icon/info_up.gif" id="productConfigInfoImg' + i + '"/></legend>'
								+ '<div id="productConfigInfo' + i + '"></div></fieldset></tr></table></div>';
					var processHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
								+ '<legend class="legend" onclick="showAndHideDiv(\'processInfoImg' + i + '\',\'processInfo' + i + '\')">' + this.value + '工序'
								+ '<img src="images/icon/info_up.gif" id="processInfoImg' + i + '"/></legend>'
								+ '<div id="processInfo' + i + '"></div></fieldset></tr></table></div>';
					$("#productInfo").append(tableHtml);
					$("#productInfo").append(processHtml);
					var configInfoObj = $("#productConfigInfo" + i);
					var tableHead = getTableHead(this.value);
					configInfoObj.yxeditgrid({
						url : '?model=produce_task_taskconfigitem&action=tableJson',
						param : {
							taskId : $("#id").val(),
							configCode : this.value,
							dir : 'ASC'
						},
						type : 'view',
						colModel : tableHead
					});

					//加载工序
					addProcessequ(configProductObj.yxeditgrid("getCmpByRowAndCol" ,i ,"id").val(),i);
					templateData($("#id").val(),configProductObj.yxeditgrid("getCmpByRowAndCol" ,i ,"productCode").val())
				});
			}
		}
	});


});

function templateData(id,rowNum){

	var templateHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
			+ '<legend class="legend" onclick="showAndHideDiv(\'templateImg' + rowNum + '\',\'templateData' + rowNum + '\')">' + rowNum + '物料'
			+ '<img src="images/icon/info_up.gif" id="templateImg' + rowNum + '"/></legend>'
			+ '<div id="templateData' + rowNum + '"></div></fieldset></tr></table></div>';

	if ($("#productConfigInfo" + rowNum).length == 0) {
		$("#fiel_classify").show();
		$("#templateData").append(templateHtml);
	}else{
		$("#templateData" + rowNum).parent().remove();
		$("#templateData").append(templateHtml);
	}
	$('#fiel_classify').show();
	var itemsObj = $("#templateData" + rowNum);
	var url =  '?model=produce_task_producetask&action=product&id=' + id + '&code=' + rowNum;
	itemsObj.yxeditgrid({
		url:url,
		type : 'view',
		colModel : [{
			display : '物料类型',
			name : 'proType'
		},{
			display : '物料编码',
			name : 'productCode'
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
}

function getTableHead(configCode) {
	var tableHead = [];
	var data = $.ajax({
				type : 'POST',
				url : '?model=produce_task_taskconfig&action=listJson',
				data : {
					taskId : $("#id").val(),
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

//加载工序
function addProcessequ(id,number){
	var equTableObj = $("#processInfo"+number);
	equTableObj.yxeditgrid({
		objName: 'producetask[configPro]['+number+'][process]',
		url: '?model=produce_task_processequ&action=listJson',
		param: {
			parentId: id,
			dir: 'ASC'
		},
		type : 'view',
		colModel: [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '工  序',
			name : 'process',
			width : '15%'
		},{
			display : '项目名称',
			name : 'processName',
			width : '30%',
			align : 'left'
		},{
			display : '工序时间（秒）',
			name : 'processTime',
			width : '10%'
		},{
			display : '接收人',
			name : 'recipient',
			width : 180,
			align : 'left'
		},{
			display : '接收人ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '备注',
			name : 'remark',
			width : '20%',
			align : 'left'
		}]
	});
}