<script>
function reload(self){
	if(self.parent.show_page){
		self.parent.tb_remove();
		self.parent.show_page();//ˢ�±����
	}else if(window.opener){
		self.close();
		self.opener.show_page();
	}else{
		reload(self.parent);
	}

};
reload(self);

</script>