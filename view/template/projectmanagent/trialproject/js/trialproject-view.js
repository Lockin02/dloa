// 加载下拉列表
$(function () {
    // 转正项目初始化
    $("#turnProjectShow").html(initProjectCode($("#turnProject").val()));

    // 产品明细初始化
    $("#productInfo").yxeditgrid({
        objName: 'trialproject[items]',
        url: '?model=projectmanagent_trialproject_trialprojectEqu&action=listJson',
        title: '从表信息',
        param: {
            trialprojectId: $("#pid").val(),
            'dir': 'ASC'
        },
        type: "view",
        colModel: [
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
            },
            {
                display: '加密配置Id',
                name: 'license',
                type: 'hidden'
            },
            {

                name: 'licenseButton',
                display: '加密配置',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='javascript:void(0)' onclick='showLicense(\""
                            + row.license + "\")'>加密配置</a>";
                    }
                },
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
        ]
    });
});


//license查看方法
function showLicense(thisVal) {
    if (thisVal == 0 || thisVal == '' || thisVal == 'undefined') {
        alert("没有加密配置");
        return false;
    }
    var url = "?model=yxlicense_license_tempKey&action=toViewRecord"
            + "&id=" + thisVal
        ;

    var sheight = screen.height - 200;
    var swidth = screen.width - 70;
    var winoption = "dialogHeight:" + sheight + "px;dialogWidth:" + swidth + "px;status:yes;scroll:yes;resizable:yes;center:yes";

    showModalDialog(url, '', winoption);
}

//产品查看方法
function showGoods(thisVal, goodsName) {
    var url = "?model=goods_goods_properties&action=toChooseView"
            + "&cacheId=" + thisVal
            + "&goodsName=" + goodsName
        ;
    window.open(url, "", "width=900,height=500,top=200,left=200");
}