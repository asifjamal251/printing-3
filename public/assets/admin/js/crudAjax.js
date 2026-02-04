$("form").on("keydown", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
    }
});


function store(element) {
    var form = element.closest('form'); // Move this before using the form variable

    var preloaderHtml = `<div class="card-preloader show"><div class="card-status"><div class="spinner-grow text-danger"><span class="visually-hidden">Loading...</span></div></div></div>`;
    var preloaderElement = document.createRange().createContextualFragment(preloaderHtml);
    form.prepend(preloaderElement); // Now we can prepend the preloader

    var button = new Button(element);
    button.process();

    clearErrors();

    var formData = new FormData(form);
    var url = form.getAttribute('action');

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: url,
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        success: function(response) {
            if(response.message.length > 0){
                Toastify({
                    text: response.message,
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    className: response.class,
                }).showToast();

                if (!response.error && response.table_refresh === true) {
                    setTimeout(function() {
                        console.log('ok')
                        $('.datatable').DataTable().draw('page');
                        $('.dataTableAjax').DataTable().draw('page');
                    }, 10);
                }

                if (!response.error && response.table_referesh === true) {
                    setTimeout(function() {
                        console.log('ok')
                        $('.datatable').DataTable().draw('page');
                        $('.dataTableAjax').DataTable().draw('page');
                    }, 10);
                }

                if (!response.error && response.model_id && response.model_id.length > 0) {
                    $('#'+response.model_id).on('hidden.bs.modal', function () {
                        $('#addForm').html('')
                        $(this).find('form').trigger('reset');
                    })
                    setTimeout(function() {
                        $('#'+response.model_id).modal('hide');
                    }, 100);
                }

                if (!response.error && response.call_back.length > 0) {
                    setTimeout(function() {
                        window.location.href = response.call_back;
                    }, 500);
                }
                

                setTimeout(function() {
                    var preloader = form.querySelector('.card-preloader');
                    if (preloader) {
                        preloader.remove();
                    }
                    button.normal();

                    if ($(".refereshJSTree").length) {
                        $(".refereshJSTree").each(function () {
                            const treeInstance = $(this).jstree(true);
                            if (treeInstance) {
                                treeInstance.refresh();
                            }
                        });
                    }
                        
                }, 1500);
            }

        },
        error: function(error) {
            handleErrors(error.responseJSON);

            setTimeout(function() {
                var preloader = form.querySelector('.card-preloader');
                if (preloader) {
                    preloader.remove();
                }
                button.normal();
            }, 1000);
        }
    });
}




function downloadExcel(element){
    var form = element.closest('form');
    var button = new Button(element);
    button.process();
    clearErrors();
    var formData = new FormData(form);
    var url = form.getAttribute('action');

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url:url,
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        success:function(response){
           // $('#offcanvasTop').offcanvas('hide')
            Toastify({
                text: response.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                className: response.class,

            }).showToast();
            button.normal();
            window.location.href = response.filename;
        },
        error:function(error){
            Toastify({
                text: error.responseJSON.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                className: "error",

            }).showToast();
            button.normal();
        }
    });
}
