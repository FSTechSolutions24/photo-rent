$(function () {
    // Auto-initialize all .select2 elements
    $('.select2').select2({
        placeholder: 'Select an option',
        allowClear: true
    });

    $('.btn-close, .btn[data-bs-dismiss="modal"]').on('click',()=>{
        $('.modal').modal('hide');
    })

    $('.modal').modal({
        backdrop: 'static',
        keyboard: false
    });
});