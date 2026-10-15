@extends('layouts.app')
@section('page-title', 'Organisation Tickets')
@section('content')
<div class="space-y-6">
    <x-page-header title="Organisation Tickets" subtitle="Follow and contribute to your colleagues' support threads. Ownership stays with the requester." sys="SUPPORT://ORG" num="02">
        <x-slot:actions>
            <a href="{{ route('portal.tickets.index') }}" class="term-btn term-btn-ghost term-btn-sm">&larr; My Tickets</a>
        </x-slot:actions>
    </x-page-header>
    <form method="GET" class="term-panel p-4 flex flex-wrap gap-2" role="search">
        <select name="status" class="term-input !w-auto" aria-label="Filter by status">
            <option value="">All statuses</option>
            @foreach(['new', 'open', 'assigned', 'in_progress', 'waiting_customer', 'waiting_third_party', 'escalated', 'resolved', 'closed', 'cancelled'] as $s)<option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>@endforeach
        </select>
        <button class="term-btn term-btn-sm">Filter</button>
    </form>
    <div class="term-panel overflow-hidden">
        <div class="term-table-wrap !border-0">
            <table class="data-table term-table term-table-cards">
                <thead><tr><th>Ticket</th><th>Subject</th><th>Requester</th><th>Priority</th><th>Status</th><th>Created</th><th></th></tr></thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td data-label="Ticket"><a href="{{ route('portal.tickets.show', $ticket) }}" class="font-mono text-accent-soft hover:underline">{{ $ticket->ticket_number }}</a></td>
                        <td data-label="Subject" class="font-medium text-slate-900 dark:text-white">{{ $ticket->subject }}</td>
                        <td data-label="Requester">{{ $ticket->customer->name ?? '—' }}</td>
                        <td data-label="Priority"><x-status-badge :status="$ticket->priority" /></td>
                        <td data-label="Status"><x-status-badge :status="$ticket->status" /></td>
                        <td data-label="Created" class="text-slate-600 dark:text-term-800">{{ $ticket->created_at->diffForHumans() }}</td>
                        <td data-label="Action"><a href="{{ route('portal.tickets.show', $ticket) }}" class="term-btn term-btn-ghost term-btn-sm">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-slate-600 dark:text-term-800 py-8">No shared organisation tickets.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $tickets->links() }}</div>
    </div>
</div>
@endsection
