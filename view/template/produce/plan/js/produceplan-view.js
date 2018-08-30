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
			display : '��  ��',
			name : 'process',
			width : '8%'
		},{
			display : '��Ŀ����',
			name : 'processName',
			width : '15%',
			align : 'left'
		},{
			display : '����ʱ�䣨�룩',
			name : 'processTime',
			width : '8%'
		},{
			display : '������',
			name : 'recipient',
			width : '10%',
			align : 'left'
		},{
			display : '������ID',
			name : 'recipientId',
			type : 'hidden'
		},{
			display : '����ʱ��',
			name : 'recipientTime',
			width : '8%'
		},{
			display : '���ʱ��',
			name : 'finishTime',
			width : '8%'
		},{
			display : '�ϸ�����',
			name : 'qualifiedNum',
			width : '5%'
		},{
			display : '���ϸ�����',
			name : 'unqualifiedNum',
			width : '6%'
		},{
			display : '�������κ�',
			name : 'productBatch',
			width : '10%'
		},{
			display : '��ע',
			name : 'remark',
			width : '15%',
			align : 'left'
		}]
	});

	//�ӱ�����
	var configProductObj = $("#configProduct");
	configProductObj.yxeditgrid({
		url : '?model=produce_task_configproduct&action=listJson',
		param : {
			taskId : $("#taskId").val(),
			dir : 'ASC'
		},
		type : 'view',
		colModel : [{
			display : '����ID',
			name : 'productId',
			type : 'hidden'
		},{
			display : '��������',
			name : 'productCode'
		},{
			display : '��������',
			name : 'productName',
			type : 'hidden'
		},{
			display : '����ͺ�',
			name : 'pattern',
			type : 'hidden'
		},{
			display : '��λ����',
			name : 'unitName',
			type : 'hidden'
		}],
		event : {
			reloadData : function (p ,g ,data) {
				$.each(data ,function (i ,v) {
					var tableHtml = '<div style="overflow-x:scroll;"><fieldset>'
								+ '<legend class="legend" onclick="showAndHideDiv(\'productConfigInfoImg' + i + '\',\'productConfigInfo' + i + '\')">' + v['productCode'] + '���� �� ' + v['num'] + '&nbsp;'
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
			display : '��������',
			name : 'proType'
		},{
			display : '���ϱ���',
			name : 'productCode',
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
	
	//��ʼ����ӡ����
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

//��ӡ�¼�
function changePrintCount(id){
	if($("#printCount").val() > 0 && !confirm("�üƻ������д�ӡ��¼��ȷ�����´�ӡ��")){
		return false;
	}else{
		//���µ��ݴ�ӡ����
		if(printBatch('table')){
			$.ajax({
			    type: "POST",
			    url: "?model=produce_plan_produceplan&action=changePrintCountId",
			    data: {"id" : id},
			    async: false,
			    success: function(data){
			    	if(data > 0){
			    		$("#printCount").val($("#printCount").val() + data);
			    		$("#isReprint").html('ע�⣺�ظ���ӡ���� ');
			    	}

			   		if(window.opener != undefined){
				    	window.opener.show_page();
				    }
				}
			});
		}
	}
}

//��ʼ���ظ���ӡ��Ϣ
function initPrintTips(){
	//�ظ���ӡ����
	if($("#printCount").val() > 0){
		$("#isReprint").html('ע�⣺�ظ���ӡ���� ');
	}
}