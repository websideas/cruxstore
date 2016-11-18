(function($){
    
    $(document).on('click', ".preview-position", function(e){
		e.preventDefault();
        
        var $form = $(this).closest('.wpb_edit_form_elements'),
            $el_content = $form.find('.preview-position-content').show(),
            $el_image = $form.find('.preview-position-image'),
            $el_text = $form.find('.preview-position-text').html(''),
            $image = $form.find('.gallery_widget_attached_images_ids').val(),
            $size = $form.find('.img_size').val(),
            $group_parrent = $form.find('.vc_param_group-list'),
            $groups = $group_parrent.children('li.vc_param');
        
        $groups.each(function(){
            var $this = $(this),
                $label = $this.find('.values_label').val(),
                $top = parseFloat($this.find('.values_top').val()),
                $left = parseFloat($this.find('.values_left').val()),
                $color = $this.find('.values_color').val(); 
                
            if(isNaN($top)){
                $top = 0;
            }
            if(isNaN($left)){
                $left = 0;
            }
            
            $content = '<div class="tooltip-item '+$color+'" data-top="'+$top+'" data-left="'+$left+'" style="top: '+$top+'%; left: '+$left+'%;"><div class="tooltip-item-content" data-toggle="tooltip" title="'+$label+'"></div></div>'
            
            $el_text.append($content);
            
        });
        
        var data = {
			'action': 'get_thumbnail',
			'image': $image,
            'size' : $size
		};
        
		$.post(ajaxurl, data, function(response) {
			$el_image.html(response);
		});
        
        
        $( ".tooltip-item" ).draggable({
          stop: function( event, ui ) {
            $item = ui.helper;
            
            $top_n = parseFloat((ui.position.top / $el_image.height()) * 100);
            $left_n = parseFloat((ui.position.left / $el_image.width()) * 100);
            $top = Math.round($top_n * 10)/10;
            $left = Math.round($left_n * 10)/10;
            
            $item.attr('data-top', $top);
            $item.attr('data-left', $left);
            
            update_position();
            
          }
        });
        
        // Tooltips (bootstrap plugin activated)
        $('[data-toggle="tooltip"]').each(function(){
            var $this = $(this);
            $this.tooltip({container:"body", delay: { "show": 100, "hide": 50 }});
        });
        
        function update_position(){
            $( ".tooltip-item" ).each(function( $i ){
                var $this = $(this),
                    $param = $($groups[$i]),
                    $top_n = $this.attr('data-top'),
                    $left_n = $this.attr('data-left');
                
                if(isNaN($top_n)){
                    $top_n = 0;
                }
                if(isNaN($left_n)){
                    $left_n = 0;
                }
                
                $top_po = $param.find('.values_top').val($top_n),
                $left_po = $param.find('.values_left').val($left_n);
                
                
            });
        }
        
        
    });
    
    
    
    
    
    
    
})(jQuery);