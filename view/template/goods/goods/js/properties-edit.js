$(document).ready(function () {
    validate({
        propertiesName: {
            required: true
        },
        orderNum: {
            required: true,
            custom: ['onlyNumber']
        }
    });

    // ���ò�Ʒid
    $("#mainId").val(parent.parent.window.$("#goodsId").val());
    // ����������Ϣ ѡ������
    $("#parentName").yxcombotree({
        hiddenId: 'parentId',
        treeOptions: {
            url: "?model=goods_goods_properties&action=getTreeData&goodsId="
            + $("#mainId").val()
        }
    });

    var detalArr = [{
        value: "",
        name: "��license"
    }, {
        value: "PIO",
        name: "Pioneer"
    }, {
        value: "NAV",
        name: "Navigator"
    }, {
        value: "Pioneer-Navigator",
        name: "Pioneer-Navigator"
    }, {
        value: "WT",
        name: "Walktour"
    }, {
        value: "Walktour Pack-Ipad",
        name: "Walktour Pack-Ipad"
    }, {
        value: "FL2",
        name: "Fleet"
    }];

    $.ajax({
        url : '?model=yxlicense_license_baseinfo&action=getLicense',
        type : "POST",
        data : {},
        async : false,
        success: function(data) {
            data = eval("(" + data + ")");
            for (var i = 0; i <= data.length; i++) {
                detalArr.push({
                    value: data[i].value,
                    name: data[i].name
                });
            }
        }
    });

    $("#itemTable").yxeditgrid({
        objName: 'properties[items]',
        url: '?model=goods_goods_propertiesitem&action=pageItemJson',
        param: {
            mainId: $("#id").val()
        },
        colModel: [{
            name: 'id',
            display: 'id',
            type: 'hidden'
        }, {
            name: 'itemContent',
            tclass: 'txt',
            display: 'ֵ����',
            validation: {
                required: true
            }
        }, {
            name: 'isNeed',
            display: '�Ƿ��ѡ',
            type: 'checkbox',
            tclass: 'txtmin',
            sortable: true
        }, {
            name: 'isDefault',
            display: '�Ƿ�Ĭ��',
            type: 'checkbox',
            tclass: 'txtmin',
            sortable: true
        }, {
            name: 'defaultNum',
            display: '����',
            tclass: 'txtmin',
            sortable: true
        }, {
            name: 'productId',
            display: '��Ʒid',
            type: "hidden",
            sortable: true
        }, {
            name: 'productCode',
            display: '��Ӧ���ϱ��',
            sortable: true,
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                $input.yxcombogrid_product({
                    hiddenId: 'itemTable_cmp_productId'
                    + rowNum,
                    nameCol: 'productCode',
                    width: 600,
                    gridOptions: {
                        event: {
                            row_dblclick: (function (rowNum) {
                                return function (e, row, rowData) {
                                    g.getCmpByRowAndCol(rowNum, 'productName').val(rowData.productName);
                                    g.getCmpByRowAndCol(rowNum, 'pattern').val(rowData.pattern);
                                    g.getCmpByRowAndCol(rowNum, 'itemContent').val(rowData.productName)
                                    .attr('readonly','readonly').removeClass('txt').addClass('readOnlyTxtNormal');
                                }
                            })(rowNum)
                        }
                    },
                    event : {
                        'clear' : function() {
                            g.getCmpByRowAndCol(rowNum, 'productName').val('');
                            g.getCmpByRowAndCol(rowNum, 'pattern').val('');
                            g.getCmpByRowAndCol(rowNum, 'itemContent').val('')
                            .attr('readonly',false).removeClass('readOnlyTxtNormal').addClass('txt');
                        }
                    }
                });
            }
        }, {
            name: 'productName',
            display: '��Ӧ��������',
            tclass: 'txt',
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                $input.yxcombogrid_product({
                    hiddenId: 'itemTable_cmp_productId'
                    + rowNum,
                    nameCol: 'productName',
                    width: 600,
                    gridOptions: {
                        event: {
                            row_dblclick: (function (rowNum) {
                                return function (e, row, rowData) {
                                    g.getCmpByRowAndCol(rowNum, 'productCode').val(rowData.productCode);
                                    g.getCmpByRowAndCol(rowNum, 'pattern').val(rowData.pattern);
                                    g.getCmpByRowAndCol(rowNum, 'itemContent').val(rowData.productName)
                                    .attr('readonly','readonly').removeClass('txt').addClass('readOnlyTxtNormal');
                                }
                            })(rowNum)
                        }
                    },
                    event : {
                        'clear' : function() {
                            g.getCmpByRowAndCol(rowNum, 'productCode').val('');
                            g.getCmpByRowAndCol(rowNum, 'pattern').val('');
                            g.getCmpByRowAndCol(rowNum, 'itemContent').val('')
                            .attr('readonly',false).removeClass('readOnlyTxtNormal').addClass('txt');
                        }
                    }
                });
            },
            sortable: true
        }, {
            name: 'pattern',
            display: '��Ӧ�����ͺ�',
            sortable: true,
            tclass: 'readOnlyTxtItem'
        }, {
            name: 'proNum',
            display: '��Ӧ��������',
            tclass: 'txtmin',
            sortable: true
        }, {
            name: 'status',
            display: '״̬',
            type: 'select',
            tclass: 'txtshort',
            options: [{
                name: "�ڲ�",
                value: 'ZC'
            }, {
                name: "ͣ��",
                value: 'TC'
            }],
            sortable: true
        }, {
            name: 'licenseTypeName',
            display: 'license����',
            type: 'hidden'
        }, {
            name: 'licenseTypeCode',
            display: 'license����',
            type: 'select',
            options: detalArr,
            event: {
                change: function (e) {
                    var rowNum = e.data.rowNum;
                    var g = e.data.gird;
                    var $cmp = g.getCmpByRowAndCol(rowNum,
                        'licenseTypeName');
                    var name = $(this).find("option:selected").text();
                    $cmp.val(name);
                    $.ajax({
                        type: "POST",
                        url: "?model=yxlicense_license_template&action=getTemplateByType",
                        data: {
                            'licenseType': $(this).val()
                        },
                        async: false,
                        success: function (data) {
                            var $cmp = g.getCmpByRowAndCol(rowNum,
                                'licenseTemplateId');
                            $cmp.children().remove();
                            $cmp.append("<option value=''>��ѡ��</option>");
                            var dataRows = eval('(' + data + ')');
                            for (var i = 0, l = dataRows.length; i < l; i++) {
                                $cmp.append("<option title='"
                                + dataRows[i].remark
                                + "' value='" + dataRows[i].id
                                + "' innerTitle='"
                                + dataRows[i].extVal
                                + "' value='"
                                + dataRows[i].thisVal + "'>"
                                + dataRows[i].name
                                + "</option>");
                            }
                        }
                    });
                }
            }
        }, {
            name: 'licenseTemplateId',
            display: 'licenseģ��',
            type: 'select',
            options: []
        }, {
            name: 'remark',
            display: '����',
            type: "hidden"
        }, {
            name: 'rkey',
            display: '������ʶ',
            type: "hidden"
        }, {
            name: 'staticRemark',
            display: '����������ť',
            type: 'statictext',
            event: {
                'click': function (e) {
                    var rowNum = $(this).data("rowNum");
                    var g = $(this).data("grid");
                    window
                        .open(
                        "?model=goods_goods_properties&action=toEditRemark&rowNum="
                        + rowNum
                        + "&id="
                        + g.getCmpByRowAndCol(
                            rowNum, 'id').val()
                        + "&rkey="
                        + $("#itemTable_cmp_rkey"
                        + rowNum).val(),
                        '������Ϣ�༭',
                        'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                }
            },
            html: '<input type="button"  value="�༭"  class="txt_btn_a"  />'
        }, {
            name: 'assitem',
            display: '���������',
            type: "hidden"
        }, {
            name: 'assitemIdStr',
            display: '������Id����',
            type: "hidden"
        }, {
            name: 'assitemTipStr',
            display: '������Tip����',
            type: "hidden"
        }, {
            name: 'staticAssitem',
            display: '���������',
            type: 'statictext',
            event: {
                'click': function (e) {
                    var rowNum = $(this).data("rowNum");
                    var g = $(this).data("grid");
                    var rowData = $(this).data("rowData");
                    var url = "?model=goods_goods_properties&action=toSetAssItem&goodsId="
                        + $("#mainId").val() + "&orderNum=" + $("#orderNum").val()
                        + "&rowNum=" + rowNum + "&assitem=" + $("#itemTable_cmp_assitem" + rowNum).val()
                        + "&assItemIdStr=" + $("#itemTable_cmp_assitemIdStr" + rowNum).val()
                        + "&assitemTipStr=" + geturl($("#itemTable_cmp_assitemTipStr" + rowNum).val());
                    window.open(url, '���������', 'height=500, width=650, top=200, left=200, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=n o, status=no');
                }
            },
            html: '<input type="button"  value="����"  class="txt_btn_a"  />'

        }],
        event: {
            addRow: function (e, rowNum, rowData, g, $tr) {
                if (rowData && rowData.licenseTypeCode) {
                    $.ajax({
                        type: "POST",
                        url: "?model=yxlicense_license_template&action=getTemplateByType",
                        data: {
                            'licenseType': rowData.licenseTypeCode
                        },
                        async: false,
                        success: function (data) {

                            var $cmp = g.getCmpByRowAndCol(rowNum,
                                'licenseTemplateId');
                            $cmp.children().remove();
                            $cmp.append("<option value=''>��ѡ��</option>");
                            dataRows = eval('(' + data + ')');
                            for (var i = 0, l = dataRows.length; i < l; i++) {
                                var selected = "";
                                if (rowData.licenseTemplateId == dataRows[i].id) {
                                    selected = "selected";
                                }
                                $cmp.append("<option " + selected + " title='"
                                + dataRows[i].remark + "' value='"
                                + dataRows[i].id + "' >"
                                + dataRows[i].name + "</option>");
                            }
                        }
                    });
                }
            },
            reloadData : function(e){
            	var objGrid = $("#itemTable");
            	//��ȡֵ����
            	var itemContentArr = objGrid.yxeditgrid("getCmpByCol", "itemContent");
				if (itemContentArr.length > 0) {
					itemContentArr.each(function(){
               			var rowNum = $(this).data("rowNum");
	            		//��ȡ��Ӧ��������
						var productName = objGrid.yxeditgrid("getCmpByRowAndCol",rowNum,"productName").val();
						//ֵ�������Ӧ��������һ��ʱ���������ֵ����
						if($(this).val() == productName){
							$(this).removeClass('txt').addClass('readOnlyTxtNormal').attr('readonly','readonly');
						}
					});
				}
			}
        }
    })
});

//����ת��
function geturl(str) {
    return str.replace(/\&/g, '%26');
}

//����ʱ��֤��
function checkForm() {
	var objGrid = $("#itemTable");
	var statusZC = false;//�Ƿ���ڡ��ڲ���״̬��������
	objGrid.yxeditgrid("getCmpByCol","status").each(function(){
		if($(this).val() == 'ZC'){
			statusZC = true;
			return false; //����eachѭ��
		}
    });
	if($("#isLeast").attr("checked") && statusZC == false){
		alert("��ǰ�����ڡ��ڲ��������������ѡ����������ġ�����ѡ��һ�");
	    return false;
	}
}
