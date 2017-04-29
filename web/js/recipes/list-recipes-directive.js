app.directive("showMoreRecipes",[ function(){
    return{
        restrict: 'A',
        link: function(scope, element, attrs, ngModel)
        {
            scope.allRecipes = angular.fromJson(attrs.allRecipes);
            console.log(scope.allRecipes);
            /*console.log("here");
            console.log(recipes);

            scope.allRecipes = [];*/
        }
    };
}]);
