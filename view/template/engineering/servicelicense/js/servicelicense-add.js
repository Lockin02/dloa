// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".licenseGrid").yxegrid('reload');
};
$(function() {
			$(".licenseGrid").yxegrid({
						// 如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'engineering_servicelicense_servicelicense',
//						objName : 'servicelicense',
						action : 'pageJson',
						// 列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '软件狗类型',
									name : 'softdogType',
									sortable : true,
									sortname : 'c.softdogType',
									editor : {
										defVal : '软件狗类型一'
									}
								}, {
									display : '数量',
									name : 'amount',
									sortable : true
								}, {
									display : 'License值',
									editor : {
										type : 'text'
									},
									name : 'licenseTypeIds',
									sortable : true
								}
//								, {
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
//								}
								, {
									display : 'License类型',
									name : 'licenseType',
									sortable : true
								}],
						//扩展按钮
						buttonsEx : [
							{
								name : 'submit',
								text : '提交所选License',
								icon : 'add',
								action : function(row,rows,grid){

								}
							}
						],
						// 快速搜索
						searchitems : [{
									display : '软件狗类型',
									name : 'customerType'
								}, {
									display : 'License类型',
									name : 'licenseType',
									isdefault : true
								}],
						// title : '客户信息',
						// 业务对象名称
						boName : 'License',
						// 默认搜索字段名
						sortname : "id",
						// 默认搜索顺序
						sortorder : "ASC"
					});

		});