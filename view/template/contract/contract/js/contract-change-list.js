/**
 * �������ʱ���ж�
 */

$(function () {
	//����һ��ȫ�ֱ����������жϵ�ǰӦ�ü���Դ����¼������ʱ�����¼
	//��ͬid����Դ��id�������Դ����¼�����������ʱ�����¼
	isTemp = $("#contractId").val() == $("#oldId").val() ? '0' : '1';
	
    var deliveryStatus = $("#DeliveryStatus").val();
    var outstockDate = $("#outstockDate").val();
    var Nowdate = formatDate(new Date());

    var days = DateDiff(outstockDate, Nowdate);
    // 2018-07-02 PMS 708 ���ۺ�ͬ��������6���£���Ʒ��Ϣ�����޸ĵ�����ȡ����
    // if ((deliveryStatus == 'YFH' || deliveryStatus == 'TZFH') && days > '180') {
    //     productListHtml("view");
    //     $("#changeTips").html("<img src='images/icon/icon095.gif' />���ѣ��˺�ͬ����ɷ������������£����涨�ڲ��ı��ͬ����ǰ���£����޷������Ʒ����");
    // } else {
        productListHtml("edit");
    // }


    $("#contractMoney_v").change(function () {
        deliveryCon();
    });
});

//����������޿��Ʒ���
function deliveryCon(){
    var oldMoney = moneyFormat2($("#oldMoney").val());
    // if (this.value != oldMoney) {
        $("#productInfo").productInfoGrid("remove");
        productListHtml("edit");
    // } else {
    //     $("#productInfo").productInfoGrid("remove");
    //     productListHtml("view");
    //     $("#changeTips").html("<img src='images/icon/icon095.gif' />���ѣ��˺�ͬ����ɷ������������£����涨�ڲ��ı��ͬ����ǰ���£����޷������Ʒ����");
    // }
}


// ���㷽��
function countAll(rowNum) {
    var beforeStr = "productInfo_cmp_";
    if ($("#" + beforeStr + "number" + rowNum).val() == ""
        || $("#" + beforeStr + "price" + rowNum + "_v").val() == "") {
        return false;
    } else {
        // ��ȡ��ǰ��
        var thisNumber = $("#" + beforeStr + "number" + rowNum).val();
        // alert(thisNumber)

        // ��ȡ��ǰ����
        var thisPrice = $("#" + beforeStr + "price" + rowNum + "_v").val();
        // alert(thisPrice)

        // ���㱾�н�� - ����˰
        var thisMoney = accMul(thisNumber, thisPrice, 2);
        setMoney(beforeStr + "money" + rowNum, thisMoney);
    }
}

function linkmanList(customerId, flag) {

    var listObj = {
        objName: 'contract[linkman]',
        isAddOneRow: false,
        url: '?model=contract_contract_linkman&action=listJson',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0'
        },
        tableClass: 'form_in_table',
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
        //     display: '�ͻ���ϵ��',
        //     name: 'linkmanName',
        //     tclass: 'txt',
        //     process: function ($input, rowData) {
        //         var rowNum = $input.data("rowNum");
        //         var g = $input.data("grid");
        //         $input.yxcombogrid_linkman({
        //             hiddenId: 'linkmanListInfo_cmp_linkmanId' + rowNum,
        //             isFocusoutCheck: false,
        //             gridOptions: {
        //                 showcheckbox: false,
        //                 param: {
        //                     'customerId': customerId
        //                 },
        //                 event: {
        //                     "row_dblclick": (function (rowNum) {
        //                         return function (e, row, rowData) {
        //                             var $telephone = g.getCmpByRowAndCol(
        //                                 rowNum, 'telephone');
        //                             $telephone.val(rowData.mobile);
        //                             var $QQ = g.getCmpByRowAndCol(rowNum, 'QQ');
        //                             $QQ.val(rowData.QQ);
        //                             var $email = g.getCmpByRowAndCol(rowNum,
        //                                 'Email');
        //                             $email.val(rowData.email);
        //                         }
        //                     })(rowNum)
        //                 }
        //             }
        //         });
        //     }
        // }, {
        //     display: '��ϵ��ID',
        //     name: 'linkmanId',
        //     type: 'hidden'
        // }, {
            display: '�ͻ���ϵ��',
            name: 'linkmanName',
            tclass: 'txt'
        },{
            display: '�绰',
            name: 'telephone',
            tclass: 'txt'
        }, {
            display: 'ְλ',
            name: 'position',
            tclass: 'txt'
        },{
            display: '����',
            name: 'Email',
            tclass: 'txt'
        }, {
            display: '��ע',
            name: 'remark',
            tclass: 'txt'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    };
    if (flag == 1) {
        listObj.url = '';
        listObj.param = '';
    }
    // �ͻ���ϵ��
    $("#linkmanListInfo").yxeditgrid(listObj);

    setTimeout(
        function(){
            var length = $("#linkmanListInfo").yxeditgrid("getCmpByCol", "telephone").length;
            if(length <= 0){
                $("#linkmanListInfo").yxeditgrid('addRow',1);
            }
        }, 300
    )
}

