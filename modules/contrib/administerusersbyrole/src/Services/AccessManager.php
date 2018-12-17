<?php

namespace Drupal\administerusersbyrole\Services;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\user\Entity\Role;

/**
 * Access Manager.
 */
class AccessManager implements AccessManagerInterface {

  use StringTranslationTrait;

  /* @var \Drupal\Core\Config\ImmutableConfig */
  protected $config;

  const SAFE = 'safe';
  const UNSAFE = 'unsafe';
  const PERM = 'perm';

  const CONVERT_OP = [
    'cancel' => 'cancel',
    'delete' => 'cancel',
    'edit' => 'edit',
    'update' => 'edit',
    'view' => 'view',
    'role-assign' => 'role-assign',
  ];

  function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('administerusersbyrole.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function permissions() {
    // Base permissions.
    $op_titles = [
      'edit' => $this->t('Edit users by role'),
      'cancel' => $this->t('Cancel users by role'),
      'view' => $this->t('View users by role'),
      'role-assign' => $this->t('Assign roles by role'),
    ];

    foreach ($op_titles as $op => $title) {
      $perm_string = $this->buildPermString($op);
      $perms[$perm_string] = ['title' => $title];
    }

    // Per role permissions.
    $role_config = array_filter($this->config->get('roles'), function($s) { return $s == self::PERM; });
    $roles = Role::loadMultiple(array_keys($role_config));

    foreach ($roles as $rid => $role) {
      $op_titles = [
        'edit' => $this->t('Edit users with role %role', ['%role' => $role->label()]),
        'cancel' => $this->t('Cancel users with role %role', ['%role' => $role->label()]),
        'view' => $this->t('View users with role %role', ['%role' => $role->label()]),
        'role-assign' => $this->t('Assign role %role', ['%role' => $role->label()]),
      ];

      foreach ($op_titles as $op => $title) {
        $perm_string = $this->buildPermString($op, $rid);
        $perms[$perm_string] = ['title' => $title];
      }
    }

    return $perms;
  }

  /**
   * {@inheritdoc}
   */
  public function access(array $roles, $operation, AccountInterface $account) {
    if (!$this->preAccess($operation, $account)) {
      return AccessResult::neutral();
    }

    foreach ($roles as $rid) {
      if (!$this->roleAccess($operation, $account, $rid)) {
        return AccessResult::neutral();
      }
    }

    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  public function listRoles($operation, AccountInterface $account) {
    if (!$this->preAccess($operation, $account)) {
      return [];
    }

    $roles = [];
    foreach (array_keys($this->config->get('roles')) as $rid) {
      if ($this->roleAccess($operation, $account, $rid)) {
        $roles[] = $rid;
      }
    }

    return $roles;
  }

  /**
   * Initial access check for an operation to test if access might be granted for some roles.
   *
   * @param string $operation: The operation that is to be performed on the user.
   *   Value is updated to match the canonical value used in this module.
   *
   * @param \Drupal\Core\Session\AccountInterface $account: The account trying to access the entity.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result. hook_entity_access() has detailed documentation.
   */
  protected function preAccess(&$operation, AccountInterface $account) {
    // Full admins already have permissions so we are wasting our time to continue.
    if ($account->hasPermission('administer users')) {
      return FALSE;
    }

    // Ignore unrecognised operation.
    if (!array_key_exists($operation, self::CONVERT_OP)) {
      return FALSE;
    }

    // Check the base permission.
    $operation = self::CONVERT_OP[$operation];
    return $this->hasPerm($operation, $account);
  }

  /**
   * Checks access for a given role.
   */
  protected function roleAccess($operation, AccountInterface $account, $rid) {
    $setting = $this->config->get("roles.$rid") ?? self::UNSAFE;

    switch ($setting) {
      case self::SAFE:
        return TRUE;

      case self::UNSAFE:
        return FALSE;

      case self::PERM:
        return $this->hasPerm($operation, $account, $rid);
    }
  }

  /**
   * Checks access to a permission for a given role.
   */
  protected function hasPerm($operation, AccountInterface $account, $rid = NULL) {
    return $account->hasPermission($this->buildPermString($operation, $rid));
  }

  /**
   * Generates a permission string for a given role.
   */
  protected function buildPermString($operation, $rid = NULL) {
    return $rid ? "$operation users with role $rid" : "$operation users by role";
  }

}
