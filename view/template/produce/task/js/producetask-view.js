$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
		$("#department1").hide();
	}
	//�ӱ�����
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
			display : '����ID',
			name : 'productId',
			type : 'hidden'
		},{
			display : '���ϱ��',
			name : 'productCode'
		},{
			display : '��������',
			name : 'productName'
		},{
			display : '����ͺ�',
			name : 'pattern'
		},{
			display : '����',
			name : 'num'
		},{
			display : '��λ����',
			name : 'unitName',
			type : 'hidden'
		},{
			display : 'ȷ�����',
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
						return "δȷ��";
					} else if (v == '1') {
						return "��������";
					} else if (v == '2') {
						return "<span class='red'>����������</span>";
					}
				}
			}
		}],
		event : {
			reloadData : function (data) {
				var productObj = configProductObj.yxeditgrid("getCmpByCol" ,'productCode');
				productObj.each(function (i) {
					var tableHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
								+ '<legend class="legend" onclick="showAndHideDiv(\'productConfigInfoImg' + i + '\',\'productConfigInfo' + i + '\')">' + this.value + '����'
								+ '<img src="images/icon/info_up.gif" id="productConfigInfoImg' + i + '"/></legend>'
								+ '<div id="productConfigInfo' + i + '"></div></fieldset></tr></table></div>';
					var processHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
								+ '<legend class="legend" onclick="showAndHideDiv(\'processInfoImg' + i + '\',\'processInfo' + i + '\')">' + this.value + '����'
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

					//���ع���
					addProcessequ(configProductObj.yxeditgrid("getCmpByRowAndCol" ,i ,"id").val(),i);
					templateData($("#id").val(),configProductObj.yxeditgrid("getCmpByRowAndCol" ,i ,"productCode").val())
				});
			}
		}
	});


});

function templateData(id,rowNum){

	var templateHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
			+ '<legend class="legend" onclick="showAndHideDiv(\'templateImg' + rowNum + '\',\'templateData' + rowNum + '\')">' + rowNum + '����'
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
			display : '��������',
			name : 'proType'
		},{
			display : '���ϱ���',
			name : 'productCode'
		},{
			display : '��������',
			name : 'productName'
		},{
			display : '����ͺ�',
			name : 'pattern'
		},{
			display : '��λ����',
			name : 'unitName'
		},{
			display : '����',
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

//���ع���
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
			display : '��  ��',
			name : 'process',
			width : '15%'
		},{
			display : '��Ŀ����',
			name : 'processName',
			width : '30%',
			align : 'left'
		},{
			display : '����ʱ�䣨�룩',
			name : 'processTime',
			width : '10%'
		},{
			display : '������',
			name : 'recipient',
			width : 180,
			align : 'left'
		},{
			display : '������ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '��ע',
			name : 'remark',
			width : '20%',
			align : 'left'
		}]
	});
}