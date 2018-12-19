<?php

namespace Drupal\annonce\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\Context\ContextDefinition;

/**
* Provides a 'Date condition' condition to enable a condition based in module selected status.
*
* @Condition(
*   id = "date_condition",
*   label = @Translation("Date condition"),
*   context = {
*     "annonce" = @ContextDefinition("entity:annonce", required = FALSE , label = @Translation("annonce"))
*   }
* )
*
*/
class DateCondition extends ConditionPluginBase {

/**
* {@inheritdoc}
*/
public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
{
    return new static(
    $configuration,
    $plugin_id,
    $plugin_definition
    );
}

/**
 * Creates a new DateCondition object.
 *
 * @param array $configuration
 *   The plugin configuration, i.e. an array with configuration values keyed
 *   by configuration option name. The special key 'context' may be used to
 *   initialize the defined contexts by setting it to an array of context
 *   values keyed by context names.
 * @param string $plugin_id
 *   The plugin_id for the plugin instance.
 * @param mixed $plugin_definition
 *   The plugin implementation definition.
 */
 public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
 }

 /**
   * {@inheritdoc}
   */
 public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildConfigurationForm($form, $form_state);
    $form['date_debut'] = [
     '#type' => 'date',
     '#title' => $this->t('Date de debut'),
     '#default_value' => $this->configuration['date_debut'],
     '#description' => $this->t('Date de debut de parution.'),
   ];

    $form['date_fin'] = [
     '#type' => 'date',
     '#title' => $this->t('Date de fin'),
     '#default_value' => $this->configuration['date_fin'],
     '#description' => $this->t('Date de fin de parution.'),
   ];

    $form['negate']['#access'] = FALSE;

    return $form;
 }

/**
 * {@inheritdoc}
 */
 public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
     $this->configuration['date_debut'] = $form_state->getValue('date_debut');
     $this->configuration['date_fin'] = $form_state->getValue('date_fin');
     parent::submitConfigurationForm($form, $form_state);
 }

/**
 * {@inheritdoc}
 */
 public function defaultConfiguration() {
    return ['module' => ''] + parent::defaultConfiguration();
 }

/**
  * Evaluates the condition and returns TRUE or FALSE accordingly.
  *
  * @return bool
  *   TRUE if the condition has been met, FALSE otherwise.
  */
  public function evaluate() {

    $date_debut = strtotime($this->configuration['date_debut']);
    $date_fin =strtotime($this->configuration['date_fin']);

    if(!empty($date_debut) && !empty(date_fin)){
      // ici le comportement
      return TRUE;
    }
    else{

      return FALSE;
    }

  }

/**
 * Provides a human readable summary of the condition's configuration.
 */
 public function summary()
 {
     $module = $this->getContextValue('module');
     $modules = system_rebuild_module_data();

     $status = ($modules[$module]->status)?t('enabled'):t('disabled');

     return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);
 }

}
