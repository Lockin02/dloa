$(document).ready(function() {
		//商机的渲染
//		buildInputSet('chanceCode','商机编号','chance');
//		buildInputSet('chanceName','商机名称','chance');
	validate({
				"projectName" : {
					required : true
				},
				"appDate" : {
					required : true
				},
				"description" : {
					required : true
				}
			});
			$("#chanceCode").yxcombogrid_chance({
				nameCol : 'chanceCode',
				hiddenId : 'chanceId',
				isDown : true,
				height : 250,
				gridOptions : {
					isTitle : true,
					param : {'prinvipalId':$("#appUserId").val()},
					event: {
						row_dblclick : function(e, row, data) {
							$("#chanceName").val(data.chanceName);
						}
					}
				},
				event : {
					'clear' : function() {
						$("#chanceName").val("");
					}
				}
			});
/*
$("#appUserName").yxselect_user({
		hiddenId : 'appUserId',
		isGetDept : [true, "appDeptId", "appDeptName"],
		event : {
				select : function(e,row){
					if(row){
		    		$("#appUserName").val(row.name);
				}
			}
		}
	});

*/
$("#productListInfo").yxeditgrid({
		objName : 'apply[list]',
		url : 'index1.php?model=stockup_apply_applyProducts&action=getJsonEdit',
        param : {
            appId : $("#id").val()
        },
		dir : 'ASC',
		realDel : true,
		colModel : [{
					display : '物料名称（产品）',
					name : 'productName',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					},
					process : function($input, rowData) {
						var rowNum = $input.data("rowNum");
						var g = $input.data("grid");
						$input.yxcombogrid_stockupProducts({
							nameCol : 'productName',
							hiddenId : 'productListInfo_cmp_productId' + rowNum,
							isDown : true,
							height : 250,
							gridOptions : {
								action : 'jsonSelect',
								isTitle : true,
								event : {
									row_dblclick : function(e, row, data) {
										$("#productListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"productId").val(data.id);
										$("#productListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"productName").val(data.productName);
										$("#productListInfo").yxeditgrid("getCmpByRowAndCol",rowNum,"productCode").val(data.productCode);
									}
								}
							}
						});
					}
				},{
					display : '产品ID',
					name : 'productId',
					type:'hidden'
				},{
					display : '产品CODE',
					name : 'productCode',
					type:'hidden'
				},{
					display : '数量',
					name : 'productNum',
					type : 'txt',
					width : 50,
					validation : {
						required : true
					},
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //判断是否为数字
								if (isNaN(this.value)||this.value==0) {
									this.value = 1;
								}else{
								}
							}
						}
					}
				},{
					display : '产品配置',
					name : 'productConfig',
					type : 'txt',
					width : 250,
					validation : {
						required : true
					}
				},{
					display : '期望交付日期',
					name : 'exDeliveryDate',
					type : 'date',
					width : 80,
					validation : {
						required : true
					}
				},{
					display : '备注',
					name : 'remark',
					type : 'txt',
					width : 140
				}]
	});


})
// 提交审批
function setAudit(thisVal) {
		$("#auditType").val(thisVal);
}