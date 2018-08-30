var show_page = function(page) {
	$("#compensateGrid").yxsubgrid("reload");
};
$(function() {
	$("#compensateGrid").yxsubgrid({
		model : 'finance_compensate_compensate',
		title : '个人赔偿信息',
		param : {
			ExaStatus : '完成',
			dutyType : 'PCZTLX-01',
			formStatusArr : '3,4'
		},
		isOpButton : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'formCode',
			display : '单据编号',
			sortable : true,
			width : 110,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=finance_compensate_compensate&action=toView&id="+row.id+"\",1,600,1000,"+row.id+")'>"+v+"</a>";
			}
		}, {
			name : 'formDate',
			display : '单据日期',
			sortable : true,
			width : 80
		}, {
			name : 'objId',
			display : '业务单id',
			sortable : true,
			hide : true
		}, {
			name : 'objType',
			display : '业务类型',
			sortable : true,
			width : 60,
			datacode : 'PCYWLX'
		}, {
			name : 'objCode',
			display : '业务单编号',
			sortable : true
		}, {
			name : 'dutyTypeName',
			display : '赔偿主体',
			sortable : true,
			width : 60
		}, {
			name : 'dutyObjName',
			display : '赔偿对象',
			sortable : true
		}, {
			name : 'formMoney',
			display : '单据金额',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'compensateMoney',
			display : '确认赔偿金额',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'deductMoney',
			display : '已收赔偿金额',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'formStatus',
			display : '单据状态',
			sortable : true,
			process : function(v){
				switch(v){
					case '3' : return '待录入扣款';break;
					case '4' : return '已完成';break;
					default : return v;
				}
			},
			width : 70
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 70
		}, {
			name : 'ExaDT',
			display : '审批日期',
			sortable : true,
			width : 70
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改时间',
			sortable : true,
			hide : true
		}, {
			name : 'confirmName',
			display : '金额确认人',
			sortable : true,
			width : 80
		}, {
			name : 'confirmTime',
			display : '金额确认时间',
			sortable : true,
			width : 120
		}, {
			name : 'comConfirmName',
			display : '赔偿确认人',
			sortable : true,
			width : 80
		}, {
			name : 'comConfirmTime',
			display : '赔偿确认时间',
			sortable : true,
			width : 120
		}, {
//			name : 'auditorName',
//			display : '财务经理审核',
//			sortable : true,
//			width : 80
//		}, {
//			name : 'auditTime',
//			display : '审核时间',
//			sortable : true,
//			width : 120
//		}, {
			name : 'closerName',
			display : '关闭人',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'closeTime',
			display : '关闭时间',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'relDocType',
			display : '源单类型',
			sortable : true,
			width : 60,
			datacode : 'PCYDLX'
		}, {
			name : 'relDocCode',
			display : '源单编号',
			sortable : true,
			width : 120
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=finance_compensate_compensatedetail&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productNo',
				display : '物料编号',
				width : 80
			},{
				name : 'productName',
				display : '物料名称',
				width : 140
			},{
				name : 'productModel',
				display : '规格型号',
				width : 140
			},{
				name : 'unitName',
				display : '单位',
				width : 60
			},{
				name : 'number',
				display : '数量',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				name : 'price',
				display : '单价',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				name : 'money',
				display : '金额',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},{
				name : 'compensateRate',
				display : '赔偿比例',
				process : function(v){
					return v + " %" ;
				},
				width : 80
			},{
				name : 'compensateMoney',
				display : '赔偿金额',
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			}]
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showOpenWin(
					"?model=finance_compensate_compensate&action=toView&id=" + row[p.keyField]
					,1,600,1100,row.id);
			}
		},
		menusEx : [{
			text : '录入扣款',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.formStatus == '3') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=finance_compensate_compensate&action=toDeduct&id="
	                    + row.id
	                    + "&skey=" + row.skey_
	                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
			}
		}],
		//过滤数据
		comboEx : [{
			text : '单据状态',
			key : 'formStatus',
			value : '3',
			data : [{
				text : '待录入扣款',
				value : '3'
			}, {
				text : '已完成',
				value : '4'
			}]
		}],
		searchitems : [{
			display : "单据编号",
			name : 'formCodeSearch'
		},{
			display : "业务单编号",
			name : 'objCodeSearch'
		},{
			display : "源单编号",
			name : 'relDocCodeSearch'
		}]
	});
});