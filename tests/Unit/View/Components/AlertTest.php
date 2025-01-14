<?php

namespace Tests\Unit\View\Components;

use WireUi\Enum\Packs;
use WireUi\View\Components\Alert;
use WireUi\WireUi\Alert\Color\{Flat, Outline};
use WireUi\WireUi\Rounded;

beforeEach(function () {
    $this->component = (new Alert())->withName('alert');
});

test('it should have array properties', function () {
    $packs = $this->invokeProperty($this->component, 'packs');

    expect($packs)->toBe(['shadow', 'padding']);

    $props = $this->invokeProperty($this->component, 'props');

    expect($props)->toBe([
        'icon'       => null,
        'title'      => null,
        'iconless'   => false,
        'shadowless' => false,
    ]);
});

test('it should have properties in component', function () {
    $this->runWireUiComponent($this->component);

    expect($this->component)->toHaveProperties([
        'icon',
        'color',
        'title',
        'shadow',
        'padding',
        'rounded',
        'squared',
        'variant',
        'iconless',
        'shadowless',
        'colorClasses',
        'shadowClasses',
        'paddingClasses',
        'roundedClasses',
    ]);

    expect($this->component->iconless)->toBeFalse();

    expect($this->component->shadowless)->toBeFalse();
});

test('it should not have properties in component', function () {
    expect($this->component)->not->toHaveProperties([
        'icon',
        'title',
        'shadow',
        'padding',
        'iconless',
        'shadowless',
        'shadowClasses',
        'paddingClasses',
    ]);
});

test('it should set specific title in component', function () {
    $this->setAttributes($this->component, [
        'title' => $title = fake()->word(),
    ]);

    $this->runWireUiComponent($this->component);

    expect($this->component->title)->toBe($title);

    expect('<x-alert :title="$title" />')->render(compact('title'))->toContain($title);
});

test('it should set icon in component and using iconless', function () {
    $title = fake()->word();

    $this->setAttributes($this->component, [
        'icon' => $icon = $this->getRandomIcon(),
    ]);

    $this->runWireUiComponent($this->component);

    expect($this->component->icon)->toBe($icon);

    $iconColor = data_get((new Flat())->get(), 'iconColor');

    $html = render('<x-icon :name="$icon" @class([$iconColor, "w-5 h-5 mr-3 shrink-0"]) />', compact('icon', 'iconColor'));

    expect('<x-alert :$icon :title="$title" />')->render(compact('icon', 'title'))->toContain($html);

    expect('<x-alert :$icon :title="$title" iconless />')->render(compact('icon', 'title'))->not->toContain($html);
});

test('it should set specific color in component with variant outline', function () {
    $title = fake()->word();

    $this->setAttributes($this->component, [
        'color'   => Packs\Color::INFO,
        'variant' => Packs\Variant::OUTLINE,
    ]);

    $this->runWireUiComponent($this->component);

    expect($this->component->color)->toBe($color = Packs\Color::INFO);

    expect($this->component->variant)->toBe($variant = Packs\Variant::OUTLINE);

    expect($this->component->colorClasses)->toBe($class = (new Outline())->get(Packs\Color::INFO));

    $icon = collect($class)->get('icon');

    $iconColor = collect($class)->get('iconColor');

    $html = render('<x-icon :name="$icon" @class([$iconColor, "w-5 h-5 mr-3 shrink-0"]) />', compact('icon', 'iconColor'));

    expect('<x-alert :title="$title" :$color :$variant />')
        ->render(compact('title', 'color', 'variant'))
        ->toContain(...collect($class)->except(['icon', 'iconColor'])->flatten()->toArray())
        ->toContain($html);
});

test('it should set rounded full in component', function () {
    $this->setAttributes($this->component, [
        'rounded' => true,
    ]);

    $this->runWireUiComponent($this->component);

    expect($this->component->rounded)->toBeTrue();

    expect($this->component->squared)->toBeFalse();

    expect($this->component->roundedClasses)->toBe($class = (new Rounded())->get(Packs\Rounded::FULL));

    expect('<x-alert rounded />')->render()->toContain($class);
});

test('it should set squared in component', function () {
    $this->setAttributes($this->component, [
        'squared' => true,
    ]);

    $this->runWireUiComponent($this->component);

    expect($this->component->squared)->toBeTrue();

    expect($this->component->rounded)->toBeFalse();

    expect($this->component->roundedClasses)->toBe($class = (new Rounded())->get(Packs\Rounded::NONE));

    expect('<x-alert squared />')->render()->toContain($class);
});

test('it should custom rounded in component', function () {
    $this->setAttributes($this->component, [
        'rounded' => $class = 'rounded-[40px]',
    ]);

    $this->runWireUiComponent($this->component);

    expect($this->component->squared)->toBeFalse();

    expect($this->component->rounded)->toBe($class);

    expect($this->component->roundedClasses)->toBe($class);

    expect('<x-alert rounded="rounded-[40px]" />')->render()->toContain($class);
});
