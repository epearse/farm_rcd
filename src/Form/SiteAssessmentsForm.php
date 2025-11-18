<?php

declare(strict_types=1);

namespace Drupal\farm_rcd\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\log\Entity\LogInterface;
use Drupal\plan\Entity\PlanInterface;

/**
 * Site assessments form.
 */
class SiteAssessmentsForm extends PlanningWorkflowFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'farm_rcd_site_assessment_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?PlanInterface $plan = NULL) {
    $form = parent::buildForm($form, $form_state, $plan);

    // Form details.
    $form['assessments'] = [
      '#type' => 'details',
      '#title' => $this->t('Site Assessments'),
      '#description' => $this->t('Site assessments are used to collect more information about ecosites during a site visit.'),
    ];

    // Require that a property with land asset children is associated with the
    // plan first.
    if (empty($this->landAssets)) {
      $form['assessments']['#markup'] = $this->t('Ecological site descriptions must be created before site assessments can be made.');
      return $form;
    }

    // Open if the status is "planning" and there are no site assessment logs.
    else {
      $form['assessments']['#open'] = $this->plan->get('status')->value == 'planning' && empty($this->siteAssessmentLogs);
    }

    // Build vertical tabs for each site assessment form.
    $form['assessments']['tabs'] = [
      '#type' => 'vertical_tabs',
    ];

    // Provide simplified edit forms for site assessment logs.
    foreach ($this->siteAssessmentLogs as $id => $log) {

      // Details wrapper.
      $form['assessments'][$id] = $this->buildSiteAssessmentForm($log);
      $form['assessments'][$id]['#type'] = 'details';
      $form['assessments'][$id]['#title'] = $log->label();
      $form['assessments'][$id]['#description'] = $this->t('Site assessment log: <a href=":uri">%label</a>', [':uri' => $log->toUrl()->toString(), '%label' => $log->label()]);
      $form['assessments'][$id]['#group'] = 'assessments][tabs';
    }

    // Add a new site assessment.
    $form['assessments']['add'] = $this->buildSiteAssessmentForm();
    $form['assessments']['add']['#type'] = 'details';
    $form['assessments']['add']['#title'] = $this->t('+ Add site assessment');
    $form['assessments']['add']['#description'] = $this->t('Create a new site assessment log to record information about an ecosite.');

    // If there are site assessment logs, show the add form in vertical tabs.
    // Otherwise, leave it ungrouped and open it by default.
    if (!empty($this->siteAssessmentLogs)) {
      $form['assessments']['add']['#group'] = 'assessments][tabs';
    }
    else {
      $form['assessments']['add']['#open'] = TRUE;
    }

    // Submit button.
    $form['assessments']['actions'] = [
      '#type' => 'actions',
      '#weight' => 1000,
    ];
    $form['assessments']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save site assessments'),
    ];

    return $form;
  }

  /**
   * Site assessment log subform.
   *
   * @param \Drupal\log\Entity\LogInterface|null $log
   *   The log entity or NULL.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  protected function buildSiteAssessmentForm(?LogInterface $log = NULL) {

    // Log ID (if available).
    $form['log_id'] = [
      '#type' => 'value',
      '#value' => !is_null($log) ? $log->id() : NULL,
    ];

    // Ecosite reference.
    // If this is an existing log, show links to land assets, but do not allow
    // editing.
    if (!is_null($log)) {
      /** @var \Drupal\asset\Entity\AssetInterface[] $locations */
      $locations = $log->get('location')->referencedEntities();
      $form['ecosite'] = [
        '#type' => 'checkboxes',
        '#title' => $this->t('Ecosite'),
        '#options' => array_combine(
          array_map(function ($asset) {
            return $asset->id();
          }, $locations),
          array_map(function ($asset) {
            return $asset->toLink()->toString();
          }, $locations),
        ),
        '#default_value' => array_map(function ($asset) {
          return $asset->id();
        }, $locations),
        '#required' => TRUE,
        '#disabled' => TRUE,
      ];
    }
    else {
      $form['ecosite'] = [
        '#type' => 'select',
        '#title' => $this->t('Ecosite'),
        '#options' => array_combine(
          array_keys($this->landAssets),
          array_map(function ($asset) {
            return $asset->toLink()->toString();
          }, $this->landAssets),
        ),
      ];

      // Add a null option to the beginning and default to that.
      // Drupal core only adds this if the field is required and doesn't have a
      // null default value. We do this to ensure that the form can be submitted
      // without requiring the new ecosite details. See #states below.
      $form['ecosite']['#options'] = [NULL => '- Select -'] + $form['ecosite']['#options'];
      $form['ecosite']['#default_value'] = NULL;
    }

    // Build the name of the ecosite field for #states below.
    $ecosite_name = !is_null($log) ? 'assessments[' . $log->id() . '][ecosite]' : 'assessments[add][ecosite]';

    // Date of site visit.
    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date of site visit'),
      '#date_date_element' => 'date',
      '#date_time_element' => 'none',
      '#default_value' => date('Y-m-d', $log ? (int) $log->get('timestamp')->value : NULL),
      '#states' => [
        'required' => [
          ':input[name="' . $ecosite_name . '"]' => ['filled' => TRUE],
        ],
      ],
    ];

    // Land use history.
    $form['land_use_history'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Land use history'),
      '#default_value' => $log ? $log->get('rcd_land_use_history')->value : '',
    ];

    // Existing infrastructure.
    $form['infrastructure'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Existing infrastructure'),
      '#default_value' => $log ? $log->get('rcd_infrastructure')->value : '',
    ];

    // Priority environmental concerns.
    $form['priority_concerns'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Priority environmental concerns'),
      '#default_value' => $log ? $log->get('rcd_priority_concerns')->value : '',
    ];

    // Watersheds.
    $form['watershed'] = [
      '#type' => 'entity_autocomplete_tagify',
      '#title' => $this->t('Watersheds'),
      '#target_type' => 'taxonomy_term',
      '#selection_handler' => 'default:taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => ['rcd_watershed'],
        'sort' => [
          'field' => 'name',
          'direction' => 'asc',
        ],
      ],
      '#autocreate' => TRUE,
      // @see https://www.drupal.org/project/tagify/issues/3551805
      '#attributes' => [
        'class' => ['tagify--autocreate'],
      ],
      '#default_value' => $log ? $log->get('rcd_watershed')->referencedEntities() : NULL,
    ];

    // Groundwater basins.
    $form['groundwater_basin'] = [
      '#type' => 'entity_autocomplete_tagify',
      '#title' => $this->t('Groundwater basins'),
      '#target_type' => 'taxonomy_term',
      '#selection_handler' => 'default:taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => ['rcd_groundwater_basin'],
        'sort' => [
          'field' => 'name',
          'direction' => 'asc',
        ],
      ],
      '#autocreate' => TRUE,
      // @see https://www.drupal.org/project/tagify/issues/3551805
      '#attributes' => [
        'class' => ['tagify--autocreate'],
      ],
      '#default_value' => $log ? $log->get('rcd_groundwater_basin')->referencedEntities() : NULL,
    ];

    // Wildlife species.
    $form['wildlife_species'] = [
      '#type' => 'entity_autocomplete_tagify',
      '#title' => $this->t('Wildlife species'),
      '#target_type' => 'taxonomy_term',
      '#selection_handler' => 'default:taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => ['rcd_wildlife_species'],
        'sort' => [
          'field' => 'name',
          'direction' => 'asc',
        ],
      ],
      '#autocreate' => TRUE,
      // @see https://www.drupal.org/project/tagify/issues/3551805
      '#attributes' => [
        'class' => ['tagify--autocreate'],
      ],
      '#default_value' => $log ? $log->get('rcd_wildlife_species')->referencedEntities() : NULL,
    ];

    // Plant species.
    $form['plant_species'] = [
      '#type' => 'entity_autocomplete_tagify',
      '#title' => $this->t('Plant species'),
      '#target_type' => 'taxonomy_term',
      '#selection_handler' => 'default:taxonomy_term',
      '#selection_settings' => [
        'target_bundles' => ['rcd_plant_species'],
        'sort' => [
          'field' => 'name',
          'direction' => 'asc',
        ],
      ],
      '#autocreate' => TRUE,
      // @see https://www.drupal.org/project/tagify/issues/3551805
      '#attributes' => [
        'class' => ['tagify--autocreate'],
      ],
      '#default_value' => $log ? $log->get('rcd_plant_species')->referencedEntities() : NULL,
    ];

    // Landowner objectives.
    $form['landowner_objectives'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Landowner objectives'),
      '#default_value' => $log ? $log->get('rcd_landowner_objectives')->value : '',
    ];

    // Notes.
    $form['notes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Notes'),
      '#default_value' => $log ? $log->get('notes')->value : '',
    ];

    // Build vertical tabs for each resource assessment.
    $form['resources'] = [
      '#type' => 'vertical_tabs',
    ];

    // Build fields for each resource assessment.
    $resources = [
      'soil' => [
        'title' => $this->t('Soil'),
        'description' => $this->t('Describe soil health, compaction, drought resilience, structure, erosion, nutrient management, etc.'),
      ],
      'water' => [
        'title' => $this->t('Water'),
        'description' => $this->t('Describe water quality issues, supply, flooding, drainage concerns, etc.'),
      ],
      'plant' => [
        'title' => $this->t('Plant/Vegetation'),
        'description' => $this->t('Describe plant/vegetation yield, quality, vigor, invasives, new crop introduction, nutrient management, etc.'),
      ],
      'aquatic' => [
        'title' => $this->t('Aquatic Habitat'),
        'description' => $this->t('Describe aquatic habitat drainage, riparian vegetation, erosion, sedimentation, etc.'),
      ],
      'livestock' => [
        'title' => $this->t('Livestock'),
        'description' => $this->t('Describe livestock health, protection, forage, etc.'),
      ],
      'wildlife' => [
        'title' => $this->t('Wildlife'),
        'description' => $this->t('Describe wildlife pollinator habitat, IPM, crop protection, predation issues, etc.'),
      ],
      'infrastructure' => [
        'title' => $this->t('Infrastructure'),
        'description' => $this->t('Describe fencing, water supply and distribution, roads, drainage, etc.'),
      ],
    ];
    foreach ($resources as $key => $resource) {

      // Details wrapper.
      $log_id = !is_null($log) ? $log->id() : 'add';
      $form['resources'][$key] = [
        '#type' => 'details',
        '#title' => $resource['title'],
        '#description' => $resource['description'],
        '#group' => 'assessments][' . $log_id . '][resources',
      ];

      // Rating.
      $form['resources'][$key]['rating'] = [
        '#type' => 'select',
        '#title' => $this->t('Rating'),
        '#description' => $this->t('Please rate the condition of this resource between 1-5, where 1 is terrible and 5 is excellent.'),
        '#options' => [
          NULL => '',
          1 => 1,
          2 => 2,
          3 => 3,
          4 => 4,
          5 => 5,
        ],
        '#default_value' => $log ? $log->get('rcd_' . $key . '_rating')->value : NULL,
      ];

      // Baseline conditions.
      $form['resources'][$key]['baseline'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Baseline conditions'),
        '#default_value' => $log ? $log->get('rcd_' . $key . '_baseline')->value : '',
      ];

      // Goals.
      $form['resources'][$key]['goals'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Goals'),
        '#default_value' => $log ? $log->get('rcd_' . $key . '_goals')->value : '',
      ];

      // How to achieve resource goals.
      $form['resources'][$key]['strategy'] = [
        '#type' => 'textarea',
        '#title' => $this->t('How to achieve resource goals'),
        '#default_value' => $log ? $log->get('rcd_' . $key . '_strategy')->value : '',
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Filter submitted values to those with a numeric key (representing the
    // site assessment log ID), or "add" (for adding a new practice plan).
    $assessment_values = array_filter($form_state->getValue('assessments'), function ($key) {
      return is_numeric($key) || $key === 'add';
    }, ARRAY_FILTER_USE_KEY);

    // If the "add" ecosite field is empty, a new site assessment log will not
    // be created.
    if (empty($assessment_values['add']['ecosite'])) {
      unset($assessment_values['add']);
    }

    // For each set of values, generate/update and validate the site assessment
    // log.
    $logs = [];
    foreach ($assessment_values as $values) {
      $log = $this->generateSiteAssessmentLog($values);
      $violations = $log->validate();
      if ($violations->count() > 0) {
        $form_state->setErrorByName('', $this->t('The site assessment log did not pass validation.'));
        foreach ($violations as $violation) {
          $this->messenger()->addWarning($violation->getMessage());
        }
        return;
      }
      $logs[] = $log;
    }

    // Save the logs to form state storage.
    $form_state->setStorage(['logs' => $logs]);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Load the site assessment logs from storage.
    $storage = $form_state->getStorage();
    $logs = $storage['logs'] ?? [];

    // If there are no logs, bail.
    if (empty($logs)) {
      $this->messenger()->addWarning($this->t('No site assessment logs saved.'));
      return;
    }

    // Save the site assessment logs.
    foreach ($logs as $log) {
      $log->save();
    }

    // Show a message.
    $this->messenger()->addMessage($this->t('Site assessment logs saved.'));
  }

  /**
   * Generate/update a site assessment log entity from submitted values.
   *
   * @param array $values
   *   Submitted values from $form_state->getValue().
   *
   * @return \Drupal\log\Entity\LogInterface|null
   *   Returns an unsaved site assessment log entity, or null if something goes
   *   wrong.
   */
  protected function generateSiteAssessmentLog(array $values): ?LogInterface {

    // If a log ID is included, load it.
    // Otherwise, start a new one.
    $log_storage = $this->entityTypeManager->getStorage('log');
    if (!empty($values['log_id'])) {
      $log = $log_storage->load($values['log_id']);
    }
    else {
      $asset_storage = $this->entityTypeManager->getStorage('asset');
      $land = $asset_storage->load($values['ecosite']);
      $log = $log_storage->create([
        'type' => 'rcd_site_assessment',
        'location' => [$land],
        'status' => 'done',
      ]);
    }

    // Fill in the log details from form values.
    $log->set('timestamp', strtotime($values['date']));
    $log->set('rcd_land_use_history', $values['land_use_history']);
    $log->set('rcd_infrastructure', $values['infrastructure']);
    $log->set('rcd_priority_concerns', $values['priority_concerns']);
    $log->set('rcd_landowner_objectives', $values['landowner_objectives']);
    $log->set('notes', $values['notes']);
    foreach ([
      'soil',
      'water',
      'plant',
      'aquatic',
      'livestock',
      'wildlife',
      'infrastructure',
    ] as $resource) {
      $log->set('rcd_' . $resource . '_rating', $values['resources'][$resource]['rating']);
      $log->set('rcd_' . $resource . '_baseline', $values['resources'][$resource]['baseline']);
      $log->set('rcd_' . $resource . '_goals', $values['resources'][$resource]['goals']);
      $log->set('rcd_' . $resource . '_strategy', $values['resources'][$resource]['strategy']);
    }

    // Process taxonomy reference fields.
    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    foreach ([
      'watershed',
      'groundwater_basin',
      'wildlife_species',
      'plant_species',
    ] as $name) {
      $vid = 'rcd_' . $name;
      $log->set($vid, []);
      if (!empty($values[$name])) {
        $terms = array_map(function ($value) use ($term_storage, $vid) {
          if (!empty($value['entity_id'])) {
            return $term_storage->load($value['entity_id']);
          }
          elseif (!empty($value['value'])) {
            return $this->createOrLoadTerm($value['value'], $vid);
          }
          return NULL;
        }, json_decode($values[$name], TRUE) ?? []);
        $log->set($vid, $terms);
      }
    }

    // Set the name of the log based on its timestamp and locations.
    // Only add the first location name. If there are multiple locations, add
    // "(+ X more)".
    // Ensure the name is under 255 characters (we need to do this because the
    // user can't).
    $name = date('m/d/Y', (int) $log->get('timestamp')->value);
    $locations = array_map(function ($location) {
      /** @var \Drupal\asset\Entity\AssetInterface $location */
      return $location->label();
    }, $log->get('location')->referencedEntities());
    $name .= ' ' . $locations[0];
    $count = count($locations);
    if ($count > 1) {
      $name .= ' (+ ' . ($count - 1) . ' more)';
    }
    $name = mb_strimwidth($name, 0, 255, '…');
    $log->set('name', $name);

    /** @var \Drupal\log\Entity\LogInterface $log */
    return $log;
  }

  /**
   * Given a term name, create or load a matching term entity.
   *
   * @param string $name
   *   The term name.
   * @param string $vocabulary
   *   The vocabulary to search or create in.
   *
   * @return \Drupal\taxonomy\TermInterface
   *   The term entity that was created or loaded.
   */
  protected function createOrLoadTerm(string $name, string $vocabulary) {

    // Get term entity storage.
    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');

    // First try to load an existing term.
    /** @var \Drupal\taxonomy\TermInterface[] $search */
    $search = $term_storage->loadByProperties(['name' => $name, 'vid' => $vocabulary]);
    if (!empty($search)) {
      $term = reset($search);
    }

    // Otherwise, create a new term.
    else {
      $term = $term_storage->create([
        'name' => $name,
        'vid' => $vocabulary,
      ]);
      $term->save();
    }

    return $term;
  }

}
