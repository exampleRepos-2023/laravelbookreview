@extends('layout.app')

@section('content')
<h1 class='mb-10 text-2xl'>Books</h1>

<form class='flex mb-4 items-center gap-2' method="GET" action="{{ route('books.index') }}">
    <input type="text" class='input h-10' name="title" placeholder="Search" value="{{ request()->input('title') }}">
    <input type="hidden" name="filter" value="{{ request()->input('filter') }}">
    <button type="submit" class="btn h-10">Search</button>
    <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
</form>

<div class="filter-container mb-4 flex">
    @php
        $filters = [
            'latest'                    => 'Latest',
            'popular_last_month'        => 'Popular Last Month',
            'popular_last_6month'       => 'Popular Last 6 Months',
            'highest_rated_last_month'  => 'Highest Rated Last Month',
            'highest_rated_last_6month' => 'Highest Rated Last 6 Months',
        ];
    @endphp

    @foreach ($filters as $key => $label)
        <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"
            class="{{ request()->input('filter') === $key || (request()->input('filter') === null && $key === 'latest') ? 'filter-item-active' : 'filter-item' }}">{{ $label }}
        </a>
    @endforeach
</div>

<ul>
    @forelse ($books as $book)
        <li class="mb-4">
            <div class="book-item">
                <div class="flex flex-wrap items-center justify-between">
                    <div class="w-full flex-grow sm:w-auto">
                        <a href="{{route('books.show', $book)}}" class="book-title">{{ $book->title }}</a>
                        <span class="book-author">by {{ $book->author }}</span>
                    </div>
                    <div>
                        <div class="book-rating">
                            {{ number_format($book->reviews->avg('rating'), 1) }}
                        </div>
                        <div class="book-review-count">
                            out of {{ $book->reviews->count() }}
                            {{ Str::plural('review', $book->reviews->count()) }}
                        </div>
                    </div>
                </div>
            </div>
        </li>
    @empty
        <li class="mb-4">
            <div class="empty-book-item">
                <p class="empty-text">No books found</p>
                <a href="{{route('books.index')}}" class="reset-link">Reset criteria</a>
            </div>
        </li>
    @endforelse
</ul>
@endSection
