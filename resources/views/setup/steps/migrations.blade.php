<div class="card-header p-3 {{ $step['completed'] ? 'bg-success text-white' : ($active ? 'bg-light' : null) }}">
    <h6 class="mb-0">
        {{ $step['completed'] ? '✔' : ($active ? '➡️' : null) }} {{ $step['name'] }}
        <span class="text-small float-right"><em>{{ $iteration }}/{{ $total }}</em></span>
    </h6>
</div>
<div class="collapse {{ $active ? 'show' : null }}">
    <div class="card-body">
        @if ($step['completed'])
            <p>✔️ Database migrations are up to date</p>
            <button class="btn btn-primary btn-md" wire:click="next">Next</button>
        @else
            <p>There are pending database migrations. Would you like to run migrations now?</p>
            <button class="btn btn-primary btn-md" wire:click="run" wire:loading.attr="disabled">
                <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Run Migrations
            </button>
        @endif
    </div>
</div>
