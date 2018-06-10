<?php
include '../../../i18n_setup.php';
 ?>
  'use strict';
  $(document).ready(function() {

    validate.extend(validate.validators.datetime, {

      parse: function(value, options) {

        return +moment.utc(value);
      },
      // Input is a unix timestamp
      format: function(value, options) {

        var format = options.dateOnly ? "DD/MM/YYYY" : "DD/MM/YYYY";
        return moment.utc(value).format(format);
      }
    });

    // These are the constraints used to validate the form
    var constraints = {

      namn: {
        // You need to pick a username too
        presence: true,
        // And it must be between 3 and 20 characters long
        length: {
          minimum: 4,
          maximum: 30,
          message: "<?=gettext('minst 4 tecken och max 30 tecken')?>"
        },

      },
    };

    // Hook up the form so we can prevent it from being posted
    var form = document.querySelector("form#main");
    form.addEventListener("submit", function(ev) {

      ev.preventDefault();
      handleFormSubmit(form);
    });

    // Hook up the inputs to validate on the fly
    var inputs = document.querySelectorAll("input, textarea, select")
    for (var i = 0; i < inputs.length; ++i) {

      inputs.item(i).addEventListener("change", function(ev) {

        var errors = validate(form, constraints) || {};
        showErrorsForInput(this, errors[this.name]);

      });
    }

    function handleFormSubmit(form, input) {


      // validate the form aainst the constraints
      var errors = validate(form, constraints);
      // then we update the form to reflect the results
      showErrors(form, errors || {});
      if (!errors) {

        jQuery.ajax({
          type: "POST",
          url: form.action,
          data: $(form).serialize(),
          datatype: 'html',
          success: function(data) {
            var mydata = $.parseJSON(data);
            var fel = mydata.error;
            var kod = mydata.errorcode;
            if (fel == "true") {
              if (kod == "0") {
                alert('<?=gettext('Den angivna zonen finns redan med i systemet.')?>');
              } else if (kod == "1") {
                alert('<?=gettext('Databas fel')?>');
              } else if (kod == "2") {
                alert('<?=gettext('Nått är fel i formuläret')?>');
              }
            } else if (fel == "false") {
              alert('<?=gettext('Zonen har blivit tillagd och du kan börja använda den.')?>');
              $("#namn").val("");
              $("#besk").val("");
            }
          }
        });
      }
    }

    // Updates the inputs with the validation errors
    function showErrors(form, errors) {

      // We loop through all the inputs and show the errors for that input
      _.each(form.querySelectorAll("input[name], select[name]"), function(input) {
        // Since the errors can be null if no errors were found we need to handle
        // that
        showErrorsForInput(input, errors && errors[input.name]);
      });
    }

    // Shows the errors for a specific input
    function showErrorsForInput(input, errors) {
      // This is the root of the input

      var formGroup = closestParent(input.parentNode, "form-group")
        // Find where the error messages will be insert into
        ,
        messages = formGroup.querySelector(".messages");
      // First we remove any old messages and resets the classes
      resetFormGroup(formGroup);
      // If we have errors
      if (errors) {
        // we first mark the group has having errors
        formGroup.classList.add("has-error");
        // then we append all the errors
        _.each(errors, function(error) {

          addError(messages, error, input);
        });
      } else {
        // otherwise we simply mark it as success
        formGroup.classList.add("has-success");
      }
    }

    // Recusively finds the closest parent that has the specified class
    function closestParent(child, className) {
      if (!child || child == document) {
        return null;
      }
      if (child.classList.contains(className)) {
        return child;
      } else {
        return closestParent(child.parentNode, className);
      }
    }

    function resetFormGroup(formGroup) {

      // Remove the success and error classes
      formGroup.classList.remove("has-error");
      formGroup.classList.remove("has-success");
      // and remove any old messages
      _.each(formGroup.querySelectorAll(".text-danger"), function(el) {
        el.parentNode.removeChild(el);
      });
    }

    // Adds the specified error with the following markup
    // <p class="help-block error">[message]</p>
    function addError(messages, error, input) {

      var block = document.createElement("p");
      block.classList.add("text-danger");
      block.classList.add("error");
      block.innerText = error;
      messages.appendChild(block);
      $(input).addClass("input-danger");
    }

    function showSuccess() {

      // We made it \:D/
      alert("Success!");
    }


  });
