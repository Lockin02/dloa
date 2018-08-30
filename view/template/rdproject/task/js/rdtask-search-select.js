
$().ready(function() {
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            alert(msg);
        },
        onsuccess: function() {
            if ($("#overDate").val() < $("#beginDate").val()) {
            	alert('起始时间不能大于终止时间');
                return false;
            } else {
                return true;
            }
        }
    });

    $("#beginDate").formValidator({
        onshow: "请选择计划开始日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
    }); //.defaultPassed();

    $("#overDate").formValidator({
        onshow: "请选择计划结束日期",
        onfocus: "请选择日期",
        oncorrect: "你输入的日期合法"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "日期必须在\"1900-01-01\"和\"2100-01-01\"之间"
    }); //.defaultPassed();


	$('#departmentRow').hide();
	$('#projectRow').hide();
	$('#person').bind('click', function() {
		$('#personRow').show();
		$('#departmentRow').hide();
		$('#departmentClear').trigger("click");
		$('#projectRow').hide();
		$('#projectNames').val("");
	});
	$('#department').bind('click', function() {
		$('#personRow').hide();
		$('#personClear').trigger("click");
		$('#departmentRow').show();
		$('#projectRow').hide();
		$('#projectIds').val("");
		$('#projectNames').val("");
	});
	$('#project').bind('click', function() {
		$('#personRow').hide();
		$('#personClear').trigger("click");
		$('#departmentRow').hide();
		$('#departmentClear').trigger("click");
		$('#projectRow').show();

		if (!Ext.getCmp('projectComboxGrid')) {
			/** ***************************渲染项目名称**************************** */
			Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
			Ext.QuickTips.init();

			var projectGrid = {
				xtype : 'projectinfocombogrid',
				listeners : {
					'dblclick' : function(e) { // mydelAll();
						var record = this.getSelectionModel()
								.getSelected();
					}
				}
			};

			new Ext.ux.combox.MyGridComboBox({
				id : 'projectComboxGrid',
				applyTo : 'projectNames', //
				gridName : 'projectName',// 下拉表格显示的属性
				gridValue : 'id',
				hiddenFieldId : 'projectIds',
				myGrid : projectGrid
			});
		}
	});

	function thisMonth(){
		 var d, s;

	    // 创建 Date 对象。
	    d = new Date();
	    s = d.getFullYear() + "-";
	    s += ("0"+(d.getMonth()+1)).slice(-2) + "-01";

	    return s;
    }

    $("#beginDate").val(thisMonth());

    $("#overDate").val(formatDate(new Date()));
})
