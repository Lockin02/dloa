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
		gridName : 'templateName',// 下拉表格显示的属性
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
	        	alert('启动时间不能大于完成时间');
	            return false;
	        }
	        if($('#templateId').val() == ""){
	        	alert('选择操作错误，请重新选择模板');
	        	return false;
	        }
	    }
	});


	$("#templateName").formValidator({
	    onshow: "请选择计划模板",
	    oncorrect: "OK"
	}).inputValidator({
		min :1,
		empty:{leftempty:false,rightempty:false,emptyerror:"两边不能有空符号"},
	    onerror: "不能为空"
	}); //.defaultPassed();

	$("#planName").formValidator({
	    onshow: "请填写计划名称",
	    oncorrect: "OK"
	}).inputValidator({
		min :1,
		empty:{leftempty:false,rightempty:false,emptyerror:"两边不能有空符号"},
	    onerror: "不能为空"
	}); //.defaultPassed();

	$("#planDateStart").formValidator({
	    onshow: "请选择计划启动时间",
	    onfocus: "请选择日期",
	    oncorrect: "你输入的日期合法"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
	}); //.defaultPassed();

	$("#planDateClose").formValidator({
	    onshow: "请选择计划完成时间",
	    onfocus: "请选择日期",
	    oncorrect: "你输入的日期合法"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
    }).compareValidator({
		desid : "planDateStart",
		operateor : ">=",
		onerror : "计划完成日期不能小于计划开始日期"
	}); // .defaultPassed();
})

