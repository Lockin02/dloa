$(function () {
    $("#purchaseProductTable").yxeditgrid({
        event: {
            removeRow: function (t, rowNum, rowData) {
                countAmount();
            }
        },
        isAdd: false,
        objName: 'apply[applyItem]',
        url: '?model=asset_require_requireitem&action=requireJsonApply',
        param: {
            mainId: $("#relDocId").val()
        },
        isAddOneRow: true,
        colModel: [{
            display: '����Id',
            name: 'productId',
            type: 'hidden'
        }, {
            display: '���ϱ���',
            name: 'productCode',
            tclass: 'readOnlyTxtNormal',
            width: 120,
            readonly: true
        }, {
            display: '��������',
            name: 'productName',
            tclass: 'readOnlyTxtNormal',
            readonly: true
        }, {
            display: '�ʲ�����',
            name: 'inputProductName',
            tclass: 'txt'
        }, {
            display: '�ʲ�����',
            name: 'description',
            validation: {
                required: true
            },
            tclass: 'txt'
        }, {
            display: 'requireItemId',
            name: 'requireItemId',
            type: "hidden"
        }, {
            display: '�������',
            name: 'productCategoryCode',
            validation: {
                required: true
            },
            tclass: 'txtshort',
            type: 'select',
            datacode: 'CGWLLB',
            processData: function (data) {
                var newData = [{
                    dataName: '',
                    dataCode: ''
                }];
                for (var i = 0; i < data.length; i++) {
                    newData.push(data[i]);
                }
                return newData;
            }
        }, {
            display: '���',
            name: 'pattem',
            validation: {
                required: true
            }
        }, {
            display: '��������',
            name: 'applyAmount',
            tclass: 'txtshort',
            validation: {
                custom: ['onlyNumber']
            },
            event: {
                blur: function (e) {
                    var rownum = $(this).data('rowNum');// �ڼ���
                    var grid = $(this).data('grid');// ������

                    var applyAmount = grid.getCmpByRowAndCol(rownum, 'applyAmount').val();
                    var maxAmount = grid.getCmpByRowAndCol(rownum, 'maxAmount').val();

                    if ($(this).val() * 1 < 0) {
                        alert("��������С��0��");
                        $(this).val(maxAmount);
                    }

                    if ($(this).val() * 1 > maxAmount * 1) {
                        alert("�������ܴ���ʣ�����������");
                        $(this).val(maxAmount);
                    }
                }
            }
        }, {
            display: '�������',
            name: 'maxAmount',
            type: "hidden"
        }, {
            display: 'Ԥ�ƽ��',
            name: 'amounts',
            tclass: 'txtshort',
            type: 'money',
            // blur ʧ������������������ķ���
            event: {
                blur: function () {
                    countAmount();
                }
            }
        }, {
            display: '��λ',
            name: 'unitName',
            tclass: 'txtshort'
        }, {
            display: 'ϣ����������',
            name: 'dateHope',
            type: 'date',
            tclass: 'txtshort',
            validation: {
                custom: ['date']
            }
        }, {
            display: '��ע',
            name: 'remark',
            tclass: 'txt'
        }, {
            display: 'ѯ�۽��',
            name: 'inquiryAmount',
            type: 'money',
            tclass: 'txtshort'
        }, {
            display: '�������',
            name: 'suggestion',
            type: 'textarea',
            cols: '40',
            rows: '3'
        }]
    });
//	// ѡ����Ա���
//	$("#userName").yxselect_user({
//		hiddenId : 'userId',
//		isGetDept : [true, "useDetId", "useDetName"]
//	});

    $("#applicantName").yxselect_user({
        hiddenId: 'applicantId',
        isGetDept: [true, "applyDetId", "applyDetName"]
    });

    // ���ݲɹ���������ʾ�ʲ���;
    purchCategoryArr = getData('CGZL');
    addDataToSelect(purchCategoryArr, 'purchCategory');
    $('#purchCategory').change(function () {
        $('#assetUseCode').empty();
        assetUseArr = getData($('#purchCategory').val());
        addDataToSelect(assetUseArr, 'assetUseCode');
        if ($('#assetUseCode').val()) {
            $('#assetUse').val($('#assetUseCode').get(0).options[$('#assetUseCode').get(0).selectedIndex].innerText);
        }
    });

    // ���ݲɹ���������ʾ�����ֶΣ��ƻ���š��ƻ���ȣ�
    $("#hiddenA").hide();
    $('#purchaseType').change(function () {
        if ($("#purchaseType").val() == "CGLX-JHN") {
            $("#hiddenA").show();
        } else {
            $('#planYear').val("");
            $("#hiddenA").hide();
        }
    });

    // ���ݲɹ�����Ϊ���з��ࡱʱ����ʾ�����ֶΣ��ɹ����ࡢ�ش�ר�����ơ�ļ���ʽ���Ŀ�������з���Ŀ��
    $("#hiddenD").hide();
    $("#hiddenE").hide();
    $("#purchCategory").change(function () {
        if ($("#purchCategory").val() == "CGZL-YFL") {
            $('#assetUseCode').val("");
            $('#assetUse').val("");
            $("#hiddenC").hide();
            $("#hiddenD").show();
            $("#hiddenE").show();
        } else {
            $('#assetClass').val("");
            $('#importProject').val("");
            $('#moneyProject').val("");
            $('#otherProject').val("");
            $("#hiddenC").show();
            $("#hiddenD").hide();
            $("#hiddenE").hide();
        }
    });

    /**
     * ��֤��Ϣ
     */
    validate({
        "userName": {
            required: true
        },
        "applicantName": {
            required: true
        },
        "applyTime": {
            custom: ['date']
        },
        "amounts_v": {
            required: true
        }
    });

    $("#purchaseViewTable").yxeditgrid({
        objName: 'apply[applyItem]',
        url: '?model=asset_purchase_apply_applyItem&action=preListJson',
        delTagName: 'isDelTag',
        type: 'view',
        param: {
            relDocId: $("#relDocId").val(),
            "isDel": '0'
        },
        colModel: [{
            display: '��������',
            name: 'inputProductName',
            tclass: 'readOnlyTxtItem'
        }, {
            display: '���',
            name: 'pattem',
            tclass: 'readOnlyTxtItem'
        }, {
            display: '��������',
            name: 'applyAmount',
            tclass: 'txtshort'
        }, {
            display: '��Ӧ��',
            name: 'supplierName',
            tclass: 'txtmiddle'
        }, {
            display: '��λ',
            name: 'unitName',
            tclass: 'readOnlyTxtItem'
        }, {
            display: 'ϣ����������',
            name: 'dateHope',
            type: 'date'
        }, {
            display: '��ע',
            name: 'remark',
            tclass: 'txt'
        }]
    });

    $("#itemTable").yxeditgrid({
        url: '?model=asset_require_requireitem&action=listByRequireJson',
        objName: 'requirement[items]',
        type: 'view',
        param: {
            mainId: $("#relDocId").val()
        },
        isAddOneRow: true,
        colModel: [{
            display: 'id',
            name: 'id',
            tclass: 'txtshort',
            type: 'hidden'
        }, {
            display: '�豸����',
            name: 'description',
            validation: {
                required: true
            },
            tclass: 'txt'
        }, {
            display: '����',
            name: 'number',
            validation: {
                required: true,
                custom: ['onlyNumber']
            },
            tclass: 'txtshort'
        }, {
            display: '�ѷ�������',
            name: 'executedNum',
            validation: {
                required: true,
                custom: ['onlyNumber']
            },
            tclass: 'txtshort'
        }, {
            display: 'Ԥ�ƽ��',
            name: 'expectAmount',
            tclass: 'txtshort',
            process: function (v) {
                return moneyFormat2(v);
            }
        }, {
            display: 'Ԥ�ƽ�������',
            name: 'dateHope',
            type: 'date',
            validation: {
                required: true
            },
            tclass: 'txtshort'
        }, {
            display: '��ע',
            name: 'remark',
            tclass: 'txt'
        }, {
            display: 'ѯ�۽��',
            name: 'inquiryAmount',
            type: 'money',
            tclass: 'txtshort'
        }, {
            display: '�������',
            name: 'suggestion',
            type: 'textarea'
        }]
    })
});

