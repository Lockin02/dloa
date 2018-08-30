/*******************************************************************************
 * *�������
 */

Ext.namespace("Ext.ux.combox");
Ext.ux.combox.MyGridComboBox = Ext.extend(Ext.form.ComboBox, {
	// typeAhead : true,
	mode : 'local',
	// height:500,
	/**
	 * ����Ĭ�Ͽ��
	 *
	 * @type Number
	 */
	listWidth : 550,
	/**
	 * ����������ťʱִ�е�Ĭ�ϲ���
	 *
	 * @type String
	 */
	triggerAction : 'all',
	/**
	 * ֵ����ʾ���ַ���
	 *
	 * @type String
	 */
	emptyText : '',
	/**
	 * �Ƿ�����������������߶ȸ����
	 *
	 * @type Boolean
	 */
	resizable : true,
	/**
	 * ��������֧��
	 *
	 * @type Boolean
	 */
	editable : true,
	// valueField : 'id',
	/**
	 * ����ý���ʱ����ѡ��һ���Ѿ����ڵı���
	 *
	 * @type Boolean
	 */
	selectOnFocus : true,
	/**
	 * ��������¼�
	 *
	 * @type Boolean
	 */
	enableKeyEvents : true,
	/**
	 * ���ر���ʼ��
	 *
	 * @type Boolean
	 */
	lazyLoad : true,
	/**
	 * ���Ĭ��ѡ����
	 *
	 * @type Number
	 */
	selectGridNumEx : 0,
	/**
	 * ����������ѡ�����������
	 *
	 * @type
	 */
	beforLoadRecords : [],
	/**
	 * ����ѡ�еļ�¼
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
	 * �ӳ���ʾ�����
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
	 * ģ��ƥ����ʾ�������
	 */
	showComboGrid : function() {
		if (!this.isExpanded()) {
			this.lazyLoad = false; // ����������
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
			// ģ��ƥ�书��
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
			collapse : function(t) { // �ر����Ч����������Ƿ������mygrid����Դ��
				// �ر�������¸�ֵѡ�м�¼
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
			blur : function(t) { // Ч����������Ƿ������mygrid����Դ��
				hiddenObj = document.getElementById(this.hiddenFieldId);
				if (hiddenObj && Ext.isEmpty(hiddenObj.value)) {
					t.setValue('');
				}
			},
			keydown : function(t, e) {
				if (document.getElementById(this.hiddenFieldId))
					document.getElementById(this.hiddenFieldId).value = ''; // ��������IDΪnull
				if (e.getKey() == 38) { // �ϼ�ͷ
					if (t.selectGridNumEx > 0) {
						t.selectGridNumEx--;
						t.myGrid.getSelectionModel().selectRow(
								t.selectGridNumEx, true);
						t.myGrid.getSelectionModel()
								.deselectRow(t.selectGridNumEx + 1);
					}
				} else if (e.getKey() == 40) { // �¼�ͷ
					if (t.selectGridNumEx < t.myGrid.store.getCount() - 1) {
						t.selectGridNumEx++;
						t.myGrid.getSelectionModel()
								.deselectRow(t.selectGridNumEx - 1);
						t.myGrid.getSelectionModel().selectRow(
								t.selectGridNumEx, true);
					}
				} else if (e.getKey() == 13) {
					if (t.myGrid.selectType == 'check') {// ��ѡ
						var records = t.myGrid.getSelectionModel()
								.getSelections();
						t.setValue(records);
					} else {
						var record = t.myGrid.getSelectionModel().getSelected();
						t.setValue(record);
					}
					t.myGrid.fireEvent("rowdblclick", t.myGrid); // ��ֲ�¼�
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
	// //��д��������ʹ��keyup�¼�
	// },
	onKeyUp : function(e) { // ��д
		if (this.editable !== false && !e.isSpecialKey()) {
			this.lastKey = e.getKey();
			// this.dqTask.delay(this.queryDelay);
			// //ȥ���˷���������ʹ�ü����¼�չ�����
			// ������ֿհ׵�BUG
		}
	},
	setValue : function(v) {

		// Ext.getCmp(this.displayField).getValue()
		var textArry = [];
		var valueArry = [];
		if (typeof(v) == 'object') {
			// this.vRecords = v;
			if (this.myGrid.selectType != 'check') {// ����ǵ�ѡ

				textArry.push(v.get(this.gridName));
				valueArry.push(v.get(this.gridValue));
			} else {
				// text = '';// ���text
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
	initMyGrid : function(combo) { // ��ʼ��mygrid
		var mygrid = combo.myGrid;
		// var isFirstExpand = false;
		// var comObj = Ext.getCmp(mygrid.id);
		// if(!mygrid.rendered && comObj){
		// comObj.destroy(); //�ݻٶ������´���
		// }
		if (!mygrid.rendered) {// ���ﲢ��ÿ��չ�������򶼴������ֻ�ڵ�һ�γ�ʼ����ʱ��������ϴγ�ʼ�����Ϊ��ͬxtype��ʱ����д���
			if (mygrid.xtype && this.oldGrid
					&& mygrid.xtype != this.oldGrid.xtype) {// ���ϴγ�ʼ�����Ϊ��ͬxtype
				this.oldGrid.destroy();// �ݻٶ�̬����������һ�����
				mygrid.selectType = '';
				mygrid.isToExcel = false;
				mygrid.isToPDF = false;
				mygrid.isReturn = false;
				mygrid = Ext.ComponentMgr.create(mygrid, mygrid.xtype);
			}
			// if (mygrid.xtype && mygrid.lazyLoad ==
			// true)//
			// ��������ͨ��xtype�������ӳټ���,��ͨ��xtype�������ʵ��
			else if (mygrid.xtype) {
				mygrid = Ext.ComponentMgr.create(mygrid, mygrid.xtype);
			}
			// isFirstExpand = true;
			this.oldGrid = mygrid;// Ϊ�˶�̬�������������Ҫ��¼��һ������������
			mygrid.render(this.gridId);
			// ���α��˫���༭�¼�
			if (mygrid.editFunction) {
				mygrid.removeListener('rowdblclick', mygrid.editFunction);
			}
			if (!this.hideTrigger) {
				if (mygrid.selectType == 'check') {// ��ѡ
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

				} else {// ��ѡ
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
			combo.myGrid = mygrid;// �Ѵ����õı�񴫻�ȥ
			// event
			if (mygrid.selectType != 'check') {// ��ѡ
				mygrid.store.on("load", function() { // ѡ�ֵ�һ��
							for (var i = 0; i < mygrid.store.getCount(); i++) { // �Ƴ�����ѡ��
								mygrid.getSelectionModel().deselectRow(i);
							}
							mygrid.getSelectionModel().selectRow(0, true);
							combo.selectGridNumEx = 0; // ��ʼ��ѡ����
						});
			} else {
				mygrid.store.on("beforeload", function() {// loadǰ���浱ҳѡ���ֵ
							combo.beforLoadRecords = combo.beforLoadRecords
									.concat(mygrid.getSelectionModel()
											.getSelections());
						});
				mygrid.store.on("load", function() { // �ж�ֵ�Ƿ�����id��ȣ���ѡ��
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
			var myform = combo.myForm; // �Ѷ��󴫸�myWinForm���ڹر�ʱ�ݻٸö�����Ϊ�ر�ʱ�ö��󲻻��Զ��ݻ�
			if (myform && myform.ownerCt
					&& Ext.isArray(myform.ownerCt.destroyArry)) {
				myform.ownerCt.destroyArry.push(mygrid);
			}
		}
		if (this.afterInitMyGrid) {
			this.afterInitMyGrid(combo);
		}
	}
		// ,collapse : function() { // ��д�ر��¼�--δ���
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