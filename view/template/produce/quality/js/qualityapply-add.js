$(document).ready(function() {
	//源单类型为原材料检验时,允许删除明细
	var isDel = false;
	if($("#relDocType").val() == "ZJSQYDSL"){
		isDel = true;
	}
	//源单类型为生产计划单时,允许编辑报检数量
	var isQualityNumReadonly = true;
	var qualityNumClass = 'readOnlyTxtItem';
	if($("#relDocType").val() == "ZJSQYDSC"){
		isQualityNumReadonly = false;
		qualityNumClass = 'txtmiddle';
	}
	$("#itemTable").yxeditgrid({
		objName : 'qualityapply[items]',
		url : '?model=produce_quality_qualityapply&action=toAddDetail',
		param : {
			relDocId : $("#relDocId").val(),
			relDocType : $("#relDocType").val()
		},
        event : {
			reloadData : function(e,g,data) {
				if(!data || data.length == 0){
					alert('已全部生成质检,不能继续操作');
					closeFun();
				}
			}
		},
		title : '质检申请明细',
		isAddAndDel : isDel,
		isAdd : false,
		colModel : [{
			name : 'productId',
			display : 'productId',
			type : 'hidden'
		}, {
			name : 'productCode',
			display : '物料编号',
			tclass : 'readOnlyTxtItem',
			width : 80,
			readonly : true
		}, {
			name : 'productName',
			tclass : 'readOnlyTxtMiddle',
			display : '物料名称',
			readonly : true
		}, {
			name : 'pattern',
			tclass : 'readOnlyTxtMiddle',
			display : '型号',
			readonly : true
		}, {
			name : 'unitName',
			tclass : 'readOnlyTxtItem',
			display : '单位',
			readonly : true
		}, {
			name : 'checkTypeName',
			tclass : 'readOnlyTxtItem',
			display : '质检方式',
			readonly : true
		}, {
			name : 'checkType',
			display : 'checkType',
			type : 'hidden'
		}, {
			name : 'qualityNum',
			tclass : qualityNumClass,
			display : '报检数量',
            readonly : isQualityNumReadonly,
            event : {
            	blur : function(e){
	            	var rownum = $(this).data('rowNum');// 第几行
	            	var grid = $(this).data('grid');// 表格组件
	
	            	var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();
	            	
	            	if(!isNum($(this).val()) || $(this).val() *1 <= 0){
		            	alert("报检数量必须为正整数！");
		            	$(this).val(maxNum);
	            	}
	
	            	if($(this).val() *1 > maxNum *1){
		            	alert("报检数量不能大于" + maxNum);
		            	$(this).val(maxNum);
	            	}
            	}
            },
		}, {
			name : 'maxNum',
			display : '最大数量',
			process:function($input,row){
				$input.val(row.qualityNum);
			},
			type : 'hidden'
		}, {
			name : 'relDocItemId',
			display : 'relDocItemId',
			type : 'hidden'
		}, {
			name : 'serialId',
			display : '序列号ID',
			type : 'hidden'
		}, {
			name : 'serialName',
			tclass : 'readOnlyTxtNormal',
			display : '序列号',
			readonly : true
		}]
	});
});

//提交时验证
function checkForm(){
	if($("#relDocType").val() == "ZJSQYDSL"){//源单类型为原材料检验时,明细不允许为空
		if($("#itemTable").yxeditgrid("getCurShowRowNum") == 0){
			alert("质检申请明细不能为空");
			return false;
		}
	}
	return true;
}