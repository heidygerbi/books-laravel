<?php

namespace Tests\Feature;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    // obtener todos los libros
    /** @test */
    function can_get_all_books() {
        $books = Book::factory(4)->create();
        // dd($books->count());
        // $this->get('/api/books')->dump();
        $respose = $this->getJson(route('books.index'));
        $respose ->assertJsonFragment([
            'title' => $books[0] ->title
        ]);
    }   


    // obtener un libro
    /** @test */
    function can_get_one_book() {
        $book = Book::factory()->create();
        $respose= $this->getJson(route('books.show', $book));
        $respose ->assertJsonFragment([
            'title' => $book->title
        ]);
    }   

    // crear libros
    /** @test */
    function can_create_books() {
        $this->postJson(route('books.store'))
            ->assertJsonValidationErrorFor('title');
        $this->postJson(route('books.store'), [
            'title'=>'Libro nuevo'
        ]) ->assertJsonFragment([
            'title' => 'Libro nuevo'
        ]);
        $this->assertDatabaseHas('books',[
            'title' => 'Libro nuevo'
        ]);
    }   

    // actualizar libros
    /** @test */
    function can_update_books() {
        $book = Book::factory()->create();
        $this->patchJson(route('books.update',$book),[])
            ->assertJsonValidationErrorFor('title');
        $this->patchJson(route('books.update', $book), [
            'title'=>'Libro editado'
        ])->assertJsonFragment([
            'title' => 'Libro editado'
        ]);
        $this->assertDatabaseHas('books',[
            'title' => 'Libro editado'
        ]);
    }   

    // eliminar libros
    /** @test */
    function can_delete_books() {
        $book = Book::factory()->create();
        $this->deleteJson(route('books.destroy',$book))
            ->assertNoContent();
        $this->assertDatabaseCount('books',0);
    }
}
