app.directive("importDataToFile",['$cookies', function($cookies){
    return{
        restrict: 'A',
        link: function(scope, element, attrs, ngModel)
        {
            scope.clickFunc = function(type){
                console.log("processing");
                var url = $("#remote-rul").val();
                $.ajax({
                    url: '/get-url-data',
                    type: "POST",
                    data: url,//'http://daily-menu.ru/dailymenu/recipes/view/28',
                    processData: false,
                    contentType: false
                }).done(function (data) {
                    var d = data.substring(data.indexOf("<body>") + "<body>".length, data.indexOf("</body>"));
                    d = d.replace(/src"/g, '"');
                    var newDiv = $('<div></div>');
                    newDiv.append(d);
                    var recipeData = {};

                    var cpfcBlock = newDiv.find("section.recipe_calculation_container:nth-child(1) .recipe_calculation");
                    var productBlocks = newDiv.find("section.recipe_calculation_container:first .recipe_calculation tbody tr");
                    var stepsBlock = newDiv.find("#recipe_content_block > p");

                    recipeData["name"] = newDiv.find("div.breadcrumb > span").html().trim();
                    recipeData["photo"] = "http://daily-menu.ru" + newDiv.find("img.recipe_image_main").parent().attr("href").trim();
                    recipeData["countPortions"] = parseInt(newDiv.find("#servingCount").html().trim());

                    recipeData["calories"] = parseFloat(cpfcBlock.find("tr:first > td.variable:nth-child(3) > strong").html().trim());
                    recipeData["proteins"] = parseFloat(cpfcBlock.find("tr:first > td.variable:nth-child(4) > strong").html().trim());
                    recipeData["fats"] = parseFloat(cpfcBlock.find("tr:first > td.variable:nth-child(5) > strong").html().trim());
                    recipeData["carboh"] = parseFloat(cpfcBlock.find("tr:first > td.variable:nth-child(6) > strong").html().trim());

                    recipeData["products"] = [];

                    $.each(productBlocks, function(index, item){
                        var newObj = {};

                        newObj[$(item).find("td:first").html().trim()] = parseFloat($(item).find("td.variable:first").html().trim());
                        recipeData["products"].push(newObj);
                    });

                    recipeData["steps"] = [];

                    if(type === "numbers"){
                        $.each(stepsBlock, function(index, item){
                            if($(item).html().trim().length && $.isNumeric($(item).html().trim()[0])){
                                recipeData["steps"].push($(item).html().trim());
                            }
                        });
                    }
                    else if(type === "word"){
                        var start = false;

                        $.each(stepsBlock, function(index, item){
                            if(start && $(item).html().trim().length){
                                recipeData["steps"].push($(item).html().trim());
                            }

                            if($(item).html().trim().indexOf("Приготовление") > -1){
                                start = true;
                            }
                        });
                    }

                    else if(type === "list"){
                        $.each( newDiv.find("#recipe_content_block li"), function(index, item){
                            if($(item).html().trim().length){
                                recipeData["steps"].push($(item).html().trim());
                            }
                        });
                    }

                    //document.cookie = "recipeRemote=" + JSON.stringify(recipeData);
                    $cookies.putObject('recipeRemote', recipeData);

                    console.log("done");
                });
            };
        }
    };
}]);
