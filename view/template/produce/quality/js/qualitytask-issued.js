$(document).ready(function() {
	//质检人
	$("#chargeUserName").yxselect_user({
		hiddenId : 'chargeUserCode',
		formCode : 'qualitytaskUser'
	});

	validate({
		"chargeUserName" : {
			required : true
		}
	});

	//初始化质检明细
	initDetail();
})

//初始化质检明细
function initDetail(){
	var paramArr;
	if($("#applyId").val() == ""){
		paramArr = {
			'idArr' : $("#itemId").val(),
			'status' : '4'
		};
	}else{
		paramArr = {
			'mainId' : $("#applyId").val(),
			'status' : '4'
		};
	}

	$("#itemTable").yxeditgrid({
		objName : 'qualitytask[items]',
		url : '?model=produce_quality_qualityapplyitem&action=confirmPassJson',
		param : paramArr,
		isAdd : false,
		event : {
			'reloadData' : function(e){
				//如果获取不到从表
				if($("#itemTable").yxeditgrid("getCmpByCol", "productCode").length == 0){
					alert('质检申请已完全下达，不能继续此操作');
					closeFun();
				}
				//初始化邮件接收人
				countAssignNum();
				//初始化申请单信息
				countApplyCode();
			},
			'removeRow' : function(){
				//初始化申请单信息
				countApplyCode();
			}
		},
		colModel : [{
			name : 'productCode',
			display : '物料编号',
			width : 90,
			type : 'statictext'
		}, {
			name : 'productName',
			display : '物料名称',
			width : 180,
			type : 'statictext'
		}, {
			name : 'pattern',
			display : '型号',
			width : 130,
			type : 'statictext'
		}, {
			name : 'unitName',
			display : '单位',
			width : 60,
			type : 'statictext'
		}, {
			name : 'checkTypeName',
			display : '质检方式',
			width : 70,
			type : 'statictext'
		}, {
			name : 'qualityNum',
			display : '报检数量',
			width : 70,
			type : 'statictext'
		}, {
			name : 'assignNum',
			display : '下达数量',
			width : 70,
			tclass : 'readOnlyTxtMiddle',
			readonly : true
		}, {
			name : 'canAssignNum',
			display : '可下达数量',
			type : 'hidden'
		}, {
			name : 'remark',
			display : '备注'
		}, {
			name : 'relDocCode',
			display : '源单编号',
			width : 90,
			type : 'statictext'
		}, {
			name : 'applyUserName',
			display : '申请人',
			width : 90,
			type : 'statictext'
		}, {
			name : 'productId',
			display : "productId",
			type : 'hidden'
		}, {
			name : 'productCode',
			display : "productCode",
			type : 'hidden'
		}, {
			name : 'productName',
			display : "productName",
			type : 'hidden'
		}, {
			name : 'pattern',
			display : "pattern",
			type : 'hidden'
		}, {
			name : 'unitName',
			display : "unitName",
			type : 'hidden'
		}, {
			name : 'checkTypeName',
			display : "checkTypeName",
			type : 'hidden'
		}, {
			name : 'checkType',
			display : "checkType",
			type : 'hidden'
		}, {
			name : 'applyItemId',
			display : "applyItemId",
			type : 'hidden'
		}, {
			name : 'supplierId',
			display : "supplierId",
			type : 'hidden'
		}, {
			name : 'supplierName',
			display : "supplierName",
			type : 'hidden'
		}, {
			name : 'supportTime',
			display : "supportTime",
			type : 'hidden'
		}, {
			name : 'purchaserName',
			display : "purchaserName",
			type : 'hidden'
		}, {
			name : 'purchaserId',
			display : "purchaserId",
			type : 'hidden'
		}, {
			name : 'objId',
			display : "objId",
			type : 'hidden'
		}, {
			name : 'objCode',
			display : "objCode",
			type : 'hidden'
		}, {
			name : 'objType',
			display : "objType",
			type : 'hidden'
		}, {
			name : 'objItemId',
			display : "objItemId",
			type : 'hidden'
		}, {
			name : 'applyId',
			display : "applyId",
			type : 'hidden'
		}, {
			name : 'applyCode',
			display : "applyCode",
			type : 'hidden'
		}]
	});
}

//计算可下达数量
function countAssignNum(){
	var objGrid = $("#itemTable");
	var cmps = objGrid.yxeditgrid("getCmpByCol", "canAssignNum");
	cmps.each(function(i,n) {
		//过滤掉删除的行
		if($("#qualitytask[items]_" + i +"_isDelTag").length == 0){
			//本行已下达数量
			objGrid.yxeditgrid("getCmpByRowAndCol",i,"assignNum").val(this.value);
		}
	});
}

//载入申请单信息
function countApplyCode(){
	var objGrid = $("#itemTable");
	var cmps = objGrid.yxeditgrid("getCmpByCol", "applyId");

	//缓存数组
	var applyIdArr = [];
	var applyCodeArr = [];

	cmps.each(function(i,n) {
		//过滤掉删除的行
		if($("#qualitytask[items]_" + i +"_isDelTag").length == 0){
			if(jQuery.inArray(this.value,applyIdArr) == -1){

				//数据载入
				applyIdArr.push(this.value);
				applyCodeArr.push(objGrid.yxeditgrid("getCmpByRowAndCol",i,"applyCode").val());
			}
		}
	});

	$("#applyId").val(applyIdArr.toString());
	$("#applyCode").val(applyCodeArr.toString());
}

/**
 * 表单校验
 * @returns {Boolean}
 */
function checkForm() {
	var checkResult = true;
	var itemCount = $("#itembody").children("tr").size();
	for ( var i = 0; i < itemCount; i++) {
		if (parseFloat($("#assignNum" + i).val()) > parseFloat($(
				"#notExeNum" + i).val())) {
			checkResult = false;
			alert("数量不能大于未下达数量!");
		}

		if (!checkResult) {
			break;
		}
	}

	return checkResult;
}