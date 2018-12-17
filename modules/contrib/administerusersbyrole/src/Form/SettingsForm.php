<?php

namespace Drupal\administerusersbyrole\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\administerusersbyrole\Services\AccessManager;

/**
 * Configure AlbanyWeb settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'administerusersbyrole_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'administerusersbyrole.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('administerusersbyrole.settings');

    $form['roles'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Roles'),
    ];

    $options = [
      AccessManager::SAFE => $this->t('Safe'),
      AccessManager::UNSAFE => $this->t('Unsafe'),
      AccessManager::PERM => $this->t('Create permission'),
    ];

    foreach (user_roles(TRUE) as $rid => $role) {
      $form['roles'][$rid] = [
        '#type' => 'select',
        '#title' => Html::escape($role->label()),
        '#default_value' => $config->get("roles.$rid"),
        '#options' => $options,
        '#required' => TRUE,
      ];

      // Exclude the AUTHENTICATED_ROLE which is not a real role.
      if ($rid == AccountInterface::AUTHENTICATED_ROLE) {
        $form['roles'][$rid]['#default_value'] = AccessManager::SAFE;
        $form['roles'][$rid]['#access'] = FALSE;
      }
      // Exclude admin roles.  Once you can edit an admin, you can set their password, log in and do anything,
      // which defeats the point of using this module.
      elseif ($role->isAdmin() || $role->hasPermission('administer users')) {
        $form['roles'][$rid]['#default_value'] = AccessManager::UNSAFE;
        $form['roles'][$rid]['#access'] = FALSE;
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('administerusersbyrole.settings');
    $values = $form_state->cleanValues()->getValues();
    foreach ($values as $rid => $value) {
      $config->set("roles.$rid", $value);
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
