Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();

	var templatePlanGrid = {
		xtype : 'templatePlanComboGrid',
	initSearchFields : ['status'],
	initSearchValues: ['1'],
		listeners : {
			'dblclick' : function(e) { // mydelAll();
				var record =this.getSelectionModel().getSelected();
				 $("#templateName").focus();
			}
		}
	};

	new Ext.ux.combox.MyGridComboBox({
		applyTo : 'templateName',
		// renderTo : 'contractName',
		gridName : 'templateName',// ���������ʾ������
		gridValue : 'id',
		hiddenFieldId : 'templateId',
		myGrid : templatePlanGrid
	})
});


$().ready(function(){
	$.formValidator.initConfig({
		formid: "form1",
	    //autotip: true,
	    onerror: function(msg) {
	        //alert(msg);
	    	return false;
	    },
	    onsuccess: function() {
	    	if ($("#planDateClose").val() < $("#planDateStart").val()) {
	        	alert('����ʱ�䲻�ܴ������ʱ��');
	            return false;
	        }
	        if($('#templateId').val() == ""){
	        	alert('ѡ���������������ѡ��ģ��');
	        	return false;
	        }
	    }
	});


	$("#templateName").formValidator({
	    onshow: "��ѡ��ƻ�ģ��",
	    oncorrect: "OK"
	}).inputValidator({
		min :1,
		empty:{leftempty:false,rightempty:false,emptyerror:"���߲����пշ���"},
	    onerror: "����Ϊ��"
	}); //.defaultPassed();

	$("#planName").formValidator({
	    onshow: "����д�ƻ�����",
	    oncorrect: "OK"
	}).inputValidator({
		min :1,
		empty:{leftempty:false,rightempty:false,emptyerror:"���߲����пշ���"},
	    onerror: "����Ϊ��"
	}); //.defaultPassed();

	$("#planDateStart").formValidator({
	    onshow: "��ѡ��ƻ�����ʱ��",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
	}); //.defaultPassed();

	$("#planDateClose").formValidator({
	    onshow: "��ѡ��ƻ����ʱ��",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }).compareValidator({
		desid : "planDateStart",
		operateor : ">=",
		onerror : "�ƻ�������ڲ���С�ڼƻ���ʼ����"
	}); // .defaultPassed();
})

