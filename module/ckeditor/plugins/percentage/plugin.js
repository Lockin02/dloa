CKEDITOR.plugins.add('percentage', {
	//lang:['zh-cn','en'],
	requires: ['dialog'],
	init: function(a)
	{
		var b = a.addCommand('percentage', new CKEDITOR.dialogCommand('percentage'));
		a.ui.addButton('percentage', {
			label: '�ٷֱ�',
			icon: this.path + 'images/pic.gif',
			command: 'percentage'
			//icon: this.path + 'images/hello.png'
		});
		CKEDITOR.dialog.add('percentage', this.path + 'dialogs/percentage.js');
	}
});