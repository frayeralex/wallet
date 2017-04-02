'use strict';

jQuery(function ($) {
    //API urls for ajax request
    const removeWalletUrl = '/ajax/wallet/remove';
    const updateWalletUrl = '/ajax/wallet/update';
    const reportUrl = '/ajax/wallet/report';

    const walletItems = $('.wallet-item');
    const editWalletModal = $('#editWallet');
    const updateWalletBtn = $('#updateWallet');
    const removeWalletBtn = $('#removeWallet');


    walletItems.on('click', (event)=>{
        const item = $(event.currentTarget);
        const id = item.attr('data-id');
        const name = item.find('.name').text();
        const value = item.find('.value').text();

        editWalletModal.attr('data-wallet-id', id);
        editWalletModal.find('input.name').val(name);
        editWalletModal.find('input.value').val(parseFloat(value));
        editWalletModal.modal('show');
    });

    updateWalletBtn.on('click', ()=>{
        const id = editWalletModal.attr('data-wallet-id');
        const name = editWalletModal.find('input.name').val();
        const value = editWalletModal.find('input.value').val();
        const data = {
            id,
            name,
            value,
        };

        $.ajax({
            url: updateWalletUrl,
            type: 'POST',
            data,
            success: ()=>{
                const item = $(`[data-id="${data.id}"]`);
                item.find('.name').text(data.title);
                item.find('.value').text(data.value);
                editWalletModal.modal('hide');
            },
            error: ()=>{
                editWalletModal.modal('hide');
            }
        })
    });

    removeWalletBtn.on('click',()=>{
        const id = editWalletModal.attr('data-wallet-id');
        $.ajax({
            url: removeWalletUrl,
            type: 'POST',
            data: {id},
            success: ()=>{
                const item = $(`[data-id="${id}"]`);
                item.remove();
                editWalletModal.modal('hide');
            },
            error: ()=>{
                editWalletModal.modal('hide');
            }
        })
    });

    /*
    * Wallet transaction form
    * */

    const transactionForm = $('#walletTransaction form');
    const transactionModal = $('#walletTransaction');
    const selectFrom = $('#wallet_from');
    const selectTo = $('#wallet_to');
    const valueFrom = $('#value_from');
    const valueRate = $('#value_rate');
    const valueTo = $('#value_to');

    function getSelectValue(select) {
        return select.find(`[value="${select.val()}"]`).attr("data-value")
    }
    function getSelectOption(select) {
        return select.find(`[value="${select.val()}"]`)
    }

    //init values
    const optionValues = $.map($(`${selectTo.selector} option`), item=>item.value);
    let valFrom = +getSelectValue(selectFrom);
    let valTo = +getSelectValue(selectTo);
    let rate = 1;

    valueFrom.val(valFrom);
    valueRate.val(rate);

    function checkWalletConflict() {
        if(selectFrom.val() === selectTo.val()){
            const value = selectTo.val();
            $(`${selectTo.selector} option`).attr('disabled', false);
            getSelectOption(selectTo).attr('disabled', true);
            selectTo.val(optionValues.find(item=>item!==value));
        }
    }

    function updateCalculate() {
        if(isNaN(rate) || isNaN(valFrom)) return;
        valueTo.val(((valFrom * rate) + valTo).toFixed(2));
    }
    checkWalletConflict();
    updateCalculate();

    function changeValue(event) {
        valFrom = event.target.value !== 0 ? parseFloat(event.target.value): 0;
        updateCalculate();
    }

    function changeRate(event) {
        rate = event.target.value !== 0 ? parseFloat(event.target.value) : 0;
        updateCalculate();
    }

    function selectToCb() {
        checkWalletConflict();
        valTo = parseFloat(getSelectValue(selectTo));
        updateCalculate();
    }

    function selectFromCb() {
        checkWalletConflict();
        valFrom = parseFloat(getSelectValue(selectFrom));
        updateCalculate();
        valueFrom.val(valFrom);
    }

    function transactionFormCb(event) {
        event.preventDefault();

        $.ajax({
            url: '/ajax/wallet/transaction',
            type: 'POST',
            data: {
                from: selectFrom.val(),
                to: selectTo.val(),
                valFrom,
                rate
            },
            success: (res)=> {
                transactionModal.modal('hide');
                $(`tr[data="${selectFrom.val()}"] .value`).text(+getSelectValue(selectFrom) - valFrom);
                $(`tr[data="${selectTo.val()}"] .value`).text(valTo);
            },
            error: ()=> {
                transactionModal.modal('hide');
            }
        })
    }

    valueFrom.on('input', changeValue);
    valueRate.on('input', changeRate);
    selectFrom.on('change', selectFromCb);
    selectTo.on('change', selectToCb);
    transactionForm.on('submit', transactionFormCb)

    /*
    * Pdf
    * */

    const pdfModal = $('#pdfModal');
    const pdfForm = $('#pdf_wallet_form');
    const inputFrom = $('#pdf_date_from');
    const inputTo = $('#pdf_date_to');

    //set default date values
    inputFrom.val(moment().subtract(30, 'days').format("YYYY-MM-DD"));
    inputTo.val(moment().format("YYYY-MM-DD"));

    pdfForm.on('submit', event=>{
        event.preventDefault();
        const id = $('#pdf_wallet_id').val();
        let dateFrom = inputFrom.val();
        let dateTo = inputTo.val();
        dateFrom = dateFrom ? moment(dateFrom).format("YYYY-MM-DD") : moment().subtract(7, 'days').format("YYYY-MM-DD");
        dateTo = dateTo ? moment(dateTo).add(1, 'days').format("YYYY-MM-DD") : moment().add(1, 'days').format("YYYY-MM-DD") ;

        $.ajax({
            url: reportUrl,
            method: "GET",
            dataType: 'json',
            data: { id, dateFrom, dateTo },
            success: (result)=>{
                result.wallet.dateFrom = dateFrom;
                result.wallet.dateTo = dateTo;
                generatePdf(result);
                pdfModal.modal('hide');
            },
            error: (err)=>{
                pdfModal.modal('hide');
            }
        })
    });

    function generatePdf(data){
        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'pt',
        });

        let { incomes, outcomes, wallet } = data;
        function filterData(item,index) {
            item.index = index+1;
            item.date = moment(item.createdAt).format('ddd MMM DD YYYY');
            return item;
        }
        incomes = incomes.map(filterData);
        outcomes = outcomes.map(filterData);
        const walletRows = [
            {key: 'Wallet', value: wallet.name},
            {key: 'Value', value: wallet.value},
            {key: 'Currency', value: wallet.currency},
            {key: 'Date range', value: `${wallet.dateFrom} - ${wallet.dateTo}`},
        ];

        const walletCol = [
            {title: "Key", dataKey: "key"},
            {title: "Value", dataKey: "value"},
        ];
        const transactionCol = [
            {title: "#", dataKey: "index"},
            {title: "Title", dataKey: "title"},
            {title: "Sum", dataKey: "value"},
            {title: "Date", dataKey: "date"},
        ];

        pdf.autoTable(walletCol, walletRows, {
            startY: 40,
            showHeader: 'never',
            columnStyles: {
                key: {fontStyle: 'bold', halign: 'right'}
            }
        });

        pdf.autoTable([{title: "Incomes", dataKey: "foo"}], [], {
            startY: pdf.autoTable.previous.finalY + 40,
            theme: 'grid',
            headerStyles: {
                halign: 'center'
            }
        });
        pdf.autoTable(transactionCol, incomes, {
            startY: pdf.autoTable.previous.finalY,
            theme: 'grid'
        });

        pdf.autoTable([{title: "Outcomes", dataKey: "foo"}], [], {
            startY: pdf.autoTable.previous.finalY + 40,
            theme: 'grid',
            headerStyles: {
                halign: 'center'
            }
        });
        pdf.autoTable(transactionCol, outcomes, {
            startY: pdf.autoTable.previous.finalY,
            theme: 'grid'
        });

        pdf.save(`${wallet.name}--${wallet.dateFrom}-${wallet.dateTo}.pdf`);
    }
});