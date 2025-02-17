<?php

namespace Spatie\BladeX\Tests\Features\ComponentCompilation;

use Illuminate\Support\Facades\View;
use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\Tests\TestCase;

class ComponentCompilationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__.'/stubs');
    }

    /** @test */
    public function it_compiles_a_regular_component()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('regularComponent');
    }

    /** @test */
    public function it_compiles_a_self_closing_component()
    {
        BladeX::component('components.alert');

        $this->assertMatchesViewSnapshot('selfClosingComponent');
    }

    /** @test */
    public function it_compiles_a_view_with_two_components()
    {
        BladeX::component('components.card');
        BladeX::component('components.textField');

        $this->assertMatchesViewSnapshot('twoComponents');
    }

    /** @test */
    public function it_compiles_a_component_that_is_used_recursively()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('recursiveComponents');
    }

    /** @test */
    public function it_compiles_a_component_with_scoped_slots()
    {
        BladeX::component('components.layout');

        $this->assertMatchesViewSnapshot('componentWithScopedSlots');
    }

    /** @test */
    public function it_compiles_a_component_with_no_spaces()
    {
        BladeX::component('components.layout');
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithNoSpaces');
    }

    /** @test */
    public function it_compiles_a_component_with_variables()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithVariables');
    }

    /** @test */
    public function it_compiles_a_component_that_uses_an_object_property_as_value()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentUsingObjectProperty');
    }

    /** @test */
    public function it_compiles_a_component_with_an_unescaped_variable()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithUnescapedVariables');
    }

    /** @test */
    public function it_compiles_a_component_with_a_quoteless_attribute()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithQuotelessAttribute');
    }

    /** @test */
    public function it_compiles_a_component_with_a_spaceship_operator()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithSpaceshipOperatorInAttribute');
    }

    /** @test */
    public function it_works_with_a_global_prefix()
    {
        BladeX::component('components.card');

        BladeX::prefix('x');

        $this->assertMatchesViewSnapshot('globalPrefix');
    }

    /** @test */
    public function a_global_prefix_works_with_namespaced_component()
    {
        View::addNamespace('namespaced-components', __DIR__.'/stubs/namespacedComponents');

        BladeX::component('namespaced-components::namespacedCard');

        BladeX::prefix('x');

        $this->assertMatchesViewSnapshot('namespacedGlobalPrefix');
    }

    /** @test */
    public function it_compiles_components_that_use_a_global_function()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('globalFunction');
    }

    /** @test */
    public function it_compiles_kebas_case_attributes_as_camelcase_variables()
    {
        BladeX::component('components.header');

        $this->assertMatchesViewSnapshot('kebabCaseAttributes');
    }

    /** @test */
    public function it_compiles_single_line_tags_with_content()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('singleLineComponentWithContent');
    }

    /** @test */
    public function it_compiles_components_with_multi_line_closing_tags()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithMultiLineClosingTag');
    }

    /** @test */
    public function it_compiles_boolean_attributes_as_true_values()
    {
        BladeX::component('components.checkbox');

        $this->assertMatchesViewSnapshot('componentWithBooleanAttribute');
    }

    /** @test */
    public function it_compiles_components_whose_names_begin_with_the_same_string()
    {
        BladeX::component('components.card');
        BladeX::component('components.cardGroup');

        $this->assertMatchesViewSnapshot('componentsWithSimilarNames');
    }

    /** @test */
    public function it_compiles_components_with_empty_attributes()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithEmptyAttributes');
    }

    /** @test */
    public function it_compiles_livewire_syntax()
    {
        BladeX::component('components.textField');

        $this->assertMatchesViewSnapshot('livewireAttributes');
    }

    /** @test */
    public function it_compiles_namespaced_attributes_syntax()
    {
        BladeX::component('components.textField');

        $this->assertMatchesViewSnapshot('namespacedAttributes');
    }

    /** @test */
    public function it_compiles_a_attribute_spread_component()
    {
        BladeX::component('components.textField');

        $this->assertMatchesViewSnapshot('spreadAttributes', [
            'input' => [
                'name' => 'email',
                'label' => 'e-mail',
                'type' => 'email',
                'value' => 'example@domain.tld',
            ],
            'email' => [
                'value' => 'blade-x@spatie.be',
            ],
            'foo' => 'bar',
        ]);
    }
}
