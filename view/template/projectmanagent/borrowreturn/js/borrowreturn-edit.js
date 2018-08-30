// ����֤
function checkform(){
    var objGrid = $("#productInfo");
    var isOK = true;
    var productIdArr = []; // �����ѯ���кŵ�����Id
    // ѭ����ȡ����
    objGrid.yxeditgrid("getCmpByCol","number").each(function(){
        var rowNum = $(this).data('rowNum');
        // ������֤
        if($(this).val() * 1 == "0" || strTrim($(this).val()) ==''){
            alert('�黹���ϲ��ܺ�������Ϊ0���߿յ���');
            isOK = false;
            return false;
        }

        // ���к�������֤
        var serialId = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'serialId').val();
        var arr = serialId.split(",");
        if(serialId != "" && $(this).val() * 1 != arr.length){
            alert("����黹������" + $(this).val() + "����ѡ������к�������" + arr.length + "�������");
            isOK = false;
            return false;
        }
        // �����к�Ϊ�յ�ʱ��,����ȥ��ѯ���к��Ƿ�Ϊ��
        if(serialId == ""){
            productIdArr.push(objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'productId').val());
        }
    });
    // ���к���֤
    if(productIdArr.length > 0 && isOK == true){
        $.ajax({
            url : '?model=stock_serialno_serialno&action=checkHasSerialNo',
            data : { 'productIdArr' : productIdArr.toString() , 'relDocCode' : $("#borrowCode").val() ,
                'relDocId' : $("#borrowId").val() , 'relDocType' : 'oa_borrow_borrow' },
            type : 'POST',
            async : false,
            success : function(data){
                if(data != "0"){
                    var obj = eval("(" + data + ")");
                    alert('���ϡ�' + obj.productName + "���������к���Ϣ����ѡ��!");
                    isOK = false;
                }
            }
        });
    }
    if(isOK == true){
        return confirm('ȷ���ύ���ݣ�');
    }else{
        return false;
    }
}

$(function() {
	//�ʼ���Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	// ��֤��Ϣ
	validate({
		"editReason" : {
			required : true
		}
	});
	// ��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		objName : 'borrowreturn[product]',
		url:'?model=projectmanagent_borrowreturn_borrowreturnequ&action=listJson',
		tableClass : 'form_in_table',
		param:{
        	'returnId' : $("#id").val()
        },
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		}, {
			display : 'equId',
			name : 'equId',
			type : 'hidden'
		}, {
			display : '���ϱ��',
			name : 'productNo',
			tclass : 'readOnlyTxtShort',
			readonly : true
		},{
			display : '��������',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '����Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display: '����ͺ�',
			name: 'productModel',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '��λ',
			name: 'unitName',
			tclass: 'readOnlyTxtMin',
			readonly: true,
			width: 50
		}, {
			display : '�黹����',
			name : 'number',
			tclass : 'txtshort'
		}, {
			name : 'serialId',
			display : '���к�ID',
			type : 'hidden'
		}, {
			name : 'serialName',
			display : '���к�',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly',
			process : function($input, rowData) {
				if (rowData.productId == "-1") {
					return false;
				}
				var rownum = $input.data('rowNum');// �ڼ���
				var $img = $("<img src='images/add_snum.png' align='absmiddle' title='ѡ�����к�'>");
				$img.click(function(productId, rownum) {
                    return function() {
                        serialNum(productId, rownum);
                    }
                }(rowData.productId,rownum));
				$input.before($img);
			}
		}],
		isAddOneRow:false,
		isAdd : false
	});
});

// ѡ�����к�
function serialNum(productId, rownum) {
	var serialId = $("#productInfo_cmp_serialId"+rownum).val();
	var serialName = $("#productInfo_cmp_serialName"+rownum).val();
    var borrowCode = $("#borrowCode").val();
    var borrowId = $("#borrowId").val();
	showThickboxWin('?model=stock_serialno_serialno&action=toChooseFrameForRe'
        + '&productId=' + productId
        + '&elNum=' + rownum
        + '&serialId=' + serialId
        + '&serialName=' + serialName
        + '&relDocCode=' + borrowCode
        + '&relDocId=' + borrowId
        + '&relDocType=oa_borrow_borrow'
        + "&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=700");
}

//��ʾ/�����޸�ԭ��
function showOrHideReason(obj) {
	if (obj.value == "y") {
		$('#editReason').parents('tr:first').show();
		$('#editReason').addClass('validate[required]');
	}else{
		$('#editReason').parents('tr:first').hide();
		$('#editReason').removeClass('validate[required]').val('');
	}
}