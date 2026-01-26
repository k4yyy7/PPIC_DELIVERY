@extends('dashboard.layouts.index')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Search results for "{{ $q }}"</h5>

                @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <p class="text-muted">Found {{ $users->total() }} user(s)</p>
                @elseif($users->isEmpty())
                    <p class="text-muted">No results found.</p>
                @endif

                <div class="list-group">
                    @forelse($users as $user)
                        <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" href="#">
                            <div>
                                <div class="fw-bold">{{ $user->name }}</div>
                                <div class="text-muted small">{{ $user->email }}</div>
                            </div>
                            <small class="text-muted">ID: {{ $user->id }}</small>
                        </a>
                    @empty
                        <div class="list-group-item">No users matched your query.</div>
                    @endforelse
                </div>

                @if(method_exists($users, 'links'))
                    <div class="mt-3">{{ $users->links() }}</div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