/*
 * ���ȷ��
 */
function confirmAudit() {
    //�����Ƿ��в�ͬ
//	var deptDiff = false;
//	var markDept;
//	$("select[id^='purchaseProductTable_cmp_purchDept']").each(function(i,n){
//		//�������ɾ�����ݣ��Ŵ���
//		if($("#apply[applyItem]_"+ i +"_isDelTag").length == 0){
//			if(!markDept){
//				markDept = this.value;
//			}else if(markDept != this.value){
//				deptDiff = true;
//				return false;
//			}
//		}
//	});
//	//������Ų�ͬ�������ύ��
//	if(deptDiff == true){
//		alert('�����´�ɹ�������뱣�ֲɹ�����һ��');
//		return false;
//	}

    if (confirm("ȷ��Ҫ�ύ��?")) {
        var purchaseDept = $('#purchaseDept').val();
        var audit = (purchaseDept == '1') ? 'noaudit' : 'audit';
        $("#form1").attr("action", "?model=asset_purchase_apply_apply&action=add&actType=" + audit).submit();
    } else {
        return false;
    }
}
// ���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
    // ��ȡ��ǰ����������Ƭ���ʲ���
    var curRowNum = $("#purchaseProductTable").yxeditgrid("getCurRowNum");
    var rowAmountVa = 0;
    var cmps = $("#purchaseProductTable").yxeditgrid("getCmpByCol", "amounts");
    cmps.each(function () {
        rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
    });
    $("#amounts").val(rowAmountVa);
    $("#amounts_v").val(moneyFormat2(rowAmountVa));
    return true;
}
