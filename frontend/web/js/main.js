'use strict';

jQuery(function ($) {
    //load google chart
    window.google.charts.load("current", {packages:["corechart"]});

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
    App.prototype.renderInfo = function (text) {
        return (
            `
            <div class="alert alert-warning" role="alert">
                <p>${text}</p>
            </div>
            `
        )
    };

    const app = new App('#app');

    /**
     * Analytic page
     */

    const incomePieChart = document.querySelector('#incomePieChart');

    function pendingGoogle(cb) {
        if(
            !google
            || !google.charts
            || !google.visualization
            || !google.visualization.arrayToDataTable
            || !google.visualization.PieChart
            || !google.visualization.ColumnChart
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
    function drawLinearChart(rows, options, domElement) {
        const data = google.visualization.arrayToDataTable(rows);
        const chart = new google.visualization.LineChart(domElement);
        chart.draw(data, options);
    }

    function drawColumnChart(rows, options, domElement, chartObject) {
        const data = google.visualization.arrayToDataTable(rows);
        if(chartObject) {
            chartObject.draw(data, options);
            return chartObject;
        }
        const chart = new google.visualization.ColumnChart(domElement);
        chart.draw(data, options);
        return chart;
    }

    if(incomePieChart){
        $.ajax({
            url: '/ajax/income',
            type: 'GET',
            success: (response)=> {
                const incomes = JSON.parse(response);
                const rows = [['Category', 'Value']];
                const options = {
                    is3D: true,
                    chartArea: {
                        top: 20,
                        left: 20,
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
                pendingGoogle(()=>drawPieChart(rows, options, incomePieChart));
            }
        });
    }

    const outcomePieChart = document.querySelector('#outcomePieChart');
    function formatDate(data) {
        return data ? moment(data).format("YYYY MM DD") : moment().format("YYYY MM DD");
    }
    if(outcomePieChart){
        $.ajax({
            url: '/ajax/outcome',
            type: 'GET',
            success: (response)=> {
                const outcomes = JSON.parse(response);
                const rows = [['Category', 'Value']];
                const options = {
                    is3D: true,
                    chartArea: {
                        top: 20,
                        left: 20,
                        right: 0,
                        bottom: 0,
                        width: '100%',
                        height: '100%'
                    }
                };
                const categories = outcomes
                    .map(outcome=>outcome.category)
                    .reduce((prev, current) => {
                        if(!prev.some(item=>item.id === current.id)) prev.push(current);
                        return prev;
                    }, []);

                categories.forEach(category=>{
                    const value = outcomes.reduce((value, outcome)=>{
                        if(outcome.categoryId === category.id) value += +outcome.value;
                        return value;
                    },0);
                    rows.push([category.name,value]);
                });
                pendingGoogle(()=>drawPieChart(rows, options, outcomePieChart));
            }
        });
    }

    const lastTransactionsColumnChart = document.querySelector('#lastTransactionsColumnChart');
    const $currencySelect = $('#currencySelect');
    let startDate = moment().subtract(7, 'days').format("YYYY-MM-DD");
    let endDate = moment().add(1, 'days').format("YYYY-MM-DD");
    let currencyId = $currencySelect.val();
    let lastTransactionsChart;

    $currencySelect.on('change',(event)=>{
        renderTransactionsChart(startDate,endDate,event.target.value, lastTransactionsChart)
    });

    function renderTransactionsChart(dateStart, dateEnd, currency) {
        $.ajax({
            url: '/ajax/analytic/index',
            type: 'GET',
            data: {
                dateStart,
                dateEnd,
                currency
            },
            success: (response)=> {
                const json = JSON.parse(response);
                const dates = json.incomes
                    .concat(json.outcomes)
                    .reduce((dates, item)=>{
                        if(dates.indexOf(formatDate(item.createdAt)) === -1){
                            dates.push(formatDate(item.createdAt))
                        }
                        return dates;
                    },[])
                    .sort((a,b)=>{
                        if(new Date(a)> new Date(b)) return 1;
                        if(new Date(a)< new Date(b)) return -1;
                        return 0;
                    });
                const incomes = json.incomes || [];
                const outcomes = json.outcomes || [];
                if(!incomes.length && !outcomes.length) {
                    lastTransactionsColumnChart.innerHTML = app.renderInfo("No data");
                    lastTransactionsChart = null;
                    return;
                }
                const rows = [['Date', 'Incomes', 'Outcomes']];

                dates.forEach(date=>{
                    const outcomeValue = outcomes.reduce((value, item)=>{
                        return formatDate(item.createdAt)===date ? value + parseFloat(item.value) : value
                    },0);
                    const incomeValue = incomes.reduce((value, item)=>{
                        return formatDate(item.createdAt)===date ? value + parseFloat(item.value) : value
                    },0);
                    rows.push([date, incomeValue, outcomeValue])
                });

                const options = {
                    width: '100%',
                    height: '100%',
                    legend: {position: 'top'},
                    animation:{
                        duration: 1000,
                        easing: 'out',
                    },
                };
                if(lastTransactionsChart){
                    drawColumnChart(rows, options, lastTransactionsColumnChart, lastTransactionsChart)
                }else{
                    pendingGoogle(()=>{
                        lastTransactionsChart = drawColumnChart(rows, options, lastTransactionsColumnChart)
                    });
                }
            }
        });
    }

    if(lastTransactionsColumnChart){
        renderTransactionsChart(startDate,endDate,currencyId);
    }

    const ratesLinearChart = document.querySelector('#ratesLinearChart');

    if(ratesLinearChart){
        $.ajax({
            url: '/ajax/rate/index',
            type: 'GET',
            success: (response)=>{
                const rates = JSON.parse(response)
                    .map(rate=>{
                        rate.value = parseFloat(rate.value);
                        return rate;
                    });
                if(!rates.length) return ratesLinearChart.innerHTML = app.renderInfo('No data');

                const dates = rates.reduce((dates,item)=>{
                    const date = item.exchangedate;
                    if(!dates.some(d=>d===date)) dates.push(date);
                    return dates;
                },[])
                    .sort();
                const currencies = ['USD', 'EUR'];
                const rows = [['Date', ...currencies]];

                dates.forEach(date=>{
                    const row = [date];
                    rates.forEach(rate=>{
                        if(rate.exchangedate === date){
                            if(rate.cc === currencies[0]) row[1] = rate.value;
                            if(rate.cc === currencies[1]) row[2] = rate.value;
                        }
                    });
                    rows.push(row);
                });

                const options = {
                    legend: { position: 'top' }
                };

                pendingGoogle(()=>drawLinearChart(rows,options,ratesLinearChart))
            },
            error: (err)=> {
                return ratesLinearChart.innerHTML = app.renderInfo('Somsing wrong with server connection');

            }
        })
    }


    /*
     * Sidebar actions
     */

    const logout = $('.logout-btn');

    logout.on('click',  logoutAction);

    function logoutAction(){
        $.ajax({
            url: '/auth/logout',
            type: 'POST'
        })
    }
});
