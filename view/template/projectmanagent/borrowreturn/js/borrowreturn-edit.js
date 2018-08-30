// 表单验证
function checkform(){
    var objGrid = $("#productInfo");
    var isOK = true;
    var productIdArr = []; // 缓存查询序列号的物料Id
    // 循环获取数量
    objGrid.yxeditgrid("getCmpByCol","number").each(function(){
        var rowNum = $(this).data('rowNum');
        // 数量验证
        if($(this).val() * 1 == "0" || strTrim($(this).val()) ==''){
            alert('归还物料不能含有数量为0或者空的行');
            isOK = false;
            return false;
        }

        // 序列号数量验证
        var serialId = objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'serialId').val();
        var arr = serialId.split(",");
        if(serialId != "" && $(this).val() * 1 != arr.length){
            alert("申请归还数量【" + $(this).val() + "】与选择的序列号数量【" + arr.length + "】不相等");
            isOK = false;
            return false;
        }
        // 当序列号为空的时候,尝试去查询序列号是否为空
        if(serialId == ""){
            productIdArr.push(objGrid.yxeditgrid('getCmpByRowAndCol',rowNum,'productId').val());
        }
    });
    // 序列号验证
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
                    alert('物料【' + obj.productName + "】含有序列号信息，请选择!");
                    isOK = false;
                }
            }
        });
    }
    if(isOK == true){
        return confirm('确认提交单据？');
    }else{
        return false;
    }
}

$(function() {
	//邮件渲染
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check'
	});
	// 验证信息
	validate({
		"editReason" : {
			required : true
		}
	});
	// 产品清单
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
			display : '物料编号',
			name : 'productNo',
			tclass : 'readOnlyTxtShort',
			readonly : true
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'readOnlyTxtNormal',
			readonly : true
		}, {
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		}, {
			display: '规格型号',
			name: 'productModel',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '单位',
			name: 'unitName',
			tclass: 'readOnlyTxtMin',
			readonly: true,
			width: 50
		}, {
			display : '归还数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			name : 'serialId',
			display : '序列号ID',
			type : 'hidden'
		}, {
			name : 'serialName',
			display : '序列号',
			tclass : 'readOnlyTxtNormal',
			readonly : 'readonly',
			process : function($input, rowData) {
				if (rowData.productId == "-1") {
					return false;
				}
				var rownum = $input.data('rowNum');// 第几行
				var $img = $("<img src='images/add_snum.png' align='absmiddle' title='选择序列号'>");
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

// 选择序列号
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

//显示/隐藏修改原因
function showOrHideReason(obj) {
	if (obj.value == "y") {
		$('#editReason').parents('tr:first').show();
		$('#editReason').addClass('validate[required]');
	}else{
		$('#editReason').parents('tr:first').hide();
		$('#editReason').removeClass('validate[required]').val('');
	}
}