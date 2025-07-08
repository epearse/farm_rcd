<?php

declare(strict_types=1);

namespace Drupal\farm_rcp\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_quick\Attribute\QuickForm;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_rcp\RcpAllowedValues;
use Drupal\organization\Entity\Organization;
use Drupal\organization\Entity\OrganizationInterface;
use Drupal\plan\Entity\Plan;

/**
 * RCP Intake quick form.
 */
#[QuickForm(
  id: 'rcp_intake',
  label: new TranslatableMarkup('Resource Conservation Plan Intake Form'),
  description: new TranslatableMarkup(''),
  helpText: new TranslatableMarkup(''),
  permissions: ['create rcp plan']),
]
class RcpIntake extends QuickFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#tree'] = TRUE;

    // Define the vertical tabs.
    $form['tabs'] = [
      '#type' => 'vertical_tabs',
      '#default_tab' => 'edit-general',
    ];

    // Introduction tab.
    $form['intro'] = [
      '#type' => 'details',
      '#title' => $this->t('Introduction'),
      '#group' => 'tabs',
    ];

    // Introductory text.
    $form['intro']['intro'] = [
      '#type' => 'details',
      '#title' => $this->t('Please note the following:'),
      '#open' => TRUE,
    ];
    $form['intro']['intro']['text1'] = [
      '#type' => 'item',
      '#markup' => $this->t('Please complete this form to express interest in adopting sustainable practices on your land.'),
    ];
    $form['intro']['intro']['text2'] = [
      '#type' => 'item',
      '#markup' => $this->t('An RCD staff member will contact you to discuss the practices that best align to your goals for your land. Sustainable practices identified may help with water management / retention, soil quality, erosion reduction, increased profits, and reduced climate impacts.'),
    ];
    $form['intro']['intro']['text3'] = [
      '#type' => 'item',
      '#markup' => $this->t('If you decide to pursue any of the practices identified, then RCD staff will help to secure funding and provide technical assistance for implementation.'),
    ];

    // Stakeholder tab.
    $form['stakeholder'] = [
      '#type' => 'details',
      '#title' => $this->t('Stakeholder information'),
      '#group' => 'tabs',
    ];

    // Personal information section.
    $form['stakeholder']['personal'] = [
      '#type' => 'details',
      '#title' => $this->t('Personal information'),
      '#open' => TRUE,
    ];

    // Stakeholder name.
    $form['stakeholder']['personal']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Stakeholder name'),
      '#description' => $this->t('If Stakeholder is a Company, state the name of the Company. If the Stakeholder is the owner of a registered business name, state the business name and the name(s) of the owner(s). If Stakeholder is a person applying in his/her own name, state the name of the Stakeholder.'),
      '#required' => TRUE,
    ];

    // Stakeholder email.
    $form['stakeholder']['personal']['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];

    // Stakeholder name.
    $form['stakeholder']['personal']['phone'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone'),
      '#required' => TRUE,
    ];

    // Stakeholder mailing address section.
    $form['stakeholder']['address'] = [
      '#type' => 'details',
      '#title' => $this->t('Stakeholder mailing address'),
      '#open' => TRUE,
    ];

    // Stakeholder mailing address: street.
    $form['stakeholder']['address']['street'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Street'),
      '#required' => TRUE,
    ];

    // Stakeholder mailing address: city.
    $form['stakeholder']['address']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#required' => TRUE,
    ];

    // Stakeholder mailing address: postal code.
    $form['stakeholder']['address']['zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Postal code'),
      '#required' => TRUE,
    ];

    // Stakeholder type.
    $form['stakeholder']['address']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Stakeholder type'),
      '#options' => RcpAllowedValues::stakeholderTypes(),
      '#required' => TRUE,
    ];

    // Stakeholder section.
    $form['stakeholder']['stakeholder'] = [
      '#type' => 'details',
      '#title' => $this->t('Stakeholder'),
      '#open' => TRUE,
    ];

    // Own or lease the land?
    $form['stakeholder']['stakeholder']['own_or_lease'] = [
      '#type' => 'radios',
      '#title' => $this->t('Do you own the land or lease the land?'),
      '#options' => [
        'own' => $this->t('Own'),
        'lease' => $this->t('Lease'),
      ],
      '#required' => TRUE,
    ];

    // Stakeholder group.
    $form['stakeholder']['stakeholder']['group'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Many grants are prioritized for specific groups of farmers and ranchers. Please let us know if you or a property owner identify as any of the following as it could increase likelihood of funding projects on your land (choose all that apply):'),
      '#description' => $this->t('Read more about the Social disadvantage community. <a href=":url" target="_blank">Click here</a>', [':url' => 'https://www.nrcs.usda.gov/wps/portal/nrcs/detail/national/people/outreach/slbfr/?cid=nrcsdev11_001040']),
      '#options' => RcpAllowedValues::stakeholderGroups(),
    ];

    // Share with other RCDs.
    $form['stakeholder']['stakeholder']['share_rcds'] = [
      '#type' => 'radios',
      '#title' => $this->t('Would you like to share the application information with other RCDs?'),
      '#description' => $this->t('You have the right to submit the application and not to share the information with other RCDs. However, allowing your application information to be shared will allow the RCDs in the State to follow more transparently the development of your Sustainable land initiatives in order to collaborate and share best practices.'),
      '#options' => [
        'yes' => $this->t('Yes'),
        'no' => $this->t('No'),
      ],
      '#required' => TRUE,
    ];

    // Property description tab.
    $form['property'] = [
      '#type' => 'details',
      '#title' => $this->t('Property description'),
      '#group' => 'tabs',
    ];

    // Property information section.
    $form['property']['info'] = [
      '#type' => 'details',
      '#title' => $this->t('Property information'),
      '#open' => TRUE,
    ];

    // Farm or ranch name.
    $form['property']['info']['farm_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Farm or Ranch name'),
    ];

    // Approximate total acreage.
    $form['property']['info']['acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Approximate total acreage'),
      '#description' => $this->t('If exact acreage is not known please provide the approximate acreage of the land, so we can get a sense of your project.'),
      '#min' => 0,
      '#step' => 1,
      '#required' => TRUE,
    ];

    // Property has address?
    $form['property']['info']['has_address'] = [
      '#type' => 'radios',
      '#title' => $this->t('Does the land have an address?'),
      '#options' => [
        'yes' => $this->t('Yes'),
        'no' => $this->t('No'),
      ],
      '#required' => TRUE,
    ];

    // Property address: street.
    $form['property']['info']['street'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Street'),
      '#states' => [
        'visible' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'yes'],
        ],
      ],
    ];

    // Property address: city.
    $form['property']['info']['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#states' => [
        'visible' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'yes'],
        ],
      ],
    ];

    // Property address: postal code.
    $form['property']['info']['zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Postal code'),
      '#states' => [
        'visible' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'yes'],
        ],
      ],
    ];

    // Property address: parcel number or GPS coordinates.
    $form['property']['info']['parcel_gps'] = [
      '#type' => 'textfield',
      '#title' => $this->t('If no address exists, please enter the parcel number or GPS coordinates'),
      '#states' => [
        'visible' => [
          ':input[name="property[info][has_address]"]' => ['value' => 'no'],
        ],
      ],
    ];

    // Land use section.
    $form['property']['land_use'] = [
      '#type' => 'details',
      '#title' => $this->t('Current land use and acreage'),
      '#open' => TRUE,
    ];

    // Land use checkboxes.
    $form['property']['land_use']['land_use'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select at least one'),
      '#options' => RcpAllowedValues::landUses(),
      '#required' => TRUE,
    ];

    // Grazing acreage.
    $form['property']['land_use']['grazing_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Grazing acreage'),
      '#min' => 0,
      '#step' => 1,
      '#states' => [
        'visible' => [
          ':input[name="property[land_use][land_use][grazing]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Vineyards acreage.
    $form['property']['land_use']['vineyards_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Vineyards acreage'),
      '#min' => 0,
      '#step' => 1,
      '#states' => [
        'visible' => [
          ':input[name="property[land_use][land_use][vineyards]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Orchards acreage.
    $form['property']['land_use']['orchards_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Orchards acreage'),
      '#min' => 0,
      '#step' => 1,
      '#states' => [
        'visible' => [
          ':input[name="property[land_use][land_use][orchards]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Row crops acreage.
    $form['property']['land_use']['rowcrops_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Row crops acreage'),
      '#min' => 0,
      '#step' => 1,
      '#states' => [
        'visible' => [
          ':input[name="property[land_use][land_use][rowcrops]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Natural lands acreage.
    $form['property']['land_use']['natural_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Natural lands acreage'),
      '#min' => 0,
      '#step' => 1,
      '#states' => [
        'visible' => [
          ':input[name="property[land_use][land_use][natural]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Other land use.
    $form['property']['land_use']['other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Specify other land usage'),
      '#min' => 0,
      '#step' => 1,
      '#states' => [
        'visible' => [
          ':input[name="property[land_use][land_use][other]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Other land use acreage.
    $form['property']['land_use']['other_acreage'] = [
      '#type' => 'number',
      '#title' => $this->t('Other land use acreage'),
      '#min' => 0,
      '#step' => 1,
      '#states' => [
        'visible' => [
          ':input[name="property[land_use][land_use][other]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Goals tab.
    $form['goals'] = [
      '#type' => 'details',
      '#title' => $this->t('Goals'),
      '#group' => 'tabs',
    ];

    // Stakeholder goals section.
    $form['goals']['stakeholder'] = [
      '#type' => 'details',
      '#title' => $this->t('Stakeholder goals'),
      '#open' => TRUE,
    ];

    // Stakeholder goals checklist.
    $form['goals']['stakeholder']['goals'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select at least one'),
      '#options' => RcpAllowedValues::goals(),
      '#required' => TRUE,
    ];

    // Other land use.
    $form['goals']['stakeholder']['other'] = [
      '#type' => 'textfield',
      '#title' => $this->t('If other, please elaborate'),
      '#states' => [
        'visible' => [
          ':input[name="goals[stakeholder][goals][other]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    // Additional comments.
    $form['goals']['stakeholder']['comments'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Additional comments'),
    ];

    // Resource interests tab.
    $form['interests'] = [
      '#type' => 'details',
      '#title' => $this->t('Resource interests'),
      '#group' => 'tabs',
    ];

    // Resource interests.
    $form['interests']['interests'] = [
      '#type' => 'details',
      '#title' => $this->t('Resource interests'),
      '#open' => TRUE,
    ];

    // Stakeholder goals checklist.
    $form['interests']['interests']['resource_interests'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select at least one'),
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
      '#required' => TRUE,
    ];

    // Additional comments.
    $form['interests']['interests']['comments'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Additional comments'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Generate and validate entities.
    $farm = $this->generateFarm($form_state);
    $plan = $this->generatePlan($form_state, $farm);
    $farm_violations = $farm->validate();
    $plan_violations = $plan->validate();
    if ($farm_violations->count() > 0 || $plan_violations->count() > 0) {
      $form_state->setErrorByName('', $this->t('A validation error occurred. Please contact the system administrator.'));
    }

    // Save the generated entities to form state storage.
    $form_state->setStorage([
      'farm' => $farm,
      'plan' => $plan,
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load the form state storage.
    $storage = $form_state->getStorage();

    // Save the farm and plan.
    $storage['farm']->save();
    $storage['plan']->save();
  }

  /**
   * Generate a farm organization entity from $form_state.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return \Drupal\organization\Entity\OrganizationInterface
   *   Returns an unsaved farm organization entity.
   */
  protected function generateFarm(FormStateInterface $form_state) {

    // Establish the name of the Farm Organization. This will be the farm/ranch
    // name, if available. Otherwise, it will be the stakeholder name.
    $stakeholder_name = $form_state->getValue(['stakeholder', 'personal', 'name']);
    $farm_name = $form_state->getValue(['property', 'info', 'farm_name']);
    $name = !empty($farm_name) ? $farm_name : $stakeholder_name;

    // Return a farm organization entity.
    return Organization::create([
      'type' => 'farm',
      'name' => $name,
      'status' => 'active',
    ]);
  }

  /**
   * Generate an RCP plan entity from $form_state.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param \Drupal\organization\Entity\OrganizationInterface $farm
   *   The farm organization entity to associate with the plan.
   *
   * @return \Drupal\plan\Entity\PlanInterface
   *   Returns an unsaved rcp plan entity.
   */
  protected function generatePlan(FormStateInterface $form_state, OrganizationInterface $farm) {

    // Generate a case number for the plan name.
    // Count how many plans have been created this year, add one, pad with
    // zeroes, and concatenate to the current year.
    $plan_number = \Drupal::entityQuery('organization')
      ->condition('type', 'rcp')
      ->condition('created', [strtotime('first day of January this year'), time()], 'BETWEEN')
      ->accessCheck(FALSE)
      ->count()
      ->execute();
    $case_number = 'Case ' . date('Y') . str_pad((string) ($plan_number + 1), 4, '0', STR_PAD_LEFT);

    // Return an RCP plan entity.
    return Plan::create([
      'type' => 'rcp',
      'name' => $case_number,
      'farm' => [$farm],
      'rcp_stakeholder_name' => $form_state->getValue(['stakeholder', 'personal', 'name']),
      'rcp_stakeholder_email' => $form_state->getValue(['stakeholder', 'personal', 'email']),
      'rcp_stakeholder_phone' => $form_state->getValue(['stakeholder', 'personal', 'phone']),
      'rcp_stakeholder_street' => $form_state->getValue(['stakeholder', 'address', 'street']),
      'rcp_stakeholder_city' => $form_state->getValue(['stakeholder', 'address', 'city']),
      'rcp_stakeholder_zip' => $form_state->getValue(['stakeholder', 'address', 'zip']),
      'rcp_stakeholder_type' => $form_state->getValue(['stakeholder', 'address', 'type']),
      'rcp_stakeholder_own_or_lease' => $form_state->getValue(['stakeholder', 'stakeholder', 'own_or_lease']),
      'rcp_stakeholder_group' => array_keys(array_filter($form_state->getValue(['stakeholder', 'stakeholder', 'group']))),
      'rcp_property_acreage' => $form_state->getValue(['property', 'info', 'acreage']),
      'rcp_property_street' => $form_state->getValue(['property', 'info', 'street']),
      'rcp_property_city' => $form_state->getValue(['property', 'info', 'city']),
      'rcp_property_zip' => $form_state->getValue(['property', 'info', 'zip']),
      'rcp_property_parcel_gps' => $form_state->getValue(['property', 'info', 'parcel_gps']),
      'rcp_property_land_use' => array_keys(array_filter($form_state->getValue(['property', 'land_use', 'land_use']))),
      'rcp_property_land_use_grazing_acreage' => $form_state->getValue(['property', 'land_use', 'grazing_acreage']),
      'rcp_property_land_use_vineyards_acreage' => $form_state->getValue(['property', 'land_use', 'vineyards_acreage']),
      'rcp_property_land_use_orchards_acreage' => $form_state->getValue(['property', 'land_use', 'orchards_acreage']),
      'rcp_property_land_use_rowcrops_acreage' => $form_state->getValue(['property', 'land_use', 'rowcrops_acreage']),
      'rcp_property_land_use_natural_acreage' => $form_state->getValue(['property', 'land_use', 'natural_acreage']),
      'rcp_property_land_use_other' => $form_state->getValue(['property', 'land_use', 'other']),
      'rcp_property_land_use_other_acreage' => $form_state->getValue(['property', 'land_use', 'other_acreage']),
      'rcp_goals' => array_keys(array_filter($form_state->getValue(['goals', 'stakeholder', 'goals']))),
      'rcp_goals_other' => $form_state->getValue(['goals', 'stakeholder', 'other']),
      'rcp_goals_comments' => $form_state->getValue(['goals', 'stakeholder', 'comments']),
      'rcp_interests' => array_keys(array_filter($form_state->getValue(['interests', 'interests', 'resource_interests']))),
      'rcp_interests_comments' => $form_state->getValue(['interests', 'interests', 'comments']),
      'rcp_sharing_allowed' => $form_state->getValue(['stakeholder', 'stakeholder', 'share_rcds']) === 'yes',
      'status' => 'intake',
    ]);
  }

}
