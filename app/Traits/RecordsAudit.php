<?php
namespace App\Traits;

use App\Models\Audit;
use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;

trait RecordsAudit
{
    /**
     * Record an audit event.
     *
     * @param string $event
     * @param string $details
     * @return void
     */
    public function recordAudit($event, $details)
    {
        Auditoria::create([
            'creado_por' => Auth::id(),
            'evento' => $event,
            'detalles' => $details,
        ]);
    }
}
