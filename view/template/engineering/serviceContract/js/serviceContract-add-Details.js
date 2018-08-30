// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".detailsGrid").yxegrid('reload');
};
$(function() {
			$(".detailsGrid").yxegrid({
						// 如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_serviceContract_serviceContract',
//						objName : 'serviceContract',
						// action : 'pageJson',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '客户名称',
									name : 'Name',
									sortable : true,
									sortname : 'c.Name',
									editor : {
										defVal : '默认客户名称'
									}
								}, {
									display : '区域负责人',
									name : 'AreaLeader',
									sortable : true
								}, {
									display : '销售工程师',
									editor : {
										type : 'text'
									},
									name : 'SellMan',
									sortable : true
								}, {
									display : '客户类型',
									name : 'TypeOne',
									hiddenName : 'TypeOneName',// 设置提交的冗余值
									datacode : 'KHLX',// 数据字典编码
									editor : {
										type : 'combo',
										// 默认选择第一项
										defValIndex : 1

									},
									sortable : true
								}, {
									display : '省份',
									name : 'Prov',
									datacode : 'PROVINCE',
									sortable : true
								}],
						// 快速搜索
						searchitems : [
								{
									display : '合同名称',
									name : 'orderName'
								},{
									display : '合同编号',
									name : 'orderCodeOrTempSearch'
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