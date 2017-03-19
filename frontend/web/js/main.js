'use strict';

jQuery(function ($) {
    const App = function (selector) {
        this.DOM = $(selector);
        const foo = 'foo';

    };
    App.loaderClass = 'activeLoader';

    App.prototype.showLoader = function () {
        this.DOM.addClass(App.loaderClass)
    };
    App.prototype.hideLoader = function () {
        this.DOM.removeClass(App.loaderClass)
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
            success: ()=> {
                $(event.currentTarget).parents('tr').remove();
                app.hideLoader();
            },
            error: ()=> {
                app.hideLoader();
            }
        })
    });

    /**
     * Analytic page
     */

    const pieChart = document.querySelector('#piechart_3d');
    function pendingGoogle(cb) {
        if(
            !google
            || !google.charts
            || !google.visualization
            || !google.visualization.arrayToDataTable
            || !google.visualization.PieChart
        ){
            pendingGoogle.count = pendingGoogle.count ? pendingGoogle.count + 1 : 0;
            if(pendingGoogle.count < 100){
                pendingGoogle.time = setTimeout(()=>{pendingGoogle(cb)}, 20);
            }else{
                clearTimeout(pendingGoogle.time);
            }
        }else{
            return cb()
        }
    }
    function drawPieChart(rows, options, domElement) {
        const data = google.visualization.arrayToDataTable(rows);
        const chart = new google.visualization.PieChart(domElement);
        chart.draw(data, options);
    }

    if(pieChart){
        window.google.charts.load("current", {packages:["corechart"]});

        $.ajax({
            url: '/ajax/user-incomes',
            type: 'POST',
            success: (response)=> {
                const incomes = JSON.parse(response);
                const rows = [['Category', 'Value']];
                const options = {
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
                const categories = incomes
                    .map(income=>income.category)
                    .reduce((prev, current) => {
                        if(!prev.some(item=>item.id === current.id)) prev.push(current);
                        return prev;
                    }, []);

                categories.forEach(category=>{
                    const value = incomes.reduce((value, income)=>{
                        if(income.categoryId === category.id) value += +income.value;
                        return value;
                    },0);
                    rows.push([category.name,value]);
                });
                pendingGoogle(()=>drawPieChart(rows, options, pieChart));
            }
        });
    }


    /**
     * Sidebar actions
     */

    const logout = $('.logout-btn');

    logout.on('click',  logoutAction);

    function logoutAction(){
        $.ajax({
            url: '/authorisation/logout',
            type: 'POST',
            success: ()=>{
                console.log("good")
            }
        })
    }

})
