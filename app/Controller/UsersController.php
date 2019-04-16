<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

  public function index() {
    $this->User->recursive = 0;
    $this->set('users', $this->paginate());
  }

  public function view($id = null) {
    $this->User->id = $id;
    if (!$this->User->exists()) {
      throw new NotFoundException(__('Invalid user'));
    }
    $this->set('user', $this->User->findById($id));
  }

  public function add() {
    if ($this->request->is('post')) {
      $this->User->create();
      if ($this->User->save($this->request->data)) {
        $this->Flash->success(__('The user has been saved'));
        return $this->redirect(array('action' => 'index'));
      }
      $this->Flash->error(
      __('The user could not be saved. Please, try again.')
      );
    }
  }

  public function edit($id = null) {
    $this->User->id = $id;
    if (!$this->User->exists()) {
      throw new NotFoundException(__('Invalid user'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if ($this->User->save($this->request->data)) {
        $this->Flash->success(__('The user has been saved'));
        return $this->redirect(array('action' => 'index'));
      }
      $this->Flash->error(
      __('The user could not be saved. Please, try again.')
      );
    } else {
      $this->request->data = $this->User->findById($id);
      unset($this->request->data['User']['password']);
    }
  }

  public function delete($id = null) {
    // Prior to 2.5 use
    // $this->request->onlyAllow('post');

    $this->request->allowMethod('post');

    $this->User->id = $id;
    if (!$this->User->exists()) {
      throw new NotFoundException(__('Invalid user'));
    }
    if ($this->User->delete()) {
      $this->Flash->success(__('User deleted'));
      return $this->redirect(array('action' => 'index'));
    }
    $this->Flash->error(__('User was not deleted'));
    return $this->redirect(array('action' => 'index'));
  }

  public function beforeFilter() {
    parent::beforeFilter();
    // ユーザー自身による登録とログアウトを許可する
    $this->Auth->allow('add', 'logout');
  }

  public function login() {
    if ($this->request->is('post')) {
      if ($this->Auth->login()) {
        $this->redirect($this->Auth->redirect());
      } else {
        $this->Flash->error(__('Invalid username or password, try again'));
      }
    }
  }

  public function logout() {
    $this->redirect($this->Auth->logout());
  }

}
