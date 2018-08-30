// 使用extjs的triggerfield.js和combo.js文件修改，并合并成单独文件以便管理

// MyTriggerField
$import("Ext.form.TextField");
$import("Ext.BoxComponent");
$package("Ext.ux.combox");
Ext.ux.combox.MyTriggerField = Ext.extend(Ext.form.TextField, {
			defaultAutoCreate : {
				tag : "input",
				type : "text",
				size : "16",
				autocomplete : "off"
			},
			hideTrigger : false,
			hideFistTrigger : false,
			autoSize : Ext.emptyFn,
			monitorTab : true,
			deferHeight : true,
			mimicing : false,
			onResize : function(w, h) {
				Ext.ux.combox.MyTriggerField.superclass.onResize.call(this, w, h);
				//2.x版本ext
//				if (typeof w == 'number') {
//					this.el.setWidth(this.adjustWidth('input', w
//									- this.trigger.getWidth()));
//				}
//				this.wrap
//						.setWidth(this.el.getWidth() + this.trigger.getWidth());
				//3.x版本ext
		        var tw = this.getTriggerWidth();
		        if(Ext.isNumber(w)){
		            this.el.setWidth(w - tw);
		        }
		        this.wrap.setWidth(this.el.getWidth() + tw);		
			},
		    getTriggerWidth: function(){ //3.x版本ext
		        var tw = this.trigger.getWidth();
		        if(!this.hideTrigger && tw === 0){
		            tw = this.defaultTriggerWidth;
		        }
		        return tw;
		    },
			adjustSize : Ext.BoxComponent.prototype.adjustSize,
			getResizeEl : function() {
				return this.wrap;
			},
			getPositionEl : function() {
				return this.wrap;
			},
			alignErrorIcon : function() {
				if (this.wrap) {
					this.errorIcon.alignTo(this.wrap, 'tl-tr', [2, 0]);
				}
			},
			onRender : function(ct, position) {
				Ext.ux.combox.MyTriggerField.superclass.onRender.call(this, ct,
						position);
				this.wrap = this.el.wrap({
							cls : "x-form-field-wrap"
						});
				this.trigger = this.wrap.createChild(this.triggerConfig || {
					tag : "img",
					src : Ext.BLANK_IMAGE_URL,
					cls : "x-form-trigger " + this.triggerClass
				});
				if (this.hideTrigger) {
					this.trigger.setDisplayed(false);
				}
				this.initTrigger();
				if (!this.width) {
					this.wrap.setWidth(this.el.getWidth()
							+ this.trigger.getWidth());
				}

				// if (this.getRawValue() != '') {
				if(this.hideFistTrigger)
					this.triggers[0].show();
				// }
			},
			afterRender : function() {
				Ext.ux.combox.MyTriggerField.superclass.afterRender.call(this);
				var y;
				if (Ext.isIE && !this.hideTrigger
						&& this.el.getY() != (y = this.trigger.getY())) {
					this.el.position();
					this.el.setY(y);
				}
			},

			initTrigger : function() {
				var ts = this.trigger.select('.x-form-trigger', true);
				this.wrap.setStyle('overflow', 'hidden');
				var MyTriggerField = this;
				ts.each(function(t, all, index) {
							t.hide = function() {
								var w = MyTriggerField.wrap.getWidth();
								this.dom.style.display = 'none';
								MyTriggerField.el.setWidth(w
										- MyTriggerField.trigger.getWidth());
							};
							t.show = function() {
								var w = MyTriggerField.wrap.getWidth();
								this.dom.style.display = '';
								MyTriggerField.el.setWidth(w
										- MyTriggerField.trigger.getWidth());
							};
							var triggerIndex = 'Trigger' + (index + 1);

							if (this['hide' + triggerIndex]) {
								t.dom.style.display = 'none';
							}
							t.on("click", this['on' + triggerIndex + 'Click'],
									this, {
										preventDefault : true
									});
							t.addClassOnOver('x-form-trigger-over');
							t.addClassOnClick('x-form-trigger-click');
						}, this);
				this.triggers = ts.elements;
			},
			onTriggerClick : Ext.emptyFn,
			onDestroy : function() {
				if (this.trigger) {
					this.trigger.removeAllListeners();
					this.trigger.remove();
				}
				if (this.wrap) {
					this.wrap.remove();
				}
				Ext.ux.combox.MyTriggerField.superclass.onDestroy.call(this);
			},
			onFocus : function() {
				Ext.ux.combox.MyTriggerField.superclass.onFocus.call(this);
				if (!this.mimicing) {
					this.wrap.addClass('x-trigger-wrap-focus');
					this.mimicing = true;
					Ext.get(Ext.isIE ? document.body : document).on(
							"mousedown", this.mimicBlur, this, {
								delay : 10
							});
					if (this.monitorTab) {
						this.el.on("keydown", this.checkTab, this);
					}
				}
			},
			checkTab : function(e) {
				if (e.getKey() == e.TAB) {
					this.triggerBlur();
				}
			},
			onBlur : function() {
			},
			mimicBlur : function(e) {
				if (!this.wrap.contains(e.target) && this.validateBlur(e)) {
					this.triggerBlur();
				}
			},
			triggerBlur : function() {
				this.mimicing = false;
				Ext.get(Ext.isIE ? document.body : document).un("mousedown",
						this.mimicBlur, this);
				if (this.monitorTab && this.el) {
					this.el.un("keydown", this.checkTab, this);
				}
				this.beforeBlur();
				if (this.wrap) {
					this.wrap.removeClass('x-trigger-wrap-focus');
				}
				Ext.ux.combox.MyTriggerField.superclass.onBlur.call(this);
			},
			beforeBlur : Ext.emptyFn,
			validateBlur : function(e) {
				return true;
			},
			onDisable : function() {
				Ext.ux.combox.MyTriggerField.superclass.onDisable.call(this);
				if (this.wrap) {
					this.wrap.addClass(this.disabledClass);
					this.el.removeClass(this.disabledClass);
				}
			},
			onEnable : function() {
				Ext.ux.combox.MyTriggerField.superclass.onEnable.call(this);
				if (this.wrap) {
					this.wrap.removeClass(this.disabledClass);
				}
			},
			onShow : function() {
				if (this.wrap) {
					this.wrap.dom.style.display = '';
					this.wrap.dom.style.visibility = 'visible';
				}
			},
			onHide : function() {
				this.wrap.dom.style.display = 'none';
			},
			onTriggerClick : Ext.emptyFn
		});
Ext.reg('mytrigger', Ext.ux.combox.MyTriggerField);
