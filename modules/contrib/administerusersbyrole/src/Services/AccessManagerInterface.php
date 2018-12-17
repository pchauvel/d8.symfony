<?php

namespace Drupal\administerusersbyrole\Services;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Config\Config;

/**
 * Interface for access manager service.
 */
interface AccessManagerInterface {

  /**
   * Check access for the specified roles.
   *
   * @param array $roles
   *   Roles of the user object to check access for.
   *
   * @param string $operation
   *   The operation that is to be performed on the user.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account trying to access the entity.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result. hook_entity_access() has detailed documentation.
   */
  public function access(array $roles, $operation, AccountInterface $account);

  /**
   * List all accessible roles for the specified operation.
   *
   * @param string $operation
   *   The operation that is to be performed.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account trying to access the entity.
   *
   * @return array of role IDs.
   */
  public function listRoles($operation, AccountInterface $account);

  /**
   * Return permissions to add.
   *
   * @return array of permissions.
   */
  public function permissions();

}
