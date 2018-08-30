$(function() {
	var equIdArr = $("#equIdArr").val();
	var productInfoObj = $("#productInfo");
	// ��Ʒ�嵥
	productInfoObj.yxeditgrid({
		objName : 'borrowreturnDis[product]',
		url : '?model=projectmanagent_borrowreturn_borrowreturnequ&action=listJsonReturn',
		tableClass : 'form_in_table',
		realDel : true,
		// type:'view',
		title : "�����嵥",
		param : {
			'returnId' : $("#id").val(),
			'applyType' : $("#applyType").val()
		},
		event : {
			reloadData : function(event, g,data) {
				if(!data || data.length == 0){
//					alert('û�п��´�黹������');
//					closeFun();
				}else{
					var applyType = $("#applyType").val();
					//������豸��ʧ����ô�⳥�������Ǻ��´�������ȵģ��˴�����Ӧ����
					var productArr = g.getCmpByCol("disposeNum");
					productArr.each(function(){
						var rowNum = $(this).data('rowNum');//�к�

						if(applyType == "JYGHSQLX-02"){//�黹���⳥�Ĵ���
							//�������ʧ,һ���Դ���
							g.getCmpByRowAndCol(rowNum,'disposeNum').attr("readonly",true).removeClass('txtshort').addClass('readOnlyTxtShort');
							g.getCmpByRowAndCol(rowNum,'outNum').attr("readonly",true).removeClass('txtshort').addClass('readOnlyTxtShort');
						}
					});
				}
			}
		},
		colModel : [{
            display : 'id',
            name : 'id',
            type : 'hidden',
            isSubmit : true,
            readonly : true
        }, {
            display : 'equId',
            name : 'equId',
            type : 'hidden',
            isSubmit : true,
            readonly : true
        }, {
            display : 'borrowequId',
            name : 'borrowequId',
            type : 'hidden',
            isSubmit : true
        }, {
            display : '���ϱ��',
            name : 'productNo',
            type : 'statictext',
            isSubmit : true,
            width : 70,
            readonly : true
        }, {
            display : '��������',
            name : 'productName',
            type : 'statictext',
            isSubmit : true,
            readonly : true
        }, {
            display : '����Id',
            name : 'productId',
            type : 'hidden'
        }, {
            display : '����ͺ�',
            name : 'productModel',
            type : 'hidden'
        }, {
            display : '��λ',
            name : 'unitName',
            type : 'hidden'
        }, {
            display : '����Id',
            isSubmit : true,
            type : 'hidden'
        }, {
            display : '����<br/>�黹����',
            name : 'number',
            type : 'statictext',
            width : 60,
            isSubmit : true
        }, {
//            display : '����<br/>�ʼ�����',
//            name : 'qualityNum',
//            width : 60,
//            type : 'statictext'
//        }, {
//            display : '�ϸ�����',
//            name : 'qPassNum',
//            width : 50,
//            type : 'statictext'
//        }, {
//            display : '���ϸ�����',
//            name : 'qBackNum',
//            width : 60,
//            type : 'statictext'
//        }, {
            display : '���´�<br/>�黹����',
            name : 'disposeNumber',
            type : 'statictext',
            width : 60,
            isSubmit : true
        }, {
            display : '�����´�<br/>�黹����',
            name : 'disposeNum',
            tclass : 'readOnlyTxtShort',
            width : 60,
            readonly : true,
            validation : {
                required : true
            },
            event : {
                blur : function(e, rowNum) {
                    var rowNum = $(this).data("rowNum");
                    var thisGrid = $(this).data("grid");
                    var thisNumber = $(this).val()*1;
                    var disposeNumber = thisGrid.getCmpByRowAndCol(rowNum,'disposeNumber').val()*1;
                    var number = thisGrid.getCmpByRowAndCol(rowNum,'number').val()*1;
                    var num = number - disposeNumber;
                    if (thisNumber < 0 || thisNumber > num) {
                        alert("�´�黹�������ܴ���" + num + ",��С��0 ");
                        thisGrid.setRowColValue(rowNum, "disposeNum", num, true);
                    }
                }
            }
        }, {
            name : 'serialId',
            display : '�黹���к�',
            type : 'hidden'
        }, {
            name : 'serialName',
            display : '�黹���к�',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly'
        }, {
            display : '���´�<br/>��������',
            name : 'outedNum',
            type : 'statictext',
            width : 60,
            isSubmit : true
        }, {
            display : '�����´�<br/>��������',
            name : 'outNum',
            tclass : 'txtshort',
            width : 60,
            validation : {
                required : true
            }
        }, {
            name : 'serialOutId',
            display : '�������к�ID',
            type : 'hidden'
        }, {
            name : 'serialOutName',
            display : '�������к�',
            tclass : 'readOnlyTxtNormal',
            readonly : 'readonly',
            process : function($input, rowData, $tr, grid) {
                var borrowLimit = $("#borrowLimit").val();
                var inputId = $input.attr('id');
                var rownum = $input.data('rowNum');// �ڼ���
                var sid = grid.getCmpByRowAndCol(rownum, 'serialOutId').attr('id');
                var $img = $("<img src='images/add_snum.png' align='absmiddle'  title='ѡ�����к�'>");
                $img.click(function(serialId, serialName, num, inputId, sid, borrowLimit, rownum) {
                    return function() {
                        serialNum(serialId, serialName, num, inputId, sid, borrowLimit, rownum);
                    }
                }(rowData.serialOutId, rowData.serialOutName, rowData.qBackNum, inputId, sid, rowData.qBackNum, rownum));
                $input.before($img)
            },
            event : {
                dblclick : function() {
                    var serial = $(this).val();
                    if (serial != "") {
                        showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialShow&serial='
                            + serial
                            + "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
                    }
                }
            }
        }],
		isAddOneRow : false,
		isAdd : false
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		//��������֤
	});

	//�󶨱���֤
	$("form").submit(function(){
		var disposeNumArr = productInfoObj.yxeditgrid("getCmpByCol", "disposeNum");
		var rs = true;
		disposeNumArr.each(function(){
			var rowNum = $(this).data("rowNum");
			var outNum = productInfoObj.yxeditgrid("getCmpByRowAndCol",rowNum,"outNum").val();
			if(this.value *1 < outNum *1){
				rs = false;
				var productName = productInfoObj.yxeditgrid("getCmpByRowAndCol",rowNum,"productName").val();
				alert('���ϡ�'+productName+'�����´�����������ܴ����´�黹����');
				return false;
			}
		});
        if(rs == false){
            return false;
        }

		return confirm('ȷ���ύ������');
	});

	//��ʾ�ʼ����
	$("#showQualityReport").showQualityDetail({
		param : {
			"objId" : $("#id").val(),
			"objType" : 'ZJSQYDGH'
		}
	});
});

// ѡ�����к�
function serialNum(serialId, serialName, num, inputId, sid, borrowLimit, rownum) {
	showThickboxWin('?model=projectmanagent_borrow_borrow&action=serialNumBorrowReturn&serialId='
			+ serialId
			+ '&serialName=' + serialName
			+ '&num=' + num
			+ '&amount=' + num
			+ '&inputId=' + inputId
			+ '&sid=' + sid
			+ '&borrowLimit=' + borrowLimit
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=800");
}

function borrowView() {
    var borrowId = $("#borrowId").val();
    showModalWin("?model=projectmanagent_borrow_borrow&action=toViewTab&id=" + $("#borrowId").val(),1,borrowId);
}