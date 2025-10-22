/**
 * @file
 * Behavior for disabling forms when one is updated.
 */
(function ($, Drupal, settings) {

  "use strict"

  Drupal.behaviors.disable_forms = {

    // Form IDs that will be monitored.
    formIds: [],

    // The original form state when the page is loaded.
    originalFormStates: {},

    // Serialize each form and add a listener for updates.
    attach: function (context, settings) {
      let behavior = Drupal.behaviors.disable_forms;
      behavior.formIds = settings.disable_form_ids;
      behavior.formIds.forEach((form_id) => {
        const form = $('#' + form_id, context);
        behavior.originalFormStates[form_id] = form.serialize();
        form.on('change input', () => {behavior.updateForm(form_id)});
      });
    },

    // When a form is updated, check to see if its state has changed.
    // If it has, disable all other forms.
    updateForm: function (updated_form_id) {
      let behavior = Drupal.behaviors.disable_forms;
      if ($('#' + updated_form_id).serialize() !== behavior.originalFormStates[updated_form_id]) {
        behavior.formIds.forEach((form_id) => {
          if (form_id !== updated_form_id) {
            behavior.disableForm(form_id);
          }
        });
      }
    },

    // Disable all elements in a form.
    // Add a message to help the user understand.
    disableForm: function (form_id) {
      let elements = document.getElementById(form_id).elements;
      for (let i = 0, len = elements.length; i < len; ++i) {
        elements[i].disabled = true;
      }
    }
  }

})(jQuery, Drupal, drupalSettings)
