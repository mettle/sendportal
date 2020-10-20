<div class="card-header p-3 {{ $step['completed'] ? 'bg-success text-white' : ($active ? 'bg-light' : null) }}">
    <h6 class="mb-0">
        {{ $step['completed'] ? '✔' : ($active ? '➡️' : null) }} {{ $step['name'] }}
        <span class="text-small float-right"><em>{{ $iteration }}/{{ $total }}</em></span>
    </h6>
</div>
<div class="collapse {{ $active ? 'show' : null }}">
    <div class="card-body">
        @if ($step['completed'])
            <p>✔️ <code>.env</code> file already exists</p>
            <button class="btn btn-primary btn-md" wire:click="next">Next</button>
        @else
            <p>The .env file does not yet exist. Would you like to create it now?</p>
            <button class="btn btn-primary btn-md" wire:click="run" wire:loading.attribute="disabled">
                <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Run
            </button>
        @endif
    </div>
</div>
