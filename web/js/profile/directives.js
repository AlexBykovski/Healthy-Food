app.directive("ageInputOnlyInteger",[ function(){
    return{
        restrict: 'A',
        link: function(scope, element, attrs)
        {
            element.on("keypress", function(ev){
                if(!$.isNumeric(ev.key) || element.val().length === 2){
                    ev.preventDefault();
                }
            });
        }
    };
}]);