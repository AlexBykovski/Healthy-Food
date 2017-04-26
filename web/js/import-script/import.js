app.directive("importDataToFile",[ function(){
    return{
        restrict: 'A',
        link: function(scope, element, attrs, ngModel)
        {
            scope.clickFunc = function(){
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

                    recipeData["name"] = newDiv.find("div.breadcrumb > span").html();
                    recipeData["photo"] = "http://daily-menu.ru" + newDiv.find("img.recipe_image_main").parent().attr("href");
                    recipeData["countPortions"] = parseInt(newDiv.find("#servingCount").html().trim());

                    recipeData["calories"] = parseFloat(cpfcBlock.find("tr:first > td.variable:nth-child(3) > strong").html());
                    recipeData["proteins"] = parseFloat(cpfcBlock.find("tr:first > td.variable:nth-child(4) > strong").html());
                    recipeData["fats"] = parseFloat(cpfcBlock.find("tr:first > td.variable:nth-child(5) > strong").html());
                    recipeData["carboh"] = parseFloat(cpfcBlock.find("tr:first > td.variable:nth-child(6) > strong").html());

                    recipeData["products"] = [];

                    $.each(productBlocks, function(index, item){
                        recipeData["products"].push({
                           "name" : $(item).find("td:first").html().trim(),
                           "count" : parseFloat($(item).find("td.variable:first").html().trim()),
                           "measure" : "гр"
                        });
                    });

                    recipeData["steps"] = [];

                    $.each(stepsBlock, function(index, item){
                        if($(item).html().trim().length && $.isNumeric($(item).html().trim()[0])){
                            recipeData["steps"].push($(item).html().trim());
                        }
                    });

                    $.cookie("recipeRemote", angular.toJson(recipeData));
                    console.log("done");
                });
            };
        }
    };
}]);
