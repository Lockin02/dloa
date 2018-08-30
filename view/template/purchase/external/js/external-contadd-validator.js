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
		title : '�ɹ������嵥',
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
                //��ȡ����������ֶ�
                var itemArr = itemTable.yxeditgrid("getCmpByCol", "productNo");

                //ѭ������ԭ����
                itemArr.each(function(){
                	$(this).removeClass('txtshort').addClass('readOnlyTxtMiddle');
                });

                //���ڹ��񳤶�ʱ
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
//	            						$("#itemTable_cmp_executedNum"+rowNum).val('��');
//	            						$("#itemTable_cmp_dateIssued"+rowNum).val(tDay);
//	            						$("#itemTable_cmp_number"+rowNum).val('��');
//	            						$("#itemTable_cmp_issuedPurNum"+rowNum).val('��');
//
//	            					}
//	                        }
//	                    }
//	                });
//				}
//
//                //��ȡ����������ֶ�
//                var itemArr = itemTable.yxeditgrid("getCmpByCol", "productName");
//                //���ڹ��񳤶�ʱ
//                if(itemArr.length > 15){
//                	itemTable.attr("style", "overflow-x:auto;overflow-y:auto;height:550px;");
//                }
//			}
		},
		colModel : [ {
					name : 'purchType',
					display : '����',
					sortable : true,
					type : 'hidden'
				}, {
					name : 'productNumb',
					display : '���ϱ��',
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
			            						$("#itemTable_cmp_executedNum"+rowNum).val('��');
			            						$("#itemTable_cmp_dateIssued"+rowNum).val(tDay);
			            						$("#itemTable_cmp_number"+rowNum).val('��');
			            						$("#itemTable_cmp_issuedPurNum"+rowNum).val('��');
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
					display : '����Id',
					sortable : true,
					type : 'hidden'
				}, {
					name : 'productName',
					display : '��������',
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
//			            						$("#itemTable_cmp_executedNum"+rowNum).val('��');
//			            						$("#itemTable_cmp_dateIssued"+rowNum).val(tDay);
//			            						$("#itemTable_cmp_number"+rowNum).val('��');
//			            						$("#itemTable_cmp_issuedPurNum"+rowNum).val('��');
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
					display : '�����ͺ�',
					tclass : 'readOnlyTxtItem',
					readonly : true
				}, {
					name : 'qualityCode',
					display : '�ɹ�����',
					sortable : true,
					tclass : 'txtshort',
					type : 'select',
					datacode : 'CGZJSX'
				}, {
					name : 'testType',
					display : '���鷽ʽ',
					sortable : true,
					tclass : 'txtshort',
					type : 'select',
					options : optionsArr
				}, {
					name : 'exeNum',
					display : '�������',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'contNum',
					display : '��ͬ����',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'executedNum',
					display : '�ѳ�������',
					sortable : true,
					width : 60,
					type : 'statictext'
				}, {
					name : 'issuedPurNum',
					display : '����������',
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
					display : '�ɹ���������',
					sortable : true,
					tclass : 'txtshort',
					event : {
		            	'change' : function(e){
		            		var g= e.data.gird;
			        		var rowNum=e.data.rowNum;
			        		var remainNum=g.getCmpByRowAndCol(rowNum, 'remainNum'); // ��ǰ���
							var remainNums = parseInt(remainNum.val());
							var qty=$(this).val();//����
							if(isNaN(qty)){
								alert('��������ȷ������');
								g.getCmpByRowAndCol(rowNum, 'amountAll').val(remainNums);
								return;
							}
							if(qty<0){
								alert('��������ȷ������');
								g.getCmpByRowAndCol(rowNum, 'amountAll').val(remainNums);
								return;
							}
							qty = parseInt(qty);
							if(qty>remainNums){
								alert('��������ȷ������');
								g.getCmpByRowAndCol(rowNum, 'amountAll').val(remainNums);
								return;
							}
		                }
            		}
				}, {
					name : 'unitName',
					display : '��λ',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'dateIssued',
					display : '��������',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'dateHope',
					display : 'Ԥ�����������',
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
					display : '�������ڣ��죩',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'purchPeriod',
					display : '�ɹ����ڣ��죩',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'leastOrderNum',
					display : '��С������',
					sortable : true,
					tclass : 'readOnlyTxtShort',
					readonly : true
				}, {
					name : 'remark',
					display : '��ע',
					tclass : 'txt',
					sortable : true
				}]
	});
	validate({
    });

});


function getOptions(){
	var dataArr = ['ȫ��','���','���'];
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