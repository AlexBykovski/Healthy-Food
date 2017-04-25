app.directive("proteinsChart",['$sce', function($sce){
    return{
        restrict: 'A',
        link: function(scope, element, attrs, ngModel)
        {
            var eatingData = angular.fromJson(attrs.eatingData);
            var elemValues = $(element).parent().find(".values-nutrient");
            setValues();

            var myChart = new Chart(element, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(eatingData),
                    datasets: [getDataSet()]
                },
                options: {
                    animation:{
                        animateScale:true
                    }
                }
            });

            function getDataSet(){
                var datasets = {
                    data : [],
                    backgroundColor : [],
                    hoverBackgroundColor : []
                };

                for(type in eatingData){
                    datasets.data.push(eatingData[type]);
                    datasets.backgroundColor.push(getColorForEating(type));
                    datasets.hoverBackgroundColor.push(getColorForEating(type));
                }

                return datasets;
            }

            function getColorForEating(type){
                switch(type){
                    case "завтрак":
                        return "#2e1366";
                    case "второй завтрак":
                        return "#36A2EB";
                    case "обед":
                        return "#FFCE56";
                    case "полдник":
                        return "#4BC0C0";
                    case "ужин":
                        return "#cc65fe";
                    case "второй ужин":
                        return "#fc6514";
                }
            }

            function setValues(){
                if(Object.keys(eatingData).length === 0){
                    return false;
                }

                var sum = 0;
                var data = "";

                for(type in eatingData){
                    sum += eatingData[type];
                    data += '<span style="color: ' + getColorForEating(type) + '">' + eatingData[type] + '</span> + ';
                }

                data = data.slice(0, data.length - 3) + ' = <span style="color: #d40000">' + sum.toFixed(2) + "</span>";

                elemValues.html(data);
            }

            function to_trusted(html_code) {
                return $sce.trustAsHtml(html_code);
            }
        }
    };
}]);



