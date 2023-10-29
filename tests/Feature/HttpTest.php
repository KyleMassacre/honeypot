<?php

namespace Larapress\Honeypot\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Str;
use JMac\Testing\Traits\AdditionalAssertions;
use Larapress\Honeypot\Facades\Honeypot;
use Larapress\Honeypot\Http\Middleware\HoneypotMiddleware;
use Larapress\Honeypot\Providers\HoneypotServiceProvider;
use Larapress\Honeypot\Tests\Helpers\TestServiceProvider;
use Larapress\Honeypot\View\HoneypotComponent;
use Orchestra\Testbench\TestCase as Orchestra;

class HttpTest extends Orchestra
{
    use InteractsWithViews;
    use AdditionalAssertions;

    protected function getPackageProviders($app): array
    {
        return [
            HoneypotServiceProvider::class,
            TestServiceProvider::class,
        ];
    }

    public function testHttpRequest()
    {
        $response = $this->get('/');
        $this->component(HoneypotComponent::class, [
            'cssClass' => 'test',
            'fields' => Honeypot::fields(),
            ])
            ->assertSee('.test {');

        $response->assertStatus(200);
    }

    public function testHoneypotInputComponent()
    {
        $this->blade('<x-honeypot::input name="test" css-class="test"/>')
            ->assertSee('<input type="text" name="test" class="test" autocomplete="new-test">', false);
    }

    public function testHoneypotComponentClassWithAttributes()
    {
        $fields = Honeypot::fields();
        $cssClass = 'test';
        $innerComponent = [];
        $component = $this->component(HoneypotComponent::class, [
            'cssClass' => $cssClass,
            'fields' => $fields,
        ]);
        foreach ($fields as $field) {
            $innerComponent[] = '<input type="text" name="'.$field.'" class="'.$cssClass.'" autocomplete="new-'.$field.'">';
        }
        $component->assertSeeInOrder($innerComponent, false);
    }

    public function testHoneypotComponentClassWithoutAttributes()
    {
        $fields = Honeypot::fields();
        $innerComponent = [];
        $component = $this->component(HoneypotComponent::class, []);
        foreach ($fields as $field) {
            $innerComponent[] = '<input type="text" name="'.$field.'" class="my-awesome-inputs" autocomplete="new-'.$field.'">';
        }
        $component->assertSeeInOrder($innerComponent, false);
    }

    public function testCanPostToForm()
    {
        $username = fake()->userName();
        $password = Str::random();
        $request = $this->post(route('honeypot.post'), [
            'username' => $username,
            'password' => $password,
        ]);

        $request->assertSee(['username' => $username, 'password' => $password], false);
    }

    public function testCanPostToFormWithHoneypotValuesAndGetRedirected()
    {
        $username = fake()->userName();
        $password = Str::random();
        $organization = fake()->company();
        $request = $this
            ->post(route('honeypot.post'), [
                'username' => $username,
                'password' => $password,
                'organization' => $organization,
                'method' => __FUNCTION__,
        ]);

        $this->followRedirects($request);
        $request->assertRedirect(Honeypot::redirectTo());
    }

    public function testCanUpdateFieldsOnTheFly()
    {
        $newFields = ['test1', 'test2'];
        Honeypot::updateFields($newFields);
        $fields = Honeypot::fields();
        $innerComponent = [];
        $component = $this->component(HoneypotComponent::class, [
            'fields' => $fields,
        ]);

        $this->assertEquals($newFields, Honeypot::fields());

        foreach ($fields as $field) {
            $innerComponent[] = '<input type="text" name="'.$field.'" class="my-awesome-inputs" autocomplete="new-'.$field.'">';
        }
        $component->assertSeeInOrder($innerComponent, false);
    }

    public function testCanPostToFormWithHoneypotValuesAndGetRedirectedAndReturnsPayload()
    {
        Honeypot::setRedirectTo('honeypot.redirect');
        $username = fake()->userName();
        $password = Str::random();
        $organization = fake()->company();
        $request = $this
            ->followingRedirects()
            ->post(route('honeypot.post'), [
                'username' => $username,
                'password' => $password,
                'organization' => $organization,
        ]);

        $this->assertRouteUsesMiddleware('honeypot.post', [HoneypotMiddleware::class]);

        $this->followRedirects($request)
            ->assertSee(Honeypot::payload(), false);
    }

    public function testCanPostToFormWithHoneypotValuesAndGetRedirectedWithoutALocation()
    {
        Honeypot::setRedirectTo(null);
        $username = fake()->userName();
        $password = Str::random();
        $organization = fake()->company();
        $request = $this
            ->followingRedirects()
            ->post(route('honeypot.post'), [
                'username' => $username,
                'password' => $password,
                'organization' => $organization,
            ]);

        $this->assertRouteUsesMiddleware('honeypot.post', [HoneypotMiddleware::class]);

        $this->followRedirects($request)
            ->assertLocation('/')
            ->assertSee(Honeypot::payload(), false);
    }
}
