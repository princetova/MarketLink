@extends('layouts.admin')

@section('title', 'Farmer Approvals | MarketLink Admin')
@section('meta_description', 'Review MarketLink farmer registrations and manage selling access.')

@section('content')
<main class="admin-main" id="main-content">
    <header class="admin-intro">
        <p class="admin-eyebrow">MarketLink Administration</p>
        <h1>Farmer Approvals</h1>
        <p>Review farmer registrations and manage access to MarketLink selling features.</p>
    </header>

    @if (session('status'))
        <p class="admin-flash admin-flash--success" role="status">{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <div class="admin-flash admin-flash--error" role="alert">
            <strong>The status could not be updated.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="approval-summary" aria-label="Farmer approval totals">
        @foreach ($statuses as $status)
            <a class="approval-summary__card approval-summary__card--{{ strtolower($status) }}{{ $activeStatus === $status ? ' is-active' : '' }}"
               href="{{ route('admin.farmers.approvals.index', array_filter(['search' => $search, 'status' => $status])) }}">
                <span>{{ ucfirst(strtolower($status)) }}</span>
                <strong>{{ $statusCounts->get($status, 0) }}</strong>
            </a>
        @endforeach
    </section>

    <section class="approval-workspace" aria-labelledby="approval-list-title">
        <div class="approval-toolbar">
            <div>
                <h2 id="approval-list-title">Registered farmers</h2>
                <p>{{ $farmerProfiles->total() }} {{ Str::plural('registration', $farmerProfiles->total()) }} found</p>
            </div>

            <form class="approval-filters" method="GET" action="{{ route('admin.farmers.approvals.index') }}">
                <div class="approval-filter-field approval-filter-field--search">
                    <label for="approval-search">Search farmers</label>
                    <input id="approval-search" name="search" type="search" value="{{ $search }}" placeholder="Business, contact, or email">
                </div>
                <div class="approval-filter-field">
                    <label for="approval-status-filter">Status</label>
                    <select id="approval-status-filter" name="status">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" @selected($activeStatus === $status)>{{ ucfirst(strtolower($status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="admin-button admin-button--primary" type="submit">Apply filters</button>
                @if ($search !== '' || $activeStatus)
                    <a class="admin-button admin-button--quiet" href="{{ route('admin.farmers.approvals.index') }}">Clear</a>
                @endif
            </form>
        </div>

        @if ($farmerProfiles->isEmpty())
            <div class="approval-empty">
                <h3>No farmer registrations found</h3>
                <p>Try changing the search text or status filter.</p>
            </div>
        @else
            <div class="approval-table-wrap">
                <table class="approval-table">
                    <caption class="sr-only">Farmer registrations and approval actions</caption>
                    <thead>
                        <tr>
                            <th scope="col">Farmer</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Address</th>
                            <th scope="col">Registered</th>
                            <th scope="col">Status</th>
                            <th scope="col">Review action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($farmerProfiles as $farmerProfile)
                            @php
                                $transitions = $allowedTransitions[$farmerProfile->approval_status] ?? [];
                                $requiresDecisionNote = in_array('REJECTED', $transitions, true) || in_array('SUSPENDED', $transitions, true);
                            @endphp
                            <tr>
                                <td data-label="Business">
                                    <strong class="approval-business">{{ $farmerProfile->business_name }}</strong>
                                </td>
                                <td data-label="Contact">
                                    <strong>{{ $farmerProfile->user->name }}</strong>
                                    <a href="mailto:{{ $farmerProfile->user->email }}">{{ $farmerProfile->user->email }}</a>
                                    <span>{{ $farmerProfile->user->phone ?: 'No phone provided' }}</span>
                                </td>
                                <td data-label="Address">{{ $farmerProfile->address }}</td>
                                <td data-label="Registered">
                                    <time datetime="{{ $farmerProfile->created_at->toDateString() }}">{{ $farmerProfile->created_at->format('M j, Y') }}</time>
                                </td>
                                <td data-label="Status">
                                    <span class="approval-status approval-status--{{ strtolower($farmerProfile->approval_status) }}">{{ $farmerProfile->approval_status }}</span>
                                </td>
                                <td data-label="Action">
                                    @if ($transitions !== [])
                                        <form class="approval-action" method="POST" action="{{ route('admin.farmers.approvals.update', $farmerProfile) }}" data-approval-form>
                                            @csrf
                                            @method('PATCH')
                                            <label for="approval-status-{{ $farmerProfile->id }}">New status</label>
                                            <select id="approval-status-{{ $farmerProfile->id }}" name="approval_status" required data-approval-status>
                                                <option value="">Choose action</option>
                                                @foreach ($transitions as $transition)
                                                    <option value="{{ $transition }}">
                                                        @switch($transition)
                                                            @case('APPROVED') Approve @break
                                                            @case('REJECTED') Reject @break
                                                            @case('SUSPENDED') Suspend @break
                                                            @case('PENDING') Return to pending @break
                                                        @endswitch
                                                    </option>
                                                @endforeach
                                            </select>

                                            <label for="review-note-{{ $farmerProfile->id }}">Review note <span>({{ $requiresDecisionNote ? 'required for reject or suspend' : 'optional' }})</span></label>
                                            <textarea id="review-note-{{ $farmerProfile->id }}" name="review_note" rows="2" maxlength="500" placeholder="Add a concise decision note"></textarea>

                                            <button class="admin-button admin-button--primary" type="submit">Update status</button>
                                        </form>
                                    @else
                                        <span class="approval-unavailable">No action available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($farmerProfiles->hasPages())
                <nav class="approval-pagination" aria-label="Farmer approvals pagination">
                    @if ($farmerProfiles->onFirstPage())
                        <span aria-disabled="true">Previous</span>
                    @else
                        <a href="{{ $farmerProfiles->previousPageUrl() }}" rel="prev">Previous</a>
                    @endif

                    <span>Page {{ $farmerProfiles->currentPage() }} of {{ $farmerProfiles->lastPage() }}</span>

                    @if ($farmerProfiles->hasMorePages())
                        <a href="{{ $farmerProfiles->nextPageUrl() }}" rel="next">Next</a>
                    @else
                        <span aria-disabled="true">Next</span>
                    @endif
                </nav>
            @endif
        @endif
    </section>
</main>
@endsection
