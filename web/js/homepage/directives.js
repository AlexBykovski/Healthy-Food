app.directive("proteinsChart",[ function(){
    return{
        restrict: 'A',
        link: function(scope, element, attrs, ngModel)
        {
            console.log(angular.fromJson(attrs.eatingData));
            var myChart = new Chart(element, {
                type: 'doughnut',
                data: {
                    labels: [
                        "Red",
                        "Blue",
                        "Yellow"
                    ],
                    datasets: [
                        {
                            data: [300, 50, 100],
                            /*backgroundColor: [
                                "#FF6384",
                                "#36A2EB",
                                "#FFCE56"
                            ],*/
                            /*hoverBackgroundColor: [
                                "#FF6384",
                                "#36A2EB",
                                "#FFCE56"
                            ]*/
                        }]
                },
                options: {
                    animation:{
                        animateScale:true
                    }
                }
            });
        }
    };
}]);



