<?php

namespace Drupal\administerusersbyrole\Constraint;

use Drupal\user\Plugin\Validation\Constraint\UserMailRequired;

/**
 * Checks if the user's email address is provided if required.
 *
 * The user mail field is NOT required if account originally had no mail set
 * and the user is managing another user's account.
 * This allows users without email address to be edited and deleted.
 */
class OverrideUserMailRequired extends UserMailRequired {

}
