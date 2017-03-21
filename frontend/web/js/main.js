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

    const ratesLinearChart = document.querySelector('#ratesLinearChart');

    if(ratesLinearChart){
        $.ajax({
            url: '/ajax/get-rates',
            type: 'POST',
            success: (response)=>{
                const rates = JSON.parse(response)
                    .map(rate=>{
                        rate.value = parseFloat(rate.value);
                        return rate;
                    });
                const dates = rates.reduce((dates,item)=>{
                    const date = item.exchangedate;
                    if(!dates.some(d=>d===date)) dates.push(date);
                    return dates;
                },[])
                .sort();
                const currencies = ['USD', 'RUB', 'EUR'];
                const rows = [['Date', ...currencies]];

                dates.forEach(date=>{
                    const row = [date];
                    rates.forEach(rate=>{
                        if(rate.exchangedate === date){
                            if(rate.cc === currencies[0]) row[1] = rate.value;
                            if(rate.cc === currencies[1]) row[2] = rate.value;
                            if(rate.cc === currencies[2]) row[3] = rate.value;
                        }
                    });
                    rows.push(row);
                });

                const options = {
                    title: 'Company Performance',
                    curveType: 'function',
                    legend: { position: 'bottom' }
                };

                console.log(rows)

                pendingGoogle(()=>drawLinearChart(rows,options,ratesLinearChart))
            }
        })
    }



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
            url: '/ajax/user-incomes',
            type: 'POST',
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
            url: '/ajax/user-outcomes',
            type: 'POST',
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
            url: '/ajax/user-last-transactions',
            type: 'POST',
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

    /**
     * Category page
     */

    const categoryItems = $('.category-item');
    const editCategoryModal = $('#editCategory');
    const updateCategoryBtn = editCategoryModal.find('#updateCategory');
    const removeCategoryBtn = editCategoryModal.find('#removeCategory');

    categoryItems.on('click', (event)=>{
        const categoryItem = $(event.currentTarget);
        const categoryId = categoryItem.attr('data-catagory-id');
        const categoryName = categoryItem.find('.name').text();

        editCategoryModal.find('input.category-name').val(categoryName);
        editCategoryModal.attr('data-category', categoryId);
        editCategoryModal.modal('show');
    });

    updateCategoryBtn.on('click', ()=>{
        const id = editCategoryModal.attr('data-category');
        const name = editCategoryModal.find('input.category-name').val();
        if(!id || !name) return;

        $.ajax({
            url: '/ajax/update-category-name',
            type: 'POST',
            data: {id, name },
            success: ()=>{
                $(`[data-catagory-id="${id}"] .name`).text(name);
                editCategoryModal.modal('hide');
            },
            error: ()=>{
                editCategoryModal.modal('hide');
            }
        })
    });

    removeCategoryBtn.on('click', ()=>{
        const id = editCategoryModal.attr('data-category');
        if(!id) return;

        $.ajax({
            url: '/ajax/remove-category',
            type: 'POST',
            data: {id },
            success: ()=>{
                $(`[data-catagory-id="${id}"]`).remove();
                editCategoryModal.modal('hide');
            },
            error: ()=>{
                editCategoryModal.modal('hide');
            }
        })
    });

    /**
     * Outcome page
     */

    const outcomeItems = $('.outcome-item');
    const editOutcomeModal = $('#editOutcome');
    const updateOutcomeBtn = $('#updateOutcome');
    const removeOutcomeBtn = $('#removeOutcome');

    outcomeItems.on('click', (event)=>{
        const item = $(event.currentTarget);
        const id = item.attr('data-id');
        const title = item.find('.title').text();
        const value = item.find('.value').text();
        const date = item.find('.date').attr('data-date');

        editOutcomeModal.attr('data-outcome-id', id);
        editOutcomeModal.find('input.title').val(title);
        editOutcomeModal.find('input.value').val(parseFloat(value));
        editOutcomeModal.find('input.date').val(date.slice(0,10));
        editOutcomeModal.modal('show');
    });

    updateOutcomeBtn.on('click', ()=>{
        const id = +editOutcomeModal.attr('data-outcome-id');
        const title = editOutcomeModal.find('input.title').val();
        const value = parseFloat(editOutcomeModal.find('input.value').val());
        const date = new Date(editOutcomeModal.find('input.date').val()).toISOString();
        const data = {
            id,
            title,
            value,
            date
        };

        $.ajax({
            url: '/ajax/update-outcome',
            type: 'POST',
            data,
            success: ()=>{
                const item = $(`[data-id="${data.id}"]`);
                item.find('.title').text(data.title);
                item.find('.value').text(data.value);
                item.find('.date').text(moment(data.date).format('MMM D, YYYY'))
                item.find('.date').attr('data-date', data.date);
                editOutcomeModal.modal('hide');
            },
            error: ()=>{
                editOutcomeModal.modal('hide');
            }
        })
    });

    removeOutcomeBtn.on('click',()=>{
        const id = +editOutcomeModal.attr('data-outcome-id');
        $.ajax({
            url: '/ajax/remove-outcome',
            type: 'POST',
            data: {id},
            success: ()=>{
                const item = $(`[data-id="${id}"]`);
                item.remove();

                editOutcomeModal.modal('hide');
            },
            error: ()=>{
                editOutcomeModal.modal('hide');
            }
        })
    });

    /**
     * Income page
     */

    const incomeItems = $('.income-item');
    const editIncomeModal = $('#editIncome');
    const updateIncomeBtn = $('#updateIncome');
    const removeIncomeBtn = $('#removeIncome');

    incomeItems.on('click', (event)=>{
        const item = $(event.currentTarget);
        const id = item.attr('data-id');
        const title = item.find('.title').text();
        const value = item.find('.value').text();
        const date = item.find('.date').attr('data-date');

        editIncomeModal.attr('data-outcome-id', id);
        editIncomeModal.find('input.title').val(title);
        editIncomeModal.find('input.value').val(parseFloat(value));
        editIncomeModal.find('input.date').val(date.slice(0,10));
        editIncomeModal.modal('show');
    });

    updateIncomeBtn.on('click', ()=>{
        const id = +editIncomeModal.attr('data-outcome-id');
        const title = editIncomeModal.find('input.title').val();
        const value = parseFloat(editIncomeModal.find('input.value').val());
        const date = new Date(editIncomeModal.find('input.date').val()).toISOString();
        const data = {
            id,
            title,
            value,
            date
        };

        $.ajax({
            url: '/ajax/update-income',
            type: 'POST',
            data,
            success: ()=>{
                const item = $(`[data-id="${data.id}"]`);
                item.find('.title').text(data.title);
                item.find('.value').text(data.value);
                item.find('.date').text(moment(data.date).format('MMM D, YYYY'))
                item.find('.date').attr('data-date', data.date);
                editIncomeModal.modal('hide');
            },
            error: ()=>{
                editIncomeModal.modal('hide');
            }
        })
    });

    removeIncomeBtn.on('click',()=>{
        const id = +editIncomeModal.attr('data-outcome-id');
        $.ajax({
            url: '/ajax/remove-income',
            type: 'POST',
            data: {id},
            success: ()=>{
                const item = $(`[data-id="${id}"]`);
                item.remove();

                editIncomeModal.modal('hide');
            },
            error: ()=>{
                editIncomeModal.modal('hide');
            }
        })
    });

});
