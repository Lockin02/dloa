$(document).ready(function () {
    $("#productInfo").yxeditgrid({
        objName: 'trialproject[product]',
        url: '?model=projectmanagent_trialproject_trialprojectEqu&action=listJson',
        param: {
            'trialprojectId': $("#trialprojectId").val()
        },
        type: "view",
        tableClass: 'form_in_table',
        colModel: [
            {
                display: 'id',
                name: 'id',
                type: 'hidden'
            },
            {
                display: '产品线',
                name: 'newProLineName',
                tclass: 'txt'
            },
            {
                display: '执行区域',
                name: 'exeDeptName',
                tclass: 'txt'
            },
            {
                display: '产品名称',
                name: 'conProductName',
                tclass: 'readOnlyTxtNormal',
                readonly: true
            },
            {
                display: '产品Id',
                name: 'conProductId',
                type: 'hidden'
            },
            {
                display: '产品描述',
                name: 'conProductDes',
                tclass: 'txt'
            },
            {
                display: '产品线编号',
                name: 'exeDeptCode',
                type: 'hidden'
            },
            {
                display: '数量',
                name: 'number',
                tclass: 'txtshort',
                type: 'money',
                event: {
                    blur: function () {
                        countAll($(this).data("rowNum"));
                    }
                }
            },
            {
                display: '单价',
                name: 'price',
                tclass: 'txtshort',
                type: 'moneySix',
                event: {
                    blur: function () {
                        countAll($(this).data("rowNum"));
                    }
                }
            },
            {
                display: '金额',
                name: 'money',
                tclass: 'txtshort',
                type: 'money'
//		}, {
//			display : '保修期',
//			name : 'warrantyPeriod',
//			tclass : 'txtshort'
            },
            {
                display: '加密配置Id',
                name: 'license',
                type: 'hidden'
            },
            {

                name: 'licenseButton',
                display: '加密配置',
                type: 'statictext',
                event: {
                    'click': function (e) {
                        var rowNum = $(this).data("rowNum");
                        // 获取licenseid
                        var licenseObj = $("#productInfo_cmp_license" + rowNum);
                        var isView = $("#isView").val();
                        if (isView != 1) {
                            // 弹窗
                            url = "?model=yxlicense_license_tempKey&action=toSelectWin" + "&licenseId=" + licenseObj.val()
                                + "&productInfoId="
                                + "productInfo_cmp_license"
                                + rowNum;
                            var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");

                            if (returnValue) {
                                licenseObj.val(returnValue);
                            }
                        } else {
                            // 获取物料加密配置id
                            var thisLicense = licenseObj.val();
                            showLicense(thisLicense);
                        }
                    }
                },
                html: '<input type="button"  value="加密配置"  class="txt_btn_a"  />',
                type: 'hidden'
            },
            {
                display: '产品配置Id',
                name: 'deploy',
                type: 'hidden'
            },
            {
                name: 'deployButton',
                display: '产品配置',
                process: function (v, row) {
                    if (row.deploy != "") {
                        return "<a href='javascript:void(0)' onclick='showGoods(\""
                            + row.deploy
                            + "\",\""
                            + row.conProductName
                            + "\")'>产品配置</a>";
                    }
                }
            }
        ],
        isAddOneRow: false,
        event: {
            'clickAddRow': function (e, rowNum, g) {
                url = "?model=contract_contract_product&action=toProductIframe";
                var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");
                if (returnValue) {
                    dataTip = $.ajax({
                        type: "POST",
                        url: "?model=goods_goods_goodsbaseinfo&action=getExeDeptCodeById",
                        data: {
                            "pid": returnValue.goodsId
                        },
                        async: false,
                        success: function (data) {

                        }
                    }).responseText;
                    if ($.inArray(dataTip, exedeptArr) == "-1" && exedeptArr.length != '0') {
                        alert("请选择同一执行区域的产品！");
                        g.removeRow(rowNum);
                    } else {
                        exedeptArr.push(dataTip);
                        g.setRowColValue(rowNum, "conProductId", returnValue.goodsId, true);
                        g.setRowColValue(rowNum, "conProductName", returnValue.goodsName, true);
                        g.setRowColValue(rowNum, "number", returnValue.number, true);
                        g.setRowColValue(rowNum, "price", returnValue.price, true);
                        g.setRowColValue(rowNum, "money", returnValue.money, true);
                        g.setRowColValue(rowNum, "warrantyPeriod", returnValue.warrantyPeriod, true);
                        g.setRowColValue(rowNum, "deploy", returnValue.cacheId, true);
                        g.setRowColValue(rowNum, "license", returnValue.licenseId, true);
                        var $tr = g.getRowByRowNum(rowNum);
                        $tr.data("rowData", returnValue);
                        //选择产品后动态渲染下面的配置单
                        getCacheInfo(returnValue.cacheId, rowNum);
                    }
                } else {
                    g.removeRow(rowNum);
                }

                return false;
            },
            'removeRow': function (e, rowNum, rowData) {
                if (typeof(rowData) != 'undefined') {
                    $("#goodsDetail_" + rowData.deploy).remove();
                }
            }
        }
    });
//	$.formValidator.initConfig({
//				theme : "Default",
//				submitOnce : true,
//				formID : "form1",
//				onError : function(msg, obj, errorlist) {
//					alert(msg);
//				}
//			});

})

