// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".rdcontactGrid").yxgrid("reload");
};
$(function() {
			$(".rdcontactGrid").yxgrid({
						tittle : '联系人列表',
						//如果传入url，则用传入的url，否则使用model及action自动组装
						 //url : '',
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_temporary_stcontact',
//						action : 'getById',
						action : 'pageJson&parentId=' + $("#parentId").val(),
						isToolBar : false,
						showcheckbox:false,
						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : '联系人姓名',
									name : 'name',
									sortable : true,
									//特殊处理字段函数
									process : function(v,row) {
										return row.name;
									}
								},{
									display : '业务编号',
									name : 'busiCode',
									sortable : true
								},{
									display : '邮箱地址',
									name : 'email',
									sortable : true
								}, {
									display : '座机',
									name : 'plane',
									sortable : true
								}, {
									display : '传真',
									name : 'fax',
									sortable : true
								}],
						//扩展按钮
						buttonsEx : [],
						//扩展右键菜单
						menusEx : [
						{
							text : '查看',
							icon : 'view',
							action :function(row,rows,grid) {
								if(row){
									showThickboxWin("?model=supplierManage_temporary_stcontact&action=init"
										+ "&id="
										+ row.id
										+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
										+300 + "&width=" + 640);
								}else{
									alert("请选中一条数据");
								}
							}

						}],
						//快速搜索
						searchitems : [{
									display : '联系人姓名',
									name : 'name'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '供应商联系人',
						//默认搜索字段名
						sortname : "name",
						//默认搜索顺序
						sortorder : "ASC",
						//显示查看按钮
						isViewAction : true,
						isEditAction : false,
						isDelAction : false,
						isAddAction : false,
						//查看扩展信息
						toViewConfig : {
							action : 'toRead'
						}
					});

		});