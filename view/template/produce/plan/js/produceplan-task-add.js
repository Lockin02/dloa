$(document).ready(function() {
	if ($("#projectName").val() != '') {
		$("#department0").hide();
		$("#department1").hide();
	}
	//�ӱ�����
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
					$("#configId").val(data.id); //�����嵥id
					$("#applyDocItemId").val(data.planId); //�����嵥id
					var num = data.num - data.planNum;
					if (num > 0) {
						$("#planNum").val(num).blur(function () {
							if ($(this).val() > num) {
								alert('���������������´�����������');
								$(this).val(num);
							}
						});


					//��������
					$("#productConfigInfo" + 0).parent().parent().remove();
					$("#processInfo" + 0).parent().parent().remove();

					var tableHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
								+ '<legend class="legend" onclick="showAndHideDiv(\'productConfigInfoImg' + 0 + '\',\'productConfigInfo' + 0 + '\')">' + data.productCode + '����'
								+ '<img src="images/icon/info_up.gif" id="productConfigInfoImg' + 0 + '"/></legend>'
								+ '<div id="productConfigInfo' + 0 + '"></div></fieldset></tr></table></div>';
					var processHtml = '<div style="overflow-x:scroll;"><table class="form_main_table"><tr><fieldset>'
												+ '<legend class="legend" onclick="showAndHideDiv(\'processInfoImg' + 0 + '\',\'processInfo' + 0 + '\')">' + data.productCode + '����'
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
					//���ع���
					addProcessequ(data.id,0);
					} else {
						$("#planNum").val(0);
						alert('���´���������Ϊ0��');
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

//���ع���
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
			display: '��  ��',
			name: 'process',
			width: '15%',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
//			validation: {
//				required: true
//			}
		}, {
			display: '��Ŀ����',
			name: 'processName',
			width: '30%',
			validation: {
				required: true
			},
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display: '����ʱ�䣨�룩',
			name: 'processTime',
			width: '10%',
			validation: {
				required: true,
				custom: ['percentageNum']
			},
			readonly : true,
			tclass : 'readOnlyTxtShort'
		}, {
			display: '������',
			name: 'recipient',
			width: 180,
			readonly: true,
			tclass : 'readOnlyTxtShort'
		}, {
			display: '������ID',
			name: 'recipientId',
			type: 'hidden'
		}, {
			display: '��ע',
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
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������Id',
			name : 'proTypeId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'proType',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '���ϱ���',
			name : 'productCode',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		},{
			display : '����ͺ�',
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '��λ����',
			name : 'unitName',
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		},{
			display : '����',
			name : 'num',
			tclass : 'readOnlyTxtShort',
			validation : {
				custom : ['onlyNumber']
			},
			readonly : true
		}]
	});
}