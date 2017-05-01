app.directive("showMoreRecipes",['$window', function($window){
    return{
        restrict: 'A',
        link: function(scope, element, attrs, ngModel)
        {
            scope.allRecipes = angular.fromJson(attrs.allRecipes);

            function chooseRecipe(date, id, type, portions){
                bootbox.prompt("Сколько порций данного блюда вы планируете употребить? (максимально количество порций: " + portions + ")",
                    function(result){
                        if(result == null){
                            return true;
                        }
                        if(Number.isNaN(parseInt(result)) || parseInt(result) < 1){
                            return bootbox.alert({
                                size: "small",
                                title: "Ошибка!",
                                message: "Для количества порций вы должны ввести целоe положительное число",
                                callback: function(){}
                            })
                        }

                        if(parseInt(result) > parseInt(portions)){
                            return bootbox.alert({
                                size: "small",
                                title: "Ошибка!",
                                message: "Количество порций должно быть не более " + portions,
                                callback: function(){}
                            })
                        }

                        $.ajax({
                            url: '/choose-eating-recipe/' + date + '/' + id + '/' + type + '/' + portions,
                            type: "POST",
                            processData: false,
                            contentType: false
                        }).done(function (data) {
                            $window.location.reload();
                        });
                    });
            }

            scope.chooseRecipe = chooseRecipe;
        }
    };
}]);
