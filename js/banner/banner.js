var banner = {

	starttop: function(){
		jQuery('#banner_top').after('<div class="pager-banner" id="pager-banner-top">').cycle({ 
		    fx: 'scrollHorz',
		    pager:  '#pager-banner-top' 
		});
	},

	startcontainer: function(){
		jQuery('#banner_container').after('<div class="pager-banner"  id="pager-banner-container">').cycle({ 
		    fx: 'scrollHorz',
		    pager:  '#pager-banner-container' 
		});
	},

	startcategory: function(){
		jQuery('#banner_category').after('<div class="pager-banner"  id="pager-banner-category">').cycle({ 
		    fx: 'scrollHorz',
		    pager:  '#pager-banner-category' 
		});
	}

	
}