<?php

declare(strict_types=1);

namespace App\Traits;

trait HasSendportalCommandUtilities
{
    /**
     * Print awesomeness.
     */
    protected function intro(): void
    {
        $this->line('');
        $this->line(' ____                 _ ____            _        _ ');
        $this->line('/ ___|  ___ _ __   __| |  _ \ ___  _ __| |_ __ _| |');
        $this->line('\___ \ / _ \ \'_ \ / _` | |_) / _ \| \'__| __/ _` | |');
        $this->line(' ___) |  __/ | | | (_| |  __/ (_) | |  | || (_| | |');
        $this->line('|____/ \___|_| |_|\__,_|_|   \___/|_|   \__\__,_|_|');
    }
}
