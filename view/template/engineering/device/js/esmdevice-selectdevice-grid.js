var show_page = function (page) {
    $("#esmdeviceGrid").yxgrid("reload");
};

//��ȡ��������������
var formTypeArr = [];

$(function () {
    $.ajax({
        type: "POST",
        url: "?model=engineering_device_esmdevice&action=getFormType",
        data: "",
        async: false,
        success: function (data) {
            formTypeArr = eval("(" + data + ")");
        }
    });

    var showcheckbox = $("#showcheckbox").val();
    var showButton = $("#showButton").val();

    var combogrid = window.dialogArguments[0];
    var p = combogrid.options;
    var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
    var titleVal = "˫��ѡ���豸";

    if (eventStr.row_dblclick) {
        var dbclickFunLast = eventStr.row_dblclick;
        eventStr.row_dblclick = function (e, row, data) {
            dbclickFunLast(e, row, data);
            window.returnValue = row.data('data');
            window.close();
        };
    } else {
        eventStr.row_dblclick = function (e, row, data) {
//			var returnVal = "normal";
//			if (p.checkNum) {
//				var flag = $.ajax({
//					type : "POST",
//					async : false,
//					url : "?model=engineering_device_esmdevice&action=ajaxFindSurplus",
//					dataType : "json",
//					data : {
//						"deviceId" : data['id']
//					},
//					success : function(result) {
//
//					}
//				}).responseText;
//				returnValue = eval("(" + flag + ")");
//
//				if (returnValue['surplus'] <= 0) {
//					var tipStr = "���豸����������㡿 \n  ���������" + returnValue['total']
//							+ "   ����������" + returnValue['borrow'] + "   ʣ��������"
//							+ returnValue['surplus'] + "\n";
//					if (returnValue['replacedIds'] == 'none') {
//						tipStr += "�޿��滻�豸,�Ƿ�ʹ�ø��豸";
//						var returnVal = "none";
//					} else {
//						tipStr += "  ���滻�豸 �� " + returnValue['replacedNames']
//								+ "\n\n";
//						tipStr += "�Ƿ���Ҫѡ���滻�豸";
//						var returnVal = "done";
//					}
//				}
//			}
//			if (returnVal == 'normal') {
//				window.returnValue = row.data('data');
//				window.close();
//			} else {
//				if (returnVal == 'none') {
//					if (confirm(tipStr)) {
//						window.returnValue = row.data('data');
//						window.close();
//					} else {
//						return;
//					}
//				} else {
//					if (confirm(tipStr)) {
//						var listGrid = $("#esmdeviceGrid").data('yxgrid');
//						listGrid.options.extParam['ids'] = returnValue['replacedIds'];
//						listGrid.reload();
//					} else {
//						return;
//					}
//				}
//			}
        };
    }

    var gridOptions = combogrid.options.gridOptions;

    $("#esmdeviceGrid").yxgrid({
        model: 'engineering_device_esmdevice',
        action: gridOptions.action,
        title: titleVal,
        showcheckbox: showcheckbox,
        param: gridOptions.param,
        isDelAction: false,
        isAddAction: false,
        isViewAction: false,
        isEditAction: false,
        isOpButton: false,
        // ����Ϣ
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'deviceType',
                display: '�豸����',
                sortable: true,
                width: 150
            },
            {
                name: 'device_name',
                display: '�豸����',
                sortable: true,
                width: 300
            },
            {
                name: 'unit',
                display: '��λ',
                sortable: true,
                width: 60
            }
        ],
        searchitems: [
            {
                display: '�豸����',
                name: 'device_nameSearch'
            }
        ],
        // ����״̬���ݹ���
        comboEx: [
            {
                text: "�豸����",
                key: 'typeid',
                data: formTypeArr
            }
        ],
        sortname: gridOptions.sortname,
        sortorder: gridOptions.sortorder,
        // ���¼����ƹ���
        event: eventStr
    });
});