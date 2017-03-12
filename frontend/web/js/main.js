jQuery(function ($) {
    const App = function (selector) {
        this.DOM = $(selector);
        const self = this;

        this.showLoader = function () {
            self.DOM.addClass('activeLoader')
        };

        this.hideLoader = function () {
            self.DOM.removeClass('activeLoader')
        };
    };

    const app = new App('#app');

    /**
     * site-wallet page
     */
    const walletPage = $('.site-wallet');

    walletPage.on('click', '[data-wallet-id]', function (event) {
        app.showLoader();
        const id = event.currentTarget.getAttribute('data-wallet-id');
        $.ajax({
            url: '/site/delete-wallet',
            type: 'POST',
            data: {id: id },
            success: function () {
                $(event.currentTarget).parents('tr').remove();
                console.log($(event.currentTarget))
                app.hideLoader();
            },
            error: function (err) {
                app.hideLoader();
                console.log("err",err)
            }
        })
    });

    /**
     * Home page analytic
     */

    const pieChart = document.querySelector('#piechart_3d');
    if(pieChart && window.google){
        window.google.charts.load("current", {packages:["corechart"]});


        $.ajax({
            url: '/ajax/index',
            type: 'POST',
            success: function (d) {
                window.google.charts.setOnLoadCallback(drawChart);
                function drawChart() {
                    var rows = [['Category', 'Value']];
                    JSON.parse(d).category
                        .forEach(function(item){
                            var value = 0;
                            JSON.parse(d).incomes.forEach(function (income) {
                                if(income.categoryId == item.id){
                                    value += parseFloat(income.value);
                                }
                            });
                            rows.push([item.name, value])
                        });

                    var data = google.visualization.arrayToDataTable(rows);

                    var options = {
                        title: 'Incomes for categories',
                        is3D: true,
                        chartArea: {
                            top: 20,
                            left: 0,
                            right: 0,
                            bottom: 0,
                            width: '100%',
                            height: '100%'
                        }
                    };

                    var chart = new google.visualization.PieChart(pieChart);
                    chart.draw(data, options);
                }
            }
        });



    }

})