function addDay(dayNumber, date) {
    var newDate = "";
    if(date != ""){
        date = Date.parse(date);
        var ms = dayNumber * (1000 * 60 * 60 * 24);
        newDate = new Date(date + ms);
        newDate.setMonth(newDate.getMonth()+1);
        newDate = newDate.Format("yyyy-MM-dd");
    }
    return newDate;
}

$(function () {

    //开始时间
    var endDateOld = $("#endDateOld").val();
    if(endDateOld != ""){
        var newDate = addDay(31,endDateOld);
        $("#extensionDateTips").text("（注意: 最大可选日期为: "+newDate+"）");
    }

    /**
     * 验证信息
     */
    validate({
    });
})

function sub() {
    $("form").bind("submit", function () {
        var newProjectDays = $("#newProjectDays").val();
        var extensionDate = $("#extensionDate").val();
        if (extensionDate == "" && newProjectDays == "") {
            alert("请填写项目延期或者是项目工期！")
            return false;
        }
        return true;

    })

}


//license查看方法
function showLicense(thisVal) {
    if (thisVal == 0 || thisVal == '' || thisVal == 'undefined') {
        alert("没有加密配置");
        return false;
    }
    url = "?model=yxlicense_license_tempKey&action=toViewRecord"
        + "&id=" + thisVal
    ;

    var sheight = screen.height - 200;
    var swidth = screen.width - 70;
    var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth + "px;status:yes;scroll:yes;resizable:yes;center:yes";

    showModalDialog(url, '', winoption);
}


//产品查看方法
function showGoods(thisVal, goodsName) {

    url = "?model=goods_goods_properties&action=toChooseView"
        + "&cacheId=" + thisVal
        + "&goodsName=" + goodsName
    ;

    var sheight = screen.height - 300;
    var swidth = screen.width - 200;
    var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth + "px;status:yes;scroll:yes;resizable:yes;center:yes";

//	showModalDialog(url, '',winoption);
    window.open(url, "", "width=900,height=500,top=200,left=200");

//	showThickboxWin("?model=goods_goods_properties&action=toChooseView"
//		+ "&cacheId=" + thisVal
//		+ "&goodsName=" + goodsName
//		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
}


//判断试用时间间隔
function timeInterval() {
    //开始时间
    var endDateOld = $("#endDateOld").val();
    //结束时间
    var extensionDate = $("#extensionDate").val();
    if (endDateOld != '' && extensionDate != '') {
        if (extensionDate >= endDateOld) {
            var days = daysBetween(endDateOld, extensionDate);
            if (days > 31) {
                alert("试用项目时间不得超过一个月（31天）！");
                $("#extensionDate").val("");
            }
        } else {
            alert("结束日期不能小于开始日期！");
            $("#extensionDate").val("");
        }
    }
}

//判断工期
function timeIntervals() {
    var newProjectDays = $("#newProjectDays");
    if (newProjectDays.val() != "") {
        if (!(/^(\+|-)?\d+$/.test(newProjectDays.val()))) {
            alert("请输入正整数");
            newProjectDays.val("");
        } else {
            if (newProjectDays.val() < 0 || newProjectDays.val() > 31) {
                alert("项目工期延长不得超过一个月（31天）！");
                newProjectDays.val("");
            }
        }
    }
}