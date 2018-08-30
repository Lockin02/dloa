$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
		$("#department1").hide();
	}
	//从表配置
	$("#productCode").yxcombogrid_productconfig({
			hiddenId : 'productId',
			nameCol : 'productCode',
	    	isFocusoutCheck : false,
			gridOptions : {
				param: {
					taskId: $("#id").val(),
					isMeetProduction: '1'
				},
			event: {
				row_dblclick: function (e, row, data) {
					$("#productId").val(data.productId);
					$("#productName").val(data.productName);
					$("#pattern").val(data.pattern);
					$("#unitName").val(data.unitName);
					$("#configId").val(data.id); //配置清单id
					$("#applyDocItemId").val(data.planId); //配置清单id
					var num = data.num - data.planNum;
					if (num > 0) {
						$("#planNum").val(num).blur(function () {
							if ($(this).val() > num) {
								alert('任务数量超过可下达任务数量！');
								$(this).val(num);
							}
						});


					//加载配置
					$("#productConfigInfo" + 0).parent().parent().remove();
					$("#processInfo" + 0).parent().parent().remove();

					var tableHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
								+ '<legend class="legend" onclick="showAndHideDiv(\'productConfigInfoImg' + 0 + '\',\'productConfigInfo' + 0 + '\')">' + data.productCode + '配置'
								+ '<img src="images/icon/info_up.gif" id="productConfigInfoImg' + 0 + '"/></legend>'
								+ '<div id="productConfigInfo' + 0 + '"></div></fieldset></tr></table></div>';
					var processHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
												+ '<legend class="legend" onclick="showAndHideDiv(\'processInfoImg' + 0 + '\',\'processInfo' + 0 + '\')">' + data.productCode + '工序'
												+ '<img src="images/icon/info_up.gif" id="processInfoImg' + 0 + '"/></legend>'
												+ '<div id="processInfo' + 0 + '"></div></fieldset></tr></table></div>';
					$("#productInfo").append(tableHtml);
					$("#productInfo").append(processHtml);
					var configInfoObj = $("#productConfigInfo" + 0);
					var tableHead = getTableHead(this.value);
					configInfoObj.yxeditgrid({
						url : '?model=produce_task_taskconfigitem&action=tableJson',
						param : {
							taskId : $("#id").val(),
							configCode :data.productCode,
							dir : 'ASC'
						},
						type : 'view',
						colModel : tableHead
					});
					//加载工序
					addProcessequ(data.id,0);
					} else {
						$("#planNum").val(0);
						alert('可下达任务数量为0！');
					}

					$('#fiel_process').show();
					templateData($("#id").val(),data.productCode);
				}
			}
			}
	});
});

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
		objName: 'produceplan[process]',
		url: '?model=produce_task_processequ&action=listJson',
		param: {
			parentId: id,
			dir: 'ASC'
		},
//		isFristRowDenyDel: true,
//		realDel :true,
		type:'view',
		colModel: [{
			display: '工  序',
			name: 'process',
			width: '15%',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
//			validation: {
//				required: true
//			}
		}, {
			display: '项目名称',
			name: 'processName',
			width: '30%',
			validation: {
				required: true
			},
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display: '工序时间（秒）',
			name: 'processTime',
			width: '10%',
			validation: {
				required: true,
				custom: ['percentageNum']
			},
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display: '接收人',
			name: 'recipient',
			width: 180,
			readonly: true,
			tclass : 'readOnlyTxtShort'
		}, {
			display: '接收人ID',
			name: 'recipientId',
			type: 'hidden'
		}, {
			display: '备注',
			name: 'remark',
			type: 'textarea',
			width: '20%',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}]
	});
}

function templateData(id,code){
	$('#templateData').html('');
	$('#fiel_classify').show();
	var itemsObj = $("#templateData");
	var url = '?model=produce_task_producetask&action=product&id=' + id + '&code=' + code;
	$('#templateData').yxeditgrid({
		objName : 'producetask[classify]',
		type:'view',
		url:url,
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '物料类型Id',
			name : 'proTypeId',
			type : 'hidden'
		},{
			display : '物料类型',
			name : 'proType',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '物料编码',
			name : 'productCode',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '规格型号',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '单位名称',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '数量',
			name : 'num',
			tclass : 'readOnlyTxtShort',
			validation : {
				custom : ['onlyNumber']
			},
			readonly : true
		}]
	});
}