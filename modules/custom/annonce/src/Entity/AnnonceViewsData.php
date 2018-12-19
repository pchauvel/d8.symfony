<?php

namespace Drupal\annonce\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Annonce entities.
 */
class AnnonceViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {

    $data = parent::getViewsData();

    $data['annonce_history']['table']['group'] = t('Annonce history');
    $data['annonce_history']['table']['provider'] = 'annonce';
    $data['annonce_history']['table']['base'] = array(
      'field' => 'ahid',
      'title' => t('Annonce history'),
      'help' => t('Annonce history contains historical datas and can be related to annonces.'),
      'weight' => -100,
    );
    $data['annonce_history']['uid'] = array(
      'title' => t('Annonce User ID'),
      'help' => t('Annonce User ID.'),
      'field' => array('id' => 'numeric'),
      'sort' => array('id' => 'standard'),
      'filter' => array('id' => 'numeric'),
      'argument' => array('id' => 'numeric'),
      'relationship' => array(
        'base' => 'users_field_data',
        'base field' => 'uid',
        'id' => 'standard',
        'label' => t('Annonce history UID -> User ID'),
      ),
    );
    $data['annonce_history']['aid'] = array(
      'title' => t('Annonce ID'),
      'help' => t('Annonce content ID.'),
      'field' => array('id' => 'numeric'),
      'sort' => array('id' => 'standard'),
      'filter' => array('id' => 'numeric'),
      'argument' => array('id' => 'numeric'),
      'relationship' => array(
        'base' => 'annonce_field_data',
        'base field' => 'id',
        'id' => 'standard',
        'label' => t('Annonce history AID -> Annonce ID'),
      ),
    );
    $data ['annonce_history']['date'] = array(
      'title' => t('Annonce view date'),
      'help' => t('The date the annonce was viewed.'),
      'field' => array('id' => 'date'),
      'sort' => array('id' => 'date'),
      'filter' => array('id' => 'date'),
    );
    return $data;
  }

}
