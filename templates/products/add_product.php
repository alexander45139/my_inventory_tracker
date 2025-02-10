<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$this->disableAutoLayout();

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Inventory Tracker: Add Product
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake', 'home']) ?>
    <?= $this->Html->script('home') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
        <div class="container text-center">
            <h1>
                Add Product
            </h1>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <?= $this->Form->create(null, ['type' => 'post', 'url' => ['action' => 'add']]) ?>
                <?= $this->Form->control('name', [
                    'type' => 'text',
                    'required' => true,
                    'minlength' => 3,
                    'maxlength' => 50
                ]) ?>
                <?= $this->Form->control('quantity', [
                    'type' => 'number',
                    'required' => true,
                    'step' => 0,
                    'min' => 0,
                    'max' => 1000
                ]) ?>
                <?= $this->Form->control('price', [
                    'type' => 'number',
                    'required' => true,
                    'step' => 0.01,
                    'min' => 0,
                    'max'=> 10000
                ]) ?>
                <?php if (isset($product) && $product->hasErrors()): ?>
                    <?php foreach($product->getErrors() as $error): ?>
                        <div><?= $error ?></div>
                    <?php endforeach ?>
                <?php endif ?>
            <?= $this->Form->submit('Submit') ?>
        </div>
    </main>
</body>
</html>