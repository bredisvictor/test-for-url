$(function(){
    
 
    $('.event').click(function(){
        var event = $(this).children('.eventName').text();
        $.ajax({
        method:'POST',
        data: {tournaments:event},
        url:'controller.php',
        success: function(data){
            
            window.location.href = 'tournaments/';
            
        }
        
    }); 
        
    });
    
    $('.event').click(function(){
        var event = $(this).children('.eventName').text();
        $.ajax({
        method:'POST',
        data: {getTikets:event},
        url:'../controller.php',
        success: function(data){
            
            window.location.href = '../tikets/';
            
        }
        
    }); 
        
    });
    
});