// ���ݽ���������ID��ѯ��Ӧ��ִ������
function chkBorrowEquExeNum(borrowEquId){
    var returnValue = $.ajax({
        type: 'POST',
        url: "?model=projectmanagent_borrow_borrow&action=getOriginalBorrowEquInfo",
        data: {
            id: borrowEquId
        },
        async: false,
        success: function (data) {
        }
    }).responseText;
    returnValue = eval("(" + returnValue + ")");
    return returnValue[0];
}

// ��ͬ�����ӱ�t
$(function () {

    linkmanList($("#customerId").val(), 0);
    // ��Ʒ�嵥


    //�տ��ƻ�
    $("#financialplanInfo").yxeditgrid({
        objName: 'contract[financialplan]',
        url: '?model=contract_contract_financialplan&action=listJson',
        tableClass: 'form_in_table',
        param: {
            'contractId': $("#contractId").val()
        },
        colModel: [{
            display: '����',
            name: 'planDate',
            type: 'date'
        }, {
            display: '��Ʊ���',
            name: 'invoiceMoney',
            tclass: 'txtshort',
            type: 'money'
        }, {
            display: '�տ���',
            name: 'incomeMoney',
            tclass: 'txtshort',
            type: 'money'
        }, {
            display: '��ע',
            name: 'remark',
            tclass: 'txtlong'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    });

    //������ת����
    $("#borrowConEquInfo").yxeditgrid({
        objName: 'contract[material]',
        url: '?model=contract_contract_equ&action=listJson',
        async: false,
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0',
            'isBorrowToorder': '1',
            'parentEquId': '0'
        },
        isAddOneRow: false,
        isAdd: false,
        tableClass: 'form_in_table',
        colModel: [{
            display: '������Ʒ����',
            name: 'conProduct',
            tclass: 'txt',
            type: 'hidden'
        }, {
            display: '�ӱ�Id',
            name: 'id',
            tclass: 'txt',
            type: 'hidden'
        }, {
            display: '������Ʒ',
            name: 'onlyProductId',
            type: 'select',
            tclass: 'txt',
            width: 100,
            options: [
                {
                    name: '..��ѡ���Ʒ..',
                    value: ''
                }
            ],
            event: {
                change: function () {
                    rowNum = $(this).data("rowNum");//�к�
                }
            }
        }, {
            display: '����Id',
            name: 'productId',
            tclass: 'txt',
            type: 'hidden'
        }, {
            display: '���ϱ��',
            name: 'productCode',
            tclass: 'readOnlyTxtNormal',
            readonly: 'readonly'
        }, {
            display: '��������',
            name: 'productName',
            tclass: 'readOnlyTxtNormal',
            readonly: 'readonly'
        }, {
            display: '�ͺ�/�汾',
            name: 'productModel',
            tclass: 'readOnlyTxtNormal',
            readonly: 'readonly'
        }, {
            display: '����',
            name: 'number',
            tclass: 'txtshort',
            event: {
                blur: function () {
                    var borrowEquId = $("#borrowConEquInfo_cmp_toBorrowequId"+$(this).data("rowNum")).val();
                    var borrowEqu = chkBorrowEquExeNum(borrowEquId);
                    var executedNum = borrowEqu.executedNum - borrowEqu.backNum;
                    if(isNaN($(this).val()) || parseInt($(this).val()) <= 0){
                        alert("���������0��������");
                        $(this).val(executedNum);
                    }else if(parseInt($(this).val()) > parseInt(executedNum)){
                        alert("ת��������������ڿ�����������Χ�ڡ�");
                        $(this).val(executedNum);
                    }
                }
            }
        }, {
            display: '����',
            name: 'price',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '���',
            name: 'money',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '������',
            name: 'warrantyPeriod',
            tclass: 'txtshort'
        }, {
            display: '������ת���۱�ʶ',
            name: 'isBorrowToorder',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '�����ñ�Id',
            name: 'toBorrowId',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '�����ô�ID',
            name: 'toBorrowequId',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            name: 'serialId',
            display: '���к�ID',
            type: 'hidden'
        }, {
            name: 'serialName',
            display: '���к�',
            tclass: 'readOnlyTxtNormal',
            readonly: 'readonly',
            process: function ($input, rowData, $tr, grid) {
                var inputId = $input.attr('id');
                var rownum = $input.data('rowNum');// �ڼ���
                var sid = grid.getCmpByRowAndCol(rownum, 'serialId').attr('id');
                var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='ѡ�����к�'>");
                $img.click(function (toBorrowId, productId, num, inputId, sid) {
                    return function () {
                        serialNum(toBorrowId, productId, num, inputId, sid);
                    }
                }(rowData.toBorrowId, rowData.productId, rowData.number, inputId, sid));
                $input.before($img)
            },
            event: {
                dblclick: function () {
                    var serial = $(this).val();
                    if (serial != "") {
                        showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialShow&serial='
                        + serial
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
                    }
                }
            }
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }],
        event: {
            beforeRemoveRow : function(e, rowNum, rowData, g) {
                if( rowData.executedNum>0 ){//������ѳ��ⲻ��ɾ
                    alert("�������Ѳ��ֳ��⣬��ֱֹ��ɾ���������˻����̣�");
                    g.isRemoveAction=false;
                    return false;
                }else if(rowData.issuedShipNum > 0){//���û���ѳ���,�����з����ƻ���Ҳ����ɾ
                    alert("���������´﷢���ƻ�����ֱֹ��ɾ����");
                    g.isRemoveAction=false;
                    return false;
                }
            },
            //������ת��������
            reloadData: function () {
                createProArr();
            }
        }

    });

    var ids = $("#ids").val();
    if (ids != '') {
        var returnValue = $.ajax({
            type: 'POST',
            url: "?model=projectmanagent_borrow_borrow&action=getBorrowequInfo",
            data: {
                ids: ids
            },
            async: false,
            success: function (data) {
            }
        }).responseText;
        returnValue = eval("(" + returnValue + ")");
        if (returnValue) {
            var g = $("#borrowConEquInfo").data("yxeditgrid");
            var rn = $("#borrowConEquInfo").yxeditgrid('getAllAddRowNum');
            var j = rn + 1;
            //ѭ���������
            for (var i = 0; i < returnValue.length; i++) {
                var canExeNum = returnValue[i].executedNum - returnValue[i].backNum;
                outJson = {
                    "productId": returnValue[i].productId,
                    "productCode": returnValue[i].productNo,
                    "productName": returnValue[i].productName,
                    "productModel": returnValue[i].productModel,
                    // "number": returnValue[i].executedNum - returnValue[i].backNum,
                    "number": canExeNum, //����Ĭ�ϴ�����ִ������
                    "price": returnValue[i].price,
                    "money": returnValue[i].price*canExeNum,
                    "warrantyPeriod": returnValue[i].warrantyPeriod,
                    "isBorrowToorder": 1,
                    "toBorrowId": returnValue[i].borrowId,
                    "toBorrowequId": returnValue[i].id
                };

                //��������
                $("#customerIdtest").val(returnValue[i].customerId);
                g.addRow(j, outJson);
                j++;
            }
        }
    }

    // ��Ʊ�ƻ�
    $("#invoiceListInfo").yxeditgrid({
        objName: 'contract[invoice]',
        url: '?model=contract_contract_invoice&action=listJson',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0'
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '��Ʊ���',
            name: 'money',
            tclass: 'txt'
        }, {
            display: '������',
            name: 'softMoney',
            tclass: 'txt'
        }, {
            display: '��Ʊ����',
            name: 'iType',
            type: 'select',
            datacode: 'FPLX'
        }, {
            display: '��Ʊ����',
            name: 'invDT',
            type: 'date'
        }, {
            display: '��Ʊ����',
            name: 'remark',
            tclass: 'txt'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    });
    // �տ�ƻ�
    $("#incomeListInfo").yxeditgrid({
        objName: 'contract[income]',
        url: '?model=contract_contract_receiptplan&action=listJson',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0'
        },
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '�տ���',
            name: 'money',
            tclass: 'txt'
        }, {
            display: '�տ�����',
            name: 'payDT',
            type: 'date'
        }, {
            display: '�տʽ',
            name: 'pType',
            tclass: 'txt'
        }, {
            display: '�տ�����',
            name: 'collectionTerms',
            tclass: 'txtlong'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    });
    //����
    $("#equInfo").yxeditgrid({
        objName: 'contract[equ]',
        url: '?model=contract_contract_equ&action=listJson',
        param: {
            'contractId': $("#contractId").val(),
            'isTemp': isTemp,
            'isDel': '0',
            'isBorrowToorder': '0'
        },
        tableClass: 'form_in_table',
//		isAddOneRow:false,
//		isAdd : false,
        colModel: [{
            display: 'id',
            name: 'id',
            type: 'hidden'
        }, {
            display: '���ϱ��',
            name: 'productCode',
            tclass: 'readOnlyTxtNormal',
            readonly: true,
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                var isEqu = g.getCmpByRowAndCol(rowNum, 'productCode').val();
                if (isEqu == '') {
                    $input.yxcombogrid_product({
                        hiddenId: 'itemTable_cmp_productId' + rowNum,
                        nameCol: 'productCode',
//					closeCheck : true,// �ر�״̬,����ѡ��
                        closeAndStockCheck: true,
                        width: 600,
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
                                        g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
                                        g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                        g.getCmpByRowAndCol(rowNum, 'number').val("1");
                                    }
                                })(rowNum)
                            }
                        }
                    });
                }
            }
        }, {
            display: '������Ʒ',
            name: 'onlyProductId',
            type: 'hidden',
        }, {
            display: '������ת���۱�ʶ',
            name: 'isBorrowToorder',
            tclass: 'txtshort',
            type: 'hidden'
        },{
            display: '����Id',
            name: 'productId',
            type: 'hidden'
        }, {
            display: '��������',
            name: 'productName',
            tclass: 'readOnlyTxtNormal',
            readonly: true,
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                var isEqu = g.getCmpByRowAndCol(rowNum, 'productCode').val();
                if (isEqu == '') {
                    $input.yxcombogrid_product({
                        hiddenId: 'itemTable_cmp_productId' + rowNum,
                        nameCol: 'productName',
//					closeCheck : true,// �ر�״̬,����ѡ��
                        closeAndStockCheck: true,
                        width: 600,
                        gridOptions: {
                            event: {
                                row_dblclick: (function (rowNum) {
                                    return function (e, row, rowData) {
                                        g.getCmpByRowAndCol(rowNum, 'productId').val(rowData.id);
                                        g.getCmpByRowAndCol(rowNum, 'productCode').val(rowData.productCode);
                                        g.getCmpByRowAndCol(rowNum, 'productModel').val(rowData.pattern);
                                        g.getCmpByRowAndCol(rowNum, 'number').val("1");
                                    }
                                })(rowNum)
                            }
                        }
                    });
                }
            }
        }, {
            display: '�ͺ�/�汾',
            name: 'productModel',
            tclass: 'readOnlyTxtNormal',
            readonly: true
        }, {
            display: '����',
            name: 'number',
            tclass: 'txtshort',
            event: {
                blur: function () {
                    countAll($(this).data("rowNum"), "equinfo");
                }
            }
        }, {
            display: '����',
            name: 'price',
            tclass: 'txtshort',
            type: 'money',
            event: {
                blur: function () {
                    countAll($(this).data("rowNum"), "equinfo");
                }
            }
        }, {
            display: '���',
            name: 'money',
            tclass: 'txtshort',
            type: 'money'
        }, {
            display: '��������Id',
            name: 'license',
            type: 'hidden'
        }, {
            display: '������ƷId',
            name: 'conProductId',
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
                    var licenseObj = $("#equInfo_cmp_license" + rowNum);
                    if (licenseObj.val() == '') {
                        // ����
                        url = "?model=yxlicense_license_tempKey&action=toSelectWin"
                        + "&productInfoId="
                        + "equInfo_cmp_license"
                        + rowNum;
                        var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");
                        if (returnValue) {
                            licenseObj.val(returnValue);
                        }
                    } else {
                        // ����
                        url = "?model=yxlicense_license_tempKey&action=toSelectChange" + "&licenseId=" + licenseObj.val()
                        + "&productInfoId="
                        + "equInfo_cmp_license"
                        + rowNum;
                        var returnValue = showModalDialog(url, '', "dialogWidth:1000px;dialogHeight:600px;");

                        if (returnValue) {
                            licenseObj.val(returnValue);
                        }
                    }
                }
            },
            html: '<input type="button"  value="��������"  class="txt_btn_a"  />'
        }, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }]
    });
});

