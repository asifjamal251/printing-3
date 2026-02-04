function Button(button) {
    this.buttonOldHtml = button.innerHTML;
    this.process = function() {
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>&nbsp;' + this.buttonOldHtml;
        button.disabled = true;
    }
    this.normal = function() {
        button.innerHTML = this.buttonOldHtml;
        button.disabled = false;
    }
}

clearErrors = function() {
        Array.from(document.getElementsByClassName('validate')).map(function(e, f) {
            e.innerHTML = '';
        });

        Array.from(document.querySelectorAll('.form-group small')).map(function(e, f) {
            e.innerHTML = '';
        });
        Array.from(document.getElementsByClassName('error')).map(function(e, f) {
            e.innerHTML = '';
        });

        Array.from(document.getElementsByClassName('form-group')).map(function(e, f) {
            e.classList.remove("has-error");
        });

        //var remvcl = document.getElementsByClassName('form-group');
        //remvcl.classList.remove("as-error");
}



handleErrors = function(error, options = {}) {
    var options = {
        element: options.element ? options.element : 'small',
        style: options.style,
        class: options.class ? options.class : 'text-danger'
    };

    if (!error) return;

    // Show a toast notification if there's a general error message
    if (error.message) {
        Toastify({
            text: error.message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: true,
            className: "bg-danger",
        }).showToast();
    }

    // Clear previous validation messages
    clearErrors();

    // Check if the error object has errors
    if (error && error.errors) {
        Object.entries(error.errors).forEach(function(a) {
            // Parse the field name, e.g., 'kt_docs_repeater_advanced.0.product'
            let fieldParts = a[0].split('.');
            let fieldKey = fieldParts[fieldParts.length - 1]; // Extract the field name
            let fieldName;

            // Check if the field is part of a repeater or a normal field
            if (fieldParts.length > 2) {
                let fieldIndex = fieldParts[1]; // Extract the index for repeater
                fieldName = 'kt_docs_repeater_advanced[' + fieldIndex + '][' + fieldKey + ']';
            } else {
                fieldName = fieldKey; // For normal fields, just use the field key
            }

            // Find the input field based on the name attribute
            let input = document.querySelector('[name="' + fieldName + '"]');
            if (input) {
                // Find the existing <small> tag for showing errors
                let smallErrorTag = input.closest('.form-group').querySelector('small.text-danger');

                // If the <small> tag exists, set the error message
                if (smallErrorTag) {
                    smallErrorTag.innerHTML = a[1].join(', '); // Join multiple error messages
                    smallErrorTag.style.display = 'block'; // Ensure it is visible
                }

                // Optionally, add an error class to the input's parent
                input.parentNode.classList.add("has-error");
            }
        });
    }
}




function setCookie(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  let expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  let name = cname + "=";
  let ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function delete_cookie(name) {
  document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}






function autoResize($textarea) {
    $textarea.height('auto').height($textarea[0].scrollHeight);
}



function initAddressTextarea() {
    const $textarea = $('#address');
    if (!$textarea.length) return;

    $textarea.on('input', function () {
        autoResize($textarea);
    });

    $textarea.on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const pos = this.selectionStart;
            const value = $textarea.val();
            $textarea.val(value.slice(0, pos) + '\n' + value.slice(pos));
            autoResize($textarea);
            this.setSelectionRange(pos + 1, pos + 1);
        }
    });

    autoResize($textarea);
}




function initMobileInput() {
    const $input = $('#mobile_no');
    if (!$input.length) return;

    const prefix = '+91';

    function setCursor() {
        const input = $input[0];
        input.setSelectionRange(prefix.length, prefix.length);
    }

    $input.on('focus', function () {
        if (!$input.val().startsWith(prefix)) $input.val(prefix);
        setCursor();
    });

    $input.on('blur', function () {
        if ($input.val().trim() === prefix) $input.val('');
    });

    $input.on('input', function () {
        if (!$input.val().startsWith(prefix)) {
            $input.val(prefix);
            setCursor();
        }
    });
}

function initFormElements() {
    initAddressTextarea();
    initMobileInput();
}

$(window).on('load', initFormElements);
window.initFormElements = initFormElements;










$('.select-mediatype').focus(function() {
    $(this).closest('.onFocus').addClass('focused');
});

// Optionally remove the border color when the input loses focus
$('.select-mediatype').blur(function() {
    $(this).closest('.onFocus').removeClass('focused');
});

$('.select2').on('keydown', function(e) {
    if (e.key === 'Tab') {
        var $searchField = $('.select2-container--open .select2-search__field');

        // Focus on the search field if it exists and dropdown is open
        if ($searchField.length && $('.select2-container--open').length) {
            e.preventDefault(); // Prevent default tab behavior
            $searchField.focus();
        }
    }
});
$(document).on("select2:open", () => {
  document.querySelector(".select2-container--open .select2-search__field").focus()
});