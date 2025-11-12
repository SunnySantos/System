@extends('layouts.dashboard')

@section('title', 'Audit Trail')

@section('page_title', 'Audit Trail')

@section('content')

<div class="flex gap-4">
    <x-table-search />
</div>

<div class="overflow-x-auto rounded-box border border-base-content/5 bg-base-100 my-4">
    <table class="table" id="data_table">
        <!-- head -->
        <thead>
            <tr>
                <th>
                    <x-sortable-column column="created_at" label="Date & Time" />
                </th>
                <th>User</th>
                <th>Event</th>
                <th>Summary</th>
                <th>IP Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)

            <tr>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->user->name ?? '-' }}</td>
                <td>{{ $log->event ?? '-' }}</td>
                <td>{{ $log->message ?? '-' }}</td>
                <td>{{ $log->ip_address ?? '-' }}</td>
                <td>
                    @if (Auth::user()->canAccess('audit.show'))
                    <div class="tooltip tooltip-bottom" data-tip="View">
                        <a href="{{ route('audit.show', $log->id) }}" class="text-[#297AFF] view-modal-btn" role="button">
                            <x-lucide-eye />
                        </a>
                    </div>
                    @endif
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5" class="text-center">No logs</td>
            </tr>
            @endforelse
        </tbody>
        <!-- foot -->
        <tfoot>
            <tr>
                <th>
                    <x-sortable-column column="created_at" label="Date & Time" />
                </th>
                <th>User</th>
                <th>Event</th>
                <th>Summary</th>
                <th>IP Address</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>

{{ $logs->links() }}
@endsection