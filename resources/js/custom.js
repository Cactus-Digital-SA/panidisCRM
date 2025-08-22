$('.select2').select2({
    allowClear: true,
    placeholder: 'Select an option'
});

$('.modalSelect2').each(function () {
    let $this = $(this);
    $this.select2({
        dropdownParent: $this.parent(),
        allowClear: true,
    });
});

$('.edit-icon').on('click', function() {
    $(this).prevAll('input:first').focus();
});

// Fetch all the forms we want to apply custom Bootstrap validation styles to
const bsValidationForms = document.querySelectorAll('.needs-validation');

// Loop over them and prevent submission
Array.prototype.slice.call(bsValidationForms).forEach(function (form) {
    form.addEventListener(
        'submit',
        function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        },
        false
    );
});

$('#search').on("click", function () {
    search();
});

let elementsArray = document.querySelectorAll(".enter_filter");

elementsArray.forEach(function(elem) {
    elem.addEventListener("keypress", function() {
        if (event.key === "Enter") {
            search();
        }
    });
});

const birthday = document.querySelector('#birthday');

if(birthday){
    birthday.flatpickr({
        altFormat: 'Y-m-d',
        dateFormat: 'd-m-Y',
        maxDate: 'today',
        allowInput: true,
        locale: {
            ...flatpickr.l10ns.gr, // Merge Greek locale settings
            firstDayOfWeek: 1, // Set the first day of the week to Monday
        },
    });
}

function getAjaxCall(url) {
    return $.ajax({
        url: url,
    }).fail(function (e) {
        console.log(e);
    });
}

// Generic function to fill a select
function fillSelect(url, selectId, placeholder) {
    if (url) {
        getAjaxCall(url).done(function (data) {
            let options = `<option value=''>${placeholder}</option>`;

            data.forEach(function (value) {
                options += `<option value="${value.id}">${value.name}</option>`;
            });

            $(`#${selectId}`).empty().append(options);
        });
    }
}
window.fillSelect = fillSelect;

