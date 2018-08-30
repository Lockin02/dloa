/*****************************��Ⱦ��������*****************************/
Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();

	var taskGrid = {
		xtype : 'taskinfocombogrid',
		urlAction : 'index1.php?model=rdproject_task_rdtask&action=myTask',
		initSearchFields:['user'],
		initSearchValues:[1],
		listeners : {
			'dblclick' : function(e) { // mydelAll();
				var record =this.getSelectionModel().getSelected();
				//alert(record.get('name'))
				 $("#chargeName").val(record.get('chargeName'));//������
				 $("#createName").val(record.get('createName'));//������
				 $("#updateTime").val(record.get('updateTime'));//�������ʱ��
				 $("#createTime").val(record.get('createTime'));//��������
				 $("#taskType").val(record.get('taskType'));//��������
				 $("#priority").val(record.get('priority'));//���ȼ�
				 $("#projectName").val(record.get('projectName'));//������Ŀ
				 $("#projectCode").val(record.get('projectCode'));
				 $("#projectId").val(record.get('projectId'));
				 $("#status").val(record.get('status'));//״̬
				 $("#taskStatus").val(record.get('status'));//״̬
				 $("#planEndDate").val(record.get('planEndDate'));//�ƻ��������
				 $("#planStartDate").val(record.get('planBeginDate'));//�ƻ���ʼ����
				 $("#appraiseWorkload").val(record.get('appraiseWorkload'));//���ƹ�����
				 $("#effortRate").val(record.get('effortRate'));//�����
				 $("#putWorkload").val(record.get('putWorkload'));//��Ͷ�빤����
				 $("#wlplanEndDate").val(record.get('planEndDate'));//Ԥ���������
				 $("#taskName").focus();
			}
		}
	};

	new Ext.ux.combox.MyGridComboBox({
		applyTo : 'taskName', //
		gridName : 'name',// ����������ʾ������
		gridValue : 'id',
		hiddenFieldId : 'taskId',
		myGrid : taskGrid
	});

});

$().ready(function(){
//	var thisDate = formatDate(new Date());
	$("#executionDate").val();
	$("#thisDateInput").val();
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        	return false;
        },
        onsuccess: function() {
        	workloadDay = $('#workloadDay').val();
			if (confirm("��д������Ϊ "+ workloadDay +" ?")) {
				return true;
			} else {
				return false;
			}
        }
    });

    $("#taskName").formValidator({
        onshow: "ѡ������",
        oncorrect: "OK"
    }).inputValidator({
    	min :1,
    	empty:{leftempty:false,rightempty:false,emptyerror:"���߲����пշ���"},
        onerror: "����Ϊ��"
    }); //.defaultPassed();

    $("#workloadDay").formValidator({
    	onshow:"�����빤����",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		max:24,
		type:"value",
		onerrormin:"�������ֵ������ڵ���0.1",
		onerror:"������0.1��24֮�䣬����������"
	});//.defaultPassed();

	$("#wlplanEndDate").formValidator({
        onshow: "��ѡ��Ԥ���������",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }); //.defaultPassed();

    $("#rdeffortRate").formValidator({
    	onshow:"����������ǰ�����",
    	oncorrect:"OK"
	}).inputValidator({
		min:0.1,
		max:100,
		type:"value",
		onerrormin:"�������ֵ������ڵ���0.1",
		onerror:"������0.1��100֮�䣬����������"
	});//.defaultPassed();

	 $("#executionDate").formValidator({
    	onshow: "��ѡ��ִ������",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }).compareValidator({
		desid : "thisDateInput",
		operateor : "<=",
		onerror : "ִ�����ڲ��ܴ��ڵ�ǰ����"
	}); //.defaultPassed();
})

function residualWorkload(workloadDay,appraiseWorkload,putWorkload,workloadSurplus){//����Ͷ�빤����,���ƹ�����,��Ͷ�빤����,ʣ�๤����
	if($("#"+appraiseWorkload).val() != "" && $("#"+workloadDay).val() != ""){
		//�ж�������Ͷ�빤�����͹��ƹ�����
		if($("#"+putWorkload).val() == "") vputWorkload = 0;
		else vputWorkload = $("#"+putWorkload ).val();
		//�ж��Ƿ������Ͷ�빤����
		var rs =parseFloat( $("#"+appraiseWorkload).val()) - parseFloat( $("#"+workloadDay).val()) - vputWorkload;
		$("#"+workloadSurplus).val(rs);

	}
}

function editWorkload(workloadDay,newWorkloadDay,appraiseWorkload,putWorkload,workloadSurplus){//ʵ�ʵ���Ͷ�빤����,ԭ����Ͷ�빤����,���ƹ�����,��Ͷ�빤����,ʣ�๤����
	if($("#"+appraiseWorkload).val() != "" && $("#"+workloadDay).val() != ""){
		//�ж�������Ͷ�빤�����͹��ƹ�����
		var rs =parseFloat( $("#"+appraiseWorkload).val()) - parseFloat( $("#"+putWorkload).val()) - parseFloat( $("#"+newWorkloadDay).val()) + parseFloat( $("#"+workloadDay).val());
		$("#"+workloadSurplus).val(rs);

	}
}