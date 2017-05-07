//https://developers.google.com/chart/interactive/docs/gallery/piechart#making-a-donut-chart
app.directive("proteinsChart",[ function(){
    return{
        restrict: 'A',
        link: function(scope, element, attrs, ngModel)
        {
            var eatingData = angular.fromJson(attrs.eatingData);

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var dataset = getDataSet();
                var data = google.visualization.arrayToDataTable(dataset);

                var options = {
                    title: 'Общее количество в день: ' + getFullMeasure().toFixed(2),
                    pieHole: 0.3,
                    width:800,
                    height:400
                };

                if(dataset.length === 2 && dataset[1].indexOf("Пусто") > -1){
                    options["colors"] = ["lightgrey"];
                    options["title"] = "Вы ещё не выбрали ни одного блюда ни для одного приёма пищи"
                }

                var chart = new google.visualization.PieChart(document.getElementById(attrs.id));
                chart.draw(data, options);
            }

            function getFullMeasure(){
                if(Object.keys(eatingData).length === 0){
                    return 0;
                }

                var sum = 0;

                for(type in eatingData){
                    sum += eatingData[type];
                }

                return sum;
            }

            function getDataSet(){
                var dataset = [['Type of eating', "measure"]];

                if(Object.keys(eatingData).length > 0) {
                    for (type in eatingData) {
                        dataset.push([type, eatingData[type]])
                    }
                }
                else{
                    dataset.push(["Пусто", 1])
                }

                return dataset;
            }
        }
    };
}]);



