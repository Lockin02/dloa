$(function() {

	//发货清单
	if ($("#isSubAppChange").val() == '1') {
		var param = {
			'contractId' : $("#contractId").val(),
			'isDel' : '0',
			'isBorrowToorder' : '0'
		};
	} else {
		var param = {
			'contractId' : $("#contractId").val(),
			'isTemp' : '0',
			'isDel' : '0',
			'isBorrowToorder' : '0'
		};
	}
	$("#equInfo").yxeditgrid({
		objName : 'contract[equ]',
		url : '?model=contract_contract_equ&action=deliveryListJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : param,
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '需求数量',
			name : 'number',
			tclass : 'txtshort'
		},{
			display : '库存数量',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '在途数量',
			name : 'onwayAmount',
			tclass : 'txtshort'
		}]
	});

	//无需下达任务
	if ($("#productIds").val() != '') {
		var noParam = {
			noProductIds : $("#productIds").val()
		}
		$.extend(noParam ,param);
	} else {
		var noParam = param;
	}
	$("#unneedInfo").yxeditgrid({
		objName : 'contract[unneed]',
		url : '?model=contract_contract_equ&action=deliveryListJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : noParam,
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '需求数量',
			name : 'number',
			tclass : 'txtshort'
		},{
			display : '已执行数量',
			name : 'executedNum',
			tclass : 'txtshort'
		},{
			display : '未执行数量',
			name : 'backNum',
			tclass : 'txtshort',
			process : function (v ,row) {
				return row.number - row.backNum;
			}
		},{
			display : '库存数量',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '在途数量',
			name : 'onwayAmount',
			type : 'txtshort'
		},{
			display : '在途物料预计到货期',
			name : 'daohuoqi',
			type : 'txtshort'
		}]
	});

	//采购任务
	$("#basicInfo").yxeditgrid({
		objName : 'contract[equipment]',
		url : '?model=purchase_plan_equipment&action=deliveryListJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			basicIds : $("#basicIds").val() ? $("#basicIds").val() : 0
		},
		colModel : [{
			display : '物料编号',
			name : 'productNumb',
			tclass : 'txt'
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '需求数量',
			name : 'amountAllOld',
			tclass : 'txtshort'
		},{
			display : '已执行数量',
			name : 'amountIssued',
			tclass : 'txtshort'
		},{
			display : '未执行数量',
			name : 'failNum',
			tclass : 'txtshort',
			process : function (v ,row) {
				return row.amountAll - row.amountIssued;
			}
		},{
			display : '库存数量',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '在途数量',
			name : 'onwayAmount',
			tclass : 'txtshort'
		},{
			display : '采购任务数量',
			name : 'amountAll',
			type : 'txtshort'
		},{
			display : '采购预计到货期',
			name : 'dateHope',
			type : 'txtshort'
		},{
			display : '入库数量',
			name : 'stockNum',
			type : 'txtshort'
		}]
	});

	//生产任务
	$("#produceapplyInfo").yxeditgrid({
		objName : 'contract[produceapply]',
		url : '?model=produce_apply_produceapplyitem&action=contractListJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			mainIdArr : $("#produceapplyIds").val() ? $("#produceapplyIds").val() : 0,
			groupBy : 'c.id'
		},
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '需求数量',
			name : 'number',
			tclass : 'txtshort'
		},{
			display : '已执行数量',
			name : 'issuedProNum',
			tclass : 'txtshort'
		},{
			display : '未执行数量',
			name : 'failNum',
			tclass : 'txtshort',
			process : function (v ,row) {
				return row.number - row.issuedProNum;
			}
		},{
			display : '库存数量',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '生产任务数量',
			name : 'produceNum',
			type : 'txtshort'
		},{
			display : '生产预计完成时间',
			name : 'planEndDate',
			type : 'txtshort'
		},{
			display : '入库数量',
			name : 'stockNum',
			type : 'txtshort'
		}]
	});

	//加密锁任务
	$("#encryptionInfo").yxeditgrid({
		objName : 'contract[encryption]',
		url : '?model=stock_delivery_encryptionequ&action=listJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			sourceDocId : $("#contractId").val()
		},
		colModel : [{
			display : '物料编号',
			name : 'productCode',
			tclass : 'txt'
		},{
			display : '物料名称',
			name : 'productName',
			tclass : 'txt'
		},{
			display : '物料Id',
			name : 'productId',
			type : 'hidden'
		},{
			display : '需求数量',
			name : 'needNum',
			tclass : 'txtshort'
		},{
			display : '已执行数量',
			name : 'finshNum',
			tclass : 'txtshort'
		},{
			display : '未执行数量',
			name : 'failNum',
			tclass : 'txtshort',
			process : function (v ,row) {
				return row.needNum - row.finshNum;
			}
		},{
			display : '库存数量',
			name : 'exeNum',
			tclass : 'txtshort',
			process : function (v) {
				if (v > 0) {
					return v;
				} else {
					return 0;
				}
			}
		},{
			display : '加密锁任务数量',
			name : 'produceNum',
			type : 'txtshort'
		},{
			display : '加密锁预计完成时间',
			name : 'planFinshDate',
			type : 'txtshort'
		},{
			display : '入库数量',
			name : 'putNum',
			type : 'txtshort'
		}]
	});
});