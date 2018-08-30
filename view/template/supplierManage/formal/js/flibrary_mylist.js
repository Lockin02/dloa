// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".flibrarymyGrid").yxgrid("reload");
};
$(function() {
			$(".flibrarymyGrid").yxgrid({
						//如果传入url，则用传入的url，否则使用model及action自动组装
						// url :
						// '?model=customer_customer_customer&action=pageJson',
						model : 'supplierManage_formal_flibrary',
						action : 'mypageJson',
						title : "我负责的供应商",
						isToolBar : false,
						showcheckbox : false,
						//列信息
						colModel : [{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								}, {
									display : 'manageUserId',
									name : 'manageUserId',
									sortable : true,
									hide : true
								}, {
									display : '供应商名称',
									name : 'suppName',
									sortable : true,
									//特殊处理字段函数
									process : function(v,row) {
										return row.suppName;
									}
								},{
									display : '业务编号',
									name : 'busiCode',
									sortable : true
								},{
									display : '主营产品',
									name : 'products',
									sortable : true
								}, {
									display : '地址',
									name : 'address',
									sortable : true
								}, {
									display : '办公电话',
									name : 'plane',
									sortable : true
								}, {
									display : '传真',
									name : 'fax',
									sortable : true
								}, {
									display : '注册人',
									name : 'createName',
									sortable : true
								},{
									display : '注册人Id',
									name : 'createId',
									sortable : true,
									hide : true
								}, {
									display : '联系人',
									name : 'name',
									sortable : true
								}, {
									display : '状态',
									name : 'status',
									sortable : true
								}],
						//扩展按钮
						buttonsEx : [{
									name : 'aduit',
									text : '分配负责人',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row){
											showThickboxWin("?model=supplierManage_formal_flibrary&action=toAduitP&id="+row.id+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
										}else{
										   alert("请选中一条数据");
										}
									}
								}
								,{
									name : 'stoc',
									text : '启用供应商',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row && row.status=='禁用'){
											if( confirm("确定启动供应商【"+ row.suppName +"】？") ){
												location="?model=supplierManage_formal_flibrary&action=stoc&id="+row.id+"&skey="+row['skey_'];
											}
										}else{
											alert("请选中一条数据并且选中的数据状态只能是禁用");
										}
									}
								},
									{
									name : 'ctos',
									text : '禁用供应商',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row && row.status=='正常'){
											if( confirm("确定禁用供应商【"+ row.suppName +"】？") ){
												location="?model=supplierManage_formal_flibrary&action=ctos&id="+row.id+"&skey="+row['skey_'];
											}
										}else{
											alert("请选中一条数据并且选中的数据状态只能是正常");
										}
									}

								}],
						//扩展右键菜单
						menusEx : [{
									text : '查看',
									icon : 'view',
									action : function(row,rows,grid) {
										if(row){
											showThickboxWin("?model=supplierManage_formal_flibrary&action=toRead&id="+row.id+"&objCode="
											+row.objCode+"&skey="+row['skey_']+"&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
										}else{
										   alert("请选中一条数据");
										}
									}

								},{
									text : '分配负责人',
									icon : 'edit',
									action : function(row,rows,grid) {
										if(row){
											showThickboxWin("?model=supplierManage_formal_flibrary&action=toAduitP&id="+row.id+"&skey="+row['skey_']+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
										}else{
										   alert("请选中一条数据");
										}
									}

								},{
									name : 'stoc',
									text : '启用供应商',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.status == '禁用') {
											return true;
										}
										return false;
									},
									action : function(row,rows,grid) {
											if( confirm("确定启动供应商【"+ row.suppName +"】？") ){
												location="?model=supplierManage_formal_flibrary&action=stoc&id="+row.id+"&skey="+row['skey_'];
											}
										}
								},
									{
									name : 'ctos',
									text : '禁用供应商',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.status == '正常') {
											return true;
										}
										return false;
									},
									action : function(row,rows,grid) {
											if( confirm("确定禁用供应商【"+ row.suppName +"】？") ){
												location="?model=supplierManage_formal_flibrary&action=ctos&id="+row.id+"&skey="+row['skey_'];
											}

									}

								},{
									text : '删除供应商',
									icon : 'delete',
						//			showMenuFn : function(row) {
						//				if (row.ExaStatus == '完成') {
						//					return true;
						//				}
						//				return false;
						//			},
									action : function(row) {
										if(confirm('确认删除？')){
											$.ajax({
												type : "POST",
												url : "?model=supplierManage_formal_flibrary&action=delSupplier",
												data : {
													id : row.id
												},
												success : function(msg) {
													if (msg == 1) {
														alert('删除成功！');
														$(".flibrarymyGrid").yxgrid("reload");
													}else{
														alert('删除失败!');
													}
												}
											});
										}
									}
								}],
						//快速搜索
						searchitems : [{
									display : '供应商名称',
									name : 'suppName'
								},{
									display : '主营产品',
									name : 'mainProduct'
								}],
						// title : '客户信息',
						//业务对象名称
						boName : '供应商名称',
						//默认搜索字段名
						sortname : "updateTime",
						//默认搜索顺序
						sortorder : "DESC",
						//显示查看按钮
						isViewAction : true,
						//隐藏添加按钮
						isAddAction : false,
						//隐藏删除按钮
						isDelAction : false,
						//查看扩展信息
						toViewConfig : {
											text : '查看',
											/**
											 * 默认点击查看按钮触发事件
											 */
											toViewFn : function(p, g) {
												var c = p.toViewConfig;
												var w = c.formWidth ? c.formWidth : p.formWidth;
												var h = c.formHeight ? c.formHeight : p.formHeight;
												var rowObj = g.getSelectedRow();
												if (rowObj) {
													showThickboxWin("?model="
															+ p.model
															+ "&action="
															+ p.toViewConfig.action
															+ c.plusUrl
															+ "&id="
															+ rowObj.data('data').id
															+"&objCode="
															+ rowObj.data('data').objCode
															+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
															+ 600 + "&width=" + 800);
												} else {
													alert('请选择一行记录！');
												}
											},
											/**
											 * 加载表单默认调用的后台方法
											 */
											action : 'toRead'
			},

						//修改扩展信息
						toEditConfig : {
											text : '编辑',
											/**
											 * 默认点击编辑按钮触发事件
											 */
											toEditFn : function(p, g) {
												var c = p.toEditConfig;
												var w = c.formWidth ? c.formWidth : p.formWidth;
												var h = c.formHeight ? c.formHeight : p.formHeight;
												var rowObj = g.getSelectedRow();
												if (rowObj) {
													showThickboxWin("?model="
															+ p.model
															+ "&action="
															+ c.action
															+ c.plusUrl
															+ "&id="
															+ rowObj.data('data').id
															+"&objCode="
															+ rowObj.data('data').objCode
															+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
															+ 600 + "&width=" + 800);
												} else {
													alert('请选择一行记录！');
												}
											},
											/**
											 * 加载表单默认调用的后台方法
											 */
											action : 'toEdit'

										}
					});

		});