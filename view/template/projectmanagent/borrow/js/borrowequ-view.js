// 物料确认查看
var changeDetailObj = {};
$(function () {
    equConfig.type = "view";
    var rowNum = $('#rowNum').val();
    var contractId = $("#contractId").val();
    var linkId = $("#linkId").val();
    var isTemp = $("#isTemp").val();
    var originalId = $("#originalId").val();
    var objId = linkId;
    if (isTemp == 1) {
        objId = originalId;
    }

    // 获取变更明细记录
    var data = $.ajax({
        url: '?model=common_changeLog&action=pageJsonDetail',
        type: 'POST',
        data: {
            logObj: 'borrowequ',
            objId: objId,
            // detailType : "product",
            isLast: true,
            isGetUpdate: true,
            isTemp: 9//9代表无需审批标识
            // 只获取编辑的明细
        },
        async: false,
        dataType: 'json'
    }).responseText;
    data = eval("(" + data + ")");

    var key = "detailId";
    if (data.collection) {
        for (var i = 0; i < data.collection.length; i++) {
            var c = data.collection[i];
            if (c.detailId != 0) {
                var detailId = "d" + c[key];
                if (!changeDetailObj[detailId]) {
                    changeDetailObj[detailId] = [];
                }
                changeDetailObj[detailId].push(c);
            } else {
                var $cf = $("#" + c.changeField);
                var oldHtml = $cf.html();
                var newHtml = tranToChangeShow(c.oldValue, oldHtml);
                $cf.html(newHtml);
            }
        }
    }

    for (var i = 1; i <= rowNum; i++) {
        getGoodsProducts(i);
    }
    $("#isShowDelCheckbox").click(function () {
        var checked = this.checked;
        var url = "?model=projectmanagent_borrow_borrowequ&action=toEquView&&perm=view&linkId="
            + linkId + "&isShowDel=" + checked;
        if ($("#changeView").val()) {
            url += "&changeView=" + $("#changeView").val();
        }
        document.location = url
    });
    var checked = $("#isShowDel").val();
    if (checked == "false") {
        $("#isShowDelCheckbox").attr('checked', false);
    } else {
        $("#isShowDelCheckbox").attr('checked', true);
    }
    var newEquRowNum = rowNum * 1 + 1;
    getNoGoodsProducts(newEquRowNum);

    $("#selectChange").change(function () {
        var v = $(this).val();
        $(".oldValue").hide();
        $(".newValue").hide();
        $(".compare").hide();
        if (v == 1) {
            $(".newValue").show();
        } else if (v == 2) {
            $(".oldValue").show();
        } else {
            $(".oldValue").show();
            $(".newValue").show();
            $(".compare").show();
        }
    });
});
