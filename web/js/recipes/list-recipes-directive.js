app.directive("showMoreRecipes",['$window', function($window){
    return{
        restrict: 'A',
        link: function(scope, element, attrs, ngModel)
        {
            scope.allRecipes = angular.fromJson(attrs.allRecipes);

            function chooseRecipe(date, id, type, portions, portionWeight, calories){
                bootbox.prompt("Сколько порций данного блюда вы планируете употребить? (вес одной порции: " + parseFloat(portionWeight).toFixed(2) + "гр.) (максимально количество порций: " + portions + ")",
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
                            url: '/choose-eating-recipe/' + date + '/' + id + '/' + type + '/' + result,
                            type: "POST",
                            processData: false,
                            contentType: false
                        }).done(function (data) {
                            if(data.status === "calories"){ //unavailable recipe
                                var caloriesNeed = parseFloat(calories);
                                var availableCalories = parseFloat(data.available);

                                if(caloriesNeed > availableCalories){
                                    return bootbox.alert({
                                        size: "small",
                                        title: "Ошибка!",
                                        message: "Вы не можете выбрать это блюдо. В противном случае вы не сможете выбрать ни одного блюда из других приёмов пищи.",
                                        callback: function(){}
                                    })
                                }
                                else{
                                    var availablePortions = (availableCalories / caloriesNeed).toFixed(0);

                                    return bootbox.alert({
                                        size: "small",
                                        title: "Ошибка!",
                                        message: "Вы не можете выбрать такое количество порций. Максимальное доступное количество порций: " + availablePortions,
                                        callback: function(){}
                                    })
                                }
                            }
                            else if(data.status === "ok"){
                                $window.location.reload();
                            }
                        });
                    });
            }

            scope.chooseRecipe = chooseRecipe;
        }
    };
}]);
