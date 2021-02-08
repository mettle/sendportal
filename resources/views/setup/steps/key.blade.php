<div class="card-header p-3 {{ $step['completed'] ? 'bg-success text-white' : ($active ? 'bg-light' : null) }}">
    <h6 class="mb-0">
        {{ $step['completed'] ? '✔' : ($active ? '➡️' : null) }} {{ $step['name'] }}
        <span class="text-small float-right"><em>{{ $iteration }}/{{ $total }}</em></span>
    </h6>
</div>
<div class="collapse {{ $active ? 'show' : null }}">
    <div class="card-body">
        @if ($step['completed'])
            <p>✔️ The Application key has been set</p>
            <button class="btn btn-primary btn-md" wire:click="next">Next</button>
        @else
            <p>The Application key has not been set. Would you like to set it now?</p>
            <button class="btn btn-primary btn-md" wire:click="run">Run</button>
        @endif
    </div>
</div>
