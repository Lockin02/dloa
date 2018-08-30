$(document).ready(function (){
	var equIds = $("#equIds").val();
	var purchType = $("#purchType").val();
	var borrowId = $("#borrowId").val();
	var contractId = $("#contractId").val();
	var tDay = $("#tDay").val();
	var optionsArr = getOptions();
	var itemTable = $("#itemTable");
	itemTable.yxeditgrid({
		objName : 'basic[equipment]',
		url : "?model=purchase_external_external&action=externalJson",
		title : '采购申请清单',
		isAdd : false,
//		isAdd : false,
		param : {
			equIds : equIds,
			purchType :purchType,
			borrowId : borrowId,
			contractId : contractId
		},
		event:{
			'reloadData' : function(e){
                //获取表格上所有字段
                var itemArr = itemTable.yxeditgrid("getCmpByCol", "productNo");

                //循环禁用原数据
                itemArr.each(function(){
                	$(this).removeClass('txtshort').addClass('readOnlyTxtMiddle');
                });

                //大于够格长度时
                if(itemArr.length > 15){
                	itemTable.attr("style", "overflow-x:auto;overflow-y:auto;height:550px;");
                }
			}
//			'addRow' : function(e,rowNum,rowData){
//				if(!rowData){
//					var productNameObj = itemTable.yxeditgrid("getCmpByRowAndCol",rowNum,"productName");
//					productNameObj.removeClass('readOnlyTxtMiddle').addClass('txtmiddle');
//					var productIdObj = itemTable.yxeditgrid("getCmpByRowAndCol",rowNum,"productId");
//					productNameObj.yxcombogrid_product({
//	                    hiddenId: productIdObj.attr('productId'),
//                    	height : 230,
//	                    gridOptions: {
//	                    	showcheckbox : false,
//	            			param:{'notStatTypeArr':'TJCP,TJBCP'},
//	                        event : {
//	                        	'row_dblclick' : function(e, row, data){
//	            						$("#itemTable_cmp_productId"+rowNum).val(data.id);
//	            						$("#itemTable_cmp_pattem"+rowNum).val(data.pattem);
//	            						$("#itemTable_cmp_amountAll"+rowNum).val('');
//	            						$("#itemTable_cmp_unitName"+rowNum).val(data.unitName);
//	            						$("#itemTable_cmp_productId"+rowNum).val(data.productId);
//	            						$("#itemTable_cmp_productNumb"+rowNum).val(data.productCode);
//	            						$("#itemTable_cmp_productName"+rowNum).val(data.productName);
//	            						$("#itemTable_cmp_pattem"+rowNum).val(data.pattern);
//	            						$("#itemTable_cmp_arrivalPeriod"+rowNum).val(data.arrivalPeriod);
//	            						$("#itemTable_cmp_purchPeriod"+rowNum).val(data.purchPeriod);
//	            						$("#itemTable_cmp_leastOrderNum"+rowNum).val(data.leastOrderNum);
//	            						$("#itemTable_cmp_executedNum"+rowNum).val('无');
//	            						$("#itemTable_cmp_dateIssued"+rowNum).val(tDay);
//	            						$("#itemTable_cmp_number"+rowNum).val('无');
//	            						$("#itemTable_cmp_issuedPurNum"+rowNum).val('无');
//
//	            					}
//	                        }
//	                    }
//	                });
//				}
//
//                //获取表格上所有字段
//                var itemArr = itemTable.yxeditgrid("getCmpByCol", "productName");
//                //大于够格长度时
//                if(itemArr.length > 15){
//                	itemTable.attr("style", "overflow-x:auto;overflow-y:auto;height:550px;");
//                }
//			}
		},
		colModel : [ {
					name : 'purchType',
					display : '类型',
					sortable : true,
					type : 'hidden'
				}, {
					name : 'productNumb',
					display : '物料编号',
					sortable : true,
					process: function($input, rowData) {
	                       var rowNum = $input.data("rowNum");
			                var g = $input.data("grid");
			                $input.yxcombogrid_product({
			                    hiddenId: 'itemTable_cmp_productId' + rowNum,
			                    height: 250,
			                    gridOptions: {
			                        showcheckbox: false,
			                        isTitle: true,
			                        event: {
			                            "row_dblclick": (function(rowNum) {
			                                return function(e, row, rowData) {
			                                    $("#itemTable_cmp_productId"+rowNum).val(rowData.id);
			            						$("#itemTable_cmp_pattem"+rowNum).val(rowData.pattem);
			            						$("#itemTable_cmp_amountAll"+rowNum).val('');
			            						$("#itemTable_cmp_unitName"+rowNum).val(rowData.unitName);
			            						$("#itemTable_cmp_productId"+rowNum).val(rowData.productId);
			            						$("#itemTable_cmp_productNumb"+rowNum).val(rowData.productCode);
			            						$("#itemTable_cmp_productName"+rowNum).val(rowData.productName);
			            						$("#itemTable_cmp_pattem"+rowNum).val(rowData.pattern);
			            						$("#itemTable_cmp_arrivalPeriod"+rowNum).val(rowData.arrivalPeriod);
			            						$("#itemTable_cmp_purchPeriod"+rowNum).val(rowData.purchPeriod);
			            						$("#itemTable_cmp_leastOrderNum"+rowNum).val(rowData.leastOrderNum);
			            						$("#itemTable_cmp_executedNum"+rowNum).val('无');
			            						$("#itemTable_cmp_dateIssued"+rowNum).val(tDay);
			            						$("#itemTable_cmp_number"+rowNum).val('无');
			            						$("#itemTable_cmp_issuedPurNum"+rowNum).val('无');
			                                }
			                            })(rowNum)
			                        }
			                    }
			                });
	                 },
	                 validation: {
		                required: true
		            }
				}, {
					name : 'productId',
					display : '物料Id',
					sortable : true,
					type : 'hidden'
				}, {
					name : 'productName',
					display : '物料名称',
					sortable : true,
					tclass : 'readOnlyTxtItem',
					readonly : true
//					process: function($input, rowData) {
//	                       var rowNum = $input.data("rowNum");
//			                var g = $input.data("grid");
//			                $input.yxcombogrid_product({
//			                    hiddenId: 'itemTable_cmp_productId' + rowNum,
//			                    height: 250,
//			                    gridOptions: {
//			                        showcheckbox: false,
//			                        isTitle: true,
//			                        event: {
//			                            "row_dblclick": (function(rowNum) {
//			                                return function(e, row, rowData) {
//			                                    $("#itemTable_cmp_productId"+rowNum).val(rowData.id);
//			            						$("#itemTable_cmp_pattem"+rowNum).val(rowData.pattem);
//			            						$("#itemTable_cmp_amountAll"+rowNum).val('');
//			            						$("#itemTable_cmp_unitName"+rowNum).val(rowData.unitName);
//			            						$("#itemTable_cmp_productNumb"+rowNum).val(rowData.productCode);
//			            						$("#itemTable_cmp_productName"+rowNum).val(rowData.productName);
//			            						$("#itemTable_cmp_pattem"+rowNum).val(rowData.pattern);
//			            						$("#itemTable_cmp_arrivalPeriod"+rowNum).val(rowData.arrivalPeriod);
//			            						$("#itemTable_cmp_purchPeriod"+rowNum).val(rowData.purchPeriod);
//			            						$("#itemTable_cmp_leastOrderNum"+rowNum).val(rowData.leastOrderNum);
//			            						$("#itemTable_cmp_executedNum"+rowNum).val('无');
//			            						$("#itemTable_cmp_dateIssued"+rowNum).val(tDay);
//			            						$("#itemTable_cmp_number"+rowNum).val('无');
//			            						$("#itemTable_cmp_issuedPurNum"+rowNum).val('无');
//			                                }
//			                            })(rowNum)
//			                        }
//			                    }
//			                });
//	                 },
//					validation: {
//		                required: true
//		            }
				}, {
					name : 'pattem',
					display : '物料型号',
					tclass : 'readOnlyTxtItem',
					readonly : true
				}, {
					name : 'qualityCode',
					display : '采购属性',
					sortable : true,
					tclass : 'txtshort',
					type : 'select',
					datacode : 'CGZJSX'
				}, {
					name : 'testType',
					display : '检验方式',
					sortable : true,
					tclass : 'txtshort',
					type : 'select',
					options : optionsArr
				}, {
					name : 'exeNum',
					display : '库存数量',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'contNum',
					display : '合同数量',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'executedNum',
					display : '已出库数量',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'issuedPurNum',
					display : '已申请数量',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'remainNum',
					display : 'remainNum',
					sortable : true,
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					name : 'amountAll',
					display : '采购申请数量',
					sortable : true,
					tclass : 'txtshort',
					event : {
		            	'change' : function(e){
		            		var g= e.data.gird;
			        		var rowNum=e.data.rowNum;
			        		var remainNum=g.getCmpByRowAndCol(rowNum, 'remainNum'); // 当前金额
							var remainNums = parseInt(remainNum.val());
							var qty=$(this).val();//数量
							if(isNaN(qty)){
								alert('请输入正确数量！');
								g.getCmpByRowAndCol(rowNum, 'amountAll').val(remainNums);
								return;
							}
							if(qty<0){
								alert('请输入正确数量！');
								g.getCmpByRowAndCol(rowNum, 'amountAll').val(remainNums);
								return;
							}
							qty = parseInt(qty);
							if(qty>remainNums){
								alert('请输入正确数量！');
								g.getCmpByRowAndCol(rowNum, 'amountAll').val(remainNums);
								return;
							}
		                }
            		}
				}, {
					name : 'unitName',
					display : '单位',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'dateIssued',
					display : '申请日期',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'dateHope',
					display : '预期望完成日期',
					sortable : true,
					tclass : 'txtshort',
					type : 'date'
				}, {
					name : 'applyEquId',
					display : 'applyEquId',
					sortable : true,
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					name : 'equObjAssId',
					display : 'equObjAssId',
					sortable : true,
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					name : 'uniqueCode',
					display : 'uniqueCode',
					sortable : true,
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					name : 'purchType',
					display : 'purchType',
					sortable : true,
					tclass : 'txtshort',
					type : 'hidden'
				}, {
					name : 'arrivalPeriod',
					display : '交货周期（天）',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'purchPeriod',
					display : '采购周期（天）',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'leastOrderNum',
					display : '最小订单量',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'remark',
					display : '备注',
					tclass : 'txt',
					sortable : true
				}]
	});
	validate({
    });

});


function getOptions(){
	var dataArr = ['全检','免检','抽检'];
	var newArr = [];
	if(dataArr.length>0){
		for(var i=0;i<dataArr.length;i++){
			newArr.push({
				"name": dataArr[i], "value" : dataArr[i]
			});
		}
	}
	return newArr;
}