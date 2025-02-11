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
        Inventory Tracker: Welcome to CakePHP's Inventory Tracker
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
                Welcome to CakePHP's Inventory Tracker
            </h1>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <div class="text-center">
                <?= $this->Html->link('Add Product', ['action' => 'product_form']) ?>
            </div>
            <?= $this->Form->create(null, ['type' => 'get', 'url' => ['action' => 'search']]) ?>
                <div class="search-container">
                    <div class="search-input">
                        <?= $this->Form->control('search', [
                            'label' => '',
                            'type' => 'search',
                            'placeholder' => 'Search for Product'
                        ]) ?>
                    </div>
                    <div class="search-button">
                        <?= $this->Form->button('Search') ?>
                    </div>
                </div>
            <?= $this->Form->end() ?>
            <?php if (isset($searchKeywords) && $searchKeywords !== ''): ?>
                <h2>Search for '<?= $searchKeywords ?>'</h2>
            <?php endif ?>
            <div class="content">
                <div class="row">
                    <strong class="column">
                        Name
                    </strong>
                    <strong class="column">
                        Quantity
                    </strong>
                    <strong class="column">
                        Price
                    </strong>
                    <strong class="column">
                        Status
                    </strong>
                    <strong class="column">
                        Last Updated
                    </strong>
                    <strong class="column">
                        Edit Product
                    </strong>
                    <strong class="column">
                        Delete Product
                    </strong>
                </div>
                <?php foreach ($products as $pr): ?>
                <div class="row">
                    <div class="column">
                        <?= h($pr->getName()) ?>
                    </div>
                    <div class="column">
                        <?= h($pr->getQuantity()) ?>
                    </div>
                    <div class="column">
                        £<?= h($pr->getPrice()) ?>
                    </div>
                    <div class="column">
                        <?= h($pr->getStatus()) ?>
                    </div>
                    <div class="column">
                        <?= h($pr->getLastUpdated()) ?>
                    </div>
                    <div class="column">
                        <?= $this->Html->link('Edit', [
                            'action' => 'product_form', $pr->getId()
                        ]) ?>
                    </div>
                    <div class="column">
                        <?= $this->Html->link('Delete', [
                            'action' => 'delete', $pr->getId()
                        ]) ?>
                    </div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </main>
</body>
</html>
