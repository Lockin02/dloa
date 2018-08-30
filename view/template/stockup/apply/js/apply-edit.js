$(document).ready(function() {
		//�̻�����Ⱦ
//		buildInputSet('chanceCode','�̻����','chance');
//		buildInputSet('chanceName','�̻�����','chance');
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
					display : '�������ƣ���Ʒ��',
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
					display : '��ƷID',
					name : 'productId',
					type:'hidden'
				},{
					display : '��ƷCODE',
					name : 'productCode',
					type:'hidden'
				},{
					display : '����',
					name : 'productNum',
					type : 'txt',
					width : 50,
					validation : {
						required : true
					},
					event : {
						blur : function() {
							var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
							if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
								if (isNaN(this.value)||this.value==0) {
									this.value = 1;
								}else{
								}
							}
						}
					}
				},{
					display : '��Ʒ����',
					name : 'productConfig',
					type : 'txt',
					width : 250,
					validation : {
						required : true
					}
				},{
					display : '������������',
					name : 'exDeliveryDate',
					type : 'date',
					width : 80,
					validation : {
						required : true
					}
				},{
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : 140
				}]
	});


})
// �ύ����
function setAudit(thisVal) {
		$("#auditType").val(thisVal);
}