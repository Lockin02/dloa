/*
 * 文件上传控件
 */
$import("Ext.form.TextField");
$import("Ext.Button");
$import("Ext.Msg");
$package("Ext.ux.form");
Ext.ux.form.FileUploadField = Ext.extend(Ext.form.TextField, {
			/**
			 * @cfg {String} buttonText The button text to display on the upload
			 *      button (defaults to 'Browse...'). Note that if you supply a
			 *      value for {@link #buttonCfg}, the buttonCfg.text value will
			 *      be used instead if available.
			 */
			fileType : '',//可以上传的文件类型, 默认是任意类型。全部用小写形式，中间用逗号隔开，如: fileType : 'jpg,jpeg'
			buttonText : '选择...',
			/**
			 * @cfg {Boolean} buttonOnly True to display the file upload field
			 *      as a button with no visible text field (defaults to false).
			 *      If true, all inherited TextField members will still be
			 *      available.
			 */
			buttonOnly : false,
			/**
			 * @cfg {Number} buttonOffset The number of pixels of space reserved
			 *      between the button and the text field (defaults to 3). Note
			 *      that this only applies if {@link #buttonOnly} = false.
			 */
			buttonOffset : 3,
			/**
			 * @cfg {Object} buttonCfg A standard {@link Ext.Button} config
			 *      object.
			 */

			// private
			readOnly : true,

			/**
			 * @hide
			 * @method autoSize
			 */
			autoSize : Ext.emptyFn,

			// private
			initComponent : function() {
				Ext.ux.form.FileUploadField.superclass.initComponent.call(this);

				this.addEvents(
						/**
						 * @event fileselected Fires when the underlying file
						 *        input field's value has changed from the user
						 *        selecting a new file from the system file
						 *        selection dialog.
						 * @param {Ext.ux.form.FileUploadField}
						 *            this
						 * @param {String}
						 *            value The file value returned by the
						 *            underlying file input field
						 */
						'fileselected');
			},

			// private
			onRender : function(ct, position) {
				Ext.ux.form.FileUploadField.superclass.onRender.call(this, ct,
						position);

				this.wrap = this.el.wrap({
							cls : 'x-form-field-wrap x-form-file-wrap'
						});
				this.el.addClass('x-form-file-text');
				this.el.dom.removeAttribute('name');

				this.fileInput = this.wrap.createChild({
							id : this.getFileInputId(),
							//name : this.name || this.getId(),
							name : this.name,
							cls : 'x-form-file',
							tag : 'input',
							type : 'file',
							size : 1
						});

				var btnCfg = Ext.applyIf(this.buttonCfg || {}, {
							text : this.buttonText
						});
				this.button = new Ext.Button(Ext.apply(btnCfg, {
							renderTo : this.wrap,
							cls : 'x-form-file-btn'
									+ (btnCfg.iconCls ? ' x-btn-icon' : '')
						}));

				if (this.buttonOnly) {
					this.el.hide();
					this.wrap.setWidth(this.button.getEl().getWidth());
				}

				this.fileInput.on('change', function() {
							var v = this.fileInput.dom.value;
							//验证文件格式是否正确
							var extension = v.substring(v.lastIndexOf("."))
							if(this.fileType!=null && this.fileType!=''){
								if(extension==null || extension=='' || extension.length<2){
									Ext.Msg.alert('提示','请上传正确的文件！');
									return;
								}else{
									var fType = extension.substring(1, extension.length).toLowerCase();
									if(this.fileType.indexOf(fType)<0){
										Ext.Msg.alert('提示','请上传正确的文件！');
										return;
									}
								}
							}
							
							this.setValue(v);
							this.fireEvent('fileselected', this, v);
						}, this);
			},

			// private
			getFileInputId : function() {
				return this.id + '-file';
			},

			// private
			onResize : function(w, h) {
				Ext.ux.form.FileUploadField.superclass.onResize.call(this, w, h);

				this.wrap.setWidth(w);

				if (!this.buttonOnly) {
					var w = this.wrap.getWidth()
							- this.button.getEl().getWidth()
							- this.buttonOffset;
					this.el.setWidth(w);
				}
			},

			// private
			preFocus : Ext.emptyFn,

			// private
			getResizeEl : function() {
				return this.wrap;
			},

			// private
			getPositionEl : function() {
				return this.wrap;
			},

			// private
			alignErrorIcon : function() {
				this.errorIcon.alignTo(this.wrap, 'tl-tr', [2, 0]);
			}

		});
Ext.reg('fileuploadfield', Ext.ux.form.FileUploadField);