// 使用extjs的triggerfield.js和combo.js文件修改，并合并成单独文件以便管理
// MyComboBox
$import("Ext.data.SimpleStore");
$import("Ext.Layer");
$import("Ext.PagingToolbar");
$import("Ext.DataView");
$import("Ext.StoreMgr");
$import("Ext.KeyNav");
$import("Ext.util.DelayedTask");
$import("Ext.EventObject");
$import("Ext.lib.Extlib");
$package("Ext.ux.combox");
$import("Ext.ux.combox.MyTriggerField");
/**
 * 下拉选择：同时提交value跟text
 * 
 * @class Ext.ux.combox.MyComboBox
 * @extends Ext.ux.combox.MyTriggerField
 */
Ext.ux.combox.MyComboBox = Ext.extend(Ext.ux.combox.MyTriggerField, {
			defaultAutoCreate : {
				tag : "input",
				type : "text",
				size : "24",
				autocomplete : "off"
			},
			listClass : '',
			selectedClass : 'x-combo-selected',
			hideTrigger1 : true,
			trigger1Class : 'x-form-clear-trigger',
			trigger2Class : 'x-form-arrow-trigger',
			clearClass : 'x-form-clear-trigger',
			shadow : 'sides',
			listAlign : 'tl-bl?',
			maxHeight : 300,
			minHeight : 90,
			triggerAction : 'query',
			minChars : 4,
			typeAhead : false,
			queryDelay : 500,
			pageSize : 0,
			selectOnFocus : false,
			queryParam : 'query',
			loadingText : '加载中...',
			resizable : false,
			handleHeight : 8,
			editable : true,
			allQuery : '',
			mode : 'remote',
			minListWidth : 70,
			forceSelection : false,
			typeAheadDelay : 250,
			lazyInit : true,
			initSelect : false, // 扩展，用于判断是否是修改时调用setValue
			initComponent : function() {
				Ext.ux.combox.MyComboBox.superclass.initComponent.call(this);

				this.triggerConfig = {
					tag : 'span',
					cls : 'x-form-twin-triggers',
					cn : [{
								tag : "img",
								src : Ext.BLANK_IMAGE_URL,
								cls : "x-form-trigger " + this.trigger1Class
							}, {
								tag : "img",
								src : Ext.BLANK_IMAGE_URL,
								cls : "x-form-trigger " + this.trigger2Class
							}]
				};

				this.addEvents('expand', 'collapse', 'beforeselect', 'select',
						'beforequery');
				if (this.transform) {
					this.allowDomMove = false;
					var s = Ext.getDom(this.transform);
					if (!this.hiddenName) {
						this.hiddenName = s.name;
					}
					if (!this.store) {
						this.mode = 'local';
						var d = [], opts = s.options;
						for (var i = 0, len = opts.length; i < len; i++) {
							var o = opts[i];
							var value = (Ext.isIE
									? o.getAttributeNode('value').specified
									: o.hasAttribute('value'))
									? o.value
									: o.text;
							if (o.selected) {
								this.value = value;
							}
							d.push([value, o.text]);
						}
						this.store = new Ext.data.SimpleStore({
									'id' : 0,
									fields : ['value', 'text'],
									data : d
								});
						this.valueField = 'value';
						this.displayField = 'text';
					}
					s.name = Ext.id(); // wipe out the name in case somewhere
					// else they have a reference
					if (!this.lazyRender) {
						this.target = true;
						this.el = Class.forName("Ext.DomHelper").insertBefore(
								s, this.autoCreate || this.defaultAutoCreate);
						Ext.removeNode(s); // remove it
						this.render(this.el.parentNode);
					} else {
						Ext.removeNode(s); // remove it
					}
				} else if (Ext.isArray(this.store)) {
					if (Ext.isArray(this.store[0])) {
						this.store = new Ext.data.SimpleStore({
									fields : ['value', 'text'],
									data : this.store
								});
						this.valueField = 'value';
					} else {
						this.store = new Ext.data.SimpleStore({
									fields : ['text'],
									data : this.store,
									expandData : true
								});
						this.valueField = 'text';
					}
					this.displayField = 'text';
					this.mode = 'local';
				}

				this.selectedIndex = -1;
				if (this.mode == 'local') {
					if (this.initialConfig.queryDelay === undefined) {
						this.queryDelay = 10;
					}
					if (this.initialConfig.minChars === undefined) {
						this.minChars = 0;
					}
				}
			},
			onRender : function(ct, position) {
				Ext.ux.combox.MyComboBox.superclass.onRender.call(this, ct,
						position);
				this.initSelect = false; // 初始化没有执行选择操作
				if (this.hiddenName) {
					this.hiddenField = this.el.insertSibling({
								tag : 'input',
								type : 'hidden',
								name : this.hiddenName,
								id : (this.hiddenId || this.hiddenName)
							}, 'before', true);
					// this.el.dom.removeAttribute('name');
				} else {// 不存在隐藏域值，则移除name,把name创建成隐藏域(实现不保存名称只保存ID)
					this.hiddenField = this.el.insertSibling({
								tag : 'input',
								type : 'hidden',
								name : this.name,
								id : this.name
							}, 'before', true);
					this.el.dom.removeAttribute('name');
				}
				if (Ext.isGecko) {
					this.el.dom.setAttribute('autocomplete', 'off');
				}

				if (!this.lazyInit) {
					this.initList();
				} else {
					this.on('focus', this.initList, this, {
								single : true
							});
				}

				if (!this.editable) {
					this.editable = true;
					this.setEditable(false);
				}
			},
			initValue : function() {
				Ext.ux.combox.MyComboBox.superclass.initValue.call(this);
				if (this.hiddenField) {
					this.hiddenField.value = this.hiddenValue !== undefined
							? this.hiddenValue
							: this.value !== undefined ? this.value : '';
				}
			},
			initList : function() {
				if (!this.list) {
					var cls = 'x-combo-list';

					this.list = new Ext.Layer({
								shadow : this.shadow,
								cls : [cls, this.listClass].join(' '),
								constrain : false
							});

					var lw = this.listWidth
							|| Math
									.max(this.wrap.getWidth(),
											this.minListWidth);
					this.list.setWidth(lw);
					this.list.swallowEvent('mousewheel');
					this.assetHeight = 0;

					if (this.title) {
						this.header = this.list.createChild({
									cls : cls + '-hd',
									html : this.title
								});
						this.assetHeight += this.header.getHeight();
					}

					this.innerList = this.list.createChild({
								cls : cls + '-inner'
							});
					this.innerList.on('mouseover', this.onViewOver, this);
					this.innerList.on('mousemove', this.onViewMove, this);
					this.innerList.setWidth(lw - this.list.getFrameWidth('lr'));

					if (this.pageSize) {
						this.footer = this.list.createChild({
									cls : cls + '-ft'
								});
						this.pageTb = new Ext.PagingToolbar({
									store : this.store,
									pageSize : this.pageSize,
									renderTo : this.footer
								});
						this.assetHeight += this.footer.getHeight();
					}

					if (!this.tpl) {
						this.tpl = '<tpl for="."><div class="' + cls
								+ '-item">{' + this.displayField
								+ '}</div></tpl>';
					}
					this.view = new Ext.DataView({
								applyTo : this.innerList,
								tpl : this.tpl,
								singleSelect : true,
								selectedClass : this.selectedClass,
								itemSelector : this.itemSelector || '.' + cls
										+ '-item'
							});

					this.view.on('click', this.onViewClick, this);

					this.bindStore(this.store, true);

					if (this.resizable) {
						this.resizer = Class.forName("Ext.Resizable")
								.newInstance(this.list, {
											pinned : true,
											handles : 'se'
										});
						this.resizer.on('resize', function(r, w, h) {
									this.maxHeight = h - this.handleHeight
											- this.list.getFrameWidth('tb')
											- this.assetHeight;
									this.listWidth = w;
									this.innerList.setWidth(w
											- this.list.getFrameWidth('lr'));
									this.restrictHeight();
								}, this);
						this[this.pageSize ? 'footer' : 'innerList'].setStyle(
								'margin-bottom', this.handleHeight + 'px');
					}
				}
			},
			getStore : function() {
				return this.store;
			},
			bindStore : function(store, initial) {
				if (this.store && !initial) {
					this.store.un('beforeload', this.onBeforeLoad, this);
					this.store.un('load', this.onLoad, this);
					this.store.un('loadexception', this.collapse, this);
					if (!store) {
						this.store = null;
						if (this.view) {
							this.view.setStore(null);
						}
					}
				}
				if (store) {
					this.store = Ext.StoreMgr.lookup(store);

					this.store.on('beforeload', this.onBeforeLoad, this);
					this.store.on('load', this.onLoad, this);
					this.store.on('loadexception', this.collapse, this);

					if (this.view) {
						this.view.setStore(store);
					}
				}
			},
			initEvents : function() {
				Ext.ux.combox.MyComboBox.superclass.initEvents.call(this);

				this.keyNav = new Ext.KeyNav(this.el, {
							"up" : function(e) {
								this.inKeyMode = true;
								this.selectPrev();
							},

							"down" : function(e) {
								if (!this.isExpanded()) {
									this.onTrigger2Click();
								} else {
									this.inKeyMode = true;
									this.selectNext();
								}
							},

							"enter" : function(e) {
								this.onViewClick();
								this.delayedCheck = true;
								this.unsetDelayCheck.defer(10, this);
							},

							"esc" : function(e) {
								this.collapse();
							},

							"tab" : function(e) {
								this.onViewClick(false);
								return true;
							},

							scope : this,

							doRelay : function(foo, bar, hname) {
								if (hname == 'down' || this.scope.isExpanded()) {
									return Ext.KeyNav.prototype.doRelay.apply(
											this, arguments);
								}
								return true;
							},

							forceKeyDown : true
						});
				this.queryDelay = Math.max(this.queryDelay || 10,
						this.mode == 'local' ? 10 : 250);
				this.dqTask = new Ext.util.DelayedTask(this.initQuery, this);
				if (this.typeAhead) {
					this.taTask = new Ext.util.DelayedTask(this.onTypeAhead,
							this);
				}
				if (this.editable !== false) {
					this.el.on("keyup", this.onKeyUp, this);
				}
				if (this.forceSelection) {
					this.on('blur', this.doForce, this);
				}
			},

			onDestroy : function() {
				if (this.view) {
					Ext.destroy(this.view);
				}
				if (this.list) {
					if (this.innerList) {
						this.innerList.un('mouseover', this.onViewOver, this);
						this.innerList.un('mousemove', this.onViewMove, this);
					}
					this.list.destroy();
				}
				if (this.dqTask) {
					this.dqTask.cancel();
					this.dqTask = null;
				}
				this.bindStore(null);
				Ext.ux.combox.MyComboBox.superclass.onDestroy.call(this);
			},
			unsetDelayCheck : function() {
				delete this.delayedCheck;
			},
			fireKey : function(e) {
				if (e.isNavKeyPress() && !this.isExpanded()
						&& !this.delayedCheck) {
					this.fireEvent("specialkey", this, e);
				}
			},
			onResize : function(w, h) {
				Ext.ux.combox.MyComboBox.superclass.onResize.apply(this,
						arguments);
				if (this.list && this.listWidth === undefined) {
					var lw = Math.max(w, this.minListWidth);
					this.list.setWidth(lw);
					this.innerList.setWidth(lw - this.list.getFrameWidth('lr'));
				}
			},
			onEnable : function() {
				Ext.ux.combox.MyComboBox.superclass.onEnable.apply(this,
						arguments);
				if (this.hiddenField) {
					this.hiddenField.disabled = false;
				}
			},
			onDisable : function() {
				Ext.ux.combox.MyComboBox.superclass.onDisable.apply(this,
						arguments);
				if (this.hiddenField) {
					this.hiddenField.disabled = true;
				}
			},
			setEditable : function(value) {
				if (value == this.editable) {
					return;
				}
				this.editable = value;
				if (!value) {
					this.el.dom.setAttribute('readOnly', true);
					this.el.on('mousedown', this.onTrigger2Click, this);
					this.el.addClass('x-combo-noedit');
				} else {
					this.el.dom.removeAttribute('readOnly');
					this.el.un('mousedown', this.onTrigger2Click, this);
					this.el.removeClass('x-combo-noedit');
				}
			},
			onBeforeLoad : function() {
				if (!this.hasFocus) {
					return;
				}
				this.innerList.update(this.loadingText
						? '<div class="loading-indicator">' + this.loadingText
								+ '</div>'
						: '');
				this.restrictHeight();
				this.selectedIndex = -1;
			},
			onLoad : function() {
				if (!this.hasFocus) {
					return;
				}
				if (this.store.getCount() > 0) {
					this.expand();
					this.restrictHeight();
					if (this.lastQuery == this.allQuery) {
						if (this.editable) {
							this.el.dom.select();
						}
						if (!this.selectByValue(this.value, true)) {
							this.select(0, true);
						}
					} else {
						this.selectNext();
						if (this.typeAhead
								&& this.lastKey != Ext.EventObject.BACKSPACE
								&& this.lastKey != Ext.EventObject.DELETE) {
							this.taTask.delay(this.typeAheadDelay);
						}
					}
				} else {
					this.onEmptyResults();
				}
			},
			onTypeAhead : function() {
				if (this.store.getCount() > 0) {
					var r = this.store.getAt(0);
					var newValue = r.data[this.displayField];
					var len = newValue.length;
					var selStart = this.getRawValue().length;
					if (selStart != len) {
						this.setRawValue(newValue);
						this.selectText(selStart, newValue.length);
					}
				}
			},
			onSelect : function(record, index) {
				this.initSelect = true;
				if (this.fireEvent('beforeselect', this, record, index) !== false) {
					this.setValue(record.data[this.valueField
							|| this.displayField]);
					this.collapse();
					this.fireEvent('select', this, record, index);

					// if (this.getRawValue() != '') {
					// this.triggers[0].show();
					// }
				}
			},
			getValue : function() {
				if (this.valueField) {
					return typeof this.value != 'undefined' ? this.value : '';
				} else {
					return Ext.ux.combox.MyComboBox.superclass.getValue
							.call(this);
				}
			},
			clearValue : function() {
				if (this.hiddenField) {
					this.hiddenField.value = '';
				}
				this.setRawValue('');
				this.lastSelectionText = '';
				this.applyEmptyText();
				this.value = '';
			},
			setValue : function(v) {
				var text = v;
				if (this.initSelect || !this.hiddenName) { // 手动修改操作 ||
					// 不存在隐藏域双附值
					if (this.valueField) {
						var r = this.findRecord(this.valueField, v);
						if (r) {
							text = r.data[this.displayField];
						} else if (this.valueNotFoundText !== undefined) {
							text = this.valueNotFoundText;
						}
					}
					if (this.hiddenField) {
						this.hiddenField.value = v;
					}
				} else { // 修改时调用setValue
					if (this.valueField) {
						var r = this.findRecord(this.displayField, v);
						if (r) {
							text = r.data[this.displayField];
							v = r.data[this.valueField];
						} else if (this.valueNotFoundText !== undefined) {
							text = this.valueNotFoundText;
						}
					}

					this.lastSelectionText = text;
					if (this.hiddenField) {
						this.hiddenField.value = v;
					}
				}
				this.lastSelectionText = text;
				Ext.ux.combox.MyComboBox.superclass.setValue.call(this, text);
				this.value = v;
				// if (this.getRawValue() != '') {
				// this.triggers[0].show();
				// }
			},
			findRecord : function(prop, value) {
				var record;
				if (this.store.getCount() > 0) {
					this.store.each(function(r) {
								if (r.data[prop] == value) {
									record = r;
									return false;
								}
							});
				}
				return record;
			},
			onViewMove : function(e, t) {
				this.inKeyMode = false;
			},
			onViewOver : function(e, t) {
				if (this.inKeyMode) { // prevent key nav and mouse over
					// conflicts
					return;
				}
				var item = this.view.findItemFromChild(t);
				if (item) {
					var index = this.view.indexOf(item);
					this.select(index, false);
				}
			},
			onViewClick : function(doFocus) {
				var index = this.view.getSelectedIndexes()[0];
				var r = this.store.getAt(index);
				if (r) {
					this.onSelect(r, index);
				}
				if (doFocus !== false) {
					this.el.focus();
				}
			},
			restrictHeight : function() {
				this.innerList.dom.style.height = '';
				var inner = this.innerList.dom;
				var pad = this.list.getFrameWidth('tb')
						+ (this.resizable ? this.handleHeight : 0)
						+ this.assetHeight;
				var h = Math.max(inner.clientHeight, inner.offsetHeight,
						inner.scrollHeight);
				var ha = this.getPosition()[1] - Ext.getBody().getScroll().top;
				var hb = Ext.lib.Dom.getViewHeight() - ha
						- this.getSize().height;
				var space = Math.max(ha, hb, this.minHeight || 0)
						- this.list.shadowOffset - pad - 5;
				h = Math.min(h, space, this.maxHeight);

				this.innerList.setHeight(h);
				this.list.beginUpdate();
				this.list.setHeight(h + pad);
				this.list.alignTo(this.wrap, this.listAlign);
				this.list.endUpdate();
			},
			reset : function() {
				Ext.ux.combox.MyComboBox.superclass.reset.call(this);
				if (this.clearFilterOnReset && this.mode == 'local') {
					this.store.clearFilter();
				}
			},
			onEmptyResults : function() {
				this.collapse();
			},
			isExpanded : function() {
				return this.list && this.list.isVisible();
			},
			selectByValue : function(v, scrollIntoView) {
				if (v !== undefined && v !== null) {
					var r = this.findRecord(this.valueField
									|| this.displayField, v);
					if (r) {
						this.select(this.store.indexOf(r), scrollIntoView);
						return true;
					}
				}
				return false;
			},
			select : function(index, scrollIntoView) {
				this.selectedIndex = index;
				this.view.select(index);
				if (scrollIntoView !== false) {
					var el = this.view.getNode(index);
					if (el) {
						this.innerList.scrollChildIntoView(el, false);
					}
				}
			},
			selectNext : function() {
				var ct = this.store.getCount();
				if (ct > 0) {
					if (this.selectedIndex == -1) {
						this.select(0);
					} else if (this.selectedIndex < ct - 1) {
						this.select(this.selectedIndex + 1);
					}
				}
			},
			selectPrev : function() {
				var ct = this.store.getCount();
				if (ct > 0) {
					if (this.selectedIndex == -1) {
						this.select(0);
					} else if (this.selectedIndex != 0) {
						this.select(this.selectedIndex - 1);
					}
				}
			},
			onKeyUp : function(e) {
				if (this.editable !== false && !e.isSpecialKey()) {
					this.lastKey = e.getKey();
					this.dqTask.delay(this.queryDelay);
				}
			},
			validateBlur : function() {
				return !this.list || !this.list.isVisible();
			},
			initQuery : function() {
				this.doQuery(this.getRawValue());
			},
			doForce : function() {
				if (this.el.dom.value.length > 0) {
					this.el.dom.value = this.lastSelectionText === undefined
							? ''
							: this.lastSelectionText;
					this.applyEmptyText();
				}
			},
			doQuery : function(q, forceAll) {
				if (q === undefined || q === null) {
					q = '';
				}
				var qe = {
					query : q,
					forceAll : forceAll,
					combo : this,
					cancel : false
				};
				if (this.fireEvent('beforequery', qe) === false || qe.cancel) {
					return false;
				}
				q = qe.query;
				forceAll = qe.forceAll;
				if (forceAll === true || (q.length >= this.minChars)) {
					if (this.lastQuery !== q) {
						this.lastQuery = q;
						if (this.mode == 'local') {
							this.selectedIndex = -1;
							if (forceAll) {
								this.store.clearFilter();
							} else {
								this.store.filter(this.displayField, q);
							}
							this.onLoad();
						} else {
							this.store.baseParams[this.queryParam] = q;
							this.store.load({
										params : this.getParams(q)
									});
							this.expand();
						}
					} else {
						this.selectedIndex = -1;
						this.onLoad();
					}
				}
			},
			getParams : function(q) {
				var p = {};
				if (this.pageSize) {
					p.start = 0;
					p.limit = this.pageSize;
				}
				return p;
			},
			collapse : function() {
				if (!this.isExpanded()) {
					return;
				}
				this.list.hide();
				Ext.getDoc().un('mousewheel', this.collapseIf, this);
				Ext.getDoc().un('mousedown', this.collapseIf, this);
				this.fireEvent('collapse', this);
			},
			collapseIf : function(e) {
				if (!e.within(this.wrap) && !e.within(this.list)) {
					this.collapse();
				}
			},
			expand : function() {
				if (this.isExpanded() || !this.hasFocus) {
					return;
				}
				this.list.alignTo(this.wrap, this.listAlign);
				this.list.show();
				this.innerList.setOverflow('auto'); // necessary for FF 2.0/Mac
				Ext.getDoc().on('mousewheel', this.collapseIf, this);
				Ext.getDoc().on('mousedown', this.collapseIf, this);
				this.fireEvent('expand', this);
			},
			onTrigger2Click : function() {
				if (this.disabled) {
					return;
				}
				if (this.isExpanded()) {
					this.collapse();
					this.el.focus();
				} else {
					this.onFocus({});
					if (this.triggerAction == 'all') {
						this.doQuery(this.allQuery, true);
					} else {
						this.doQuery(this.getRawValue());
					}
					this.el.focus();
				}
			},
			onTrigger1Click : function() {
				if (this.disabled) {
					return;
				}
				this.clearValue();
				// this.triggers[0].hide();
			}
		});
Ext.reg('mycombo', Ext.ux.combox.MyComboBox);
