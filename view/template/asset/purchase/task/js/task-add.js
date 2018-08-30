$(function() {
	$("#taskTable").yxeditgrid({
		objName : 'task[taskItem]',
		url : '?model=asset_purchase_task_taskItem&action=getApplyItemPage',
		param : {
			"applyId" : $("#applyId").val()
		},
		isAdd : false,
		colModel : [{
			display : '采购申请id',
			name : 'applyId',
			type : 'hidden'
		}, {
			display : '采购申请编码',
			name : 'applyCode',
			type : 'hidden'
		}, {
			display : '采购申请明细表id',
			name : 'applyEquId',
			type : 'hidden'
		}, {
			display : '物料名称',
			name : 'productName',
			process : function($input,row){
				if(row.productName==''){
					$input.val( row.inputProductName )
				}else{
					$input.val( row.productName )
				}
			},
			tclass : 'readOnlyTxtItem'
		}, {
			display : '规格',
			name : 'pattem',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '单位',
			name : 'unitName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '供应商',
			name : 'supplierName',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '采购数量',
			name : 'purchAmount',
			tclass : 'readOnlyTxtItem'
		}, {
			display : '下达数量',
			name : 'issuedAmount',
			type : 'hidden',
			process:function($input,row){
				$input.val(row.purchAmount-row.issuedAmount);
			}
		}, {
			display : '任务数量',
			name : 'taskAmount',
			tclass : 'txtshort',
			process:function($input,row){
				$input.val(row.purchAmount-row.issuedAmount);
			},
			event : {
				blur : function(e) {
					var rownum = $(this).data('rowNum');// 第几行
					var colnum = $(this).data('colNum');// 第几列
					var grid = $(this).data('grid');// 表格组件
//					var purchAmount = grid.getCmpByRowAndCol(rownum,'purchAmount').val();
					var issuedAmount = grid.getCmpByRowAndCol(rownum, 'issuedAmount').val();
					var taskAmount = $(this).val();
					taskAmount = parseFloat(taskAmount);
					issuedAmount = parseFloat(issuedAmount);
					if (taskAmount > issuedAmount) {
						alert("任务数量不能超过下达数量"+issuedAmount+" !");
						$(this).val(issuedAmount);
//						$issuedAmount.val(purchAmount);
					}
					var price = grid.getCmpByRowAndCol(rownum, 'price').val();
					var $moneyAll = grid.getCmpByRowAndCol(rownum, 'moneyAll');
					var taskAmount = $(this).val();
					$moneyAll.val(accMul(price,taskAmount));

					var $moneyAllv = $("#"+$moneyAll.attr('id')+'_v');
					$moneyAllv.val(moneyFormat2(accMul(price,taskAmount)));
//					$issuedAmount.val(taskAmount);
				}
			},
			validation : {
				custom : ['onlyNumber']
			}
		}, {
			display : '单价',
			name : 'price',
			tclass : 'readOnlyTxtItem',
			type : 'money'
		}, {
			display : '金额',
			name : 'moneyAll',
			tclass : 'readOnlyTxtItem',
			type : 'money'
			,
			process:function($input,row){
//				alert((row.purchAmount-row.issuedAmount)*row.price);
				$input.val((row.purchAmount-row.issuedAmount)*row.price);
			}
		}, {
			display : '希望交货日期',
			name : 'dateHope',
			type : 'date',
			tclass : 'txtshort datehope'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	// 选择人员组件
	$("#purcherName").yxselect_user({
		hiddenId : 'purcherId'
	});

	/**
	 * 验证信息
	 */
	validate({
		"purcherName" : {
			required : true
		},
		"acceptDate" : {
			custom : ['date']
		},
		"arrivaDate" : {
			custom : ['date']
		}
	});

	//日期联动
	$("#arrivaDate").bind("focusout",function(){
		var dateHope=$(this).val();
		$.each($(':input[class^="txtshort datehope"]'),function(i,n){
			$(this).val(dateHope);
		})
	});

});
