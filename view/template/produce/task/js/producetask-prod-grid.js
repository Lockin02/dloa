var show_page = function(page) {
	$("#prodtaskGrid").yxgrid("reload");
};
$(function() {
	$("#prodtaskGrid").yxgrid({
		model : 'produce_task_producetask',
		action :　'getProdTaskPj',
		title : '生产任务单',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'docType',
			display : '单类型',
			sortable : true,
			hide : true
		}, {
			name : 'documentCode',
			display : '单据号',
			sortable : true
		}, {
			name : 'taskReqCode',
			display : '任务需求编号',
			sortable : true
		}, {
			name : 'taskReqId',
			display : '任务需求id',
			sortable : true,
			hide : true
		}, {
			name : 'taskReqName',
			display : '任务需求名称',
			sortable : true
		}, {
			name : 'materialCode',
			display : '物料编码',
			sortable : true
		}, {
			name : 'materialName',
			display : '物料名称',
			sortable : true
		}, {
			name : 'materialId',
			display : '物料id',
			sortable : true,
			hide : true
		}, {
			name : 'pattern',
			display : '规格型号',
			sortable : true,
			hide : true
		}, {
			name : 'costObject',
			display : '成本对象',
			sortable : true,
			hide : true
		}, {
			name : 'orgName',
			display : '单位名称',
			sortable : true
		}, {
			name : 'orgId',
			display : '单位id',
			sortable : true,
			hide : true
		}, {
			name : 'pProduceNum',
			display : '计划生产数量',
			sortable : true
		}, {
			name : 'workShop',
			display : '生产车间',
			sortable : true,
			hide : true
		}, {
			name : 'planStartDate',
			display : '计划开工日期',
			sortable : true
		}, {
			name : 'planEndDate',
			display : '计划完工日期',
			sortable : true
		}, {
			name : 'docSource',
			display : '单据来源',
			sortable : true,
			hide : true
		}, {
			name : 'storageInPer',
			display : '完成入库超收比例',
			sortable : true,
			hide : true
		}, {
			name : 'storageNotPer',
			display : '完成入库欠收比例',
			sortable : true,
			hide : true
		}, {
			name : 'storageUp',
			display : '完成入库上限',
			sortable : true,
			hide : true
		}, {
			name : 'storageDown',
			display : '完成入库下限',
			sortable : true,
			hide : true
		}, {
			name : 'docStatus',
			display : '单据状态',
			sortable : true
		}, {
			name : 'docReleaseDate',
			display : '单据下达日期',
			sortable : true,
			hide : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '创建人',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '创建人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建日期',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改日期',
			sortable : true,
			hide : true
		}],
		isEditAction : false,
		isViewAction : true,
		toViewConfig : {
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 1000,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 500
		},
		toAddConfig : {
			action : 'toProdTaskAdd',
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 1000,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 500
		}
	});
});