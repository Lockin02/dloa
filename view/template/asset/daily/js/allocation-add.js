function editGridCreate(deptIdVal, agencyCode) {
	var param = {
		'useStatusCode' : 'SYZT-XZ',
		'isDel' : '0',
		'belongTo' : '0',
		'machineCodeSearch':'0',
		'isScrap' : '0'
	};
	if( deptIdVal!='' ){
		param.orgId=deptIdVal;
	}
	if( agencyCode!='' ){
		param.agencyCode=agencyCode;
	}

	/**
	 * ѡ��Ƭ���Զ��������ԭֵ����Ϣ
	 */
	var selectAssetFn = function(g, rowNum, rowData) {
		g.getCmpByRowAndCol(rowNum, 'sequence').val(rowData.sequence);
		g.getCmpByRowAndCol(rowNum, 'assetId').val(rowData.id);
		g.getCmpByRowAndCol(rowNum, 'assetCode').val(rowData.assetCode);
		g.getCmpByRowAndCol(rowNum, 'assetName').val(rowData.assetName);
		g.getCmpByRowAndCol(rowNum, 'englishName').val(rowData.englishName);
		g.getCmpByRowAndCol(rowNum, 'buyDate').val(rowData.buyDate);
		g.getCmpByRowAndCol(rowNum, 'spec').val(rowData.spec);
		g.getCmpByRowAndCol(rowNum, 'estimateDay').val(rowData.estimateDay);
		g.getCmpByRowAndCol(rowNum, 'alreadyDay').val(rowData.alreadyDay);
		g.getCmpByRowAndCol(rowNum, 'depreciation').val(rowData.depreciation);
		g.getCmpByRowAndCol(rowNum, 'salvage').val(rowData.salvage);
		g.getCmpByRowAndCol(rowNum, 'beforeUse').val(rowData.beforeUse);
		g.getCmpByRowAndCol(rowNum, 'afterUse').val(rowData.afterUse);
		g.getCmpByRowAndCol(rowNum, 'beforePlace').val(rowData.beforePlace);
		g.getCmpByRowAndCol(rowNum, 'afterPlace').val(rowData.afterPlace);
		
		var $equip = g.getCmpByRowAndCol(rowNum, 'equip');
		$equip.children().unbind("click");
		$equip.unbind("click");
		$equip.click((function(assetCode) {
			return function() {
				window.open('?model=asset_assetcard_equip&action=toPage&assetCode=' + assetCode);
			}
		})(rowData.assetCode));

	}
	$("#allocationTable").yxeditgrid({
		objName : 'allocation[allocationitem]',
		title : '��Ƭ��Ϣ',
		isAddOneRow : false,
		colModel : [{
//			display : 'id',
//			name : 'id',
//			type : 'hidden'
//		}, {
			display : 'sequence',
			name : 'sequence',
			type : 'hidden'
		}, {
			display : '��Ƭ���',
			name : 'assetCode',
			tclass : 'txt',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'allocationTable_cmp_assetId' + rowNum,
					nameCol : 'assetCode',
					gridOptions : {
						param : param,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetName = g.getCmpByRowAndCol(
												rowNum, 'assetName');
										$assetName.val(rowData.assetName);
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			validation : {
				required : true
			},
			process : function($input, rowData) {
				var rowNum = $input.data("rowNum");
				var g = $input.data("grid");
				$input.yxcombogrid_asset({
					hiddenId : 'allocationTable_cmp_assetId' + rowNum,
					gridOptions : {
						param : param,
						event : {
							row_dblclick : (function(rowNum) {
								return function(e, row, rowData) {
									var $cmps = g.getCmpByCol('assetCode');
									var isReturn = false;
									$cmps.each(function() {
										if ($(this).val() == rowData.assetCode) {
											alert("�벻Ҫѡ����ͬ���ʲ�.");
											isReturn = true;
										}
									});
									if (!isReturn) {
										var $assetCode = g.getCmpByRowAndCol(
												rowNum, 'assetCode');
										$assetCode.val(rowData.assetCode);
										selectAssetFn(g, rowNum, rowData);
									} else {
										return false;
									}
								}
							})(rowNum)
						}
					}
				});
			}
		}, {
			display : '�ʲ�Id',
			name : 'assetId',
			type : 'hidden'
		}, {
//			display : 'ʹ��״̬',
//			validation : {
//				required : true
//			},
//			name : 'useStatus',
//			tclass : 'txtshort',
//			type : 'select',
//			datacode : 'SYZT',
//			processData : function(data) {
//				var newData = [{
//					dataName : '',
//					dataCode : ''
//				}];
//				for (var i = 0; i < data.length; i++) {
//					newData.push(data[i]);
//				}
//				return newData;
//			}
//		}, {
			display : 'Ӣ������',
			name : 'englishName',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		}, {
			display : '��������',
			name : 'buyDate',
			// type:'date',
			type : 'hidden',
			readonly : true
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'txtshort',
			readonly : true
		}, {
			display : '�����豸',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				if (data) {
					var $href = $("<a>��ϸ</a>");
					$href.attr("href", "#");
					$href.click(function() {
						window
								.open('?model=asset_assetcard_equip&action=toPage&assetCode='
										+ data.assetCode);
					})
					return $href;
				} else {
					return '<a href="#" >��ϸ</a>';
				}
			}

		}, {
			display : '��������',
			name : 'estimateDay',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		}, {
			display : '�Ѿ�ʹ���ڼ���',
			name : 'alreadyDay',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		},
		// {
		// display : '���۾ɶ�',
		// name : 'monthDepr',
		// tclass : 'txtshort',
		// readonly : true
		// },
		{
			display : '���۾ɽ��',
			name : 'depreciation',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		}, {
			display : '�����ֵ',
			name : 'salvage',
			tclass : 'txtshort',
			type : 'hidden',
			readonly : true
		}, {
			display : '����ǰ��;',
			name : 'beforeUse',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '�������;',
			name : 'afterUse',
			tclass : 'txtshort'

		}, {
			display : '����ǰ��ŵص�',
			name : 'beforePlace',
			tclass : 'txtshort',
			type : 'hidden'
		}, {
			display : '������ŵص�',
			name : 'afterPlace',
			tclass : 'txtshort'

		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	//����ѡ��Ƭ��Ϣ��ť
	$("#allocationTable").find("tr:first td").append("<input type='button' value='ѡ��Ƭ��Ϣ' class='txt_btn_a' style='margin-left:10px;' onclick='selectCard();'/>");
}
$(function() {
	// ѡ��������������
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		mode : 'single',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});
	// ѡ�����ȷ�������
	$("#recipient").yxselect_user({
		hiddenId : 'recipientId',
		mode : 'single'
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
//		"billNo" : {
//			required : true
//		},
		"moveDate" : {
			required : true
		},
		"outDeptName" : {
			required : true
		},
		"inDeptName" : {
			required : true
		},
//		"outProName" : {
//			required : true
//		},
//		"inProName" : {
//			required : true
//		},
		"proposer" : {
			required : true
		},
		"recipient" : {
			required : true
		}
	});

});
function indeptAddSelect() {
	// ѡ����벿�����
	$("#inDeptName").yxselect_dept({
		hiddenId : 'inDeptId',
		mode : 'single'
	});
}
function outdeptAddSelect() {
	$("#allocationTable").yxeditgrid("remove");
	// ѡ������������
	$("#outDeptName").yxselect_dept({
		hiddenId : 'outDeptId',
		mode : 'single',
		event : {
			selectReturn : function(e, returnValue) {
				$("#allocationTable").yxeditgrid("remove");
				editGridCreate($("#outDeptId").val(), '');
			}
		}
	});
}

function inAgencyAddSelect() {
	// ѡ������������
	$("#inAgencyName").yxcombogrid_agency({
		hiddenId : 'inAgencyCode'
	});
}

function outAgencyAddSelect() {
	$("#allocationTable").yxeditgrid("remove");
	// ѡ������������
	$("#outAgencyName").yxcombogrid_agency({
		hiddenId : 'outAgencyCode',
		gridOptions : {
			showcheckbox : false
			,event : {
				'row_dblclick' : function(e, row, data) {
				$("#allocationTable").yxeditgrid("remove");
				editGridCreate('', data.agencyCode);
				}
			}
		}
	});
}
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_allocation&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}

$(function() {
	indeptAddSelect();
	outdeptAddSelect();
	editGridCreate($('#outDeptId').val());
//	$("#inProName").yxcombogrid_esmproject({
//		hiddenId : 'inProId',
//		gridOptions : {
//			param : {
//				'ExaStatus' : '���'
//			},
//			showcheckbox : false,
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#inDeptId").val(data.depId);
//					$("#inDeptName").val(data.depName);
//				}
//			}
//		}
//	});
//	$("#outProName").yxcombogrid_esmproject({
//		hiddenId : 'outProId',
//		gridOptions : {
//			param : {
//				'ExaStatus' : '���'
//			},
//			showcheckbox : false,
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#allocationTable").yxeditgrid("remove");
//					editGridCreate($("#outDeptId").val(), $("#outProId").val());
//					$("#outDeptId").val(data.depId);
//					$("#outDeptName").val(data.depName);
//				}
//			}
//		}
//	});
	$('#outAgencyType').removeClass("validate[required]")
	$('#inAgencyType').removeClass("validate[required]")
	$('.outAgencyType').hide();
	$('.inAgencyType').hide();
	$('#alloType').change(function() {
		if ($('#alloType').val() == 'DTD') {
			//��ʾ����
			$('.inDeptType').show();
			$('.outDeptType').show();
			//�Ƴ����������б�
			$("#outAgencyName").yxcombogrid_agency("remove");
			$("#inAgencyName").yxcombogrid_agency("remove");
			//ȡ���������
			$('#outAgencyName').removeClass("validate[required]")
			$('#inAgencyName').removeClass("validate[required]")
			//���ò��ű���
			$('#outDeptName').addClass("validate[required]")
			$('#inDeptName').addClass("validate[required]")
			//�������ÿ�д
			$('#outDeptName').removeClass("readOnlyTxtNormal").addClass("txt");
			$('#inDeptName').removeClass("readOnlyTxtNormal").addClass("txt");
			//������֯�������
			indeptAddSelect();
			outdeptAddSelect();
			//���������ÿ�
			$('.inAgencyType > input').val("");
			$('.inAgencyType').hide();
			$('.outAgencyType > input').val("");
			$('.outAgencyType').hide();
		} else if ($('#alloType').val() == 'ATA') {
			//��ʾ����
			$('.inAgencyType').show();
			$('.outAgencyType').show();
			//ȡ�����ű���
			$('#outDeptName').removeClass("validate[required]")
			$('#inDeptName').removeClass("validate[required]")
			//�����������
			$('#outAgencyName').addClass("validate[required]")
			$('#inAgencyName').addClass("validate[required]")
			//������֯��������д
			$('#outDeptName').removeClass("txt").addClass("readOnlyTxtNormal");
			$('#inDeptName').removeClass("txt").addClass("readOnlyTxtNormal");
			//�Ƴ���֯�������
			$("#inDeptName").yxselect_dept("remove");
			$("#outDeptName").yxselect_dept("remove");
			//�������������б�
			inAgencyAddSelect();
			outAgencyAddSelect();
			//���ز��Ų��ÿ�
			$('.inDeptType > input').val("");
			$('.inDeptType').hide();
			$('.outDeptType > input').val("");
			$('.outDeptType').hide();
		}
	});
})
//ѡ��Ƭ��Ϣ
function selectCard(){
	showOpenWin("?model=asset_assetcard_assetcard"
			+ "&action=selectCard&showType=allocation&agencyCode="
			+$("#outAgencyCode").val()
			+"&deptId="
			+$("#outDeptId").val(),1,500,900);
}
//���ÿ�Ƭ����
function setDatas(rows){
	var objGrid = $("#allocationTable");
	for(var i = 0; i < rows.length ; i++){
		//�жϿ�Ƭ�����Ƿ��Ѵ���
		var assetIdArr = objGrid.yxeditgrid("getCmpByCol","assetCode");
		var isExist = false;
		if(assetIdArr.length > 0){
			assetIdArr.each(function(){
				if(this.value == rows[i].assetCode){
					isExist = true;
					alert("�벻Ҫѡ����ͬ���ʲ�" );
					return false;
				}
			});
		}
		//����Ѿ��ظ��ˣ��Ͳ��ܼ���ѡ��
		if(isExist){
			return false;
		}
		//���»�ȡ����
		var tbRowNum = objGrid.yxeditgrid("getAllAddRowNum");
		//������
		objGrid.yxeditgrid("addRow",tbRowNum);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"sequence",rows[i].sequence);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetId",rows[i].id);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetCode",rows[i].assetCode);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"assetName",rows[i].assetName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"englishName",rows[i].englishName);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"buyDate",rows[i].buyDate);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"spec",rows[i].spec);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"estimateDay",rows[i].estimateDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"alreadyDay",rows[i].alreadyDay);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"depreciation",rows[i].depreciation);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"salvage",rows[i].salvage);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"beforeUse",rows[i].beforeUse);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"afterUse",rows[i].afterUse);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"beforePlace",rows[i].beforePlace);
		objGrid.yxeditgrid("setRowColValue",tbRowNum,"afterPlace",rows[i].afterPlace);
		
		var $equip = objGrid.yxeditgrid("getCmpByRowAndCol",tbRowNum, 'equip');
		$equip.children().unbind("click");
		$equip.unbind("click");
		$equip.click((function(assetCode) {
			return function() {
				window.open('?model=asset_assetcard_equip&action=toPage&assetCode=' + assetCode);
			}
		})(rows[i].assetCode));
	}
}