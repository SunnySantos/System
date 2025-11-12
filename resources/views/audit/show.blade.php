<dialog id="view_log_modal" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-[12px]">✕</button>
        </form>
        <h3 class="text-lg font-bold">Event Log</h3>
        <div class="log-content">
            <h4 class="mt-4 text-sm font-bold">Date & Time</h4>
            <p>{{ $audit->created_at }}</p>

            <h4 class="mt-4 text-sm font-bold">User</h4>
            <p>{{ $audit->user->name ?? '-' }}</p>

            <h4 class="mt-4 text-sm font-bold">Event</h4>
            <p>{{ ucwords($audit->event) ?? '-' }}</p>

            @if(!$audit->isLogin())

            @if($audit->hasChangedValues())
            <h4 class="mt-4 text-sm font-bold">Old values</h4>
            {!! $audit->getOldValuesAttribute() !!}
            @endif
            
            @if($audit->hasOldValues())
            <h4 class="mt-4 text-sm font-bold">Updated values</h4>
            {!! $audit->getChangedValuesAttribute() !!}
            @endif

            @if($audit->hasNewValues())
            <h4 class="mt-4 text-sm font-bold">New values</h4>
            {!! $audit->getNewValuesAttribute() !!}
            @endif
            @endif
        </div>
    </div>
</dialog>