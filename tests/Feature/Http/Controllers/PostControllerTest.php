<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PostController
 */
final class PostControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $posts = Post::factory()->count(3)->create();

        $response = $this->get(route('post.index'));

        $response->assertOk();
        $response->assertViewIs('post.index');
        $response->assertViewHas('posts');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('post.create'));

        $response->assertOk();
        $response->assertViewIs('post.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PostController::class,
            'store',
            \App\Http\Requests\PostStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $name = $this->faker->name();

        $response = $this->post(route('post.store'), [
            'name' => $name,
        ]);

        $posts = Post::query()
            ->where('name', $name)
            ->get();
        $this->assertCount(1, $posts);
        $post = $posts->first();

        $response->assertRedirect(route('post.index'));
        $response->assertSessionHas('post.id', $post->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('post.show', $post));

        $response->assertOk();
        $response->assertViewIs('post.show');
        $response->assertViewHas('post');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('post.edit', $post));

        $response->assertOk();
        $response->assertViewIs('post.edit');
        $response->assertViewHas('post');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PostController::class,
            'update',
            \App\Http\Requests\PostUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $post = Post::factory()->create();
        $name = $this->faker->name();

        $response = $this->put(route('post.update', $post), [
            'name' => $name,
        ]);

        $post->refresh();

        $response->assertRedirect(route('post.index'));
        $response->assertSessionHas('post.id', $post->id);

        $this->assertEquals($name, $post->name);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $post = Post::factory()->create();

        $response = $this->delete(route('post.destroy', $post));

        $response->assertRedirect(route('post.index'));

        $this->assertModelMissing($post);
    }
}
