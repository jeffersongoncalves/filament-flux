<?php

use Jeffersongoncalves\FilamentFlux\Components\FluxBreadcrumbs;

it('builds a list of items', function () {
    $crumbs = FluxBreadcrumbs::make()
        ->add('Home', '/')
        ->add('Posts', '/posts', 'document-text')
        ->add('Edit');

    expect($crumbs->getItems())->toHaveCount(3);
    expect($crumbs->getItems()[0])->toMatchArray(['label' => 'Home', 'url' => '/']);
    expect($crumbs->getItems()[1])->toMatchArray(['icon' => 'document-text']);
});

it('accepts items() as a bulk setter', function () {
    $crumbs = FluxBreadcrumbs::make()->items([
        ['label' => 'Dashboard', 'url' => '/admin'],
        ['label' => 'Users'],
    ]);

    expect($crumbs->getItems())->toHaveCount(2);
});
