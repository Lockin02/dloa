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
                display: '��Ʒ��',
                name: 'newProLineName',
                tclass: 'txt'
            },
            {
                display: 'ִ������',
                name: 'exeDeptName',
                tclass: 'txt'
            },
            {
                display: '��Ʒ����',
                name: 'conProductName',
                tclass: 'readOnlyTxtNormal',
                readonly: true
            },
            {
                display: '��ƷId',
                name: 'conProductId',
                type: 'hidden'
            },
            {
                display: '��Ʒ����',
                name: 'conProductDes',
                tclass: 'txt'
            },
            {
                display: '��Ʒ�߱��',
                name: 'exeDeptCode',
                type: 'hidden'
            },
            {
                display: '����',
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
                display: '����',
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
                display: '���',
                name: 'money',
                tclass: 'txtshort',
                type: 'money'
//		}, {
//			display : '������',
//			name : 'warrantyPeriod',
//			tclass : 'txtshort'
            },
            {
                display: '��������Id',
                name: 'license',
                type: 'hidden'
            },
            {

                name: 'licenseButton',
                display: '��������',
                type: 'statictext',
                event: {
                    'click': function (e) {
                        var rowNum = $(this).data("rowNum");
                        // ��ȡlicenseid
                        var licenseObj = $("#productInfo_cmp_license" + rowNum);
                        var isView = $("#isView").val();
                        if (isView != 1) {
                            // ����
                            url = "?model=yxlicense_license_tempKey&action=toSelectWin" + "&licenseId=" + licenseObj.val()
                                + "&productInfoId="
                                + "productInfo_cmp_license"
                                + rowNum;
                            var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");

                            if (returnValue) {
                                licenseObj.val(returnValue);
                            }
                        } else {
                            // ��ȡ���ϼ�������id
                            var thisLicense = licenseObj.val();
                            showLicense(thisLicense);
                        }
                    }
                },
                html: '<input type="button"  value="��������"  class="txt_btn_a"  />',
                type: 'hidden'
            },
            {
                display: '��Ʒ����Id',
                name: 'deploy',
                type: 'hidden'
            },
            {
                name: 'deployButton',
                display: '��Ʒ����',
                process: function (v, row) {
                    if (row.deploy != "") {
                        return "<a href='javascript:void(0)' onclick='showGoods(\""
                            + row.deploy
                            + "\",\""
                            + row.conProductName
                            + "\")'>��Ʒ����</a>";
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
                        alert("��ѡ��ͬһִ������Ĳ�Ʒ��");
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
                        //ѡ���Ʒ��̬��Ⱦ��������õ�
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

    //��ʼʱ��
    var endDateOld = $("#endDateOld").val();
    if(endDateOld != ""){
        var newDate = addDay(31,endDateOld);
        $("#extensionDateTips").text("��ע��: ����ѡ����Ϊ: "+newDate+"��");
    }

    /**
     * ��֤��Ϣ
     */
    validate({
    });
})

function sub() {
    $("form").bind("submit", function () {
        var newProjectDays = $("#newProjectDays").val();
        var extensionDate = $("#extensionDate").val();
        if (extensionDate == "" && newProjectDays == "") {
            alert("����д��Ŀ���ڻ�������Ŀ���ڣ�")
            return false;
        }
        return true;

    })

}


//license�鿴����
function showLicense(thisVal) {
    if (thisVal == 0 || thisVal == '' || thisVal == 'undefined') {
        alert("û�м�������");
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


//��Ʒ�鿴����
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


//�ж�����ʱ����
function timeInterval() {
    //��ʼʱ��
    var endDateOld = $("#endDateOld").val();
    //����ʱ��
    var extensionDate = $("#extensionDate").val();
    if (endDateOld != '' && extensionDate != '') {
        if (extensionDate >= endDateOld) {
            var days = daysBetween(endDateOld, extensionDate);
            if (days > 31) {
                alert("������Ŀʱ�䲻�ó���һ���£�31�죩��");
                $("#extensionDate").val("");
            }
        } else {
            alert("�������ڲ���С�ڿ�ʼ���ڣ�");
            $("#extensionDate").val("");
        }
    }
}

//�жϹ���
function timeIntervals() {
    var newProjectDays = $("#newProjectDays");
    if (newProjectDays.val() != "") {
        if (!(/^(\+|-)?\d+$/.test(newProjectDays.val()))) {
            alert("������������");
            newProjectDays.val("");
        } else {
            if (newProjectDays.val() < 0 || newProjectDays.val() > 31) {
                alert("��Ŀ�����ӳ����ó���һ���£�31�죩��");
                newProjectDays.val("");
            }
        }
    }
}