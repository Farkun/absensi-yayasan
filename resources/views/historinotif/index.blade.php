@extends('layouts.admin.admin')

@section('content')
    <div class="container">
        <h4 class="mt-4">History</h4>
        <div class="history-list" style="max-height: 500px; overflow-y: auto;">
            @php
                $unread = $notifications->filter(function ($n) {
                    return is_null($n->read_at);
                });
            @endphp
            @if($unread->isEmpty())
                <p class="text-center">No History Avaiable.</p>
            @else
                @foreach($unread as $notification)
                    <a href="{{ route('notifications.read', $notification->id) }}" class="text-decoration-none">
                        <div class="history-item mb-3 p-3 border rounded">
                            <h6>{{ $notification->data['messages'] }}</h6>
                            <p class="mb-1 text-muted">{{ $notification->created_at->diffForHumans() }}</p>
                            <small class="text-danger">Unread</small>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>

        <!-- Gunakan tampilan pagination kustom -->
        <div class="d-flex justify-content-center mt-3">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $notifications->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $notifications->previousPageUrl() }}" tabindex="-1">Previous</a>
                    </li>
                    @for ($i = 1; $i <= $notifications->lastPage(); $i++)
                        <li class="page-item {{ $notifications->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $notifications->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ !$notifications->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $notifications->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
@endsection