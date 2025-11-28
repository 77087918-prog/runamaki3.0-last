<?php

namespace App\Exports;

use App\Models\Trueque;
use Illuminate\Contracts\View\View;

class TruequesExport
{
    protected $filtros;

    public function __construct($filtros = [])
    {
        $this->filtros = $filtros;
    }

    public function view(): View
    {
        $query = Trueque::query()->with(['solicitante', 'proveedor', 'habilidadSolicitada', 'habilidadOfrecida']);

        // Aplicar filtros
        if (!empty($this->filtros['estado'])) {
            $query->where('estado', $this->filtros['estado']);
        }

        if (!empty($this->filtros['desde'])) {
            $query->whereDate('created_at', '>=', $this->filtros['desde']);
        }

        if (!empty($this->filtros['hasta'])) {
            $query->whereDate('created_at', '<=', $this->filtros['hasta']);
        }

        $trueques = $query->orderBy('created_at', 'desc')->get();

        return view('admin.exports.trueques', [
            'trueques' => $trueques
        ]);
    }
}
