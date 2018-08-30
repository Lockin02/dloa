var show_page = function(page) {
	$("#compensateGrid").yxgrid("reload");
};
$(function() {
	$("#compensateGrid").yxgrid({
		model : 'finance_compensate_compensatededuct',
		title : '赔偿扣款记录',
		isOpButton : false,
		isViewAction : false,
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
			display : 'compensateId',
			name : '赔偿单id',
			sortable : true,
			hide : true
		}, {
			name : 'compensateCode',
			display : '赔偿单编号',
			sortable : true,
			width : 110,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=finance_compensate_compensate&action=toView&id="+row.compensateId+"\",1,600,1000,"+row.compensateId+")'>"+v+"</a>";
			}
		}, {
			name : 'dutyType',
			display : '赔偿主体',
			datacode : 'PCZTLX',
			sortable : true,
			width : 80
		}, {
			name : 'dutyObjId',
			display : '赔偿对象id',
			sortable : true,
			hide : true
		}, {
			name : 'dutyObjName',
			display : '赔偿对象',
			sortable : true,
			width : 80
		}, {
			name : 'payType',
			display : '扣款方式',
			datacode : 'KKFS',
			sortable : true,
			width : 80
		}, {
			name : 'deductMoney',
			display : '扣款金额',
			sortable : true,
			width : 80,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'operateName',
			display : '操作人',
			sortable : true,
			width : 100
		}, {
			name : 'operateTime',
			display : '操作时间',
			sortable : true,
			width : 120
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 200
		}],
		//过滤数据
		comboEx : [{
		     text:'赔偿主体',
		     key:'dutyType',
		     datacode : 'PCZTLX'
		}],
		searchitems : [{
			display : "赔偿单编号",
			name : 'compensateCode'
		},{
			display : "赔偿对象",
			name : 'dutyObjName'
		}]
	});
});