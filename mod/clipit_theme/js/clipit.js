/**
 * Collapse function
 */
$(function(){
    $(".collapse").click(function(event) {
        event.preventDefault();
        var $obj = $(this);
        var element_parent = $(this).closest(".elgg-module-widget");
        $(element_parent).find(".elgg-body").toggle("fast", function(){
            // complete
            $obj.toggleClass("fa-chevron-up fa-chevron-down");
        });
    });
});
