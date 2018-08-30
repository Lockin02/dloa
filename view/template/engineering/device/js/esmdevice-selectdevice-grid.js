var show_page = function (page) {
    $("#esmdeviceGrid").yxgrid("reload");
};

//获取工作流类型数组
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
    var titleVal = "双击选择设备";

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
//					var tipStr = "【设备库存数量不足】 \n  库存总数：" + returnValue['total']
//							+ "   借用数量：" + returnValue['borrow'] + "   剩余数量："
//							+ returnValue['surplus'] + "\n";
//					if (returnValue['replacedIds'] == 'none') {
//						tipStr += "无可替换设备,是否使用该设备";
//						var returnVal = "none";
//					} else {
//						tipStr += "  可替换设备 ： " + returnValue['replacedNames']
//								+ "\n\n";
//						tipStr += "是否需要选择替换设备";
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
        // 列信息
        colModel: [
            {
                display: 'id',
                name: 'id',
                sortable: true,
                hide: true
            },
            {
                name: 'deviceType',
                display: '设备类型',
                sortable: true,
                width: 150
            },
            {
                name: 'device_name',
                display: '设备名称',
                sortable: true,
                width: 300
            },
            {
                name: 'unit',
                display: '单位',
                sortable: true,
                width: 60
            }
        ],
        searchitems: [
            {
                display: '设备名称',
                name: 'device_nameSearch'
            }
        ],
        // 审批状态数据过滤
        comboEx: [
            {
                text: "设备类型",
                key: 'typeid',
                data: formTypeArr
            }
        ],
        sortname: gridOptions.sortname,
        sortorder: gridOptions.sortorder,
        // 把事件复制过来
        event: eventStr
    });
});