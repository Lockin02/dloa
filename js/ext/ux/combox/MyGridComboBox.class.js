/*******************************************************************************
 * *下拉表格
 */

Ext.namespace("Ext.ux.combox");
Ext.ux.combox.MyGridComboBox = Ext.extend(Ext.form.ComboBox, {
	// typeAhead : true,
	mode : 'local',
	// height:500,
	/**
	 * 下拉默认宽度
	 *
	 * @type Number
	 */
	listWidth : 550,
	/**
	 * 单击触发按钮时执行的默认操作
	 *
	 * @type String
	 */
	triggerAction : 'all',
	/**
	 * 值空显示的字符串
	 *
	 * @type String
	 */
	emptyText : '',
	/**
	 * 是否可以拉动更改下拉高度跟宽度
	 *
	 * @type Boolean
	 */
	resizable : true,
	/**
	 * 键盘输入支持
	 *
	 * @type Boolean
	 */
	editable : true,
	// valueField : 'id',
	/**
	 * 当获得焦点时立刻选择一个已经存在的表项
	 *
	 * @type Boolean
	 */
	selectOnFocus : true,
	/**
	 * 激活键盘事件
	 *
	 * @type Boolean
	 */
	enableKeyEvents : true,
	/**
	 * 加载表格初始化
	 *
	 * @type Boolean
	 */
	lazyLoad : true,
	/**
	 * 表格默认选种行
	 *
	 * @type Number
	 */
	selectGridNumEx : 0,
	/**
	 * 用来缓存表格选择的数组数据
	 *
	 * @type
	 */
	beforLoadRecords : [],
	/**
	 * 保存选中的记录
	 *
	 * @type
	 */
	vRecords : [],
	store : new Ext.data.SimpleStore({
				fields : [],
				data : [[]]
			}),
	initComponent : function() {
		this.init();
		Ext.ux.combox.MyGridComboBox.superclass.initComponent.call(this);
	},
	/**
	 * 延迟显示表格函数
	 *
	 * @param {}
	 *            e
	 */
	delayShowGrid : function(e) {
		if (!e.isNavKeyPress()) {
			this.showGridTask.delay(500);
		}
	},
	/**
	 * 模糊匹配显示下拉表格
	 */
	showComboGrid : function() {
		if (!this.isExpanded()) {
			this.lazyLoad = false; // 不加载数据
			this.expand();
		}
		var mygrid = this.myGrid;
		mygrid.cleanSearch();
		// if (!Ext.isEmpty(this.getValue())) {
		mygrid.searchFields.push(this.gridName);
		mygrid.searchValues.push(this.getValue());
		// }
		mygrid.store.reload();

	},
	init : function() {
		this.gridId = Ext.id() + '-grid';
		this.tpl = "<tpl for='.'><div style='height:200px'><div id='"
				+ this.gridId + "' style='height:200px'></div></div></tpl>";

		this.on({
			// 模糊匹配功能
			render : {
				single : true,
				scope : this,
				fn : function() {
					this.showGridTask = new Ext.util.DelayedTask(
							this.showComboGrid, this);
					this.el.on('keyup', this.delayShowGrid, this);
				}
			},
			expand : this.initMyGrid,
			collapse : function(t) { // 关闭面板效验输入对象是否存在于mygrid数据源中
				// 关闭面板重新赋值选中记录
				if (t.myGrid.selectType == 'check') {
					var records = t.myGrid.getSelectionModel().getSelections();
					if (t.beforLoadRecords.length > 0) {
						t.vRecords = records.concat(t.beforLoadRecords).strip();
					} else
						t.vRecords = records;
				}
				hiddenObj = document.getElementById(this.hiddenFieldId);
				if (hiddenObj && Ext.isEmpty(hiddenObj.value)) {
					t.setValue('');
				}
			},
			blur : function(t) { // 效验输入对象是否存在于mygrid数据源中
				hiddenObj = document.getElementById(this.hiddenFieldId);
				if (hiddenObj && Ext.isEmpty(hiddenObj.value)) {
					t.setValue('');
				}
			},
			keydown : function(t, e) {
				if (document.getElementById(this.hiddenFieldId))
					document.getElementById(this.hiddenFieldId).value = ''; // 设置隐藏ID为null
				if (e.getKey() == 38) { // 上箭头
					if (t.selectGridNumEx > 0) {
						t.selectGridNumEx--;
						t.myGrid.getSelectionModel().selectRow(
								t.selectGridNumEx, true);
						t.myGrid.getSelectionModel()
								.deselectRow(t.selectGridNumEx + 1);
					}
				} else if (e.getKey() == 40) { // 下箭头
					if (t.selectGridNumEx < t.myGrid.store.getCount() - 1) {
						t.selectGridNumEx++;
						t.myGrid.getSelectionModel()
								.deselectRow(t.selectGridNumEx - 1);
						t.myGrid.getSelectionModel().selectRow(
								t.selectGridNumEx, true);
					}
				} else if (e.getKey() == 13) {
					if (t.myGrid.selectType == 'check') {// 多选
						var records = t.myGrid.getSelectionModel()
								.getSelections();
						t.setValue(records);
					} else {
						var record = t.myGrid.getSelectionModel().getSelected();
						t.setValue(record);
					}
					t.myGrid.fireEvent("rowdblclick", t.myGrid); // 移植事件
					t.collapse();
				}
			}
				// resize : function(t, adjWidth, adjHeight,
				// rawWidth,
				// rawHeight) {
				// alert(adjWidth)
				// t.myGrid.width = adjWidth;
				// //t.myGrid.height = adjHeight;
				// //t.myGrid.doLayout();
				//
				// }
		});
	},
	// initEvents : function(){
	// Ext.ux.combox.MyGridComboBox.superclass.initEvents.call(this);
	// //重写这样可以使用keyup事件
	// },
	onKeyUp : function(e) { // 重写
		if (this.editable !== false && !e.isSpecialKey()) {
			this.lastKey = e.getKey();
			// this.dqTask.delay(this.queryDelay);
			// //去掉此方法，才能使用键盘事件展开表格
			// 不会出现空白的BUG
		}
	},
	setValue : function(v) {

		// Ext.getCmp(this.displayField).getValue()
		var textArry = [];
		var valueArry = [];
		if (typeof(v) == 'object') {
			// this.vRecords = v;
			if (this.myGrid.selectType != 'check') {// 如果是单选

				textArry.push(v.get(this.gridName));
				valueArry.push(v.get(this.gridValue));
			} else {
				// text = '';// 清空text
				for (var i = 0, l = v.length; i < l; i++) {
					// alert(this.vRecords [i].get('subtotal'));
					textArry.push(v[i].get(this.gridName));
					valueArry.push(v[i].get(this.gridValue));
					// this.beforLoadRecords.push(v[i]);
				}

			}
			if (this.hiddenFieldId) {
				document.getElementById(this.hiddenFieldId).value = valueArry;
			}

		} else {
			if (v != undefined) {
				if (!Ext.isEmpty(v)
						&& this.hiddenFieldId != undefined
						&& v.indexOf(",") > 0
						&& document.getElementById(this.hiddenFieldId).value
								.indexOf(",") > 0) {
					textArry = v.split(",");
				} else
					textArry = v;
			}

		}
		Ext.form.ComboBox.superclass.setValue.call(this, textArry);

		if (!Ext.isEmpty(textArry)) {
			if (this.toolTip)
				this.toolTip.destroy();
			if (Ext.isArray(textArry)) {
				var tipText = "";
				for (var i = 0; i < textArry.length; i++) {
					tipText += (i + 1) + "." + textArry[i] + "<br>";
				}
				textArry = tipText;
			}
			this.toolTip = new Ext.ToolTip({
						target : this.id,
						html : textArry
					});
		}
	},
	initMyGrid : function(combo) { // 初始化mygrid
		var mygrid = combo.myGrid;
		// var isFirstExpand = false;
		// var comObj = Ext.getCmp(mygrid.id);
		// if(!mygrid.rendered && comObj){
		// comObj.destroy(); //摧毁对象重新创建
		// }
		if (!mygrid.rendered) {// 这里并非每次展开下拉框都创建表格，只在第一次初始化的时候或者与上次初始化表格为不同xtype的时候进行创建
			if (mygrid.xtype && this.oldGrid
					&& mygrid.xtype != this.oldGrid.xtype) {// 与上次初始化表格为不同xtype
				this.oldGrid.destroy();// 摧毁动态下拉表格的上一个表格
				mygrid.selectType = '';
				mygrid.isToExcel = false;
				mygrid.isToPDF = false;
				mygrid.isReturn = false;
				mygrid = Ext.ComponentMgr.create(mygrid, mygrid.xtype);
			}
			// if (mygrid.xtype && mygrid.lazyLoad ==
			// true)//
			// 如果表格是通过xtype并且是延迟加载,则通过xtype创建表格实例
			else if (mygrid.xtype) {
				mygrid = Ext.ComponentMgr.create(mygrid, mygrid.xtype);
			}
			// isFirstExpand = true;
			this.oldGrid = mygrid;// 为了动态更改下拉表格，需要记录上一次下拉表格对象
			mygrid.render(this.gridId);
			// 屏蔽表格双击编辑事件
			if (mygrid.editFunction) {
				mygrid.removeListener('rowdblclick', mygrid.editFunction);
			}
			if (!this.hideTrigger) {
				if (mygrid.selectType == 'check') {// 多选
					mygrid.getSelectionModel().on('selectionchange',
							function() {
								if (combo.noSelected != true) {
									var records = mygrid.getSelectionModel()
											.getSelections();
									// alert(combo.beforLoadRecord);
									if (combo.beforLoadRecords) {
										var newRecords = records
												.concat(combo.beforLoadRecords);
										newRecords = newRecords.strip();
										combo.setValue(newRecords);
									} else
										combo.setValue(records);
								}
								combo.noSelected = false;
							});
					mygrid.getSelectionModel().on('rowdeselect',
							function(t, rownum, record) {
								combo.vRecords = combo.vRecords
										.removeRecord(record);
								combo.beforLoadRecords = combo.vRecords;
								combo.noSelected = true;
								combo.setValue(combo.vRecords);
							});

				} else {// 单选
					mygrid.on('dblclick', function(e) {
								var record = mygrid.getSelectionModel()
										.getSelected();
								combo.setValue(record);
								// combo.setRawValue(record);
								combo.collapse();
							})
				}
			}
			if (mygrid.xtype && mygrid.lazyLoad == true
					&& combo.lazyLoad != false) {
				mygrid.store.reload();
			}
			combo.myGrid = mygrid;// 把创建好的表格传回去
			// event
			if (mygrid.selectType != 'check') {// 单选
				mygrid.store.on("load", function() { // 选种第一行
							for (var i = 0; i < mygrid.store.getCount(); i++) { // 移除所有选种
								mygrid.getSelectionModel().deselectRow(i);
							}
							mygrid.getSelectionModel().selectRow(0, true);
							combo.selectGridNumEx = 0; // 初始化选种行
						});
			} else {
				mygrid.store.on("beforeload", function() {// load前缓存当页选择的值
							combo.beforLoadRecords = combo.beforLoadRecords
									.concat(mygrid.getSelectionModel()
											.getSelections());
						});
				mygrid.store.on("load", function() { // 判断值是否跟表格id相等，是选中
							var valueArr = document.getElementById(combo.hiddenFieldId).value
									.split(",");
							// alert(valueArr);
							var textArr = combo.getValue().split(",");

							for (var j = 0, jl = valueArr.length; j < jl; j++) {
								if (!Ext.isEmpty(valueArr[j])) {
									// var Record =
									// Ext.data.Record.create({
									// name :
									// combo.gridName
									// }, {
									// name :
									// combo.gridValue
									// });
									// var record = new
									// Record({});
									// record.set(combo.gridName,
									// textArr[j]);
									// record.set(combo.gridValue,
									// valueArr[j]);
									//
									// combo.beforLoadRecords.push(record);

									for (var i = 0, il = mygrid.store
											.getCount(); i < il; i++) {
										if (valueArr[j] == mygrid.store
												.getAt(	i).get('id')) {
											mygrid.getSelectionModel()
													.selectRow(i, true);
											continue;
										}
									}
								}
							}

						});
			}
			// mygrid.on('keydown', function(e){
			// if(e.getKey() == 38){
			// if(mygrid.getSelectionModel().isSelected(0)){
			// combo.focus();
			// }
			// }
			// });
			var myform = combo.myForm; // 把对象传给myWinForm用于关闭时摧毁该对象，因为关闭时该对象不会自动摧毁
			if (myform && myform.ownerCt
					&& Ext.isArray(myform.ownerCt.destroyArry)) {
				myform.ownerCt.destroyArry.push(mygrid);
			}
		}
		if (this.afterInitMyGrid) {
			this.afterInitMyGrid(combo);
		}
	}
		// ,collapse : function() { // 重写关闭事件--未完成
		// if (!this.isExpanded()) {
		// return;
		// }
		// this.list.hide();
		// Ext.getDoc().un('mousewheel', this.collapseIf, this);
		// Ext.getDoc().un('mousedown', this.collapseIf, this);
		// this.fireEvent('collapse', this);
		// }
});
Ext.reg('combogrid', Ext.ux.combox.MyGridComboBox);