// ������װ��Ʒѡ��
(function($) {
	// ��Ʒ�嵥
	$.woo.yxeditgrid.subclass('woo.productInfoGrid', {
		objName: 'contract[product]',
		url: '?model=contract_contract_product&action=listJson',
		tableClass: 'form_in_table',
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			display: '��Ʒ��',
			name: 'newProLineName',
			tclass: 'readOnlyTxtNormal',
			width: 80,
			readonly: true
		}, {
			display: '��Ʒ�߱��',
			name: 'newProLineCode',
			type: 'hidden'
		}, {
			display: 'ִ������',
			name: 'exeDeptId',
			type: 'select',
            emptyOption : true,
			datacode: 'GCSCX'
		}, {
			display: 'ִ������Name',
			name: 'exeDeptName',
			type: 'hidden'
		}, {
			display: '��Ʒ����',
			tclass: 'readOnlyTxtMiddle',
			name: 'proType',
			readonly: true
		}, {
			display: '��Ʒ����id',
			name: 'proTypeId',
			type: 'hidden'
		}, {
			display: 'proExeDeptId',
			name: 'proExeDeptId',
			type: 'hidden'
		}, {
			display: 'proExeDeptName',
			name: 'proExeDeptName',
			type: 'hidden'
		}, {
			display: 'newExeDeptCode',
			name: 'newExeDeptCode',
			type: 'hidden'
		}, {
			display: 'newExeDeptName',
			name: 'newExeDeptName',
			type: 'hidden'
		}, {
			display: '��Ʒ����',
			name: 'conProductName',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		},
        {
			display: '��ƷId',
			name: 'conProductId',
			type: 'hidden'
		}, {
			display: '��Ʒ����',
			name: 'conProductDes',
			tclass: 'txt'
		}, {
			display: '����',
			name: 'number',
			tclass: 'txtshort',
			event: {
				blur: function () {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '����',
			name: 'price',
			tclass: 'txtshort',
			type: 'money',
			event: {
				blur: function () {
					countAll($(this).data("rowNum"));
				}
			}
		}, {
			display: '���',
			name: 'money',
			tclass: 'txtshort',
			type: 'money'
		}, {
			display: 'ԭ��Ʒ����Id',
			name: 'orgDeploy',
			type: 'hidden'
		}, {
			display: '��Ʒ����Id',
			name: 'deploy',
			type: 'hidden'
		}, {
			name: 'deployButton',
			display: '��Ʒ����',
			type: 'statictext',
			event: {
				click: function (e) {
					var rowNum = $(this).data("rowNum");
					// �����Ʒ��Ϣ
					var conProductId = $("#productInfo_cmp_conProductId"
					+ rowNum).val();
					var conProductName = $("#productInfo_cmp_conProductName"
					+ rowNum).val();
					var deploy = $("#productInfo_cmp_deploy" + rowNum).val();
					var orgDeploy = $("#productInfo_cmp_orgDeploy" + rowNum).val();

					if (conProductId == "") {
						alert('����ѡ����ز�Ʒ!');
						return false;
					} else {
						if (deploy == "") {
							var url = "?model=goods_goods_properties&action=toChoose"
								+ "&productInfoId="
								+ "productInfo_cmp_deploy"
								+ rowNum
								+ "&goodsId="
								+ conProductId
								+ "&goodsName="
								+ conProductName
								+ "&rowNum="
								+ rowNum
								+ "&componentId=productInfo"
							;
							window.open(url, '',
								'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
						} else {
							if (deploy == orgDeploy) {
								var url = "?model=goods_goods_properties&action=toChooseChange"
									+ "&productInfoId="
									+ "productInfo_cmp_deploy"
									+ rowNum
									+ "&goodsId="
									+ conProductId
									+ "&goodsName="
									+ conProductName
									+ "&cacheId="
									+ deploy
									+ "&rowNum="
									+ rowNum
									+ "&componentId=productInfo"
								;
								window.open(url, '',
									'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
							} else {
								var url = "?model=goods_goods_properties&action=toChooseAgain"
									+ "&productInfoId="
									+ "productInfo_cmp_deploy"
									+ rowNum
									+ "&goodsId="
									+ conProductId
									+ "&goodsName="
									+ conProductName
									+ "&cacheId="
									+ deploy
									+ "&rowNum="
									+ rowNum
									+ "&componentId=productInfo"
								;

								window.open(url, '',
									'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
							}
						}
					}

				}
			},
			html: '<input type="button"  value="��Ʒ����"  class="txt_btn_a"/>'
		},{
			display: '��Ʒ����ΨһId',
			name: 'onlyProductId',
			type: 'hidden'
		}, {
            display: 'originalId',
            name: 'originalId',
            type: 'hidden'
        }],
		isAddOneRow: false,
		event: {
			clickAddRow: function (e, rowNum, g) {
				rowNum = g.allAddRowNum;
				//�Ƿ��ܺ�ͬ���������ͬ�ǿ�ܺ�ͬ�����ͬ���Ϊ0
				var isFrame = $("#contractType").val() == 'HTLX-XSHT' && $("#isFrame").val() == '1' ? '1' : '0';
				var url = "?model=contract_contract_product&action=toProductIframe&isCon=1"
					+ "&componentId=productInfo"
					+ "&rowNum="
					+ rowNum
					+ "&isFrame="
					+ isFrame;

				window.open(url, '',
					'top=0,left=0,menubar=0,toolbar=0,status=1,scrollbars=1,resizable=1,width=1000px,height=600px');
			},
			reloadData: function (e, g ,data) {
				if ($("#proflag").val() == '0') {
					changeEqu();
				}
				initCacheInfo();
				createProArr();

				// ִ�в��Ŵ���
//				initExeDept(data, g);
			},
            beforeRemoveRow: function (e, rowNum, rowData, g){ //Create By HuangHaoJin
                var contractId = $('#oldId').val();
                var isPlaning = false;//�������´﷢���ƻ�

                if (typeof(rowData) != 'undefined') {
                    var productId = (rowData.originalId>0)?rowData.originalId : rowData.id;
                    //����Ƿ����´﷢���ƻ�
                    var resultNum = $.ajax({
                        type: 'POST',
                        url: "?model=contract_contract_equ&action=chkPlaningEqu",
                        data: {
                            contractid: contractId,
                            productId: productId
                        },
                        async: false,
                        success: function (data) {
                        }
                    }).responseText;
                    isPlaning = (resultNum>0) ? true : false;
                }

                if(isPlaning){
                    alert("��Ʒ�޷�ɾ�����˲�Ʒ���������´﷢���ƻ���");
                    g.isRemoveAction = false;
                }
            },
			removeRow: function (e, rowNum, rowData) {
                var equObj = $("#equInfo");
				if (typeof(rowData) != 'undefined') {
					$("#goodsDetail_" + rowData.deploy).remove();
                    //ɾ����Ʒͬʱɾ�����¹���������
                    for (var i = 0; i < equObj.yxeditgrid("getAllAddRowNum"); i++) {
                        if(equObj.yxeditgrid("getCmpByRowAndCol",i,"conProductId").val() == rowData.id){
                            //����ж�Ӧ�����ϣ�ɾ������
                            $("#equInfo").append('<input type="hidden" id="equInfo_cmp_isDelTag'+i+'" name="contract[equ]['+i+'][isDelTag]" value="1" class="">');
                        }
                    }
					createProArr();
				}
			}
		},
		addBtnClick: function() {
			return false;
		},
		setData: function(returnValue, rowNum) {
			var g = this;
			if (returnValue) {
//                var rowsNum = parseInt(g.tbody.find('.tr_even').length);
                // ����һ��
                g.addRow(g.allAddRowNum);

				//��Ʒ
				var proArr = returnValue[0];
				g.setRowColValue(rowNum, "proType", proArr.proType);
				g.setRowColValue(rowNum, "proTypeId", proArr.proTypeId);
				g.setRowColValue(rowNum, "proExeDeptId", proArr.proExeDeptId);
				g.setRowColValue(rowNum, "proExeDeptName", proArr.proExeDeptName);
				g.setRowColValue(rowNum, "newExeDeptCode", proArr.newExeDeptCode);
				g.setRowColValue(rowNum, "newExeDeptName", proArr.newExeDeptName);
				g.setRowColValue(rowNum, "newProLineCode", proArr.newExeDeptCode);
				g.setRowColValue(rowNum, "newProLineName", proArr.newExeDeptName);
//				initExeDeptByRow(g, rowNum);
				setProExeDeptByRow(rowNum);

				g.setRowColValue(rowNum, "conProductId", proArr.goodsId, true);
				g.setRowColValue(rowNum, "conProductName", proArr.goodsName, true);
				g.setRowColValue(rowNum, "goodsClass", proArr.goodsClass, true);
				g.setRowColValue(rowNum, "goodsClassName", proArr.goodsClassName, true);
				g.setRowColValue(rowNum, "number", proArr.number, true);
				g.setRowColValue(rowNum, "price", proArr.price, true);
				g.setRowColValue(rowNum, "money", proArr.money, true);
				g.setRowColValue(rowNum, "warrantyPeriod", proArr.warrantyPeriod, true);
				g.setRowColValue(rowNum, "deploy", proArr.cacheId, true);
				g.setRowColValue(rowNum, "license", proArr.licenseId, true);
				g.setRowColValue(rowNum, "onlyProductId", proArr.onlyProductId, true);
				proArr.deploy = proArr.cacheId;
				var $tr = g.getRowByRowNum(rowNum);
                // ���ԭ���й���������ϵģ���ɾ��ԭ���������
                if($tr.data("rowData")){
                    var oldDeploy = $tr.data("rowData").deploy;
                    if($("#goodsDetail_" + oldDeploy)){$("#goodsDetail_" + oldDeploy).remove();}
                }

//                $("#goodsDetail_" + oldDeploy).remove();

				$tr.data("rowData", proArr);
				//ѡ���Ʒ��̬��Ⱦ��������õ�
				getCacheInfo(proArr.cacheId, rowNum);

				//����
				var equArr = returnValue[1];
				if (typeof(equArr) != 'undefined') {
					var equLen = equArr.length;
//					var equObj = $("#materialInfo");
                    var equObj = $("#equInfo");
					for (var i = 0; i < equLen; i++) {
						//���»�ȡ����
						var tbRowNum = equObj.yxeditgrid("getAllAddRowNum");
						//������
						equObj.yxeditgrid("addRow", tbRowNum);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productCode", equArr[i].productCode);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productName", equArr[i].productName);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productId", equArr[i].productId);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "conProductId", equArr[i].conProductId);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "productModel", equArr[i].productModel);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "number", equArr[i].number);
						equObj.yxeditgrid("setRowColValue", tbRowNum, "onlyProductId", equArr[i].onlyProductId);
                        equObj.yxeditgrid("setRowColValue", tbRowNum, "price", equArr[i].price);
                        equObj.yxeditgrid("setRowColValue", tbRowNum, "money", equArr[i].money);
                        equObj.yxeditgrid("setRowColValue", tbRowNum, "license", equArr[i].license);
					}
				}
				createProArr();
			}
		},
		reloadCache: function(cacheId, rowNum) {
			if (cacheId) {
				$("#goodsDetail_" + cacheId).remove();
				//ѡ���Ʒ��̬��Ⱦ��������õ�
				getCacheInfo(cacheId, rowNum);
			}
		}
	});
})(jQuery);

//��Ʒ�嵥
function productListHtml(typeState) {
	$("#productInfo").productInfoGrid({
		type: typeState,
		param: {
			contractId: $("#contractId").val(),
			dir: 'ASC',
			isTemp: isTemp,
			isDel: '0'
		}
	});
}

function changeEqu() {
    var rowNum = $("#productInfo").productInfoGrid('getCurShowRowNum');
    if (rowNum == '0') {
        //2012-11-09 л��˵������ͬ�������
        document.getElementById("equ").style.display = "none";
    } else {
        document.getElementById("equ").style.display = "none";
    }
}
// ѡ�����к�
function serialNum(borrowId, productId, num, inputId, sid) {
    var amount = $("#bornumber" + num).val();
    showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialNum&borrowId='
    + borrowId
    + '&productId='
    + productId
    + '&num='
    + num
    + '&amount='
    + num
    + '&inputId='
    + inputId
    + '&sid='
    + sid
    + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
}

//����������Ʒ��������
function createProArr(denyRmBtn) {
    var itemArr = $("#productInfo").productInfoGrid("getCmpByCol", "conProductName");
    if (itemArr.length > 0) {
        var returnArr = [];
        //ѭ��
        itemArr.each(function () {
            var rowNum = $(this).data("rowNum");
            var rowArr = $("#productInfo").productInfoGrid("getRowByRowNum", rowNum);
            rowArr.each(function () {
                var beforeStr = "productInfo_cmp_";
				//������ת���۵����ϣ�ֻ�ܹ����������Ʒ��
				if($("#" + beforeStr + "proTypeId" + rowNum).val() == "11"){
					var equJson = {
						name: $("#" + beforeStr + "conProductName" + rowNum).val(),
						value: $("#" + beforeStr + "onlyProductId" + rowNum).val()
					};
					returnArr.push(equJson);
				}

                // �����Ҫ����ɾ����ť�����ظ��б��ɾ����ť
                if (typeof(denyRmBtn) != 'undefined' && denyRmBtn) {
                    $("#" + beforeStr + "removeBn" + rowNum).hide();
                }else{
                    //����л����ͬ��������ԭ��ͬ�Ĳ�ƷID��originalId��,���û�����õ�ǰ��ͬ��ƷID��id��
                    var conProductId = ($("#" + beforeStr + "originalId" + rowNum).val() > 0)? $("#" + beforeStr + "originalId" + rowNum).val() : $("#" + beforeStr + "id" + rowNum).val();
                    var contractId = $("#oldId").val();

                    // ��ѯ�Ƿ���ڹ�������(������ڵĲ���ɾ��)
                    var chkEquResult = $.ajax({
                        type: 'POST',
                        url: "?model=contract_contract_equ&action=chkRelativeEqu",
                        data: {
                            contractid: contractId,
                            productid:conProductId
                        },
                        async: false,
                        success: function (data) {
                        }
                    }).responseText;
                    if( parseInt(chkEquResult)>0 && conProductId!=''){$("#" + beforeStr + "removeBn" + rowNum).hide();}//�����Ʒ�й���yi���ϣ��Ҳ��Ǳ��ҳ�������Ĳ���ɾ��
                }
            })
        });
    }
    proLineArr = returnArr;
//	        proLineArr = eval("(" + returnArr + ")");
    //��������
    var bowItemArr = $("#borrowConEquInfo").yxeditgrid("getCmpByCol", "productId");
    if (bowItemArr.length > 0) {
        var returnArr = [];
        //ѭ��
        bowItemArr.each(function () {
            var borowNum = $(this).data("rowNum");
            var borowArr = $("#borrowConEquInfo").yxeditgrid("getRowByRowNum", borowNum);
            borowArr.each(function () {
                var OldVal = $("#borrowConEquInfo_cmp_conProduct" + borowNum).val();
                $("#borrowConEquInfo_cmp_onlyProductId" + borowNum).empty();
                var obj = document.getElementById("borrowConEquInfo_cmp_onlyProductId" + borowNum);
                obj.add(new Option("...��ѡ��...", " "));
//	            	 	$("#borrowConEquInfo_cmp_proId" + borowNum).append("<option value=''>...��ѡ��...<option>");
                if (typeof(proLineArr) != 'undefined') {
                    if (proLineArr.length > 0) {
                        for (i = 0; i < proLineArr.length; i++) {
                            obj.add(new Option("" + proLineArr[i]['name'] + "", "" + proLineArr[i]['value'] + ""));
                            if (proLineArr[i]['name'] == OldVal) {
                                var oldTval = proLineArr[i]['value'];
                            }
//	            	 	       $("#borrowConEquInfo_cmp_proId" + borowNum).append("<option value='"+proLineArr[i]['value']+"'>"+proLineArr[i]['name']+"<option>");
                        }
                    }
                }
                for (var i = 0; i < obj.options.length; i++) {
                    if (oldTval == obj.options[i].value) {
                        obj.options[i].selected = 'selected';
                        break;
                    }
                }

            })
        });
    }
}
