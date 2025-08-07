<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Plugin\QuickForm;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_quick\Attribute\QuickForm;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_sli\SliAllowedValues;
use Drupal\log\Entity\Log;
use Drupal\log\Entity\LogInterface;

/**
 * SLI Intake quick form.
 */
#[QuickForm(
  id: 'intake',
  label: new TranslatableMarkup('Intake Form'),
  description: new TranslatableMarkup(''),
  helpText: new TranslatableMarkup(''),
)]
class Intake extends QuickFormBase {

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {

    // Allow anonymous access.
    return AccessResult::allowed();
  }

  /**
   * Define the steps in this multistep form.
   */
  protected function steps(): array {
    return [
      'intro' => [
        'label' => $this->t('Introduction'),
        'message' => '',
        'callback' => 'buildIntroForm',
        'progress' => 0,
      ],
      'stakeholder' => [
        'label' => $this->t('Stakeholder information'),
        'message' => $this->t('Step @num of @total', ['@num' => 1, '@total' => 4]),
        'callback' => 'buildStakeholderForm',
        'progress' => 25,
      ],
      'property' => [
        'label' => $this->t('Property description'),
        'message' => $this->t('Step @num of @total', ['@num' => 2, '@total' => 4]),
        'callback' => 'buildPropertyForm',
        'progress' => 50,
      ],
      'goals' => [
        'label' => $this->t('Stakeholder goals'),
        'message' => $this->t('Step @num of @total', ['@num' => 3, '@total' => 4]),
        'callback' => 'buildGoalsForm',
        'progress' => 75,
      ],
      'interests' => [
        'label' => $this->t('Resource interests'),
        'message' => $this->t('Step @num of @total', ['@num' => 4, '@total' => 4]),
        'callback' => 'buildInterestsForm',
        'progress' => 100,
      ],
      'review' => [
        'label' => $this->t('Review'),
        'message' => '',
        'callback' => 'buildReviewForm',
        'progress' => 100,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#tree'] = TRUE;

    // If the form has been submitted, only display a message to the user.
    if ($form_state->has('submitted') && $form_state->get('submitted')) {
      $form['#markup'] = $this->t('Thank you for your interest. A staff member will review your information and follow up with you shortly.');
      $form['actions']['submit']['#access'] = FALSE;
      return $form;
    }

    // This is a multistep form. We track which step we are on via a step
    // property in $form_state. Each step has a corresponding form method that
    // we use to build it
    $step = 'intro';
    if ($form_state->has('step') && array_key_exists($form_state->get('step'), $this->steps())) {
      $step = $form_state->get('step');
    }

    // Show a progress bar if progress is greater than 0.
    if ($this->steps()[$step]['progress'] > 0) {
      $form['progress'] = [
        '#theme' => 'progress_bar',
        '#label' => $this->steps()[$step]['label'],
        '#percent' => $this->steps()[$step]['progress'],
        '#message' => $this->steps()[$step]['message'],
      ];
    }

    // Load saved values for this step.
    // The review step gets all saved values.
    $saved_values = [];
    if ($form_state->has('saved_values')) {
      $saved_values = $form_state->get('saved_values');
      if ($step != 'review' && isset($saved_values[$step])) {
        $saved_values = $saved_values[$step];
      }
    }

    // Load the appropriate form.
    $form[$step] = $this->{$this->steps()[$step]['callback']}($saved_values);

    // Create form actions.
    $form['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];

    // Add "Next" and "Back" buttons depending on the step we're on.
    if ($this->steps()[$step]['progress'] > 0) {
      $form['actions']['back'] = [
        '#type' => 'submit',
        '#value' => $this->t('Back'),
        '#submit' => [[$this, 'submitBack']],
      ];
    }
    if ($step != array_key_last($this->steps())) {
      $form['actions']['next'] = [
        '#type' => 'submit',
        '#value' => $this->t('Next'),
        '#submit' => [[$this, 'submitNext']],
      ];
    }

    // Add the submit button, but only make it accessible on the last step.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#validate' => [[$this, 'validateIntake']],
      '#access' => $step == array_key_last($this->steps()),
    ];

    return $form;
  }

  /**
   * Build the intro page of the intake form.
   *
   * @param array $saved_values
   *   Saved values for this step.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildIntroForm(array $saved_values) {

    // Introductory text.
    $form['text1'] = [
      '#type' => 'item',
      '#markup' => $this->t('Please complete this form to express interest in adopting sustainable practices on your land.'),
    ];
    $form['text2'] = [
      '#type' => 'item',
      '#markup' => $this->t('An RCD staff member will contact you to discuss the practices that best align to your goals for your land. Sustainable practices identified may help with water management / retention, soil quality, erosion reduction, increased profits, and reduced climate impacts.'),
    ];
    $form['text3'] = [
      '#type' => 'item',
      '#markup' => $this->t('If you decide to pursue any of the practices identified, then RCD staff will help to secure funding and provide technical assistance for implementation.'),
    ];

    return $form;
  }

  /**
   * Build the stakeholder page of the intake form.
   *
   * @param array $saved_values
   *   Saved values for this step.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildStakeholderForm(array $saved_values) {

    // Personal information section.
    $form['personal'] = [
      '#type' => 'details',
      '#title' => $this->t('Personal information'),
      '#open' => TRUE,
    ];

    // Stakeholder name.
    $form['personal']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Stakeholder name'),
      '#description' => $this->t('If Stakeholder is a Company, state the name of the Company. If the Stakeholder is the owner of a registered business name, state the business name and the name(s) of the owner(s). If Stakeholder is a person applying in his/her own name, state the name of the Stakeholder.'),
      '#default_value' => $saved_values['personal']['name'] ?? '',
      '#required' => TRUE,
    ];

    // Stakeholder email.
    $form['personal']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => $saved_values['personal']['email'] ?? '',
      '#required' => TRUE,
    ];

    // Stakeholder name.
    $form['personal']['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone'),
      '#default_value' => $saved_values['personal']['phone'] ?? '',
      '#required' => TRUE,
    ];

    // Stakeholder mailing address section.
    $form['address'] = [
      '#type' => 'details',
      '#title' => $this->t('Stakeholder mailing address'),
      '#open' => TRUE,
    ];

    // Stakeholder mailing address: street.
    $form['address']['street'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Street'),
      '#default_value' => $saved_values['address']['street'] ?? '',
      '#required' => TRUE,
    ];

    // Stakeholder mailing address: city.
    $form['address']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $saved_values['address']['city'] ?? '',
      '#required' => TRUE,
    ];

    // Stakeholder mailing address: postal code.
    $form['address']['zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Postal code'),
      '#default_value' => $saved_values['address']['zip'] ?? '',
      '#required' => TRUE,
    ];

    // Stakeholder type.
    $form['address']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Stakeholder type'),
      '#options' => SliAllowedValues::stakeholderTypes(),
      '#default_value' => $saved_values['address']['type'] ?? '',
      '#required' => TRUE,
    ];

    // Stakeholder section.
    $form['stakeholder'] = [
      '#type' => 'details',
      '#title' => $this->t('Stakeholder'),
      '#open' => TRUE,
    ];

    // Own or lease the land?
    $form['stakeholder']['own_or_lease'] = [
      '#type' => 'radios',
      '#title' => $this->t('Do you own the land or lease the land?'),
      '#options' => [
        'own' => $this->t('Own'),
        'lease' => $this->t('Lease'),
      ],
      '#default_value' => $saved_values['stakeholder']['own_or_lease'] ?? '',
      '#required' => TRUE,
    ];

    // Lease expiration.
    $form['stakeholder']['lease_expiration'] = [
      '#type' => 'date',
      '#title' => $this->t('If you lease the land, when does the lease expire?'),
      '#default_value' => $saved_values['stakeholder']['lease_expiration'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="stakeholder[stakeholder][own_or_lease]"]' => ['value' => 'lease'],
        ],
        'visible' => [
          ':input[name="stakeholder[stakeholder][own_or_lease]"]' => ['value' => 'lease'],
        ],
      ],
    ];

    // Property owner.
    $form['stakeholder']['property_owner'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Who is the property owner?'),
      '#default_value' => $saved_values['stakeholder']['property_owner'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="stakeholder[stakeholder][own_or_lease]"]' => ['value' => 'lease'],
        ],
        'visible' => [
          ':input[name="stakeholder[stakeholder][own_or_lease]"]' => ['value' => 'lease'],
        ],
      ],
    ];

    // Stakeholder group.
    $form['stakeholder']['group'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Many grants are prioritized for specific groups of farmers and ranchers. Please let us know if you or a property owner identify as any of the following as it could increase likelihood of funding projects on your land (choose all that apply):'),
      '#description' => $this->t('Read more about the Social disadvantage community. <a href=":url" target="_blank">Click here</a>', [':url' => 'https://www.nrcs.usda.gov/wps/portal/nrcs/detail/national/people/outreach/slbfr/?cid=nrcsdev11_001040']),
      '#options' => SliAllowedValues::stakeholderGroups(),
      '#default_value' => $saved_values['stakeholder']['group'] ?? '',
    ];

    // Share with other RCDs.
    $form['stakeholder']['share_rcds'] = [
      '#type' => 'radios',
      '#title' => $this->t('Would you like to share the application information with other RCDs?'),
      '#description' => $this->t('You have the right to submit the application and not to share the information with other RCDs. However, allowing your application information to be shared will allow the RCDs in the State to follow more transparently the development of your Sustainable land initiatives in order to collaborate and share best practices.'),
      '#options' => [
        'yes' => $this->t('Yes'),
        'no' => $this->t('No'),
      ],
      '#default_value' => $saved_values['stakeholder']['share_rcds'] ?? '',
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * Build the property page of the intake form.
   *
   * @param array $saved_values
   *   Saved values for this step.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildPropertyForm(array $saved_values) {

    // Property information section.
    $form['info'] = [
      '#type' => 'details',
      '#title' => $this->t('Property information'),
      '#open' => TRUE,
    ];

    // Farm or ranch name.
    $form['info']['farm_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Farm or Ranch name'),
      '#default_value' => $saved_values['info']['farm_name'] ?? '',
    ];

    // Approximate total acreage.
    $form['info']['acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Approximate total acreage'),
      '#description' => $this->t('If exact acreage is not known please provide the approximate acreage of the land, so we can get a sense of your project.'),
      '#min' => 0,
      '#step' => 0.1,
      '#default_value' => $saved_values['info']['acreage'] ?? '',
      '#required' => TRUE,
    ];

    // Property has address?
    $form['info']['has_address'] = [
      '#type' => 'radios',
      '#title' => $this->t('Does the land have an address?'),
      '#options' => [
        'yes' => $this->t('Yes'),
        'no' => $this->t('No'),
      ],
      '#default_value' => $saved_values['info']['has_address'] ?? '',
      '#required' => TRUE,
    ];

    // Property address: street.
    $form['info']['street'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Street'),
      '#default_value' => $saved_values['info']['street'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'yes'],
        ],
        'visible' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'yes'],
        ],
      ],
    ];

    // Property address: city.
    $form['info']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $saved_values['info']['city'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'yes'],
        ],
        'visible' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'yes'],
        ],
      ],
    ];

    // Property address: postal code.
    $form['info']['zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Postal code'),
      '#default_value' => $saved_values['info']['zip'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'yes'],
        ],
        'visible' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'yes'],
        ],
      ],
    ];

    // Property address: parcel number or GPS coordinates.
    $form['info']['parcel_gps'] = [
      '#type' => 'textfield',
      '#title' => $this->t('If no address exists, please enter the parcel number or GPS coordinates'),
      '#default_value' => $saved_values['info']['parcel_gps'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'no'],
        ],
        'visible' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'no'],
        ],
      ],
    ];

    // Land use section.
    $form['land_use'] = [
      '#type' => 'details',
      '#title' => $this->t('Current land use and acreage'),
      '#open' => TRUE,
    ];

    // Land use checkboxes.
    $form['land_use']['land_use'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select at least one'),
      '#options' => SliAllowedValues::landUses(),
      '#default_value' => $saved_values['land_use']['land_use'] ?? '',
      '#required' => TRUE,
    ];

    // Grazing acreage.
    $form['land_use']['grazing_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Grazing acreage'),
      '#min' => 0,
      '#step' => 0.1,
      '#default_value' => $saved_values['land_use']['grazing_acreage'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[land_use][land_use][grazing]"]' => ['checked' => TRUE],
        ],
        'visible' => [
          ':input[name="property[land_use][land_use][grazing]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Vineyards acreage.
    $form['land_use']['vineyards_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Vineyards acreage'),
      '#min' => 0,
      '#step' => 0.1,
      '#default_value' => $saved_values['land_use']['vineyards_acreage'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[land_use][land_use][vineyards]"]' => ['checked' => TRUE],
        ],
        'visible' => [
          ':input[name="property[land_use][land_use][vineyards]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Orchards acreage.
    $form['land_use']['orchards_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Orchards acreage'),
      '#min' => 0,
      '#step' => 0.1,
      '#default_value' => $saved_values['land_use']['orchards_acreage'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[land_use][land_use][orchards]"]' => ['checked' => TRUE],
        ],
        'visible' => [
          ':input[name="property[land_use][land_use][orchards]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Row crops acreage.
    $form['land_use']['rowcrops_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Row crops acreage'),
      '#min' => 0,
      '#step' => 0.1,
      '#default_value' => $saved_values['land_use']['rowcrops_acreage'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[land_use][land_use][rowcrops]"]' => ['checked' => TRUE],
        ],
        'visible' => [
          ':input[name="property[land_use][land_use][rowcrops]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Natural lands acreage.
    $form['land_use']['natural_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Natural lands acreage'),
      '#min' => 0,
      '#step' => 0.1,
      '#default_value' => $saved_values['land_use']['natural_acreage'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[land_use][land_use][natural]"]' => ['checked' => TRUE],
        ],
        'visible' => [
          ':input[name="property[land_use][land_use][natural]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Other land use.
    $form['land_use']['other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Specify other land usage'),
      '#default_value' => $saved_values['land_use']['other'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[land_use][land_use][other]"]' => ['checked' => TRUE],
        ],
        'visible' => [
          ':input[name="property[land_use][land_use][other]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Other land use acreage.
    $form['land_use']['other_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Other land use acreage'),
      '#min' => 0,
      '#step' => 0.1,
      '#default_value' => $saved_values['land_use']['other_acreage'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="property[land_use][land_use][other]"]' => ['checked' => TRUE],
        ],
        'visible' => [
          ':input[name="property[land_use][land_use][other]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    return $form;
  }

  /**
   * Build the goals page of the intake form.
   *
   * @param array $saved_values
   *   Saved values for this step.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildGoalsForm(array $saved_values) {

    // Goals wrapper.
    $form['goals'] = [
      '#type' => 'details',
      '#title' => $this->t('What are your goals?'),
      '#open' => TRUE,
    ];

    // Stakeholder goals checklist.
    $form['goals']['goals'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Please select at least one'),
      '#options' => SliAllowedValues::goals(),
      '#default_value' => $saved_values['goals']['goals'] ?? '',
      '#required' => TRUE,
    ];

    // Other land use.
    $form['goals']['other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('If other, please elaborate'),
      '#default_value' => $saved_values['goals']['other'] ?? '',
      '#states' => [
        'required' => [
          ':input[name="goals[goals][goals][other]"]' => ['checked' => TRUE],
        ],
        'visible' => [
          ':input[name="goals[goals][goals][other]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Additional comments.
    $form['goals']['comments'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Additional comments'),
      '#default_value' => $saved_values['goals']['comments'] ?? '',
    ];

    return $form;
  }

  /**
   * Build the interests page of the intake form.
   *
   * @param array $saved_values
   *   Saved values for this step.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildInterestsForm(array $saved_values) {

    // Interests wrapper.
    $form['interests'] = [
      '#type' => 'details',
      '#title' => $this->t('What are your resource interests?'),
      '#open' => TRUE,
    ];

    // Stakeholder goals checklist.
    $form['interests']['resource_interests'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Please select at least one'),
      '#options' => [
        'rangeland_erosion' => $this->t('Manage rangeland to protect soil from erosion and increase production'),
        'cropland_erosion' => $this->t('Manage cropland, pastureland, or forestland to protect soil from erosion and increase production'),
        'roads' => $this->t('Manage ranch roads to reduce movement of sediment into streams and other water bodies'),
        'bank_erosion' => $this->t('Reduce erosion of streambanks and gullies'),
        'cover' => $this->t('Manage to increase tree cover and/or ground cover in riparian areas or along streams'),
        'livestock_concentration' => $this->t('Reduce concentration of livestock in or near streams, wetlands, or other water bodies'),
        'runoff' => $this->t('Manage to reduce entry of sediment, nutrients, and pathogens to streams or wetlands'),
        'wildfire' => $this->t('Reduce wildfire hazard'),
        'plants' => $this->t('Maintain or enhance oak woodland, native grass, or other plant communities'),
        'wildlife' => $this->t('Maintain or enhance wildlife or fisheries habitat or other aquatic resources'),
        'weeds' => $this->t('Reduce/manage invasive weeds'),
        'predators' => $this->t('Reduce/manage predator impacts on the ranching operation'),
        'water_regulations' => $this->t('Meet water quality regulations'),
        'water_capacity' => $this->t('Improve water holding capacity of your soil, increase forage production'),
        'alt_water' => $this->t('Utilize alternative water storage, water conservation strategies'),
        'climate_resilience' => $this->t('Increase farm resilience to drought, flood and other climate impacts'),
        'carbon_farming' => $this->t('Be part of the climate change solution through carbon farming'),
      ],
      '#default_value' => $saved_values['interests']['resource_interests'] ?? '',
      '#required' => TRUE,
    ];

    // Additional comments.
    $form['interests']['comments'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Additional comments'),
      '#default_value' => $saved_values['interests']['comments'] ?? '',
    ];

    return $form;
  }

  /**
   * Build the review page of the intake form.
   *
   * @param array $saved_values
   *   Saved values for this step.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildReviewForm(array $saved_values) {
    $form = [];

    // Generate log entity.
    $log = $this->generateIntakeLog($saved_values);

    // Render the log entity.
    $form['log'] = \Drupal::entityTypeManager()->getViewBuilder('log')->view($log, 'sli_intake_preview');

    return $form;
  }

  /**
   * Submit handler for the "Back" button.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitBack(array &$form, FormStateInterface $form_state) {

    // Save current values to form state.
    $values = $form_state->getValues();
    if ($form_state->has('saved_values')) {
      $values = array_merge($form_state->get('saved_values'), $values);
    }
    $form_state->set('saved_values', $values);

    // Go back one step.
    $step = $form_state->get('step');
    $steps = array_keys($this->steps());
    $position = array_search($step, $steps);
    $previous_step = ($position > 0) ? $steps[$position - 1] : null;
    $form_state->set('step', $previous_step);

    // Rebuild the form.
    $form_state->setRebuild(TRUE);
  }

  /**
   * Submit handler for the "Next" button.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitNext(array &$form, FormStateInterface $form_state) {

    // Save current values to form state.
    $values = $form_state->getValues();
    if ($form_state->has('saved_values')) {
      $values = array_merge($form_state->get('saved_values'), $values);
    }
    $form_state->set('saved_values', $values);

    // Go forward one step.
    $step = $form_state->get('step');
    $steps = array_keys($this->steps());
    $position = array_search($step, $steps);
    $next_step = ($position < count($steps) - 1) ? $steps[$position + 1] : null;
    $form_state->set('step', $next_step);

    // Rebuild the form.
    $form_state->setRebuild(TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function validateIntake(array &$form, FormStateInterface $form_state) {

    // Load and validate saved values.
    $saved_values = $form_state->get('saved_values');
    if (is_null($saved_values)) {
      $form_state->setErrorByName('', $this->t('An error occurred. Please contact the system administrator.'));
      return;
    }

    // Generate and validate sli_intake log.
    $log = $this->generateIntakeLog($saved_values);
    $violations = $log->validate();
    if ($violations->count() > 0) {
      $form_state->setErrorByName('', $this->t('A validation error occurred. Please contact the system administrator.'));
      return;
    }

    // Save the generated log to form state storage.
    $form_state->setStorage(['log' => $log]);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load the log from storage and save it.
    $storage = $form_state->getStorage();
    $storage['log']->save();

    // Remember that the form was submitted, so we can display a message to the user.
    $form_state->set('submitted', TRUE);
    $form_state->setRebuild(TRUE);
  }

  /**
   * Generate a sli_intake log entity from $form_state.
   *
   * @param array $saved_values
   *   Saved values for this step.
   *
   * @return \Drupal\log\Entity\LogInterface|null
   *   Returns an unsaved sli_intake log entity, or null if something goes wrong.
   */
  protected function generateIntakeLog(array $saved_values): ?LogInterface {

    // Convert date to timestamp.
    $intake_stakeholder_lease_exp = $saved_values['stakeholder']['stakeholder']['lease_expiration'] ? strtotime($saved_values['stakeholder']['stakeholder']['lease_expiration']) : NULL;

    // Process checkboxes.
    $intake_stakeholder_group = isset($saved_values['stakeholder']['stakeholder']['group']) ? array_keys(array_filter($saved_values['stakeholder']['stakeholder']['group'])) : NULL;
    $intake_property_use = isset($saved_values['property']['land_use']['land_use']) ? array_keys(array_filter($saved_values['property']['land_use']['land_use'])) : NULL;
    $intake_goals = isset($saved_values['goals']['goals']['goals']) ? array_keys(array_filter($saved_values['goals']['goals']['goals'])) : NULL;
    $intake_interests = isset($saved_values['interests']['interests']['resource_interests']) ? array_keys(array_filter($saved_values['interests']['interests']['resource_interests'])) : NULL;

    // Process booleans.
    $intake_rcd_sharing_allowed = $saved_values['stakeholder']['stakeholder']['share_rcds'] === 'yes';

    // Create and return the log.
    return Log::create([
      'type' => 'sli_intake',
      'intake_stakeholder_name' => $saved_values['stakeholder']['personal']['name'],
      'intake_stakeholder_email' => $saved_values['stakeholder']['personal']['email'],
      'intake_stakeholder_phone' => $saved_values['stakeholder']['personal']['phone'],
      'intake_stakeholder_street' => $saved_values['stakeholder']['address']['street'],
      'intake_stakeholder_city' => $saved_values['stakeholder']['address']['city'],
      'intake_stakeholder_zip' => $saved_values['stakeholder']['address']['zip'],
      'intake_stakeholder_type' => $saved_values['stakeholder']['address']['type'],
      'intake_stakeholder_own_or_lease' => $saved_values['stakeholder']['stakeholder']['own_or_lease'],
      'intake_stakeholder_lease_exp' => $intake_stakeholder_lease_exp,
      'intake_property_owner' => $saved_values['stakeholder']['stakeholder']['property_owner'],
      'intake_stakeholder_group' => $intake_stakeholder_group,
      'intake_farm_name' => $saved_values['property']['info']['farm_name'],
      'intake_property_acreage' => $saved_values['property']['info']['acreage'],
      'intake_property_street' => $saved_values['property']['info']['street'],
      'intake_property_city' => $saved_values['property']['info']['city'],
      'intake_property_zip' => $saved_values['property']['info']['zip'],
      'intake_property_parcel_gps' => $saved_values['property']['info']['parcel_gps'],
      'intake_property_use' => $intake_property_use,
      'intake_property_use_grazing_ac' => $saved_values['property']['land_use']['grazing_acreage'],
      'intake_property_use_vineyard_ac' => $saved_values['property']['land_use']['vineyards_acreage'],
      'intake_property_use_orchard_ac' => $saved_values['property']['land_use']['orchards_acreage'],
      'intake_property_use_rowcrop_ac' => $saved_values['property']['land_use']['rowcrops_acreage'],
      'intake_property_use_natural_ac' => $saved_values['property']['land_use']['natural_acreage'],
      'intake_property_use_other' => $saved_values['property']['land_use']['other'],
      'intake_property_use_other_ac' => $saved_values['property']['land_use']['other_acreage'],
      'intake_goals' => $intake_goals,
      'intake_goals_other' => $saved_values['goals']['goals']['other'],
      'intake_goals_comments' => $saved_values['goals']['goals']['comments'],
      'intake_interests' => $intake_interests,
      'intake_interests_comments' => $saved_values['interests']['interests']['comments'],
      'intake_rcd_sharing_allowed' => $intake_rcd_sharing_allowed,
      'status' => 'pending',
      'flag' => ['review'],
    ]);
  }

}
