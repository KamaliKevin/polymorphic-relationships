<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CommentController
 */
final class CommentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $comments = Comment::factory()->count(3)->create();

        $response = $this->get(route('comment.index'));

        $response->assertOk();
        $response->assertViewIs('comment.index');
        $response->assertViewHas('comments');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('comment.create'));

        $response->assertOk();
        $response->assertViewIs('comment.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CommentController::class,
            'store',
            \App\Http\Requests\CommentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $content = $this->faker->paragraphs(3, true);

        $response = $this->post(route('comment.store'), [
            'content' => $content,
        ]);

        $comments = Comment::query()
            ->where('content', $content)
            ->get();
        $this->assertCount(1, $comments);
        $comment = $comments->first();

        $response->assertRedirect(route('comment.index'));
        $response->assertSessionHas('comment.id', $comment->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->get(route('comment.show', $comment));

        $response->assertOk();
        $response->assertViewIs('comment.show');
        $response->assertViewHas('comment');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->get(route('comment.edit', $comment));

        $response->assertOk();
        $response->assertViewIs('comment.edit');
        $response->assertViewHas('comment');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CommentController::class,
            'update',
            \App\Http\Requests\CommentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $comment = Comment::factory()->create();
        $content = $this->faker->paragraphs(3, true);

        $response = $this->put(route('comment.update', $comment), [
            'content' => $content,
        ]);

        $comment->refresh();

        $response->assertRedirect(route('comment.index'));
        $response->assertSessionHas('comment.id', $comment->id);

        $this->assertEquals($content, $comment->content);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->delete(route('comment.destroy', $comment));

        $response->assertRedirect(route('comment.index'));

        $this->assertModelMissing($comment);
    }
}
