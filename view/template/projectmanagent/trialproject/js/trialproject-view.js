// ���������б�
$(function () {
    // ת����Ŀ��ʼ��
    $("#turnProjectShow").html(initProjectCode($("#turnProject").val()));

    // ��Ʒ��ϸ��ʼ��
    $("#productInfo").yxeditgrid({
        objName: 'trialproject[items]',
        url: '?model=projectmanagent_trialproject_trialprojectEqu&action=listJson',
        title: '�ӱ���Ϣ',
        param: {
            trialprojectId: $("#pid").val(),
            'dir': 'ASC'
        },
        type: "view",
        colModel: [
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
            },
            {
                display: '��������Id',
                name: 'license',
                type: 'hidden'
            },
            {

                name: 'licenseButton',
                display: '��������',
                process: function (v, row) {
                    if (row.license != "") {
                        return "<a href='javascript:void(0)' onclick='showLicense(\""
                            + row.license + "\")'>��������</a>";
                    }
                },
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
        ]
    });
});


//license�鿴����
function showLicense(thisVal) {
    if (thisVal == 0 || thisVal == '' || thisVal == 'undefined') {
        alert("û�м�������");
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

//��Ʒ�鿴����
function showGoods(thisVal, goodsName) {
    var url = "?model=goods_goods_properties&action=toChooseView"
            + "&cacheId=" + thisVal
            + "&goodsName=" + goodsName
        ;
    window.open(url, "", "width=900,height=500,top=200,left=200");
}