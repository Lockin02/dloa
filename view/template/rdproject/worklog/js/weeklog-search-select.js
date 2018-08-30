
$().ready(function() {
	$.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            alert(msg);
        },
        onsuccess: function() {
            if ($("#overDate").val() < $("#beginDate").val()) {
            	alert('��ʼʱ�䲻�ܴ�����ֹʱ��');
                return false;
            } else {
                return true;
            }
        }
    });

	$("#personNames").yxselect_user({
		hiddenId : 'personIds',
		mode : 'check'
	});

	$("#departmentNames").yxselect_dept({
		hiddenId : 'departmentIds',
		mode : 'check'
	});

    $("#beginDate").formValidator({
        onshow: "��ѡ��ƻ���ʼ����",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }); //.defaultPassed();

    $("#overDate").formValidator({
        onshow: "��ѡ��ƻ���������",
        onfocus: "��ѡ������",
        oncorrect: "����������ںϷ�"
    }).inputValidator({
        min: "1900-01-01",
        max: "2100-01-01",
        type: "date",
        onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
    }); //.defaultPassed();

	//��Ŀ���������ʼ��
	if (!Ext.getCmp('projectComboxGrid')) {
		/** ***************************��Ⱦ��Ŀ����**************************** */
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
			gridName : 'projectName',// ����������ʾ������
			gridValue : 'id',
			hiddenFieldId : 'projectIds',
			myGrid : projectGrid
		});
	}

	function thisMonth(){
		 var d, s;

	    // ���� Date ����
	    d = new Date();
	    s = d.getFullYear() + "-";
	    s += ("0"+(d.getMonth()+1)).slice(-2) + "-01";

	    return s;
    }

    $("#beginDate").val(thisMonth());

    $("#overDate").val(formatDate(new Date()));
})