// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".trainGrid").yxegrid('reload');
};
$(function() {
			$(".trainGrid").yxegrid({
						// 如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=engineering_serviceContract_serviceContract&action=pageJson',
						model : 'engineering_serviceTrain_servicetrain',
//						objName : 'serviceContract',
						isToolBar : false,
						title : '培训计划',
						// action : 'pageJson',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
//								{
//									display : '合同编号',
//									name : 'contractNo',
//									sortable : true
//								},
//								{
//									display : '合同名称',
//									name : 'contractName',
//									editor : {
//										type : 'text'
//									},
//									sortable : true
//								},
								{
									display : '培训开始日期',
									name : 'beginDT'
								},
								{
									display : '培训结束日期',
									name : 'endDT'
								},
								{
									display : '培训人数',
									name : 'traNum'
								},
								{
									display : '培训地点',
									name : 'adress'
								},
								{
									display : '培训内容',
									name : 'content'
								},
								{
									display : '培训师要求',
									name : 'trainer'
								},
								{
									display : '是否结束',
									name : 'isOver'
								},
								{
									display : '结束时间',
									name : 'overDT'
								}
//									{
//									display : '客户名称',
//									name : 'Name',
//									sortable : true,
//									sortname : 'c.Name',
//									editor : {
//										defVal : '默认客户名称'
//									}
//								}, {
//									display : '区域负责人',
//									name : 'AreaLeader',
//									sortable : true
//								}, {
//									display : '销售工程师',
//									editor : {
//										type : 'text'
//									},
//									name : 'SellMan',
//									sortable : true
//								}, {
//									display : '客户类型',
//									name : 'TypeOne',
//									hiddenName : 'TypeOneName',// 设置提交的冗余值
//									datacode : 'KHLX',// 数据字典编码
//									editor : {
//										type : 'combo',
//										// 默认选择第一项
//										defValIndex : 1
//
//									},
//									sortable : true
//								}, {
//									display : '省份',
//									name : 'Prov',
//									datacode : 'PROVINCE',
//									sortable : true
//								}
								],
						// 快速搜索
						searchitems : [{
									display : '合同名称',
									name : 'contractName'
								}, {
									display : '培训地点',
									name : 'adress',
									isdefault : true
								}],
						// title : '客户信息',
						// 业务对象名称
						boName : '客户',
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					});

		});