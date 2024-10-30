jQuery(function($) {
    function ratingEnable() {
        
        if ($('.bars-1to10').length > 0) {
            $('.bars-1to10').each(function(){
                var currentRating = $(this).data('current-rating');  
                $(this).barrating('show', {
                    theme: 'bars-1to10',
                    showSelectedRating: false,
                    allowEmpty: true,
                    initialRating:currentRating
                });
                 if (typeof currentRating != 'undefined') {
                    $(this).barrating('readonly', true);
                }
            })
        }

        if ($('.bars-movie').length > 0) {
            $('.bars-movie').each(function(){
                var currentRating = $(this).data('current-rating');  
                $(this).barrating('show', {
                    theme: 'bars-movie',
                    allowEmpty: true,
                    showSelectedRating: false,
                    initialRating:currentRating
                });
                 if (typeof currentRating != 'undefined') {
                    $(this).barrating('readonly', true);
                }
            })
        }
        
        if ($('.bars-square').length > 0) {
            $('.bars-square').each(function(){
                var currentRating = $(this).data('current-rating');
                $(this).barrating('show', {
                    theme: 'bars-square',
                    showValues: true,
                    showSelectedRating: false,
                    allowEmpty: true,
                    initialRating:currentRating
                });
                
                if (typeof currentRating != 'undefined') {
                    $(this).barrating('readonly', true);
                }
            })
        }

         if ($('.bars-pill').length > 0) {
            $('.bars-pill').each(function(){ 
                var currentRating = $(this).data('current-rating');
                
                $(this).barrating('show', {
                    theme: 'bars-pill',
                    showValues: true,
                    showSelectedRating: false,
                    allowEmpty: true,
                    initialRating:currentRating
                });
                 if (typeof currentRating != 'undefined') {
                    $(this).barrating('readonly', true);
                }
            })
         }

        if ($('.bars-boxed').length > 0) {
            $('.bars-boxed').each(function(){
                var currentRating = $(this).data('current-rating');
                $(this).barrating('show', {
                    theme: 'bars-reversed',
                    showSelectedRating: false,
                    reverse: false,
                    allowEmpty: true,
                    initialRating:currentRating
                });
                 if (typeof currentRating != 'undefined') {
                    $(this).barrating('readonly', true);
                }
            })
        }
        
        if ($('.fontawesome-stars').length > 0) {
            $('.fontawesome-stars').each(function(){
                var currentRating = $(this).data('current-rating');
                $(this).barrating({
                    theme: 'fontawesome-stars',
                    showSelectedRating: false,
                    allowEmpty: true,
                    initialRating:currentRating,
                });
                 if (typeof currentRating != 'undefined') {
                    $(this).barrating('readonly', true);
                }
            }) 
        }
        
    }
    
    if ($('.fontawesome-stars-o').length > 0) {
    $('.fontawesome-stars-o').each(function(){
            var currentRating = $(this).data('current-rating');
            $(this).barrating({
               theme: 'fontawesome-stars-o',
               showSelectedRating: false,
               initialRating: currentRating,
               onSelect: function(value, text) {
                   
               },
               onClear: function(value, text) {
                   
               }
           });
            if (typeof currentRating != 'undefined') {
               $(this).barrating('readonly', true);
           }
        })
    }

    ratingEnable();

});

    
