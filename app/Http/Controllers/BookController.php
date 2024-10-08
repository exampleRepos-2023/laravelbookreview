<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller {
    public function index(Request $request) {
        $title  = $request->input('title');
        $filter = $request->input('filter', '');

        $books = Book::when($title, function ($query, $title) {
            $query->title($title);
        });

        $books = match ($filter) {
            'popular_last_month'        => $books->popularLastMonth(),
            'popular_last_6month'       => $books->popularLast6Month(),
            'highest_rated_last_month'  => $books->highestRatedLastMonth(),
            'highest_rated_last_6month' => $books->highestRatedLast6Month(),
            default                     => $books->latest(),
        };

        $books = $books->get();

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book) {
        return view('books.show',
            ['book' =>
                $book->load(['reviews' => fn($query) => $query->latest()])
            ],
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
